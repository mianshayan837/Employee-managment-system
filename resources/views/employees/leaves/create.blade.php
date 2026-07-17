@extends('layouts.app')

@section('title', 'Apply Leave')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Leave</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Apply for leave</h1>
    </div>

    <div class="panel p-4 p-md-5" style="max-width: 640px;">
        <form method="POST" action="{{ route('leaves.store') }}">
            @csrf

            <div class="mb-3">
                <label for="type" class="label-mono form-label">Leave type</label>
                <select id="type" name="type" required class="form-select">
                    <option value="">Select type</option>
                    <option value="annual" @selected(old('type') === 'annual')>Annual</option>
                    <option value="sick" @selected(old('type') === 'sick')>Sick</option>
                    <option value="casual" @selected(old('type') === 'casual')>Casual</option>
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="label-mono form-label">Start date</label>
                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" required class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="label-mono form-label">End date</label>
                    <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required class="form-control">
                </div>
            </div>

            <div class="mb-4">
                <label for="reason" class="label-mono form-label">Reason (optional)</label>
                <textarea id="reason" name="reason" rows="3" class="form-control" placeholder="Briefly explain the reason">{{ old('reason') }}</textarea>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-ink px-4">Submit request</button>
                <a href="{{ route('leaves.index') }}" class="text-slate text-decoration-none">Cancel</a>
            </div>
        </form>
    </div>

@endsection