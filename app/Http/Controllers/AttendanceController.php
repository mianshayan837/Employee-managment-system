<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::user()->employee()->with('shift')->first();

        $today = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        $month = $request->query('month', now()->format('Y-m'));
        $days = $this->buildMonthDays($employee->id, $month);

        return view('employees.attendance.index', compact('employee', 'today', 'days', 'month'));
    }

   public function checkIn()
{
    $employee = Auth::user()->employee()->with('shift')->first();

    if (! $employee->shift) {
        return back()->with('error', 'No shift assigned to you. Please contact the admin.');
    }

    $shift = $employee->shift;
    $now = Carbon::now();
    $workStart = Carbon::parse($shift->start_time);
    $workEnd = Carbon::parse($shift->end_time);

  
    if ($now->format('H:i:s') < $workStart->format('H:i:s')) {
        return back()->with('error', 'Your shift ('.$shift->timeRangeLabel().') has not started yet. You can check in from '.$workStart->format('h:i A').' onwards.');
    }

    if ($now->format('H:i:s') > $workEnd->format('H:i:s')) {
        return back()->with('error', 'Your shift ('.$shift->timeRangeLabel().') has already ended. Check-in window is closed for today.');
    }

    $existing = Attendance::where('employee_id', $employee->id)
        ->whereDate('date', today())
        ->first();

    if ($existing) {
        return back()->with('error', 'You have already checked in today.');
    }

    $onLeaveToday = LeaveRequest::where('employee_id', $employee->id)
        ->where('status', 'approved')
        ->where('start_date', '<=', today())
        ->where('end_date', '>=', today())
        ->exists();

    if ($onLeaveToday) {
        return back()->with('error', 'You are on approved leave today. Check-in is not allowed.');
    }

    $graceLimit = $workStart->copy()->addMinutes($shift->grace_minutes);

    $lateMinutes = 0;
    $status = 'present';

    if ($now->format('H:i:s') > $graceLimit->format('H:i:s')) {
        $lateMinutes = $workStart->diffInMinutes($now);
        $status = 'late';
    }

    Attendance::create([
        'employee_id' => $employee->id,
        'shift_id' => $shift->id,
        'date' => today(),
        'check_in' => $now->format('H:i:s'),
        'status' => $status,
        'late_minutes' => $lateMinutes,
    ]);

    return back()->with('status', 'Checked in successfully at '.$now->format('h:i A').'.');
}

    public function checkOut()
    {
        $employee = Auth::user()->employee;

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->with('shift')
            ->first();

        if (! $attendance || ! $attendance->check_in) {
            return back()->with('error', 'You have not checked in today yet.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out today.');
        }

        $shift = $attendance->shift ?? $employee->shift;

        $now = Carbon::now();
        $checkIn = Carbon::parse($attendance->check_in);
        $workedMinutes = $checkIn->diffInMinutes($now);

        $workEnd = Carbon::parse($shift->end_time);
        $overtimeMinutes = 0;
        if ($now->format('H:i:s') > $workEnd->format('H:i:s')) {
            $overtimeMinutes = $workEnd->diffInMinutes($now);
        }

        $halfDayThreshold = $shift->halfDayThresholdMinutes();

        $status = $attendance->status;
        if ($workedMinutes < $halfDayThreshold) {
            $status = 'half_day';
        }

        $attendance->update([
            'check_out' => $now->format('H:i:s'),
            'worked_minutes' => $workedMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'status' => $status,
        ]);

        return back()->with('status', 'Checked out successfully at '.$now->format('h:i A').'.');
    }

    public function calendar(Request $request)
    {
        $employee = Auth::user()->employee;
        $month = $request->query('month', now()->format('Y-m'));
        $days = $this->buildMonthDays($employee->id, $month);

        return view('employees.attendance.calendar', compact('days', 'month'));
    }

    private function buildMonthDays(int $employeeId, string $month): array
    {
        $start = Carbon::parse($month.'-01');
        $end = $start->copy()->endOfMonth();

        $records = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($row) => $row->date->format('Y-m-d'));

        $leaves = LeaveRequest::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->where('start_date', '<=', $end)
            ->where('end_date', '>=', $start)
            ->get();

        $days = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $dateKey = $cursor->format('Y-m-d');
            $isWeekend = $cursor->isWeekend();
            $isFuture = $cursor->isAfter(today());

            $record = $records->get($dateKey);
            $onLeave = $leaves->first(fn ($leave) => $cursor->between($leave->start_date, $leave->end_date));

            if ($record) {
                $status = $record->status;
                $statusLabel = $record->statusLabel();
                $color = $record->statusColor();
            } elseif ($onLeave) {
                $status = 'on_leave';
                $statusLabel = 'On Leave';
                $color = 'pending';
            } elseif ($isWeekend) {
                $status = 'weekend';
                $statusLabel = 'Weekend';
                $color = 'weekend';
            } elseif ($isFuture) {
                $status = 'upcoming';
                $statusLabel = '—';
                $color = 'upcoming';
            } else {
                $status = 'absent';
                $statusLabel = 'Absent';
                $color = 'danger';
            }

            $days[] = [
                'date' => $cursor->copy(),
                'status' => $status,
                'label' => $statusLabel,
                'color' => $color,
                'record' => $record,
            ];

            $cursor->addDay();
        }

        return $days;
    }
}