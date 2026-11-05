<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function index()
    {
        $admin        = $this->session->userdata('admin');
        $adminId      = (int) ($admin['user_id'] ?? 0);
        $profileImage = $admin['profile_image'] ?? '';
        $businessName = trim((string) ($admin['business_name'] ?? ''));

        // ── Always-on data (not affected by filter) ─────────────────────────
        $productCount = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->count_all_results('product');

        $lowStockProducts = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->where('quantity <=', 5)
            ->order_by('quantity', 'ASC')
            ->order_by('id', 'DESC')
            ->get('product')
            ->result_array();

        $recentSales = $this->db
            ->select('sales.invoice_number, sales.grand_total, sales.sale_date, customers.name AS customer_name')
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.admin_id', $adminId)
            ->order_by('sales.id', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();

        // ── Initial SSR snapshot — default "Today" (JS will also load this on page-ready) ──
        $initial = $this->buildDashboardPayload($adminId, 'day', '', 0, '');

        // ── Profile image URL ────────────────────────────────────────────────
        if (!empty($profileImage) && !preg_match('#^https?://#i', $profileImage)) {
            $profileImage = (strpos($profileImage, 'uploads/') === 0 || strpos($profileImage, 'assets/') === 0)
                ? base_url($profileImage)
                : base_url('uploads/profile/' . ltrim($profileImage, '/'));
        }

        $data['userName']     = $admin['user_name'] ?? ($admin['name'] ?? 'Admin');
        $data['userEmail']    = $admin['email'] ?? '';
        $data['businessName'] = $businessName !== '' ? $businessName : 'Bills Book';
        $data['role']         = (!empty($admin['role']) && (string) $admin['role'] !== '1')
            ? (string) $admin['role'] : 'admin';
        $data['profileImage'] = !empty($profileImage)
            ? $profileImage
            : base_url('assets/images/Default.jpg');
        $data['profileUrl']   = base_url('profile');
        $data['pageTitle']    = 'Dashboard';

        $data['productCount']          = $productCount;
        $data['lowStockProducts']      = $lowStockProducts;
        $data['lowStockCount']         = count($lowStockProducts);
        $data['recentSales']           = $recentSales;
        $data['privacyModeEnabled']    = (bool) ($admin['privacy_mode'] ?? false);

        // Initial filtered snapshot (default Today) — view uses these for first paint
        $data['customerCount']         = $initial['customerCount'];
        $data['totalSales']            = $initial['totalSales'];
        $data['totalProfit']           = $initial['totalProfit'];
        $data['totalPurchase']         = $initial['totalPurchase'];
        $data['filterLabel']           = $initial['filterLabel'];

        $this->load->view('header', $data);
        $this->load->view('dashboard_view', $data);
        $this->load->view('footer');
    }

    /**
     * AJAX endpoint — single source of truth for ALL filtered dashboard data
     * (stat cards + chart). Called on page load AND on every filter change.
     */
    public function getDashboardData()
    {
        $adminId     = (int) ($this->session->userdata('admin')['user_id'] ?? 0);
        $filter      = $this->input->post('filter') ?: 'day';
        $monthPicker = trim((string) ($this->input->post('month_picker') ?? ''));
        $yearPicker  = (int) ($this->input->post('year_picker') ?? 0);
        $datePicker  = trim((string) ($this->input->post('date_picker') ?? ''));

        $payload = $this->buildDashboardPayload($adminId, $filter, $monthPicker, $yearPicker, $datePicker);

        echo json_encode($payload);
    }

    /**
     * Builds the complete filtered dashboard payload (stat cards + chart data).
     * Shared by index() (initial SSR) and getDashboardData() (AJAX refresh).
     */
  private function buildDashboardPayload(int $adminId, string $filter, string $monthPicker, int $yearPicker, string $datePicker): array
{
    if (!in_array($filter, ['day', 'week', 'month', 'year'], true)) {
        $filter = 'day';
    }

    // Priority: date > month > year > pill
    if (!empty($datePicker) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $datePicker)) {
        $activeMode = 'custom_date';
    } elseif (!empty($monthPicker) && preg_match('/^\d{4}-\d{2}$/', $monthPicker)) {
        $activeMode = 'custom_month';
    } elseif ($yearPicker >= 2000 && $yearPicker <= 2100) {
        $activeMode = 'custom_year';
    } else {
        $activeMode = $filter;
    }

    // ── Customer count ───────────────────────────────────────────────
    $custQ = $this->db->where('admin_id', $adminId)->where('isActive', 1);
    $this->applyDateFilter($custQ, $activeMode, 'created_on', $monthPicker, $yearPicker, $datePicker);
    $customerCount = $custQ->count_all_results('customers');

    // ── Total Sales ───────────────────────────────────────────────────
    $salesQ = $this->db
        ->select('SUM(grand_total) AS total_sales', false)
        ->from('sales')
        ->where('admin_id', $adminId);
    $this->applyDateFilter($salesQ, $activeMode, 'sale_date', $monthPicker, $yearPicker, $datePicker);
    $salesRow   = $salesQ->get()->row_array();
    $totalSales = (float) ($salesRow['total_sales'] ?? 0);

    // ── Total Profit ─────────────────────────────────────────────────
    $profitQ = $this->db
        ->select('SUM(si.line_total - (si.purchase_price * si.quantity)) AS profit', false)
        ->from('sale_items si')
        ->join('sales s', 's.id = si.sale_id')
        ->where('s.admin_id', $adminId);
    $this->applyDateFilter($profitQ, $activeMode, 's.sale_date', $monthPicker, $yearPicker, $datePicker);
    $profitRow   = $profitQ->get()->row_array();
    $totalProfit = (float) ($profitRow['profit'] ?? 0);

    // ── Total Purchase ───────────────────────────────────────────────
    $purchQ = $this->db
        ->select('SUM(quantity * purchase_price) AS total_purchase', false)
        ->where('admin_id', $adminId);
    $this->applyDateFilter($purchQ, $activeMode, 'purchase_date', $monthPicker, $yearPicker, $datePicker);
    $purchRow      = $purchQ->get('stock_purchases')->row_array();
    $totalPurchase = (float) ($purchRow['total_purchase'] ?? 0);

    // ── Filter label ──────────────────────────────────────────────────
    $filterLabels = [
        'day'   => 'Today',
        'week'  => 'This Week',
        'month' => 'This Month',
        'year'  => 'This Year',
    ];

    if ($activeMode === 'custom_date' && !empty($datePicker)) {
        $filterLabel = date('d M Y', strtotime($datePicker));
    } elseif ($activeMode === 'custom_month' && !empty($monthPicker)) {
        $filterLabel = date('F Y', strtotime($monthPicker . '-01'));
    } elseif ($activeMode === 'custom_year' && $yearPicker) {
        $filterLabel = 'Year ' . $yearPicker;
    } else {
        $filterLabel = $filterLabels[$filter] ?? 'Today';
    }

    // ── Chart data ────────────────────────────────────────────────────
    $isSingleDay = in_array($activeMode, ['day', 'custom_date'], true);

    if ($isSingleDay) {
        // Single-day view → break down by HOUR so the chart has enough
        // points to draw an actual line instead of a single dot.
        $targetDate = ($activeMode === 'custom_date' && !empty($datePicker))
            ? $datePicker
            : date('Y-m-d');

        $sRows = $this->db
            ->select('HOUR(sale_date) AS hr, SUM(grand_total) AS total', false)
            ->from('sales')
            ->where('admin_id', $adminId)
            ->where('DATE(sale_date)', $targetDate)
            ->group_by('HOUR(sale_date)')
            ->order_by('hr', 'ASC')
            ->get()
            ->result_array();

        $pRows = $this->db
            ->select('HOUR(s.sale_date) AS hr, SUM(si.line_total - (si.purchase_price * si.quantity)) AS profit', false)
            ->from('sale_items si')
            ->join('sales s', 's.id = si.sale_id')
            ->where('s.admin_id', $adminId)
            ->where('DATE(s.sale_date)', $targetDate)
            ->group_by('HOUR(s.sale_date)')
            ->order_by('hr', 'ASC')
            ->get()
            ->result_array();

        $salesByHour  = [];
        foreach ($sRows as $r) { $salesByHour[(int) $r['hr']] = (float) $r['total']; }
        $profitByHour = [];
        foreach ($pRows as $r) { $profitByHour[(int) $r['hr']] = (float) $r['profit']; }

        $labels = $chartSales = $chartProfit = [];
        for ($h = 0; $h <= 23; $h++) {
            $labels[]      = date('g A', mktime($h, 0, 0));
            $chartSales[]  = $salesByHour[$h] ?? 0.0;
            $chartProfit[] = $profitByHour[$h] ?? 0.0;
        }
    } else {
        // Week / Month / Year / custom_month / custom_year → daily grouping
        $sQ = $this->db->from('sales')->where('admin_id', $adminId);
        $this->applyDateFilter($sQ, $activeMode, 'sale_date', $monthPicker, $yearPicker, $datePicker);
        $salesRows = $sQ
            ->select('DATE(sale_date) AS date, SUM(grand_total) AS total')
            ->group_by('DATE(sale_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result_array();

        $pQ = $this->db
            ->from('sale_items si')
            ->join('sales s', 's.id = si.sale_id')
            ->where('s.admin_id', $adminId);
        $this->applyDateFilter($pQ, $activeMode, 's.sale_date', $monthPicker, $yearPicker, $datePicker);
        $profitRows = $pQ
            ->select('DATE(s.sale_date) AS date, SUM(si.line_total - (si.purchase_price * si.quantity)) AS profit')
            ->group_by('DATE(s.sale_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result_array();

        $profitByDate = [];
        foreach ($profitRows as $row) {
            $profitByDate[$row['date']] = (float) $row['profit'];
        }

        $labels = $chartSales = $chartProfit = [];
        foreach ($salesRows as $row) {
            $labels[]      = date('d M', strtotime($row['date']));
            $chartSales[]  = (float) $row['total'];
            $chartProfit[] = $profitByDate[$row['date']] ?? 0.0;
        }
    }

    return [
        'customerCount' => $customerCount,
        'totalSales'    => $totalSales,
        'totalProfit'   => $totalProfit,
        'totalPurchase' => $totalPurchase,
        'filterLabel'   => $filterLabel,
        'chart'         => [
            'labels'       => $labels,
            'values'       => $chartSales,
            'profit'       => $chartProfit,
            'total_sales'  => array_sum($chartSales),
            'total_profit' => array_sum($chartProfit),
        ],
    ];
}

    // ── save_privacy_preference ─────────────────────────────────────────────
    public function save_privacy_preference()
    {
        if ($this->input->is_ajax_request()) {
            $postData     = json_decode(file_get_contents('php://input'), true);
            $privacyMode  = $postData['privacy_mode'] ?? false;
            $privacyValue = $privacyMode ? 1 : 0;

            $admin   = $this->session->userdata('admin');
            $adminId = $admin['user_id'] ?? 0; // ⚠ was $admin['id'] — fixed to match rest of controller

            if ($adminId > 0) {
                $columns = $this->db->list_fields('admin');
                if (in_array('privacy_mode', $columns)) {
                    $this->db->where('id', $adminId)
                        ->update('admin', ['privacy_mode' => $privacyValue]);
                    $admin['privacy_mode'] = $privacyValue;
                    $this->session->set_userdata('admin', $admin);
                    echo json_encode(['success' => true, 'message' => 'Preference saved to database']);
                } else {
                    $admin['privacy_mode'] = $privacyValue;
                    $this->session->set_userdata('admin', $admin);
                    echo json_encode(['success' => true, 'message' => 'Session updated only (no DB column)']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Admin ID not found']);
            }
            return;
        }
        show_404();
    }

    // ══════════════════════════════════════════════════════════════════════
    // PRIVATE HELPER
    // ══════════════════════════════════════════════════════════════════════

    private function applyDateFilter($q, string $mode, string $col, string $monthPicker = '', int $yearPicker = 0, string $datePicker = ''): void
    {
        switch ($mode) {

            case 'day':
                $q->where("DATE($col)", date('Y-m-d'));
                break;

            case 'week':
                $q->where("DATE($col) >=", date('Y-m-d', strtotime('-6 days')));
                break;

            case 'month':
                $q->where("MONTH($col)", date('m'));
                $q->where("YEAR($col)",  date('Y'));
                break;

            case 'year':
                $q->where("YEAR($col)", date('Y'));
                break;

            case 'custom_month':
                if (!empty($monthPicker) && preg_match('/^(\d{4})-(\d{2})$/', $monthPicker, $m)) {
                    $q->where("MONTH($col)", (int) $m[2]);
                    $q->where("YEAR($col)",  (int) $m[1]);
                }
                break;

            case 'custom_year':
                if ($yearPicker >= 2000 && $yearPicker <= 2100) {
                    $q->where("YEAR($col)", $yearPicker);
                }
                break;

            case 'custom_date':
                if (!empty($datePicker) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $datePicker)) {
                    $q->where("DATE($col)", $datePicker);
                }
                break;
        }
    }
}