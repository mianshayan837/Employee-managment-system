@extends('layouts.guest')

@section('title', 'EMS-Login')
@section('heading', 'Sign in')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="label-mono form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6c0-1.1-.9-2-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h16a2 2 0 0 0 2-2V6Z"/><path d="m22 6-10 7L2 6"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-control" placeholder="you@example.com">
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="label-mono form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input id="password" type="password" name="password" required
                    class="form-control" placeholder="Password">
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label text-slate small" for="remember">Keep me signed in</label>
        </div>

        <button type="submit" class="btn btn-ink w-100 py-2 fw-medium">
            Sign in <span class="text-brass">→</span>
        </button>
    </form>

    <p class="text-center text-slate small mt-4 mb-0">
        New to the system?
        <a href="{{ route('register') }}" class="text-brass-dark fw-medium text-decoration-none">Create an account</a>
    </p>
@endsection