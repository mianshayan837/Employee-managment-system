<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('employee.department')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(10);

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('status', 'This request has already been reviewed.');
        }

        $employee = $leaveRequest->employee;
        $remaining = $employee->remainingLeaveDays($leaveRequest->type);

        if ($remaining < $leaveRequest->days) {
            return back()->with('error',
                $employee->name.' does not have enough '.$leaveRequest->type.' leave balance to approve this request.'
            );
        }

        $leaveRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Leave request for '.$employee->name.' has been approved.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('status', 'This request has already been reviewed.');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Leave request for '.$leaveRequest->employee->name.' has been rejected.');
    }
}