<style>
    /* ═══════════════════════════════════════════════
   DASHBOARD — clean, modern, card-based
═══════════════════════════════════════════════ */
    :root {
        --db-radius: 16px;
        --db-gap: 1.5rem;
        --db-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        --db-border: 1px solid rgba(0, 0, 0, .06);

        --clr-sales: #2563eb;
        --clr-profit: #16a34a;
        --clr-purchase: #d97706;
        --clr-product: #7c3aed;
        --clr-customer: #0891b2;
        --clr-danger: #dc2626;

        --clr-sales-bg: #eff6ff;
        --clr-profit-bg: #f0fdf4;
        --clr-purchase-bg: #fffbeb;
        --clr-product-bg: #f5f3ff;
        --clr-customer-bg: #ecfeff;
        --clr-danger-bg: #fef2f2;
    }

    /* ── Shell ── */
    .db-shell {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.75rem 1.25rem 3rem;
        display: flex;
        flex-direction: column;
        gap: var(--db-gap);
    }

    /* ── Hero ── */
    .db-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 60%, #2563eb 100%);
        border-radius: var(--db-radius);
        padding: 2rem 2.25rem;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 8px 32px rgba(37, 99, 235, .25);
    }

    .db-hero-left h1 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 .25rem;
        letter-spacing: -.3px;
    }

    .db-hero-left p {
        margin: 0;
        opacity: .75;
        font-size: .9rem;
    }

    .db-hero-badge {
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 50px;
        padding: .35rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .4rem;
        backdrop-filter: blur(6px);
    }

    /* ══════════════════════════════════════════
       UNIFIED GLOBAL FILTER BAR
    ══════════════════════════════════════════ */
    .db-filter-bar {
        background: #fff;
        border-radius: var(--db-radius);
        border: var(--db-border);
        box-shadow: var(--db-shadow);
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: .75rem;
    }

    .db-filter-top-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .db-filter-bar .filter-label {
        font-size: .82rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .db-filter-pills {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
    }

    .db-filter-pill {
        display: inline-block;
        padding: .4rem 1rem;
        border-radius: 50px;
        font-size: .82rem;
        font-weight: 600;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        text-decoration: none;
        transition: all .18s ease;
        cursor: pointer;
        white-space: nowrap;
        line-height: 1.3;
    }

    .db-filter-pill:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
        text-decoration: none;
    }

    .db-filter-pill.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        box-shadow: 0 3px 10px rgba(37, 99, 235, .3);
    }

    .db-filter-sep {
        width: 1px;
        height: 28px;
        background: #e2e8f0;
        flex-shrink: 0;
    }

    .db-picker-group {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-shrink: 0;
    }

    .db-picker-group label {
        font-size: .78rem;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
        margin-bottom: 0;
    }

    .db-picker-group input {
        height: 34px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 .6rem;
        font-size: .82rem;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color .15s;
        outline: none;
    }

    .db-picker-group input:focus {
        border-color: #2563eb;
        background: #fff;
    }

    .db-picker-group input[type=date] {
        width: 150px;
    }

    .db-picker-group input[type=month] {
        width: 150px;
    }

    .db-picker-group input[type=number] {
        width: 90px;
    }

    /* Reset button */
    .db-filter-reset {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        height: 34px;
        padding: 0 .9rem;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s ease;
        white-space: nowrap;
        flex-shrink: 0;
        margin-left: auto;
    }

    .db-filter-reset:hover {
        border-color: #dc2626;
        background: #fef2f2;
        color: #dc2626;
    }

    .db-filter-reset i {
        font-size: 1rem;
    }

    .db-filter-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .6rem;
        flex-wrap: wrap;
    }

    .db-filter-info {
        font-size: .8rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    .db-filter-info strong {
        color: #1e293b;
    }

    .db-filter-loading {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .78rem;
        font-weight: 600;
        color: #2563eb;
    }

    .db-filter-loading i {
        font-size: 1rem;
    }

    /* ── Stat Cards ── */
    .db-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    @media (max-width: 992px) {
        .db-stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .db-stats-row {
            grid-template-columns: 1fr;
        }
    }

    .db-stat-card {
        background: #fff;
        border-radius: var(--db-radius);
        border: var(--db-border);
        box-shadow: var(--db-shadow);
        padding: 1.4rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        transition: transform .18s ease, box-shadow .18s ease;
        position: relative;
        overflow: hidden;
    }

    .db-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: var(--db-radius) var(--db-radius) 0 0;
    }

    .db-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
    }

    .db-stat-card.c-sales::before {
        background: var(--clr-sales);
    }

    .db-stat-card.c-profit::before {
        background: var(--clr-profit);
    }

    .db-stat-card.c-purchase::before {
        background: var(--clr-purchase);
    }

    .db-stat-card.c-product::before {
        background: var(--clr-product);
    }

    .db-stat-card.c-customer::before {
        background: var(--clr-customer);
    }

    .db-stat-card.c-stock::before {
        background: var(--clr-danger);
    }

    .db-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .db-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .db-stat-card.c-sales .db-stat-icon {
        background: var(--clr-sales-bg);
        color: var(--clr-sales);
    }

    .db-stat-card.c-profit .db-stat-icon {
        background: var(--clr-profit-bg);
        color: var(--clr-profit);
    }

    .db-stat-card.c-purchase .db-stat-icon {
        background: var(--clr-purchase-bg);
        color: var(--clr-purchase);
    }

    .db-stat-card.c-product .db-stat-icon {
        background: var(--clr-product-bg);
        color: var(--clr-product);
    }

    .db-stat-card.c-customer .db-stat-icon {
        background: var(--clr-customer-bg);
        color: var(--clr-customer);
    }

    .db-stat-card.c-stock .db-stat-icon {
        background: var(--clr-danger-bg);
        color: var(--clr-danger);
    }

    .db-stat-badge {
        font-size: .72rem;
        font-weight: 600;
        padding: .22rem .7rem;
        border-radius: 50px;
        white-space: nowrap;
        transition: all .15s ease;
    }

    .c-sales .db-stat-badge {
        background: var(--clr-sales-bg);
        color: var(--clr-sales);
    }

    .c-profit .db-stat-badge {
        background: var(--clr-profit-bg);
        color: var(--clr-profit);
    }

    .c-purchase .db-stat-badge {
        background: var(--clr-purchase-bg);
        color: var(--clr-purchase);
    }

    .c-product .db-stat-badge {
        background: var(--clr-product-bg);
        color: var(--clr-product);
    }

    .c-customer .db-stat-badge {
        background: var(--clr-customer-bg);
        color: var(--clr-customer);
    }

    .c-stock .db-stat-badge {
        background: var(--clr-danger-bg);
        color: var(--clr-danger);
    }

    .db-stat-value {
        font-size: 1.95rem;
        font-weight: 800;
        letter-spacing: -.5px;
        line-height: 1;
        color: #0f172a;
        transition: opacity .15s ease;
    }

    .db-stat-value.rs {
        font-size: 1.45rem;
    }

    .db-stat-label {
        font-size: .8rem;
        color: #64748b;
        font-weight: 500;
        margin: 0;
    }

    /* ══════════════════════════════════════════
       CHART CARD
    ══════════════════════════════════════════ */
    .db-chart-card {
        background: #fff;
        border-radius: var(--db-radius);
        border: var(--db-border);
        box-shadow: var(--db-shadow);
        padding: 1.75rem 1.75rem 1.5rem;
    }

    .db-chart-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .db-chart-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .db-chart-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .2rem;
    }

    .db-chart-sub {
        font-size: .82rem;
        color: #94a3b8;
        margin: 0;
    }

    .db-chart-controls-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .db-chart-totals {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .db-chart-total-pill {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: #f8fafc;
        border: var(--db-border);
        border-radius: 10px;
        padding: .45rem 1rem;
        font-size: .82rem;
    }

    .db-chart-total-pill .dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .db-chart-total-pill .lbl {
        color: #64748b;
    }

    .db-chart-total-pill .val {
        font-weight: 700;
        color: #0f172a;
    }

    .chart-wrap {
        position: relative;
        width: 100%;
        height: 300px;
    }

    /* ── Bottom Row ── */
    .db-bottom-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--db-gap);
    }

    @media (max-width: 900px) {
        .db-bottom-row {
            grid-template-columns: 1fr;
        }
    }

    .db-list-card {
        background: #fff;
        border-radius: var(--db-radius);
        border: var(--db-border);
        box-shadow: var(--db-shadow);
        padding: 1.5rem;
    }

    .db-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
    }

    .db-list-header h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .db-list-header a {
        font-size: .8rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .db-list-sub {
        font-size: .78rem;
        color: #94a3b8;
        margin: .15rem 0 0;
    }

    .db-list-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .65rem 0;
        border-bottom: 1px solid #f1f5f9;
        gap: .5rem;
    }

    .db-list-row:last-child {
        border-bottom: none;
    }

    .db-list-row-name {
        font-size: .88rem;
        font-weight: 600;
        color: #1e293b;
    }

    .db-list-row-sub {
        font-size: .76rem;
        color: #94a3b8;
        margin-top: .1rem;
    }

    .db-stock-pill {
        font-size: .75rem;
        font-weight: 700;
        padding: .2rem .7rem;
        border-radius: 50px;
        white-space: nowrap;
    }

    .db-stock-pill.low {
        background: #fef2f2;
        color: #dc2626;
    }

    .db-stock-pill.ok {
        background: #f0fdf4;
        color: #16a34a;
    }

    .db-sale-amount {
        font-size: .88rem;
        font-weight: 700;
        color: #16a34a;
    }

    .db-empty {
        color: #94a3b8;
        font-size: .85rem;
        padding: .5rem 0;
    }

    /* ══════════════════════════════════════════
       RESPONSIVE FIXES
    ══════════════════════════════════════════ */
    @media (max-width: 768px) {
        .db-filter-top-row {
            flex-direction: column;
            align-items: stretch;
        }

        .db-filter-pills {
            width: 100%;
        }

        .db-filter-sep {
            display: none;
        }

        .db-picker-group {
            width: 100%;
        }

        .db-picker-group label {
            flex-shrink: 0;
            width: 85px;
        }

        .db-picker-group input[type=date],
        .db-picker-group input[type=month],
        .db-picker-group input[type=number] {
            flex: 1;
            width: auto;
        }

        .db-filter-reset {
            width: 100%;
            justify-content: center;
            margin-left: 0;
        }

        .db-filter-bottom-row {
            flex-direction: column;
            align-items: flex-start;
            gap: .3rem;
        }

        .db-chart-controls-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .db-chart-totals {
            width: 100%;
        }

        .db-chart-total-pill {
            flex: 1;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .db-hero {
            padding: 1.4rem 1.25rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .db-hero-left h1 {
            font-size: 1.3rem;
        }

        .db-filter-bar {
            padding: .85rem 1rem;
        }

        .db-stat-value {
            font-size: 1.6rem;
        }

        .db-stat-value.rs {
            font-size: 1.25rem;
        }

        .chart-wrap {
            height: 240px;
        }
    }

    /* Privacy */
    .privacy-mode-active #dashboardMainContent {
        display: none !important;
    }

    .privacy-center-message {
        position: fixed;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, .55);
        backdrop-filter: blur(8px);
        z-index: 1000;
        gap: 1.5rem;
    }

    .privacy-eye {
        font-size: 4rem;
        color: #fff;
        opacity: .7;
    }

    .privacy-box {
        background: #fff;
        border-radius: 16px;
        padding: 2rem 2.5rem;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <div id="dashboardMainContent">
            <div class="db-shell">

                <!-- ── Hero ── -->
                <div class="db-hero">
                    <div class="db-hero-left">
                        <h1 class="text-light">
                            <i class='bx bx-store-alt me-2' style="vertical-align:middle;"></i>
                            <?= htmlspecialchars($businessName ?? 'Bills Book') ?>
                        </h1>
                        <p>Dashboard Overview &mdash; real-time business snapshot</p>
                    </div>
                    <div class="db-hero-badge">
                        <i class='bx bx-user-circle'></i>
                        <?= htmlspecialchars($userName ?? 'Admin') ?>
                    </div>
                </div>

                <!-- ── Global Filter Bar (FIXED) ── -->
           <!-- ── UNIFIED GLOBAL FILTER (controls stat cards + chart together) ── -->
<div class="db-filter-bar" id="masterFilterBar">
    <div class="db-filter-top-row">
        <span class="filter-label"><i class='bx bx-filter-alt me-1'></i>Filter</span>

        <div class="db-filter-pills">
            <button type="button" class="db-filter-pill active" data-filter="day">Today</button>
            <button type="button" class="db-filter-pill" data-filter="week">This Week</button>
            <button type="button" class="db-filter-pill" data-filter="month">This Month</button>
            <button type="button" class="db-filter-pill" data-filter="year">This Year</button>
        </div>

        <div class="db-filter-sep"></div>

        <div class="db-picker-group">
            <label for="masterDatePicker">Specific Date</label>
            <input type="date" id="masterDatePicker">
        </div>

        <div class="db-picker-group">
            <label for="masterMonthPicker">Month</label>
            <input type="month" id="masterMonthPicker">
        </div>

        <div class="db-picker-group">
            <label for="masterYearPicker">Year</label>
            <input type="number" id="masterYearPicker" placeholder="e.g. 2025" min="2000" max="2100">
        </div>

        <button type="button" class="db-filter-reset" id="resetFilterBtn" title="Reset to Today">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>

    <div class="db-filter-bottom-row">
        <span class="db-filter-info">Showing: <strong id="masterFilterLabel"><?= htmlspecialchars($filterLabel) ?></strong></span>
        <span class="db-filter-loading" id="filterLoadingTag" style="display:none;">
            <i class='bx bx-loader-alt bx-spin'></i> Updating...
        </span>
    </div>
</div>

<!-- ================= ROW 1 ================= -->
<div class="db-stats-row">
    <div class="db-stat-card c-product">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-box'></i></div>
            <a href="<?= base_url('product') ?>" style="font-size:.78rem;color:var(--clr-product);font-weight:600;text-decoration:none;">Manage</a>
        </div>
        <div class="db-stat-value"><?= (int) ($productCount ?? 0) ?></div>
        <p class="db-stat-label">Active Products</p>
    </div>

    <div class="db-stat-card c-customer">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-user'></i></div>
            <span class="db-stat-badge db-period-badge"><?= htmlspecialchars($filterLabel) ?></span>
        </div>
        <div class="db-stat-value" id="statCustomerCount"><?= (int) ($customerCount ?? 0) ?></div>
        <p class="db-stat-label">Customers</p>
    </div>

    <div class="db-stat-card c-stock">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-error-circle'></i></div>
            <a href="<?= base_url('product') ?>" style="font-size:.78rem;color:var(--clr-danger);font-weight:600;text-decoration:none;">Check</a>
        </div>
        <div class="db-stat-value"><?= (int) ($lowStockCount ?? 0) ?></div>
        <p class="db-stat-label">Low Stock Items</p>
    </div>
</div>

<!-- ================= ROW 2 ================= -->
<div class="db-stats-row" style="margin-top:0;">
    <div class="db-stat-card c-sales">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-trending-up'></i></div>
            <span class="db-stat-badge db-period-badge"><?= htmlspecialchars($filterLabel) ?></span>
        </div>
        <div class="db-stat-value rs" id="statTotalSales">Rs. <?= number_format($totalSales ?? 0, 2) ?></div>
        <p class="db-stat-label">Total Sales</p>
    </div>

    <div class="db-stat-card c-profit">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-dollar-circle'></i></div>
            <span class="db-stat-badge db-period-badge"><?= htmlspecialchars($filterLabel) ?></span>
        </div>
        <div class="db-stat-value rs" id="statTotalProfit">Rs. <?= number_format($totalProfit ?? 0, 2) ?></div>
        <p class="db-stat-label">Total Profit</p>
    </div>

    <div class="db-stat-card c-purchase">
        <div class="db-stat-top">
            <div class="db-stat-icon"><i class='bx bx-cart-alt'></i></div>
            <span class="db-stat-badge db-period-badge"><?= htmlspecialchars($filterLabel) ?></span>
        </div>
        <div class="db-stat-value rs" id="statTotalPurchase">Rs. <?= number_format($totalPurchase ?? 0, 2) ?></div>
        <p class="db-stat-label">Total Purchase</p>
    </div>
</div>

<!-- ── Sales & Profit Chart ── -->
<div class="db-chart-card">
    <div class="db-chart-header">
        <div class="db-chart-header-top">
            <div>
                <p class="db-chart-title">Sales &amp; Profit Analytics</p>
                <p class="db-chart-sub">Revenue vs profit trend &mdash; <span id="chartFilterLabel"><?= htmlspecialchars($filterLabel) ?></span></p>
            </div>
        </div>

        <div class="db-chart-controls-row">
            <div class="db-chart-totals">
                <div class="db-chart-total-pill">
                    <span class="dot" style="background:var(--clr-sales);"></span>
                    <span class="lbl">Sales</span>
                    <span class="val" id="chartTotalSales">Rs. 0.00</span>
                </div>
                <div class="db-chart-total-pill">
                    <span class="dot" style="background:var(--clr-profit);"></span>
                    <span class="lbl">Profit</span>
                    <span class="val" id="chartTotalProfit">Rs. 0.00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="chart-wrap">
        <canvas id="salesChart"></canvas>
    </div>
</div>
                <!-- ── Bottom: Low Stock + Recent Sales ── -->
                <div class="db-bottom-row">

                    <!-- Low Stock Products -->
                    <div class="db-list-card">
                        <div class="db-list-header">
                            <div>
                                <h5>Low Stock Products</h5>
                                <p class="db-list-sub">Quantity 5 or below</p>
                            </div>
                            <a href="<?= base_url('product') ?>">Manage &rarr;</a>
                        </div>
                        <?php if (!empty($lowStockProducts)): ?>
                            <?php foreach ($lowStockProducts as $product): ?>
                                <div class="db-list-row">
                                    <div>
                                        <div class="db-list-row-name"><?= htmlspecialchars($product['name']) ?></div>
                                        <div class="db-list-row-sub">
                                            Rs. <?= number_format((float)($product['sale_price'] ?? 0), 2) ?>
                                            <span style="color:#94a3b8;font-size:12px;">
                                                (Cost: <?= number_format((float)($product['purchase_price'] ?? 0), 2) ?>)
                                            </span>
                                        </div>
                                    </div>
                                    <span class="db-stock-pill <?= (int)$product['quantity'] <= 2 ? 'low' : 'ok' ?>">
                                        <?= (int)$product['quantity'] ?> left
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="db-empty">No low stock products right now. 🎉</div>
                        <?php endif; ?>
                    </div>

                    <!-- Recent Sales -->
                    <div class="db-list-card">
                        <div class="db-list-header">
                            <div>
                                <h5>Recent Sales</h5>
                                <p class="db-list-sub">Latest 5 invoices</p>
                            </div>
                            <a href="<?= base_url('sales') ?>">View all &rarr;</a>
                        </div>
                        <?php if (!empty($recentSales)): ?>
                            <?php foreach ($recentSales as $sale): ?>
                                <div class="db-list-row">
                                    <div>
                                        <div class="db-list-row-name"><?= htmlspecialchars($sale['invoice_number']) ?></div>
                                        <div class="db-list-row-sub">
                                            <?= htmlspecialchars($sale['customer_name'] ?: 'Walk-in Customer') ?>
                                            &bull; <?= htmlspecialchars(date('d M Y', strtotime($sale['sale_date']))) ?>
                                        </div>
                                    </div>
                                    <div class="db-sale-amount">Rs. <?= number_format((float)$sale['grand_total'], 2) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="db-empty">No sales added yet.</div>
                        <?php endif; ?>
                    </div>

                </div>

            </div><!-- /.db-shell -->
        </div><!-- /#dashboardMainContent -->

        <!-- Privacy Overlay -->
        <div id="privacyBanner" class="privacy-banner" style="display:none;">
            <div class="container-fluid">
                <div class="alert alert-info d-flex align-items-center justify-content-between mb-0 rounded-0 border-0"
                    style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;">
                    <div class="d-flex align-items-center">
                        <i class='bx bxs-lock-alt fs-4 me-2'></i>
                        <span><strong>Privacy Mode enabled.</strong> Toggle the Privacy button to view your Dashboard.</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" id="closePrivacyBanner"></button>
                </div>
            </div>
        </div>

        <div id="privacyMessage" class="privacy-center-message" style="display:none;">
            <div class="privacy-eye"><i class='bx bx-show'></i></div>
            <div class="privacy-box">Privacy Mode enabled.<br>Toggle the Privacy button to view your Dashboard.</div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════════════════════════════════
       MASTER FILTER — single source for stat cards + chart
    ══════════════════════════════════════════════════ */
    var pillBtns      = document.querySelectorAll('.db-filter-pill');
    var datePick      = document.getElementById('masterDatePicker');
    var monthPick     = document.getElementById('masterMonthPicker');
    var yearPick      = document.getElementById('masterYearPicker');
    var resetBtn      = document.getElementById('resetFilterBtn');
    var loadingTag    = document.getElementById('filterLoadingTag');
    var filterLabelEl = document.getElementById('masterFilterLabel');
    var chartLabelEl  = document.getElementById('chartFilterLabel');
    var canvas        = document.getElementById('salesChart');

    var state = { filter: 'day', month: '', year: '', date: '' };
    var yearDebounce = null;
    var chart = null;

    function fmt(n) { return 'Rs. ' + parseFloat(n || 0).toFixed(2); }

    function setActivePill(val) {
        pillBtns.forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-filter') === val);
        });
    }

    function clearPickers() {
        datePick.value = '';
        monthPick.value = '';
        yearPick.value = '';
    }

    // ── Pill clicks ──
    pillBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            state.filter = btn.getAttribute('data-filter');
            state.month = ''; state.year = ''; state.date = '';
            clearPickers();
            setActivePill(state.filter);
            loadDashboard();
        });
    });

    // ── Specific date ──
    datePick.addEventListener('change', function () {
        if (!this.value) return;
        state.date = this.value;
        state.month = ''; state.year = '';
        monthPick.value = ''; yearPick.value = '';
        setActivePill(null);
        loadDashboard();
    });

    // ── Month ──
    monthPick.addEventListener('change', function () {
        if (!this.value) return;
        state.month = this.value;
        state.date = ''; state.year = '';
        datePick.value = ''; yearPick.value = '';
        setActivePill(null);
        loadDashboard();
    });

    // ── Year (debounced) ──
    yearPick.addEventListener('input', function () {
        clearTimeout(yearDebounce);
        var v = parseInt(this.value);
        if (v >= 2000 && v <= 2100) {
            yearDebounce = setTimeout(function () {
                state.year = v;
                state.date = ''; state.month = '';
                datePick.value = ''; monthPick.value = '';
                setActivePill(null);
                loadDashboard();
            }, 600);
        }
    });

    // ── Reset ──
    resetBtn.addEventListener('click', function () {
        state = { filter: 'day', month: '', year: '', date: '' };
        clearPickers();
        setActivePill('day');
        loadDashboard();
    });

    function loadDashboard() {
        loadingTag.style.display = 'inline-flex';
        fetch('<?= base_url("dashboard/getDashboardData") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'filter=' + encodeURIComponent(state.filter) +
                  '&month_picker=' + encodeURIComponent(state.month) +
                  '&year_picker=' + encodeURIComponent(state.year) +
                  '&date_picker=' + encodeURIComponent(state.date)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            filterLabelEl.textContent = data.filterLabel;
            if (chartLabelEl) chartLabelEl.textContent = data.filterLabel;

            document.querySelectorAll('.db-period-badge').forEach(function (b) {
                b.textContent = data.filterLabel;
            });

            document.getElementById('statCustomerCount').textContent = data.customerCount;
            document.getElementById('statTotalSales').textContent    = fmt(data.totalSales);
            document.getElementById('statTotalProfit').textContent   = fmt(data.totalProfit);
            document.getElementById('statTotalPurchase').textContent = fmt(data.totalPurchase);

            drawChart(data.chart);
            loadingTag.style.display = 'none';
        })
        .catch(function () {
            loadingTag.style.display = 'none';
        });
    }

    /* ══════════════════════════════════════════════════
       CHART
    ══════════════════════════════════════════════════ */
    function drawChart(data) {
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        if (chart) { chart.destroy(); chart = null; }

        var sales  = (data.values || []).map(function (v) { return parseFloat(v) || 0; });
        var profit = (data.profit || []).map(function (v) { return parseFloat(v) || 0; });
        var labels = data.labels || [];

        document.getElementById('chartTotalSales').textContent  = fmt(data.total_sales);
        document.getElementById('chartTotalProfit').textContent = fmt(data.total_profit);

        var hasData = sales.some(function (v) { return v > 0; }) || profit.some(function (v) { return v > 0; });
        if (!hasData) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = 'bold 15px sans-serif';
            ctx.fillStyle = '#94a3b8';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('No data available for this period', canvas.width / 2, canvas.height / 2);
            return;
        }

        // Note: a single-day filter naturally produces 1 data point — Chart.js
        // will show a single dot, not a connecting line. This is expected, not a bug.

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sales', data: sales,
                        borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,.08)',
                        borderWidth: 2.5, fill: true, tension: 0.4,
                        pointRadius: 5, pointHoverRadius: 8,
                        pointBackgroundColor: '#fff', pointBorderColor: '#2563eb', pointBorderWidth: 2,
                        order: 2
                    },
                    {
                        label: 'Profit', data: profit,
                        borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,.08)',
                        borderWidth: 2.5, fill: true, tension: 0.4,
                        pointRadius: 5, pointHoverRadius: 8,
                        pointBackgroundColor: '#fff', pointBorderColor: '#16a34a', pointBorderWidth: 2,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        display: true, position: 'top',
                        labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12, weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b', titleColor: '#94a3b8', bodyColor: '#f8fafc',
                        padding: 12, cornerRadius: 10,
                        callbacks: {
                            label: function (ctx) { return ' ' + ctx.dataset.label + ': Rs. ' + ctx.parsed.y.toFixed(2); }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11 } } },
                    y: {
                        beginAtZero: true, grid: { color: 'rgba(0,0,0,.05)' },
                        ticks: { color: '#64748b', font: { size: 11 }, callback: function (v) { return 'Rs. ' + v; } }
                    }
                }
            }
        });
    }

    window.addEventListener('resize', function () { if (chart) chart.resize(); });

    // ── Initial load (confirms SSR values via AJAX, populates chart) ──
    loadDashboard();

    /* ══════════════════════════════════════════════════
       PRIVACY MODE — unchanged from before
    ══════════════════════════════════════════════════ */
    var privacySwitch  = document.getElementById('privacySwitch');
    var privacyBanner  = document.getElementById('privacyBanner');
    var closeBannerBtn = document.getElementById('closePrivacyBanner');
    var quickToggleBtn = document.getElementById('quickTogglePrivacy');

    if (privacySwitch) {
        var serverMode = <?= isset($privacyModeEnabled) && $privacyModeEnabled ? 'true' : 'false' ?>;
        var privacyMode = localStorage.getItem('privacyMode');
        privacyMode = (privacyMode === null) ? serverMode : (privacyMode === 'true');

        privacySwitch.checked = privacyMode;
        updatePrivacyUI(privacyMode);

        privacySwitch.addEventListener('click', function (e) {
            e.stopPropagation();
            privacyMode = this.checked;
            localStorage.setItem('privacyMode', privacyMode);
            updatePrivacyUI(privacyMode);
            savePrivacyPreference(privacyMode);
        });
    }

    if (closeBannerBtn) closeBannerBtn.addEventListener('click', function () {
        privacyBanner.style.display = 'none';
    });

    if (quickToggleBtn) {
        quickToggleBtn.addEventListener('click', function () {
            if (privacySwitch) privacySwitch.checked = false;
            localStorage.setItem('privacyMode', 'false');
            updatePrivacyUI(false);
            savePrivacyPreference(false);
        });
    }

    var privacyToggleItem = document.getElementById('privacyModeToggle');
    if (privacyToggleItem) {
        privacyToggleItem.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    function updatePrivacyUI(enabled) {
        var msg = document.getElementById('privacyMessage');
        if (enabled) {
            document.body.classList.add('privacy-mode-active');
            if (msg) msg.style.display = 'flex';
        } else {
            document.body.classList.remove('privacy-mode-active');
            if (msg) msg.style.display = 'none';
        }
    }

    function savePrivacyPreference(enabled) {
        fetch('<?= base_url("dashboard/save_privacy_preference") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ privacy_mode: enabled })
        }).then(function (r) { return r.json(); }).catch(function () {});
    }
});
</script>