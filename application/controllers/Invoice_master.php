<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'core/My_Controller.php');
require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Invoice_master extends My_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->admin = $this->session->userdata('admin');
        if (!$this->admin) {
            redirect('login');
        }
    }

    // ─────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────
    public function index()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

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

        // Logo from session profile_image
        $logoImage = $this->admin['profile_image'] ?? null;
        $logoUrl   = !empty($logoImage)
            ? base_url('uploads/profile/' . $logoImage)
            : null;

        // FIX: Build base data first, then add extra keys — no overwrite issue
        $data = $this->buildBaseData($adminId);
        $data['pageTitle']    = 'Invoice Templates';
        $data['settings']     = $settings;
        $data['logoUrl']      = $logoUrl;
        $data['logoImage']    = $logoImage;

        // FIX: Pass adminData fields explicitly so the view uses DB values, not session
        $data['adminAddress']   = $adminData['address']   ?? '';
        $data['adminMobile']    = $adminData['mobile']    ?? $adminData['phone'] ?? '';
        $data['adminInstagram'] = $adminData['instagram'] ?? '';
        $data['adminFacebook']  = $adminData['facebook']  ?? '';
        $data['adminBusiness']  = $adminData['business_name'] ?? ($this->admin['business_name'] ?? 'Your Business');

        $this->load->view('header', $data);
        $this->load->view('invoice_templates', $data);
        $this->load->view('footer', $data);
    }

   
    // ─────────────────────────────────────────────
    // GENERATE PDF (private helper)
    // ─────────────────────────────────────────────
   

    // ─────────────────────────────────────────────
    // SAVE INVOICE SETTINGS
    // ─────────────────────────────────────────────
    public function save_invoice_settings()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $templateId   = (int) $this->input->post('template_id');
        $primaryColor = $this->input->post('primary_color');
        $accentColor  = $this->input->post('accent_color');

        if ($templateId < 1 || $templateId > 4) {
            $templateId = 1;
        }
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $primaryColor)) {
            $primaryColor = '#0f172a';
        }
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $accentColor)) {
            $accentColor = '#2563eb';
        }

        $exists = $this->db
            ->where('admin_id', $adminId)
            ->count_all_results('invoice_settings');

        $settingsData = [
            'admin_id'      => $adminId,
            'template_id'   => $templateId,
            'primary_color' => $primaryColor,
            'accent_color'  => $accentColor,
        ];

        if ($exists > 0) {
            $this->db->where('admin_id', $adminId)->update('invoice_settings', $settingsData);
        } else {
            $this->db->insert('invoice_settings', $settingsData);
        }

        $this->session->set_flashdata('success', 'Invoice template updated successfully.');
        redirect('invoice_master');
    }

    // ─────────────────────────────────────────────
    // UPLOAD SIGNATURE
    // ─────────────────────────────────────────────
    public function upload_signature()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $config['upload_path']   = FCPATH . 'uploads/signatures/';
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['max_size']      = 512;
        $config['file_name']     = 'sig_' . $adminId . '_' . time();

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('signature_image')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('invoice_master');
            return;
        }

        $uploadData = $this->upload->data();
        $fileName   = $uploadData['file_name'];

        $old = $this->db
            ->select('signature_image')
            ->where('admin_id', $adminId)
            ->get('invoice_settings')
            ->row_array();

        if (!empty($old['signature_image'])) {
            $oldPath = FCPATH . 'uploads/signatures/' . $old['signature_image'];
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $exists = $this->db
            ->where('admin_id', $adminId)
            ->count_all_results('invoice_settings');

        if ($exists > 0) {
            $this->db->where('admin_id', $adminId)->update('invoice_settings', [
                'signature_image' => $fileName,
            ]);
        } else {
            $this->db->insert('invoice_settings', [
                'admin_id'        => $adminId,
                'template_id'     => 1,
                'primary_color'   => '#0f172a',
                'accent_color'    => '#2563eb',
                'signature_image' => $fileName,
            ]);
        }

        $this->session->set_flashdata('success', 'Signature uploaded successfully.');
        redirect('invoice_master');
    }

    // ─────────────────────────────────────────────
    // REMOVE SIGNATURE
    // ─────────────────────────────────────────────
    public function remove_signature()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);

        $old = $this->db
            ->select('signature_image')
            ->where('admin_id', $adminId)
            ->get('invoice_settings')
            ->row_array();

        if (!empty($old['signature_image'])) {
            $path = FCPATH . 'uploads/signatures/' . $old['signature_image'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->db->where('admin_id', $adminId)->update('invoice_settings', [
            'signature_image' => null,
        ]);

        $this->session->set_flashdata('success', 'Signature removed.');
        redirect('invoice_master');
    }

    // ─────────────────────────────────────────────
    // BUILD BASE DATA
    // ─────────────────────────────────────────────
    private function buildBaseData(int $adminId): array
    {
        return [
            'userName'     => $this->admin['user_name'] ?? ($this->admin['name'] ?? 'Admin'),
            'userEmail'    => $this->admin['email'] ?? '',
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'role'         => (!empty($this->admin['role']) && (string) $this->admin['role'] !== '1')
                                ? (string) $this->admin['role']
                                : 'admin',
            'profileImage' => $this->admin['profile_image'] ?? '',
            'profileUrl'   => base_url('profile'),
            'adminId'      => $adminId,
        ];
    }
}