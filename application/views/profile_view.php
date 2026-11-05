<div class="page-wrapper">
    <div class="page-content">
        <style>
            .pf-shell { max-width: 1100px; width: 100%; }

            .pf-grid {
                display: grid;
                grid-template-columns: 300px 1fr;
                gap: 1.25rem;
                align-items: start;
            }

            /* Left Card */
            .pf-left {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: linear-gradient(180deg, #f8fbff 0%, #fff 100%);
                box-shadow: 0 16px 48px rgba(15,23,42,.06);
                overflow: hidden;
                position: sticky;
                top: 92px;
            }

            .pf-cover {
                height: 80px;
                background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 50%, #6366f1 100%);
                position: relative;
            }

            .pf-avatar-wrap {
                position: relative;
                width: 110px;
                margin: -55px auto 0;
            }

            .pf-avatar {
                width: 110px;
                height: 110px;
                object-fit: cover;
                border-radius: 50%;
                border: 4px solid #fff;
                box-shadow: 0 10px 28px rgba(37,99,235,.18);
                background: #fff;
                display: block;
            }

            .pf-cam-btn {
                position: absolute;
                right: 0;
                bottom: 2px;
                width: 34px;
                height: 34px;
                border: 0;
                border-radius: 50%;
                background: linear-gradient(135deg, #2563eb, #0ea5e9);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 6px 16px rgba(37,99,235,.3);
                cursor: pointer;
                font-size: 0.95rem;
                transition: transform 0.15s;
            }

            .pf-cam-btn:hover { transform: scale(1.08); }

            .pf-info {
                text-align: center;
                padding: 1rem 1.25rem 1.25rem;
            }

            .pf-biz-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.3rem 0.7rem;
                background: #eff6ff;
                color: #2563eb;
                border-radius: 999px;
                font-size: 0.78rem;
                font-weight: 700;
                margin-bottom: 0.6rem;
            }

            .pf-name {
                font-size: 1.15rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0 0 0.2rem;
            }

            .pf-email {
                font-size: 0.82rem;
                color: #64748b;
                margin: 0 0 0.5rem;
                word-break: break-all;
            }

            .pf-social-links {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
                margin-top: 0.6rem;
            }

            .pf-social-link {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                text-decoration: none;
                transition: all 0.15s;
            }

            .pf-social-ig {
                background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
                color: #fff;
            }
            .pf-social-ig:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220,39,67,.3); }

            .pf-social-fb {
                background: #1877f2;
                color: #fff;
            }
            .pf-social-fb:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(24,119,242,.3); }

            .pf-social-wa {
                background: #25d366;
                color: #fff;
            }
            .pf-social-wa:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37,211,102,.3); }

            .pf-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                border-top: 1px solid #f1f5f9;
            }

            .pf-stat {
                padding: 0.7rem;
                text-align: center;
            }

            .pf-stat:first-child { border-right: 1px solid #f1f5f9; }

            .pf-stat-val {
                font-size: 1.05rem;
                font-weight: 800;
                color: #0f172a;
            }

            .pf-stat-label {
                font-size: 0.68rem;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                font-weight: 700;
            }

            /* Right Card */
            .pf-right {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: #fff;
                box-shadow: 0 16px 48px rgba(15,23,42,.06);
                overflow: hidden;
            }

            /* Tabs */
            .pf-tabs {
                display: flex;
                border-bottom: 1px solid #e9eff6;
                background: #f8fafc;
            }

            .pf-tab {
                flex: 1;
                padding: 0.85rem 1rem;
                text-align: center;
                font-size: 0.85rem;
                font-weight: 700;
                color: #64748b;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                transition: all 0.15s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                background: none;
                border-left: none;
                border-right: none;
                border-top: none;
            }

            .pf-tab:hover { color: #2563eb; background: #eff6ff; }

            .pf-tab.active {
                color: #2563eb;
                border-bottom-color: #2563eb;
                background: #fff;
            }

            .pf-tab i { font-size: 1.1rem; }

            /* Tab Content */
            .pf-tab-content { display: none; padding: 1.5rem; }
            .pf-tab-content.active { display: block; }

            .pf-section-title {
                font-size: 0.78rem;
                font-weight: 800;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.85rem;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .pf-section-title i { font-size: 1rem; color: #2563eb; }

            /* Form Fields */
            .pf-field label {
                font-size: 0.78rem;
                font-weight: 700;
                color: #334155;
                margin-bottom: 0.35rem;
                display: block;
            }

            .pf-field .form-control {
                height: 44px;
                border-radius: 12px;
                border-color: #dbe3ef;
                font-size: 0.88rem;
                padding: 0.4rem 0.85rem;
                transition: all 0.15s;
            }

            .pf-field textarea.form-control {
                height: auto;
                min-height: 90px;
                resize: vertical;
            }

            .pf-field .form-control:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96,165,250,.12);
            }

            .pf-field .form-control.is-invalid,
            .pf-field .form-control:invalid:not(:placeholder-shown) {
                border-color: #f87171;
            }

            .pf-input-icon {
                position: relative;
            }

            .pf-input-icon i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 1rem;
            }

            .pf-input-icon .form-control {
                padding-left: 2.4rem;
            }

            /* Social field prefix */
            .pf-social-prefix {
                position: relative;
            }

            .pf-social-prefix .pf-prefix {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 0.78rem;
                color: #94a3b8;
                font-weight: 600;
                pointer-events: none;
            }

            .pf-social-prefix .form-control {
                padding-left: 2rem;
            }

            /* Save Button */
            .pf-save-btn {
                height: 46px;
                padding: 0 1.5rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 0.88rem;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
            }

            /* Password Strength */
            .pf-pw-strength {
                height: 4px;
                border-radius: 2px;
                background: #e2e8f0;
                margin-top: 0.4rem;
                overflow: hidden;
            }

            .pf-pw-bar {
                height: 100%;
                border-radius: 2px;
                width: 0%;
                transition: all 0.3s;
            }

            .pf-pw-text {
                font-size: 0.7rem;
                font-weight: 600;
                margin-top: 0.2rem;
            }

            /* Password Toggle */
            .pf-pw-toggle {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                font-size: 1.1rem;
                padding: 0;
            }

            .pf-pw-toggle:hover { color: #64748b; }

            .pf-pw-field {
                position: relative;
            }

            .pf-pw-field .form-control {
                padding-right: 2.5rem;
            }

            /* Separator */
            .pf-sep {
                border: none;
                border-top: 1px dashed #e9eff6;
                margin: 1.25rem 0;
            }

            /* Alert inside tab */
            .pf-alert {
                padding: 0.6rem 0.85rem;
                border-radius: 10px;
                font-size: 0.82rem;
                font-weight: 600;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .pf-alert-success {
                background: #f0fdf4;
                color: #166534;
                border: 1px solid #bbf7d0;
            }

            .pf-alert-danger {
                background: #fef2f2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }

            /* Responsive */
            @media (max-width: 991.98px) {
                .pf-grid {
                    grid-template-columns: 1fr;
                }

                .pf-left {
                    position: static;
                    max-width: 400px;
                    margin: 0 auto;
                    width: 100%;
                }
            }

            @media (max-width: 575.98px) {
                .pf-tab { font-size: 0.78rem; padding: 0.7rem 0.5rem; }
                .pf-tab span { display: none; }
                .pf-tab-content { padding: 1rem; }
            }
        </style>

        <div class="pf-shell mx-auto">
            <div class="pf-grid">

                <!-- ── LEFT: Profile Card ── -->
                <div class="pf-left">
                    <div class="pf-cover"></div>
                    <div class="pf-avatar-wrap">
                        <img src="<?= htmlspecialchars($profileImage) ?>"
                             class="pf-avatar" id="avatarPreview"
                             alt="Profile"
                             onerror="this.src='<?= base_url('assets/images/Default.jpg') ?>'">
                        <label for="profile_image" class="pf-cam-btn" title="Change photo">
                            <i class='bx bx-camera'></i>
                        </label>
                    </div>
                    <div class="pf-info">
                        <div class="pf-biz-badge">
                            <i class='bx bx-buildings'></i>
                            <?= htmlspecialchars($businessName ?? 'Bills Book') ?>
                        </div>
                        <h4 class="pf-name"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></h4>
                        <p class="pf-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>

                        <?php
                            $hasInstagram = !empty($user['instagram']);
                            $hasFacebook  = !empty($user['facebook']);
                            $hasMobile    = !empty($user['mobile']);
                        ?>
                        <?php if ($hasInstagram || $hasFacebook || $hasMobile): ?>
                            <div class="pf-social-links">
                                <?php if ($hasInstagram): ?>
                                    <a href="https://instagram.com/<?= htmlspecialchars($user['instagram']) ?>"
                                       target="_blank" class="pf-social-link pf-social-ig" title="Instagram">
                                        <i class='bx bxl-instagram'></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($hasFacebook): ?>
                                    <a href="https://facebook.com/<?= htmlspecialchars($user['facebook']) ?>"
                                       target="_blank" class="pf-social-link pf-social-fb" title="Facebook">
                                        <i class='bx bxl-facebook'></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($hasMobile): ?>
                                    <a href="https://wa.me/91<?= htmlspecialchars($user['mobile']) ?>"
                                       target="_blank" class="pf-social-link pf-social-wa" title="WhatsApp">
                                        <i class='bx bxl-whatsapp'></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="pf-stats">
                        <div class="pf-stat">
                            <div class="pf-stat-val"><?= htmlspecialchars($user['mobile'] ?? '—') ?></div>
                            <div class="pf-stat-label">Mobile</div>
                        </div>
                        <div class="pf-stat">
                            <div class="pf-stat-val" style="font-size:0.82rem"><?= htmlspecialchars($user['address'] ?? '—') ?></div>
                            <div class="pf-stat-label">Location</div>
                        </div>
                    </div>
                </div>

                <!-- ── RIGHT: Settings Panel ── -->
                <div class="pf-right">

                    <!-- Tabs -->
                    <div class="pf-tabs">
                        <button type="button" class="pf-tab <?= ($activeTab ?? 'profile') === 'profile' ? 'active' : '' ?>" data-tab="profile">
                            <i class='bx bx-user'></i> <span>Profile</span>
                        </button>
                        <button type="button" class="pf-tab <?= ($activeTab ?? 'profile') === 'social' ? 'active' : '' ?>" data-tab="social">
                            <i class='bx bx-share-alt'></i> <span>Social</span>
                        </button>
                        <button type="button" class="pf-tab <?= ($activeTab ?? 'profile') === 'password' ? 'active' : '' ?>" data-tab="password">
                            <i class='bx bx-lock-alt'></i> <span>Password</span>
                        </button>
                    </div>

                    <!-- ═══ TAB 1: Profile ═══ -->
                    <div class="pf-tab-content <?= ($activeTab ?? 'profile') === 'profile' ? 'active' : '' ?>" id="tab-profile">

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="pf-alert pf-alert-success">
                                <i class='bx bx-check-circle'></i> <?= $this->session->flashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($profileError)): ?>
                            <div class="pf-alert pf-alert-danger">
                                <i class='bx bx-error-circle'></i> <?= $profileError ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('profile/update') ?>" enctype="multipart/form-data">
                            <input type="file" name="profile_image" id="profile_image" class="d-none" accept=".jpg,.jpeg,.png,.webp">

                            <div class="pf-section-title">
                                <i class='bx bx-id-card'></i> Personal Information
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 pf-field">
                                    <label>Full Name</label>
                                    <div class="pf-input-icon">
                                        <i class='bx bx-user'></i>
                                        <input type="text" name="full_name"
                                               value="<?= set_value('full_name', $user['name'] ?? '') ?>"
                                               class="form-control" placeholder="Your full name" required>
                                    </div>
                                    <?= form_error('full_name', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-md-6 pf-field">
                                    <label>Business Name</label>
                                    <div class="pf-input-icon">
                                        <i class='bx bx-buildings'></i>
                                        <input type="text" name="business_name"
                                               value="<?= set_value('business_name', $user['business_name'] ?? '') ?>"
                                               class="form-control" placeholder="Business name" required>
                                    </div>
                                    <?= form_error('business_name', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <hr class="pf-sep">

                            <div class="pf-section-title">
                                <i class='bx bx-envelope'></i> Contact Details
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 pf-field">
                                    <label>Email Address</label>
                                    <div class="pf-input-icon">
                                        <i class='bx bx-envelope'></i>
                                        <input type="email" name="email"
                                               value="<?= set_value('email', $user['email'] ?? '') ?>"
                                               class="form-control" placeholder="your@email.com" required>
                                    </div>
                                    <?= form_error('email', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-md-6 pf-field">
                                    <label>Mobile Number</label>
                                    <div class="pf-input-icon">
                                        <i class='bx bx-phone'></i>
                                        <input type="text" name="mobile"
                                               value="<?= set_value('mobile', $user['mobile'] ?? '') ?>"
                                               class="form-control" placeholder="10 digit number" required maxlength="10">
                                    </div>
                                    <?= form_error('mobile', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-12 pf-field">
                                    <label>Address</label>
                                    <div class="pf-input-icon" style="align-items:flex-start">
                                        <i class='bx bx-map' style="top:14px;transform:none"></i>
                                        <textarea name="address" class="form-control" placeholder="Business address"><?= set_value('address', $user['address'] ?? '') ?></textarea>
                                    </div>
                                    <?= form_error('address', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <hr class="pf-sep">

                            <div class="pf-section-title">
                                <i class='bx bx-share-alt'></i> Social Profiles
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 pf-field">
                                    <label>Instagram Username</label>
                                    <div class="pf-social-prefix">
                                        <span class="pf-prefix">@</span>
                                        <input type="text" name="instagram"
                                               value="<?= set_value('instagram', $user['instagram'] ?? '') ?>"
                                               class="form-control" placeholder="username">
                                    </div>
                                </div>
                                <div class="col-md-6 pf-field">
                                    <label>Facebook Page</label>
                                    <div class="pf-social-prefix">
                                        <span class="pf-prefix">fb/</span>
                                        <input type="text" name="facebook"
                                               value="<?= set_value('facebook', $user['facebook'] ?? '') ?>"
                                               class="form-control" placeholder="pagename" style="padding-left:2.5rem">
                                    </div>
                                </div>
                            </div>

                            <hr class="pf-sep">

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small style="color:#94a3b8;font-size:0.75rem">
                                    <i class='bx bx-info-circle'></i>
                                    JPG, PNG, WEBP — Max 2MB
                                </small>
                                <button type="submit" class="btn btn-primary pf-save-btn">
                                    <i class='bx bx-save'></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ TAB 2: Social Preview ═══ -->
                    <div class="pf-tab-content <?= ($activeTab ?? 'profile') === 'social' ? 'active' : '' ?>" id="tab-social">

                        <div class="pf-section-title">
                            <i class='bx bx-link-alt'></i> Your Social Links
                        </div>

                        <div style="background:#f8fafc;border:1px solid #e9eff6;border-radius:14px;padding:1.25rem;margin-bottom:1rem">
                            <?php if (!empty($user['instagram'])): ?>
                                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem">
                                    <div class="pf-social-link pf-social-ig" style="width:40px;height:40px;border-radius:10px">
                                        <i class='bx bxl-instagram'></i>
                                    </div>
                                    <div>
                                        <div style="font-size:0.75rem;color:#94a3b8;font-weight:600">Instagram</div>
                                        <a href="https://instagram.com/<?= htmlspecialchars($user['instagram']) ?>"
                                           target="_blank" style="font-size:0.88rem;color:#0f172a;font-weight:700;text-decoration:none">
                                            @<?= htmlspecialchars($user['instagram']) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user['facebook'])): ?>
                                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem">
                                    <div class="pf-social-link pf-social-fb" style="width:40px;height:40px;border-radius:10px">
                                        <i class='bx bxl-facebook'></i>
                                    </div>
                                    <div>
                                        <div style="font-size:0.75rem;color:#94a3b8;font-weight:600">Facebook</div>
                                        <a href="https://facebook.com/<?= htmlspecialchars($user['facebook']) ?>"
                                           target="_blank" style="font-size:0.88rem;color:#0f172a;font-weight:700;text-decoration:none">
                                            fb/<?= htmlspecialchars($user['facebook']) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user['mobile'])): ?>
                                <div style="display:flex;align-items:center;gap:0.75rem">
                                    <div class="pf-social-link pf-social-wa" style="width:40px;height:40px;border-radius:10px">
                                        <i class='bx bxl-whatsapp'></i>
                                    </div>
                                    <div>
                                        <div style="font-size:0.75rem;color:#94a3b8;font-weight:600">WhatsApp</div>
                                        <a href="https://wa.me/91<?= htmlspecialchars($user['mobile']) ?>"
                                           target="_blank" style="font-size:0.88rem;color:#0f172a;font-weight:700;text-decoration:none">
                                            +91 <?= htmlspecialchars($user['mobile']) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (empty($user['instagram']) && empty($user['facebook'])): ?>
                                <div style="text-align:center;padding:1.5rem;color:#94a3b8">
                                    <i class='bx bx-link-alt' style="font-size:2rem;display:block;margin-bottom:0.5rem;color:#cbd5e1"></i>
                                    <p style="margin:0;font-size:0.85rem">No social links added yet.</p>
                                    <p style="margin:0.3rem 0 0;font-size:0.78rem">Add them in the <strong>Profile</strong> tab.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="background:#eff6ff;border:1px solid #dbeafe;border-radius:10px;padding:0.7rem 0.85rem;font-size:0.78rem;color:#1e40af;display:flex;align-items:center;gap:0.4rem">
                            <i class='bx bx-info-circle'></i>
                            Social links are shown on your invoices and business profile.
                        </div>
                    </div>

                    <!-- ═══ TAB 3: Change Password ═══ -->
                    <div class="pf-tab-content <?= ($activeTab ?? 'profile') === 'password' ? 'active' : '' ?>" id="tab-password">

                        <?php if ($this->session->flashdata('password_success')): ?>
                            <div class="pf-alert pf-alert-success">
                                <i class='bx bx-check-circle'></i> <?= $this->session->flashdata('password_success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($passwordError)): ?>
                            <div class="pf-alert pf-alert-danger">
                                <i class='bx bx-error-circle'></i> <?= $passwordError ?>
                            </div>
                        <?php endif; ?>

                        <div class="pf-section-title">
                            <i class='bx bx-shield-quarter'></i> Change Password
                        </div>

                        <form method="post" action="<?= base_url('profile/change_password') ?>">
                            <div class="row g-3" style="max-width:480px">
                                <div class="col-12 pf-field">
                                    <label>Current Password</label>
                                    <div class="pf-pw-field">
                                        <input type="password" name="current_password" class="form-control pw-input"
                                               placeholder="Enter current password" required>
                                        <button type="button" class="pf-pw-toggle" onclick="togglePw(this)">
                                            <i class='bx bx-hide'></i>
                                        </button>
                                    </div>
                                    <?= form_error('current_password', '<small class="text-danger">', '</small>') ?>
                                </div>

                                <div class="col-12 pf-field">
                                    <label>New Password</label>
                                    <div class="pf-pw-field">
                                        <input type="password" name="new_password" id="newPassword" class="form-control pw-input"
                                               placeholder="Min 6 characters" required minlength="6">
                                        <button type="button" class="pf-pw-toggle" onclick="togglePw(this)">
                                            <i class='bx bx-hide'></i>
                                        </button>
                                    </div>
                                    <div class="pf-pw-strength">
                                        <div class="pf-pw-bar" id="pwBar"></div>
                                    </div>
                                    <div class="pf-pw-text" id="pwText"></div>
                                    <?= form_error('new_password', '<small class="text-danger">', '</small>') ?>
                                </div>

                                <div class="col-12 pf-field">
                                    <label>Confirm New Password</label>
                                    <div class="pf-pw-field">
                                        <input type="password" name="confirm_password" id="confirmPassword" class="form-control pw-input"
                                               placeholder="Re-enter new password" required>
                                        <button type="button" class="pf-pw-toggle" onclick="togglePw(this)">
                                            <i class='bx bx-hide'></i>
                                        </button>
                                    </div>
                                    <small class="text-danger" id="matchError" style="display:none">Passwords don't match</small>
                                    <?= form_error('confirm_password', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <hr class="pf-sep">

                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem">
                                <small style="color:#94a3b8;font-size:0.75rem">
                                    <i class='bx bx-info-circle'></i>
                                    Min 6 characters. Use letters, numbers & symbols.
                                </small>
                                <button type="submit" class="btn btn-primary pf-save-btn" id="pwSubmitBtn">
                                    <i class='bx bx-lock-alt'></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- ── SCRIPTS ── -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // ── Tab Switching
                document.querySelectorAll('.pf-tab').forEach(tab => {
                    tab.addEventListener('click', function () {
                        document.querySelectorAll('.pf-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.pf-tab-content').forEach(c => c.classList.remove('active'));

                        this.classList.add('active');
                        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
                    });
                });

                // ── Avatar Preview
                const fileInput = document.getElementById('profile_image');
                const avatar = document.getElementById('avatarPreview');

                if (fileInput && avatar) {
                    fileInput.addEventListener('change', function (e) {
                        const file = e.target.files && e.target.files[0];
                        if (file) {
                            avatar.src = URL.createObjectURL(file);
                        }
                    });
                }

                // ── Password Strength Meter
                const newPw = document.getElementById('newPassword');
                const pwBar = document.getElementById('pwBar');
                const pwText = document.getElementById('pwText');

                if (newPw) {
                    newPw.addEventListener('input', function () {
                        const val = this.value;
                        let score = 0;

                        if (val.length >= 6) score++;
                        if (val.length >= 10) score++;
                        if (/[A-Z]/.test(val)) score++;
                        if (/[0-9]/.test(val)) score++;
                        if (/[^A-Za-z0-9]/.test(val)) score++;

                        const levels = [
                            { width: '0%',   color: '#e2e8f0', text: '', textColor: '#94a3b8' },
                            { width: '20%',  color: '#ef4444', text: 'Very Weak', textColor: '#ef4444' },
                            { width: '40%',  color: '#f97316', text: 'Weak', textColor: '#f97316' },
                            { width: '60%',  color: '#eab308', text: 'Fair', textColor: '#eab308' },
                            { width: '80%',  color: '#22c55e', text: 'Strong', textColor: '#22c55e' },
                            { width: '100%', color: '#16a34a', text: 'Very Strong', textColor: '#16a34a' },
                        ];

                        const level = levels[Math.min(score, 5)];
                        pwBar.style.width = level.width;
                        pwBar.style.background = level.color;
                        pwText.textContent = level.text;
                        pwText.style.color = level.textColor;
                    });
                }

                // ── Confirm Password Match
                const confirmPw = document.getElementById('confirmPassword');
                const matchError = document.getElementById('matchError');
                const submitBtn = document.getElementById('pwSubmitBtn');

                if (confirmPw && newPw) {
                    confirmPw.addEventListener('input', function () {
                        if (this.value && this.value !== newPw.value) {
                            matchError.style.display = 'block';
                            submitBtn.disabled = true;
                            submitBtn.style.opacity = '0.5';
                        } else {
                            matchError.style.display = 'none';
                            submitBtn.disabled = false;
                            submitBtn.style.opacity = '1';
                        }
                    });
                }
            });

            // ── Toggle Password Visibility
            function togglePw(btn) {
                const input = btn.parentElement.querySelector('.pw-input');
                const icon = btn.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            }
        </script>
    </div>
</div>