<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovalLevelTwoTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_two_approval_completes_booking()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approver1 = User::factory()->create(['role' => 'approver']);
        $approver2 = User::factory()->create(['role' => 'approver']);

        $vehicle = Vehicle::factory()->create();
        $driver = Driver::factory()->create();

        $this->actingAs($admin);

        $this->withoutExceptionHandling();

        $this->post('/bookings', [
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'purpose' => 'Test L2 approval',
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

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('approvals', [
            'id' => $approvalL2->id,
            'status' => 'approved',
        ]);
    }
}
