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
        <ul class="nav flex-column flex-grow-1 custom-scroll p-0" style="overflow-y: auto; max-height: calc(100vh - 200px);">
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
                        <i class="bi bi-clipboard-check me-2"></i> <span>ساختمان ها</span>
                    </a>
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
                           href="#invoiceSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'true' : 'false' }}"
                           aria-controls="invoiceSubmenu">
                            <i class="bi bi-receipt me-2"></i> <span>صورتحساب‌ها</span>
                        </a>
                        <div class="collapse {{ request()->routeIs('manager.invoices.*') || request()->routeIs('manager.bulk_invoices.*') ? 'show' : '' }}" id="invoiceSubmenu">
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
                           href="#reportSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
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
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش بدهی</span>
                                    </a>
                                </li>
                                 <li>
                                    <a class="nav-link sub-nav-link {{ request()->routeIs('reports.overduePayments') ? 'active fw-bold' : '' }}"
                                       href="{{ route('reports.unit_debts') }}">
                                        <i class="bi bi-currency-dollar me-2"></i> <span>گزارش بدهی</span>
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
                       href="#residentInvoiceSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('resident.invoices.*') ? 'true' : 'false' }}"
                       aria-controls="residentInvoiceSubmenu">
                        <i class="bi bi-receipt me-2"></i> <span>صورتحساب‌ها</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('resident.invoices.*') ? 'show' : '' }}" id="residentInvoiceSubmenu">
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
            <li class="nav-item mt-auto py-3">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const pageContentWrapper = document.getElementById('page-content-wrapper');
    const customScroll = document.querySelector('.custom-scroll');

    if (!sidebar || !sidebarToggle || !pageContentWrapper || !customScroll) {
        console.log('One or more required elements not found');
        return;
    }

    // تنظیم نوار اسکرول فقط برای سایدبار
    function setupScrollArea() {
        customScroll.style.maxHeight = 'calc(100vh - 200px)'; // تنظیم ارتفاع بر اساس هدر و یوزر اینفو
        customScroll.style.overflowY = 'auto';
        customScroll.style.overflowX = 'hidden';
        document.body.style.overflow = 'auto'; // اطمینان از عدم اسکرول کل صفحه
    }

    // بارگذاری حالت سایدبار
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        pageContentWrapper.classList.add('collapsed');
    }

    // رویداد کلیک برای باز و بسته کردن سایدبار
    sidebarToggle.addEventListener('click', function() {
        const currentCollapsedState = sidebar.classList.contains('collapsed');

        // بستن منوهای باز
        document.querySelectorAll('.sidebar .nav-item.dropdown .collapse.show').forEach(openCollapse => {
            const bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
            if (bsCollapse) bsCollapse.hide();
        });

        sidebar.classList.toggle('collapsed');
        pageContentWrapper.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', !currentCollapsedState);
    });

    // مدیریت منوهای کشویی
    document.querySelectorAll('.sidebar .nav-item.dropdown').forEach(function(dropdownItem) {
        const dropdownToggle = dropdownItem.querySelector('.dropdown-toggle');
        const collapseElement = dropdownItem.querySelector('.collapse');

        if (!collapseElement || !dropdownToggle) return;

        const bsCollapse = new bootstrap.Collapse(collapseElement, { toggle: false });

        dropdownToggle.addEventListener('click', function(e) {
            if (sidebar.classList.contains('collapsed')) {
                e.preventDefault();
                const rect = dropdownToggle.getBoundingClientRect();
                collapseElement.style.top = `${rect.top}px`;
                collapseElement.style.right = `${sidebar.offsetWidth}px`;

                document.querySelectorAll('.sidebar .nav-item.dropdown .collapse.show').forEach(openCollapse => {
                    if (openCollapse !== collapseElement) {
                        const bsOpenCollapse = bootstrap.Collapse.getInstance(openCollapse);
                        if (bsOpenCollapse) bsOpenCollapse.hide();
                    }
                });

                bsCollapse.toggle();
            } else {
                collapseElement.style.top = '';
                collapseElement.style.right = '';
            }
        });

        collapseElement.addEventListener('hidden.bs.collapse', function() {
            if (!sidebar.classList.contains('collapsed')) {
                this.style.top = '';
                this.style.right = '';
            }
        });
    });

    // بستن منوهای کشویی با کلیک خارج از سایدبار
    document.addEventListener('click', function(e) {
        if (sidebar.classList.contains('collapsed')) {
            let clickedInsideDropdown = false;
            document.querySelectorAll('.sidebar .nav-item.dropdown').forEach(dropdownItem => {
                if (dropdownItem.contains(e.target)) clickedInsideDropdown = true;
            });

            if (!clickedInsideDropdown && !sidebar.contains(e.target)) {
                document.querySelectorAll('.sidebar .nav-item.dropdown .collapse.show').forEach(openCollapse => {
                    const bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
                    if (bsCollapse) bsCollapse.hide();
                });
            }
        }
    });

    // مقداردهی اولیه نوار اسکرول
    setupScrollArea();
    window.addEventListener('resize', setupScrollArea);
});
</script>
@endpush
