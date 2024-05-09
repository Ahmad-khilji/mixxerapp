<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="background: #EEEAE5 !important">
    <div class="app-brand demo">
        <a class="app-brand-link">

            <span class="app-brand-text demo menu-text fw-bold"><img src="/assets/img/mixxer_logo.png" alt=""
                    style="width: 50% !important"></span>
        </a>

        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a> --}}
    </div>
    <div class="brandborder">

    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Dashboard</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Statistics">Statistics</div>
            </a>
        </li>

        <!-- User Management -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">User Management</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-user-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-user-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Contact Us Categories">User List</div>
            </a>
        </li>


        {{-- ticket --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Help & Supports</span>
        </li>
        <li
            class="menu-item {{ Request::url() == route('dashboard-ticket-ticket', 'active') ? 'active' : '' }} || {{ Str::contains(Request::url(), 'dashboard/ticket/active/messages') ? 'active' : '' }}">
            <a href="{{ route('dashboard-ticket-ticket', 'active') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div> Active Tickets </div>

            </a>
        </li>
        <li
            class="menu-item {{ Request::url() == route('dashboard-ticket-ticket', 'close') ? 'active' : '' }} || {{ Str::contains(Request::url(), 'dashboard/ticket/close/messages') ? 'active' : '' }}">
            <a href="{{ route('dashboard-ticket-ticket', 'close') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>

                <div>Close Tickets</div>
            </a>
        </li>


        {{-- faqs --}}
        <li class="menu-item {{ Request::url() == route('dashboard-faqs-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-faqs-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="FAQ'S">FAQ'S</div>
            </a>
        </li>

        {{-- reports --}}

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text"> Reported Requests</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-report-', 'user') ? 'active' : '' }}">
            <a href="{{ route('dashboard-report-', 'user') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="User">Reported Accounts</div>

            </a>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-report-', 'post') ? 'active' : '' }}">
            <a href="{{ route('dashboard-report-', 'post') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="User">Reported Posts</div>

            </a>
        </li>


    </ul>

</aside>
