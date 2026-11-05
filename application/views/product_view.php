<div class="page-wrapper">
    <div class="page-content">
        <style>
            .product-shell {
                max-width: 1200px;
            }

            .product-card {
                border: 1px solid #e2e8f0;
                box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            }

            .product-kicker {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 0.85rem;
                border-radius: 999px;
                background: #eff6ff;
                color: #2563eb;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .product-thumb {
                width: 56px;
                height: 56px;
                min-width: 56px;
                border-radius: 14px;
                object-fit: cover;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                flex-shrink: 0;
            }

            .product-name {
                font-weight: 700;
                color: #0f172a;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .product-meta {
                color: #64748b;
                font-size: 0.88rem;
                max-width: 340px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Ensure text column never pushes the image out */
            .product-info-wrap {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 0;
            }

            .product-info-wrap>div {
                min-width: 0;
                flex: 1;
                overflow: hidden;
            }

            .product-info-wrap .product-name {
                white-space: normal;
                word-break: break-word;
            }

            .product-price {
                font-weight: 700;
                color: #16a34a;
            }

            .product-stock {
                font-weight: 700;
                color: #0f172a;
            }

            .product-empty {
                padding: 2.5rem;
                text-align: center;
                color: #64748b;
            }

            /* ── Icon Action Buttons ── */
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

            .product-action-group {
                display: inline-flex;
                gap: 0.4rem;
                justify-content: flex-end;
            }

            /* ── Pagination ── */
            .cat-pagination-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                padding-top: 1.4rem;
                gap: 0.3rem;
                flex-wrap: wrap;
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

            .cat-page-btn.disabled {
                opacity: 0.38;
                pointer-events: none;
            }

            .cat-page-btn.dots {
                border-color: transparent;
                background: transparent;
                color: #94a3b8;
                pointer-events: none;
            }

            /* ── Summary bar ── */
            .cat-summary-bar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 0.6rem;
                padding-top: 1rem;
                border-top: 1px solid #f1f5f9;
                margin-top: 0.4rem;
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
                .product-shell {
                    max-width: 100%;
                }

                /* Hide Category and Created On columns */
                .prod-col-category,
                .prod-col-created {
                    display: none;
                }

                .product-card .card-body {
                    padding: 1.25rem !important;
                }
            }

            /* ── Mobile (max 767px) ── */
            @media (max-width: 767.98px) {
                .product-card .card-body {
                    padding: 1rem !important;
                }

                /* Header: stack title and button */
                .product-card .d-flex.justify-content-between {
                    flex-direction: column;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                }

                .product-card .d-flex.justify-content-between>a.btn {
                    width: 100%;
                    text-align: center;
                }

                /* Thumbnail: smaller on mobile */
                .product-thumb {
                    width: 42px;
                    height: 42px;
                    min-width: 42px;
                    border-radius: 10px;
                    flex-shrink: 0;
                }

                /* Product name & meta */
                .product-name {
                    font-size: 0.88rem;
                }

                .product-meta {
                    font-size: 0.78rem;
                    max-width: 180px;
                }

                /* Hide Stock column — show inline with name instead */
                .prod-col-stock {
                    display: none;
                }

                /* Table cells: tighter padding */
                .product-card table thead th,
                .product-card table tbody td {
                    padding: 0.5rem 0.4rem;
                    font-size: 0.82rem;
                }

                /* # column: narrow */
                .product-card table thead th:first-child,
                .product-card table tbody td:first-child {
                    width: 28px;
                    padding-left: 0.25rem;
                    padding-right: 0.25rem;
                }

                /* Action column: don't shrink */
                .product-card table tbody td:last-child {
                    min-width: 80px;
                }

                /* Action buttons: slightly smaller */
                .btn-icon-edit,
                .btn-icon-delete {
                    width: 30px;
                    height: 30px;
                    font-size: 0.9rem;
                }

                /* Pagination: tighter */
                .cat-page-btn {
                    min-width: 34px;
                    height: 34px;
                    font-size: 0.82rem;
                }

                /* Summary bar: centered */
                .cat-summary-bar {
                    justify-content: center;
                }

                .cat-summary-pill {
                    font-size: 0.78rem;
                }
            }

            /* ── Extra small (max 479px) ── */
            @media (max-width: 479.98px) {
                .product-card .card-body {
                    padding: 0.75rem !important;
                }

                .product-thumb {
                    width: 36px;
                    height: 36px;
                    min-width: 36px;
                    border-radius: 8px;
                    flex-shrink: 0;
                }

                .product-name {
                    font-size: 0.83rem;
                }

                .product-meta {
                    font-size: 0.74rem;
                    max-width: 130px;
                }

                /* Hide price column too — very narrow screens */
                .prod-col-price {
                    display: none;
                }

                .product-card table thead th,
                .product-card table tbody td {
                    padding: 0.4rem 0.3rem;
                    font-size: 0.78rem;
                }

                .btn-icon-edit,
                .btn-icon-delete {
                    width: 28px;
                    height: 28px;
                    font-size: 0.85rem;
                    border-radius: 8px;
                }

                .cat-page-btn {
                    min-width: 30px;
                    height: 30px;
                    font-size: 0.76rem;
                }

                .cat-pagination-wrap {
                    gap: 0.2rem;
                }
            }
            .barcode-copies-input {
    width: 55px;
    padding: 4px 6px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    color: #1e293b;
    background: #f8fafc;
    transition: all 0.2s ease;
}

