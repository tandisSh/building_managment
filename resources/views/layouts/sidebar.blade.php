<div class="sidebar">
    <div class="p-4">
        <div class="text-center mb-4 text-white">
            <h4>سیستم مدیریت ساختمان</h4>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-house-door me-2"></i>
                    خانه
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-building me-2"></i>
                    درخواست ساختمان
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-list-ul me-2"></i>
                    لیست ساختمان‌ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people me-2"></i>
                    مدیریت ساکنین
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-file-text me-2"></i>
                    گزارشات
                </a>
            </li>
            <li class="nav-item mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start bg-transparent border-0">
                        <i class="bi bi-box-arrow-left me-2"></i>
                        خروج از حساب
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
