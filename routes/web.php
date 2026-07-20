<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use Illuminate\Support\Facades\Route;
use App\Models\Employee;

Route::get('/', function () {
    $employees = Employee::with('department')->latest()->get();
    return view('welcome', compact('employees'));
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Employee self-service area ----
    Route::get('/my/dashboard', [EmployeeDashboardController::class, 'index'])->name('employees.dashboard');
    Route::get('/my/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/my/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/my/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // ---- Attendance (employee) ----
Route::get('/my/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/my/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
Route::post('/my/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
Route::get('/my/attendance/calendar', [AttendanceController::class, 'calendar'])->name('attendance.calendar');

    Route::resource('employees', EmployeeController::class)->except(['destroy']);
    Route::resource('departments', DepartmentController::class)->except(['destroy', 'show']);

    Route::middleware('admin')->group(function () {
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');

        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
            ->name('departments.destroy');

        Route::get('/employees-trashed', [EmployeeController::class, 'trashed'])
            ->name('employees.trashed');

        Route::put('/employees/{id}/restore', [EmployeeController::class, 'restore'])
            ->name('employees.restore');

        Route::delete('/employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])
            ->name('employees.force-delete');

        // ---- Leave approval (admin only) ----
        Route::get('/leave-requests', [LeaveApprovalController::class, 'index'])->name('leave-requests.index');
        Route::put('/leave-requests/{leaveRequest}/approve', [LeaveApprovalController::class, 'approve'])->name('leave-requests.approve');
        Route::put('/leave-requests/{leaveRequest}/reject', [LeaveApprovalController::class, 'reject'])->name('leave-requests.reject');

        // ---- Attendance reports (admin) ----
Route::get('/attendance-report', [AttendanceReportController::class, 'today'])->name('attendance-report.today');
Route::get('/attendance-report/{employee}', [AttendanceReportController::class, 'employee'])->name('attendance-report.employee');
    });
});