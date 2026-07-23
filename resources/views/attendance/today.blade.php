@extends('layouts.app')

@section('title', "Today's Attendance")

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Attendance</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Today — {{ now()->format('d M Y') }}</h1>
    </div>

    <form method="GET" action="{{ route('attendance-report.today') }}" class="panel p-3 mb-4 row g-3 align-items-end">
        <div class="col-md-5">
            <label class="label-mono form-label">Search</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="Employee name or department" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="label-mono form-label">Shift</label>
            <select name="shift" class="form-select" onchange="this.form.submit()">
                <option value="">All Shifts</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}" @selected((string) $shiftFilter === (string) $shift->id)>
                        {{ $shift->name }} ({{ $shift->timeRangeLabel() }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-ink w-100">Search</button>
        </div>
        @if ($search || $shiftFilter)
            <div class="col-12">
                <a href="{{ route('attendance-report.today') }}" class="small text-slate text-decoration-none">Clear filters</a>
            </div>
        @endif
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Department</th>
                        <th>Shift</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Monthly report</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                   @php
    $today = $employee->attendances->first();
    $onLeave = $employee->leaveRequests->first();
@endphp
<tr>
    <td class="ps-4">{{ $employee->name }}</td>
    <td class="text-slate small">{{ $employee->department->name ?? '—' }}</td>
    <td class="text-slate small">{{ $employee->shift->name ?? '—' }}</td>
    <td class="text-slate small">{{ $today && $today->check_in ? \Carbon\Carbon::parse($today->check_in)->format('h:i A') : '—' }}</td>
    <td class="text-slate small">{{ $today && $today->check_out ? \Carbon\Carbon::parse($today->check_out)->format('h:i A') : '—' }}</td>
    <td>
        @if ($today)
            <span class="leave-status-pill {{ $today->statusColor() }}">{{ $today->statusLabel() }}</span>
        @elseif ($onLeave)
            <span class="leave-status-pill pending">On Leave</span>
        @else
            <span class="leave-status-pill danger">Absent</span>
        @endif
    </td>
    <td class="pe-4 text-end">
        <a href="{{ route('attendance-report.employee', $employee) }}" class="text-brass-dark small text-decoration-none">View →</a>
    </td>
</tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate small py-5">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection