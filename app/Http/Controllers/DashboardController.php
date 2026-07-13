<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
            'departments' => Department::count(),
        ];

        $recent = Employee::with('department')->latest()->take(5)->get();

        $byDepartment = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(6)
            ->get();

        return view('dashboard.index', [
            'stats' => $stats,
            'recent' => $recent,
            'byDepartment' => $byDepartment,
            'user' => Auth::user(),
        ]);
    }
}
