<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Compiling Logics · Team</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <nav class="public-nav d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="public-brand text-decoration-none">
            <span class="public-brand-mark">CL</span>
            <span class="font-display fw-semibold">Compiling Logics</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-ink btn-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-ink btn-sm">Register</a>
        </div>
    </nav>

    <div class="team-section py-5">

        <div class="team-inner mb-4">
            <p class="eyebrow mb-2">Our People</p>
            <h1 class="font-display fw-semibold text-ink mb-1">Meet the team</h1>
            <p class="text-slate mb-0">Everyone currently on record at Compiling Logics.</p>
        </div>

        @php
            $employeeRows = ($employees ?? collect())->chunk(6);
        @endphp

        @if($employeeRows->count())
            <div class="team-inner d-flex flex-column gap-4">
                @foreach($employeeRows as $rowIndex => $rowEmployees)
                    <div class="team-row" data-row>
                        <button type="button" class="row-nav row-nav-prev" aria-label="Previous">
                            <span class="row-nav-arrow">&#8592;</span>
                        </button>

                        <div class="team-row-viewport">
                            <div class="team-row-track">
                                @foreach($rowEmployees as $employee)
                                    <div class="team-row-card-wrap">
                                        <div class="employee-card h-100">
                                            <div class="employee-card-top d-flex align-items-start">
                                                <div class="employee-card-avatar">
                                                    <img
                                                        src="{{ $employee->profile_image_url ?: 'https://ui-avatars.com/api/?name='.urlencode($employee->name).'&background=B98A3D&color=fff&size=128' }}"
                                                        alt="{{ $employee->name }}">
                                                </div>
                                                <div class="employee-card-info flex-grow-1 min-w-0">
                                                    <p class="employee-name mb-0">{{ $employee->name }}</p>
                                                    <p class="employee-role mb-0">{{ $employee->department->name ?? '—' }}</p>
                                                </div>
                                            </div>

                                          <div class="employee-meta">
    <span class="meta-pill meta-pill-salary">
        💰 {{ isset($employee->salary) ? number_format($employee->salary) : '—' }}
    </span>
    <span class="meta-pill meta-pill-role">
        💼 {{ $employee->designation ?? '—' }}
    </span>
</div>

                                            <button type="button" class="btn-status-lg {{ $employee->status }}" disabled>
                                                {{ ucfirst($employee->status) }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" class="row-nav row-nav-next" aria-label="Next">
                            <span class="row-nav-arrow">&#8594;</span>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="team-inner">
                <div class="panel text-center text-slate small py-5">
                    No employees on file yet.
                </div>
            </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body></html>