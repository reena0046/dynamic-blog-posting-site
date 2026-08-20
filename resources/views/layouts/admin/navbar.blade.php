<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">
        <li class="sidebar-item">
            <a class="sidebar-link @if (Route::is('admin.dashboard')) active @endif"
                href="{{ route('admin.dashboard') }}"
                aria-expanded="false">
                <span>
                    <i class="fas fa-chart-line"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link @if (Route::is('admin.blogs.*')) active @endif"
                href="{{ route('admin.blogs.index') }}"
                aria-expanded="false">
                <span>
                    <i class="fas fa-blog"></i>
                </span>
                <span class="hide-menu">Blogs</span>
            </a>
        </li>
    </ul>
</nav>
