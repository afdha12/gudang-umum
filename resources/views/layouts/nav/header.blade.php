<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
            style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <div class="header-nav d-none d-lg-flex me-auto">
            @yield('title', 'RS HERMINA LAMPUNG')
        </div>
        <ul class="header-nav">
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg theme-icon-active">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-contrast') }}"></use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="light">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-sun') }}"></use>
                            </svg>Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="dark">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-moon') }}"></use>
                            </svg>Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button"
                            data-coreui-theme-value="auto">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-contrast') }}">
                                </use>
                            </svg>Auto
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        {{-- <img class="avatar-img" src="{{ asset('assets/img/avatars/8.jpg') }}" alt="user@email.com"> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        Account</div>
                    <a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-user') }}"></use>
                        </svg> Profile</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>

                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-account-logout') }}">
                            </use>
                        </svg>
                        Logout
                    </a>

                </div>
            </li>
        </ul>
        {{-- </div>
    <div class="container-fluid px-4">
        
    </div> --}}
</header>
