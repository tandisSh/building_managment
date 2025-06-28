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

<div class="sidebar" id="sidebar"> <!-- اضافه کردن ID برای دسترسی با JS -->
    <div class="d-flex flex-column h-100">
        <!-- Sidebar Header -->
        <div class="text-center mb-3 sidebar-header position-relative p-4">
            <h6 class="text-dark-blue mb-0">پنل مدیریت ساختمان</h6>

            <!-- دکمه برای باز و بسته کردن سایدبار داخل سایدبار -->
            <button class="btn toggle-sidebar-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <!-- User Info -->
        <div class="user-info text-center mb-3 pb-2 border-bottom px-4">
            <i class="bi bi-person-circle user-icon-small d-block mb-1"></i>
            <small class="mb-0 text-dark-blue fw-bold">{{ $user->name ?? 'کاربر مهمان' }}</small>
            <br><small class="text-muted text-truncate d-block">{{ $user->email ?? '' }}</small>
        </div>

        <!-- Sidebar Nav Links - This is the scrollable area -->
        <ul class="nav flex-column flex-grow-1 custom-scroll p-0">
            <!-- سوپر ادمین -->
            @if ($roles->contains('super_admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> <span>داشبورد ادمین</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.requests') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.requests') }}">
                        <i class="bi bi-clipboard-check me-2"></i> <span>درخواست‌ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.buildings.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.buildings.index') }}">
                        <i class="bi bi- bi-building me-2"></i> <span>ساختمان ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.building_managers.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.building_managers.index') }}">
                        <i class="bi bi-person me-2"></i> <span> مدیران ساختمان </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.users.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.users.index') }}">
                        <i class="bi bi-people me-2"></i> <span>  کاربران </span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.invoices.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.invoices.index') }}">
                        <i class="bi bi-receipt me-2"></i> <span>  صورتحساب‌ها </span>
                    </a>
                </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.payments.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('superadmin.payments.index') }}">
                        <i class="bi bi-credit-card me-2"></i> <span>  پرداخت‌ها </span>
                    </a>
                </li>
                    <!-- گزارشات (سرگروه) -->
                <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active fw-bold' : '' }}"
                            href="#reportSubmenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
                            aria-controls="reportSubmenu">
                            <i class="bi bi-graph-up-arrow me-2"></i> <span>گزارشات</span>
                        </a>
                        <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportSubmenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.overall_payments') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.overall_payments') }}">
                                        <i class="bi bi-file-text me-2"></i> <span>گزارش پرداخت‌ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.building_performance') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.building_performance') }}">
                                        <i class="bi bi-graph-up me-2"></i> <span>عملکرد ساختمان‌ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.user_activity') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.user_activity') }}">
                                        <i class="bi bi-people-fill me-2"></i> <span>فعالیت کاربران</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.building_requests') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.building_requests') }}">
                                        <i class="bi bi-building-add me-2"></i> <span>درخواست‌های ساختمان</span>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.aggregate_invoices') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.aggregate_invoices') }}">
                                        <i class="bi bi-journal-check me-2"></i> <span>گزارش صورتحساب</span>
                                    </a>
                                </li> --}}
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.system_debts') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.system_debts') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span > گزارش بدهی سیستم</span>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.overdue_payments') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.overdue_payments') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش بدهی تاریخ گذشته</span>
                                    </a>
                                </li> --}}
                                  {{-- <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('superadmin.reports.annual_summary') ? 'active fw-bold' : '' }}"
                                        href="{{ route('superadmin.reports.annual_summary') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش  سالانه</span>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                </li>
            @endif

            <!-- مدیر ساختمان -->
            @if ($roles->contains('manager'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('manager.dashboard') }}">
                        <i class="bi bi-house-door me-2"></i> <span>داشبورد</span>
                    </a>
                </li>

                <!-- ثبت ساختمان (فقط وقتی تایید نشده) -->
                @if (!in_array($buildingRequestStatus, ['pending', 'approved']))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('manager.buildings.create') ? 'active fw-bold' : '' }}"
                            href="{{ route('manager.buildings.create') }}">
                            <i class="bi bi-building-add me-2"></i> <span>ثبت ساختمان</span>
                        </a>
                    </li>
                @endif

                <!-- اطلاعات ساختمان -->
                <li class="nav-item">
                    @if ($building)
                        <a class="nav-link {{ request()->routeIs('manager.building.show') ? 'active fw-bold' : '' }}"
                            href="{{ route('manager.building.show', $building->id) }}">
                            <i class="bi bi-building me-2"></i> <span>اطلاعات ساختمان</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-building me-2"></i> <span>اطلاعات ساختمان</span>
                        </span>
                    @endif
                </li>

                <!-- اطلاعات واحدها -->
                <li class="nav-item">
                    @if ($building)
                        <a class="nav-link {{ request()->routeIs('units.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('units.index', $building->id) }}">
                            <i class="bi bi-door-open me-2"></i> <span>اطلاعات واحدها</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-door-open me-2"></i> <span>اطلاعات واحدها</span>
                        </span>
                    @endif
                </li>

                <!-- ساکنین -->
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('residents.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('residents.index', $building->id) }}">
                            <i class="bi bi-people me-2"></i> <span>ساکنین</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-people me-2"></i> <span>ساکنین</span>
                        </span>
                    @endif
                </li>

                <!-- صورتحساب‌ها (سرگروه) -->
                <li class="nav-item dropdown">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'active fw-bold' : '' }}"
                            href="#invoiceSubmenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'true' : 'false' }}"
                            aria-controls="invoiceSubmenu">
                            <i class="bi bi-receipt me-2"></i> <span>صورتحساب‌ها</span>
                        </a>
                        <div class="collapse {{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'show' : '' }}"
                            id="invoiceSubmenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('manager.invoices.index') ? 'active fw-bold' : '' }}"
                                        href="{{ route('manager.invoices.index') }}">
                                        <i class="bi bi-journals me-2"></i> <span>صورتحساب‌های واحد</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('bulk_invoices.index') ? 'active fw-bold' : '' }}"
                                        href="{{ route('bulk_invoices.index') }}">
                                        <i class="bi bi-file-earmark-ruled me-2"></i> <span>صورتحساب‌های کلی</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-receipt me-2"></i> <span>صورتحساب‌ها</span>
                        </span>
                    @endif
                </li>

                <!-- پرداخت ها -->
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('payments.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('payments.index', $building->id) }}">
                            <i class="bi bi-credit-card me-2"></i> <span>پرداخت‌ها</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-credit-card me-2"></i> <span>پرداخت‌ها</span>
                        </span>
                    @endif
                </li>

                <!-- درخواست ها -->
                <li class="nav-item">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link {{ request()->routeIs('requests.index') ? 'active fw-bold' : '' }}"
                            href="{{ route('requests.index') }}">
                            <i class="bi bi-tools me-2"></i> <span>درخواست‌ها</span>
                        </a>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-tools me-2"></i> <span>درخواست‌ها</span>
                        </span>
                    @endif
                </li>

                <!-- گزارشات (سرگروه) -->
                <li class="nav-item dropdown">
                    @if ($building && $buildingRequestStatus === 'approved')
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active fw-bold' : '' }}"
                            href="#reportSubmenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
                            aria-controls="reportSubmenu">
                            <i class="bi bi-graph-up-arrow me-2"></i> <span>گزارشات</span>
                        </a>
                        <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportSubmenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.payments') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.payments') }}">
                                        <i class="bi bi-file-text me-2"></i> <span>گزارش پرداخت‌ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.invoices') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.invoices') }}">
                                        <i class="bi bi-journal-check me-2"></i> <span>گزارش صورتحساب</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.unit_debts') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.unit_debts') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش بدهی واحدها</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.overduePayments') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.overduePayments') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش بدهی تاریخ گذشته</span>
                                    </a>
                                </li>
                                 <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.financialOverview') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.financialOverview') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش مالی ماهانه</span>
                                    </a>
                                </li>
                                 <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.ResidentAccountStatus') ? 'active fw-bold' : '' }}"
                                        href="{{ route('reports.ResidentAccountStatus') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش وضعیت حساب ساکنین</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <span class="nav-link disabled">
                            <i class="bi bi-graph-up-arrow me-2"></i> <span>گزارشات</span>
                        </span>
                    @endif
                </li>

                <!-- پیام وضعیت درخواست -->
                @if ($buildingRequestStatus === 'pending')
                    <li class="nav-item px-3 small mt-2 text-warning">
                        <i class="bi bi-clock-history me-2"></i> <span>در انتظار تایید ادمین...</span>
                    </li>
                @elseif ($buildingRequestStatus === 'rejected')
                    <li class="nav-item px-3 small mt-2 text-danger">
                        <i class="bi bi-x-circle me-2"></i> <span>درخواست رد شده!</span>
                    </li>
                @endif
            @endif

            <!-- ساکن -->
            @if ($roles->contains('resident'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.dashboard') }}">
                        <i class="bi bi-house-door me-2"></i> <span>داشبورد ساکن</span>
                    </a>
                </li>

                <!-- پروفایل ساکن -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.profile.show') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.profile.show') }}">
                        <i class="bi bi-person me-2"></i> <span>پروفایل</span>
                    </a>
                </li>

                <!-- صورتحساب‌ها (سرگروه) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('resident.invoices.*') ? 'active fw-bold' : '' }}"
                        href="#residentInvoiceSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('resident.invoices.*') ? 'true' : 'false' }}"
                        aria-controls="residentInvoiceSubmenu">
                        <i class="bi bi-receipt me-2"></i> <span>صورتحساب‌ها</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('resident.invoices.*') ? 'show' : '' }}"
                        id="residentInvoiceSubmenu">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li>
                                <a class="nav-link sub-nav-link {{ request()->routeIs('resident.invoices.index') ? 'active fw-bold' : '' }}"
                                    href="{{ route('resident.invoices.index') }}">
                                    <i class="bi bi-journals me-2"></i> <span>لیست صورتحساب‌ها</span>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link sub-nav-link {{ request()->routeIs('resident.invoices.unpaid') ? 'active fw-bold' : '' }}"
                                    href="{{ route('resident.invoices.unpaid') }}">
                                    <i class="bi bi-credit-card-fill me-2"></i> <span>پرداخت گروهی</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- پرداخت‌ها -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.payments.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.payments.index') }}">
                        <i class="bi bi-credit-card me-2"></i> <span>پرداخت‌ها</span>
                    </a>
                </li>

                <!-- درخواست‌ها -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resident.requests.index') ? 'active fw-bold' : '' }}"
                        href="{{ route('resident.requests.index') }}">
                        <i class="bi bi-tools me-2"></i> <span>درخواست‌ها</span>
                    </a>
                </li>
            @endif

            <!-- خروج -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start bg-transparent border-0 logout-btn">
                        <i class="bi bi-box-arrow-left me-2"></i> <span>خروج</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

