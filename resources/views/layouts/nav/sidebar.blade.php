<div class="sidebar-header border-bottom">
    <div class="sidebar-brand">
        {{-- <img class="sidebar-brand-full" width="88" height="32" alt="CoreUI Logo">
            <use xlink:href="assets/icons/hermina_logo.png#full"></use>
        </img> --}}
        <img src="{{ asset('assets/icons/hermina_logo.png') }}" class="sidebar-brand-full" width="28" height="32"
            alt="CoreUI Logo">
        <img src="{{ asset('assets/icons/hermina_logo.png') }}" class="sidebar-brand-narrow" width="28" height="32"
            alt="CoreUI Logo">
        {{-- <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#signet"></use>
        </svg> --}}
    </div>
    <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark"
        aria-label="Close"
        onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
</div>


<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    @if (Auth::check())
        @if (!Auth::user()->password_changed)
            <!-- Hanya tampilkan menu dashboard saat password belum diganti -->
            <li class="nav-item"><a class="nav-link" href="{{ route('change-password.edit', $data->id) }}">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-speedometer') }}">
                        </use>
                    </svg> Ganti Password</a></li>
        @else
            {{-- Menu untuk Admin --}}
            @if ($role === 'admin')
                {{-- <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}">Manajemen User</a></li>
            <li><a href="{{ route('admin.reports') }}">Laporan</a></li> --}}
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-speedometer') }}">
                            </use>
                        </svg> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('users-management.index') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-apps') }}"></use>
                        </svg> User Manajemen</a></li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-puzzle') }}"></use>
                        </svg> Data Stok Barang</a>
                    <ul class="nav-group-items compact">
                        <li class="nav-item"><a class="nav-link" href="{{ route('stationeries.index') }}"><span class="nav-icon"><span
                                        class="nav-icon-bullet"></span></span> Alat Tulis & Form</a></li>
                        <li class="nav-item"><a class="nav-link" href="base/cards.html"><span class="nav-icon"><span
                                        class="nav-icon-bullet"></span></span> Perlengkapan Lainnya</a></li>
                    </ul>
                </li>
                {{-- <li class="nav-title">Theme</li>
            <li class="nav-item"><a class="nav-link" href="colors.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-drop') }}"></use>
                    </svg> Colors</a></li>
            <li class="nav-item"><a class="nav-link" href="typography.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-pencil') }}"></use>
                    </svg> Typography</a></li> --}}
                <li class="nav-title">Components</li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-cursor') }}"></use>
                        </svg> Buttons</a>
                    <ul class="nav-group-items compact">
                        <li class="nav-item"><a class="nav-link" href="buttons/buttons.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons</a></li>
                        <li class="nav-item"><a class="nav-link" href="buttons/button-group.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons Group</a></li>
                        <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Dropdowns</a></li>
                    </ul>
                </li>
            @elseif ($role === 'user')
                {{-- <li><a href="{{ route('user.dashboard') }}">User Dashboard</a></li>
            <li><a href="{{ route('user.profile') }}">Profil Saya</a></li>
            <li><a href="{{ route('user.activities') }}">Aktivitas</a></li> --}}

                <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-speedometer') }}">
                            </use>
                        </svg> Dashboard</a></li>
                {{-- <li class="nav-title">Theme</li>
            <li class="nav-item"><a class="nav-link" href="colors.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-drop') }}"></use>
                    </svg> Colors</a></li>
            <li class="nav-item"><a class="nav-link" href="typography.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-pencil') }}"></use>
                    </svg> Typography</a></li> --}}
                <li class="nav-title">Components</li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-puzzle') }}"></use>
                        </svg> Base</a>
                    <ul class="nav-group-items compact">
                        <li class="nav-item"><a class="nav-link" href="{{ route('data-pengajuan.index') }}"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Data Pengajuan
                                Barang</a></li>
                        <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Breadcrumb</a></li>
                        <li class="nav-item"><a class="nav-link" href="base/cards.html"><span class="nav-icon"><span
                                        class="nav-icon-bullet"></span></span> Cards</a></li>
                        <li class="nav-item"><a class="nav-link" href="base/carousel.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Carousel</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-cursor') }}"></use>
                        </svg> Buttons</a>
                    <ul class="nav-group-items compact">
                        <li class="nav-item"><a class="nav-link" href="buttons/buttons.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons</a></li>
                        <li class="nav-item"><a class="nav-link" href="buttons/button-group.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons Group</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span> Dropdowns</a></li>
                    </ul>
                </li>
            @endif
        @endif
    @endif

</ul>
<div class="sidebar-footer border-top d-none d-md-flex">
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
