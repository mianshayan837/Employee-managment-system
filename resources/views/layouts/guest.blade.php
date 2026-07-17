<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Personnel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="guest-wrapper">
        <div class="w-100" style="max-width: 550px;">

            

            <div class="badge-card">
                <div class="badge-card-header text-center">
                    <span class="badge-hole"></span>

                    

                    <h1 class="guest-title font-display fw-semibold fs-3 text-ink mb-1">
                        @yield('heading')
                    </h1>
               
                </div>

                <div class="p-4 ">

                @if(session('status'))
    <div id="flash-message" data-type="success" class="d-none">{{ session('status') }}</div>
@endif

@if(session('error'))
    <div id="flash-message" data-type="error" class="d-none">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div id="flash-message" data-type="error" class="d-none">{{ $errors->first() }}</div>
@endif

                    @yield('content')

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>