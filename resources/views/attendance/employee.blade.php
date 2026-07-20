@extends('layouts.app')

@section('title', $employee->name.' — Attendance')

@section('content')

    <div class="mb-4">
        <a href="{{ route('attendance-report.today') }}" class="text-slate text-decoration-none small">← Back to today's attendance</a>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="eyebrow mb-2">Monthly report</p>
            <h1 class="font-display fw-semibold text-ink mb-0">{{ $employee->name }}</h1>
            <p class="text-slate mb-0">{{ $employee->designation }} · {{ $employee->department->name ?? '—' }}</p>
        </div>
        <form method="GET" action="{{ route('attendance-report.employee', $employee) }}">
            <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </form>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Late</th>
                        <th>Overtime</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                        <tr>
                            <td class="ps-4">{{ $day['date']->format('d M (D)') }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->check_in ? \Carbon\Carbon::parse($day['record']->check_in)->format('h:i A') : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->check_out ? \Carbon\Carbon::parse($day['record']->check_out)->format('h:i A') : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->late_minutes ? $day['record']->late_minutes.' min' : '—' }}</td>
                            <td class="text-slate small">{{ $day['record'] && $day['record']->overtime_minutes ? $day['record']->overtime_minutes.' min' : '—' }}</td>
                            <td class="pe-4"><span class="leave-status-pill {{ $day['color'] }}">{{ $day['label'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection