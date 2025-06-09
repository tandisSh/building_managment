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

                {{-- اطلاعات ساختمان --}}
                <li class="nav-item">
                    @if ($building)
                        <a class="nav-link {{ request()->routeIs('manager.building.show') ? 'active fw-bold' : '' }}"
                            href="{{ route('manager.building.show', $building->id) }}">
                            <i class="bi bi-houses me-2"></i> اطلاعات ساختمان
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-houses me-2"></i> اطلاعات ساختمان
                        </span>
                    @endif
                </li>

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

                {{-- صورتحساب‌ها --}}
                @if ($building && $buildingRequestStatus === 'approved')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'active fw-bold' : '' }}"
                            href="#" id="invoiceDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-file-text me-2"></i> صورتحساب‌ها
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="invoiceDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('manager.invoices.index') ? 'active fw-bold' : '' }}"
                                    href="{{ route('manager.invoices.index') }}">
                                    صورتحساب‌ها
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('bulk_invoices.index') ? 'active fw-bold' : '' }}"
                                    href="{{ route('bulk_invoices.index') }}">
                                    صورتحساب‌های کلی
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <span class="nav-link disabled">
                            <i class="bi bi-file-text me-2"></i> صورتحساب‌ها
                        </span>
                    </li>
                @endif

                {{-- پرداخت ها --}}
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('payments.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('payments.index', $building->id) }}">
                            <i class="bi bi-people me-2"></i> پرداخت‌ها
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-people me-2"></i> پرداخت‌ها
                        </span>
                    @endif
                </li>

                {{-- درخواست ها --}}
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('requests.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('requests.index') }}">
                            <i class="bi bi-people me-2"></i> درخواست‌ها
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-people me-2"></i> درخواست ها
                        </span>
                    @endif
                </li>

                {{-- گزارشات --}}
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link" href="{{route('reports.payments')}}">
                            <i class="bi bi-gear me-2"></i> گزارش پرداخت‌ها
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-gear me-2"></i> گزارش پرداخت‌ها
                        </span>
                    @endif
                </li>

                  <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link" href="{{route('reports.invoices')}}">
                            <i class="bi bi-gear me-2"></i> گزارش صورتحساب
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-gear me-2"></i> گزارش صورتحساب
                        </span>
                    @endif
                </li>

                     <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link" href="{{route('reports.unit_debts')}}">
                            <i class="bi bi-gear me-2"></i> گزارش بدهی
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-gear me-2"></i> گزارش بدهی
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

            {{-- ساکن --}}
            @if ($roles->contains('resident'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.dashboard') }}">
                        <i class="bi bi-house-door me-2"></i> داشبورد ساکن
                    </a>
                </li>

                {{-- پروفایل ساکن --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.profile.show') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.profile.show') }}">
                        <i class="bi bi-person me-2"></i> پروفایل
                    </a>
                </li>

                {{-- صورتحساب‌ها --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="invoiceDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        صورتحساب‌ها
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="invoiceDropdown">
                        <li><a class="dropdown-item" href="{{ route('resident.invoices.index') }}">لیست صورتحساب‌ها</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('resident.invoices.unpaid') }}">پرداخت گروهی</a>
                        </li>
                    </ul>
                </li>


                {{-- پرداخت‌ها --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.payments.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.payments.index') }}">
                        <i class="bi bi-credit-card me-2"></i> پرداخت‌ها
                    </a>
                </li>

                {{-- درخواست‌ها --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.requests.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.requests.index') }}">
                        <i class="bi bi-clipboard-list me-2"></i> درخواست‌ها
                    </a>
                </li>
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
