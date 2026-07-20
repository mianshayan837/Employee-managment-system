@extends('layouts.app')

@section('title', 'Attendance Calendar')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="eyebrow mb-2">Calendar</p>
            <h1 class="font-display fw-semibold text-ink mb-0">{{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h1>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('attendance.calendar') }}">
                <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" onchange="this.form.submit()">
            </form>
            <a href="{{ route('attendance.index') }}" class="btn btn-ink btn-sm"><i class="fa-solid fa-list"></i> List view</a>
        </div>
    </div>

    <div class="panel p-4">
        <div class="calendar-grid">
            @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayName)
                <div class="calendar-day-name">{{ $dayName }}</div>
            @endforeach

            @php $firstDayOffset = $days[0]['date']->dayOfWeek; @endphp
            @for ($i = 0; $i < $firstDayOffset; $i++)
                <div class="calendar-cell calendar-cell-empty"></div>
            @endfor

            @foreach ($days as $day)
                <div class="calendar-cell calendar-cell-{{ $day['color'] }}" title="{{ $day['label'] }}">
                    <span class="calendar-date">{{ $day['date']->format('d') }}</span>
                    <span class="calendar-status">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="calendar-legend mt-4">
            <span><i class="legend-dot" style="background:var(--success)"></i> Present</span>
            <span><i class="legend-dot" style="background:#B98A3D"></i> Late</span>
            <span><i class="legend-dot" style="background:#D97706"></i> Half Day</span>
            <span><i class="legend-dot" style="background:var(--danger)"></i> Absent</span>
            <span><i class="legend-dot" style="background:#2563EB"></i> On Leave</span>
            <span><i class="legend-dot" style="background:rgba(16,25,46,0.15)"></i> Weekend</span>
        </div>
    </div>

@endsection