<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">
        <svg class="nav-icon">
            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-speedometer') }}">
            </use>
        </svg> Dashboard</a></li>
<li class="nav-item"><a class="nav-link {{ request()->is('admin/users-management*') ? 'active' : '' }}"
        href="{{ route('users-management.index') }}">
        <svg class="nav-icon">
            <use xlink:href="{{ asset('modules/coreui/icons/sprites/free.svg#cil-apps') }}"></use>
        </svg> User Manajemen</a></li>
<li class="nav-item"><a class="nav-link {{ request()->is('admin/stationeries*') ? 'active' : '' }}"
        href="{{ route('stationeries.index', ['type' => '1']) }}">
        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-box-seam" viewBox="0 0 16 16">
            <path
                d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
        </svg> Data Stok Barang</a></li>
{{-- <li class="nav-group {{ request()->is('admin/stationeries*') ? 'show' : '' }}">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-box-seam" viewBox="0 0 16 16">
            <path
                d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
        </svg> Data Stok Barang
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link {{ request()->get('type') === '1' ? 'active' : '' }}"
                href="{{ route('stationeries.index', ['type' => '1']) }}">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Alat Tulis & Form
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('type') === '2' ? 'active' : '' }}"
                href="{{ route('stationeries.index', ['type' => '2']) }}">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Perlengkapan Lainnya
            </a>
        </li>
    </ul>
</li> --}}

<li class="nav-group {{ request()->is('admin/demand*', 'admin/list_demands*') ? 'show' : '' }}">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-journal-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0" />
            <path
                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
            <path
                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
        </svg> Permintaan Barang
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/demand*') ? 'active' : '' }}"
                href="{{ route('demand.index') }}">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Data Permintaan
                Barang
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/list_demands*') ? 'active' : '' }}"
                href="{{ route('list_demands.index') }}">
                <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Data Barang Keluar
            </a>
        </li>
    </ul>
</li>
