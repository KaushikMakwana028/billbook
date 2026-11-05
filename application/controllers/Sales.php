<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'core/My_Controller.php');

require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Sales extends My_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        // ✅ Load admin from session
        $this->admin = $this->session->userdata('admin');
        // echo "<pre>";
        // print_r($this->admin);
        // die;

        // ✅ Protect all methods
        if (!$this->admin) {
            redirect('login');
        }
    }
    public function index()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $data = $this->buildBaseData($adminId);

        $rawFilters = [
            'search'    => trim((string) $this->input->get('search', true)),
            'date_from' => trim((string) $this->input->get('date_from', true)),
            'date_to'   => trim((string) $this->input->get('date_to', true))
        ];
        $filters = $this->normalizeFilters($rawFilters);

        // Pagination
        $page = max(1, (int)($this->input->get('page') ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Metrics (Total Rows, Total Sales, Total Profit)
        $metrics = $this->getSalesMetrics($adminId, $filters);
        $totalRows = (int)$metrics['total_rows'];

        $data['totalRows']   = $totalRows;
        $data['totalSales']  = (float)$metrics['total_sales'];
        $data['totalProfit'] = (float)$metrics['total_profit'];

        $data['totalPages']  = ceil($totalRows / $limit);
        $data['currentPage'] = $page;
        $data['perPage']     = $limit;

        // Fetch paginated sales
        $data['sales']       = $this->getSales($adminId, $filters, $limit, $offset);

        $data['filters']     = $filters;
        $data['pageTitle']   = 'Sales';

        $this->load->view('header', $data);
        $this->load->view('sales_view', $data);
        $this->load->view('footer', $data);
    }

    public function add()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $data = $this->buildBaseData($adminId);
        $data['products'] = $this->getSellableProducts($adminId);
        $data['pageTitle'] = 'Create Sale';

        $this->load->view('header', $data);
        $this->load->view('sales_add', $data);
        $this->load->view('footer', $data);
    }

    public function whatsapp_connect()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $data = $this->buildBaseData($adminId);
        $data['pageTitle'] = 'WhatsApp Connect';
        $data['whatsappQrUrl'] = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . rawurlencode(base_url('sales'));

        $this->load->view('header', $data);
        $this->load->view('whatsapp_connect_view', $data);
        $this->load->view('footer', $data);
    }

    public function customer_lookup()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $phone = preg_replace('/\D+/', '', (string) $this->input->get('phone', true));
        $customer = null;

        if ($phone !== '') {
            $customer = $this->db
                ->where('admin_id', $adminId)
                ->where('phone', $phone)
                ->where('isActive', 1)
                ->get('customers')
                ->row_array();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => $customer ? 'found' : 'not_found',
                'customer' => $customer
            ]));
    }

   public function store_old()
{
    $adminId = (int) ($this->admin['user_id'] ?? 0);

    if ($adminId <= 0) {
        redirect('login');
        return;
    }

    $customerPhone = trim((string)$this->input->post('customer_phone'));

    if (!empty($customerPhone) && !preg_match('/^[0-9]{10,15}$/', $customerPhone)) {
        $this->session->set_flashdata('error', '❌ Invalid phone number format.');
        redirect('sales/add');
        return;
    }

    $productIds           = $this->input->post('product_id');
    $quantities            = $this->input->post('item_quantity');
    $itemDiscounts         = $this->input->post('item_discount');
    $itemDiscountPercents  = $this->input->post('item_discount_percent');
    $itemPrices            = $this->input->post('item_price');
    $isCustomArr           = $this->input->post('is_custom');       // ✅ NEW
    $customNames           = $this->input->post('custom_name');     // ✅ NEW

    if (
        !is_array($productIds) || !is_array($quantities) ||
        !is_array($itemDiscounts) || !is_array($itemDiscountPercents) || !is_array($itemPrices)
    ) {
        $this->session->set_flashdata('error', '❌ Invalid form data submitted.');
        redirect('sales/add');
        return;
    }

    $isCustomArr = is_array($isCustomArr) ? $isCustomArr : [];
    $customNames = is_array($customNames) ? $customNames : [];

    // Check at least one valid item (product OR custom)
    $hasValidItems = false;
    foreach ($productIds as $index => $pid) {
        $pid = (int)$pid;
        $qty = (int)($quantities[$index] ?? 0);
        $isCustom = !empty($isCustomArr[$index]);
        $customName = trim((string)($customNames[$index] ?? ''));

        if ($qty > 0 && (($pid > 0 && !$isCustom) || ($isCustom && $customName !== ''))) {
            $hasValidItems = true;
            break;
        }
    }

    if (!$hasValidItems) {
        $this->session->set_flashdata('error', '❌ Add at least one product with a valid quantity.');
        redirect('sales/add');
        return;
    }

    $this->db->trans_begin();

    try {
        $saleItems = [];
        $subtotal = 0.0;
        $lineDiscountTotal = 0.0;

        foreach ($productIds as $index => $productId) {
            $productId = (int) $productId;
            $quantity  = (int) ($quantities[$index] ?? 0);
            $isCustom  = !empty($isCustomArr[$index]);

            if ($quantity <= 0) continue;

            $customPrice     = (float) ($itemPrices[$index] ?? 0);
            $discountAmount  = max(0, (float) ($itemDiscounts[$index] ?? 0));
            $discountPercent = max(0, min(100, (float) ($itemDiscountPercents[$index] ?? 0)));

            if ($isCustom) {
                // ── CUSTOM ITEM ──
                $customName = trim((string) ($customNames[$index] ?? ''));
                if ($customName === '') continue;

                $lineBase = $customPrice * $quantity;

                if ($discountAmount > 0) {
                    $discount = min($discountAmount, $lineBase);
                } else {
                    $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
                }
                $lineTotal = $lineBase - $discount;

                $subtotal += $lineBase;
                $lineDiscountTotal += $discount;

                $saleItems[] = [
                    'product' => [
                        'id' => 0,
                        'name' => $customName,
                        'image' => null,
                        'purchase_price' => 0,
                    ],
                    'is_custom' => 1,
                    'quantity' => $quantity,
                    'unit_price' => $customPrice,
                    'discount_amount' => $discount,
                    'discount_percent' => $discountPercent,
                    'line_total' => $lineTotal
                ];
                continue;
            }

            // ── CATALOG PRODUCT ──
            if ($productId <= 0) continue;

            $product = $this->db
                ->where('id', $productId)
                ->where('admin_id', $adminId)
                ->get('product')
                ->row_array();

            if (empty($product)) {
                throw new Exception('Invalid product selected.');
            }

            if ((int)$product['quantity'] < $quantity) {
                throw new Exception('Insufficient stock for ' . $product['name'] . '. Available: ' . $product['quantity']);
            }

            if ($customPrice <= 0) {
                $customPrice = (float)$product['sale_price'];
            }

            $lineBase = $customPrice * $quantity;

            if ($discountAmount > 0) {
                $discount = min($discountAmount, $lineBase);
            } else {
                $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
            }
            $lineTotal = $lineBase - $discount;

            $subtotal += $lineBase;
            $lineDiscountTotal += $discount;

            $saleItems[] = [
                'product' => $product,
                'is_custom' => 0,
                'quantity' => $quantity,
                'unit_price' => $customPrice,
                'discount_amount' => $discount,
                'discount_percent' => $discountPercent,
                'line_total' => $lineTotal
            ];
        }

        if (empty($saleItems)) {
            throw new Exception('No valid items to process.');
        }

        $grandTotal = $subtotal - $lineDiscountTotal;

        $customerName = trim((string)$this->input->post('customer_name'));
        $customerAddress = trim((string)$this->input->post('customer_address'));

        $customerId = null;
        $customer = [
            'name' => !empty($customerName) ? $customerName : 'Walk-in Customer',
            'phone' => $customerPhone,
            'address' => $customerAddress
        ];

        if (!empty($customerName) || !empty($customerPhone)) {
            $customerId = $this->findOrCreateCustomer($adminId);
            if ($customerId) {
                $dbCustomer = $this->getCustomer($customerId, $adminId);
                if (!empty($dbCustomer)) {
                    $customer = $dbCustomer;
                }
            }
        }

        $invoiceNumber = $this->generateInvoiceNumber($adminId);
        $notes = htmlspecialchars(trim((string)$this->input->post('notes')), ENT_QUOTES, 'UTF-8');
     $pickedDate = $this->input->post('sale_date') ?: date('Y-m-d');
// Combine the user-picked date with the actual current time of sale creation
$saleDate = $pickedDate . ' ' . date('H:i:s');

        $this->db->insert('sales', [
            'admin_id' => $adminId,
            'customer_id' => $customerId,
            'invoice_number' => $invoiceNumber,
            'sale_date' => $saleDate,
            'subtotal' => $subtotal,
            'discount_amount' => $lineDiscountTotal,
            'grand_total' => $grandTotal,
            'notes' => $notes,
            'created_on' => date('Y-m-d H:i:s')
        ]);

        $saleId = (int)$this->db->insert_id();

        if (!$saleId) {
            throw new Exception('Failed to create sale record.');
        }

        foreach ($saleItems as $item) {
            $product = $item['product'];
            $isCustom = !empty($item['is_custom']);

            $this->db->insert('sale_items', [
                'sale_id' => $saleId,
                'product_id' => $isCustom ? 0 : $product['id'],
                'is_custom' => $isCustom ? 1 : 0,
                'product_name' => $product['name'],
                'product_image' => $product['image'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'purchase_price' => $isCustom ? 0 : (float)$product['purchase_price'],
                'discount_amount' => $item['discount_amount'],
                'discount_percent' => $item['discount_percent'],
                'line_total' => $item['line_total'],
                'created_on' => date('Y-m-d H:i:s')
            ]);

            if (!$isCustom) {
                $this->db
                    ->where('id', $product['id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity - ' . (int)$item['quantity'], false)
                    ->update('product');

                if ($this->db->affected_rows() === 0) {
                    throw new Exception('Failed to update stock for ' . $product['name']);
                }
            }
        }

        if ($this->db->trans_status() === false) {
            throw new Exception('Transaction failed.');
        }

        $this->db->trans_commit();
log_message('error', 'STORE OK: committed saleId=' . $saleId);
        $viewData = [
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'invoiceNumber' => $invoiceNumber,
            'customer' => $customer,
            'items' => $saleItems,
            'totals' => [
                'subtotal' => $subtotal,
                'line_discount_total' => $lineDiscountTotal,
                'grand_total' => $grandTotal,
                'sale_date' => $saleDate,
                'notes' => $notes
            ],
            'saleId' => $saleId
        ];
log_message('error', 'STORE: starting PDF');
        $pdfUrl = null;
        try {
            $pdfUrl = $this->generateInvoicePdf($saleId, $viewData);
        } catch (Exception $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
log_message('error', 'STORE: starting WhatsApp check');

        if (!empty($cleanPhone) && strlen($cleanPhone) >= 10 && $pdfUrl) {
            try {
                if ($this->isWhatsAppConnected($adminId)) {
                    $this->sendWhatsAppInvoice(
                        '91' . $cleanPhone,
                        $pdfUrl,
                        $adminId,
                        $customer,
                        $invoiceNumber,
                        $grandTotal
                    );
                }
            } catch (Exception $e) {
                log_message('error', 'WhatsApp send failed: ' . $e->getMessage());
            }
        }
log_message('error', 'STORE: about to redirect');

        $this->session->set_flashdata('success', '✅ Sale created successfully! Invoice: ' . $invoiceNumber);

        $whatsappUrl = '';
        if (!empty($cleanPhone) && strlen($cleanPhone) >= 10) {
            $whatsappUrl = $this->buildWhatsappUrl([
                'customer_phone' => $customerPhone,
                'customer_name' => $customer['name'],
                'invoice_number' => $invoiceNumber,
                'grand_total' => $grandTotal
            ]);
        }

        $this->session->set_flashdata('latest_sale_actions', [
            'sale_id' => $saleId,
            'invoice_number' => $invoiceNumber,
            'customer_name' => $customer['name'],
            'whatsapp_url' => $whatsappUrl
        ]);

        redirect('sales');
    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', '❌ ' . $e->getMessage());
        redirect('sales/add');
        return;
    }
}
public function store()
{
    $adminId = (int) ($this->admin['user_id'] ?? 0);
// echo "<pre>";
// print_r($this->admin);
// die;
    if ($adminId <= 0) {
        redirect('login');
        return;
    }

    $customerPhone = trim((string)$this->input->post('customer_phone'));

    if (!empty($customerPhone) && !preg_match('/^[0-9]{10,15}$/', $customerPhone)) {
        $this->session->set_flashdata('error', '❌ Invalid phone number format.');
        redirect('sales/add');
        return;
    }

    $productIds           = $this->input->post('product_id');
    $quantities            = $this->input->post('item_quantity');
    $itemDiscounts         = $this->input->post('item_discount');
    $itemDiscountPercents  = $this->input->post('item_discount_percent');
    $itemPrices            = $this->input->post('item_price');
    $isCustomArr           = $this->input->post('is_custom');       // ✅ NEW
    $customNames           = $this->input->post('custom_name');     // ✅ NEW

    if (
        !is_array($productIds) || !is_array($quantities) ||
        !is_array($itemDiscounts) || !is_array($itemDiscountPercents) || !is_array($itemPrices)
    ) {
        $this->session->set_flashdata('error', '❌ Invalid form data submitted.');
        redirect('sales/add');
        return;
    }

    $isCustomArr = is_array($isCustomArr) ? $isCustomArr : [];
    $customNames = is_array($customNames) ? $customNames : [];

    // Check at least one valid item (product OR custom)
    $hasValidItems = false;
    foreach ($productIds as $index => $pid) {
        $pid = (int)$pid;
        $qty = (int)($quantities[$index] ?? 0);
        $isCustom = !empty($isCustomArr[$index]);
        $customName = trim((string)($customNames[$index] ?? ''));

        if ($qty > 0 && (($pid > 0 && !$isCustom) || ($isCustom && $customName !== ''))) {
            $hasValidItems = true;
            break;
        }
    }

    if (!$hasValidItems) {
        $this->session->set_flashdata('error', '❌ Add at least one product with a valid quantity.');
        redirect('sales/add');
        return;
    }

    $this->db->trans_begin();

    try {
        $saleItems = [];
        $subtotal = 0.0;
        $lineDiscountTotal = 0.0;

        foreach ($productIds as $index => $productId) {
            $productId = (int) $productId;
            $quantity  = (int) ($quantities[$index] ?? 0);
            $isCustom  = !empty($isCustomArr[$index]);

            if ($quantity <= 0) continue;

            $customPrice     = (float) ($itemPrices[$index] ?? 0);
            $discountAmount  = max(0, (float) ($itemDiscounts[$index] ?? 0));
            $discountPercent = max(0, min(100, (float) ($itemDiscountPercents[$index] ?? 0)));

            if ($isCustom) {
                // ── CUSTOM ITEM ──
                $customName = trim((string) ($customNames[$index] ?? ''));
                if ($customName === '') continue;

                $lineBase = $customPrice * $quantity;

                if ($discountAmount > 0) {
                    $discount = min($discountAmount, $lineBase);
                } else {
                    $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
                }
                $lineTotal = $lineBase - $discount;

                $subtotal += $lineBase;
                $lineDiscountTotal += $discount;

                $saleItems[] = [
                    'product' => [
                        'id' => 0,
                        'name' => $customName,
                        'image' => null,
                        'purchase_price' => 0,
                    ],
                    'is_custom' => 1,
                    'quantity' => $quantity,
                    'unit_price' => $customPrice,
                    'discount_amount' => $discount,
                    'discount_percent' => $discountPercent,
                    'line_total' => $lineTotal
                ];
                continue;
            }

            // ── CATALOG PRODUCT ──
            if ($productId <= 0) continue;

            $product = $this->db
                ->where('id', $productId)
                ->where('admin_id', $adminId)
                ->get('product')
                ->row_array();

            if (empty($product)) {
                throw new Exception('Invalid product selected.');
            }

            if ((int)$product['quantity'] < $quantity) {
                throw new Exception('Insufficient stock for ' . $product['name'] . '. Available: ' . $product['quantity']);
            }

            if ($customPrice <= 0) {
                $customPrice = (float)$product['sale_price'];
            }

            $lineBase = $customPrice * $quantity;

            if ($discountAmount > 0) {
                $discount = min($discountAmount, $lineBase);
            } else {
                $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
            }
            $lineTotal = $lineBase - $discount;

            $subtotal += $lineBase;
            $lineDiscountTotal += $discount;

            $saleItems[] = [
                'product' => $product,
                'is_custom' => 0,
                'quantity' => $quantity,
                'unit_price' => $customPrice,
                'discount_amount' => $discount,
                'discount_percent' => $discountPercent,
                'line_total' => $lineTotal
            ];
        }

        if (empty($saleItems)) {
            throw new Exception('No valid items to process.');
        }

        $grandTotal = $subtotal - $lineDiscountTotal;

        $customerName = trim((string)$this->input->post('customer_name'));
        $customerAddress = trim((string)$this->input->post('customer_address'));

        $customerId = null;
        $customer = [
            'name' => !empty($customerName) ? $customerName : 'Walk-in Customer',
            'phone' => $customerPhone,
            'address' => $customerAddress
        ];

        if (!empty($customerName) || !empty($customerPhone)) {
            $customerId = $this->findOrCreateCustomer($adminId);
            if ($customerId) {
                $dbCustomer = $this->getCustomer($customerId, $adminId);
                if (!empty($dbCustomer)) {
                    $customer = $dbCustomer;
                }
            }
        }

        $invoiceNumber = $this->generateInvoiceNumber($adminId);
        $notes = htmlspecialchars(trim((string)$this->input->post('notes')), ENT_QUOTES, 'UTF-8');
     $pickedDate = $this->input->post('sale_date') ?: date('Y-m-d');
// Combine the user-picked date with the actual current time of sale creation
$saleDate = $pickedDate . ' ' . date('H:i:s');

        $this->db->insert('sales', [
            'admin_id' => $adminId,
            'customer_id' => $customerId,
            'invoice_number' => $invoiceNumber,
            'sale_date' => $saleDate,
            'subtotal' => $subtotal,
            'discount_amount' => $lineDiscountTotal,
            'grand_total' => $grandTotal,
            'notes' => $notes,
            'created_on' => date('Y-m-d H:i:s')
        ]);

        $saleId = (int)$this->db->insert_id();

        if (!$saleId) {
            throw new Exception('Failed to create sale record.');
        }

        foreach ($saleItems as $item) {
            $product = $item['product'];
            $isCustom = !empty($item['is_custom']);

            $this->db->insert('sale_items', [
                'sale_id' => $saleId,
                'product_id' => $isCustom ? 0 : $product['id'],
                'is_custom' => $isCustom ? 1 : 0,
                'product_name' => $product['name'],
                'product_image' => $product['image'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'purchase_price' => $isCustom ? 0 : (float)$product['purchase_price'],
                'discount_amount' => $item['discount_amount'],
                'discount_percent' => $item['discount_percent'],
                'line_total' => $item['line_total'],
                'created_on' => date('Y-m-d H:i:s')
            ]);

            if (!$isCustom) {
                $this->db
                    ->where('id', $product['id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity - ' . (int)$item['quantity'], false)
                    ->update('product');

                if ($this->db->affected_rows() === 0) {
                    throw new Exception('Failed to update stock for ' . $product['name']);
                }
            }
        }

        if ($this->db->trans_status() === false) {
            throw new Exception('Transaction failed.');
        }

        $this->db->trans_commit();
log_message('error', 'STORE OK: committed saleId=' . $saleId);
        $viewData = [
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'invoiceNumber' => $invoiceNumber,
            'customer' => $customer,
            'items' => $saleItems,
            'totals' => [
                'subtotal' => $subtotal,
                'line_discount_total' => $lineDiscountTotal,
                'grand_total' => $grandTotal,
                'sale_date' => $saleDate,
                'notes' => $notes
            ],
            'saleId' => $saleId
        ];
log_message('error', 'STORE: starting PDF');
        $pdfUrl = null;
        try {
            $pdfUrl = $this->generateInvoicePdf($saleId, $viewData);
        } catch (Exception $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
log_message('error', 'STORE: starting WhatsApp check');
log_message('error', 'WA pdfUrl dummy: ' . $pdfUrl);           // ADD THIS LINE

        if (!empty($cleanPhone) && strlen($cleanPhone) >= 10 && $pdfUrl) {
            try {
                $this->load->library('whatsapp_sender');
                $dir     = FCPATH . 'uploads/invoices/';
$rand    = bin2hex(random_bytes(8));
$pdfFile = 'inv_' . $saleId . '_' . $rand . '.pdf';           // random name

$src = $dir . 'invoice-' . $saleId . '.pdf';                  // the PDF your code already created
if (is_file($src)) {
    copy($src, $dir . $pdfFile);
}

$jpg = $this->whatsapp_sender->make_preview_image(
    $dir . $pdfFile,
    $dir . 'inv_' . $saleId . '_' . $rand                     // ".jpg" is added automatically
);
$route = $this->whatsapp_sender->send_invoice(
    $adminId,            // the logged-in vendor id
    $customerPhone,      // raw phone, the library formats it
    $pdfUrl,             // PUBLIC https link to the invoice PDF
    [
        'business_name' => $this->admin['business_name'] ?? 'Bills Book',
        'customer_name' => $customer,
        'invoice_no'    => $invoiceNumber,
        'amount'        => number_format($grandTotal, 2, '.', ''),
        'instagram'     => $this->admin['instagram'],
         'pdf_file'      => $pdfFile,                                                        // NEW
        'image_url'     => $jpg ? base_url('uploads/invoices/' . basename($jpg)) : '',
    ]
);
log_message('error', 'WA final route in dummy: ' . $route);
            } catch (Exception $e) {
                log_message('error', 'WhatsApp send failed: ' . $e->getMessage());
            }
        }
log_message('error', 'STORE: about to redirect');

        $this->session->set_flashdata('success', '✅ Sale created successfully! Invoice: ' . $invoiceNumber);

        $whatsappUrl = '';
        if (!empty($cleanPhone) && strlen($cleanPhone) >= 10) {
            $whatsappUrl = $this->buildWhatsappUrl([
                'customer_phone' => $customerPhone,
                'customer_name' => $customer['name'],
                'invoice_number' => $invoiceNumber,
                'grand_total' => $grandTotal
            ]);
        }

        $this->session->set_flashdata('latest_sale_actions', [
            'sale_id' => $saleId,
            'invoice_number' => $invoiceNumber,
            'customer_name' => $customer['name'],
            'whatsapp_url' => $whatsappUrl
        ]);

        redirect('sales');
    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', '❌ ' . $e->getMessage());
        redirect('sales/add');
        return;
    }
}

    private function generateInvoicePdf($saleId, $viewData)
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $settings = $this->db
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

        // FIX: Fetch admin/business data from users table
        $adminData = $this->db
            ->where('id', $adminId)
            ->get('users')
            ->row_array();

        $viewData['invoiceSettings'] = $settings;

        $viewData['signatureUrl'] = !empty($settings['signature_image'])
            ? base_url('uploads/signatures/' . $settings['signature_image'])
            : null;

        $logoImage = $this->admin['profile_image'] ?? null;
        $viewData['logoUrl'] = !empty($logoImage)
            ? base_url('uploads/profile/' . $logoImage)
            : null;

        // FIX: Business info from DB, not session
        $viewData['businessInfo'] = [
            'name'      => $adminData['business_name'] ?? 'Your Business',
            'phone'     => $adminData['mobile']    ?? $adminData['phone'] ?? '',
            'address'   => $adminData['address']   ?? '',
            'instagram' => $adminData['instagram'] ?? '',
            'facebook'  => $adminData['facebook']  ?? '',
        ];

        $templateView = 'invoice_tpl_' . (int) $settings['template_id'];
        if (!file_exists(APPPATH . 'views/' . $templateView . '.php')) {
            $templateView = 'invoice_tpl_1';
        }

        $html = $this->load->view($templateView, $viewData, true);

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

        return base_url('uploads/invoices/invoice-' . $saleId . '.pdf');
    }
   private function isWhatsAppConnected($adminId)
{
    // ✅ Add 2-second timeout context so file_get_contents NEVER hangs PHP
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'GET',
            'timeout' => 2, // Max 2 seconds wait
            'ignore_errors' => true
        ]
    ]);

    $url = "https://wa.visiontechnolabs.com/status/" . (int)$adminId;
    $response = @file_get_contents($url, false, $ctx);

    if ($response === false) {
        log_message('error', 'WhatsApp status server timed out or unreachable.');
        return false; // Fail fast, do not block sale creation
    }

    $data = json_decode($response, true);
    return $data['connected'] ?? false;
}
   private function sendWhatsAppInvoice($number, $pdfUrl, $adminId, $customer, $invoiceNumber, $grandTotal)
{
    $businessName = $this->admin['business_name'] ?? 'Your Business';

    $adminId = $this->session->userdata('user_id') ?? $adminId;

    $instaLine = '';
    $userRow = $this->db->select('instagram')
                         ->where('id', $adminId)
                         ->get('users')
                         ->row();

    if (!empty($userRow->instagram)) {
        $instaLink = 'https://instagram.com/' . $userRow->instagram;
        $instaLine = "\n✨ Stay connected & explore our latest collections:\n📸 {$instaLink}\n";
    }

    $message  = "Greetings from {$businessName} 👋\n\n";
    $message .= "Dear {$customer['name']},\n";
    $message .= "We are pleased to have you as a valued customer.\n\n";
    $message .= "📄 Invoice No: {$invoiceNumber}\n";
    $message .= "💰 Amount: ₹{$grandTotal}\n";
    $message .= $instaLine;
    $message .= "\nPlease find attached your invoice.\n";
    $message .= "Thank you for your business 🙏";

    $payload = json_encode([
        "userId"   => (string)$adminId,
        "number"   => $number,
        "document" => $pdfUrl,
        "caption"  => $message
    ]);

    $ch = curl_init("https://wa.visiontechnolabs.com/send-document");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    // ✅ TIMEOUT FIXES: Hard 2s connect / 4s total timeout to prevent hanging
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        log_message('error', 'WA Curl Error: ' . curl_error($ch));
    } else {
        log_message('error', 'WA PDF Response: ' . $response);
    }

    curl_close($ch);
}
    // ─────────────────────────────────────────────
    // PRINT INVOICE
    // ─────────────────────────────────────────────
    public function print_invoice($saleId = 0)
    {
        $saleId  = (int) $saleId;
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            $this->session->set_flashdata('error', 'Sale not found.');
            redirect('sales');
            return;
        }

        $items = $this->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        $customer = $this->db
            ->where('id', $sale['customer_id'])
            ->where('admin_id', $adminId)
            ->get('customers')
            ->row_array();

        // Invoice settings
        $settings = $this->db
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

        // FIX: Fetch admin/business data from users table
        $adminData = $this->db
            ->where('id', $adminId)
            ->get('users')
            ->row_array();

        // Totals
        $subtotal         = 0;
        $lineDiscountTotal = 0;
        $saleItems        = [];

        foreach ($items as $item) {
            $lineBase          = (float) $item['unit_price'] * (int) $item['quantity'];
            $subtotal          += $lineBase;
            $lineDiscountTotal += (float) $item['discount_amount'];

            $saleItems[] = [
                'product' => [
                    'name'  => $item['product_name'],
                    'image' => $item['product_image'],
                    'price' => $item['unit_price'],
                ],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'discount_amount' => $item['discount_amount'],
                'line_total'      => $item['line_total'],
            ];
        }

        $grandTotal   = (float) $sale['grand_total'];
        $totalDiscount = (float) $sale['discount_amount'];
        $saleDiscount  = $totalDiscount - $lineDiscountTotal;

        // Logo
        $logoImage = $this->admin['profile_image'] ?? null;
        $logoUrl   = !empty($logoImage)
            ? base_url('uploads/profile/' . $logoImage)
            : null;

        // Signature
        $signatureUrl = !empty($settings['signature_image'])
            ? base_url('uploads/signatures/' . $settings['signature_image'])
            : null;

        $viewData = [
            'businessName'    => $adminData['business_name'] ?? ($this->admin['business_name'] ?? 'Bills Book'),
            'businessInfo'    => [
                'name'      => $adminData['business_name'] ?? 'Your Business',
                // FIX: was using mobile twice; now falls back to phone column
                'phone'     => $adminData['mobile'] ?? $adminData['phone'] ?? '',
                'address'   => $adminData['address']   ?? '',
                'instagram' => $adminData['instagram'] ?? '',
                'facebook'  => $adminData['facebook']  ?? '',
            ],
            'invoiceNumber'   => $sale['invoice_number'],
            'customer'        => $customer,
            'items'           => $saleItems,
            'invoiceSettings' => $settings,
            'signatureUrl'    => $signatureUrl,
            'logoUrl'         => $logoUrl,
            'totals'          => [
                'subtotal'            => $subtotal,
                'line_discount_total' => $lineDiscountTotal,
                'sale_discount'       => max(0, $saleDiscount),
                'grand_total'         => $grandTotal,
                'sale_date'           => $sale['sale_date'],
                'notes'               => $sale['notes'] ?? '',
            ],
            'saleId'  => $saleId,
            'isPrint' => true,
        ];

        $templateView = 'invoice_tpl_' . (int) $settings['template_id'];
        if (!file_exists(APPPATH . 'views/' . $templateView . '.php')) {
            $templateView = 'invoice_tpl_1';
        }

        $this->load->view($templateView, $viewData);
    }


    /**
     * Edit Sale — Load sale data into edit form
     */
    public function edit($saleId = 0)
    {
        $saleId  = (int) $saleId;
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        // Get sale
        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            $this->session->set_flashdata('error', 'Sale not found.');
            redirect('sales');
            return;
        }

        // Get sale items
        $saleItems = $this->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        // Get customer
        $customer = $this->db
            ->where('id', $sale['customer_id'])
            ->where('admin_id', $adminId)
            ->get('customers')
            ->row_array();

        // Get all sellable products
        $products = $this->getSellableProducts($adminId);

        $data = $this->buildBaseData($adminId);
        $data['pageTitle']  = 'Edit Sale';
        $data['sale']       = $sale;
        $data['saleItems']  = $saleItems;
        $data['customer']   = $customer;
        $data['products']   = $products;

        $this->load->view('header', $data);
        $this->load->view('sales_edit', $data);
        $this->load->view('footer', $data);
    }


    /**
     * Update Sale — Process edit form
     */
