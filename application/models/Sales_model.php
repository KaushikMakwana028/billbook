<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Sales_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get paginated and filtered sales list.
     */
    public function get_sales(int $adminId, array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $query = $this->db
            ->select('sales.*, COALESCE(customers.name, "Walk-in Customer") AS customer_name, COALESCE(customers.phone, "-") AS customer_phone, COALESCE(customers.address, "-") AS customer_address, COUNT(sale_items.id) AS items_count, GROUP_CONCAT(sale_items.product_name SEPARATOR ", ") AS products_list')
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
            ->where('sales.admin_id', $adminId);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->group_start()
                ->like('sales.invoice_number', $search)
                ->or_like('customers.name', $search)
                ->or_like('customers.phone', $search)
                ->group_end();
        }

        if (!empty($filters['date_from'])) {
            $query->where('DATE(sales.sale_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('DATE(sales.sale_date) <=', $filters['date_to']);
        }

        return $query
            ->group_by('sales.id')
            ->order_by('sales.id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    /**
     * Get total count of filtered sales.
     */
    public function get_sales_count(int $adminId, array $filters = []): int
    {
        $query = $this->db
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.admin_id', $adminId);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->group_start()
                ->like('sales.invoice_number', $search)
                ->or_like('customers.name', $search)
                ->or_like('customers.phone', $search)
                ->group_end();
        }

        if (!empty($filters['date_from'])) {
            $query->where('DATE(sales.sale_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('DATE(sales.sale_date) <=', $filters['date_to']);
        }

        return $query->count_all_results();
    }

    /**
     * Get single sale details.
     */
    public function get_sale_detail(int $saleId, int $adminId): ?array
    {
        $sale = $this->db
            ->select('sales.*, COALESCE(customers.name, "Walk-in Customer") AS customer_name, COALESCE(customers.phone, "-") AS customer_phone, COALESCE(customers.address, "-") AS customer_address')
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.id', $saleId)
            ->where('sales.admin_id', $adminId)
            ->get()
            ->row_array();

        if (empty($sale)) {
            return null;
        }

        $items = $this->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        // Resolve product default image URLs if needed
        foreach ($items as &$item) {
            $item['product_image_url'] = $this->resolve_product_image($item['product_image'] ?? '');
        }

        $sale['items'] = $items;

        // Invoice PDF & JPG URLs
        $pdfPath = FCPATH . 'uploads/invoices/invoice-' . $saleId . '.pdf';
        $sale['invoice_pdf_url'] = file_exists($pdfPath) 
            ? base_url('uploads/invoices/invoice-' . $saleId . '.pdf') 
            : null;

        $jpgPath = FCPATH . 'uploads/invoices/invoice-' . $saleId . '.jpg';
        $sale['invoice_image_url'] = file_exists($jpgPath)
            ? base_url('uploads/invoices/invoice-' . $saleId . '.jpg')
            : null;

        return $sale;
    }

    /**
     * Lookup or create a customer.
     */
    public function find_or_create_customer(int $adminId, ?string $name, ?string $phone, ?string $address): ?int
    {
        $name = trim((string)$name);
        $phone = trim((string)$phone);
        $address = trim((string)$address);

        // If both empty, return null (walk-in customer)
        if (empty($name) && empty($phone)) {
            return null;
        }

        if (!empty($phone)) {
            $existing = $this->db
                ->where('admin_id', $adminId)
                ->where('phone', $phone)
                ->get('customers')
                ->row_array();

            if (!empty($existing)) {
                // Update name/address if provided and different
                $updateData = [];
                if (!empty($name) && $name !== $existing['name']) {
                    $updateData['name'] = $name;
                }
                if (!empty($address) && $address !== $existing['address']) {
                    $updateData['address'] = $address;
                }

                if (!empty($updateData)) {
                    $this->db
                        ->where('id', $existing['id'])
                        ->where('admin_id', $adminId)
                        ->update('customers', $updateData);
                }
                return (int)$existing['id'];
            }
        }

        // Insert new customer
        $this->db->insert('customers', [
            'admin_id' => $adminId,
            'name' => !empty($name) ? $name : 'Walk-in Customer',
            'phone' => $phone,
            'address' => $address,
            'created_on' => date('Y-m-d H:i:s'),
            'isActive' => 1
        ]);

        return (int)$this->db->insert_id();
    }

    /**
     * Generate sequential invoice number.
     */
    public function generate_invoice_number(int $adminId): string
    {
        $date = date('Ymd');

        $last = $this->db
            ->select('invoice_number')
            ->where('admin_id', $adminId)
            ->like('invoice_number', "INV-$adminId-$date", 'after')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('sales')
            ->row_array();

        $nextNumber = 1;

        if ($last) {
            $parts = explode('-', $last['invoice_number']);
            $lastNumber = (int) end($parts);
            $nextNumber = $lastNumber + 1;
        }

        return 'INV-' . $adminId . '-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create Sale.
     */
    public function create_sale(int $adminId, array $data): array
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw new Exception('No items provided.');
        }

        $this->db->trans_begin();

        try {
            $subtotal = 0.0;
            $totalDiscount = 0.0;
            $saleItems = [];

            foreach ($items as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    continue;
                }

                $product = $this->db
                    ->where('id', $productId)
                    ->where('admin_id', $adminId)
                    ->get('product')
                    ->row_array();

                if (empty($product)) {
                    throw new Exception("Product ID $productId not found.");
                }

                if ((int)$product['quantity'] < $quantity) {
                    throw new Exception("Insufficient stock for product " . $product['name'] . ". Available: " . $product['quantity']);
                }

                $customPrice = isset($item['price']) ? (float)$item['price'] : (float)$product['sale_price'];
                if ($customPrice <= 0) {
                    $customPrice = (float)$product['sale_price'];
                }

                $discountAmount = max(0, (float) ($item['discount'] ?? 0));
                $discountPercent = max(0, min(100, (float) ($item['discount_percent'] ?? 0)));

                $lineBase = $customPrice * $quantity;

                if ($discountAmount > 0) {
                    $discount = min($discountAmount, $lineBase);
                } else {
                    $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
                }

                $lineTotal = $lineBase - $discount;

                $subtotal += $lineBase;
                $totalDiscount += $discount;

                $saleItems[] = [
                    'product_id' => $productId,
                    'product_name' => $product['name'],
                    'product_image' => $product['image'],
                    'quantity' => $quantity,
                    'unit_price' => $customPrice,
                    'purchase_price' => (float)$product['purchase_price'],
                    'discount_amount' => $discount,
                    'discount_percent' => $discountPercent,
                    'line_total' => $lineTotal,
                    'created_on' => date('Y-m-d H:i:s')
                ];
            }

            if (empty($saleItems)) {
                throw new Exception('No valid items to process.');
            }

            $grandTotal = $subtotal - $totalDiscount;

            $customerName = $data['customer_name'] ?? '';
            $customerPhone = $data['customer_phone'] ?? '';
            $customerAddress = $data['customer_address'] ?? '';

            $customerId = $this->find_or_create_customer($adminId, $customerName, $customerPhone, $customerAddress);

            $invoiceNumber = $this->generate_invoice_number($adminId);
            $notes = htmlspecialchars(trim((string)($data['notes'] ?? '')), ENT_QUOTES, 'UTF-8');
            $saleDate = !empty($data['sale_date']) ? date('Y-m-d H:i:s', strtotime($data['sale_date'])) : date('Y-m-d H:i:s');

            $this->db->insert('sales', [
                'admin_id' => $adminId,
                'customer_id' => $customerId,
                'invoice_number' => $invoiceNumber,
                'sale_date' => $saleDate,
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'notes' => $notes,
                'status' => $data['status'] ?? 'paid',
                'created_on' => date('Y-m-d H:i:s')
            ]);

            $saleId = (int)$this->db->insert_id();

            if (!$saleId) {
                throw new Exception('Failed to create sale record.');
            }

            foreach ($saleItems as &$item) {
                $item['sale_id'] = $saleId;
                $this->db->insert('sale_items', $item);

                $this->db
                    ->where('id', $item['product_id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity - ' . (int)$item['quantity'], false)
                    ->update('product');

                if ($this->db->affected_rows() === 0) {
                    throw new Exception("Failed to update stock for product ID " . $item['product_id']);
                }
            }

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction check failed.');
            }

            $this->db->trans_commit();

            return [
                'sale_id' => $saleId,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'customer_id' => $customerId
            ];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    /**
     * Update Sale.
     */
    public function update_sale(int $saleId, int $adminId, array $data): array
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw new Exception('No items provided.');
        }

        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            throw new Exception('Sale not found.');
        }

        $this->db->trans_begin();

        try {
            // Restore stock
            $oldItems = $this->db
                ->where('sale_id', $saleId)
                ->get('sale_items')
                ->result_array();

            foreach ($oldItems as $oldItem) {
                $this->db
                    ->where('id', $oldItem['product_id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity + ' . (int) $oldItem['quantity'], false)
                    ->update('product');
            }

            $subtotal = 0.0;
            $totalDiscount = 0.0;
            $saleItems = [];

            foreach ($items as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    continue;
                }

                $product = $this->db
                    ->where('id', $productId)
                    ->where('admin_id', $adminId)
                    ->get('product')
                    ->row_array();

                if (empty($product)) {
                    throw new Exception("Product ID $productId not found.");
                }

                if ((int)$product['quantity'] < $quantity) {
                    throw new Exception("Insufficient stock for product " . $product['name'] . ". Available: " . $product['quantity']);
                }

                $customPrice = isset($item['price']) ? (float)$item['price'] : (float)$product['sale_price'];
                if ($customPrice <= 0) {
                    $customPrice = (float)$product['sale_price'];
                }

                $discountAmount = max(0, (float) ($item['discount'] ?? 0));
                $discountPercent = max(0, min(100, (float) ($item['discount_percent'] ?? 0)));

                $lineBase = $customPrice * $quantity;

                if ($discountAmount > 0) {
                    $discount = min($discountAmount, $lineBase);
                } else {
                    $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
                }

                $lineTotal = $lineBase - $discount;

                $subtotal += $lineBase;
                $totalDiscount += $discount;

                $saleItems[] = [
                    'sale_id' => $saleId,
                    'product_id' => $productId,
                    'product_name' => $product['name'],
                    'product_image' => $product['image'],
                    'quantity' => $quantity,
                    'unit_price' => $customPrice,
                    'purchase_price' => (float)$product['purchase_price'],
                    'discount_amount' => $discount,
                    'discount_percent' => $discountPercent,
                    'line_total' => $lineTotal,
                    'created_on' => date('Y-m-d H:i:s')
                ];
            }

            if (empty($saleItems)) {
                throw new Exception('No valid items to process.');
            }

            $grandTotal = $subtotal - $totalDiscount;

            $customerName = $data['customer_name'] ?? '';
            $customerPhone = $data['customer_phone'] ?? '';
            $customerAddress = $data['customer_address'] ?? '';

            $customerId = $this->find_or_create_customer($adminId, $customerName, $customerPhone, $customerAddress);

            $notes = htmlspecialchars(trim((string)($data['notes'] ?? '')), ENT_QUOTES, 'UTF-8');
            $saleDate = !empty($data['sale_date']) ? date('Y-m-d H:i:s', strtotime($data['sale_date'])) : date('Y-m-d H:i:s');

            $this->db->where('id', $saleId)->where('admin_id', $adminId)->update('sales', [
                'customer_id' => $customerId,
                'sale_date' => $saleDate,
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'notes' => $notes,
                'status' => $data['status'] ?? $sale['status'] ?? 'paid',
            ]);

            $this->db->where('sale_id', $saleId)->delete('sale_items');

            foreach ($saleItems as $item) {
                $this->db->insert('sale_items', $item);

                $this->db
                    ->where('id', $item['product_id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity - ' . (int)$item['quantity'], false)
                    ->update('product');

                if ($this->db->affected_rows() === 0) {
                    throw new Exception("Failed to update stock for product ID " . $item['product_id']);
                }
            }

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction status check failed.');
            }

            $this->db->trans_commit();

            return [
                'sale_id' => $saleId,
                'invoice_number' => $sale['invoice_number'],
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'customer_id' => $customerId
            ];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    /**
     * Delete Sale.
     */
    public function delete_sale(int $saleId, int $adminId): bool
    {
        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            throw new Exception('Sale not found.');
        }

        $items = $this->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        $this->db->trans_begin();

        try {
            foreach ($items as $item) {
                $this->db
                    ->where('id', $item['product_id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity + ' . (int) $item['quantity'], false)
                    ->update('product');
            }

            $this->db->where('sale_id', $saleId)->delete('sale_items');
            $this->db->where('id', $saleId)->where('admin_id', $adminId)->delete('sales');

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction failed.');
            }

            $this->db->trans_commit();

            $pdfPath = FCPATH . 'uploads/invoices/invoice-' . $saleId . '.pdf';
            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    /**
     * Generate Invoice PDF using Dompdf.
     */
    public function generate_invoice_pdf(int $saleId, int $adminId): string
    {
        $CI =& get_instance();

        $sale = $CI->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            throw new Exception('Sale not found.');
        }

        $items = $CI->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        $customer = $CI->db
            ->where('id', $sale['customer_id'])
            ->where('admin_id', $adminId)
            ->get('customers')
            ->row_array();

        if (empty($customer)) {
            $customer = [
                'name' => 'Walk-in Customer',
                'phone' => '',
                'address' => ''
            ];
        }

        $settings = $CI->db
            ->where('admin_id', $adminId)
            ->get('invoice_settings')
            ->row_array();

        if (empty($settings)) {
            $settings = [
                'template_id'     => 1,
                'primary_color'   => '#0f172a',
                'accent_color'    => '#2563eb',
                'signature_image' => null,
            ];
        }

        $customTemplate = null;
        if (!empty($settings['template_id']) && $settings['template_id'] > 6) {
            $customTemplate = $CI->db
                ->where('id', $settings['template_id'])
                ->where('admin_id', $adminId)
                ->get('invoice_templates')
                ->row_array();
            if (!empty($customTemplate)) {
                $settings['primary_color'] = $customTemplate['primary_color'];
                $settings['accent_color'] = $customTemplate['accent_color'];
            }
        }

        $adminData = $CI->db
            ->where('id', $adminId)
            ->get('users')
            ->row_array();

        $viewData = [
            'businessName' => $adminData['business_name'] ?? 'Bills Book',
            'invoiceNumber' => $sale['invoice_number'],
            'customer' => $customer,
            'items' => array_map(function($item) {
                return [
                    'product' => [
                        'name' => $item['product_name'],
                        'image' => $item['product_image'],
                        'price' => $item['unit_price']
                    ],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'],
                    'discount_percent' => $item['discount_percent'],
                    'line_total' => $item['line_total']
                ];
            }, $items),
            'totals' => [
                'subtotal' => $sale['subtotal'],
                'line_discount_total' => $sale['discount_amount'],
                'sale_discount' => 0,
                'grand_total' => $sale['grand_total'],
                'sale_date' => $sale['sale_date'],
                'notes' => $sale['notes']
            ],
            'saleId' => $saleId,
            'invoiceSettings' => $settings,
            'signatureUrl' => !empty($settings['signature_image'])
                ? base_url('uploads/signatures/' . $settings['signature_image'])
                : null,
            'logoUrl' => !empty($adminData['profile_image'])
                ? base_url('uploads/profile/' . $adminData['profile_image'])
                : null,
            'businessInfo' => [
                'name'      => $adminData['business_name'] ?? 'Your Business',
                'phone'     => $adminData['mobile']    ?? $adminData['phone'] ?? '',
                'address'   => $adminData['address']   ?? '',
                'instagram' => $adminData['instagram'] ?? '',
                'facebook'  => $adminData['facebook']  ?? '',
            ]
        ];

        $templateView = 'invoice_tpl_1';
        if ($customTemplate) {
            $layout = $customTemplate['layout'];
            if ($layout === 'classic') {
                $templateView = 'invoice_tpl_2';
            } else if ($layout === 'creative') {
                $templateView = 'invoice_tpl_3';
            } else if ($layout === 'compact' || $layout === 'receipt') {
                $templateView = 'invoice_tpl_4';
            } else {
                $templateView = 'invoice_tpl_1';
            }
        } else {
            $tid = (int) $settings['template_id'];
            if ($tid === 2) {
                $templateView = 'invoice_tpl_2';
            } else if ($tid === 3) {
                $templateView = 'invoice_tpl_3';
            } else if ($tid === 4 || $tid === 6) {
                $templateView = 'invoice_tpl_4';
            } else {
                $templateView = 'invoice_tpl_1';
            }
        }

        $html = $CI->load->view($templateView, $viewData, true);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $folder = FCPATH . 'uploads/invoices/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . 'invoice-' . $saleId . '.pdf';
        file_put_contents($filePath, $dompdf->output());

        // Save file path to database
        $relativeName = 'uploads/invoices/invoice-' . $saleId . '.pdf';
        $CI->db->where('id', $saleId)->where('admin_id', $adminId)->update('sales', [
            'invoice_file' => $relativeName
        ]);

        // Also generate JPG invoice image (for WhatsApp preview, downloads, and image exports)
        try {
            $this->generate_invoice_image($saleId, $adminId, $html, $filePath);
        } catch (\Exception $e) {
            log_message('error', 'Invoice JPG generation notice: ' . $e->getMessage());
        }

        return base_url('uploads/invoices/invoice-' . $saleId . '.pdf');
    }

    /**
     * Generate Invoice JPG image using multi-strategy fallback:
     * 1. pdftoppm (poppler-utils on Linux server)
     * 2. Headless Chrome / Chromium / Edge screenshot (Windows / Linux)
     * 3. Imagick extension
     * 4. PHP GD receipt card generation fallback
     */
    public function generate_invoice_image(int $saleId, int $adminId, ?string $html = null, ?string $pdfPath = null): ?string
    {
        $folder = FCPATH . 'uploads/invoices/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $pdfFile = $pdfPath ?: ($folder . 'invoice-' . $saleId . '.pdf');
        $basePath = $folder . 'invoice-' . $saleId;
        $jpgPath = $basePath . '.jpg';

        // 1. Try pdftoppm (poppler-utils on Linux production server)
        if (function_exists('exec') && file_exists($pdfFile)) {
            $cmd = 'pdftoppm -jpeg -r 150 -f 1 -l 1 -singlefile ' . escapeshellarg($pdfFile) . ' ' . escapeshellarg($basePath) . ' 2>&1';
            @exec($cmd, $pOut, $pRc);
            if ($pRc === 0 && file_exists($jpgPath) && filesize($jpgPath) > 0) {
                return base_url('uploads/invoices/invoice-' . $saleId . '.jpg');
            }
        }

        // 2. Try Headless Chrome or Edge
        $browser = $this->find_system_browser();
        if ($browser && function_exists('exec')) {
            $tempPng = $folder . 'temp_' . $saleId . '_' . bin2hex(random_bytes(4)) . '.png';
            $sourceTarget = null;
            $tempHtml = null;

            if (!empty($html)) {
                $tempHtml = $folder . 'temp_' . $saleId . '_' . bin2hex(random_bytes(4)) . '.html';
                file_put_contents($tempHtml, $html);
                $sourceTarget = $tempHtml;
            } elseif (file_exists($pdfFile)) {
                $sourceTarget = $pdfFile;
            }

            if ($sourceTarget) {
                $bCmd = escapeshellarg($browser) . ' --headless=new --disable-gpu --screenshot=' . escapeshellarg($tempPng) . ' --window-size=850,1200 ' . escapeshellarg($sourceTarget) . ' 2>&1';
                @exec($bCmd, $bOut, $bRc);

                if (file_exists($tempPng) && filesize($tempPng) > 0) {
                    if (function_exists('imagecreatefrompng') && function_exists('imagejpeg')) {
                        $pngImg = @imagecreatefrompng($tempPng);
                        if ($pngImg) {
                            imagejpeg($pngImg, $jpgPath, 92);
                            imagedestroy($pngImg);
                        }
                    }
                    @unlink($tempPng);
                }
            }

            if ($tempHtml && file_exists($tempHtml)) {
                @unlink($tempHtml);
            }

            if (file_exists($jpgPath) && filesize($jpgPath) > 0) {
                return base_url('uploads/invoices/invoice-' . $saleId . '.jpg');
            }
        }

        // 3. Try Imagick extension
        if (extension_loaded('imagick') && file_exists($pdfFile)) {
            try {
                $im = new \Imagick();
                $im->setResolution(150, 150);
                $im->readImage($pdfFile . '[0]');
                $im->setImageFormat('jpeg');
                $im->setImageCompressionQuality(90);
                $im->writeImage($jpgPath);
                $im->clear();
                $im->destroy();
                if (file_exists($jpgPath) && filesize($jpgPath) > 0) {
                    return base_url('uploads/invoices/invoice-' . $saleId . '.jpg');
                }
            } catch (\Exception $e) {
                log_message('error', 'Imagick JPG generation failed: ' . $e->getMessage());
            }
        }

        return file_exists($jpgPath) ? base_url('uploads/invoices/invoice-' . $saleId . '.jpg') : null;
    }

    private function find_system_browser(): ?string
    {
        $candidates = [
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser'
        ];
        foreach ($candidates as $c) {
            if (file_exists($c)) {
                return $c;
            }
        }
        return null;
    }

    private function resolve_product_image(string $image): string
    {
        if ($image === '') {
            return base_url('assets/images/Product_Default.png');
        }

        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        if (strpos($image, 'uploads/') === 0 || strpos($image, 'assets/') === 0) {
            return base_url($image);
        }

        return base_url('uploads/product/' . ltrim($image, '/'));
    }
}
