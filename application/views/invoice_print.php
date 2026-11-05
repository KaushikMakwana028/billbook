<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($invoiceNumber) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #fff;
            padding: 20px;
        }

        .inv-wrapper { max-width: 780px; margin: 0 auto; }

        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 15px;
            border-bottom: 2px solid #0f172a;
            margin-bottom: 20px;
        }

        .inv-brand h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .inv-brand p {
            color: #64748b;
            font-size: 12px;
            margin-top: 2px;
        }

        .inv-meta { text-align: right; }

        .inv-meta h3 {
            font-size: 18px;
            color: #2563eb;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .inv-meta p {
            font-size: 12px;
            color: #475569;
            margin-top: 3px;
        }

        .inv-details {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .inv-details-box {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .inv-details-box h5 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .inv-details-box p {
            font-size: 13px;
            color: #334155;
            line-height: 1.5;
            margin: 0;
        }

        .inv-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .inv-table thead th {
            background: #0f172a;
            color: #fff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 10px 12px;
            text-align: left;
            font-weight: 700;
        }

        .inv-table thead th:last-child,
        .inv-table thead th:nth-child(n+3) {
            text-align: right;
        }

        .inv-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .inv-table tbody td:last-child,
        .inv-table tbody td:nth-child(n+3) {
            text-align: right;
        }

        .inv-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .inv-totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .inv-totals-box {
            width: 280px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .inv-totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 14px;
            font-size: 13px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }

        .inv-totals-row:last-child {
            border-bottom: none;
            background: #0f172a;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            padding: 10px 14px;
        }

        .inv-footer {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 11px;
        }

        .inv-notes {
            background: #fffbeb;
            border: 1px solid #fef08a;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 20px;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="inv-wrapper">

    <!-- Header -->
    <div class="inv-header">
        <div class="inv-brand">
            <h1><?= htmlspecialchars($businessName) ?></h1>
            <p>Tax Invoice</p>
        </div>
        <div class="inv-meta">
            <h3>Invoice</h3>
            <p><strong>#<?= htmlspecialchars($invoiceNumber) ?></strong></p>
            <p><?= date('d M Y', strtotime($totals['sale_date'])) ?></p>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="inv-details">
        <div class="inv-details-box">
            <h5>Billed To</h5>
            <p>
                <strong><?= htmlspecialchars($customer['name'] ?? 'Customer') ?></strong><br>
                <?= htmlspecialchars($customer['phone'] ?? '') ?>
                <?php if (!empty($customer['address'])): ?>
                    <br><?= htmlspecialchars($customer['address']) ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="inv-details-box">
            <h5>Invoice Info</h5>
            <p>
                Invoice No: <strong><?= htmlspecialchars($invoiceNumber) ?></strong><br>
                Date: <?= date('d/m/Y', strtotime($totals['sale_date'])) ?><br>
                Sale ID: #<?= $saleId ?>
            </p>
        </div>
    </div>

    <!-- Items Table -->
    <table class="inv-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($item['product']['name'] ?? $item['product_name'] ?? '') ?></td>
                    <td>Rs. <?= number_format((float) $item['unit_price'], 2) ?></td>
                    <td><?= (int) $item['quantity'] ?></td>
                    <td>Rs. <?= number_format((float) $item['discount_amount'], 2) ?></td>
                    <td>Rs. <?= number_format((float) $item['line_total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="inv-totals">
        <div class="inv-totals-box">
            <div class="inv-totals-row">
                <span>Subtotal</span>
                <span>Rs. <?= number_format((float) $totals['subtotal'], 2) ?></span>
            </div>
            <div class="inv-totals-row">
                <span>Item Discount</span>
                <span>− Rs. <?= number_format((float) $totals['line_discount_total'], 2) ?></span>
            </div>
            <?php if (!empty($totals['sale_discount']) && $totals['sale_discount'] > 0): ?>
                <div class="inv-totals-row">
                    <span>Sale Discount</span>
                    <span>− Rs. <?= number_format((float) $totals['sale_discount'], 2) ?></span>
                </div>
            <?php endif; ?>
            <div class="inv-totals-row">
                <span>Grand Total</span>
                <span>Rs. <?= number_format((float) $totals['grand_total'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <?php if (!empty($totals['notes'])): ?>
        <div class="inv-notes">
            <strong>Notes:</strong> <?= htmlspecialchars($totals['notes']) ?>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="inv-footer">
        Thank you for your business! — <?= htmlspecialchars($businessName) ?>
    </div>
</div>

<!-- Auto Print -->
<?php if (!empty($isPrint)): ?>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
<?php endif; ?>

</body>
</html>