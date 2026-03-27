<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Requests\VehicleRequest;
use App\Models\ActivityLog;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('plate_number', 'like', "%{$request->search}%")
                    ->orWhere('type', 'like', "%{$request->search}%")
                    ->orWhere('ownership', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $sort = $request->sort ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $vehicles = $query->paginate(10)->withQueryString();

        $groupedVehicles = $vehicles->getCollection()->groupBy('status');

        return view('vehicle.index', compact('vehicles', 'groupedVehicles'));
    }

    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_vehicle',
            'description' => 'Create vehicle ID: ' . $vehicle->id,
        ]);

        return back()->with('success', 'Berhasil tambah');
    }

    public function update(VehicleRequest $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->validated());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_vehicle',
            'description' => 'Update vehicle ID: ' . $vehicle->id,
        ]);

        return back()->with('success', 'Berhasil update');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicleId = $vehicle->id;

        $vehicle->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_vehicle',
            'description' => 'Delete vehicle ID: ' . $vehicleId,
        ]);

        return back()->with('success', 'Berhasil hapus');
    }
}
