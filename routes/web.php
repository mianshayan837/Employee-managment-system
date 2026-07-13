<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    });
});