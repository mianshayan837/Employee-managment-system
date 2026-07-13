@extends('layouts.app')

@section('title', 'EMS-Dashboard')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="eyebrow mb-2">{{ now()->format('l, d M Y') }}</p>
            <h1 class="font-display fw-semibold text-ink mb-1">Dashboard </h1>
            <p class="text-slate mb-0">A quick look at today's roster.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.create') }}" class="btn btn-ink d-flex align-items-center gap-2">
                <span class="text-brass">+</span> Add employee
            </a>
            <a href="{{ route('departments.create') }}" class="btn btn-outline-ink d-flex align-items-center gap-2">
                <span>+</span> Add department
            </a>
        </div>
    </div>

    @php
        $activePct = $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0;
        $cards = [
            [
                'label' => 'Total employees',
                'value' => $stats['total'],
                'accent' => 'accent-ink',
                'icon' => 'users',
            ],
            [
                'label' => 'Active',
                'value' => $stats['active'],
                'accent' => 'accent-success',
                'icon' => 'check',
            ],
            [
                'label' => 'Inactive',
                'value' => $stats['inactive'],
                'accent' => 'accent-slate',
                'icon' => 'pause',
            ],
            [
                'label' => 'Departments',
                'value' => $stats['departments'],
                'accent' => 'accent-brass',
                'icon' => 'building',
            ],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach ($cards as $card)
            <div class="col-6 col-lg-3">
                <div class="stat-card {{ $card['accent'] }}">
                    <div class="d-flex align-items-start justify-content-between">
                        <p class="label-mono mb-2">{{ $card['label'] }}</p>
                        <span class="stat-icon">
                            @switch($card['icon'])
                                @case('users')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @break
                                @case('check')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @break
                                @case('pause')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="10" y1="9" x2="10" y2="15"/><line x1="14" y1="9" x2="14" y2="15"/></svg>
                                    @break
                                @case('building')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                                    @break
                            @endswitch
                        </span>
                    </div>
                    <p class="font-display fw-semibold fs-2 mb-0 text-ink">{{ $card['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-header d-flex align-items-center justify-content-between">
                    <h2 class="font-display fs-5 fw-semibold mb-0">Recently added</h2>
                    <a href="{{ route('employees.index') }}" class="small text-brass-dark text-decoration-none">View all →</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($recent as $employee)
                        <div class="list-group-item d-flex align-items-center justify-content-between border-0 border-bottom py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">
                                    @if ($employee->profile_image_url)
                                        <img src="{{ $employee->profile_image_url }}" alt="{{ $employee->name }}">
                                    @else
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="mb-0 small fw-medium">{{ $employee->name }}</p>
                                    <p class="mb-0 label-mono">{{ $employee->employee_code }} · {{ $employee->department->name ?? '—' }}</p>
                                </div>
                            </div>
                            <span class="status-pill {{ $employee->status }}">{{ ucfirst($employee->status) }}</span>
                        </div>
                    @empty
                        <div class="text-center text-slate small py-5">
                            No employees on file yet. <a href="{{ route('employees.create') }}" class="text-brass-dark">Add the first one →</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel mb-4">
                <div class="panel-header">
                    <h2 class="font-display fs-5 fw-semibold mb-0">Workforce status</h2>
                </div>
                <div class="p-4 d-flex align-items-center gap-4">
                    <div class="donut" style="--pct: {{ $activePct }};">
                        <span class="donut-value">{{ $activePct }}%</span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="legend-dot" style="background: var(--brass);"></span>
                            <span class="small">Active — {{ $stats['active'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-dot" style="background: rgba(16,25,46,0.12);"></span>
                            <span class="small">Inactive — {{ $stats['inactive'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header d-flex align-items-center justify-content-between">
                    <h2 class="font-display fs-5 fw-semibold mb-0">By department</h2>
                    <a href="{{ route('departments.index') }}" class="small text-brass-dark text-decoration-none">Manage →</a>
                </div>
                <div class="p-4">
                    @forelse ($byDepartment as $dept)
                        @php $pct = $stats['total'] > 0 ? round(($dept->employees_count / $stats['total']) * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-medium">{{ $dept->name }}</span>
                                <span class="font-mono text-slate">{{ $dept->employees_count }}</span>
                            </div>
                            <div class="progress-brass">
                                <div class="bar" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate small mb-0">No department data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
