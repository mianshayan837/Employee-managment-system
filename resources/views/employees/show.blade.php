@extends('layouts.app')

@section('title', $employee->name)

@section('content')

    <div class="mb-4">
        <a href="{{ route('employees.index') }}" class="text-slate text-decoration-none small">← Back to roster</a>
    </div>

    <div class="panel overflow-hidden" style="max-width: 640px;">
        <div class="d-flex align-items-center gap-3 p-4" style="background-color: var(--ink);">
            <div class="avatar-circle" style="width:64px; height:64px; background-color: rgba(255,255,255,0.1); color: var(--brass); font-size:1.4rem;">
                @if ($employee->profile_image_url)
                    <img src="{{ $employee->profile_image_url }}" alt="{{ $employee->name }}">
                @else
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <p class="label-mono mb-1" style="color: var(--brass);">{{ $employee->employee_code }}</p>
                <h1 class="font-display fw-semibold mb-0" style="color: var(--paper);">{{ $employee->name }}</h1>
                <p class="mb-0 small" style="color: rgba(255,255,255,0.6);">{{ $employee->designation }} · {{ $employee->department->name ?? '—' }}</p>
            </div>
        </div>

        <div class="row g-4 p-4">
            <div class="col-6">
                <p class="label-mono mb-1">Email</p>
                <p class="small mb-0">{{ $employee->email }}</p>
            </div>
            <div class="col-6">
                <p class="label-mono mb-1">Phone</p>
                <p class="small mb-0">{{ $employee->phone ?: '—' }}</p>
            </div>
            <div class="col-6">
                <p class="label-mono mb-1">Salary</p>
                <p class="small font-mono mb-0">Rs {{ number_format($employee->salary, 2) }}</p>
            </div>
            <div class="col-6">
                <p class="label-mono mb-1">Joined</p>
                <p class="small mb-0">{{ $employee->joining_date->format('d M Y') }}</p>
            </div>
            <div class="col-6">
                <p class="label-mono mb-1">Status</p>
                <span class="status-pill {{ $employee->status }}">{{ ucfirst($employee->status) }}</span>
            </div>
        </div>

        <div class="d-flex gap-3 p-4 pt-3 border-top">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-ink">Edit record</a>
            @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                      onsubmit="return confirm('Remove {{ $employee->name }} from the roster?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger">Delete</button>
                </form>
            @endif
        </div>
    </div>

@endsection
