@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">My Profile</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Welcome back, {{ explode(' ', $employee->name)[0] }}</h1>
    </div>

    <div class="panel p-4 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-circle" style="width:64px; height:64px; font-size:1.4rem;">
                @if ($employee->profile_image_url)
                    <img src="{{ $employee->profile_image_url }}" alt="{{ $employee->name }}">
                @else
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h4 class="mb-0">{{ $employee->name }}</h4>
                <p class="text-slate mb-0">{{ $employee->designation }} · {{ $employee->department->name ?? '—' }}</p>
                <p class="label-mono mb-0 text-slate">Joined {{ $employee->joining_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card accent-ink">
                <p class="label-mono mb-1">Annual Leave</p>
                <h3 class="mb-0">{{ $employee->annual_leave_balance }} <span class="small text-slate" style="font-size: 28px;">leaves left</span></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card accent-success">
                <p class="label-mono mb-1">Sick Leave</p>
                <h3 class="mb-0">{{ $employee->sick_leave_balance }} <span class="small text-slate" style="font-size: 28px;">leaves left</span></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card accent-slate">
                <p class="label-mono mb-1">Casual Leave</p>
                <h3 class="mb-0">{{ $employee->casual_leave_balance }} <span class="small text-slate" style="font-size: 28px;">leaves left</span></h3>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Recent Leave Requests</h5>
        <div class="d-flex gap-2">
           
            <a href="{{ route('leaves.create') }}" class="btn btn-ink btn-sm"><i class="fa-solid fa-person-walking-arrow-right"></i> Apply Leave </a>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLeaves as $leave)
                        <tr>
                            <td class="ps-4">{{ ucfirst($leave->type) }}</td>
                            <td class="text-slate small">{{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->days }}</td>
                            <td class="pe-4"><span class="leave-status-pill {{ $leave->statusColor() }}">{{ ucfirst($leave->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-slate small py-4">No leave requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
     <a href="{{ route('leaves.index') }}" class="text-slate small text-decoration-none">View all</a>

@endsection