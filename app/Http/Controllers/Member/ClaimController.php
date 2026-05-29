<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        
        if ($membership && $membership->status === 'active') {
            return view('member.active.claims.index', compact('user', 'membership'));
        }
        
        return view('member.guest.claims.index');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member || !$member->membershipInfo) {
            return redirect()->route('member.claims.index')
                ->with('error', 'لا يوجد عضوية مسجلة.');
        }

        $validated = $request->validate([
            'claim_type'       => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Services\Claim::CLAIM_TYPES))],
            'has_minors'       => ['nullable', 'boolean'],
            'claim_documents'  => ['nullable', 'array'],
            'claim_documents.*'=> ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($validated['claim_type'] === 'death' && $request->input('has_minors') == 1) {
            $request->validate([
                'claim_documents.minors_birth_certificates' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
                'claim_documents.guardianship_decision'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            ], [
                'claim_documents.minors_birth_certificates.required' => 'شهادات ميلاد القصر مطلوبة.',
                'claim_documents.guardianship_decision.required' => 'قرار الوصاية مطلوب.',
            ]);
        }

        $claim = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated, $member, $user) {
            $claim = \App\Models\Services\Claim::create([
                'membership_id'      => $member->membershipInfo->id,
                'type'               => $validated['claim_type'],
                'amount'             => 0, // Final amount determined by admin
                'status'             => 'pending',
                'attachment_receipt'  => null,
            ]);

            if ($request->hasFile('claim_documents')) {
                foreach ($request->file('claim_documents') as $docType => $file) {
                    $path = $file->store("members/{$member->id}/claims/{$claim->id}", 'public');
                    \App\Models\Membership\Attachment::create([
                        'member_id' => $member->id,
                        'type'      => "claim_{$claim->id}_{$docType}",
                        'file_path' => $path,
                    ]);
                }
            }

            \App\Models\System\AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'create',
                'table_name' => 'claims',
                'record_id'  => $claim->id,
                'old_values' => null,
                'new_values' => $claim->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $claim;
        });

        return redirect()->route('member.claims.index')
            ->with('success', 'تم تقديم طلب المطالبة بنجاح.');
    }
}
