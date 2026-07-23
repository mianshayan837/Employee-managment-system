<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
  public function today(Request $request)
{
    $search = $request->query('q');
    $shiftFilter = $request->query('shift');

    $shifts = Shift::orderBy('start_time')->get();

    $employees = Employee::with([
            'department',
            'shift',
            'attendances' => function ($q) {
                $q->whereDate('date', today());
            },
            'leaveRequests' => function ($q) {
                $q->where('status', 'approved')
                    ->where('start_date', '<=', today())
                    ->where('end_date', '>=', today());
            },
        ])
        ->when($search, function ($query, $search) {
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($deptQuery) use ($search) {
                        $deptQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->when($shiftFilter, function ($query, $shiftFilter) {
            $query->where('shift_id', $shiftFilter);
        })
        ->orderBy('name')
        ->get();

    return view('attendance.today', compact('employees', 'search', 'shifts', 'shiftFilter'));
}

    public function employee(Request $request, Employee $employee)
    {
        $month = $request->query('month', now()->format('Y-m'));

        $controller = new AttendanceController();
        $days = (fn () => $this->buildMonthDays($employee->id, $month))
            ->call($controller);

        return view('attendance.employee', compact('employee', 'days', 'month'));
    }

    public function updateStatus(Request $request, Employee $employee, string $date)
{
    $validated = $request->validate([
        'status' => ['required', 'in:present,late,absent,half_day,on_leave'],
        'check_in' => ['nullable', 'date_format:H:i'],
        'check_out' => ['nullable', 'date_format:H:i'],
    ]);

    $attendance = Attendance::firstOrNew([
        'employee_id' => $employee->id,
        'date' => $date,
    ]);

    if (! $attendance->exists) {
        $attendance->shift_id = $employee->shift_id;
    }

    $attendance->status = $validated['status'];
    $attendance->check_in = $validated['check_in'] ? $validated['check_in'].':00' : $attendance->check_in;
    $attendance->check_out = $validated['check_out'] ? $validated['check_out'].':00' : $attendance->check_out;

    $attendance->save();

    return back()->with('status', $employee->name.'\'s attendance for '.\Carbon\Carbon::parse($date)->format('d M Y').' has been updated to '.$attendance->statusLabel().'.');
}
}