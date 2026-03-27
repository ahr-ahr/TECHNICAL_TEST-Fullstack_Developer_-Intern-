<?php

namespace App\Http\Controllers;

use App\Http\Requests\{BookingStoreRequest, BookingUpdateRequest};
use Illuminate\Http\Request;
use App\Services\BookingService;
use App\Models\{
    Booking,
    Vehicle,
    Driver,
    User,
    Approval,
    ActivityLog
};
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['vehicle', 'driver']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('purpose', 'like', "%{$request->search}%")
                    ->orWhereHas('vehicle', function ($q2) use ($request) {
                        $q2->where('plate_number', 'like', "%{$request->search}%");
                    })
                    ->orWhereHas('driver', function ($q2) use ($request) {
                        $q2->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('start_date', $request->date);
        }

        $sort = $request->sort ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $bookings = $query->paginate(10)->withQueryString();

        $groupedBookings = $bookings->getCollection()->groupBy(function ($b) {
            return \Carbon\Carbon::parse($b->start_date)->format('Y-m-d');
        });

        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = Driver::all();
        $approvers = User::where('role', 'approver')->get();

        return view('booking.index', compact(
            'bookings',
            'groupedBookings',
            'vehicles',
            'drivers',
            'approvers'
        ));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'vehicle',
            'driver',
            'requester',
            'approvals.approver',
            'usage',
            'fuelLogs',
            'serviceLogs'
        ])->findOrFail($id);

        return response()->json($booking);
    }

    public function store(BookingStoreRequest $request)
    {
        $this->bookingService->createBooking(
            $request->validated(),
            auth()->id()
        );

        return redirect()->route('booking')
            ->with('success', 'Booking created');
    }

    public function update(BookingUpdateRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->isPending()) {
            return back()->withErrors([
                'error' => 'Booking tidak bisa diubah karena sudah diproses'
            ]);
        }

        DB::transaction(function () use ($booking, $request) {


            $booking->update([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'purpose' => $request->purpose,
            ]);

            $booking->approvals()->delete();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_booking',
                'description' => 'Update booking ID: ' . $booking->id,
            ]);

        });

        return back()->with('success', 'Booking berhasil diupdate');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->isPending()) {
            return back()->withErrors([
                'error' => 'Booking tidak bisa dihapus'
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_booking',
            'description' => 'Delete booking ID: ' . $booking->id,
        ]);

        $booking->approvals()->delete();
        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus');
    }
}
