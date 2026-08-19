<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'BlogSpace')</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">


    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">


    @stack('styles')

</head>


<body>


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


                <a href="#" class="btn login-btn">

                    <i class="ti ti-login"></i>

                    <span>Login</span>

                </a>



                <a href="#" class="btn register-btn">

                    <i class="ti ti-user-plus"></i>

                    <span>Register</span>

                </a>


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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    @stack('scripts')

</body>

</html>
