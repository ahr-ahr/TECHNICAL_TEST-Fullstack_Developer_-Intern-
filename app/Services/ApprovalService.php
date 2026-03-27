<?php

namespace App\Services;

use App\Models\{
    Approval,
    Booking,
    ActivityLog,
    User
};
use Illuminate\Support\Facades\DB;
use App\Notifications\{
    BookingRejectedNotification,
    BookingApprovedNotification,
    BookingNeedL2Notification
};

class ApprovalService
{
    public function approve(int $approvalId, int $userId): void
    {
        DB::transaction(function () use ($approvalId, $userId) {

            $approval = Approval::with('booking')->findOrFail($approvalId);

            if ($approval->approver_id !== $userId) {
                abort(403);
            }

            $approval->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'approve_level_' . $approval->level,
                'description' => 'Approval ID ' . $approval->id . ' approved',
            ]);

            if ($approval->level == 1) {

                $nextApproval = Approval::where('booking_id', $approval->booking_id)
                    ->where('level', 2)
                    ->first();

                if ($nextApproval) {
                    $approver = User::find($nextApproval->approver_id);
                    $approver->notify(new BookingNeedL2Notification($approval->booking));
                }
            }

            if ($approval->level == 2) {
                $approval->booking->update([
                    'status' => 'approved'
                ]);

                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => 'booking_approved',
                    'description' => 'Booking ID ' . $approval->booking_id . ' fully approved',
                ]);

                $admin = User::find($approval->booking->requested_by);
                $admin->notify(
                    new BookingApprovedNotification($approval->booking)
                );
            }
        });
    }

    public function reject(int $approvalId, int $userId): void
    {
        DB::transaction(function () use ($approvalId, $userId) {

            $approval = Approval::with('booking')->findOrFail($approvalId);

            if ($approval->approver_id !== $userId) {
                abort(403);
            }

            $approval->update([
                'status' => 'rejected',
                'approved_at' => now(),
            ]);

            $approval->booking->update([
                'status' => 'rejected'
            ]);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'reject_level_' . $approval->level,
                'description' => 'Approval ID ' . $approval->id . ' rejected',
            ]);

            $admin = User::find($approval->booking->requested_by);
            $admin->notify(new BookingRejectedNotification($approval->booking));
        });
    }
}
