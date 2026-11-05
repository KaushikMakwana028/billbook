<div class="page-wrapper">
    <div class="page-content">
        <style>
            .product-form-shell {
                max-width: 980px;
            }

            .product-form-card {
                border: 1px solid #e2e8f0;
                box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            }

            .product-form-title {
                font-size: 2rem;
                font-weight: 700;
                color: #0f172a;
            }

            .product-form-copy {
                color: #64748b;
                max-width: 620px;
            }

            .product-field label {
                font-weight: 600;
                color: #334155;
                margin-bottom: 0.45rem;
            }

            .product-field .form-control,
            .product-field .form-select {
                min-height: 54px;
                border-radius: 14px;
                border-color: #dbe3ef;
            }

            .product-field textarea.form-control {
                min-height: 140px;
            }

            .product-preview-image {
                width: 54px;
                height: 54px;
                border-radius: 14px;
                object-fit: cover;
                border: 1px solid #dbe3ef;
                background: #fff;
            }

            .product-file-input {
                position: absolute;
                opacity: 0;
                pointer-events: none;
            }

            .product-file-trigger {
                min-height: 54px;
                border: 1px solid #dbe3ef;
                border-radius: 14px;
                background: #fff;
                display: flex;
                align-items: center;
                overflow: hidden;
                width: 100%;
                box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
            }

            .product-file-button {
                min-height: 40px;
                min-width: 124px;
                margin-left: 7px;
                padding: 0 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: #ffffff;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
                cursor: pointer;
            }

            .product-file-name {
                flex: 1 1 auto;
                padding: 0 1rem 0 0.9rem;
                color: #475569;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-weight: 500;
            }

            .product-image-inline {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 180px;
                gap: 1rem;
                align-items: stretch;
                width: 100%;
            }

            .product-inline-preview {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                padding: 0.7rem 0.85rem;
                border: 1px dashed #cbd5e1;
                border-radius: 14px;
                background: #f8fbff;
                width: 100%;
                min-height: 54px;
            }

            .product-qty-field .form-control {
                text-align: center;
            }

            .product-image-field {
                min-width: 0;
            }

            .product-image-field .product-file-trigger,
            .product-image-field .product-inline-preview,
            .product-image-field .product-file-name {
                min-width: 0;
            }

            @media (max-width: 767.98px) {
                .product-image-inline {
                    grid-template-columns: 1fr;
                }

                .product-file-button {
                    min-width: 112px;
                    margin-left: 6px;
                    padding: 0 0.85rem;
                }
            }
        </style>

        <div class="row justify-content-center product-form-shell">
            <div class="col-12">
                <div class="card rounded-4 product-form-card">
                    <div class="card-body p-4 p-xl-5">
                        <div class="mb-4">
                            <h2 class="product-form-title mb-2">Add Product</h2>
                            <p class="product-form-copy mb-0">Create a new product for the current admin. Product image is optional and stored in `uploads/product` when added.</p>
                        </div>

                        <?php if (!empty($productError)): ?>
                            <div class="alert alert-danger"><?= $productError ?></div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('product/store') ?>" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6 product-field">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">Select category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= (int) $category['id'] ?>" <?= set_select('category_id', (string) $category['id']) ?>><?= htmlspecialchars($category['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('category_id') ?>
                                </div>
                                <div class="col-md-6 product-field">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" value="<?= set_value('name') ?>" class="form-control" placeholder="Enter product name">
                                    <?= form_error('name') ?>
                                </div>
                                <div class="col-md-6 product-field">
    <label class="form-label">Barcode</label>
    <input
        type="text"
        name="barcode"
        value="<?= set_value('barcode') ?>"
        class="form-control"
        placeholder="Enter product barcode"
    >
    <?= form_error('barcode') ?>
</div>
                               <div class="col-md-4 product-field">
    <label class="form-label">Purchase Price</label>
    <input type="text" name="purchase_price" value="<?= set_value('purchase_price') ?>" class="form-control" placeholder="Enter purchase price">
    <?= form_error('purchase_price') ?>
</div>

<div class="col-md-4 product-field">
    <label class="form-label">Sale Price</label>
    <input type="text" name="sale_price" value="<?= set_value('sale_price') ?>" class="form-control" placeholder="Enter sale price">
    <?= form_error('sale_price') ?>
</div>
                                <div class="col-md-4 product-field product-qty-field">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" min="0" name="quantity" value="<?= set_value('quantity', '0') ?>" class="form-control" placeholder="Enter quantity">
                                    <?= form_error('quantity') ?>
                                </div>
                                <div class="col-12 product-field product-image-field">
                                    <label class="form-label">Product Image <span class="text-muted">(Optional)</span></label>
                                    <input type="file" name="product_image" id="product_image_add" class="product-file-input" accept=".jpg,.jpeg,.png,.webp">
                                    <div class="product-image-inline">
                                        <label for="product_image_add" class="product-file-trigger w-100 mb-0">
                                            <span class="product-file-button">Choose File</span>
                                            <span class="product-file-name" id="product_file_name_add">No file chosen</span>
                                        </label>
                                        <div class="product-inline-preview">
                                            <img src="<?= base_url('assets/images/Product_Default.png') ?>" id="product_preview_add" class="product-preview-image" alt="Selected product image">
                                            <small class="text-muted">Preview</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 product-field">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter product description"><?= set_value('description') ?></textarea>
                                    <?= form_error('description') ?>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">Save Product</button>
                                    <a href="<?= base_url('product') ?>" class="btn btn-outline-secondary px-4">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('product_image_add');
        var preview = document.getElementById('product_preview_add');
        var fileName = document.getElementById('product_file_name_add');

        if (!input || !preview || !fileName) {
            return;
        }

        input.addEventListener('change', function(event) {
            var file = event.target.files && event.target.files[0];
            if (!file) {
                preview.src = '<?= base_url('assets/images/Product_Default.png') ?>';
                fileName.textContent = 'No file chosen';
                return;
            }

            fileName.textContent = file.name;
            preview.src = URL.createObjectURL(file);
        });
    });
</script>
