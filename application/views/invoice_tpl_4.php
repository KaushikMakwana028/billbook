<?php
if (!function_exists('convertNumberToWords')) {
    function convertNumberToWords($number) {
        $number = round((float)$number);
        if ($number == 0) return 'Zero Rupees only';
        $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
        $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        $words = '';
        if ($number >= 10000000) { $words .= convertNumberToWords(floor($number/10000000)).' Crore '; $number %= 10000000; }
        if ($number >= 100000) { $words .= convertNumberToWords(floor($number/100000)).' Lakh '; $number %= 100000; }
        if ($number >= 1000) { $words .= convertNumberToWords(floor($number/1000)).' Thousand '; $number %= 1000; }
        if ($number >= 100) { $words .= $ones[floor($number/100)].' Hundred '; $number %= 100; }
        if ($number >= 20) { $words .= $tens[floor($number/10)].' '; $number %= 10; }
        if ($number > 0) { $words .= $ones[$number].' '; }
        return trim($words).' Rupees only';
    }
}
$pc = $invoiceSettings['primary_color'] ?? '#0f172a';
$ac = $invoiceSettings['accent_color'] ?? '#2563eb';
$totalQty = 0; $totalDiscount = 0;
foreach ($items as $item) { $totalQty += (int)$item['quantity']; $totalDiscount += (float)$item['discount_amount']; }
$youSaved = (float)($totals['line_discount_total'] ?? 0) + (float)($totals['sale_discount'] ?? 0);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',Arial,sans-serif; font-size:12px; color:#1a1a1a; background:#fff; }
    .inv { max-width:780px; margin:0 auto; }

    .inv-header-bar {
        background:<?= $ac ?>; padding:22px 25px; color:#fff;
    }
    .inv-header-table { width:100%; }
    .inv-logo { width:70px; vertical-align:middle; }
    .inv-logo img { max-height:65px; max-width:100px; border-radius:10px; background:rgba(255,255,255,0.15); padding:4px; }
    .inv-company { text-align:right; vertical-align:middle; }
    .inv-company-name { font-size:22px; font-weight:900; color:#fff; }
    .inv-company-detail { font-size:10px; color:rgba(255,255,255,0.75); line-height:1.6; }

    .inv-title-bar {
        text-align:center; padding:14px 0; margin:0 25px;
        border-bottom:4px solid <?= $ac ?>;
    }
    .inv-title-bar h2 {
        font-size:16px; font-weight:900; color:<?= $ac ?>;
        text-transform:uppercase; letter-spacing:0.15em;
        background:<?= $ac ?>15; display:inline-block; padding:4px 20px; border-radius:4px;
    }

    .inv-info { padding:15px 25px; }
    .inv-info-table { width:100%; }
    .inv-info-left { width:50%; vertical-align:top; }
    .inv-info-right { width:50%; vertical-align:top; text-align:right; }
    .inv-info-label {
        font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.06em;
        color:#fff; background:<?= $ac ?>; display:inline-block; padding:4px 12px;
        border-radius:6px; margin-bottom:8px;
    }
    .inv-info-content { font-size:12px; color:#334155; line-height:1.7; }
    .inv-info-content strong { color:#0f172a; font-size:13px; }
    .inv-detail-row { margin-bottom:3px; }
    .inv-detail-label { color:#64748b; font-size:11px; }
    .inv-detail-value { font-weight:700; color:#0f172a; }

    .inv-items-wrap { padding:0 25px 15px; }
    .inv-table { width:100%; border-collapse:collapse; }
    .inv-table thead th {
        background:<?= $ac ?>; color:#fff; font-size:10px; text-transform:uppercase;
        padding:11px 10px; text-align:left; font-weight:800; letter-spacing:0.03em;
    }
    .inv-table thead th.r { text-align:right; }
    .inv-table thead th.c { text-align:center; }
    .inv-table tbody td { padding:9px 10px; border-bottom:1px solid #e2e8f0; font-size:12px; }
    .inv-table tbody td.r { text-align:right; }
    .inv-table tbody td.c { text-align:center; }
    .inv-table tbody tr:nth-child(even) { background:<?= $ac ?>08; }
    .inv-table tfoot td {
        padding:11px 10px; font-weight:900; font-size:12px;
        background:<?= $ac ?>10; border-top:3px solid <?= $ac ?>; color:<?= $pc ?>;
    }
    .inv-table tfoot td.r { text-align:right; }
    .inv-table tfoot td.c { text-align:center; }

    .inv-bottom { padding:15px 25px; }
    .inv-bottom-table { width:100%; }
    .inv-bottom-left { width:50%; vertical-align:top; padding-right:15px; }
    .inv-bottom-right { width:50%; vertical-align:top; }

    .inv-words-box {
        background:<?= $ac ?>10; border:2px solid <?= $ac ?>30;
        border-radius:8px; padding:10px 12px; margin-bottom:12px;
    }
    .inv-words-label { font-size:9px; font-weight:800; text-transform:uppercase; color:<?= $ac ?>; margin-bottom:4px; }
    .inv-words-text { font-size:12px; color:#0f172a; font-weight:700; font-style:italic; }

    .inv-terms-box {
        background:#fffbeb; border:2px solid #fef08a; border-radius:8px; padding:10px 12px;
    }
    .inv-terms-label { font-size:9px; font-weight:800; text-transform:uppercase; color:#92400e; margin-bottom:4px; }
    .inv-terms-text { font-size:11px; color:#78350f; line-height:1.5; }

    .inv-summary { border:2px solid <?= $ac ?>30; border-radius:8px; overflow:hidden; }
    .inv-sum-row { display:table; width:100%; padding:8px 12px; border-bottom:1px solid <?= $ac ?>15; }
    .inv-sum-row:last-child { border-bottom:none; }
    .inv-sum-label { display:table-cell; font-size:11px; color:#64748b; width:55%; font-weight:600; }
    .inv-sum-value { display:table-cell; font-size:11px; color:#0f172a; font-weight:700; text-align:right; width:45%; }
    .inv-sum-total { background:<?= $ac ?>; }
    .inv-sum-total .inv-sum-label, .inv-sum-total .inv-sum-value { color:#fff; font-size:14px; font-weight:900; }
    .inv-sum-saved { background:#f0fdf4; }
    .inv-sum-saved .inv-sum-label { color:#15803d; font-weight:700; }
    .inv-sum-saved .inv-sum-value { color:#15803d; font-weight:800; }

    .inv-footer-sig { padding:25px 25px 15px; text-align:right; }
    .inv-sig-for { font-size:11px; color:#64748b; margin-bottom:5px; }
    .inv-sig-for strong { color:#0f172a; font-size:12px; }
    .inv-sig-img img { max-height:55px; }
    .inv-sig-line { width:160px; border-top:3px solid <?= $ac ?>; margin-left:auto; padding-top:6px; font-size:10px; color:#64748b; text-align:center; font-weight:600; }

    .inv-page-footer {
        text-align:center; padding:14px 25px; background:<?= $ac ?>;
        color:#fff; font-size:10px; font-weight:600;
    }

    @media print { body{padding:0} .no-print{display:none!important} }
</style>
</head>
<body>
<div class="inv">

    <div class="inv-header-bar">
        <table class="inv-header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="inv-logo"><?php if(!empty($logoUrl)):?><img src="<?=$logoUrl?>" alt="Logo"><?php endif;?></td>
                <td class="inv-company">
                    <div class="inv-company-name"><?= htmlspecialchars($businessInfo['name'] ?? $businessName) ?></div>
                    <div class="inv-company-detail">
    <?php if (!empty($businessInfo['address'])): ?>
        <?= htmlspecialchars($businessInfo['address']) ?><br>
    <?php endif; ?>
    <?php if (!empty($businessInfo['phone'])): ?>
        📞 <?= htmlspecialchars($businessInfo['phone']) ?>
    <?php endif; ?>
    <?php if (!empty($businessInfo['state'])): ?>
        &nbsp;|&nbsp; <?= htmlspecialchars($businessInfo['state']) ?>
    <?php endif; ?>
    <?php if (!empty($businessInfo['instagram']) || !empty($businessInfo['facebook'])): ?>
        <br>
        <?php if (!empty($businessInfo['instagram'])): ?>
            📷 @<?= htmlspecialchars($businessInfo['instagram']) ?>
        <?php endif; ?>
        <?php if (!empty($businessInfo['instagram']) && !empty($businessInfo['facebook'])): ?>
            &nbsp;|&nbsp;
        <?php endif; ?>
        <?php if (!empty($businessInfo['facebook'])): ?>
            📘 fb/<?= htmlspecialchars($businessInfo['facebook']) ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="inv-title-bar"><h2>Tax Invoice</h2></div>

    <div class="inv-info">
        <table class="inv-info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="inv-info-left">
                    <div class="inv-info-label">Bill To</div>
                    <div class="inv-info-content">
                        <strong><?= htmlspecialchars($customer['name'] ?? 'Cash Sale') ?></strong><br>
                        <?= !empty($customer['phone']) ? htmlspecialchars($customer['phone']).'<br>' : '' ?>
                        <?= !empty($customer['address']) ? htmlspecialchars($customer['address']) : '' ?>
                    </div>
                </td>
                <td class="inv-info-right">
                    <div class="inv-info-label">Invoice Details</div>
                    <div class="inv-info-content">
                        <div class="inv-detail-row"><span class="inv-detail-label">Invoice No: </span><span class="inv-detail-value"><?= htmlspecialchars($invoiceNumber) ?></span></div>
                        <div class="inv-detail-row"><span class="inv-detail-label">Date: </span><span class="inv-detail-value"><?= date('d/m/Y', strtotime($totals['sale_date'])) ?></span></div>
                        <?php if(!empty($businessInfo['state'])):?><div class="inv-detail-row"><span class="inv-detail-label">Place of Supply: </span><span class="inv-detail-value"><?= htmlspecialchars($businessInfo['state']) ?></span></div><?php endif;?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="inv-items-wrap">
        <table class="inv-table">
            <thead><tr><th style="width:30px">#</th><th>Item Name</th><th class="c" style="width:60px">Qty</th><th class="r" style="width:90px">Price/Unit</th><th class="r" style="width:90px">Discount</th><th class="r" style="width:100px">Amount</th></tr></thead>
            <tbody>
                <?php foreach($items as $i=>$item): $lb=(float)$item['unit_price']*(int)$item['quantity']; $dp=$lb>0?round(((float)$item['discount_amount']/$lb)*100):0; ?>
                <tr>
                    <td><?=$i+1?></td>
                    <td><strong><?= htmlspecialchars($item['product']['name'] ?? $item['product_name'] ?? '') ?></strong></td>
                    <td class="c"><?=(int)$item['quantity']?></td>
                    <td class="r">Rs. <?=number_format((float)$item['unit_price'],2)?></td>
                    <td class="r">Rs. <?=number_format((float)$item['discount_amount'],2)?><?php if($dp>0):?> <span style="color:<?=$ac?>;font-size:10px;font-weight:700">(<?=$dp?>%)</span><?php endif;?></td>
                    <td class="r"><strong>Rs. <?=number_format((float)$item['line_total'],2)?></strong></td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot><tr><td></td><td><strong>Total</strong></td><td class="c"><strong><?=$totalQty?></strong></td><td class="r"></td><td class="r"><strong>Rs. <?=number_format($totalDiscount,2)?></strong></td><td class="r"><strong>Rs. <?=number_format((float)$totals['grand_total'],2)?></strong></td></tr></tfoot>
        </table>
    </div>

    <div class="inv-bottom">
        <table class="inv-bottom-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="inv-bottom-left">
                    <div class="inv-words-box"><div class="inv-words-label">Invoice Amount in Words</div><div class="inv-words-text"><?= convertNumberToWords($totals['grand_total']) ?></div></div>
                    <div class="inv-terms-box"><div class="inv-terms-label">Terms & Conditions</div><div class="inv-terms-text"><?= !empty($totals['notes']) ? htmlspecialchars($totals['notes']) : 'Thanks for doing business with us!' ?></div></div>
                </td>
                <td class="inv-bottom-right">
                    <div class="inv-summary">
                        <div class="inv-sum-row"><span class="inv-sum-label">Sub Total</span><span class="inv-sum-value">Rs. <?=number_format((float)$totals['subtotal'],2)?></span></div>
                        <?php if($totalDiscount>0):?><div class="inv-sum-row"><span class="inv-sum-label">Discount</span><span class="inv-sum-value">− Rs. <?=number_format($totalDiscount,2)?></span></div><?php endif;?>
                        <div class="inv-sum-row inv-sum-total"><span class="inv-sum-label">Total</span><span class="inv-sum-value">Rs. <?=number_format((float)$totals['grand_total'],2)?></span></div>
                        <div class="inv-sum-row"><span class="inv-sum-label">Received</span><span class="inv-sum-value">Rs. <?=number_format((float)$totals['grand_total'],2)?></span></div>
                        <div class="inv-sum-row"><span class="inv-sum-label">Balance</span><span class="inv-sum-value">Rs. 0.00</span></div>
                        <?php if($youSaved>0):?><div class="inv-sum-row inv-sum-saved"><span class="inv-sum-label">🎉 You Saved</span><span class="inv-sum-value">Rs. <?=number_format($youSaved,2)?></span></div><?php endif;?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="inv-footer-sig">
        <div class="inv-sig-for">For: <strong><?= htmlspecialchars($businessInfo['name'] ?? $businessName) ?></strong></div>
        <?php if(!empty($signatureUrl)):?><div class="inv-sig-img"><img src="<?=$signatureUrl?>" alt="Signature"></div><?php else:?><div style="height:50px"></div><?php endif;?>
        <div class="inv-sig-line">Authorized Signatory</div>
    </div>

<div class="inv-page-footer">
    <div>Thank you for your business! — <?= htmlspecialchars($businessInfo['name'] ?? $businessName) ?></div>
    <?php if (!empty($businessInfo['phone']) || !empty($businessInfo['instagram']) || !empty($businessInfo['facebook'])): ?>
        <div style="margin-top:4px;font-size:9px;color:#94a3b8">
            <?php if (!empty($businessInfo['phone'])): ?>
                📞 <?= htmlspecialchars($businessInfo['phone']) ?>
            <?php endif; ?>
            <?php if (!empty($businessInfo['instagram'])): ?>
                &nbsp;&nbsp;📷 @<?= htmlspecialchars($businessInfo['instagram']) ?>
            <?php endif; ?>
            <?php if (!empty($businessInfo['facebook'])): ?>
                &nbsp;&nbsp;📘 fb/<?= htmlspecialchars($businessInfo['facebook']) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div></div>
<?php if(!empty($isPrint)):?><script>window.onload=function(){window.print()};</script><?php endif;?>
</body>
</html>