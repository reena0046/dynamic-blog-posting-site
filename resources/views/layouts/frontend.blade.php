<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'BlogSpace')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    @stack('head')


    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">


    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    @stack('styles')

</head>


<body class="@yield('body_class')">


    <!-- =========================
                NAVBAR
    ========================== -->

    <nav class="navbar frontend-navbar">

        <div class="container">


            <a href="/" class="navbar-brand blog-logo">

                <div class="logo-icon">

                    <i class="ti ti-notebook"></i>

                </div>


                <span>

                    Blog<span>Space</span>

                </span>

            </a>



            <div class="navbar-actions">
                @auth
                    <span class="nav-user-name">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn signup-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}"
                        class="btn nav-auth-btn {{ request()->routeIs('register') ? 'is-active' : 'is-outline' }}">Sign up</a>
                    <a href="{{ route('login') }}"
                        class="btn nav-auth-btn {{ request()->routeIs('register') ? 'is-outline' : 'is-active' }}">Log in</a>
                @endauth
            </div>


        </div>

    </nav>



    <!-- =========================
                MAIN CONTENT
    ========================== -->

    <main>
        @yield('content')
    </main>



    <!-- =========================
                FOOTER
    ========================== -->

    <footer class="frontend-footer">

        <div class="container">

            <div class="footer-content">

                <p>
                    © {{ date('Y') }} <strong>BlogSpace</strong>. All rights reserved.
                </p>

            </div>

        </div>

    </footer>



    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: false,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };
        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif
        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif
    </script>


    @stack('scripts')

</body>

</html>
