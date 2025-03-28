<li class="nav-item">
    <a class="nav-link" href="{{ route('user.dashboard') }}">
        <svg class="nav-icon">
            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-speedometer') }}">
            </use>
        </svg> Dashboard
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->is('user/item-demand*') ? 'active' : '' }}" href="{{ route('item-demand.index') }}">
        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-bag-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0" />
            <path
                d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
        </svg> Data Pengajuan Barang
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->is('user/print*') ? 'active' : '' }}" href="{{ route('print.index') }}">
        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-file-earmark-text" viewBox="0 0 16 16">
            <path
                d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
            <path
                d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
        </svg> Cetak Data Pengajuan
    </a>
</li>
{{-- <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-puzzle') }}"></use>
        </svg> Base</a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('user/item-demand*') ? 'active' : '' }}"
                href="{{ route('item-demand.index') }}">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Data Pengajuan
                Barang
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="base/breadcrumb.html">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Breadcrumb
            </a>
        </li>
    </ul>
</li>
<li class="nav-title">Components</li>
<li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-cursor') }}"></use>
        </svg> Buttons</a>
    <ul class="nav-group-items compact">
        <li class="nav-item"><a class="nav-link" href="buttons/buttons.html"><span class="nav-icon"><span
                        class="nav-icon-bullet"></span></span> Buttons</a></li>
        <li class="nav-item"><a class="nav-link" href="buttons/button-group.html"><span class="nav-icon"><span
                        class="nav-icon-bullet"></span></span> Buttons Group</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html"><span class="nav-icon"><span
                        class="nav-icon-bullet"></span></span> Dropdowns</a></li>
    </ul>
</li> --}}
