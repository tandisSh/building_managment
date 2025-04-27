@php
    $user = auth()->user();
    $roles = $user->roles->pluck('name'); // لیست همه نقش‌های کاربر

    // وضعیت درخواست ساختمان برای مدیر
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
            {{-- بررسی نقش ادمین --}}
            @if ($roles->contains('super_admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('super-admin/dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('super_admin.dashboard') }}">
                        <i class="bi bi-shield-lock me-2"></i> داشبورد ادمین
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('super-admin/requests') ? 'active fw-bold' : '' }}"
                        href="{{ route('super_admin.requests') }}">
                        <i class="bi bi-clipboard-check me-2"></i> درخواست‌ها
                    </a>
                </li>
            @endif

            {{-- بررسی نقش مدیر --}}
            @if ($roles->contains('manager'))
                {{-- همیشه فعال --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('manager/dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('manager.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> داشبورد
                    </a>
                </li>

                {{-- ثبت درخواست ساختمان  --}}
                @if ($roles->contains('manager') && !in_array($buildingRequestStatus, ['pending', 'approved']))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('manager/buildings/create') ? 'active fw-bold' : '' }}"
                            href="{{ route('manager.buildings.create') }}">
                            <i class="bi bi-building-add me-2"></i> ثبت ساختمان
                        </a>
                    </li>
                @endif

                {{-- سایر گزینه‌ها بسته به وضعیت درخواست --}}

                <li class="nav-item">
                    @php
                        $building = \App\Models\Building::where('manager_id', auth()->id())->first();
                    @endphp
                    @if ($building)
                        <a class="nav-link" href="{{ route('units.index', $building->id) }}">
                            <i class="bi bi-houses me-2"></i> اطلاعات واحدها
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-houses me-2"></i> اطلاعات واحدها
                        </span>
                    @endif
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $buildingRequestStatus !== 'approved' ? 'disabled' : '' }}" href="#">
                        <i class="bi bi-people me-2"></i> ساکنین
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ $buildingRequestStatus !== 'approved' ? 'disabled' : '' }}" href="#">
                        <i class="bi bi-gear me-2"></i> تنظیمات
                    </a>
                </li>

                {{-- وضعیت درخواست نمایش داده شود --}}
                @if ($buildingRequestStatus === 'pending')
                    <li class="nav-item px-3 small mt-2 text-warning">
                        <i class="bi bi-clock-history me-2"></i> در انتظار تایید ادمین...
                    </li>
                @elseif($buildingRequestStatus === 'rejected')
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
