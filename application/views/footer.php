<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const site_url = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('#menu').metisMenu();
    });
    document.addEventListener('DOMContentLoaded', function() {
        var body = document.body;
        var sidebar = document.querySelector('.sidebar-wrapper');
        var toggleButton = document.querySelector('.mobile-toggle-menu');
        var closeButton = document.querySelector('.mobile-toggle-icon');
        var backdrop = document.querySelector('.mobile-sidebar-backdrop');
        var hoverSuspended = false;

        document.querySelectorAll('.top-menu .dropdown, .top-menu .dropdown-menu').forEach(function(el) {
            el.classList.remove('show');
        });
        document.body.classList.remove('sidebar-preload');

        function closeMobileSidebar() {
            body.classList.remove('mobile-sidebar-open');
        }

        function isDesktop() {
            return window.innerWidth > 991.98;
        }

        function clearDesktopHoverState() {
            body.classList.remove('sidebar-hover-open');
        }

        function toggleMobileSidebar(event) {
            if (event) {
                event.preventDefault();
            }

            if (!isDesktop()) {
                body.classList.toggle('mobile-sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
                clearDesktopHoverState();
                hoverSuspended = false;
            }
        }

        if (toggleButton) {
            toggleButton.addEventListener('click', toggleMobileSidebar);
        }

        if (closeButton) {
            closeButton.addEventListener('click', function(event) {
                event.preventDefault();
                if (!isDesktop()) {
                    closeMobileSidebar();
                    return;
                }

                if (body.classList.contains('sidebar-collapsed')) {
                    clearDesktopHoverState();
                    hoverSuspended = true;
                }
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', closeMobileSidebar);
        }

        if (sidebar) {
            sidebar.addEventListener('mouseenter', function() {
                if (isDesktop() && body.classList.contains('sidebar-collapsed') && !hoverSuspended) {
                    body.classList.add('sidebar-hover-open');
                }
            });

            sidebar.addEventListener('mouseleave', function() {
                if (isDesktop()) {
                    clearDesktopHoverState();
                    hoverSuspended = false;
                }
            });

            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (!isDesktop()) {
                        closeMobileSidebar();
                    }
                });
            });
        }


        window.addEventListener('resize', function() {
            if (isDesktop()) {
                closeMobileSidebar();
            } else {
                body.classList.remove('sidebar-collapsed');
            }

            clearDesktopHoverState();
            hoverSuspended = false;
        });

        document.querySelectorAll('.js-swal-delete').forEach(function(el) {
            el.addEventListener('click', function(event) {
                event.preventDefault();
                var href = this.getAttribute('href');
                var title = this.getAttribute('data-swal-title') || 'Delete item?';
                var text = this.getAttribute('data-swal-text') || 'This action cannot be undone.';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33'
                }).then(function(result) {
                    if (result.isConfirmed && href) {
                        window.location.href = href;
                    }
                });
            });
        });
    });

    // document.addEventListener("DOMContentLoaded", function() {

    //     let privacyMode = localStorage.getItem("privacyMode");

    //     if (privacyMode === "true") {
    //         updatePrivacyUI(true);

    //         let switchBtn = document.getElementById("privacySwitch");
    //         if (switchBtn) {
    //             switchBtn.checked = true;
    //         }
    //     }

    // });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            window.location.href = site_url + 'dashboard';
        }
    });

   document.addEventListener('keydown', function(event) {

    if (event.altKey) {
        event.preventDefault();
        window.location.href = site_url + 'sales/add';
    }

});
</script>
<?php
$flashSuccess = $this->session->flashdata('success');
$flashError = $this->session->flashdata('error');
?>
<?php if (!empty($flashSuccess) || !empty($flashError)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?= !empty($flashError) ? 'error' : 'success' ?>',
                title: '<?= !empty($flashError) ? 'Error' : 'Success' ?>',
                text: <?= json_encode(!empty($flashError) ? $flashError : $flashSuccess) ?>,
                confirmButtonText: 'OK'
            });
        });
    </script>
<?php endif; ?>
<?php
$routerClass = $this->router->class ?? '';
$routerMethod = $this->router->method ?? '';
$isDashboardPage = ($routerClass === 'dashboard' && $routerMethod === 'index');
?>
<?php if ($isDashboardPage): ?>
    <script src="<?= base_url('assets/js/index.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/peity/jquery.peity.min.js') ?>"></script>
    <script>
        $(".data-attributes span").peity("donut");
    </script>
<?php endif; ?>
</body>

</html>