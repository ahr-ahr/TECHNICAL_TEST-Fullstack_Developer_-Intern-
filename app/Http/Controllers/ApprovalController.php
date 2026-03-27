<?php

namespace App\Http\Controllers;

use App\Services\ApprovalService;
use App\Models\Booking;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    protected $service;

    public function __construct(ApprovalService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Booking::with([
            'vehicle',
            'driver',
            'approvals.approver'
        ]);

        if ($user->role === 'approver') {

            $query->whereHas('approvals', function ($q) use ($user) {
                $q->where('approver_id', $user->id);
            });

            $query->where(function ($q) use ($user) {
                $q->whereHas('approvals', function ($q2) use ($user) {
                    $q2->where('approver_id', $user->id)
                        ->where('level', 1);
                })->orWhere(function ($q3) use ($user) {
                    $q3->whereHas('approvals', function ($q4) use ($user) {
                        $q4->where('approver_id', $user->id)
                            ->where('level', 2);
                    })
                        ->whereHas('approvals', function ($q5) {
                            $q5->where('level', 1)
                                ->where('status', 'approved');
                        });
                });
            });
        }

        if ($request->status) {

            if ($request->status === 'pending') {
                $query->where('status', 'pending');

            } elseif ($request->status === 'approved') {
                $query->where('status', 'approved');

            } elseif ($request->status === 'rejected') {
                $query->where('status', 'rejected');

            } elseif ($request->status === 'waiting_level_2') {
                $query->where('status', 'pending')
                    ->whereHas('approvals', function ($q) {
                        $q->where('level', 1)->where('status', 'approved');
                    })
                    ->whereHas('approvals', function ($q) {
                        $q->where('level', 2)->where('status', 'pending');
                    });
            }
        }

        $request->sort === 'oldest' ? $query->oldest() : $query->latest();

        $approvals = $query->paginate(10)->withQueryString();

        return view('approval.index', compact('approvals'));
    }

    public function approve($id)
    {
        $this->service->approve($id, auth()->id());

        return back()->with('success', 'Approved');
    }

    public function reject($id)
    {
        $this->service->reject($id, auth()->id());

        return back()->with('success', 'Rejected');
    }
}
