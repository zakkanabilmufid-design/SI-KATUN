<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .produk-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
        background: #f9fafb;
    }

    .produk-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .produk-img-wrapper {
        background: #f0f0f0;
    }

    .object-fit-cover {
        object-fit: cover;
        width: 100%;
    }
    .logout-btn {
    color: #dc3545 !important; /* Bootstrap's text-danger */
    }
    .logout-btn:hover {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

</style>

    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            font-family: 'Nunito', sans-serif;
        }
        .navbar {
            background-color: #0d6efd !important;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .dropdown-item {
            color: #212529 !important;
        }

        .btn-lg {
            border-radius: 1rem;
        }
        .dropdown-menu {
            background-color: #e3f2fd;
        }
    </style>

    @livewireStyles
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <div class="container">
                @if (Auth::user()->peran == 'admin')
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @endif
                @if (Auth::user()->peran == 'kasir')
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                                </a>


                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-danger fw-bold d-flex align-items-center gap-2 logout-btn" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex flex-wrap gap-3 justify-content-center">
                        @if (Auth::user()->peran == 'admin')
                            <a href="{{ route('keuangan')}}" wire:navigate class="btn btn-primary btn-lg px-4 py-3 shadow">
                                <i class="fas fa-home"></i> Beranda
                            </a>    
                            <a href="{{ route('user')}}" wire:navigate class="btn btn-info btn-lg px-4 py-3 shadow">
                                <i class="fas fa-users"></i> Pengguna
                            </a>
                            <a href="{{ route('produk')}}" wire:navigate class="btn btn-success btn-lg px-4 py-3 shadow">
                                <i class="fas fa-box"></i> Produk
                            </a>
                        @endif

                        @if (Auth::user()->peran == 'kasir')
                            <a href="{{ route('produk.kasir')}}" wire:navigate class="btn btn-primary btn-lg px-4 py-3 shadow">
                                <i class="fas fa-home"></i> Beranda
                            </a>
                            <a href="{{ route('transaksi')}}" wire:navigate class="btn btn-warning btn-lg px-4 py-3 shadow">
                                <i class="fas fa-shopping-cart"></i> Transaksi
                            </a>
                            <a href="{{ route('laporan')}}" wire:navigate class="btn btn-danger btn-lg px-4 py-3 shadow">
                                <i class="fas fa-file-alt"></i> Laporan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
