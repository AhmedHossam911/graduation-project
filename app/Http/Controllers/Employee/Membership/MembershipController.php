<?php

namespace App\Http\Controllers\Employee\Membership;

use App\Http\Controllers\Controller;
use App\Models\Services\Membership;
use App\Models\Membership\Member;
use App\Models\System\AuditLog;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * These are the valid membership statuses as defined by our database migration enum.
     */
    private const ALLOWED_STATUSES = ['active', 'pending_registration', 'loaned', 'pension_eligible', 'withdrawn', 'dismissed', 'unpaid_leave', 'membership_expired', 'suspended'];

    /**
     * Approve a pending membership request by updating its status to 'active' and recording the user who approved it.
     */
    public function approve(Request $request, $id)
    {
        $membership = Membership::with('member')->findOrFail($id);

        $oldStatus = $membership->status;

        $membership->update([
            'status'      => 'active',
            'approved_by' => auth()->id(),
        ]);

        $this->logAudit('approve', 'memberships', $membership->id, [
            'status'      => $oldStatus,
            'approved_by' => null,
        ], [
            'status'      => 'active',
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('members.show', $membership->member_id)
            ->with('success', 'تم اعتماد العضوية بنجاح.');
    }

    /**
     * Reject a pending membership request and mark its status as 'inactive'.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $membership = Membership::with('member')->findOrFail($id);

        $oldStatus = $membership->status;

        $membership->update([
            'status' => 'inactive',
        ]);

        $this->logAudit('reject', 'memberships', $membership->id, [
            'status' => $oldStatus,
        ], [
            'status' => 'inactive',
            'reason' => $request->reason,
        ]);

        return redirect()
            ->route('members.show', $membership->member_id)
            ->with('success', 'تم رفض العضوية.');
    }

    /**
     * Handle any general status transition for a membership based on the provided status input.
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $membership = Membership::with('member')->findOrFail($id);

        $oldStatus = $membership->status;

        $membership->update([
            'status' => $request->status,
        ]);

        $this->logAudit('change_status', 'memberships', $membership->id, [
            'status' => $oldStatus,
        ], [
            'status' => $request->status,
            'reason' => $request->reason,
        ]);

        $statusLabels = MemberController::STATUS_MAP;
        $newLabel = $statusLabels[$request->status]['label'] ?? $request->status;

        return redirect()
            ->route('members.show', $membership->member_id)
            ->with('success', "تم تغيير حالة العضوية إلى: {$newLabel}");
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    /**
     * Create an audit log entry.
     */
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
