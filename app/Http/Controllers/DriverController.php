<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Requests\DriverRequest;
use App\Models\ActivityLog;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $sort = $request->sort ?? 'latest';

        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            $query->latest();
        }

        $drivers = $query->paginate(10)->withQueryString();

        return view('driver.index', compact('drivers'));
    }

    public function store(DriverRequest $request)
    {
        $driver = Driver::create($request->validated());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_vehicle',
            'description' => 'Create vehicle ID: ' . $driver->id,
        ]);

        return back()->with('success', 'Driver berhasil ditambahkan');
    }

    public function update(DriverRequest $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $driver->update($request->validated());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_vehicle',
            'description' => 'Update vehicle ID: ' . $driver->id,
        ]);

        return back()->with('success', 'Driver berhasil diupdate');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);

        $driverId = $driver->id;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_vehicle',
            'description' => 'Delete vehicle ID: ' . $driverId,
        ]);

        return back()->with('success', 'Driver berhasil dihapus');
    }
}
