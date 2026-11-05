<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/ico">
    <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/pace.min.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/js/pace.min.js') ?>"></script>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/sass/app.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/sass/dark-theme.css') ?>">
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet">
    <title>Bills Book | Admin Login</title>
</head>

<body class="">
    <div class="wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg rounded-4" style="width:420px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="<?= base_url('assets/images/logo-icon.png') ?>" width="60">
                    <h4 class="mt-2">Syndron Admin</h4>
                    <p class="text-muted">Please log in to your account</p>
                </div>

                <form method="post" action="<?= base_url('login'); ?>">
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php } ?>

                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile Number">
                        <?= form_error('mobile'); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                            <button type="button" class="input-group-text" onclick="togglePassword()">
                                <i id="eyeIcon" class="bx bx-hide"></i>
                            </button>
                        </div>
                        <?= form_error('password'); ?>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-primary">Sign in</button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-0">Don't have an account? <a href="<?= base_url('register') ?>">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (!pwd || !icon) return;

            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                pwd.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
</body>

</html>