@extends('layouts.app')

@section('title', "Today's Attendance")

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Attendance</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Today — {{ now()->format('d M Y') }}</h1>
    </div>

    <form method="GET" action="{{ route('attendance-report.today') }}" class="panel p-3 mb-4 row g-3 align-items-end">
        <div class="col-md-6">
            <label class="label-mono form-label">Search</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="Employee name or department" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-ink border w-100">Search</button>
        </div>
        @if ($search)
            <div class="col-12">
                <a href="{{ route('attendance-report.today') }}" class="small text-slate text-decoration-none">Clear search</a>
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
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Monthly report</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        @php $today = $employee->attendances->first(); @endphp
                        <tr>
                            <td class="ps-4">{{ $employee->name }}</td>
                            <td class="text-slate small">{{ $employee->department->name ?? '—' }}</td>
                            <td class="text-slate small">{{ $today && $today->check_in ? \Carbon\Carbon::parse($today->check_in)->format('h:i A') : '—' }}</td>
                            <td class="text-slate small">{{ $today && $today->check_out ? \Carbon\Carbon::parse($today->check_out)->format('h:i A') : '—' }}</td>
                            <td>
                                @if ($today)
                                    <span class="leave-status-pill {{ $today->statusColor() }}">{{ $today->statusLabel() }}</span>
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
                            <td colspan="6" class="text-center text-slate small py-5">
                                @if ($search)
                                    No employees match "{{ $search }}".
                                @else
                                    No employees found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection