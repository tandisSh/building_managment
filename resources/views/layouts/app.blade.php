<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | سیستم مدیریت ساختمان</title>

    <!-- CSS ها -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- استایل های سفارشی -->
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-bg: #2c3e50;
            --active-bg: #3498db;
        }

        body {
            font-family: 'Vazir', 'Segoe UI', Tahoma, sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--primary-bg);
            position: fixed;
            top: 56px; /* ارتفاع نوار نویگیشن */
            right: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 1000;
        }

        .main-content {
            margin-right: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        .nav-link {
            color: #ecf0f1 !important;
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-5px);
        }

        .nav-link.active {
            background: var(--active-bg) !important;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .sidebar {
                right: -100%;
            }

            .sidebar.show {
                right: 0;
            }

            .main-content {
                margin-right: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">
    <!-- نوار نویگیشن -->
    @include('layouts.nav')

    <div class="container-fluid">
        <div class="row">
            <!-- سایدبار -->
            @auth
                @include('layouts.sidebar')
            @endauth

            <!-- محتوای اصلی -->
            <main class="col-md-9 ms-sm-auto px-md-4 py-3 main-content">
                <!-- نمایش پیام های سیستمی -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- اسکریپت ها -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // کنترل نمایش/مخفی کردن سایدبار در موبایل
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
