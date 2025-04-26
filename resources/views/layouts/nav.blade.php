<nav class="navbar navbar-expand navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-building"></i> سیستم مدیریت ساختمان
        </a>

        <div class="d-flex align-items-center">
            @auth
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        <span class="me-2">{{ auth()->user()->name }}</span>
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">پروفایل</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">خروج</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">ورود</a>
                <a href="{{ route('register.manager') }}" class="btn btn-primary">ثبت نام مدیر</a>
            @endauth
        </div>
    </div>
</nav>
