<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee()->with('department')->firstOrFail();

        $recentLeaves = $employee->leaveRequests()
            ->latest()
            ->take(5)
            ->get();

        return view('employees.dashboard', compact('employee', 'recentLeaves'));
    }
}