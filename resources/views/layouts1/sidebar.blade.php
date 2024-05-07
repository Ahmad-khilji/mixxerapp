<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="background: #EEEAE5 !important">
    <div class="app-brand demo">
        <a class="app-brand-link">

            <span class="app-brand-text demo menu-text fw-bold"><img src="/assets/img/mixxer_logo.png" alt="" style="width: 50% !important"></span>
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
                <div data-i18n="Contact Us Categories">User</div>
            </a>
        </li>
    
        <!-- Help & Supports -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Help & Supports</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-ticket-') ? 'active' : '' }}">
            <a href="{{ route('dashboard-ticket-') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-circle"></i>
                <div data-i18n="Contact Us Categories">Active Ticket</div>
            </a>
        </li>
        

        
    </ul>
    
</aside>
