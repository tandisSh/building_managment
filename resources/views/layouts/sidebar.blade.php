<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            @auth
                @if(auth()->user()->roles->contains('name', 'manager'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}"
                       href="{{ route('manager.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        داشبورد
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('manager.buildings.*') ? 'active' : '' }}"
                       href="{{ route('manager.buildings.create') }}">
                        <i class="bi bi-building-add"></i>
                        درخواست ساختمان
                    </a>
                </li>
                @endif

                @if(auth()->user()->roles->contains('name', 'super_admin'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-shield-lock"></i>
                        مدیریت درخواست‌ها
                    </a>
                </li>
                @endif
            @endauth
        </ul>
    </div>
</div>