.barcode-copies-input:focus {
    outline: none;
    border-color: #2563eb;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Remove number input spinners for cleaner look (optional) */
.barcode-copies-input::-webkit-outer-spin-button,
.barcode-copies-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.barcode-copies-input[type=number] {
    -moz-appearance: textfield;
}

/* Wrapper spacing for checkbox + input combo */
.barcode-select-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
        </style>

        <?php
        /* ── Pagination config ── */
        $perPage      = 10;
        $totalRecords = count($products);
        $totalPages   = max(1, (int) ceil($totalRecords / $perPage));
        $currentPage  = max(1, min((int) ($this->input->get('page') ?? 1), $totalPages));
        $offset       = ($currentPage - 1) * $perPage;
        $pageProducts = array_slice($products, $offset, $perPage);

        /* Helper: page URL */
        function prodPageUrl($base, $page)
        {
            return $base . '?page=' . $page;
        }

        /* Smart page range with … gaps */
        function prodPageRange($current, $total)
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

        $baseUrl = base_url('product');
        $pages   = prodPageRange($currentPage, $totalPages);
        ?>

        <div class="row product-shell">
            <div class="col-12">
                <div class="card rounded-4 product-card">
                    <div class="card-body p-4 p-xl-5">
                       <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <div class="product-kicker mb-3">
            <i class='bx bx-box'></i>
            <span>Products</span>
        </div>
        <h3 class="mb-1">Your Product List</h3>
    </div>
    <div class="d-flex gap-2">
        <button type="button" id="printBarcodeBtn" class="btn btn-outline-primary px-4" disabled>
            <i class='bx bx-printer'></i> Print Selected Barcodes
        </button>
        <a href="<?= base_url('product/add') ?>" class="btn btn-primary px-4">Add Product</a>
    </div>
</div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                               <thead>
    <tr>
        <th style="width:36px">
            <input type="checkbox" id="selectAllBarcode">
        </th>
        <th>#</th>
        <th>Product</th>
        <th class="prod-col-category">Category</th>
        <th class="prod-col-price">Price</th>
        <th class="prod-col-stock">Stock</th>
        <th class="prod-col-created">Created On</th>
        <th class="text-end">Action</th>
    </tr>
</thead>
                                <tbody>
                                    <?php if (!empty($pageProducts)): ?>
                                        <?php foreach ($pageProducts as $index => $product): ?>
                                            <?php $imageSource = !empty($product['image']) ? base_url('uploads/product/' . ltrim($product['image'], '/')) : base_url('assets/images/Product_Default.png'); ?>
                                            <tr>
<td>
    <div class="barcode-select-wrap">
        <input type="checkbox" class="barcode-select" 
               value="<?= (int) $product['id'] ?>" 
               data-barcode="<?= htmlspecialchars($product['barcode'] ?? '') ?>">
        <input type="number" class="barcode-copies-input" 
               value="<?= (int) ($product['quantity'] ?? 1) ?>" 
               min="1" max="1000" 
               title="Number of copies to print">
    </div>
</td>
    <td><?= $offset + $index + 1 ?></td>                                                <td>
                                                    <div class="product-info-wrap">
                                                        <img src="<?= htmlspecialchars($imageSource) ?>" class="product-thumb" alt="Product image" onerror="this.src='<?= base_url('assets/images/Product_Default.png') ?>'">
                                                        <div>
                                                            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                                            <div class="product-meta"><?= htmlspecialchars($product['description'] !== '' ? $product['description'] : 'No description added') ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="prod-col-category"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                                <td class="product-price prod-col-price">Rs. <?= number_format((float) $product['sale_price'], 2) ?></td>
                                                <td class="product-stock prod-col-stock"><?= (int) ($product['quantity'] ?? 0) ?></td>
                                                <td class="prod-col-created"><?= htmlspecialchars($product['created_on']) ?></td>
                                                <td class="text-end">
                                                   <div class="product-action-group">
   <button type="button" 
        class="btn-icon-edit barcode-row-print-btn" 
        data-id="<?= (int) $product['id'] ?>" 
        title="Print Barcode">
    <i class='bx bx-printer'></i>
</button>
    <a href="<?= base_url('product/edit/' . (int) $product['id']) ?>"
       class="btn-icon-edit"
       title="Edit">
        <i class='bx bx-edit-alt'></i>
    </a>
    <a href="<?= base_url('product/delete/' . (int) $product['id']) ?>"
       class="btn-icon-delete js-swal-delete"
       data-swal-title="Delete product?"
       data-swal-text="This product will be removed from your active list."
       title="Delete">
        <i class='bx bx-trash'></i>
    </a>
</div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
<td colspan="8" class="product-empty text-danger fw-bold">No products found for this admin yet.</td>                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- ── Pagination ── -->
                        <?php if ($totalPages > 1): ?>
                            <div class="cat-pagination-wrap">
                                <!-- Prev -->
                                <?php if ($currentPage > 1): ?>
                                    <a href="<?= prodPageUrl($baseUrl, $currentPage - 1) ?>" class="cat-page-btn" title="Previous">
                                        <i class='bx bx-chevron-left'></i>
                                    </a>
                                <?php else: ?>
                                    <span class="cat-page-btn disabled"><i class='bx bx-chevron-left'></i></span>
                                <?php endif; ?>

                                <!-- Page numbers -->
                                <?php foreach ($pages as $p): ?>
                                    <?php if ($p === '...'): ?>
                                        <span class="cat-page-btn dots">…</span>
                                    <?php elseif ($p === $currentPage): ?>
                                        <span class="cat-page-btn active"><?= $p ?></span>
                                    <?php else: ?>
                                        <a href="<?= prodPageUrl($baseUrl, $p) ?>" class="cat-page-btn"><?= $p ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <!-- Next -->
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="<?= prodPageUrl($baseUrl, $currentPage + 1) ?>" class="cat-page-btn" title="Next">
                                        <i class='bx bx-chevron-right'></i>
                                    </a>
                                <?php else: ?>
                                    <span class="cat-page-btn disabled"><i class='bx bx-chevron-right'></i></span>
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAllBarcode');
    const checkboxes = document.querySelectorAll('.barcode-select');
    const printBtn = document.getElementById('printBarcodeBtn');

    function updatePrintBtnState() {
        const anyChecked = [...checkboxes].some(cb => cb.checked);
        printBtn.disabled = !anyChecked;
    }

    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updatePrintBtnState();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updatePrintBtnState);
    });

    printBtn?.addEventListener('click', function() {
        const selectedRows = [...checkboxes].filter(cb => cb.checked);

        if (selectedRows.length === 0) {
            alert('Please select at least one product.');
            return;
        }

        const totalCopies = selectedRows.reduce((sum, cb) => {
            const row = cb.closest('tr');
            const copies = parseInt(row.querySelector('.barcode-copies-input').value) || 1;
            return sum + copies;
        }, 0);

        if (totalCopies > 500) {
            if (!confirm(`You're about to print ${totalCopies} stickers. This may take time to load. Continue?`)) {
                return;
            }
        }

        const pairs = selectedRows.map(cb => {
            const row = cb.closest('tr');
            const copiesInput = row.querySelector('.barcode-copies-input');
            const copies = Math.max(1, parseInt(copiesInput.value) || 1);
            return cb.value + ':' + copies;
        });

        const url = "<?= base_url('product/print_barcode') ?>?ids=" + pairs.join(',');
        window.open(url, '_blank');
    });
});

// Row-level single print button (event delegation — works even after pagination/AJAX reload)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.barcode-row-print-btn');
    if (!btn) return;

    const row = btn.closest('tr');
    const pid = btn.dataset.id;
    const copiesInput = row.querySelector('.barcode-copies-input');
    const copies = Math.max(1, parseInt(copiesInput.value) || 1);

    const url = "<?= base_url('product/print_barcode') ?>?ids=" + pid + ':' + copies;
    window.open(url, '_blank');
});
</script>