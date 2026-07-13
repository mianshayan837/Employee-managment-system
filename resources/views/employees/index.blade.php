@extends('layouts.app')

@section('title', 'Employees')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p class="eyebrow mb-2">Roster</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Employees</h1>
    </div>

    <div class="d-flex gap-2">
        @if (auth()->user()->isAdmin())
            <a href="{{ route('employees.trashed') }}" class="btn btn-outline-ink ">
                Trash
            </a>
        @endif

        <a href="{{ route('employees.create') }}" class="btn btn-ink d-flex align-items-center gap-2">
            <span class="text-brass">+</span> Add employee
        </a>
    </div>
</div>

    <form method="GET" action="{{ route('employees.index') }}" class="panel p-3 mb-4 row g-3 align-items-end">
        <div class="col-md-5">
            <label class="label-mono form-label">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or department" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="label-mono form-label">Department</label>
            <select name="department" class="form-select">
                <option value="">All</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" @selected((string) request('department') === (string) $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="label-mono form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-ink border w-100">Filter</button>
        </div>
        @if (request()->hasAny(['q', 'department', 'status']))
            <div class="col-12">
                <a href="{{ route('employees.index') }}" class="small text-slate text-decoration-none">Clear filters</a>
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
                        <th>Designation</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        @if ($employee->profile_image_url)
                                            <img src="{{ $employee->profile_image_url }}" alt="{{ $employee->name }}">
                                        @else
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $employee->name }}</p>
                                        <p class="mb-0 font-mono text-slate small">{{ $employee->employee_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate">{{ $employee->department->name ?? '—' }}</td>
                            <td class="text-slate">{{ $employee->designation }}</td>
                            <td class="font-mono text-slate small">{{ $employee->joining_date->format('d M Y') }}</td>
                            <td><span class="status-pill {{ $employee->status }}">{{ ucfirst($employee->status) }}</span></td>
                            <td class="pe-4 text-end text-nowrap">
                                <a href="{{ route('employees.show', $employee) }}" class="text-slate small me-3 text-decoration-none">View</a>
                                <a href="{{ route('employees.edit', $employee) }}" class="text-brass-dark small me-3 text-decoration-none">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="d-inline"
                                          onsubmit="return confirm('Remove {{ $employee->name }} from the roster?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 small">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate small py-5">
                                No employees match this search.
                                <a href="{{ route('employees.create') }}" class="text-brass-dark">Add one →</a>
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
