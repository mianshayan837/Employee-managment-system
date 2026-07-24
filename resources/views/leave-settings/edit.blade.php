@extends('layouts.app')

@section('title', 'Leave Settings')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">Global Configuration</p>
        <h1 class="font-display fw-semibold text-ink mb-1">Leave Settings</h1>
        <p class="text-slate mb-0">These defaults apply to every employee (new and existing) immediately.</p>
    </div>

    <div class="panel p-4 p-md-5" style="max-width: 700px;">
        <form method="POST" action="{{ route('leave-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-4">
                    <label for="annual_default" class="label-mono form-label">Annual Leave (days/year)</label>
                    <input type="number" id="annual_default" name="annual_default" min="0" max="365"
                           value="{{ old('annual_default', $settings->annual_default) }}" required class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="sick_default" class="label-mono form-label">Sick Leave (days/year)</label>
                    <input type="number" id="sick_default" name="sick_default" min="0" max="365"
                           value="{{ old('sick_default', $settings->sick_default) }}" required class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="casual_default" class="label-mono form-label">Casual Leave (days/year)</label>
                    <input type="number" id="casual_default" name="casual_default" min="0" max="365"
                           value="{{ old('casual_default', $settings->casual_default) }}" required class="form-control">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-ink px-4">Save settings</button>
            </div>
        </form>
    </div>

@endsection