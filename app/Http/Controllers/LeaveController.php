<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        $leaveRequests = $employee->leaveRequests()
            ->latest()
            ->paginate(10);

        return view('employees.leaves.index', compact('employee', 'leaveRequests'));
    }

    public function create()
    {
        return view('employees.leaves.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:annual,sick,casual'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = Auth::user()->employee;

        $days = \Carbon\Carbon::parse($validated['start_date'])
            ->diffInDays(\Carbon\Carbon::parse($validated['end_date'])) + 1;

        $remaining = $employee->remainingLeaveDays($validated['type']);

        if ($remaining < $days) {
            return back()->withInput()->with('error',
                'Not enough '.$validated['type'].' leave balance. Available: '.$remaining.' days.'
            );
        }

        $employee->leaveRequests()->create([
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')
            ->with('status', 'Leave request submitted. Waiting for admin approval.');
    }
}