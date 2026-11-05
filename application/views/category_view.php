<div class="page-wrapper">
    <div class="page-content">
        <style>
            .category-shell {
                width: 100%;
                max-width: 1080px;
            }

            .category-card {
                border: 1px solid #e2e8f0;
                box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .category-form-card {
                background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
            }

            .category-title {
                font-size: 1.9rem;
                font-weight: 700;
                color: #0f172a;
            }

            .category-copy {
                color: #64748b;
                max-width: 560px;
            }

            .category-hero {
                border: 1px solid #dbe7f5;
                border-radius: 28px;
                background: linear-gradient(135deg, #eff6ff 0%, #ffffff 58%, #ecfeff 100%);
                padding: 1.5rem;
            }

            .category-field label {
                font-weight: 600;
                color: #334155;
                margin-bottom: 0.45rem;
            }

            .category-field .form-control {
                min-height: 54px;
                border-radius: 14px;
                border-color: #dbe3ef;
            }

            .category-field .form-control:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.18);
            }

            .category-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 0.8rem;
                border-radius: 999px;
                background: #eff6ff;
                color: #2563eb;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .category-table thead th {
                font-size: 0.82rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #64748b;
                border-bottom-width: 1px;
            }

            .category-table tbody td {
                vertical-align: middle;
            }

            .category-name {
                font-weight: 700;
                color: #0f172a;
            }

            .category-meta {
                color: #64748b;
                font-size: 0.88rem;
            }

            .category-empty {
                padding: 2rem;
                text-align: center;
                color: #64748b;
            }

            .category-row-title {
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 0;
            }

            .category-table tbody tr:hover {
                background: #f8fbff;
            }

            .category-action-group {
                display: inline-flex;
                flex-wrap: wrap;
                gap: 0.4rem;
                justify-content: flex-end;
            }

            /* Icon Action Buttons */
            .btn-icon-edit,
            .btn-icon-delete {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 10px;
                border: none;
                font-size: 1rem;
                transition: all 0.18s ease;
                text-decoration: none;
                flex-shrink: 0;
            }

            .btn-icon-edit {
                background: #fef9ec;
                color: #d97706;
                border: 1px solid #fde68a;
            }

            .btn-icon-edit:hover {
                background: #fef3c7;
                color: #b45309;
                border-color: #fbbf24;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(217, 119, 6, 0.18);
            }

            .btn-icon-delete {
                background: #fff5f5;
                color: #dc2626;
                border: 1px solid #fecaca;
            }

            .btn-icon-delete:hover {
                background: #fee2e2;
                color: #b91c1c;
                border-color: #f87171;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(220, 38, 38, 0.18);
            }

            .category-stat {
                min-height: 126px;
                background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            }

            .category-stat-value {
                font-size: 2rem;
                font-weight: 800;
                color: #0f172a;
                line-height: 1;
            }

            .category-stat-label {
                color: #64748b;
                font-weight: 600;
            }

            /* ── Pagination ── */
            .cat-pagination-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                padding-top: 1.4rem;
                gap: 0.3rem;
            }

            .cat-page-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 38px;
                height: 38px;
                padding: 0 0.5rem;
                border-radius: 999px;
                border: 1.5px solid #e2e8f0;
                background: #ffffff;
                color: #475569;
                font-size: 0.9rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.16s ease;
                cursor: pointer;
                line-height: 1;
            }

            .cat-page-btn:hover {
                border-color: #93c5fd;
                background: #eff6ff;
                color: #2563eb;
            }

            .cat-page-btn.active {
                background: #2563eb;
                border-color: #2563eb;
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(37, 99, 235, 0.32);
            }

            .cat-page-btn.disabled,
            .cat-page-btn[disabled] {
                opacity: 0.38;
                pointer-events: none;
            }

            .cat-page-btn.dots {
                border-color: transparent;
                background: transparent;
                color: #94a3b8;
                pointer-events: none;
                letter-spacing: 0.05em;
            }

            .cat-page-arrow {
                font-size: 1rem;
            }

            /* ── Summary bar ── */
            .cat-summary-bar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 0.6rem;
                padding-top: 1rem;
                border-top: 1px solid #f1f5f9;
                margin-top: 0.2rem;
            }

            .cat-summary-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 999px;
                padding: 0.28rem 0.85rem;
                font-size: 0.84rem;
                font-weight: 600;
                color: #334155;
            }

            .cat-summary-pill .dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                display: inline-block;
                flex-shrink: 0;
            }

            .dot-blue {
                background: #3b82f6;
            }

            .dot-green {
                background: #22c55e;
            }

            /* ── Tablet (max 991px) ── */
            @media (max-width: 991.98px) {
                .category-shell {
                    max-width: 100%;
                }
            }

            /* ── Mobile (max 767px) ── */
            @media (max-width: 767.98px) {
                .category-hero {
                    padding: 1.1rem;
                    border-radius: 22px;
                }

                .category-title {
                    font-size: 1.55rem;
                }

                .category-table thead th {
                    font-size: 0.74rem;
                }

                .category-action-group {
                    width: 100%;
                    justify-content: flex-start;
                }

                .cat-pagination-wrap {
                    flex-wrap: wrap;
                }

                /* Hero: stack vertically, tighten gaps */
                .category-hero .d-flex {
                    flex-direction: column;
                    gap: 0.75rem !important;
                }

                /* Form card: reduce padding */
                .category-form-card .card-body {
                    padding: 1.25rem !important;
                }

                /* Table card: reduce padding */
                .category-card .card-body {
                    padding: 1rem !important;
                }

                /* Stat cards: reduce min-height, tighten */
                .category-stat {
                    min-height: 90px;
                }

                .category-stat-value {
                    font-size: 1.6rem;
                }

                /* Make stat cards side-by-side on small screens */
                .category-stat .card-body {
                    padding: 0.9rem 1rem !important;
                }

                /* Table: hide "Created On" column on very small screens to avoid overflow */
                .category-table .col-created,
                .category-table th.col-created {
                    display: none;
                }

                /* Table row number column: tighter */
                .category-table td:first-child,
                .category-table th:first-child {
                    width: 32px;
                    padding-left: 0.4rem;
                    padding-right: 0.4rem;
                }

                /* Action column: don't shrink */
                .category-table td:last-child {
                    min-width: 80px;
                }

                /* Buttons: full width on small screens */
                .category-form-card .d-flex.flex-wrap button,
                .category-form-card .d-flex.flex-wrap a {
                    flex: 1 1 100%;
                    text-align: center;
                    justify-content: center;
                }

                /* Summary bar: wrap nicely */
                .cat-summary-bar {
                    justify-content: center;
                }

                /* Pagination: smaller buttons */
                .cat-page-btn {
                    min-width: 34px;
                    height: 34px;
                    font-size: 0.82rem;
                }

                /* Hero chip alignment */
                .category-chip {
                    font-size: 0.78rem;
                    padding: 0.35rem 0.65rem;
                }
            }

            /* ── Extra small (max 480px) ── */
            @media (max-width: 479.98px) {
                .category-title {
                    font-size: 1.3rem;
                }

                .category-copy {
                    font-size: 0.88rem;
                }

                .category-hero {
                    padding: 0.9rem;
                    border-radius: 16px;
                }

                /* Stack stat cards vertically */
                .row .col-12.col-md-6 {
                    padding-bottom: 0;
                }

                .category-stat {
                    min-height: 80px;
                }

                .category-stat-value {
                    font-size: 1.4rem;
                }

                .category-stat-label {
                    font-size: 0.8rem;
                }

                /* Table font shrink */
                .category-table tbody td {
                    font-size: 0.85rem;
                    padding: 0.5rem 0.4rem;
                }

                .category-row-title {
                    font-size: 0.88rem;
                }

                /* Input field height */
                .category-field .form-control {
                    min-height: 46px;
                    font-size: 0.9rem;
                }

                /* Pagination tighter */
                .cat-pagination-wrap {
                    gap: 0.2rem;
                }

                .cat-page-btn {
                    min-width: 30px;
                    height: 30px;
                    font-size: 0.78rem;
                }

                /* Summary pills wrap */
                .cat-summary-pill {
                    font-size: 0.78rem;
                    padding: 0.22rem 0.65rem;
                }
            }
        </style>

        <?php
        /* ── Pagination config ── */
        $perPage      = 10;
        $totalRecords = count($categories);
        $totalPages   = max(1, (int) ceil($totalRecords / $perPage));
        $currentPage  = max(1, min((int) ($this->input->get('page') ?? 1), $totalPages));
        $offset       = ($currentPage - 1) * $perPage;
        $pageCategories = array_slice($categories, $offset, $perPage);
        ?>

        <div class="category-shell mx-auto">
            <div class="category-hero mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="category-chip mb-3">
                            <i class='bx bx-user-pin'></i>
                            <span>Admin: <?= htmlspecialchars($adminName ?? 'Admin') ?></span>
                        </div>
                        <div class="category-title mb-2">Category Manager</div>
                        <p class="category-copy mb-0">Create, update, and organize your product categories from one clean screen.</p>
                    </div>
                    <div class="category-chip">
                        <i class='bx bx-collection'></i>
                        <span><?= $totalRecords ?> Active</span>
                    </div>
                </div>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- ── Left: Form ── -->
                <div class="col-12 col-xl-4">
                    <div class="card rounded-4 category-card category-form-card h-100">
                        <div class="card-body p-4 p-xl-5">
                            <div class="category-title mb-2"><?= !empty($editCategory) ? 'Edit Category' : 'Add Category' ?></div>
                            <p class="category-copy mb-4">Categories are linked to the logged-in admin account only.</p>

                            <form method="post" action="<?= !empty($editCategory) ? base_url('category/update/' . (int) $editCategory['id']) : base_url('category/store') ?>">
                                <div class="category-field mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="name" value="<?= set_value('name', $editCategory['name'] ?? '') ?>" class="form-control" placeholder="Enter category name">
                                    <?= form_error('name') ?>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary px-4"><?= !empty($editCategory) ? 'Update Category' : 'Add Category' ?></button>
                                    <?php if (!empty($editCategory)): ?>
                                        <a href="<?= base_url('category') ?>" class="btn btn-outline-secondary px-4">Cancel</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ── Right: Stats + Table ── -->
                <div class="col-12 col-xl-8">

                    <!-- Stat Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="card rounded-4 category-card category-stat">
                                <div class="card-body p-4">
                                    <div class="category-stat-value"><?= $totalRecords ?></div>
                                    <p class="category-stat-label mb-0 mt-2">Total Active Categories</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card rounded-4 category-card category-stat">
                                <div class="card-body p-4">
                                    <div class="category-stat-value"><?= !empty($editCategory) ? 'Edit' : 'Ready' ?></div>
                                    <p class="category-stat-label mb-0 mt-2"><?= !empty($editCategory) ? 'You are updating an existing category' : 'Create a new category from the form' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Card -->
                    <div class="card rounded-4 category-card">
                        <div class="card-body p-4 p-xl-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div>
                                    <h3 class="mb-1">Your Categories</h3>
                                    <p class="text-muted mb-0">Manage your category list with quick edit and delete actions.</p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle category-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th class="col-created">Created On</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pageCategories)): ?>
                                            <?php foreach ($pageCategories as $index => $category): ?>
                                                <tr>
                                                    <td><?= $offset + $index + 1 ?></td>
                                                    <td>
                                                        <div class="category-row-title"><?= htmlspecialchars($category['name']) ?></div>
                                                    </td>
                                                    <td class="col-created"><?= htmlspecialchars($category['created_on']) ?></td>
                                                    <td class="text-end">
                                                        <div class="category-action-group">
                                                            <!-- Edit icon (pencil) -->
                                                            <a href="<?= base_url('category/edit/' . (int) $category['id']) ?>"
                                                                class="btn-icon-edit"
                                                                title="Edit">
                                                                <i class='bx bx-edit-alt'></i>
                                                            </a>
                                                            <!-- Delete icon (trash) -->
                                                            <a href="<?= base_url('category/delete/' . (int) $category['id']) ?>"
                                                                class="btn-icon-delete js-swal-delete"
                                                                data-swal-title="Delete category?"
                                                                data-swal-text="This category will be removed from your active list."
                                                                title="Delete">
                                                                <i class='bx bx-trash'></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="category-empty text-danger fw-bold">No categories found for this admin yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ── Pagination ── -->
                            <?php if ($totalPages > 1): ?>
                                <?php
                                $baseUrl = base_url('category');

                                function catPageUrl($base, $page)
                                {
                                    return $base . '?page=' . $page;
                                }

                                function catPageRange($current, $total)
                                {
                                    $delta = 2;
                                    $range = [];
                                    $rangeWithDots = [];
                                    $l = null;

                                    for ($i = 1; $i <= $total; $i++) {
                                        if ($i === 1 || $i === $total || ($i >= $current - $delta && $i <= $current + $delta)) {
                                            $range[] = $i;
                                        }
                                    }

                                    foreach ($range as $i) {
                                        if ($l !== null) {
                                            if ($i - $l === 2) {
                                                $rangeWithDots[] = $l + 1;
                                            } elseif ($i - $l > 2) {
                                                $rangeWithDots[] = '...';
                                            }
                                        }
                                        $rangeWithDots[] = $i;
                                        $l = $i;
                                    }

                                    return $rangeWithDots;
                                }

                                $pages = catPageRange($currentPage, $totalPages);
                                ?>
                                <div class="cat-pagination-wrap">
                                    <!-- Prev arrow -->
                                    <?php if ($currentPage > 1): ?>
                                        <a href="<?= catPageUrl($baseUrl, $currentPage - 1) ?>" class="cat-page-btn cat-page-arrow" title="Previous">
                                            <i class='bx bx-chevron-left'></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="cat-page-btn cat-page-arrow disabled" title="Previous">
                                            <i class='bx bx-chevron-left'></i>
                                        </span>
                                    <?php endif; ?>

                                    <!-- Page numbers -->
                                    <?php foreach ($pages as $p): ?>
                                        <?php if ($p === '...'): ?>
                                            <span class="cat-page-btn dots">…</span>
                                        <?php elseif ($p === $currentPage): ?>
                                            <span class="cat-page-btn active"><?= $p ?></span>
                                        <?php else: ?>
                                            <a href="<?= catPageUrl($baseUrl, $p) ?>" class="cat-page-btn"><?= $p ?></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <!-- Next arrow -->
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="<?= catPageUrl($baseUrl, $currentPage + 1) ?>" class="cat-page-btn cat-page-arrow" title="Next">
                                            <i class='bx bx-chevron-right'></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="cat-page-btn cat-page-arrow disabled" title="Next">
                                            <i class='bx bx-chevron-right'></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- ── Summary bar ── -->
                            <div class="cat-summary-bar">
                                <div class="cat-summary-pill">
                                    <span class="dot dot-blue"></span>
                                    Total Records: <strong><?= $totalRecords ?></strong>
                                </div>
                                <div class="cat-summary-pill">
                                    <span class="dot dot-green"></span>
                                    Page <?= $currentPage ?> of <?= $totalPages ?>
                                </div>
                            </div>

                        </div><!-- /card-body -->
                    </div><!-- /card -->
                </div><!-- /col right -->
            </div><!-- /row -->
        </div><!-- /shell -->
    </div>
</div>