public function update($saleId = 0)
{
    $saleId  = (int) $saleId;
    $adminId = (int) ($this->admin['user_id'] ?? 0);

    $sale = $this->db
        ->where('id', $saleId)
        ->where('admin_id', $adminId)
        ->get('sales')
        ->row_array();

    if (empty($sale)) {
        $this->session->set_flashdata('error', 'Sale not found.');
        redirect('sales');
        return;
    }

    $productIds           = $this->input->post('product_id');
    $quantities            = $this->input->post('item_quantity');
    $itemDiscounts         = $this->input->post('item_discount');
    $itemPrices            = $this->input->post('item_price');
    $itemDiscountPercs     = $this->input->post('item_discount_percent');
    $isCustomArr           = $this->input->post('is_custom');
    $customNames           = $this->input->post('custom_name');

    $isCustomArr = is_array($isCustomArr) ? $isCustomArr : [];
    $customNames = is_array($customNames) ? $customNames : [];

    $hasValidItems = false;
    foreach ((array)$productIds as $index => $pid) {
        $pid = (int)$pid;
        $qty = (int)($quantities[$index] ?? 0);
        $isCustom = !empty($isCustomArr[$index]);
        $customName = trim((string)($customNames[$index] ?? ''));

        if ($qty > 0 && (($pid > 0 && !$isCustom) || ($isCustom && $customName !== ''))) {
            $hasValidItems = true;
            break;
        }
    }

    if (!$hasValidItems) {
        $this->session->set_flashdata('error', 'Please fix the errors and try again.');
        redirect('sales/edit/' . $saleId);
        return;
    }

    $oldItems = $this->db
        ->where('sale_id', $saleId)
        ->get('sale_items')
        ->result_array();

    $this->db->trans_begin();

    try {
        // Step 1: Restore old stock (skip custom items — they never touched stock)
        foreach ($oldItems as $oldItem) {
            if (!empty($oldItem['is_custom'])) continue;
            $this->db
                ->where('id', $oldItem['product_id'])
                ->where('admin_id', $adminId)
                ->set('quantity', 'quantity + ' . (int) $oldItem['quantity'], false)
                ->update('product');
        }

        // Step 2: Build new items
        $subtotal = 0;
        $lineDiscountTotal = 0;
        $saleItems = [];

        foreach ((array) $productIds as $index => $productId) {
            $productId = (int) $productId;
            $quantity  = (int) ($quantities[$index] ?? 0);
            $isCustom  = !empty($isCustomArr[$index]);

            if ($quantity <= 0) continue;

            $price            = (float) ($itemPrices[$index] ?? 0);
            $discountAmount   = max(0, (float) ($itemDiscounts[$index] ?? 0));
            $discountPercent  = max(0, min(100, (float) ($itemDiscountPercs[$index] ?? 0)));

            if ($isCustom) {
                $customName = trim((string) ($customNames[$index] ?? ''));
                if ($customName === '') continue;

                $lineBase = $price * $quantity;

                if ($discountAmount > 0) {
                    $discount = min($discountAmount, $lineBase);
                } else {
                    $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
                }
                $lineTotal = $lineBase - $discount;

                $subtotal += $lineBase;
                $lineDiscountTotal += $discount;

                $saleItems[] = [
                    'product' => [
                        'id' => 0,
                        'name' => $customName,
                        'image' => null,
                        'purchase_price' => 0,
                    ],
                    'is_custom' => 1,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'discount_amount' => $discount,
                    'discount_percent' => $discountPercent,
                    'line_total' => $lineTotal,
                ];
                continue;
            }

            if ($productId <= 0) continue;

            $product = $this->getOwnedProduct($productId, $adminId);
            if (!$product) continue;

            if ($price <= 0) {
                $price = (float) ($product['sale_price'] ?? 0);
            }

            if ((int) $product['quantity'] < $quantity) {
                throw new Exception('Insufficient stock for ' . $product['name'] . '. Available: ' . $product['quantity']);
            }

            $lineBase = $price * $quantity;

            if ($discountAmount > 0) {
                $discount = min($discountAmount, $lineBase);
            } else {
                $discount = min(($lineBase * $discountPercent) / 100, $lineBase);
            }
            $lineTotal = $lineBase - $discount;

            $subtotal += $lineBase;
            $lineDiscountTotal += $discount;

            $saleItems[] = [
                'product' => $product,
                'is_custom' => 0,
                'quantity' => $quantity,
                'unit_price' => $price,
                'discount_amount' => $discount,
                'discount_percent' => $discountPercent,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($saleItems)) {
            throw new Exception('Add at least one valid item.');
        }

        $grandTotal = $subtotal - $lineDiscountTotal;

        $customerName  = trim((string) $this->input->post('customer_name'));
        $customerPhone = trim((string) $this->input->post('customer_phone'));

        $customerId = null;
        $customer = [
            'name' => !empty($customerName) ? $customerName : 'Walk-in Customer',
            'phone' => $customerPhone,
            'address' => trim((string) $this->input->post('customer_address'))
        ];

        if (!empty($customerName) || !empty($customerPhone)) {
            $customerId = $this->findOrCreateCustomer($adminId);
            if ($customerId) {
                $dbCustomer = $this->getCustomer($customerId, $adminId);
                if (!empty($dbCustomer)) $customer = $dbCustomer;
            }
        }

      $pickedDate = $this->input->post('sale_date') ?: date('Y-m-d');
$saleDate = $pickedDate . ' ' . date('H:i:s');
        $notes = htmlspecialchars(trim((string) $this->input->post('notes')), ENT_QUOTES, 'UTF-8');

        $this->db->where('id', $saleId)->update('sales', [
            'customer_id'     => $customerId,
            'sale_date'       => $saleDate,
            'subtotal'        => $subtotal,
            'discount_amount' => $lineDiscountTotal,
            'grand_total'     => $grandTotal,
            'notes'           => $notes,
        ]);

        $this->db->where('sale_id', $saleId)->delete('sale_items');

        foreach ($saleItems as $item) {
            $product = $item['product'];
            $isCustom = !empty($item['is_custom']);

            $this->db->insert('sale_items', [
                'sale_id'          => $saleId,
                'product_id'       => $isCustom ? 0 : $product['id'],
                'is_custom'        => $isCustom ? 1 : 0,
                'product_name'     => $product['name'],
                'product_image'    => $product['image'],
                'quantity'         => $item['quantity'],
                'unit_price'       => $item['unit_price'],
                'purchase_price'   => $isCustom ? 0 : (float) $product['purchase_price'],
                'discount_percent' => $item['discount_percent'],
                'discount_amount'  => $item['discount_amount'],
                'line_total'       => $item['line_total'],
                'created_on'       => date('Y-m-d H:i:s'),
            ]);

            if (!$isCustom) {
                $this->db
                    ->where('id', $product['id'])
                    ->where('admin_id', $adminId)
                    ->set('quantity', 'quantity - ' . (int) $item['quantity'], false)
                    ->update('product');
            }
        }

        if ($this->db->trans_status() === false) {
            throw new Exception('Update failed. Please try again.');
        }

        $this->db->trans_commit();

    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', $e->getMessage());
        redirect('sales/edit/' . $saleId);
        return;
    }

    $viewData = [
        'businessName'  => $this->admin['business_name'] ?? 'Bills Book',
        'invoiceNumber' => $sale['invoice_number'],
        'customer'      => $customer,
        'items'         => $saleItems,
        'totals'        => [
            'subtotal'            => $subtotal,
            'line_discount_total' => $lineDiscountTotal,
            'sale_discount'       => 0,
            'grand_total'         => $grandTotal,
            'sale_date'           => $saleDate,
            'notes'               => $notes,
        ],
        'saleId' => $saleId,
    ];

    $pdfUrl = null;
    try {
        $pdfUrl = $this->generateInvoicePdf($saleId, $viewData);
    } catch (Exception $e) {
        log_message('error', 'PDF generation failed: ' . $e->getMessage());
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $customer['phone'] ?? '');

    if (!empty($cleanPhone) && strlen($cleanPhone) >= 10 && $pdfUrl) {
        try {
            if ($this->isWhatsAppConnected($adminId)) {
                $this->sendWhatsAppInvoice(
                    '91' . $cleanPhone,
                    $pdfUrl,
                    $adminId,
                    $customer,
                    $sale['invoice_number'],
                    $grandTotal
                );
            }
        } catch (Exception $e) {
            log_message('error', 'WhatsApp send failed: ' . $e->getMessage());
        }
    }

    $this->session->set_flashdata('success', 'Sale updated successfully.');
    redirect('sales');
}


    /**
     * Delete Sale — Restore stock, remove items, remove sale, remove PDF
     */
    public function delete($saleId = 0)
    {
        $saleId  = (int) $saleId;
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        // Verify sale
        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (empty($sale)) {
            $this->session->set_flashdata('error', 'Sale not found.');
            redirect('sales');
            return;
        }

        // Get items to restore stock
        $items = $this->db
            ->where('sale_id', $saleId)
            ->get('sale_items')
            ->result_array();

        $this->db->trans_begin();

        // Restore stock for each item
        foreach ($items as $item) {
            $this->db
                ->where('id', $item['product_id'])
                ->where('admin_id', $adminId)
                ->set('quantity', 'quantity + ' . (int) $item['quantity'], false)
                ->update('product');
        }

        // Delete sale items
        $this->db->where('sale_id', $saleId)->delete('sale_items');

        // Delete sale
        $this->db->where('id', $saleId)->where('admin_id', $adminId)->delete('sales');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Delete failed.');
            redirect('sales');
            return;
        }

        $this->db->trans_commit();

        // Remove PDF file
        $pdfPath = FCPATH . 'uploads/invoices/invoice-' . $saleId . '.pdf';
        if (file_exists($pdfPath)) {
            @unlink($pdfPath);
        }

        $this->session->set_flashdata('success', 'Sale deleted and stock restored successfully.');
        redirect('sales');
    }

    private function buildBaseData(int $adminId): array
    {
        return [
            'userName' => $this->admin['user_name'] ?? ($this->admin['name'] ?? 'Admin'),
            'userEmail' => $this->admin['email'] ?? '',
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'role' => (!empty($this->admin['role']) && (string) $this->admin['role'] !== '1') ? (string) $this->admin['role'] : 'admin',
            'profileImage' => $this->admin['profile_image'] ?? '',
            'profileUrl' => base_url('profile'),
            'adminId' => $adminId
        ];
    }

    private function normalizeFilters(array $filters): array
    {
        $normalized = [
            'search'    => trim((string)($filters['search'] ?? '')),
            'date_from' => '',
            'date_to'   => ''
        ];

        if (!empty($filters['date_from'])) {
            $ts = strtotime(str_replace('/', '-', trim((string)$filters['date_from'])));
            if ($ts !== false) {
                $normalized['date_from'] = date('Y-m-d', $ts);
            }
        }

        if (!empty($filters['date_to'])) {
            $ts = strtotime(str_replace('/', '-', trim((string)$filters['date_to'])));
            if ($ts !== false) {
                $normalized['date_to'] = date('Y-m-d', $ts);
            }
        }

        return $normalized;
    }

    private function getSalesMetrics(int $adminId, array $filters = []): array
    {
        $this->db->select("
            COUNT(s.id) AS total_rows,
            COALESCE(SUM(s.grand_total), 0) AS total_sales,
            COALESCE(SUM(s.grand_total) - SUM(COALESCE(item_cost.total_cogs, 0)), 0) AS total_profit
        ", false);
        $this->db->from('sales s');
        $this->db->join('customers c', 'c.id = s.customer_id', 'left');
        $this->db->join("
            (
                SELECT 
                    si.sale_id,
                    SUM(COALESCE(NULLIF(si.purchase_price, 0), p.purchase_price, 0) * si.quantity) AS total_cogs
                FROM sale_items si
                LEFT JOIN product p ON p.id = si.product_id
                GROUP BY si.sale_id
            ) item_cost", "item_cost.sale_id = s.id", "left", false);
        $this->db->where('s.admin_id', $adminId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start()
                ->like('s.invoice_number', $search)
                ->or_like('c.name', $search)
                ->or_like('c.phone', $search)
                ->group_end();
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(s.sale_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(s.sale_date) <=', $filters['date_to']);
        }

        $row = $this->db->get()->row_array();

        return [
            'total_rows'   => (int)($row['total_rows'] ?? 0),
            'total_sales'  => (float)($row['total_sales'] ?? 0),
            'total_profit' => (float)($row['total_profit'] ?? 0)
        ];
    }

    private function getSales(int $adminId, array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $query = $this->db
            ->select('sales.*, customers.name AS customer_name, customers.phone AS customer_phone, customers.address AS customer_address')
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.admin_id', $adminId);

        // Filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
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
            ->order_by('sales.id', 'DESC') // ✅ FIX ORDER
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    public function ajax_list()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $rawFilters = [
            'search'    => trim((string) $this->input->get('search')),
            'date_from' => trim((string) $this->input->get('date_from')),
            'date_to'   => trim((string) $this->input->get('date_to'))
        ];
        $filters = $this->normalizeFilters($rawFilters);

        $page = max(1, (int)$this->input->get('page'));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $metrics = $this->getSalesMetrics($adminId, $filters);
        $totalRows = (int)$metrics['total_rows'];

        $sales = $this->getSales($adminId, $filters, $limit, $offset);

        echo json_encode([
            'sales'       => $sales,
            'totalPages'  => (int)ceil($totalRows / $limit),
            'currentPage' => $page,
            'perPage'     => $limit,
            'totalRows'   => $totalRows,
            'totalSales'  => (float)$metrics['total_sales'],
            'totalProfit' => (float)$metrics['total_profit']
        ]);
    }

    private function getSellableProducts(int $adminId): array
    {
        $products = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->order_by('name', 'ASC')
            ->get('product')
            ->result_array();

        foreach ($products as &$product) {
            $product['image_url'] = $this->resolveProductImage((string) ($product['image'] ?? ''));
            $product['quantity'] = (int) ($product['quantity'] ?? 0);
            $product['price'] = (float) ($product['price'] ?? 0);
        }
        unset($product);

        return $products;
    }

    private function getOwnedProduct(int $productId, int $adminId): ?array
    {
        $row = $this->db
            ->where('id', $productId)
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->get('product')
            ->row_array();

        return $row ?: null;
    }

    private function getCustomer(int $customerId, int $adminId): ?array
    {
        $row = $this->db
            ->where('id', $customerId)
            ->where('admin_id', $adminId)
            ->get('customers')
            ->row_array();

        return $row ?: null;
    }

    private function findOrCreateCustomer($adminId)
    {
        $name = trim((string)$this->input->post('customer_name'));
        $phone = trim((string)$this->input->post('customer_phone'));
        $address = trim((string)$this->input->post('customer_address'));

        // If both empty, return null (walk-in sale)
        if (empty($name) && empty($phone)) {
            return null;
        }

        // Try to find by phone first
        if (!empty($phone)) {
            $existing = $this->db
                ->where('admin_id', $adminId)
                ->where('phone', $phone)
                ->get('customers')
                ->row_array();

            if (!empty($existing)) {
                // Update name/address if provided and different
                if (!empty($name) || !empty($address)) {
                    $this->db
                        ->where('id', $existing['id'])
                        ->where('admin_id', $adminId)
                        ->update('customers', [
                            'name' => !empty($name) ? $name : $existing['name'],
                            'address' => !empty($address) ? $address : $existing['address']
                        ]);
                }
                return (int)$existing['id'];
            }
        }

        // Create new customer
        $this->db->insert('customers', [
            'admin_id' => $adminId,
            'name' => !empty($name) ? $name : 'Walk-in Customer',
            'phone' => $phone,
            'address' => $address,
            'created_on' => date('Y-m-d H:i:s')
        ]);

        return (int)$this->db->insert_id();
    }
    private function hasValidItems($productIds, $quantities): bool
    {
        if (!is_array($productIds) || !is_array($quantities)) {
            return false;
        }

        foreach ($productIds as $index => $productId) {
            if ((int) $productId > 0 && (int) ($quantities[$index] ?? 0) > 0) {
                return true;
            }
        }

        return false;
    }
    private function generateInvoiceNumber(int $adminId): string
    {
        $date = date('Ymd');

        // Get last invoice for today
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

    private function resolveProductImage(string $image): string
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

    public function valid_discount($amount): bool
    {
        if ($this->normalizePercentage((string) $amount) === null) {
            $this->form_validation->set_message('valid_discount', 'The {field} field must be between 0 and 100.');
            return false;
        }

        return true;
    }

    private function normalizePercentage(string $amount): ?float
    {
        $cleaned = trim(str_replace(['%', ' '], '', $amount));
        if ($cleaned === '') {
            return 0.0;
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $cleaned)) {
            return null;
        }

        $value = (float) $cleaned;
        if ($value < 0 || $value > 100) {
            return null;
        }

        return $value;
    }

    // private function normalizeAmount(string $amount): ?float
    // {
    //     $cleaned = trim(str_replace([',', ' ', 'Rs.', 'RS.', 'rs.', 'â‚¹'], '', $amount));
    //     if ($cleaned === '') {
    //         return 0.0;
    //     }

    //     if (!preg_match('/^\d+(\.\d{1,2})?$/', $cleaned)) {
    //         return null;
    //     }

    //     return (float) $cleaned;
    // }

    public function download($saleId)
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $sale = $this->db
            ->where('id', $saleId)
            ->where('admin_id', $adminId)
            ->get('sales')
            ->row_array();

        if (!$sale) {
            show_404();
            return;
        }

        $items = $this->db->where('sale_id', $saleId)->get('sale_items')->result_array();
        $customer = $this->getCustomer($sale['customer_id'], $adminId);

        $viewData = [
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'invoiceNumber' => $sale['invoice_number'],
            'customer' => $customer,
            'items' => $items,
            'totals' => [
                'subtotal' => $sale['subtotal'],
                'line_discount_total' => 0,
                'sale_discount' => 0,
                'grand_total' => $sale['grand_total'],
                'sale_date' => $sale['sale_date'],
                'notes' => $sale['notes']
            ],
            'saleId' => $saleId
        ];

        $html = $this->load->view('invoice', $viewData, true);


        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("Invoice-" . $sale['invoice_number'] . ".pdf", [
            "Attachment" => true
        ]);
    }

    private function buildWhatsappUrl(array $sale): string
    {
        $phone = preg_replace('/\D+/', '', (string) ($sale['customer_phone'] ?? ''));

        if ($phone == '') {
            return '#';
        }

        if (strlen($phone) == 10) {
            $phone = '91' . $phone;
        }

        $customerName = trim((string) ($sale['customer_name'] ?? 'Customer'));
        $invoiceNumber = (string) ($sale['invoice_number'] ?? '');
        $invoiceAmount = number_format((float) ($sale['grand_total'] ?? 0), 2);

        // ✅ FIXED LINE
        $invoiceUrl = base_url('sales/download/' . (int)($sale['id'] ?? 0));

        $message =
            "Hi {$customerName},\n\n" .
            "Your invoice {$invoiceNumber} is ready.\n" .
            "Amount: Rs. {$invoiceAmount}\n\n" .
            "View Invoice:\n{$invoiceUrl}\n\n" .
            "Thank you for your business.";

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    public function export()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        if ($adminId <= 0) {
            show_error('Unauthorized access', 403);
            return;
        }

        $rawFilters = [
            'search'    => trim((string)$this->input->get('search')),
            'date_from' => trim((string)$this->input->get('date_from')),
            'date_to'   => trim((string)$this->input->get('date_to'))
        ];
        $filters = $this->normalizeFilters($rawFilters);
        $search    = $filters['search'];
        $date_from = $filters['date_from'];
        $date_to   = $filters['date_to'];
        $businessName = $this->admin['business_name'] ?? 'My Business';

        // ─── FETCH SALES WITH ITEMS ───
        $this->db->select("
        s.id as sale_id,
        s.invoice_number,
        s.sale_date,
        s.subtotal,
        s.discount_amount as sale_discount,
        s.grand_total,
        s.notes,
        COALESCE(c.name, 'Walk-in Customer') as customer_name,
        COALESCE(c.phone, '-') as customer_phone,
        COALESCE(c.address, '-') as customer_address,
        si.product_name,
        si.quantity,
        si.unit_price,
        COALESCE(NULLIF(si.purchase_price, 0), p.purchase_price, 0) as purchase_price,
        si.discount_amount as item_discount,
        si.discount_percent,
        si.line_total
    ");

        $this->db->from('sales s');
        $this->db->join('customers c', 'c.id = s.customer_id', 'left');
        $this->db->join('sale_items si', 'si.sale_id = s.id', 'inner');
        $this->db->join('product p', 'p.id = si.product_id', 'left');
        $this->db->where('s.admin_id', $adminId);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('s.invoice_number', $search)
                ->or_like('c.name', $search)
                ->or_like('c.phone', $search)
                ->or_like('si.product_name', $search)
                ->group_end();
        }

        if (!empty($date_from)) {
            $this->db->where('DATE(s.sale_date) >=', $date_from);
        }

        if (!empty($date_to)) {
            $this->db->where('DATE(s.sale_date) <=', $date_to);
        }

        $this->db->order_by('s.id', 'DESC');
        $this->db->order_by('si.id', 'ASC');

        $items = $this->db->get()->result_array();

        // ─── CALCULATE TOTALS ───
        $totalSales = 0;
        $totalPurchaseCost = 0;
        $totalProfit = 0;
        $totalDiscount = 0;
        $totalQty = 0;
        $invoiceCount = [];

        foreach ($items as $item) {
            $purchaseCost = (float)$item['purchase_price'] * (int)$item['quantity'];
            $profit = (float)$item['line_total'] - $purchaseCost;

            $totalSales += (float)$item['line_total'];
            $totalPurchaseCost += $purchaseCost;
            $totalProfit += $profit;
            $totalDiscount += (float)$item['item_discount'];
            $totalQty += (int)$item['quantity'];
            $invoiceCount[$item['invoice_number']] = true;
        }

        $totalInvoices = count($invoiceCount);
        $profitPercent = $totalSales > 0 ? ($totalProfit / $totalSales) * 100 : 0;

        // ─── DATE RANGE LABEL ───
        $dateLabel = 'All Time';
        if (!empty($date_from) && !empty($date_to)) {
            $dateLabel = date('d M Y', strtotime($date_from)) . ' to ' . date('d M Y', strtotime($date_to));
        } elseif (!empty($date_from)) {
            $dateLabel = 'From ' . date('d M Y', strtotime($date_from));
        } elseif (!empty($date_to)) {
            $dateLabel = 'Up to ' . date('d M Y', strtotime($date_to));
        }

        // ─── HEADERS ───
        $filename = 'Sales_Report_' . date('Y-m-d_His') . '.xls';
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // ─── OUTPUT HTML EXCEL ───
        echo '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Calibri, Arial, sans-serif;
        font-size: 11pt;
        color: #1e293b;
    }

    .header-section {
        background: #1e40af;
        color: #ffffff;
        padding: 20px 24px;
        border-radius: 0;
    }

    .header-section h1 {
        font-size: 22pt;
        margin: 0 0 4px 0;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .header-section p {
        font-size: 10pt;
        margin: 2px 0;
        color: #bfdbfe;
    }

    /* Summary Cards */
    .summary-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 14px;
    }

    .summary-card {
        padding: 14px 18px;
        text-align: center;
        border: 2px solid #e2e8f0;
        width: 16%;
    }

    .summary-label {
        font-size: 8.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 14pt;
        font-weight: 800;
    }

    .card-sales {
        background: #eff6ff;
        border-color: #93c5fd !important;
    }

    .card-sales .summary-value { color: #1d4ed8; }

    .card-purchase {
        background: #fefce8;
        border-color: #fde047 !important;
    }

    .card-purchase .summary-value { color: #a16207; }

    .card-profit-positive {
        background: #f0fdf4;
        border-color: #86efac !important;
    }

    .card-profit-positive .summary-value { color: #15803d; }

    .card-profit-negative {
        background: #fef2f2;
        border-color: #fca5a5 !important;
    }

    .card-profit-negative .summary-value { color: #dc2626; }

    .card-discount {
        background: #fdf4ff;
        border-color: #e879f9 !important;
    }

    .card-discount .summary-value { color: #a21caf; }

    .card-invoices {
        background: #f0f9ff;
        border-color: #7dd3fc !important;
    }

    .card-invoices .summary-value { color: #0369a1; }

    .card-qty {
        background: #fff7ed;
        border-color: #fdba74 !important;
    }

    .card-qty .summary-value { color: #c2410c; }

    /* Data Table */
    .data-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 18px;
        font-size: 10pt;
    }

    .data-table thead th {
        background: #1e293b;
        color: #ffffff;
        padding: 10px 8px;
        text-align: left;
        font-weight: 700;
        font-size: 8.5pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #334155;
    }

    .data-table thead th.number-col {
        text-align: right;
    }

    .data-table tbody td {
        padding: 8px;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .data-table tbody td.number-col {
        text-align: right;
        font-family: Consolas, monospace;
    }

    .row-even { background: #ffffff; }
    .row-odd { background: #f8fafc; }

    .profit-positive {
        color: #15803d;
        font-weight: 700;
        background: #f0fdf4;
    }

    .profit-negative {
        color: #dc2626;
        font-weight: 700;
        background: #fef2f2;
    }

    .profit-zero {
        color: #64748b;
        font-weight: 600;
    }

    .discount-highlight {
        color: #a21caf;
        font-weight: 600;
    }

    .walkin-customer {
        color: #64748b;
        font-style: italic;
    }

    /* Invoice group separator */
    .invoice-separator td {
        border-top: 3px solid #cbd5e1 !important;
    }

    /* Totals Row */
    .totals-row td {
        background: #1e293b !important;
        color: #ffffff !important;
        font-weight: 800;
        font-size: 10.5pt;
        padding: 10px 8px;
        border: 1px solid #334155;
    }

    .totals-row .profit-total-positive {
        color: #4ade80 !important;
        font-weight: 800;
    }

    .totals-row .profit-total-negative {
        color: #f87171 !important;
        font-weight: 800;
    }

    .totals-row .discount-total {
        color: #e879f9 !important;
        font-weight: 800;
    }

    /* Footer */
    .footer-section {
        margin-top: 14px;
        font-size: 8.5pt;
        color: #94a3b8;
        padding: 8px 0;
        border-top: 1px solid #e2e8f0;
    }
</style>
</head>
<body>';

        // ─── HEADER ───
        echo '<table width="100%" style="border-collapse:collapse">
    <tr>
        <td class="header-section" colspan="14">
            <h1>📊 ' . htmlspecialchars($businessName) . '</h1>
            <p><b>Sales Report</b> &nbsp;|&nbsp; Period: ' . $dateLabel . '</p>
            <p>Generated: ' . date('d M Y, h:i A') . (!empty($search) ? ' &nbsp;|&nbsp; Search: "' . htmlspecialchars($search) . '"' : '') . '</p>
        </td>
    </tr>
</table>';

        // ─── SUMMARY CARDS ───
        $profitCardClass = $totalProfit >= 0 ? 'card-profit-positive' : 'card-profit-negative';
        $profitSign = $totalProfit >= 0 ? '' : '-';
        $profitArrow = $totalProfit >= 0 ? '↑' : '↓';

        echo '<table class="summary-table">
    <tr>
        <td class="summary-card card-sales">
            <div class="summary-label">💰 Total Sales</div>
            <div class="summary-value">₹ ' . number_format(abs($totalSales), 2) . '</div>
        </td>
        <td class="summary-card card-purchase">
            <div class="summary-label">📦 Purchase Cost</div>
            <div class="summary-value">₹ ' . number_format($totalPurchaseCost, 2) . '</div>
        </td>
        <td class="summary-card ' . $profitCardClass . '">
            <div class="summary-label">' . $profitArrow . ' Net Profit</div>
            <div class="summary-value">' . $profitSign . '₹ ' . number_format(abs($totalProfit), 2) . '</div>
        </td>
        <td class="summary-card card-discount">
            <div class="summary-label">🏷️ Total Discount</div>
            <div class="summary-value">₹ ' . number_format($totalDiscount, 2) . '</div>
        </td>
        <td class="summary-card card-invoices">
            <div class="summary-label">📄 Total Invoices</div>
            <div class="summary-value">' . $totalInvoices . '</div>
        </td>
        <td class="summary-card card-qty">
            <div class="summary-label">📊 Items Sold</div>
            <div class="summary-value">' . $totalQty . '</div>
        </td>
    </tr>
</table>';

        // ─── PROFIT MARGIN BAR ───
        echo '<table width="100%" style="border-collapse:collapse;margin-top:10px">
    <tr>
        <td style="padding:8px 12px;background:#f8fafc;border:1px solid #e2e8f0;font-size:9pt">
            <b>Profit Margin:</b> &nbsp;
            <span style="color:' . ($profitPercent >= 0 ? '#15803d' : '#dc2626') . ';font-weight:800;font-size:11pt">'
            . number_format(abs($profitPercent), 1) . '%</span>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <b>Avg per Invoice:</b> &nbsp;
            <span style="color:#1d4ed8;font-weight:700">₹ ' . ($totalInvoices > 0 ? number_format($totalSales / $totalInvoices, 2) : '0.00') . '</span>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <b>Avg Profit per Invoice:</b> &nbsp;
            <span style="color:' . ($totalProfit >= 0 ? '#15803d' : '#dc2626') . ';font-weight:700">₹ ' . ($totalInvoices > 0 ? number_format($totalProfit / $totalInvoices, 2) : '0.00') . '</span>
        </td>
    </tr>
</table>';

        // ─── DATA TABLE ───
        echo '<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Product</th>
            <th class="number-col">Qty</th>
            <th class="number-col">Unit Price</th>
            <th class="number-col">Purchase Price</th>
            <th class="number-col">Disc (₹)</th>
            <th class="number-col">Disc (%)</th>
            <th class="number-col">Line Total</th>
            <th class="number-col">Cost Total</th>
            <th class="number-col">Profit</th>
        </tr>
    </thead>
    <tbody>';

        $srNo = 1;
        $prevInvoice = '';

        foreach ($items as $item) {
            $purchaseCost = (float)$item['purchase_price'] * (int)$item['quantity'];
            $profit = (float)$item['line_total'] - $purchaseCost;
            $rowClass = ($srNo % 2 === 0) ? 'row-even' : 'row-odd';
            $isNewInvoice = ($item['invoice_number'] !== $prevInvoice);
            $separatorClass = ($isNewInvoice && $srNo > 1) ? 'invoice-separator' : '';

            // Profit cell styling
            if ($profit > 0) {
                $profitClass = 'profit-positive';
                $profitDisplay = '₹ ' . number_format($profit, 2);
            } elseif ($profit < 0) {
                $profitClass = 'profit-negative';
                $profitDisplay = '-₹ ' . number_format(abs($profit), 2);
            } else {
                $profitClass = 'profit-zero';
                $profitDisplay = '₹ 0.00';
            }

            // Discount styling
            $discountDisplay = (float)$item['item_discount'] > 0
                ? '<span class="discount-highlight">₹ ' . number_format($item['item_discount'], 2) . '</span>'
                : '₹ 0.00';

            $discountPercentDisplay = (float)$item['discount_percent'] > 0
                ? '<span class="discount-highlight">' . number_format($item['discount_percent'], 2) . '%</span>'
                : '0%';

            // Customer styling
            $customerDisplay = ($item['customer_name'] === 'Walk-in Customer')
                ? '<span class="walkin-customer">' . $item['customer_name'] . '</span>'
                : htmlspecialchars($item['customer_name']);

            echo '<tr class="' . $rowClass . ' ' . $separatorClass . '">
            <td>' . $srNo++ . '</td>
            <td><b>' . $item['invoice_number'] . '</b></td>
            <td>' . date('d-M-Y h:i A', strtotime($item['sale_date'])) . '</td>
            <td>' . $customerDisplay . '</td>
            <td>' . $item['customer_phone'] . '</td>
            <td>' . htmlspecialchars($item['product_name']) . '</td>
            <td class="number-col">' . $item['quantity'] . '</td>
            <td class="number-col">₹ ' . number_format($item['unit_price'], 2) . '</td>
            <td class="number-col">₹ ' . number_format($item['purchase_price'], 2) . '</td>
            <td class="number-col">' . $discountDisplay . '</td>
            <td class="number-col">' . $discountPercentDisplay . '</td>
            <td class="number-col"><b>₹ ' . number_format($item['line_total'], 2) . '</b></td>
            <td class="number-col">₹ ' . number_format($purchaseCost, 2) . '</td>
            <td class="number-col ' . $profitClass . '">' . $profitDisplay . '</td>
        </tr>';

            $prevInvoice = $item['invoice_number'];
        }

        // ─── TOTALS ROW ───
        $profitTotalClass = $totalProfit >= 0 ? 'profit-total-positive' : 'profit-total-negative';

        echo '<tr class="totals-row">
        <td colspan="6" style="text-align:right"><b>GRAND TOTALS →</b></td>
        <td class="number-col"><b>' . $totalQty . '</b></td>
        <td class="number-col"></td>
        <td class="number-col"></td>
        <td class="number-col discount-total"><b>₹ ' . number_format($totalDiscount, 2) . '</b></td>
        <td class="number-col"></td>
        <td class="number-col"><b>₹ ' . number_format($totalSales, 2) . '</b></td>
        <td class="number-col"><b>₹ ' . number_format($totalPurchaseCost, 2) . '</b></td>
        <td class="number-col ' . $profitTotalClass . '"><b>' . ($totalProfit >= 0 ? '' : '-') . '₹ ' . number_format(abs($totalProfit), 2) . '</b></td>
    </tr>';

        echo '</tbody></table>';

        // ─── FOOTER ───
        echo '<table width="100%" style="border-collapse:collapse">
    <tr>
        <td class="footer-section">
            Report generated by <b>' . htmlspecialchars($businessName) . '</b> 
            &nbsp;|&nbsp; Date: ' . date('d M Y, h:i:s A') . '
            &nbsp;|&nbsp; Total Records: ' . count($items) . '
            &nbsp;|&nbsp; Powered by Bills Book
        </td>
    </tr>
</table>';

        echo '</body></html>';
        exit;
    }
}
