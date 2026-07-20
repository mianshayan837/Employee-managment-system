@extends('layouts.app')

@section('title', 'My Leave Requests')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="eyebrow mb-2">History</p>
            <h1 class="font-display fw-semibold text-ink mb-0">My leave requests</h1>
        </div>
        <a href="{{ route('leaves.create') }}" class="btn btn-ink"> <i class="fa-solid fa-person-walking-arrow-right"></i> Apply Leave</a>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leave)
                        <tr>
                            <td class="ps-4">{{ ucfirst($leave->type) }}</td>
                            <td class="text-slate small">{{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->days }}</td>
                            <td class="text-slate small">{{ $leave->reason ?: '—' }}</td>
                            <td class="pe-4"><span class="leave-status-pill {{ $leave->statusColor() }}">{{ ucfirst($leave->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate small py-5">No leave requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $leaveRequests->links('pagination::bootstrap-5') }}</div>

@endsection