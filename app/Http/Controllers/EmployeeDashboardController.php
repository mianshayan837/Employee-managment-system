<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee()->with(['department', 'shift'])->firstOrFail();

        $recentLeaves = $employee->leaveRequests()
            ->latest()
            ->take(5)
            ->get();

        $leaveBalances = [
            'annual' => $employee->remainingLeaveDays('annual'),
            'sick' => $employee->remainingLeaveDays('sick'),
            'casual' => $employee->remainingLeaveDays('casual'),
        ];

        return view('employees.dashboard', compact('employee', 'recentLeaves', 'leaveBalances'));
    }
}