<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768) {
            const sidebar = document.querySelector('.main-sidebar');
            const sidebarToggle = document.querySelector('[onclick="toggleSidebar()"]');

            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                document.body.classList.remove('sidebar-active');
            }
        }
    });
</script>
