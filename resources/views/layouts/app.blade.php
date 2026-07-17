<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Personnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell d-flex">

        <aside class="sidebar">
            <div class="sidebar-brand">
                
                <div class="badge-icon" style="background-color: var(--brass); color: var(--ink);">
    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
</div>
                <div>
                   
                
                    <p class="font-display mb-0" style="font-size:1.05rem; line-height:1.1;">{{ explode(' ', auth()->user()->name)[0] }}.</p>
                 <p class="label-mono mb-0" style="color: rgba(255,255,255,0.4);">Records &amp; staff</p>
                </div>
            </div>

          <nav class="sidebar-nav">
    @if (auth()->user()->isAdmin())
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Overview
        </a>
        <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Employees
        </a>
        <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Departments
        </a>
        <a href="{{ route('leave-requests.index') }}" class="sidebar-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Leave Requests
        </a>
    @else
        <a href="{{ route('employees.dashboard') }}" class="sidebar-link {{ request()->routeIs('employees.dashboard') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> My Dashboard
        </a>
        <a href="{{ route('leaves.create') }}" class="sidebar-link {{ request()->routeIs('leaves.create') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Apply Leave
        </a>
        <a href="{{ route('leaves.index') }}" class="sidebar-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}">
            <span class="sidebar-dot"></span> Leave History
        </a>
    @endif
</nav>
@auth

            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-2 mb-3 px-1">
                    <div class="avatar-circle" style="background-color: rgba(255,255,255,0.1); color: #fff;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="text-truncate">
                        <p class="mb-0 small text-truncate">{{ auth()->user()->name }}</p>
                        <p class="mb-0 label-mono" style="color: rgba(255,255,255,0.4);">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 text-start text-white-50" style="background:transparent;">
                        Sign out
                    </button>
                </form>
                @endauth


            </div>
        </aside>

        <main class="main-content">
            <div class="content-inner">

               @if (session('status'))
    <div id="flash-message" data-type="success" class="d-none">{{ session('status') }}</div>
@endif

@if (session('error'))
    <div id="flash-message" data-type="error" class="d-none">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div id="flash-message" data-type="error" class="d-none">{{ $errors->first() }}</div>
@endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
