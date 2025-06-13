<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت ساختمان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body>
@guest
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card welcome-card text-center shadow-lg p-4">
        <div class="card-header bg-gradient text-dark mb-4">
            <h3> خوش آمدید!</h3>
        </div>
        <div class="card-body">
            <p class="lead mb-4">برای ادامه لطفاً وارد سیستم شوید یا ثبت‌نام کنید.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> ورود به سیستم
                </a>
                <a href="{{ route('register.manager') }}" class="btn btn-register">
                    <i class="bi bi-person-plus"></i> ثبت‌نام مدیر
                </a>
            </div>
        </div>
    </div>
</div>
@else
<script>
    window.location.href = "@auth {{
        auth()->user()->hasRole('super_admin') ? route('superadmin.dashboard') :
        (auth()->user()->hasRole('manager') ? route('manager.dashboard') : route('resident.dashboard'))
    }} @endauth";
</script>
@endguest
</body>
</html>
