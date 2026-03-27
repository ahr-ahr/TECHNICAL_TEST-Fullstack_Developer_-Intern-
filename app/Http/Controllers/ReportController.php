<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Booking,
    Vehicle,
    Driver
};
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BookingExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with([
            'vehicle',
            'driver',
            'requester',
            'approvals.approver'
        ])->whereIn('status', ['in_use', 'completed']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->driver_id) {
            $query->where('driver_id', $request->driver_id);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('report.index', compact('reports', 'vehicles', 'drivers'));
    }

    public function exportExcel(Request $request)
    {
        $query = Booking::with([
            'vehicle',
            'driver',
            'requester',
            'approvals.approver'
        ])->whereIn('status', ['in_use', 'completed']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->driver_id) {
            $query->where('driver_id', $request->driver_id);
        }

        $data = $query->get();

        if ($request->start_date && $request->end_date) {
            $filename = 'laporan-booking-' .
                $request->start_date . '_to_' .
                $request->end_date . '.xlsx';
        } else {
            $filename = 'laporan-booking-all-data.xlsx';
        }

        return Excel::download(
            new BookingExport($data, $request->start_date, $request->end_date),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $query = Booking::with([
            'vehicle',
            'driver',
            'requester',
            'approvals.approver'
        ])->whereIn('status', ['in_use', 'completed']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->driver_id) {
            $query->where('driver_id', $request->driver_id);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('report.pdf', [
            'data' => $data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->start_date && $request->end_date) {
            $filename = 'laporan-booking-' .
                $request->start_date . '_to_' .
                $request->end_date . '.pdf';
        } else {
            $filename = 'laporan-booking-all-data.pdf';
        }

        return $pdf->download($filename);
    }
}
