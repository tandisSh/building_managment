<!DOCTYPE html>
<html lang="fa" dir="rtl">
@include('layouts.head')

<body>
    <div class="d-flex" id="wrapper"> {{-- wrapper برای کنترل کل طرح‌بندی --}}

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="main-content flex-grow-1" id="page-content-wrapper">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

</body>

</html>
