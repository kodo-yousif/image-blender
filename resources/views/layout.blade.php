<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hair AI - Image Blender</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .navbar {
            background: #0d1b2a;
        }
        .navbar-brand span {
            font-weight: 700;
            color: #00b4d8;
        }
    </style>
</head>
<body class="bg-light">

        @if(!request()->routeIs('login') && !request()->routeIs('login.submit'))
        <nav class="navbar navbar-expand-lg shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Hair AI Logo">
                    <span>Hair AI</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                Home
                            </a>
                        </li>
                        @if(session('user.role') === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                Users
                            </a>
                        </li>
                        @endif
                    </ul>

                    <div class="d-flex">
                        @if(session('logged_in'))
                            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
        @endif

        <style>
            .navbar {
                background: #0d1b2a;
            }
            .navbar .nav-link {
                color: #fff !important;
            }
            .navbar .nav-link:hover,
            .navbar .nav-link.active {
                color: #00b4d8 !important;
            }
            .navbar-toggler {
                border-color: rgba(255,255,255,.5);
            }
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
            }
</style>

    <div class="container py-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
