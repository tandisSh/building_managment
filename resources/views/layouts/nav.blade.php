<!-- resources/views/layouts/nav.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">مدیریت ساختمان</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">ورود</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">ثبت‌نام مدیر</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
