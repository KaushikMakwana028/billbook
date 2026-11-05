<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print Barcodes</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    .print-toolbar {
        margin-bottom: 20px;
    }
    .print-toolbar button {
        padding: 10px 24px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }
    .barcode-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .barcode-sticker {
        border: 1px dashed #999;
        padding: 8px;
        text-align: center;
        page-break-inside: avoid;
    }
    .barcode-sticker img {
        max-width: 100%;
        height: auto;
    }
    .barcode-number {
        font-size: 11px;
        margin-top: 4px;
        letter-spacing: 1px;
    }

    @media print {
        .print-toolbar { display: none; }
        body { padding: 0; }
        .barcode-grid { gap: 4px; }
    }
</style>
</head>
<body>

    <div class="print-toolbar">
        <button onclick="window.print()">🖨️ Print Now</button>
    </div>

    <div class="barcode-grid">
        <?php if (!empty($barcodeItems)): ?>
            <?php foreach ($barcodeItems as $item): ?>
                <div class="barcode-sticker">
                    <img src="data:image/png;base64,<?= $item['image_base64'] ?>" alt="barcode">
                    <div class="barcode-number"><?= htmlspecialchars($item['barcode']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No barcodes available to print.</p>
        <?php endif; ?>
    </div>

</body>
</html>