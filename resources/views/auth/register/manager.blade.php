<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام مدیر | سیستم مدیریت ساختمان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
</head>

<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card register-card shadow-lg text-center p-4">
        <div class="card-header bg-gradient-purple text-white mb-4">
            <h4><i class="bi bi-person-plus"></i> ثبت نام مدیر جدید</h4>
        </div>
        <div class="card-body text-start">
            <form method="POST" action="{{ route('register.manager') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">نام کامل</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">شماره موبایل</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-purple w-100 py-2">
                    <i class="bi bi-person-plus"></i> ثبت نام مدیر
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
