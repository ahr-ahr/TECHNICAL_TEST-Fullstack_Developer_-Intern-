<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Approval::with([
            'booking.vehicle',
            'booking.driver',
            'booking.requester'
        ])->where('approver_id', $userId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('booking', function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', function ($q2) use ($search) {
                        $q2->where('plate_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('driver', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->whereDate('start_date', $request->date);
            });
        }

        $sort = $request->sort ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $approvals = $query->paginate(10)->withQueryString();

        return view('history.index', compact('approvals'));
    }
}
