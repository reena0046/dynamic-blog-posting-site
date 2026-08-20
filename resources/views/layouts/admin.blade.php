<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Admin') | BlogSpace</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    @stack('styles')
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img admin-logo">
                        <span class="logo-icon"><i class="ti ti-notebook"></i></span>
                        <span class="admin-logo-text">Blog<span>Space</span></span>
                    </a>
                    <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-5 text-muted"></i>
                    </div>
                </div>
                @include('layouts.admin.navbar')
            </div>
        </aside>

        <div class="body-wrapper">
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav flex-row ms-auto align-items-center">
                        <li class="nav-item dropdown">
                            <button type="button" class="border-0 bg-transparent" id="adminProfileDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Open Admin profile">
                                <div class="admin-avatar-circle">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown text-start"
                                aria-labelledby="adminProfileDropdown">
                                <h5 class="profile-dropdown-title">User Profile</h5>
                                <div class="profile-dropdown-user">
                                    <div class="profile-dropdown-photo">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="profile-dropdown-info">
                                        <h6>{{ auth()->user()->name ?? 'Admin' }}</h6>
                                        <span class="profile-dropdown-meta">
                                            <i class="fas fa-envelope"></i>
                                            {{ auth()->user()->email ?? '' }}
                                        </span>
                                    </div>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="profile-logout-btn">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
            </header>

            <div class="container-fluid py-4">
                @include('admin.partials.flash')
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        document.getElementById('sidebarCollapse')?.addEventListener('click', function() {
            document.getElementById('main-wrapper')?.classList.remove('show-sidebar');
        });
    </script>
    @stack('scripts')
    @include('layouts.admin.modal')
</body>

</html>
