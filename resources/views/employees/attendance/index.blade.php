@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Attendance</p>
        <h1 class="font-display fw-semibold text-ink mb-0">My attendance</h1>
    </div>

    <div class="panel p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <p class="label-mono mb-1">Today — {{ now()->format('d M Y') }}</p>
                @if ($today)
                    <p class="mb-0">
                        Check-in: <strong>{{ $today->check_in ? \Carbon\Carbon::parse($today->check_in)->format('h:i A') : '—' }}</strong>
                        &nbsp;·&nbsp;
                        Check-out: <strong>{{ $today->check_out ? \Carbon\Carbon::parse($today->check_out)->format('h:i A') : '—' }}</strong>
                    </p>
                    <span class="leave-status-pill {{ $today->statusColor() }} mt-2 d-inline-block">{{ $today->statusLabel() }}</span>
                    
                   <span class="text-slate small leave-status-pill mt-2 d-inline-block">Attendance completed for today.</span>
                @else
                    <p class="text-slate mb-0">You haven't checked in today.</p>
                @endif
            </div>

            <div class="d-flex gap-2">
                @if (! $today || ! $today->check_in)
                    <form method="POST" action="{{ route('attendance.check-in') }}">
                        @csrf
                        <button type="submit" class="btn btn-ink px-4">Check In</button>
                    </form>
                @elseif (! $today->check_out)
                    <form method="POST" action="{{ route('attendance.check-out') }}">
                        @csrf
                        <button type="submit" class="btn btn-ink px-4">Check Out</button>
                    </form>
                {{-- @else
                    <span class="text-slate small">Attendance completed for today.</span> --}}
                @endif

                <a href="{{ route('attendance.calendar') }}" class="btn btn-ink"> <i class="fa-regular fa-calendar-days"></i>  View Calendar</a>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Monthly attendance — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h5>
        <form method="GET" action="{{ route('attendance.index') }}" class="d-flex gap-2">
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