<?php

/**
 * AR Entertainment - Admin Footer Partial
 */
?>
<!-- Footer -->
<footer class="mt-auto py-3 px-4 border-top" style="border-color: var(--ar-border-color) !important; background-color: var(--ar-sidebar-bg);">
    <div class="d-flex flex-wrap justify-content-between align-items-center text-muted small">
        <div>
            &copy; <?= date('Y') ?> <strong class="text-white"><?= SITE_NAME ?></strong> &mdash; All rights reserved.
        </div>
        <div>
            Built with Modular PHP &amp; MySQL &bull; v1.0.0
        </div>
    </div>
</footer>
</div> <!-- /admin-main -->
</div> <!-- /admin-wrapper -->

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle for mobile
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }
</script>
</body>

</html>