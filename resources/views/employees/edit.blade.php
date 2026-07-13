@extends('layouts.app')

@section('title', 'Edit employee')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">{{ $employee->employee_code }}</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Edit {{ $employee->name }}</h1>
    </div>

    <div class="panel p-4 p-md-5" style="max-width: 780px;">
        <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('employees._form')

            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top ">
                <button type="submit" class="btn btn-ink px-4">Save changes</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-ink">Cancel</a>
            </div>
        </form>
    </div>

@endsection
