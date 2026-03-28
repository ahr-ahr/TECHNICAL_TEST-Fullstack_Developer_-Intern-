<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class EndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_booking_to_usage_flow()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $approver1 = User::factory()->create(['role' => 'approver']);
        $approver2 = User::factory()->create(['role' => 'approver']);

        $vehicle = Vehicle::factory()->create(['status' => 'available']);
        $driver = Driver::factory()->create();

        $this->actingAs($admin);

        $response = $this->post('/booking', [
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'purpose' => 'E2E Test Booking',
            'approver_1' => $approver1->id,
            'approver_2' => $approver2->id,
        ]);

        $response->assertStatus(302);

        $booking = Booking::first();

        $this->assertNotNull($booking);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create_booking',
        ]);

        $approvalL1 = Approval::where('booking_id', $booking->id)
            ->where('level', 1)
            ->first();

        $this->actingAs($approver1);

        $this->post("/approvals/{$approvalL1->id}/approve")
            ->assertStatus(302);

        $this->assertDatabaseHas('approvals', [
            'id' => $approvalL1->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'approve_level_1',
        ]);

        $approvalL2 = Approval::where('booking_id', $booking->id)
            ->where('level', 2)
            ->first();

        $this->actingAs($approver2);

        $this->post("/approvals/{$approvalL2->id}/approve")
            ->assertStatus(302);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'approve_level_2',
        ]);

        $this->actingAs($admin);

        $this->post("/usage/{$booking->id}/start")
            ->assertStatus(302);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'in_use',
        ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'status' => 'in_use',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'start_usage',
        ]);

        $this->post("/usage/{$booking->id}/complete", [
            'start_km' => 100,
            'end_km' => 150,
            'liters' => 20,
            'cost' => 200000,
            'service_description' => 'Routine check',
            'service_cost' => 50000,
        ])->assertStatus(302);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'status' => 'available',
        ]);

        $this->assertDatabaseHas('vehicle_usages', [
            'booking_id' => $booking->id,
            'start_km' => 100,
            'end_km' => 150,
        ]);

        $this->assertDatabaseHas('fuel_logs', [
            'booking_id' => $booking->id,
            'liters' => 20,
        ]);

        $this->assertDatabaseHas('service_logs', [
            'booking_id' => $booking->id,
            'vehicle_id' => $booking->vehicle_id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'complete_usage',
        ]);
    }
}
