@extends('layouts.app')

@section('title', 'Departments')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="eyebrow mb-2">Structure</p>
            <h1 class="font-display fw-semibold text-ink mb-0">Departments</h1>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-ink d-flex align-items-center gap-2">
            <span class="text-brass">+</span> Add department
        </a>
    </div>

    <form method="GET" action="{{ route('departments.index') }}" class="panel p-3 mb-4 row g-3 align-items-end">
        <div class="col-md-8">
            <label class="label-mono form-label">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or code" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-ink border w-100">Filter</button>
        </div>
        @if (request()->hasAny(['q']))
            <div class="col-12">
                <a href="{{ route('departments.index') }}" class="small text-slate text-decoration-none">Clear filters</a>
            </div>
        @endif
    </form>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-personnel mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Department</th>
                        <th>Code</th>
                        <th>Employees</th>
                        <th>Description</th>
                        <th class="pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $department->name }}</td>
                            <td class="font-mono text-slate small">{{ $department->code }}</td>
                            <td><span class="status-pill active">{{ $department->employees_count }}</span></td>
                            <td class="text-slate small">{{ $department->description ? \Illuminate\Support\Str::limit($department->description, 60) : '—' }}</td>
                            <td class="pe-4 text-end text-nowrap">
                                <a href="{{ route('departments.edit', $department) }}" class="text-brass-dark small me-3 text-decoration-none">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('departments.destroy', $department) }}" class="d-inline"
                                          onsubmit="return confirm('Remove {{ $department->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 small">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate small py-5">
                                No departments yet.
                                <a href="{{ route('departments.create') }}" class="text-brass-dark">Add one →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $departments->links('pagination::bootstrap-5') }}
    </div>

@endsection
