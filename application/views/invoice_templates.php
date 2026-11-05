<div class="page-wrapper">
    <div class="page-content">
        <style>
            .it-shell { max-width: 1200px; width: 100%; }

            .it-card {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: #fff;
                box-shadow: 0 12px 36px rgba(15,23,42,.06);
                overflow: hidden;
            }

            .it-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.8rem 1.5rem;
                background: linear-gradient(135deg, #f0f9ff 0%, #fff 58%, #f5f3ff 100%);
                border-bottom: 1px solid #e0e7ff;
            }

            .it-topbar h2 {
                font-size: 1.15rem;
                font-weight: 800;
                margin: 0;
                color: #0f172a;
            }

            .it-body { padding: 1.5rem; }

            /* Template Grid */
            .it-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .it-tpl {
                border: 2px solid #e2e8f0;
                border-radius: 16px;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                background: #fff;
            }

            .it-tpl:hover {
                border-color: #93c5fd;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(37,99,235,.1);
            }

            .it-tpl.active {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37,99,235,.15);
            }

            .it-tpl.active::after {
                content: '✓';
                position: absolute;
                top: 8px;
                right: 8px;
                width: 26px;
                height: 26px;
                background: #2563eb;
                color: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 800;
            }

            .it-tpl-preview {
                width: 100%;
                aspect-ratio: 210 / 297;
                background: #f8fafc;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .it-tpl-preview img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .it-tpl-info {
                padding: 0.65rem 0.85rem;
                border-top: 1px solid #f1f5f9;
            }

            .it-tpl-info h5 {
                font-size: 0.85rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 0.15rem;
            }

            .it-tpl-info p {
                font-size: 0.72rem;
                color: #94a3b8;
                margin: 0;
            }

            /* hidden radio */
            .it-tpl input[type="radio"] { display: none; }

            /* Customization Section */
            .it-section {
                border: 1px solid #e9eff6;
                border-radius: 16px;
                padding: 1.25rem;
                margin-bottom: 1.5rem;
                background: #fbfdff;
            }

            .it-section-title {
                font-size: 0.95rem;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Color Pickers */
            .it-colors {
                display: flex;
                gap: 1.5rem;
                flex-wrap: wrap;
            }

            .it-color-field {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .it-color-field label {
                font-size: 0.82rem;
                font-weight: 600;
                color: #475569;
                min-width: 100px;
            }

            .it-color-input {
                width: 48px;
                height: 48px;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 3px;
                cursor: pointer;
                background: #fff;
            }

            .it-color-input::-webkit-color-swatch-wrapper { padding: 0; }
            .it-color-input::-webkit-color-swatch { border: none; border-radius: 8px; }

            .it-color-hex {
                width: 90px;
                height: 40px;
                border: 1px solid #dbe3ef;
                border-radius: 8px;
                text-align: center;
                font-size: 0.82rem;
                font-weight: 600;
                color: #334155;
                text-transform: uppercase;
            }

            /* Signature */
            .it-sig-area {
                display: flex;
                align-items: flex-start;
                gap: 1.5rem;
                flex-wrap: wrap;
            }

            .it-sig-preview {
                width: 200px;
                min-height: 100px;
                border: 2px dashed #dbe4f0;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8fafc;
                overflow: hidden;
                position: relative;
            }

            .it-sig-preview img {
                max-width: 100%;
                max-height: 100px;
                object-fit: contain;
            }

            .it-sig-preview .it-sig-empty {
                text-align: center;
                color: #94a3b8;
                font-size: 0.78rem;
                padding: 1rem;
            }

            .it-sig-preview .it-sig-empty i {
                font-size: 1.8rem;
                display: block;
                margin-bottom: 0.3rem;
                color: #cbd5e1;
            }

            .it-sig-actions {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .it-sig-actions .btn {
                font-size: 0.82rem;
                border-radius: 10px;
                padding: 0.45rem 1rem;
            }

            .it-sig-file {
                font-size: 0.78rem;
                color: #94a3b8;
                margin-top: 0.3rem;
            }

            /* Live Preview Mini */
            .it-live-preview {
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 1.25rem;
                background: #fff;
                min-height: 200px;
            }

            .it-preview-header {
                display: flex;
                justify-content: space-between;
                padding-bottom: 0.75rem;
                margin-bottom: 0.75rem;
                border-bottom: 2px solid var(--inv-primary, #0f172a);
            }

            .it-preview-brand {
                font-size: 1rem;
                font-weight: 800;
                color: var(--inv-primary, #0f172a);
            }

            .it-preview-inv {
                font-size: 0.85rem;
                font-weight: 700;
                color: var(--inv-accent, #2563eb);
                text-transform: uppercase;
            }

            .it-preview-table {
                width: 100%;
                border-collapse: collapse;
                margin: 0.75rem 0;
            }

            .it-preview-table th {
                background: var(--inv-primary, #0f172a);
                color: #fff;
                font-size: 0.65rem;
                padding: 0.4rem 0.5rem;
                text-transform: uppercase;
                text-align: left;
            }

            .it-preview-table td {
                font-size: 0.72rem;
                padding: 0.35rem 0.5rem;
                border-bottom: 1px solid #e2e8f0;
                color: #475569;
            }

            .it-preview-total {
                text-align: right;
                font-weight: 800;
                font-size: 0.88rem;
                margin-top: 0.5rem;
            }

            .it-preview-sig {
                margin-top: 1rem;
                text-align: right;
            }

            .it-preview-sig img {
                max-height: 50px;
                margin-bottom: 0.3rem;
            }

            .it-preview-sig-line {
                width: 120px;
                border-top: 1px solid #cbd5e1;
                margin-left: auto;
                padding-top: 0.3rem;
                font-size: 0.68rem;
                color: #94a3b8;
                text-align: center;
            }

            /* Save Bar */
            .it-save-bar {
                display: flex;
                justify-content: flex-end;
                gap: 0.75rem;
                padding-top: 1rem;
                border-top: 1px solid #e9eff6;
            }

            .it-save-bar .btn {
                border-radius: 12px;
                padding: 0.6rem 1.5rem;
                font-weight: 700;
                font-size: 0.88rem;
            }

            /* Responsive */
            @media (max-width: 991.98px) {
                .it-grid { grid-template-columns: repeat(2, 1fr); }
            }

            @media (max-width: 575.98px) {
                .it-grid { grid-template-columns: 1fr; }
                .it-body { padding: 1rem; }
                .it-colors { flex-direction: column; }
                .it-sig-area { flex-direction: column; }
            }
        </style>

        <div class="it-shell mx-auto">

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success rounded-3 mb-3" style="font-size:.88rem">
                    <i class='bx bx-check-circle'></i> <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger rounded-3 mb-3" style="font-size:.88rem">
                    <i class='bx bx-error-circle'></i> <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="it-card">

                <div class="it-topbar">
                    <div>
                        <h2><i class='bx bx-palette' style="vertical-align:middle;margin-right:0.3rem"></i> Invoice Templates</h2>
                        <p style="font-size:0.78rem;color:#64748b;margin:0.2rem 0 0">Choose a template, customize colors, and add your signature</p>
                    </div>
                    <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary btn-sm px-3">← Back to Sales</a>
                </div>

                <div class="it-body">

                    <form method="post" action="<?= base_url('invoice_master/save_invoice_settings') ?>" id="templateForm">

                        <!-- ── TEMPLATE SELECTION ── -->
                        <div class="it-section">
                            <div class="it-section-title">
                                <i class='bx bx-layout' style="font-size:1.1rem;color:#2563eb"></i>
                                Choose Template
                            </div>

                            <div class="it-grid">

                                <!-- Template 1: Classic -->
                                <label class="it-tpl <?= ($settings['template_id'] ?? 1) == 1 ? 'active' : '' ?>" data-tpl="1">
                                    <input type="radio" name="template_id" value="1" <?= ($settings['template_id'] ?? 1) == 1 ? 'checked' : '' ?>>
                                    <div class="it-tpl-preview">
                                        <svg viewBox="0 0 210 297" width="100%" style="padding:12px">
                                            <!-- Classic: clean lines, top header -->
                                            <rect x="0" y="0" width="210" height="297" fill="#fff" rx="4"/>
                                            <rect x="12" y="12" width="186" height="30" fill="#0f172a" rx="3"/>
                                            <rect x="16" y="18" width="60" height="6" fill="#fff" rx="1"/>
                                            <rect x="140" y="18" width="50" height="6" fill="#60a5fa" rx="1"/>
                                            <rect x="12" y="52" width="85" height="20" fill="#f1f5f9" rx="2"/>
                                            <rect x="107" y="52" width="91" height="20" fill="#f1f5f9" rx="2"/>
                                            <rect x="12" y="82" width="186" height="5" fill="#0f172a" rx="1"/>
                                            <rect x="12" y="92" width="186" height="4" fill="#e2e8f0" rx="1"/>
                                            <rect x="12" y="101" width="186" height="4" fill="#f8fafc" rx="1"/>
                                            <rect x="12" y="110" width="186" height="4" fill="#e2e8f0" rx="1"/>
                                            <rect x="12" y="119" width="186" height="4" fill="#f8fafc" rx="1"/>
                                            <rect x="130" y="135" width="68" height="22" fill="#f1f5f9" rx="2"/>
                                            <rect x="134" y="140" width="60" height="3" fill="#94a3b8" rx="1"/>
                                            <rect x="134" y="148" width="60" height="4" fill="#0f172a" rx="1"/>
                                        </svg>
                                    </div>
                                    <div class="it-tpl-info">
                                        <h5>Classic</h5>
                                        <p>Clean & professional</p>
                                    </div>
                                </label>

                                <!-- Template 2: Modern -->
                                <label class="it-tpl <?= ($settings['template_id'] ?? 1) == 2 ? 'active' : '' ?>" data-tpl="2">
                                    <input type="radio" name="template_id" value="2" <?= ($settings['template_id'] ?? 1) == 2 ? 'checked' : '' ?>>
                                    <div class="it-tpl-preview">
                                        <svg viewBox="0 0 210 297" width="100%" style="padding:12px">
                                            <rect x="0" y="0" width="210" height="297" fill="#fff" rx="4"/>
                                            <rect x="0" y="0" width="70" height="297" fill="#1e293b" rx="4"/>
                                            <rect x="10" y="20" width="50" height="6" fill="#60a5fa" rx="1"/>
                                            <rect x="10" y="32" width="40" height="3" fill="#64748b" rx="1"/>
                                            <rect x="10" y="60" width="50" height="3" fill="#94a3b8" rx="1"/>
                                            <rect x="10" y="68" width="45" height="3" fill="#64748b" rx="1"/>
                                            <rect x="10" y="76" width="48" height="3" fill="#64748b" rx="1"/>
                                            <rect x="82" y="20" width="55" height="8" fill="#2563eb" rx="1"/>
                                            <rect x="82" y="34" width="40" height="3" fill="#94a3b8" rx="1"/>
                                            <rect x="82" y="55" width="116" height="5" fill="#1e293b" rx="1"/>
                                            <rect x="82" y="65" width="116" height="3" fill="#e2e8f0" rx="1"/>
                                            <rect x="82" y="73" width="116" height="3" fill="#f1f5f9" rx="1"/>
                                            <rect x="82" y="81" width="116" height="3" fill="#e2e8f0" rx="1"/>
                                            <rect x="82" y="89" width="116" height="3" fill="#f1f5f9" rx="1"/>
                                            <rect x="145" y="105" width="53" height="18" fill="#f0f9ff" rx="2"/>
                                        </svg>
                                    </div>
                                    <div class="it-tpl-info">
                                        <h5>Modern</h5>
                                        <p>Sidebar accent layout</p>
                                    </div>
                                </label>

                                <!-- Template 3: Minimal -->
                                <label class="it-tpl <?= ($settings['template_id'] ?? 1) == 3 ? 'active' : '' ?>" data-tpl="3">
                                    <input type="radio" name="template_id" value="3" <?= ($settings['template_id'] ?? 1) == 3 ? 'checked' : '' ?>>
                                    <div class="it-tpl-preview">
                                        <svg viewBox="0 0 210 297" width="100%" style="padding:12px">
                                            <rect x="0" y="0" width="210" height="297" fill="#fff" rx="4"/>
                                            <rect x="12" y="15" width="65" height="7" fill="#0f172a" rx="1"/>
                                            <rect x="12" y="27" width="40" height="3" fill="#cbd5e1" rx="1"/>
                                            <line x1="12" y1="40" x2="198" y2="40" stroke="#e2e8f0" stroke-width="0.5"/>
                                            <rect x="12" y="48" width="50" height="3" fill="#94a3b8" rx="1"/>
                                            <rect x="12" y="56" width="70" height="3" fill="#64748b" rx="1"/>
                                            <rect x="150" y="48" width="48" height="3" fill="#94a3b8" rx="1"/>
                                            <rect x="150" y="56" width="40" height="3" fill="#64748b" rx="1"/>
                                            <line x1="12" y1="70" x2="198" y2="70" stroke="#e2e8f0" stroke-width="0.5"/>
                                            <rect x="12" y="76" width="186" height="3" fill="#f1f5f9" rx="1"/>
                                            <rect x="12" y="84" width="186" height="3" fill="#fff" rx="1"/>
                                            <rect x="12" y="92" width="186" height="3" fill="#f1f5f9" rx="1"/>
                                            <line x1="12" y1="105" x2="198" y2="105" stroke="#e2e8f0" stroke-width="0.5"/>
                                            <rect x="150" y="112" width="48" height="5" fill="#0f172a" rx="1"/>
                                        </svg>
                                    </div>
                                    <div class="it-tpl-info">
                                        <h5>Minimal</h5>
                                        <p>Simple & elegant</p>
                                    </div>
                                </label>

                                <!-- Template 4: Bold -->
                                <label class="it-tpl <?= ($settings['template_id'] ?? 1) == 4 ? 'active' : '' ?>" data-tpl="4">
                                    <input type="radio" name="template_id" value="4" <?= ($settings['template_id'] ?? 1) == 4 ? 'checked' : '' ?>>
                                    <div class="it-tpl-preview">
                                        <svg viewBox="0 0 210 297" width="100%" style="padding:12px">
                                            <rect x="0" y="0" width="210" height="297" fill="#fff" rx="4"/>
                                            <rect x="0" y="0" width="210" height="55" fill="#2563eb" rx="4"/>
                                            <rect x="12" y="14" width="70" height="8" fill="#fff" rx="1"/>
                                            <rect x="12" y="28" width="45" height="3" fill="#bfdbfe" rx="1"/>
                                            <rect x="140" y="14" width="58" height="14" fill="#1d4ed8" rx="6"/>
                                            <rect x="148" y="18" width="42" height="4" fill="#fff" rx="1"/>
                                            <rect x="12" y="65" width="85" height="22" fill="#eff6ff" rx="3"/>
                                            <rect x="107" y="65" width="91" height="22" fill="#eff6ff" rx="3"/>
                                            <rect x="12" y="97" width="186" height="5" fill="#2563eb" rx="1"/>
                                            <rect x="12" y="107" width="186" height="4" fill="#f0f9ff" rx="1"/>
                                            <rect x="12" y="116" width="186" height="4" fill="#fff" rx="1"/>
                                            <rect x="12" y="125" width="186" height="4" fill="#f0f9ff" rx="1"/>
                                            <rect x="130" y="142" width="68" height="24" fill="#2563eb" rx="3"/>
                                            <rect x="138" y="150" width="52" height="5" fill="#fff" rx="1"/>
                                        </svg>
                                    </div>
                                    <div class="it-tpl-info">
                                        <h5>Bold</h5>
                                        <p>Vibrant & branded</p>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <!-- ── COLOR CUSTOMIZATION ── -->
                     <!-- ── COLOR CUSTOMIZATION ── -->
<div class="it-section">
    <div class="it-section-title">
        <i class='bx bx-palette' style="font-size:1.1rem;color:#8b5cf6"></i>
        Customize Colors
    </div>

    <div class="it-colors">
        <div class="it-color-field">
            <label>Primary Color</label>
            <input type="color" name="primary_color" id="primaryColor"
                   value="<?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>"
                   class="it-color-input">
            <input type="text" id="primaryHex"
                   value="<?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>"
                   class="it-color-hex" maxlength="7" readonly>
        </div>
        <div class="it-color-field">
            <label>Accent Color</label>
            <input type="color" name="accent_color" id="accentColor"
                   value="<?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>"
                   class="it-color-input">
            <input type="text" id="accentHex"
                   value="<?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>"
                   class="it-color-hex" maxlength="7" readonly>
        </div>
    </div>

    <!-- Live Preview with Logo -->
    <!-- Live Preview with Logo, Address, Colors -->
<div style="margin-top:1.25rem">
    <p style="font-size:0.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.5rem">Live Preview</p>
    <div class="it-live-preview" id="livePreview">
        
        <!-- Header Bar -->
      <div class="it-preview-header-bar" id="previewHeaderBar"
     style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>);
            padding:10px 14px;border-radius:8px 8px 0 0;color:#fff;
            display:flex;justify-content:space-between;align-items:center">
 
    <div style="display:flex;align-items:center;gap:0.5rem">
        <?php if (!empty($logoUrl)): ?>
            <img src="<?= htmlspecialchars($logoUrl) ?>"
                 alt="Logo" id="previewLogo"
                 style="height:28px;width:auto;border-radius:4px;object-fit:contain">
        <?php else: ?>
            <div id="previewLogoPlaceholder"
                 style="width:28px;height:28px;border-radius:4px;background:rgba(255,255,255,0.15);
                        display:flex;align-items:center;justify-content:center">
                <i class='bx bx-image' style="color:rgba(255,255,255,0.5);font-size:0.85rem"></i>
            </div>
        <?php endif; ?>
    </div>
 
    <div style="text-align:right">
        <!-- FIX: use $adminBusiness instead of $this->admin['business_name'] -->
        <div style="font-size:0.82rem;font-weight:800">
            <?= htmlspecialchars($adminBusiness) ?>
        </div>
 
        <!-- FIX: use $adminAddress instead of $this->admin['address'] -->
        <?php if (!empty($adminAddress)): ?>
            <div style="font-size:0.6rem;color:rgba(255,255,255,0.65);line-height:1.4;margin-top:1px">
                <?= htmlspecialchars($adminAddress) ?>
            </div>
        <?php endif; ?>
 
        <!-- FIX: use $adminMobile instead of $this->admin['mobile'] -->
        <?php if (!empty($adminMobile)): ?>
            <div style="font-size:0.6rem;color:rgba(255,255,255,0.65)">
                📞 <?= htmlspecialchars($adminMobile) ?>
            </div>
        <?php endif; ?>
 
        <!-- FIX: use $adminInstagram / $adminFacebook -->
        <?php if (!empty($adminInstagram) || !empty($adminFacebook)): ?>
            <div style="font-size:0.55rem;color:rgba(255,255,255,0.5);margin-top:1px">
                <?php if (!empty($adminInstagram)): ?>
                    📷 @<?= htmlspecialchars($adminInstagram) ?>
                <?php endif; ?>
                <?php if (!empty($adminInstagram) && !empty($adminFacebook)): ?>
                    &nbsp;|&nbsp;
                <?php endif; ?>
                <?php if (!empty($adminFacebook)): ?>
                    📘 fb/<?= htmlspecialchars($adminFacebook) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

        <!-- Tax Invoice Title -->
        <div id="previewTitle" style="text-align:center;padding:6px 0;border-bottom:2px solid var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">
            <span style="font-size:0.72rem;font-weight:800;color:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>);text-transform:uppercase;letter-spacing:0.1em">Tax Invoice</span>
        </div>

        <!-- Bill To + Invoice Details -->
        <div style="display:flex;justify-content:space-between;padding:8px 10px;gap:10px">
            <div>
              <div data-accent-label style="font-size:0.55rem;font-weight:700;text-transform:uppercase;color:var(--inv-accent, <?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>);margin-bottom:3px;border-bottom:1.5px solid var(--inv-accent, <?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>);display:inline-block;padding-bottom:1px">Bill To</div>
                <div style="font-size:0.65rem;color:#334155;line-height:1.5">
                    <strong>Cash Sale</strong><br>
                    9876543210
                </div>
            </div>
            <div style="text-align:right">
<div data-accent-label style="font-size:0.55rem;font-weight:700;text-transform:uppercase;color:var(--inv-accent, <?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>);margin-bottom:3px;border-bottom:1.5px solid var(--inv-accent, <?= htmlspecialchars($settings['accent_color'] ?? '#2563eb') ?>);display:inline-block;padding-bottom:1px">Invoice Details</div>                <div style="font-size:0.62rem;color:#64748b;line-height:1.5">
                    <span style="color:#94a3b8">No:</span> <strong style="color:#0f172a">INV-001</strong><br>
                    <span style="color:#94a3b8">Date:</span> <strong style="color:#0f172a"><?= date('d/m/Y') ?></strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="it-preview-table" style="margin:0 10px;width:calc(100% - 20px)">
            <thead>
                <tr id="previewTableHead">
                    <th style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">#</th>
                    <th style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">Item</th>
                    <th style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">Qty</th>
                    <th style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">Price</th>
                    <th style="background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Sample Product</td><td>2</td><td>Rs. 250</td><td>Rs. 500.00</td></tr>
                <tr><td>2</td><td>Another Item</td><td>1</td><td>Rs. 250</td><td>Rs. 250.00</td></tr>
            </tbody>
            <tfoot>
                <tr id="previewTableFoot" style="border-top:2px solid var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>)">
                    <td></td><td><strong>Total</strong></td><td><strong>3</strong></td><td></td><td><strong>Rs. 750.00</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Bottom: Words + Summary -->
        <div style="display:flex;gap:10px;padding:8px 10px;margin-top:4px">
            <!-- Left: Amount in Words + Terms -->
            <div style="flex:1">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:5px 8px;margin-bottom:6px">
                    <div style="font-size:0.5rem;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:2px">Amount in Words</div>
                    <div style="font-size:0.6rem;color:#0f172a;font-weight:600;font-style:italic">Seven Hundred Fifty Rupees only</div>
                </div>
                <div style="background:#fffbeb;border:1px solid #fef08a;border-radius:4px;padding:5px 8px">
                    <div style="font-size:0.5rem;font-weight:700;text-transform:uppercase;color:#92400e;margin-bottom:2px">Terms</div>
                    <div style="font-size:0.55rem;color:#78350f">Thanks for doing business with us!</div>
                </div>
            </div>
            <!-- Right: Summary -->
            <div style="width:140px;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden">
                <div style="display:flex;justify-content:space-between;padding:4px 8px;font-size:0.6rem;color:#64748b;border-bottom:1px solid #f1f5f9">
                    <span>Sub Total</span><span style="color:#0f172a;font-weight:600">Rs. 750</span>
                </div>
                <div id="previewTotalRow" style="display:flex;justify-content:space-between;padding:5px 8px;font-size:0.65rem;font-weight:800;background:var(--inv-primary, <?= htmlspecialchars($settings['primary_color'] ?? '#0f172a') ?>);color:#fff">
                    <span>Total</span><span>Rs. 750.00</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        <div style="text-align:right;padding:8px 10px 6px">
<div style="font-size:0.55rem;color:#64748b">
    For: <strong style="color:#0f172a"><?= htmlspecialchars($adminBusiness) ?></strong>
</div>            <div class="it-preview-sig" id="previewSig" style="margin-top:3px">
                <?php if (!empty($settings['signature_image'])): ?>
                    <img src="<?= base_url('uploads/signatures/' . $settings['signature_image']) ?>" alt="Signature" style="max-height:35px">
                <?php else: ?>
                    <div style="height:30px"></div>
                <?php endif; ?>
                <div style="width:100px;border-top:1px solid #cbd5e1;margin-left:auto;padding-top:3px;font-size:0.5rem;color:#94a3b8;text-align:center">Authorized Signatory</div>
            </div>
        </div>

    </div>
</div>

    <!-- Logo Info Note -->
    <?php if (empty($logoUrl)): ?>
        <div style="margin-top:0.75rem;padding:0.6rem 0.85rem;background:#fffbeb;border:1px solid #fef08a;border-radius:10px;font-size:0.78rem;color:#92400e;display:flex;align-items:center;gap:0.4rem">
            <i class='bx bx-info-circle'></i>
            <span>Upload your logo in <a href="<?= base_url('profile') ?>" style="color:#2563eb;font-weight:700">Profile Settings</a> to show it on invoices.</span>
        </div>
    <?php else: ?>
        <div style="margin-top:0.75rem;padding:0.6rem 0.85rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;font-size:0.78rem;color:#166534;display:flex;align-items:center;gap:0.4rem">
            <i class='bx bx-check-circle'></i>
            <span>Logo loaded from your profile. Change it in <a href="<?= base_url('profile') ?>" style="color:#2563eb;font-weight:700">Profile Settings</a>.</span>
        </div>
    <?php endif; ?>
</div>invoice_tpl_1

                        <!-- ── SAVE ── -->
                        <div class="it-save-bar">
                            <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Save Template Settings
                            </button>
                        </div>

                    </form>

                    <!-- ── SIGNATURE SECTION (separate form) ── -->
                    <div class="it-section" style="margin-top:1.5rem">
                        <div class="it-section-title">
                            <i class='bx bx-pen' style="font-size:1.1rem;color:#059669"></i>
                            Authorized Signature
                        </div>

                        <div class="it-sig-area">
                            <div class="it-sig-preview" id="sigPreview">
                                <?php if (!empty($settings['signature_image'])): ?>
                                    <img src="<?= base_url('uploads/signatures/' . $settings['signature_image']) ?>" alt="Signature" id="sigImage">
                                <?php else: ?>
                                    <div class="it-sig-empty" id="sigEmpty">
                                        <i class='bx bx-pen'></i>
                                        No signature uploaded
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="it-sig-actions">
                                <form method="post" action="<?= base_url('invoice_master/upload_signature') ?>" enctype="multipart/form-data" id="sigForm">
                                    <input type="file" name="signature_image" id="sigFileInput" accept=".png,.jpg,.jpeg" style="display:none">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('sigFileInput').click()">
                                        <i class='bx bx-upload'></i>
                                        <?= !empty($settings['signature_image']) ? 'Change Signature' : 'Upload Signature' ?>
                                    </button>
                                    <p class="it-sig-file">PNG or JPG, max 512KB</p>
                                </form>

                                <?php if (!empty($settings['signature_image'])): ?>
                                    <a href="<?= base_url('invoice_master/remove_signature') ?>"
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Remove signature?')">
                                        <i class='bx bx-trash'></i> Remove
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ── SCRIPT ── -->
       <script>
    document.addEventListener('DOMContentLoaded', function () {

        const primaryInput = document.getElementById('primaryColor');
        const accentInput  = document.getElementById('accentColor');
        const primaryHex   = document.getElementById('primaryHex');
        const accentHex    = document.getElementById('accentHex');
        const preview      = document.getElementById('livePreview');

        // Template Selection
        document.querySelectorAll('.it-tpl').forEach(tpl => {
            tpl.addEventListener('click', function () {
                document.querySelectorAll('.it-tpl').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Color sync — Update ALL preview elements
        function updatePreviewColors() {
            const pc = primaryInput.value;
            const ac = accentInput.value;

            primaryHex.value = pc;
            accentHex.value  = ac;

            preview.style.setProperty('--inv-primary', pc);
            preview.style.setProperty('--inv-accent', ac);

            // Header bar background
            const headerBar = document.getElementById('previewHeaderBar');
            if (headerBar) headerBar.style.background = pc;

            // Title text + border
            const titleBar = document.getElementById('previewTitle');
            if (titleBar) {
                titleBar.style.borderBottomColor = pc;
                const titleSpan = titleBar.querySelector('span');
                if (titleSpan) titleSpan.style.color = pc;
            }

            // Table header cells
            const thElements = document.querySelectorAll('#previewTableHead th');
            thElements.forEach(th => th.style.background = pc);

            // Table footer border
            const tfoot = document.getElementById('previewTableFoot');
            if (tfoot) tfoot.style.borderTopColor = pc;

            // Total row background
            const totalRow = document.getElementById('previewTotalRow');
            if (totalRow) totalRow.style.background = pc;

            // Accent color labels (Bill To, Invoice Details)
            preview.querySelectorAll('[data-accent-label]').forEach(el => {
                el.style.color = ac;
                el.style.borderBottomColor = ac;
            });
        }

        primaryInput.addEventListener('input', updatePreviewColors);
        accentInput.addEventListener('input', updatePreviewColors);

        // Init colors on page load
        updatePreviewColors();

        // Signature auto-submit
        document.getElementById('sigFileInput').addEventListener('change', function () {
            if (this.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewBox = document.getElementById('sigPreview');
                    previewBox.innerHTML = '<img src="' + e.target.result + '" alt="Signature" id="sigImage">';

                    const pvSig = document.getElementById('previewSig');
                    const existingImg = pvSig.querySelector('img');
                    if (existingImg) {
                        existingImg.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Signature';
                        img.style.maxHeight = '35px';
                        pvSig.insertBefore(img, pvSig.firstChild);
                    }
                };
                reader.readAsDataURL(this.files[0]);

                document.getElementById('sigForm').submit();
            }
        });
    });
</script>
    </div>
</div>