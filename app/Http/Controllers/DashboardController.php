<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Booking::whereDate('start_date', Carbon::today())->count();

        $yesterday = Booking::whereDate('start_date', Carbon::yesterday())->count();

        $growth = $yesterday > 0
            ? round((($today - $yesterday) / $yesterday) * 100, 1)
            : 0;

        $weekly = Booking::whereBetween('start_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        $monthly = Booking::whereMonth('start_date', Carbon::now()->month)->count();

        $yearly = Booking::whereYear('start_date', Carbon::now()->year)->count();

        $usage = Booking::select(
            DB::raw('DATE(start_date) as date'),
            DB::raw('count(*) as total')
        )
            ->whereIn('status', ['in_use', 'completed'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $perVehicle = Booking::select(
            'vehicle_id',
            DB::raw('count(*) as total')
        )
            ->with('vehicle')
            ->groupBy('vehicle_id')
            ->get();

        $perDriver = Booking::select(
            'driver_id',
            DB::raw('count(*) as total')
        )
            ->with('driver')
            ->groupBy('driver_id')
            ->get();

        $status = Booking::select(
            'status',
            DB::raw('count(*) as total')
        )->groupBy('status')->get();

        $approval = DB::table('approvals')
            ->select('level', DB::raw('count(*) as total'))
            ->groupBy('level')
            ->get();

        // =======================
// UTILIZATION (%)
// =======================
        $totalDays = 30;

        $utilization = DB::table('bookings')
            ->select(
                'vehicle_id',
                DB::raw('SUM(DATEDIFF(end_date, start_date)+1) as used_days')
            )
            ->whereBetween('start_date', [now()->subDays($totalDays), now()])
            ->groupBy('vehicle_id')
            ->get()
            ->map(function ($item) use ($totalDays) {
                $item->percentage = round(($item->used_days / $totalDays) * 100);
                return $item;
            });

        // =======================
// TOP VEHICLE (KM)
// =======================
        $topVehicle = DB::table('vehicle_usages')
            ->join('bookings', 'vehicle_usages.booking_id', '=', 'bookings.id')
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->select(
                'vehicles.plate_number',
                DB::raw('SUM(end_km - start_km) as total_km')
            )
            ->groupBy('vehicles.plate_number')
            ->orderByDesc('total_km')
            ->limit(5)
            ->get();

        $fuelCost = DB::table('fuel_logs')
            ->select(
                'vehicle_id',
                DB::raw('SUM(cost) as total_cost')
            )
            ->groupBy('vehicle_id')
            ->get();

        $serviceCost = DB::table('service_logs')
            ->select(
                'vehicle_id',
                DB::raw('SUM(cost) as total_cost')
            )
            ->groupBy('vehicle_id')
            ->get();

        $driverKm = DB::table('vehicle_usages')
            ->join('bookings', 'vehicle_usages.booking_id', '=', 'bookings.id')
            ->join('drivers', 'bookings.driver_id', '=', 'drivers.id')
            ->select(
                'drivers.name',
                DB::raw('SUM(end_km - start_km) as total_km')
            )
            ->groupBy('drivers.name')
            ->orderByDesc('total_km')
            ->get();

        $maintenance = DB::table('service_logs')
            ->join('vehicles', 'service_logs.vehicle_id', '=', 'vehicles.id')
            ->select(
                'vehicles.plate_number',
                DB::raw("
            COUNT(
                CASE
                    WHEN service_logs.description IS NOT NULL
                    AND service_logs.cost IS NOT NULL
                    THEN 1
                END
            ) as total_service
        "),
                DB::raw('MAX(service_date) as last_service')
            )
            ->groupBy('vehicles.plate_number')
            ->orderByDesc('total_service')
            ->get();

        $approvalTime = DB::table('approvals')
            ->select(
                'level',
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg_hours')
            )
            ->whereNotNull('approved_at')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        return view('dashboard', [
            'user' => auth()->user(),

            'today' => $today,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'yearly' => $yearly,
            'growth' => $growth,

            'usageLabels' => $usage->pluck('date'),
            'usageData' => $usage->pluck('total'),

            'vehicleLabels' => $perVehicle->pluck('vehicle.plate_number'),
            'vehicleData' => $perVehicle->pluck('total'),

            'driverLabels' => $perDriver->pluck('driver.name'),
            'driverData' => $perDriver->pluck('total'),

            'statusLabels' => $status->pluck('status'),
            'statusData' => $status->pluck('total'),

            'approvalLabels' => $approval->pluck('level'),
            'approvalData' => $approval->pluck('total'),

            'utilization' => $utilization,

            'topVehicle' => $topVehicle,

            'driverKmLabels' => $driverKm->pluck('name'),
            'driverKmData' => $driverKm->pluck('total_km'),

            'maintenance' => $maintenance,
            'maintenanceLabels' => $maintenance->pluck('plate_number'),
            'maintenanceData' => $maintenance->pluck('total_service'),
            'maintenanceLast' => $maintenance->pluck('last_service'),

            'approvalTimeLabels' => $approvalTime->pluck('level'),
            'approvalTimeData' => $approvalTime->pluck('avg_hours'),
        ]);
    }
}
