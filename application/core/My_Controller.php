<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller
{
    public $admin;
    public $provider;

    public function __construct()
    {
        parent::__construct();

        // Load common libraries
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->model('general_model');

        // Get admin session
        $this->admin = $this->session->userdata('admin');
        $controller = strtolower((string)$this->router->fetch_class());


        /* -------------------------------------------------------
           LOAD ADMIN ROLE DATA IF MISSING
        ------------------------------------------------------- */

        if (!empty($this->admin) && empty($this->admin['role'])) {

            $adminRow = $this->db
                ->select('role, profile_image, name, business_name, email, address,instagram')
                ->where('id', $this->admin['user_id'])
                ->get('users')
                ->row();

            if ($adminRow) {

                $this->admin['role'] = $adminRow->role ?? 'admin';
                $this->admin['profile_image'] = $adminRow->profile_image ?? null;
                $this->admin['user_name'] = $adminRow->name ?? '';
                $this->admin['business_name'] = $adminRow->business_name ?? '';
                $this->admin['email'] = $adminRow->email ?? '';
                $this->admin['address'] = $adminRow->address ?? '';
                $this->admin['instagram'] = $adminRow->instagram ?? '';


                $this->session->set_userdata('admin', $this->admin);
            }
        }

        /* -------------------------------------------------------
           LOGIN CHECK
        ------------------------------------------------------- */

        if (!$this->admin) {
            redirect('login');
        }
    }
}
