<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VehicleUsageService;
use App\Http\Requests\CompleteUsageRequest;

class VehicleUsageController extends Controller
{
    protected $service;

    public function __construct(VehicleUsageService $service)
    {
        $this->service = $service;
    }

    public function start($bookingId)
    {
        $this->service->startUsage($bookingId, auth()->id());

        return back()->with('success', 'Usage started');
    }

    public function complete(CompleteUsageRequest $request, $bookingId)
    {
        $this->service->completeUsage(
            $bookingId,
            $request->validated(),
            auth()->id()
        );

        return back()->with('success', 'Usage completed');
    }
}
