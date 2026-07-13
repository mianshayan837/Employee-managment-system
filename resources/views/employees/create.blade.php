@extends('layouts.app')

@section('title', 'Add employee')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">New record</p>
        <h1 class="font-display fw-semibold text-ink mb-1">Add employee</h1>
        <p class="text-slate mb-0">An employee code is generated automatically once saved.</p>
    </div>

    <div class="panel p-4 p-md-5" style="max-width: 780px;">
        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
            @csrf
            @include('employees._form')

            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-ink px-4">Save employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-ink">Cancel</a>
            </div>
        </form>
    </div>

@endsection
