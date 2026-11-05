<div class="page-wrapper">
    <div class="page-content">
        <style>
            .sf-shell {
                width: 100%;
                max-width: 1440px;
            }

            .sf-card {
                border: 1px solid #e2e8f0;
                box-shadow: 0 12px 36px rgba(15, 23, 42, .06);
                background: #fff;
                border-radius: 20px;
                overflow: hidden;
            }

            .sf-body {
                padding: 1.25rem 1.5rem;
            }

            .sf-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: 0.8rem 1.5rem;
                background: linear-gradient(135deg, #fffbeb 0%, #fff 58%, #fef3c7 100%);
                border-bottom: 1px solid #fde68a;
            }

            .sf-topbar h2 {
                font-size: 1.15rem;
                font-weight: 800;
                margin: 0;
                color: #0f172a;
            }

            .sf-topbar-left {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .sf-note-edit {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.25rem 0.6rem;
                border-radius: 999px;
                background: #fef3c7;
                color: #a16207;
                font-size: 0.75rem;
                font-weight: 700;
            }

            .sf-cust-row {
                display: flex;
                gap: 0.75rem;
                align-items: flex-end;
                padding: 0.85rem 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .sf-cust-row .sf-f {
                flex: 1;
                min-width: 0;
            }

            .sf-cust-row .sf-f.addr {
                flex: 2;
            }

            .sf-cust-row label {
                display: block;
                font-size: 0.72rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin-bottom: 0.3rem;
            }

            .sf-cust-row .form-control {
                height: 40px;
                border-radius: 10px;
                border-color: #dbe3ef;
                font-size: 0.88rem;
                padding: 0.35rem 0.75rem;
            }

            .sf-grid {
                display: grid;
                grid-template-columns: 1fr 260px;
                gap: 1rem;
                align-items: start;
                margin-top: 0.85rem;
            }

            .sf-items-wrap {
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                overflow: hidden;
                background: #fff;
            }

            .sf-items-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.65rem 1rem;
                background: #f8fafc;
                border-bottom: 2px solid #e2e8f0;
            }

            .sf-items-head h4 {
                font-size: 0.88rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }

            .sf-tbl {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                table-layout: fixed;
            }

            .sf-tbl thead th {
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                font-weight: 700;
                padding: 0.65rem 0.6rem;
                border-bottom: 2px solid #e2e8f0;
                background: #f1f5f9;
                white-space: nowrap;
                text-align: left;
            }

            .sf-tbl tbody td {
                padding: 0.6rem 0.6rem;
                vertical-align: middle;
                border-bottom: 1px solid #e9eff6;
                font-size: 0.88rem;
                background: #fff;
            }

            .sf-tbl tbody tr:nth-child(even) td {
                background: #fafbfd;
            }

            .sf-tbl tbody tr:hover td {
                background: #f0f4ff;
            }

            .sf-tbl tbody tr:last-child td {
                border-bottom: none;
            }

            .sf-tbl .form-control,
            .sf-tbl .form-select {
                height: 38px;
                font-size: 0.84rem;
                border-radius: 8px;
                padding: 0.25rem 0.5rem;
                border: 1px solid #cbd5e1;
                background: #fff;
                width: 100%;
                box-sizing: border-box;
            }

            .sf-tbl .form-select {
                padding-right: 2rem;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 0.5rem center;
                background-size: 12px;
                appearance: none;
            }

            .sf-tbl .form-control:focus,
            .sf-tbl .form-select:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
                outline: none;
            }

            .sf-img-thumb {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                object-fit: cover;
                border: 2px solid #e2e8f0;
                background: #f8fafc;
                display: block;
            }

            .sf-stock-tag {
                display: inline-block;
                padding: 0.25rem 0.6rem;
                border-radius: 999px;
                background: #eff6ff;
                color: #1d4ed8;
                font-size: 0.72rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .sf-line-total {
                font-weight: 700;
                color: #0f172a;
                white-space: nowrap;
                font-size: 0.88rem;
                display: block;
                padding: 0.25rem 0;
            }

            .sf-del-btn {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: 1px solid #fecaca;
                background: #fef2f2;
                color: #dc2626;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.15s;
            }

            .sf-del-btn:hover {
                background: #fee2e2;
                border-color: #f87171;
            }

            .sf-aside {
                display: grid;
                gap: 0.75rem;
            }

            .sf-aside-box {
                border: 1px solid #e9eff6;
                border-radius: 14px;
                padding: 0.85rem;
                background: linear-gradient(180deg, #f8fbff 0%, #fff 100%);
            }

            .sf-aside-title {
                font-size: 0.82rem;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 0.6rem;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }

            .sf-sum-row {
                display: flex;
                justify-content: space-between;
                padding: 0.4rem 0;
                font-size: 0.85rem;
                color: #475569;
                border-bottom: 1px dashed #e9eff6;
            }

            .sf-sum-row:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .sf-sum-row strong {
                color: #0f172a;
            }

            .sf-sum-grand strong {
                font-size: 1.05rem;
            }

            .sf-actions {
                display: grid;
                gap: 0.5rem;
            }

            .sf-actions .btn {
                width: 100%;
                font-size: 0.85rem;
                padding: 0.55rem 0.75rem;
                border-radius: 10px;
            }

            .sf-inv-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.3rem 0.7rem;
                border-radius: 999px;
                background: #f1f5f9;
                color: #475569;
                font-size: 0.78rem;
                font-weight: 600;
            }

            .sf-tbl tbody tr.sf-row-new {
                animation: sfRowGlow 0.6s ease;
            }

            @keyframes sfRowGlow {
                0% {
                    background: #dbeafe;
                }

                100% {
                    background: transparent;
                }
            }

            /* Column widths */
            .sf-tbl th:nth-child(1),
            .sf-tbl td:nth-child(1) {
                width: 26%;
            }

            .sf-tbl th.sf-col-img,
            .sf-tbl td.sf-col-img {
                width: 56px;
                text-align: center;
            }

            .sf-tbl th.sf-col-stock,
            .sf-tbl td.sf-col-stock {
                width: 80px;
                text-align: center;
            }

            .sf-tbl th.sf-col-price,
            .sf-tbl td.sf-col-price {
                width: 100px;
            }

            .sf-tbl th:nth-child(5),
            .sf-tbl td:nth-child(5) {
                width: 75px;
            }

            .sf-tbl th:nth-child(6),
            .sf-tbl td:nth-child(6) {
                width: 90px;
            }

            .sf-tbl th:nth-child(7),
            .sf-tbl td:nth-child(7) {
                width: 85px;
            }

            .sf-tbl th:nth-child(8),
            .sf-tbl td:nth-child(8) {
                width: 100px;
            }

            .sf-tbl th:nth-child(9),
            .sf-tbl td:nth-child(9) {
                width: 48px;
                text-align: center;
            }

            /* ── Large desktop (max 1199px) ── */
            @media (max-width: 1199.98px) {
                .sf-grid {
                    grid-template-columns: 1fr 240px;
                }
            }

            /* ── Tablet (max 991px) ── */
            @media (max-width: 991.98px) {
                .sf-grid {
                    grid-template-columns: 1fr;
                }

                .sf-aside {
                    grid-template-columns: 1fr 1fr;
                }

                .sf-cust-row {
                    flex-wrap: wrap;
                }

                .sf-cust-row .sf-f {
                    flex: 1 1 calc(50% - 0.5rem);
                    min-width: 160px;
                }

                .sf-cust-row .sf-f.addr {
                    flex: 1 1 100%;
                }
            }

            /* ── Mobile (max 767px) ── */
            @media (max-width: 767.98px) {
                .sf-body {
                    padding: 0.85rem;
                }

                .sf-topbar {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 0.7rem 0.85rem;
                }

                .sf-cust-row .sf-f {
                    flex: 1 1 100%;
                }

                .sf-aside {
                    grid-template-columns: 1fr;
                }

                .sf-topbar-left {
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .sf-topbar h2 {
                    font-size: 1rem;
                }

                .sf-note-edit {
                    font-size: 0.7rem;
                }

                .sf-inv-badge {
                    font-size: 0.72rem;
                }

                .sf-topbar>a.btn {
                    width: 100%;
                    text-align: center;
                    justify-content: center;
                }

                .sf-cust-row {
                    gap: 0.5rem;
                    padding: 0.65rem 0;
                }

                .sf-cust-row .form-control {
                    height: 44px;
                    font-size: 0.9rem;
                }

                .sf-grid {
                    gap: 0.75rem;
                    margin-top: 0.65rem;
                }

                .sf-items-wrap {
                    border-radius: 10px;
                }

                .sf-items-head {
                    padding: 0.5rem 0.7rem;
                }

                /* Table becomes card layout on mobile */
                .sf-tbl {
                    table-layout: auto;
                }

                .sf-tbl thead {
                    display: none;
                }

                .sf-tbl tbody tr[data-row] {
                    display: grid;
                    grid-template-columns: 50px 1fr 1fr;
                    gap: 0.4rem 0.6rem;
                    padding: 0.75rem 0.7rem;
                    border-bottom: 2px solid #e9eff6;
                    align-items: center;
                    background: #fff;
                    position: relative;
                }

                .sf-tbl tbody tr[data-row]:nth-child(even) {
                    background: #fafbfd;
                }

                .sf-tbl tbody tr[data-row]:last-child {
                    border-bottom: none;
                }

                .sf-tbl tbody td {
                    display: block;
                    border: none;
                    padding: 0;
                    background: transparent !important;
                }

                /* Product select - full row width */
                .sf-tbl tbody td:nth-child(1) {
                    grid-column: 1 / -1;
                    order: 1;
                }

                .sf-tbl tbody td:nth-child(1) .form-select {
                    height: 42px;
                    font-size: 0.85rem;
                }

                /* Image */
                .sf-tbl tbody td.sf-col-img {
                    display: block !important;
                    grid-column: 1;
                    grid-row: 2;
                    order: 2;
                }

                .sf-tbl tbody td.sf-col-img .sf-img-thumb {
                    width: 44px;
                    height: 44px;
                }

                /* Stock */
                .sf-tbl tbody td.sf-col-stock {
                    display: block !important;
                    grid-column: 2;
                    grid-row: 2;
                    order: 3;
                    text-align: left;
                }

                /* Price */
                .sf-tbl tbody td.sf-col-price {
                    display: block !important;
                    grid-column: 3;
                    grid-row: 2;
                    order: 4;
                }

                .sf-tbl tbody td.sf-col-price::before {
                    content: 'Price: ';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    display: block;
                    margin-bottom: 0.15rem;
                }

                /* Qty */
                .sf-tbl tbody td:nth-child(5) {
                    grid-column: 1;
                    grid-row: 3;
                    order: 5;
                }

                .sf-tbl tbody td:nth-child(5)::before {
                    content: 'Qty';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    display: block;
                    margin-bottom: 0.15rem;
                }

                /* Disc ₹ */
                .sf-tbl tbody td:nth-child(6) {
                    grid-column: 2;
                    grid-row: 3;
                    order: 6;
                }

                .sf-tbl tbody td:nth-child(6)::before {
                    content: 'Disc ₹';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    display: block;
                    margin-bottom: 0.15rem;
                }

                /* Disc % */
                .sf-tbl tbody td:nth-child(7) {
                    grid-column: 3;
                    grid-row: 3;
                    order: 7;
                }

                .sf-tbl tbody td:nth-child(7)::before {
                    content: 'Disc %';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    display: block;
                    margin-bottom: 0.15rem;
                }

                /* Total */
                .sf-tbl tbody td:nth-child(8) {
                    grid-column: 1 / 3;
                    grid-row: 4;
                    order: 8;
                }

                .sf-tbl tbody td:nth-child(8)::before {
                    content: 'Total: ';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                }

                .sf-tbl tbody td:nth-child(8) .sf-line-total {
                    display: inline;
                    font-size: 0.95rem;
                    color: #059669;
                }

                /* Delete btn */
                .sf-tbl tbody td:nth-child(9) {
                    grid-column: 3;
                    grid-row: 4;
                    order: 9;
                    text-align: right;
                }

                .sf-tbl .form-control,
                .sf-tbl .form-select {
                    height: 38px;
                    font-size: 0.82rem;
                }

                .sf-aside-box {
                    padding: 0.7rem;
                }

                .sf-aside-title {
                    font-size: 0.76rem;
                }

                .sf-sum-row {
                    font-size: 0.82rem;
                }

                .sf-sum-grand strong {
                    font-size: 0.95rem;
                }

                .sf-actions .btn {
                    padding: 0.65rem 0.75rem;
                    font-size: 0.88rem;
                }
            }

            /* ── Extra small (max 480px) ── */
            @media (max-width: 479.98px) {
                .sf-body {
                    padding: 0.65rem;
                }

                .sf-topbar {
                    padding: 0.6rem 0.65rem;
                    gap: 0.5rem;
                }

                .sf-topbar h2 {
                    font-size: 0.92rem;
                }

                .sf-cust-row .form-control {
                    height: 42px;
                    font-size: 0.85rem;
                }

                .sf-tbl tbody tr[data-row] {
                    grid-template-columns: 44px 1fr 1fr;
                    gap: 0.3rem 0.5rem;
                    padding: 0.65rem 0.55rem;
                }

                .sf-tbl .form-control,
                .sf-tbl .form-select {
                    height: 36px;
                    font-size: 0.78rem;
                }

                .sf-line-total {
                    font-size: 0.85rem;
                }

                .sf-del-btn {
                    width: 28px;
                    height: 28px;
                    font-size: 0.88rem;
                }

                .sf-aside-box {
                    padding: 0.6rem;
                }

                .sf-sum-row {
                    font-size: 0.78rem;
                    padding: 0.3rem 0;
                }

                .sf-aside-box div[style*="line-height"] {
                    font-size: 0.78rem !important;
                }
            }
            .sf-custom-toggle-btn {
    display: inline-block;
    margin-top: 4px;
    font-size: 11px;
    color: #2563eb;
    background: none;
    border: none;
    padding: 2px 4px;
    cursor: pointer;
    text-decoration: underline;
}
.sf-custom-toggle-btn.active,
.sf-custom-toggle-btn:hover {
    color: #1d4ed8;
}
.sf-custom-name-input {
    width: 100%;
    margin-top: 4px;
    padding: 6px 8px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
}
        </style>

        <div class="sf-shell mx-auto">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger mb-2 rounded-3" style="font-size:.88rem">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('sales/update/' . (int)$sale['id']) ?>" id="saleForm">
                <div class="sf-card">

                    <!-- ── TOP BAR ── -->
                    <div class="sf-topbar">
                        <div class="sf-topbar-left">
                            <h2><i class='bx bx-edit' style="vertical-align:middle;margin-right:0.3rem"></i> Edit Sale</h2>
                            <span class="sf-note-edit"><i class='bx bx-info-circle'></i> Editing will update stock &amp; invoice</span>
                            <span class="sf-inv-badge">
                                <i class='bx bx-receipt'></i> <?= htmlspecialchars($sale['invoice_number'] ?? '') ?>
                            </span>
                        </div>
                        <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary btn-sm px-3">← Back</a>
                    </div>

                    <div class="sf-body">

                        <!-- ── CUSTOMER ROW ── -->
                        <div class="sf-cust-row">
                            <div class="sf-f">
                                <label>Phone</label>
                                <input type="text" name="customer_phone" id="customer_phone"
                                    value="<?= htmlspecialchars($customer['phone'] ?? '') ?>"
                                    class="form-control" placeholder="Phone number">
                            </div>
                            <div class="sf-f">
                                <label>Name</label>
                                <input type="text" name="customer_name" id="customer_name"
                                    value="<?= htmlspecialchars($customer['name'] ?? '') ?>"
                                    class="form-control" placeholder="Customer name">
                            </div>
                            <div class="sf-f addr">
                                <label>Address <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                                <input type="text" name="customer_address" id="customer_address"
                                    value="<?= htmlspecialchars(str_replace(["\r", "\n"], ' ', $customer['address'] ?? '')) ?>"
                                    class="form-control" placeholder="Customer address">
                            </div>
                            <div class="sf-f">
                                <label>Date</label>
                                <input type="date" name="sale_date" id="sale_date"
                                    value="<?= date('Y-m-d', strtotime($sale['sale_date'])) ?>"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- ── TABLE + SIDEBAR ── -->
                        <div class="sf-grid">

                            <!-- LEFT: Items Table -->
                            <div class="sf-items-wrap">
                                <div class="sf-items-head">
                                    <h4><i class='bx bx-cart' style="vertical-align:middle;margin-right:0.3rem"></i> Items</h4>
                                    <button type="button" class="btn btn-primary btn-sm px-3" id="addSaleRow" style="font-size:.78rem;border-radius:8px">
                                        <i class='bx bx-plus'></i> Add Row
                                    </button>
                                </div>
                                <div style="overflow-x:auto">
                                    <table class="sf-tbl" id="saleItemsTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="sf-col-img" style="text-align:center">Img</th>
                                                <th class="sf-col-stock" style="text-align:center">Stock</th>
                                                <th class="sf-col-price">Price</th>
                                                <th>Qty</th>
                                                <th>Disc ₹</th>
                                                <th>Disc %</th>
                                                <th>Total</th>
                                                <th style="text-align:center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows injected by JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- RIGHT: Summary + Actions -->
                            <div class="sf-aside">
                                <div class="sf-aside-box">
                                    <div class="sf-aside-title">💰 Summary</div>
                                    <div class="sf-sum-row">
                                        <span>Subtotal</span>
                                        <strong id="saleSubtotal">Rs. 0.00</strong>
                                    </div>
                                    <div class="sf-sum-row">
                                        <span>Discount</span>
                                        <strong id="saleDiscountTotal" style="color:#dc2626">− Rs. 0.00</strong>
                                    </div>
                                    <div class="sf-sum-row sf-sum-grand" style="padding-top:0.6rem;margin-top:0.2rem;border-top:2px solid #e2e8f0;border-bottom:none">
                                        <span><strong>Grand Total</strong></span>
                                        <strong id="saleGrandTotal" style="color:#059669">Rs. 0.00</strong>
                                    </div>
                                </div>

                                <div class="sf-aside-box">
                                    <div class="sf-actions">
                                        <button type="submit" class="btn btn-warning text-dark">
                                            <i class='bx bx-save'></i> Update Sale
                                        </button>
                                        <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>

                                <!-- Sale Info -->
                                <div class="sf-aside-box">
                                    <div class="sf-aside-title">📋 Sale Info</div>
                                    <div style="font-size:0.82rem;color:#64748b;line-height:1.7">
                                        <div><strong>Invoice:</strong> <?= htmlspecialchars($sale['invoice_number'] ?? '') ?></div>
                                        <div><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($sale['sale_date'])) ?></div>
                                        <div><strong>Sale ID:</strong> #<?= (int)$sale['id'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
       <script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<script>
    const phoneInput = document.getElementById('customer_phone');
    const nameInput = document.getElementById('customer_name');
    const addressInput = document.getElementById('customer_address');

    if (!nameInput.value) nameInput.value = 'Walk-in Customer';
    document.getElementById('sale_date').value =
        document.getElementById('sale_date').value || new Date().toISOString().split('T')[0];

    let phoneTimer = null;

    phoneInput.addEventListener('input', function() {
        clearTimeout(phoneTimer);
        let phone = this.value.replace(/\D/g, '');
        if (phone.length < 10) return;

        phoneTimer = setTimeout(() => {
            fetch(BASE_URL + "sales/customer_lookup?phone=" + phone)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'found') {
                        nameInput.value = data.customer.name || '';
                        addressInput.value = data.customer.address || '';
                    }
                });
        }, 400);
    });

    document.addEventListener('DOMContentLoaded', function() {

        const BASE_URL = "<?= base_url() ?>";
        const products = <?= json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        const existingItems = <?= json_encode($saleItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        const tableBody = document.querySelector('#saleItemsTable tbody');
        const addRowButton = document.getElementById('addSaleRow');
        const form = document.getElementById('saleForm');
        const DEFAULT_IMAGE = "<?= base_url('assets/images/Product_Default.png') ?>";

        let rowCounter = 0;

        function formatCurrency(v) { return 'Rs. ' + Number(v || 0).toFixed(2); }

        function getProductById(id) {
            return products.find(p => String(p.id) === String(id)) || null;
        }

        function escapeHtml(s) {
            return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        function createProductOptions(selectedId) {
            let h = '<option value="">-- Select Product --</option>';
            products.forEach(p => {
                const sel = String(p.id) === String(selectedId) ? 'selected' : '';
                h += `<option value="${p.id}" ${sel}>${escapeHtml(p.name)} (Stock: ${p.quantity})</option>`;
            });
            return h;
        }

        function updateSummary() {
            let subtotal = 0, discountTotal = 0;

            tableBody.querySelectorAll('tr[data-row]').forEach(row => {
                const price = Number(row.querySelector('.sale-price-input').value || 0);
                const qty = Number(row.querySelector('.sale-qty-input').value || 0);
                const discAmt = Number(row.querySelector('.sale-discount-amount').value || 0);
                const discPer = Number(row.querySelector('.sale-item-discount').value || 0);

                let base = price * qty;
                let discount = 0;

                if (discAmt > 0) {
                    discount = Math.min(discAmt, base);
                } else {
                    discount = Math.min((base * discPer) / 100, base);
                }

                let total = base - discount;

                subtotal += base;
                discountTotal += discount;

                row.querySelector('.sf-line-total').textContent = formatCurrency(total);
            });

            document.getElementById('saleSubtotal').textContent = formatCurrency(subtotal);
            document.getElementById('saleDiscountTotal').textContent = '− ' + formatCurrency(discountTotal);
            document.getElementById('saleGrandTotal').textContent = formatCurrency(subtotal - discountTotal);
        }

        /* ✅ MODIFIED: skip reset for custom rows */
        function refreshRow(row) {
            if (row.dataset.isCustom === '1') {
                updateSummary();
                return;
            }

            const pid = row.querySelector('.sale-product-select').value;
            const product = getProductById(pid);

            const img = row.querySelector('.sf-img-thumb');
            const stock = row.querySelector('.sf-stock-tag');
            const priceInput = row.querySelector('.sale-price-input');
            const qty = row.querySelector('.sale-qty-input');

            if (!product) {
                img.src = DEFAULT_IMAGE;
                stock.textContent = '—';
                stock.style.background = '#f1f5f9';
                stock.style.color = '#94a3b8';
                priceInput.value = 0;
                qty.max = '';
                updateSummary();
                return;
            }

            img.src = product.image_url || DEFAULT_IMAGE;
            img.onerror = () => img.src = DEFAULT_IMAGE;

            stock.textContent = product.quantity + ' left';
            stock.style.background = product.quantity > 0 ? '#eff6ff' : '#fef2f2';
            stock.style.color = product.quantity > 0 ? '#1d4ed8' : '#dc2626';

            priceInput.value = product.sale_price || product.price || 0;
            qty.max = product.quantity;

            if (Number(qty.value) > product.quantity) qty.value = product.quantity;

            updateSummary();
        }

        function setupDiscountSync(row) {
            const priceInput = row.querySelector('.sale-price-input');
            const qtyInput = row.querySelector('.sale-qty-input');
            const discAmtInput = row.querySelector('.sale-discount-amount');
            const discPerInput = row.querySelector('.sale-item-discount');

            let isUpdating = false;

            function getBase() {
                const price = Number(priceInput.value || 0);
                const qty = Number(qtyInput.value || 0);
                return price * qty;
            }

            discAmtInput.addEventListener('input', function() {
                if (isUpdating) return;
                isUpdating = true;
                let base = getBase();
                let amt = Number(this.value || 0);
                if (amt > base) amt = base;
                let percent = base > 0 ? (amt / base) * 100 : 0;
                discPerInput.value = percent.toFixed(2);
                isUpdating = false;
                updateSummary();
            });

            discPerInput.addEventListener('input', function() {
                if (isUpdating) return;
                isUpdating = true;
                let base = getBase();
                let percent = Number(this.value || 0);
                if (percent > 100) percent = 100;
                let amt = (base * percent) / 100;
                discAmtInput.value = amt.toFixed(2);
                isUpdating = false;
                updateSummary();
            });

            function resync() {
                let base = getBase();
                let percent = Number(discPerInput.value || 0);
                let amt = (base * percent) / 100;
                discAmtInput.value = amt.toFixed(2);
                updateSummary();
            }

            priceInput.addEventListener('input', resync);
            qtyInput.addEventListener('input', resync);
        }

        /* ✅ NEW: custom item toggle logic for edit rows */
        function setupCustomToggle(row) {
            const select = row.querySelector('.sale-product-select');
            const hiddenPid = row.querySelector('.sf-hidden-pid');
            const isCustomHidden = row.querySelector('.sf-is-custom');
            const customNameInput = row.querySelector('.sf-custom-name-input');
            const toggleBtn = row.querySelector('.sf-custom-toggle-btn');

            toggleBtn.addEventListener('click', () => {
                const nowCustom = row.dataset.isCustom === '1';

                if (!nowCustom) {
                    row.dataset.isCustom = '1';
                    isCustomHidden.value = '1';
                    select.disabled = true;
                    hiddenPid.disabled = false;
                    hiddenPid.value = '0';

                    customNameInput.disabled = false;
                    customNameInput.style.display = '';
                    toggleBtn.textContent = '← Use Product List';

                    const img = row.querySelector('.sf-img-thumb');
                    if (img) img.src = DEFAULT_IMAGE;
                    const stock = row.querySelector('.sf-stock-tag');
                    if (stock) {
                        stock.textContent = '—';
                        stock.style.background = '#f1f5f9';
                        stock.style.color = '#94a3b8';
                    }
                    row.querySelector('.sale-qty-input').removeAttribute('max');

                    setTimeout(() => customNameInput.focus(), 30);
                } else {
                    row.dataset.isCustom = '0';
                    isCustomHidden.value = '0';
                    select.disabled = false;
                    hiddenPid.disabled = true;

                    customNameInput.value = '';
                    customNameInput.disabled = true;
                    customNameInput.style.display = 'none';
                    toggleBtn.textContent = '+ Custom Item';

                    select.value = '';
                    row.querySelector('.sale-price-input').value = 0;
                    row.querySelector('.sale-discount-amount').value = 0;
                    row.querySelector('.sale-item-discount').value = 0;

                    setTimeout(() => select.focus(), 30);
                }
                updateSummary();
            });

            customNameInput.addEventListener('input', updateSummary);
        }

        /* ✅ MODIFIED: row template includes custom item fields */
        function addRow(animate, preselect) {
            rowCounter++;

            const selectedId = preselect ? (preselect.product_id || '') : '';
            const prePrice = preselect ? (preselect.price || 0) : 0;
            const preQty = preselect ? (preselect.quantity || 1) : 1;
            const preDiscAmt = preselect ? (preselect.discount_amount || 0) : 0;
            const preDiscPer = preselect ? (preselect.discount_percent || 0) : 0;
            const preIsCustom = preselect ? !!preselect.is_custom : false;
            const preCustomName = preselect ? (preselect.custom_name || '') : '';

            const row = document.createElement('tr');
            row.setAttribute('data-row', rowCounter);
            row.dataset.isCustom = preIsCustom ? '1' : '0';
            if (animate) row.classList.add('sf-row-new');

            row.innerHTML = `
                <td>
                    <select name="product_id[]" class="form-select sale-product-select" tabindex="0" ${preIsCustom ? 'disabled' : ''}>
                        ${createProductOptions(selectedId)}
                    </select>
                    <input type="hidden" name="product_id[]" class="sf-hidden-pid" value="0" ${preIsCustom ? '' : 'disabled'}>
                    <input type="hidden" name="is_custom[]" class="sf-is-custom" value="${preIsCustom ? '1' : '0'}">
                    <input type="text" name="custom_name[]" class="sf-custom-name-input form-control mt-1"
                           placeholder="Custom item name" value="${escapeHtml(preCustomName)}"
                           style="display:${preIsCustom ? '' : 'none'};" ${preIsCustom ? '' : 'disabled'}>
                    <button type="button" class="sf-custom-toggle-btn">
                        ${preIsCustom ? '← Use Product List' : '+ Custom Item'}
                    </button>
                </td>
                <td class="sf-col-img">
                    <img src="${DEFAULT_IMAGE}" class="sf-img-thumb" alt="">
                </td>
                <td class="sf-col-stock">
                    <span class="sf-stock-tag" style="background:#f1f5f9;color:#94a3b8">—</span>
                </td>
                <td class="sf-col-price">
                    <input type="number" name="item_price[]" class="form-control sale-price-input" value="${prePrice}" step="0.01" min="0">
                </td>
                <td>
                    <input type="number" name="item_quantity[]" class="form-control sale-qty-input" value="${preQty}" min="1">
                </td>
                <td>
                    <input type="number" name="item_discount[]" class="form-control sale-discount-amount" value="${preDiscAmt}" step="0.01" min="0">
                </td>
                <td>
                    <input type="number" name="item_discount_percent[]" class="form-control sale-item-discount" value="${preDiscPer}" step="0.01" min="0" max="100">
                </td>
                <td>
                    <span class="sf-line-total">Rs. 0.00</span>
                </td>
                <td style="text-align:center">
                    <button type="button" class="sf-del-btn" title="Remove row">
                        <i class='bx bx-trash'></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
            setupDiscountSync(row);
            setupCustomToggle(row);

            const select = row.querySelector('.sale-product-select');
            select.addEventListener('change', () => {
                refreshRow(row);
                row.querySelector('.sale-qty-input').focus();
                row.querySelector('.sale-discount-amount').value = 0;
                row.querySelector('.sale-item-discount').value = 0;
            });

            row.querySelector('.sale-qty-input').addEventListener('input', () => refreshRow(row));
            row.querySelector('.sale-discount-amount').addEventListener('input', updateSummary);

            row.querySelector('.sf-del-btn').addEventListener('click', () => {
                if (tableBody.querySelectorAll('tr[data-row]').length <= 1) {
                    if (row.dataset.isCustom === '1') {
                        row.querySelector('.sf-custom-toggle-btn')?.click();
                    }
                    select.value = '';
                    row.querySelector('.sale-qty-input').value = 1;
                    row.querySelector('.sale-item-discount').value = 0;
                    refreshRow(row);
                    return;
                }
                row.remove();
                updateSummary();
            });

            if (animate) setTimeout(() => select.focus(), 50);

            row.querySelector('.sale-item-discount').addEventListener('input', function() {
                if (this.value > 100) this.value = 100;
                updateSummary();
            });

            refreshRow(row);
        }

        form.addEventListener('submit', function(e) {
            let valid = false;

            tableBody.querySelectorAll('tr[data-row]').forEach(row => {
                const q = row.querySelector('.sale-qty-input').value;
                if (row.dataset.isCustom === '1') {
                    const name = row.querySelector('.sf-custom-name-input').value.trim();
                    if (name && q > 0) valid = true;
                } else {
                    const p = row.querySelector('.sale-product-select').value;
                    if (p && q > 0) valid = true;
                }
            });

            if (!valid) {
                alert('❌ Please select at least one product with quantity');
                e.preventDefault();
            }
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                form.requestSubmit();
            }
        });

        addRowButton.addEventListener('click', () => addRow(true, null));

        if (existingItems.length > 0) {
            existingItems.forEach(item => {
                const lineBase = parseFloat(item.unit_price) * parseInt(item.quantity);
                const discAmt = parseFloat(item.discount_amount || 0);

                addRow(false, {
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: item.unit_price,
                    discount_amount: item.discount_amount,
                    discount_percent: item.discount_percent ?? (lineBase > 0 ? ((discAmt / lineBase) * 100).toFixed(2) : 0),
                    is_custom: !!(+item.is_custom),
                    custom_name: item.product_name || ''
                });
            });
        } else {
            addRow(false, null);
        }
    });
</script>
    </div>
</div>