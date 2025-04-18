<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | سیستم مدیریت ساختمان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #4e73df;
            --sidebar-bg: #2c3e50;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Vazir', sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            right: 0;
        }

        .main-content {
            margin-right: var(--sidebar-width);
            padding: 20px;
        }

        .nav-link {
            color: #ecf0f1;
            padding: 12px 15px;
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border: none;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('layouts.sidebar')

        <div class="main-content w-100">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
