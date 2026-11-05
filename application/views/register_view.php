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
    <title>Bills Book | Admin Register</title>
</head>

<body>
    <div class="wrapper d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="card shadow-lg rounded-4" style="width: 460px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="<?= base_url('assets/images/logo-icon.png') ?>" width="60" alt="Logo">
                    <h4 class="mt-2">Create Admin Account</h4>
                    <p class="text-muted mb-0">Register for the admin panel</p>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>

                <?php if (!empty($mobile_error)): ?>
                    <div class="alert alert-danger"><?= $mobile_error ?></div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('sign-up') ?>">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" value="<?= set_value('full_name') ?>" class="form-control" placeholder="Enter full name">
                        <?= form_error('full_name') ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" value="<?= set_value('business_name') ?>" class="form-control" placeholder="Enter business name">
                        <?= form_error('business_name') ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?= set_value('email') ?>" class="form-control" placeholder="Enter email">
                        <?= form_error('email') ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" value="<?= set_value('mobile') ?>" class="form-control" placeholder="Enter mobile number">
                        <?= form_error('mobile') ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                        <?= form_error('password') ?>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="<?= base_url('login') ?>">Sign in</a></p>
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
</body>

</html>