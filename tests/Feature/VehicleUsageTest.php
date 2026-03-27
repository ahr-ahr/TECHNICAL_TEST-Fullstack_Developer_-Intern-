<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehicleUsageTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_usage_flow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approver1 = User::factory()->create(['role' => 'approver']);
        $approver2 = User::factory()->create(['role' => 'approver']);

        $vehicle = Vehicle::factory()->create();
        $driver = Driver::factory()->create();

        $this->actingAs($admin);
        $this->post('/bookings', [
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'purpose' => 'Test usage flow',
            'approver_1' => $approver1->id,
            'approver_2' => $approver2->id,
        ]);

        $booking = Booking::first();

        $approvalL1 = Approval::where('booking_id', $booking->id)->where('level', 1)->first();
        $approvalL2 = Approval::where('booking_id', $booking->id)->where('level', 2)->first();

        $this->actingAs($approver1);
        $this->post("/approvals/{$approvalL1->id}/approve");

        $this->actingAs($approver2);
        $this->post("/approvals/{$approvalL2->id}/approve");

        $this->actingAs($admin);
        $this->post("/usage/{$booking->id}/start");

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'in_use',
        ]);

        $this->post("/usage/{$booking->id}/complete", [
            'start_km' => 100,
            'end_km' => 150,
            'liters' => 20,
            'cost' => 200000,
            'service_description' => 'Routine check',
            'service_cost' => 50000,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('vehicle_usages', [
            'booking_id' => $booking->id,
        ]);

        $this->assertDatabaseHas('fuel_logs', [
            'booking_id' => $booking->id,
        ]);

        $this->assertDatabaseHas('service_logs', [
            'vehicle_id' => $booking->vehicle_id,
        ]);
    }
}
