<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 18px 20px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #ffffff;
            color: #0f1117;
            font-size: 13px;
        }

        /* ══ Outer card ══ */
        .invoice-card {
            max-width: 760px;
            margin: 0 auto;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }

        /* ══ Top header ══ */
        .top-bar {
            padding: 24px 28px 20px;
            border-bottom: 1px solid #e4e7ef;
        }

        .top-table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .logo-row {
            display: inline-block;
        }

        .logo-box {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #4f46e5;
            border-radius: 10px;
            vertical-align: middle;
            text-align: center;
            line-height: 40px;
        }

        .logo-box span {
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .biz-name {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .biz-name-text {
            font-size: 17px;
            font-weight: bold;
            color: #0f1117;
        }

        .biz-sub {
            font-size: 11px;
            color: #9299a8;
            margin-top: 2px;
        }

        .inv-meta {
            text-align: right;
        }

        .inv-number {
            font-size: 20px;
            font-weight: bold;
            color: #0f1117;
            letter-spacing: -0.4px;
        }

        .inv-date {
            font-size: 11px;
            color: #9299a8;
            margin-top: 3px;
        }

        .status-pill {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 100px;
            margin-top: 6px;
            border: 1px solid #bbf7d0;
        }

        /* ══ Info strip (3 cols) ══ */
        .info-strip {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #e4e7ef;
        }

        .info-strip td {
            width: 33.33%;
            padding: 16px 24px;
            vertical-align: top;
            border: none;
        }

        .info-strip td+td {
            border-left: 1px solid #e4e7ef;
        }

        .cell-label {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #9299a8;
            margin-bottom: 6px;
        }

        .cell-primary {
            font-size: 13px;
            font-weight: bold;
            color: #0f1117;
            margin-bottom: 2px;
        }

        .cell-secondary {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* ══ Items table ══ */
        .items-wrap {
            padding: 0 24px;
        }

        .items-head-row {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #e4e7ef;
        }

        .items-head-row th {
            padding: 11px 8px 9px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9299a8;
            text-align: left;
            border: none;
        }

        .items-head-row th.r {
            text-align: right;
        }

        .items-data {
            width: 100%;
            border-collapse: collapse;
        }

        .items-data td {
            padding: 13px 8px;
            border-bottom: 1px solid #f1f3f8;
            vertical-align: middle;
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .items-data tr:last-child td {
            border-bottom: none;
        }

        .items-data td.r {
            text-align: right;
        }

        /* product cell: image + text side by side */
        .product-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .product-inner td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .product-thumb {
            width: 38px;
            height: 38px;
            background: #f6f7fb;
            border: 1px solid #e4e7ef;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }

        .product-thumb img {
            width: 36px;
            height: 36px;
            object-fit: cover;
        }

        .thumb-placeholder {
            width: 36px;
            height: 36px;
            line-height: 36px;
            font-size: 16px;
            color: #d1d5db;
            text-align: center;
        }

        .product-name-text {
            font-size: 13px;
            font-weight: bold;
            color: #0f1117;
            padding-left: 10px;
        }

        .product-sku {
            font-size: 10px;
            color: #9299a8;
            padding-left: 10px;
            margin-top: 2px;
        }

        .qty-cell {
            font-size: 13px;
            color: #4a4e5a;
            text-align: right;
        }

        .price-cell {
            font-size: 13px;
            color: #4a4e5a;
            text-align: right;
        }

        .disc-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 5px;
            border: 1px solid #fde68a;
        }

        .no-disc {
            color: #d1d5db;
        }

        .total-cell {
            font-size: 13px;
            font-weight: bold;
            color: #0f1117;
            text-align: right;
        }

        /* ══ Bottom (note + totals) ══ */
        .bottom-strip {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #e4e7ef;
        }

        .bottom-strip td {
            padding: 20px 24px;
            vertical-align: top;
            border: none;
        }

        .note-cell {
            width: 55%;
        }

        .totals-cell {
            width: 45%;
            border-left: 1px solid #e4e7ef;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px 0;
            font-size: 13px;
            color: #6b7280;
            border: none;
        }

        .totals-table td.amount {
            text-align: right;
        }

        .totals-table tr.divider td {
            border-top: 1px solid #e4e7ef;
            padding-top: 10px;
            margin-top: 6px;
        }

        .grand-label {
            font-size: 14px;
            font-weight: bold;
            color: #0f1117;
        }

        .grand-amount {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            text-align: right;
        }

        .disc-amount-text {
            color: #dc2626;
        }

        /* ══ Footer bar ══ */
        .footer-bar {
            background: #f6f7fb;
            padding: 11px 28px;
            border-top: 1px solid #e4e7ef;
        }

        .footer-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-inner td {
            border: none;
            padding: 0;
            font-size: 11px;
            color: #9299a8;
            vertical-align: middle;
        }

        .footer-right {
            text-align: right;
            color: #4f46e5;
            font-weight: bold;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <?php
    $customerName    = htmlspecialchars($customer['name']    ?? 'Walk-in Customer');
    $customerPhone   = htmlspecialchars($customer['phone']   ?? '—');
    $customerAddress = htmlspecialchars($customer['address'] ?? '—');
    $totalDiscount   = $totals['line_discount_total'] + $totals['sale_discount'];
    $firstLetter     = strtoupper(substr($businessName, 0, 1));
    ?>

    <div class="invoice-card">

        <!-- ══ HEADER ══ -->
        <div class="top-bar">
            <table class="top-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div>
                            <div class="logo-box"><span><?= $firstLetter ?></span></div>
                            <div class="biz-name">
                                <div class="biz-name-text"><?= htmlspecialchars($businessName) ?></div>
                                <div class="biz-sub">Tax Invoice &bull; GST Registered</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="inv-meta">
                            <div class="inv-number"><?= htmlspecialchars($invoiceNumber) ?></div>
                            <div class="inv-date"><?= htmlspecialchars($totals['sale_date']) ?></div>
                            <div><span class="status-pill">&#10003; Paid</span></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ══ INFO STRIP ══ -->
        <table class="info-strip" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="cell-label">Billed To</div>
                    <div class="cell-primary"><?= $customerName ?></div>
                    <div class="cell-secondary"><?= $customerPhone ?><br><?= $customerAddress ?></div>
                </td>
                <td>
                    <div class="cell-label">Payment Method</div>
                    <div class="cell-primary">Cash</div>
                    <div class="cell-secondary">Settled on delivery</div>
                </td>
                <td>
                    <div class="cell-label">Due Date</div>
                    <div class="cell-primary"><?= htmlspecialchars($totals['sale_date']) ?></div>
                    <div class="cell-secondary">Paid in full</div>
                </td>
            </tr>
        </table>

        <!-- ══ LINE ITEMS ══ -->
        <div class="items-wrap">
            <table class="items-head-row" cellpadding="0" cellspacing="0">
                <tr>
                    <th style="width:40%;">Product</th>
                    <th class="r" style="width:8%;">Qty</th>
                    <th class="r" style="width:16%;">Unit Price</th>
                    <th class="r" style="width:16%;">Discount</th>
                    <th class="r" style="width:20%;">Total</th>
                </tr>
            </table>

            <table class="items-data" cellpadding="0" cellspacing="0">
                <?php foreach ($items as $i => $item): ?>
                    <?php
                    $product = [
                        'name'  => $item['product_name'] ?? '',
                        'image' => $item['product_image'] ?? ''
                    ];

                    $imageFile = !empty($product['image'])
                        ? 'uploads/product/' . $product['image']
                        : 'assets/images/Product_Default.png';

                    $imagePath = FCPATH . $imageFile;

                    $imageData = '';

                    if (file_exists($imagePath)) {
                        $imageType   = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                        $imageBase64 = base64_encode(file_get_contents($imagePath));
                        $imageData   = 'data:image/' . $imageType . ';base64,' . $imageBase64;
                    }

                    $hasDiscount = $item['discount_amount'] > 0;
                    $skuCode     = htmlspecialchars($product['sku'] ?? ('SKU-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT)));
                    ?>
                    <tr>
                        <td style="width:40%;">
                            <table class="product-inner" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:42px;">
                                        <div class="product-thumb">
                                            <?php if ($imageData): ?>
                                                <img src="<?= $imageData ?>" width="36" height="36" alt="">
                                            <?php else: ?>
                                                <div class="thumb-placeholder">&#9632;</div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-name-text"><?= htmlspecialchars($product['name'] ?? '') ?></div>
                                        <div class="product-sku"><?= $skuCode ?></div>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td class="qty-cell r" style="width:8%;"><?= (int) $item['quantity'] ?></td>

                        <td class="price-cell r" style="width:16%;">
                            Rs <?= number_format($item['unit_price'], 2) ?>
                        </td>

                        <td class="r" style="width:16%;">
                            <?php if ($hasDiscount): ?>
                                <span class="disc-badge">Rs <?= number_format($item['discount_amount'], 2) ?></span>
                            <?php else: ?>
                                <span class="no-disc">—</span>
                            <?php endif; ?>
                        </td>

                        <td class="total-cell r" style="width:20%;">
                            Rs <?= number_format($item['line_total'], 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- ══ BOTTOM STRIP ══ -->
        <table class="bottom-strip" cellpadding="0" cellspacing="0">
            <tr>
                <td class="note-cell">
                    <div class="cell-label">Note</div>
                    <div class="cell-secondary" style="margin-top:6px; line-height:1.7;">
                        Thank you for your business!<br>
                        For queries, contact us at support@mybusiness.com
                    </div>
                </td>
                <td class="totals-cell">
                    <table class="totals-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>Subtotal</td>
                            <td class="amount">Rs <?= number_format($totals['subtotal'], 2) ?></td>
                        </tr>
                        <?php if ($totalDiscount > 0): ?>
                            <tr>
                                <td>Discount</td>
                                <td class="amount disc-amount-text">
                                    &minus; Rs <?= number_format($totalDiscount, 2) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Tax</td>
                            <td class="amount">Rs 0.00</td>
                        </tr>
                        <tr class="divider">
                            <td class="grand-label">Grand Total</td>
                            <td class="grand-amount">Rs <?= number_format($totals['grand_total'], 2) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- ══ FOOTER BAR ══ -->
        <div class="footer-bar">
            <table class="footer-inner" cellpadding="0" cellspacing="0">
                <tr>
                    <td>Generated on <?= htmlspecialchars($totals['sale_date']) ?></td>
                    <td class="footer-right"><?= htmlspecialchars($businessName) ?> &mdash; mybusiness.com</td>
                </tr>
            </table>
        </div>

    </div>

</body>

</html>