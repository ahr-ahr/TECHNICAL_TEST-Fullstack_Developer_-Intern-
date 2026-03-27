<?php

use App\Http\Controllers\{
    ActivityLogController,
    ProfileController,
    BookingController,
    ApprovalController,
    DashboardController,
    VehicleUsageController,
    VehicleController,
    DriverController,
    HistoryController,
    ReportController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin,approver'])->group(function () {
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notification/{id}', function ($id) {
        $notif = auth()->user()->notifications()->findOrFail($id);

        $notif->markAsRead();

        return redirect($notif->data['url'] ?? '/');
    })->name('notification.read');
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approval');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:approver'])->group(function () {
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/vehicle', [VehicleController::class, 'index'])->name('vehicle');
    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::put('/vehicle/{id}', [VehicleController::class, 'update'])->name('vehicle.update');
    Route::delete('/vehicle/{id}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');

    Route::get('/driver', [DriverController::class, 'index'])->name('driver');
    Route::post('/driver', [DriverController::class, 'store'])->name('driver.store');
    Route::put('/driver/{id}', [DriverController::class, 'update'])->name('driver.update');
    Route::delete('/driver/{id}', [DriverController::class, 'destroy'])->name('driver.destroy');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/excel', [ReportController::class, 'exportExcel'])->name('report.excel');
    Route::get('/report/pdf', [ReportController::class, 'exportPdf'])->name('report.pdf');

    Route::post('/usage/{booking}/start', [VehicleUsageController::class, 'start'])->name('start.usage');
    Route::post('/usage/{booking}/complete', [VehicleUsageController::class, 'complete'])->name('completed.usage');
});

require __DIR__ . '/auth.php';
