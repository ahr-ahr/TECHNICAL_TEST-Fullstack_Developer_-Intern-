<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_flow_creates_booking_approvals_and_logs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approver = User::factory()->create(['role' => 'approver']);

        $vehicle = Vehicle::factory()->create();
        $driver = Driver::factory()->create();

        $this->actingAs($admin, 'web');

        $this->withoutExceptionHandling();

        $this->post('/bookings', [
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'purpose' => 'Test booking flow',
            'approver_1' => $approver->id,
            'approver_2' => $approver->id,
        ]);

        $this->assertDatabaseHas('bookings', [
            'purpose' => 'Test booking flow'
        ]);

        $this->assertDatabaseHas('approvals', [
            'level' => 1
        ]);

        $this->assertDatabaseHas('approvals', [
            'level' => 2
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create_booking'
        ]);
    }
}
