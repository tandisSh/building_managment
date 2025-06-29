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

    <script>
        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const pageContent = document.getElementById('page-content-wrapper');
            
            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            // Apply initial state
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                pageContent.classList.add('collapsed');
            }
            
            // Toggle sidebar when button is clicked
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                pageContent.classList.toggle('collapsed');
                
                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                // Optional: Add animation class for smooth transition
                sidebar.style.transition = 'all 0.3s ease-in-out';
                pageContent.style.transition = 'all 0.3s ease-in-out';
            });
            
            // Handle responsive behavior for mobile devices
            function handleResize() {
                if (window.innerWidth <= 768) {
                    // On mobile, always start with collapsed sidebar
                    if (!sidebar.classList.contains('collapsed')) {
                        sidebar.classList.add('collapsed');
                        pageContent.classList.add('collapsed');
                        localStorage.setItem('sidebarCollapsed', 'true');
                    }
                }
            }
            
            // Call on page load and resize
            handleResize();
            window.addEventListener('resize', handleResize);
        });
    </script>

    @stack('scripts')

</body>

</html>
