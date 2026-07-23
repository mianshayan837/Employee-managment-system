@extends('layouts.app')

@section('title', 'Edit Shift')

@section('content')

    <div class="mb-4">
        <a href="{{ route('shifts.index') }}" class="text-slate text-decoration-none small">← Back to shifts</a>
    </div>

    <div class="mb-4">
        <p class="eyebrow mb-2">Shift Management</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Edit {{ $shift->name }}</h1>
    </div>

    <div class="panel p-4 p-md-5 shift-form-panel" style="max-width: 600px;">
        <form method="POST" action="{{ route('shifts.update', $shift) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_time" class="label-mono form-label">Start time</label>
                    <input type="time" id="start_time" name="start_time"
                           value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                           required class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="end_time" class="label-mono form-label">End time</label>
                    <input type="time" id="end_time" name="end_time"
                           value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                           required class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="grace_minutes" class="label-mono form-label">Grace period (minutes)</label>
                    <input type="number" id="grace_minutes" name="grace_minutes" min="0" max="120"
                           value="{{ old('grace_minutes', $shift->grace_minutes) }}" required class="form-control">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-ink px-4">Save changes</button>
                <a href="{{ route('shifts.index') }}" class="btn btn-outline-ink">Cancel</a>
            </div>
        </form>
    </div>

@endsection