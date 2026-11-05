<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('app_theme');
                if (savedTheme) {
                    document.documentElement.setAttribute('data-bs-theme', savedTheme);
                }
            } catch (e) {}
        })();
    </script>
    <link href="<?= base_url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet">
    <?php
    $routerClass = $this->router->class ?? '';
    $routerMethod = $this->router->method ?? '';
    $isDashboardPage = ($routerClass === 'dashboard' && $routerMethod === 'index');
    ?>
    <?php if ($isDashboardPage): ?>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>
    <?php endif; ?>
    <link href="<?= base_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/js/pace.min.js') ?>"></script>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/sass/app.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/sass/dark-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sass/semi-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sass/bordered-theme.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            --admin-sidebar-width: 260px;
            --admin-sidebar-collapsed-width: 84px;
            --admin-topbar-height: 76px;
        }

        .wrapper {
            min-height: 100vh;
            background: #f4f5f7;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .sidebar-brand-wrap {
            padding: 0.25rem 0;
        }

        .sidebar-brand-kicker {
            font-size: 0.68rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.15rem;
        }

        .sidebar-brand-img {
            display: block;
            max-width: 170px;
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
            color: #0f172a;
            word-break: break-word;
        }

        .top-menu .dropdown-menu {
            display: none;
        }

        .top-menu .dropdown.show>.dropdown-menu {
            display: block;
        }

        body.sidebar-preload .sidebar-wrapper,
        body.sidebar-preload .page-wrapper,
        body.sidebar-preload .topbar,
        body.sidebar-preload .metismenu ul {
            transition: none !important;
            animation: none !important;
        }

        #menu>li>ul {
            display: none;
        }

        #menu>li>ul.mm-show {
            display: block;
        }

        .topbar .navbar {
            flex-wrap: nowrap;
        }

        .user-box .user-info {
            min-width: 0;
        }

        .user-box .user-name,
        .user-box .designattion {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-box .user-name {
            font-weight: 700;
            color: #1e293b;
        }

        .user-box .designattion {
            color: #64748b;
            font-size: 0.9rem;
        }

        .sidebar-wrapper {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border-right: 1px solid #e5edf7;
            width: var(--admin-sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .page-wrapper {
            min-height: calc(100vh - var(--admin-topbar-height));
            margin-top: var(--admin-topbar-height);
            margin-left: var(--admin-sidebar-width);
            width: calc(100% - var(--admin-sidebar-width));
            max-width: calc(100% - var(--admin-sidebar-width));
            transition: margin-left .25s ease;
            overflow-x: hidden;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            bottom: auto;
            width: 100%;
            height: auto;
            min-height: var(--admin-topbar-height);
            padding: 1.2rem 1.25rem;
            border-bottom: 1px solid #edf2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            z-index: 2;
        }

        .topbar {
            left: var(--admin-sidebar-width);
            right: 0;
            height: var(--admin-topbar-height);
            width: calc(100% - var(--admin-sidebar-width));
            max-width: calc(100% - var(--admin-sidebar-width));
            margin-left: 0;
            transition: left .25s ease;
            z-index: 1030;
        }

        .topbar .navbar {
            min-height: var(--admin-topbar-height);
            padding-inline: 1.5rem;
            flex-wrap: nowrap;
            position: relative;
        }

        .mobile-toggle-menu {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid #dbe4f0;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            cursor: pointer;
            flex: 0 0 auto;
            position: relative;
            z-index: 3;
        }

        .mobile-toggle-menu i {
            font-size: 1.35rem;
            line-height: 1;
        }

        .page-content {
            padding: 1.5rem;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .page-content>* {
            max-width: 100%;
        }

        img,
        svg,
        canvas,
        table,
        .card,
        .table-responsive,
        .row {
            max-width: 100%;
        }

        #menu {
            padding: 1rem 0.85rem 1.5rem;
        }

        #menu>li {
            margin-bottom: 0.35rem;
        }

        #menu>li>a {
            border-radius: 16px;
            padding: 0.95rem 1rem;
            color: #334155;
            transition: all 0.2s ease;
        }

        #menu>li>a:hover,
        #menu>li>a.is-active {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1d4ed8;
            box-shadow: inset 0 0 0 1px #bfdbfe;
        }

        #menu>li>a .parent-icon {
            min-width: 32px;
            color: inherit;
        }

        #menu>li>a .menu-title {
            font-weight: 600;
        }

        .mobile-sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 998;
        }

        body.mobile-sidebar-open .mobile-sidebar-backdrop {
            opacity: 1;
            visibility: visible;
        }

        body.sidebar-collapsed .sidebar-wrapper {
            width: var(--admin-sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar-wrapper .menu-title {
            display: none;
        }

        body.sidebar-collapsed .sidebar-wrapper .sidebar-brand-wrap {
            display: none;
        }

        body.sidebar-collapsed .sidebar-wrapper .mobile-toggle-icon {
            display: none;
        }

        body.sidebar-collapsed .page-wrapper,
        body.sidebar-collapsed .topbar {
            margin-left: 0;
        }

        body.sidebar-collapsed .page-wrapper {
            margin-left: var(--admin-sidebar-collapsed-width);
            width: calc(100% - var(--admin-sidebar-collapsed-width));
            max-width: calc(100% - var(--admin-sidebar-collapsed-width));
        }

        body.sidebar-collapsed .topbar {
            left: var(--admin-sidebar-collapsed-width);
            width: calc(100% - var(--admin-sidebar-collapsed-width));
            max-width: calc(100% - var(--admin-sidebar-collapsed-width));
        }

        body.sidebar-collapsed .topbar .navbar {
            padding-left: 1.75rem;
        }

        body.sidebar-collapsed .mobile-toggle-menu {
            position: fixed;
            top: 16px;
            left: calc(var(--admin-sidebar-collapsed-width) + 16px);
            z-index: 1061;
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper {
            width: var(--admin-sidebar-width);
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper .sidebar-brand-wrap {
            display: block;
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper .menu-title {
            display: block;
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper #menu>li>a {
            justify-content: flex-start;
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper .mobile-toggle-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        body.sidebar-collapsed.sidebar-hover-open .sidebar-wrapper {
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
        }

        .mobile-toggle-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            color: #475569;
            background: #eef4ff;
            border: 1px solid #d7e5ff;
            cursor: pointer;
        }

        .mobile-toggle-icon i {
            font-size: 1.25rem;
        }

        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 280px;
                max-width: calc(100vw - 32px);
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                z-index: 1050;
            }

            body.mobile-sidebar-open .sidebar-wrapper {
                transform: translateX(0);
            }

            .page-wrapper,
            .topbar {
                margin-left: 0 !important;
                left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .page-content {
                padding: 1rem !important;
            }

            .topbar .navbar {
                padding-inline: 0.75rem;
            }

            .mobile-toggle-menu,
            body.sidebar-collapsed .mobile-toggle-menu {
                position: relative;
                top: auto;
                left: auto;
                z-index: 3;
            }

            .user-box {
                padding-inline: 0.5rem !important;
            }

            .user-box .user-name,
            .user-box .designattion {
                max-width: 110px;
            }

            .sidebar-header {
                padding-right: 3.5rem;
            }

            .mobile-toggle-icon {
                display: inline-flex !important;
            }
        }

        @media (max-width: 767.98px) {

            .dashboard-shell,
            .category-shell,
            .product-shell,
            .product-form-shell {
                max-width: 100% !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .category-action-group,
            .d-flex.gap-2,
            .d-flex.gap-3 {
                flex-wrap: wrap;
            }

            .user-box .user-info {
                display: none;
            }
        }
    </style>
    <?php
    $titleBusinessName = trim((string) ($businessName ?? (($this->session->userdata('admin')['business_name'] ?? ''))));
    $titleAppName = $titleBusinessName !== '' ? $titleBusinessName : 'Bills Book';
    $titlePage = trim((string) ($pageTitle ?? ''));
    $titleText = $titlePage !== '' ? $titlePage . ' | ' . $titleAppName : $titleAppName;
    ?>
    <title><?= htmlspecialchars($titleText) ?></title>
</head>

<body class="sidebar-preload">
    <?php
    $adminSession = (array) ($this->session->userdata('admin') ?? []);
    // ADD THIS LINE
    $privacyModeEnabled = $adminSession['privacy_mode'] ?? false;

    $roleValue = $role ?? ($adminSession['role'] ?? 'admin');
    $role = ((string) $roleValue === '1' || $roleValue === 1 || $roleValue === 'admin') ? 'admin' : (string) $roleValue;
    $userName = $userName ?? ($adminSession['user_name'] ?? ($adminSession['name'] ?? 'Admin'));
    $userEmail = $userEmail ?? ($adminSession['email'] ?? '');
    $businessName = trim((string) ($businessName ?? ($adminSession['business_name'] ?? '')));
    $appName = $businessName !== '' ? $businessName : 'Bills Book';
    $sidebarBrand = $businessName !== '' ? $businessName : 'Bills Book';
    $currentPath = trim((string) $this->uri->uri_string(), '/');
    $pageTitle = trim((string) ($pageTitle ?? ''));
    $documentTitle = $pageTitle !== '' ? $pageTitle . ' | ' . $appName : $appName;
    $profileUrl = $profileUrl ?? base_url('profile');
    $profileSource = $profileImage ?? ($adminSession['profile_image'] ?? '');

    if (empty($profileSource)) {
        $profileImage = base_url('assets/images/Default.jpg');
    } elseif (preg_match('#^https?://#i', $profileSource)) {
        $profileImage = $profileSource;
    } elseif (strpos($profileSource, 'uploads/') === 0 || strpos($profileSource, 'assets/') === 0) {
        $profileImage = base_url($profileSource);
    } else {
        $profileImage = base_url('uploads/profile/' . ltrim($profileSource, '/'));
    }
    ?>
    <div class="wrapper">
        <div class="mobile-sidebar-backdrop"></div>
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div class="sidebar-brand-wrap">
                    <div class="sidebar-brand-kicker">Business</div>
                    <div class="sidebar-brand-img"><?= htmlspecialchars($sidebarBrand) ?></div>
                </div>
                <div class="mobile-toggle-icon ms-auto">
                    <i class='bx bx-x'></i>
                </div>
            </div>
            <ul class="metismenu" id="menu">

                <?php if ($role === 'admin'): ?>

                    <!-- Dashboard -->
                    <li>
                        <a href="<?= base_url('dashboard'); ?>" class="<?= $currentPath === 'dashboard' ? 'is-active' : '' ?>">
                            <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                            <div class="menu-title">Dashboard</div>
                        </a>
                    </li>

                    <!-- Purchase -->
                    <li class="<?= (strpos($currentPath, 'category') === 0 || strpos($currentPath, 'product') === 0) ? 'mm-active' : '' ?>">
                        <a href="javascript:;" class="has-arrow" aria-expanded="false">
                            <div class="parent-icon"><i class='bx bx-cart'></i></div>
                            <div class="menu-title">Purchase</div>
                        </a>
                        <ul class="<?= (strpos($currentPath, 'category') === 0 || strpos($currentPath, 'product') === 0) ? 'mm-show' : '' ?>">
                            <li>
                                <a href="<?= base_url('category'); ?>" class="<?= $currentPath === 'category' || strpos($currentPath, 'category/') === 0 ? 'is-active' : '' ?>">
                                    <i class="bx bx-right-arrow-alt"></i>Category
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('product'); ?>" class="<?= $currentPath === 'product' || strpos($currentPath, 'product/') === 0 ? 'is-active' : '' ?>">
                                    <i class="bx bx-right-arrow-alt"></i>Product
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Sales -->
                    <li class="<?= (strpos($currentPath, 'sales') === 0 || strpos($currentPath, 'invoice-templates') === 0) ? 'mm-active' : '' ?>">
                        <a href="javascript:;" class="has-arrow" aria-expanded="false">
                            <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                            <div class="menu-title">Sales</div>
                        </a>
                        <ul class="<?= (strpos($currentPath, 'sales') === 0 || strpos($currentPath, 'invoice-templates') === 0) ? 'mm-show' : '' ?>">
                            <li>
                                <a href="<?= base_url('sales'); ?>" class="<?= $currentPath === 'sales' || strpos($currentPath, 'sales/') === 0 ? 'is-active' : '' ?>">
                                    <i class="bx bx-right-arrow-alt"></i>Parties
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('invoice_master'); ?>" class="<?= $currentPath === 'invoice_master' ? 'is-active' : '' ?>">
                                    <i class="bx bx-right-arrow-alt"></i>Invoice Templates
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- WhatsApp (unchanged) -->
                    <li>
                        <a href="<?= base_url('whatsapp-connect'); ?>" class="<?= $currentPath === 'whatsapp-connect' ? 'is-active' : '' ?>">
                            <div class="parent-icon"><i class='bx bxl-whatsapp'></i></div>
                            <div class="menu-title">WhatsApp Connect</div>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
            <style>
                .btn-action {
                    border: none;
                    color: #fff;
                    padding: 0.45rem 1rem;
                    /* smaller */
                    border-radius: 10px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.4rem;
                    transition: all 0.25s ease;
                    text-decoration: none;
                }

                /* Sale Button */
                .btn-sale {
                    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
                    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
                }

                .btn-sale:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
                    color: #fff;
                }

                /* Purchase Button */
                .btn-purchase {
                    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                    box-shadow: 0 3px 10px rgba(5, 150, 105, 0.25);
                }

                .btn-purchase:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
                    color: #fff;
                }

                .shortcut-hint {
                    margin-left: 8px;
                    padding: 2px 6px;
                    font-size: 11px;
                    background: #f1f5f9;
                    color: #64748b;
                    border-radius: 4px;
                    border: 1px solid #e2e8f0;
                    font-weight: 500;
                }

                .btn-sale {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 14px;
                    font-weight: 500;
                }

                /* Text */
                .btn-sale .btn-text {
                    margin-right: 4px;
                }

                /* Shortcut key style (PRO LOOK 🔥) */
                .shortcut-key {
                    font-size: 11px;
                    padding: 2px 6px;
                    border-radius: 6px;
                    background: rgba(255, 255, 255, 0.2);
                    color: #fff;
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    font-family: monospace;
                }
            </style>
        </div>
        <header>
            <div class="topbar">
                <nav class="navbar navbar-expand gap-2 align-items-center">
                    <div class="mobile-toggle-menu d-flex"><i class="bx bx-menu"></i></div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex"></li>
                        </ul>
                    </div>
                    <div style="display:flex; gap:10px; align-items:center;">
                        <a href="<?= base_url('sales/add') ?>" class="btn-action btn-sale">
                            <i class='bx bx-plus'></i>
                            <span class="btn-text">Add Sale</span>
                            <kbd class="shortcut-key">alt</kbd>
                        </a>
                        <a href="<?= base_url('product/add') ?>" class="btn-action btn-purchase">
                            <i class='bx bx-cart'></i> Add Purchase
                        </a>

                    </div>
                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="<?= $profileImage ?>" class="user-img" alt="user avatar" onerror="this.src='<?= base_url('assets/images/Default.jpg') ?>'">
                            <div class="user-info">
                                <p class="user-name mb-0"><?= htmlspecialchars($userName) ?></p>
                                <p class="designattion mb-0"><?= htmlspecialchars($businessName !== '' ? $businessName : 'Bills Book') ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= $profileUrl ?>">
                                    <i class="bx bx-user fs-5"></i><span>Profile</span>
                                </a>
                            </li>

                            <!-- Privacy Mode Toggle Option -->
                            <?php if ($isDashboardPage): ?>
                                <li>
                                    <div class="dropdown-item d-flex align-items-center justify-content-between" id="privacyModeToggle">
                                        <span class="d-flex align-items-center">
                                            <i class="bx bx-lock-alt fs-5 me-2"></i>
                                            <span>Privacy Mode</span>
                                        </span>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" id="privacySwitch" <?= $privacyModeEnabled ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('logout'); ?>">
                                    <i class="bx bx-log-out-circle fs-5"></i><span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>