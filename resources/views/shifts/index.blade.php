@extends('layouts.app')

@section('title', 'Shifts')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Shift Management</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Shifts</h1>
        <p class="text-slate mb-0">Only two fixed same-day shifts exist in the system.</p>
    </div>

    <div class="row g-4">
        @foreach ($shifts as $shift)
            <div class="col-md-6">
                <div class="shift-card shift-card-{{ $loop->index === 0 ? 'morning' : 'evening' }}">
                    <div class="shift-card-glow"></div>

                    <div class="shift-card-header">
                        <div class="shift-card-icon">
                            @if ($loop->index === 0)
                                <i class="fa-solid fa-sun"></i>
                            @else
                                <i class="fa-solid fa-moon"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0">{{ $shift->name }}</h5>
                            <p class="shift-card-time mb-0">{{ $shift->timeRangeLabel() }}</p>
                        </div>
                        <a href="{{ route('shifts.edit', $shift) }}" class="shift-edit-btn">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>

                    <div class="shift-card-stats">
                        <div class="shift-stat">
                            <span class="shift-stat-label">Grace period</span>
                            <span class="shift-stat-value">{{ $shift->grace_minutes }}<small> min</small></span>
                        </div>
                        <div class="shift-stat">
                            <span class="shift-stat-label">Half-day threshold</span>
                            <span class="shift-stat-value">{{ $shift->halfDayThresholdMinutes() }}<small> min</small></span>
                        </div>
                    </div>

                    <div class="shift-card-footer">
                        <div class="shift-employees-avatars">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <span class="shift-employees-count">{{ $shift->employees_count }}</span>
                            <span class="text-slate small">employee{{ $shift->employees_count === 1 ? '' : 's' }} assigned</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection