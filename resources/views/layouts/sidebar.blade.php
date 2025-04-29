@php
    $user = auth()->user();
    $roles = $user->roles->pluck('name');
    $building = \App\Models\Building::where('manager_id', $user->id)->first();

    // اگر مدیر بود، آخرین وضعیت درخواست ساختمانش
    $buildingRequestStatus = null;
    if ($roles->contains('manager')) {
        $latestRequest = \App\Models\BuildingRequest::where('user_id', $user->id)->latest()->first();
        $buildingRequestStatus = $latestRequest->status ?? null;
    }
@endphp

<div class="sidebar">
    <div class="p-4">
        <div class="text-center mb-4">
            <h4>پنل کاربری</h4>
        </div>

        <ul class="nav flex-column">
            {{-- سوپر ادمین --}}
            @if ($roles->contains('super_admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super_admin.dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('super_admin.dashboard') }}">
                        <i class="bi bi-shield-lock me-2"></i> داشبورد ادمین
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super_admin.requests') ? 'active fw-bold' : '' }}"
                       href="{{ route('super_admin.requests') }}">
                        <i class="bi bi-clipboard-check me-2"></i> درخواست‌ها
                    </a>
                </li>
            @endif

            {{-- مدیر ساختمان --}}
            @if ($roles->contains('manager'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('manager.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> داشبورد
                    </a>
                </li>

                {{-- ثبت ساختمان (فقط وقتی تایید نشده) --}}
                @if (!in_array($buildingRequestStatus, ['pending', 'approved']))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('manager.buildings.create') ? 'active fw-bold' : '' }}"
                           href="{{ route('manager.buildings.create') }}">
                            <i class="bi bi-building-add me-2"></i> ثبت ساختمان
                        </a>
                    </li>
                @endif

                {{-- اطلاعات واحدها --}}
                <li class="nav-item">
                    @if ($building)
                        <a class="nav-link {{ request()->routeIs('units.index') ? 'active fw-bold' : '' }}"
                           href="{{ route('units.index', $building->id) }}">
                            <i class="bi bi-houses me-2"></i> اطلاعات واحدها
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-houses me-2"></i> اطلاعات واحدها
                        </span>
                    @endif
                </li>

                {{-- ساکنین --}}
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('residents.index') ? 'active fw-bold' : '' }}"
                           href="{{ route('residents.index', $building->id) }}">
                            <i class="bi bi-people me-2"></i> ساکنین
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-people me-2"></i> ساکنین
                        </span>
                    @endif
                </li>

                {{-- تنظیمات --}}
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link" href="#">
                            <i class="bi bi-gear me-2"></i> تنظیمات
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-gear me-2"></i> تنظیمات
                        </span>
                    @endif
                </li>

                {{-- پیام وضعیت درخواست --}}
                @if ($buildingRequestStatus === 'pending')
                    <li class="nav-item px-3 small mt-2 text-warning">
                        <i class="bi bi-clock-history me-2"></i> در انتظار تایید ادمین...
                    </li>
                @elseif ($buildingRequestStatus === 'rejected')
                    <li class="nav-item px-3 small mt-2 text-danger">
                        <i class="bi bi-x-circle me-2"></i> درخواست رد شده!
                    </li>
                @endif
            @endif

            {{-- خروج --}}
            <li class="nav-item mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start bg-transparent border-0">
                        <i class="bi bi-box-arrow-left me-2"></i> خروج
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
