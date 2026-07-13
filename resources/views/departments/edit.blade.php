@extends('layouts.app')

@section('title', 'Edit department')

@section('content')

    <div class="mb-4">
        <p class="eyebrow mb-2">{{ $department->code }}</p>
        <h1 class="font-display fw-semibold text-ink mb-0">Edit {{ $department->name }}</h1>
    </div>

    <div class="panel p-4 p-md-5" style="max-width: 600px;">
        <form method="POST" action="{{ route('departments.update', $department) }}">
            @csrf
            @method('PUT')
            @include('departments._form')

            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-ink px-4">Save changes</button>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-ink">Cancel</a>
            </div>
        </form>
    </div>

@endsection
