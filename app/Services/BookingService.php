<?php

namespace App\Services;

use App\Models\{
    Booking,
    Approval,
    ActivityLog,
    User
};
use App\Notifications\BookingCreatedNotification;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function createBooking(array $data, int $userId): Booking
    {
        return DB::transaction(function () use ($data, $userId) {

            $booking = Booking::create([
                'vehicle_id' => $data['vehicle_id'],
                'driver_id' => $data['driver_id'],
                'requested_by' => $userId,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'purpose' => $data['purpose'],
                'status' => 'pending',
            ]);

            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $data['approver_1'],
                'level' => 1,
            ]);

            $approver = User::find($data['approver_1']);
            $approver?->notify(new BookingCreatedNotification($booking));

            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $data['approver_2'],
                'level' => 2,
            ]);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'create_booking',
                'description' => 'Booking created ID: ' . $booking->id,
            ]);

            return $booking;
        });
    }
}

?>