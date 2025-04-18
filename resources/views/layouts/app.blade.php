<!DOCTYPE html>
<html lang="fa" dir="rtl">
@include('layouts.head')

<body>
    <div class="d-flex">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-grow-1" style="margin-right: 260px; padding: 20px;">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
