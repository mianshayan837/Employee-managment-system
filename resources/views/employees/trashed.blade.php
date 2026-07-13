@extends('layouts.app')

@section('title', 'Trashed employees')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="eyebrow mb-2">Deleted records</p>
            <h1 class="font-display fw-semibold text-ink mb-0">Trashed employees</h1>
        </div>
        <a href="{{ route('employees.index') }}" class="text-slate text-decoration-none small">
            ← Back to roster
        </a>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Deleted on</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="ps-4">
                                <p class="mb-0 fw-medium">{{ $employee->name }}</p>
                                <p class="mb-0 font-mono text-slate small">{{ $employee->employee_code }}</p>
                            </td>
                            <td class="text-slate">{{ $employee->department->name ?? '—' }}</td>
                            <td class="text-slate">{{ $employee->designation }}</td>
                            <td class="font-mono text-slate small">{{ $employee->deleted_at->format('d M Y, h:i A') }}</td>
                            <td class="pe-4 text-end text-nowrap">
                                <form method="POST" action="{{ route('employees.restore', $employee->id) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-link btn-sm text-success p-0 small me-3">Restore</button>
                                </form>

                                <form method="POST" action="{{ route('employees.force-delete', $employee->id) }}" class="d-inline"
                                      onsubmit="return confirm('This will permanently delete {{ $employee->name }}. This cannot be undone. Continue?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 small">Delete permanently</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate small py-5">
                                No trashed employees.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>

@endsection