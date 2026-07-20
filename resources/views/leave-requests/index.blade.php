@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Approvals</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Leave requests</h1>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveRequests as $leave)
                        <tr>
                            <td class="ps-4">
                                <p class="mb-0 fw-medium">{{ $leave->employee?->name }}</p>
                                <p class="mb-0 text-slate small">{{ $leave->employee?->department->name ?? '—' }}</p>
                            </td>
                            <td>{{ ucfirst($leave->type) }}</td>
                            <td class="text-slate small">{{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->days }}</td>
                            <td><span class="leave-status-pill {{ $leave->statusColor() }}">{{ ucfirst($leave->status) }}</span></td>
                            <td class="pe-4 text-end">
                                @if ($leave->status === 'pending')
                                    <form method="POST" action="{{ route('leave-requests.approve', $leave) }}" class="d-inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm" style="font-size: 12px;"><b>Approve</b></button>

                                    </form>
                                    <form method="POST" action="{{ route('leave-requests.reject', $leave) }}" class="d-inline"
                                          onsubmit="return confirm('Reject this leave request?');">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size: 12px;"><b>Reject</b></button>
                                    </form>
                                @else
                                    <span class="text-slate small">Reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-slate small py-5">No leave requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $leaveRequests->links('pagination::bootstrap-5') }}</div>

@endsection