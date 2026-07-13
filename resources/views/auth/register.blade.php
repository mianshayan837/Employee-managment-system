@extends('layouts.guest')

@section('title', 'EMS-Register')
@section('heading', 'Create account')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="label-mono form-label">Full name</label>
            <div class="input-group">
                <span class="input-group-text">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="form-control" placeholder="Enter Your Full Name">
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="label-mono form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 6c0-1.1-.9-2-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h16a2 2 0 0 0 2-2V6Z"/><path d="m22 6-10 7L2 6"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="form-control" placeholder="you@example.com">
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-12 col-sm-12 mb-3 mb-sm-0">
                <label for="password" class="label-mono form-label">Password</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input id="password" type="password" name="password" required
                        class="form-control" placeholder="Password">
                </div>
            </div>
            <div class="col-12 col-sm-12 mb3">
                <label for="password_confirmation" class="label-mono form-label">Confirm</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="form-control" placeholder="Confirm Password">
                </div>
            </div>
        </div>
        <p class="text-slate small mb-4 mt-2">Password must contain at least 8 characters.</p>

        <button type="submit" class="btn btn-ink w-100 py-2 fw-medium">
            Create Account <span class="text-brass">→</span>
        </button>
    </form>

    <p class="text-center text-slate small mt-4 mb-0">
        Already have access?
        <a href="{{ route('login') }}" class="text-brass-dark fw-medium text-decoration-none">Sign in</a>
    </p>
@endsection