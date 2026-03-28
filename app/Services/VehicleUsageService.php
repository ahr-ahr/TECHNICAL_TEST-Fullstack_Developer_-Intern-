<?php

namespace App\Services;

use App\Models\{
    Booking,
    VehicleUsage,
    FuelLog,
    ServiceLog,
    ActivityLog,
    Vehicle
};
use Illuminate\Support\Facades\DB;

class VehicleUsageService
{
    public function startUsage(int $bookingId, int $userId)
    {
        DB::transaction(function () use ($bookingId, $userId) {

            $booking = Booking::findOrFail($bookingId);

            if ($booking->status !== 'approved') {
                abort(400, 'Booking must be approved');
            }

            $booking->update([
                'status' => 'in_use'
            ]);

            Vehicle::where('id', $booking->vehicle_id)
                ->update(['status' => 'in_use']);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'start_usage',
                'description' => 'Booking ID ' . $bookingId . ' started',
            ]);
        });
    }

    public function completeUsage(int $bookingId, array $data, int $userId)
    {
        DB::transaction(function () use ($bookingId, $data, $userId) {

            $booking = Booking::findOrFail($bookingId);

            if ($booking->status !== 'in_use') {
                abort(400, 'Booking must be in use');
            }

            VehicleUsage::create([
                'booking_id' => $bookingId,
                'start_km' => $data['start_km'],
                'end_km' => $data['end_km'],
            ]);

            FuelLog::create([
                'vehicle_id' => $booking->vehicle_id,
                'booking_id' => $bookingId,
                'date' => now(),
                'liters' => $data['liters'],
                'cost' => $data['cost'],
            ]);

            ServiceLog::create([
                'booking_id' => $bookingId,
                'vehicle_id' => $booking->vehicle_id,
                'service_date' => now(),
                'description' => $data['service_description'] ?? null,
                'cost' => $data['service_cost'] ?? null,
            ]);

            $booking->update([
                'status' => 'completed'
            ]);

            Vehicle::where('id', $booking->vehicle_id)
                ->update(['status' => 'available']);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'complete_usage',
                'description' => 'Booking ID ' . $bookingId . ' completed',
            ]);
        });
    }
}
