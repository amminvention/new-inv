@php
    $configData = Helper::applClasses();
@endphp
<div
    class="main-menu menu-fixed {{(($configData['theme'] === 'dark') || ($configData['theme'] === 'semi-dark')) ? 'menu-dark' : 'menu-light'}} menu-accordion menu-shadow"
    data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{ asset('logo.png') }}" style="width: 183px;" alt="branding logo">
{{--                    <h6 class="brand-text">Investigation</h6>--}}
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                       data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item  ">
                <a href="{{ url('/') }}"
                   class="d-flex align-items-center" target="_self">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>
            <li class="nav-item  ">
                <a href="{{ route('admin.fir.index') }}"
                   class="d-flex align-items-center" target="_self">
                    <i data-feather="file-text"></i>
                    <span class="menu-title text-truncate">FIR List</span>
                </a>
            </li>
{{--            <li class="nav-item  ">--}}
{{--                <a href="{{ url('/report') }}"--}}
{{--                   class="d-flex align-items-center" target="_self">--}}
{{--                    <i data-feather="file-text"></i>--}}
{{--                    <span class="menu-title text-truncate">Reports</span>--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
