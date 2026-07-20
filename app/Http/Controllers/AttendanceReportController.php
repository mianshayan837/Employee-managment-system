<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
  public function today(Request $request)
{
    $search = $request->query('q');

    $employees = Employee::with(['department', 'attendances' => function ($q) {
            $q->whereDate('date', today());
        }])
        ->when($search, function ($query, $search) {
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($deptQuery) use ($search) {
                        $deptQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->orderBy('name')
        ->get();

    return view('attendance.today', compact('employees', 'search'));
}

    public function employee(Request $request, Employee $employee)
    {
        $month = $request->query('month', now()->format('Y-m'));

        $controller = new AttendanceController();
        $days = (fn () => $this->buildMonthDays($employee->id, $month))
            ->call($controller);

        return view('attendance.employee', compact('employee', 'days', 'month'));
    }
}