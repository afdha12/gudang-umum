<div class="sidebar-header border-bottom">
    <div class="sidebar-brand d-flex align-items-center">
        <img src="{{ asset('assets/icons/hermina_logo.png') }}" class="sidebar-brand-full" width="28" height="32"
            alt="CoreUI Logo">
        <img src="{{ asset('assets/icons/hermina_logo.png') }}" class="sidebar-brand-narrow" width="28" height="32"
            alt="CoreUI Logo">

        @if (Auth::check())
            @php
                $nameParts = explode(' ', Auth::user()->name);
                $firstTwo = implode(' ', array_slice($nameParts, 0, 2));
            @endphp
            <span class="sidebar-brand-full fw-semibold text-white text-capitalize ms-3">
                Hi, {{ $firstTwo }}
            </span>
        @endif

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
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                        <path
                            d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5" />
                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                    </svg> Ganti Password</a></li>
        @else
            @if ($role === 'admin')
                @include('layouts.menu.admin-menu')
            @elseif ($role === 'manager')
                @include('layouts.menu.manager-menu')
            @elseif ($role === 'user')
                @include('layouts.menu.user-menu')
            @elseif ($role === 'coo')
                @include('layouts.menu.coo-menu')
            @endif
        @endif
    @endif

</ul>
<div class="sidebar-footer border-top d-none d-md-flex">
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
