<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Attachment;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use App\Models\System\Department;
use App\Models\System\AuditLog;
use App\Models\System\SystemSetting;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberAccountCreatedMail;
use Carbon\Carbon;
use Exception;

class MemberService
{
    /**
     * List of mandatory documents that must be submitted during the initial membership registration.
     */
    public const INITIAL_DOCUMENT_TYPES = [
        'national_id_card'    => 'بطاقة الرقم القومي',
        'basic_salary_letter' => 'خطاب الأجر الأساسي',
        'work_declaration'    => 'إقرار القيام بالعمل',
        'over_21_request'     => 'طلب تجاوز فوق سن ٢١ عام',
        'appointment_decision'=> 'قرار التعيين',
        'manual_request'      => 'طلب يدوي بالتسجيل من خلال المكتب',
    ];

    /**
     * Handle the full creation process of a new member, including user account, membership, and initial fees.
     * Throws an Exception if the applicant's age does not meet the specified requirements.
     */
    public function createMember(array $validated, Request $request, string $nationalId): array
    {
        // Validate the applicant's age against the system's minimum and maximum allowed limits.
        $birthDate = $this->dateFromParts($request, 'birth');
        $age = 0;
        if ($birthDate) {
            $age = Carbon::parse($birthDate)->age;
            $minAge = (int) SystemSetting::get('membership_min_age', 21);
            $maxAge = (int) SystemSetting::get('membership_max_age', 59);

            if ($age < $minAge || $age > $maxAge) {
                throw new Exception("عمر العضو ($age عامًا) غير مطابق للوائح. السن المسموح به من $minAge إلى $maxAge عامًا.");
            }
        }

        return DB::transaction(function () use ($request, $validated, $nationalId, $age) {
            $departmentId = $this->resolveDepartmentId($validated);

            // Automatically generate a corresponding user account so the member can log into the system.
            $memberRole = Role::where('name', 'Member')->first();
            $user = User::create([
                'name'     => $validated['full_name'],
                'email'    => $validated['email'],
                'password' => Hash::make($nationalId),
                'role_id'  => $memberRole ? $memberRole->id : null,
            ]);

            $member = Member::create([
                'user_id'        => $user->id,
                'department_id'  => $departmentId,
                'full_name'      => $validated['full_name'],
                'national_id'    => $nationalId,
                'birth_date'     => $this->dateFromParts($request, 'birth'),
                'phone'          => $this->digitsToString($request, 'phone_digits'),
                'landline'       => $this->digitsToString($request, 'landline_digits'),
                'address'        => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            $membership = Membership::create([
                'member_id'            => $member->id,
                'membership_number'    => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'status'               => 'pending_registration',
                'declaration_accepted' => true,
                'approved_by'          => null,
            ]);

            // Determine the total joining fee by calculating how many years remain until their expected retirement.
            $totalFee = $this->calculateFees($age, (float)($validated['salary'] ?? 0));

            Subscription::create([
                'membership_id' => $membership->id,
                'name'          => 'رسم الاشتراك بالصندوق',
                'amount'        => $totalFee,
                'due_date'      => now(),
                'status'        => 'unpaid',
            ]);

            EmploymentInfo::create([
                'member_id'          => $member->id,
                'workplace'          => $validated['employer_name'],
                'job_title'          => $validated['job_title'],
                'financial_category' => $validated['financial_category'],
                'join_date'          => $this->dateFromParts($request, 'hire'),
                'retirement_date'    => $this->dateFromParts($request, 'retirement'),
                'starting_salary'    => $validated['salary'] ?? null,
            ]);

            $familyData = ['member_id' => $member->id];
            if (isset($validated['children_count'])) $familyData['children_count'] = $validated['children_count'];
            if ($this->nullIfPlaceholder($validated['spouse_name'] ?? null)) $familyData['spouse_name'] = $validated['spouse_name'];
            if ($this->digitsToString($request, 'spouse_phone_digits')) $familyData['spouse_phone'] = $this->digitsToString($request, 'spouse_phone_digits');
            if ($this->nullIfPlaceholder($validated['spouse_workplace'] ?? null)) $familyData['spouse_workplace'] = $validated['spouse_workplace'];
            if ($this->nullIfPlaceholder($validated['child_name'] ?? null)) $familyData['child_name'] = $validated['child_name'];
            if ($this->nullIfPlaceholder($validated['child_workplace'] ?? null)) $familyData['child_workplace'] = $validated['child_workplace'];

            FamilyInfo::create($familyData);

            // Securely store all the initial mandatory documents provided during registration.
            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if ($request->hasFile("documents.$type")) {
                    $this->storeDocument($member, $request->file("documents.$type"), $type);
                }
            }

            // Audit log
            $this->logAudit('create', 'members', $member->id, null, $member->toArray());

            // Dispatch a welcome email to the newly registered member, if an email address was provided.
            if (!empty($validated['email'])) {
                Mail::to($validated['email'])->send(new MemberAccountCreatedMail($member, $nationalId));
            }

            return [
                'member' => $member,
                'membership' => $membership,
                'totalFee' => $totalFee
            ];
        });
    }

    /**
     * Process updates to an existing member's personal, employment, and family details.
     */
    public function updateMember(Member $member, array $validated, Request $request, string $nationalId): void
    {
        DB::transaction(function () use ($request, $validated, $nationalId, $member) {
            $oldValues = $member->toArray();

            $departmentId = $this->resolveDepartmentId($validated);

            $member->update([
                'department_id'  => $departmentId,
                'full_name'      => $validated['full_name'],
                'national_id'    => $nationalId,
                'birth_date'     => $this->dateFromParts($request, 'birth'),
                'phone'          => $this->digitsToString($request, 'phone_digits'),
                'landline'       => $this->digitsToString($request, 'landline_digits'),
                'address'        => $validated['address'] ?? null,
                'marital_status' => $validated['marital_status'],
            ]);

            // Keep the member's employment record up to date, or create a new one if it somehow doesn't exist.
            $member->employmentInfo()->updateOrCreate(
                ['member_id' => $member->id],
                [
                    'workplace'          => $validated['employer_name'],
                    'job_title'          => $validated['job_title'],
                    'financial_category' => $validated['financial_category'],
                    'join_date'          => $this->dateFromParts($request, 'hire'),
                    'retirement_date'    => $this->dateFromParts($request, 'retirement'),
                    'starting_salary'    => $validated['salary'] ?? null,
                ]
            );

            // Similarly, update or initialize their family information record.
            $member->familyInfo()->updateOrCreate(
                ['member_id' => $member->id],
                [
                    'children_count'   => $validated['children_count'] ?? 0,
                    'spouse_name'      => $this->nullIfPlaceholder($validated['spouse_name'] ?? null) ?? 'لا يوجد',
                    'spouse_phone'     => $this->digitsToString($request, 'spouse_phone_digits') ?? 'لا يوجد',
                    'spouse_workplace' => $this->nullIfPlaceholder($validated['spouse_workplace'] ?? null) ?? 'لا يوجد',
                    'child_name'       => $this->nullIfPlaceholder($validated['child_name'] ?? null) ?? 'لا يوجد',
                    'child_workplace'  => $this->nullIfPlaceholder($validated['child_workplace'] ?? null) ?? 'لا يوجد',
                ]
            );

            // If any new files were uploaded during this update, replace the old attachments of the same type.
            foreach (self::INITIAL_DOCUMENT_TYPES as $type => $label) {
                if ($request->hasFile("documents.$type")) {
                    Attachment::where('member_id', $member->id)->where('type', $type)->delete();
                    $this->storeDocument($member, $request->file("documents.$type"), $type);
                }
            }

            // Audit log
            $this->logAudit('update', 'members', $member->id, $oldValues, $member->fresh()->toArray());
        });
    }

    /**
     * Permanently remove a member from the system, tracking the deletion in the audit logs.
     */
    public function deleteMember(Member $member): void
    {
        $this->logAudit('delete', 'members', $member->id, $member->toArray(), null);
        $member->delete();
    }

    public function suspendMember(Member $member, string $reason, $suspensionFile = null): void
    {
        $oldStatus = $member->membershipInfo->status ?? null;

        if ($member->membershipInfo) {
            $member->membershipInfo->update(['status' => 'suspended']);
        }

        if ($suspensionFile) {
            $this->storeDocument($member, $suspensionFile, 'suspension_document');
        }

        $this->logAudit('suspend', 'memberships', $member->membershipInfo->id ?? $member->id, [
            'status' => $oldStatus,
            'reason' => null,
        ], [
            'status' => 'suspended',
            'reason' => $reason,
        ]);
    }
    
    public function uploadSignedForm(Member $member, $file): void
    {
        $this->storeDocument($member, $file, 'signed_membership_form');

        if ($member->membershipInfo) {
            Subscription::create([
                'membership_id' => $member->membershipInfo->id,
                'amount'        => 0, // Default fee, to be adjusted later
                'due_date'      => now(),
                'status'        => 'pending',
            ]);
        }

        $this->logAudit('upload_signed_form', 'attachments', $member->id, null, [
            'type' => 'signed_membership_form',
        ]);
    }
    
    public function storeAdditionalDocument(Member $member, $file, string $documentName): void
    {
        $this->storeDocument($member, $file, $documentName);

        $this->logAudit('upload_document', 'attachments', $member->id, null, [
            'type' => $documentName,
        ]);
    }

    private function calculateFees(int $age, float $basicSalary): float
    {
        $retirementAge = (int) SystemSetting::get('retirement_age', 60);
        $remainingYears = max(0, $retirementAge - $age);

        $feesSettings = json_decode(SystemSetting::get('membership_join_fee', '[]'), true);
        $feeMonths = 0;

        if (is_array($feesSettings) && count($feesSettings) > 0) {
            $settingsData = [];
            $maxSettingYear = 0;

            foreach ($feesSettings as $setting) {
                $settingYearsStr = preg_replace('/[^0-9]/', '', $setting['years'] ?? '');
                if (is_numeric($settingYearsStr)) {
                    $sy = (int) $settingYearsStr;
                    $settingsData[$sy] = (float) ($setting['fee_months'] ?? 0);
                    if ($sy > $maxSettingYear) {
                        $maxSettingYear = $sy;
                    }
                }
            }

            if ($remainingYears > $maxSettingYear && $maxSettingYear > 0) {
                $remainingYears = $maxSettingYear;
            }

            if (isset($settingsData[$remainingYears])) {
                $feeMonths = $settingsData[$remainingYears];
            }
        }

        return $feeMonths * $basicSalary;
    }

    private function resolveDepartmentId(array $validated): int
    {
        if (!empty($validated['department_id'])) {
            return $validated['department_id'];
        }
        return Department::firstOrCreate(['name' => 'Pending Registration'])->id;
    }

    private function dateFromParts(Request $request, string $prefix): ?string
    {
        $day   = $request->input("{$prefix}_day");
        $month = $request->input("{$prefix}_month");
        $year  = $request->input("{$prefix}_year");

        if (!$day || !$month || !$year || !checkdate((int) $month, (int) $day, (int) $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    private function digitsToString(Request $request, string $field): ?string
    {
        if (!$request->filled($field)) {
            return null;
        }

        $digits = array_filter($request->input($field, []), 'strlen');
        return !empty($digits) ? implode('', $digits) : null;
    }

    private function nullIfPlaceholder(?string $value): ?string
    {
        if ($value === null || $value === 'لا يوجد' || trim($value) === '') {
            return null;
        }
        return $value;
    }

    private function storeDocument(Member $member, $file, string $type): void
    {
        $path = $file->store("members/{$member->id}", 'public');

        Attachment::create([
            'member_id' => $member->id,
            'type'      => $type,
            'file_path' => $path,
        ]);
    }

    private function logAudit(string $action, string $tableName, int $recordId, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }
}
