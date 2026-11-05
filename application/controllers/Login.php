<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require_once(APPPATH . 'core/My_Controller.php');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);
        $this->load->model('general_model');

        $this->form_validation->set_error_delimiters("<div class='error'>", "</div>");

        if ($this->session->userdata('admin')) {
            if ($this->router->fetch_method() != 'logout') {
                redirect('dashboard');
            }
        }
    }

    public function index()
    {
        // echo "jj";
        // die;
        $this->form_validation->set_rules(
            'mobile',
            'Mobile',
            'required|regex_match[/^[0-9]{10}$/]',
            ['regex_match' => 'Enter valid 10 digit mobile number']
        );
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            $mobile = $this->input->post('mobile');
            $password = md5($this->input->post('password'));

            $user = $this->db
                ->where([
                    'mobile' => $mobile,
                    'password' => $password,
                    'isActive' => 1
                ])
                ->get('users')
                ->row();

            if ($user) {
                $sessionData = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'business_name' => $user->business_name,
                    'email' => $user->email,
                    'address' => $user->address ?? '',
                    'profile_image' => $user->profile_image,
                    'instagram' => $user->instagram,

                    'role' => $user->role,
                    'logged_in' => true
                ];


                $this->session->set_userdata('admin', $sessionData);
                $this->session->set_flashdata('success', 'Login successful! Welcome ' . $user->name);
                redirect('dashboard');
                return;
            }


            $data['error'] = 'Invalid mobile or password.';
            $this->load->view('login_view', $data);
            return;
        }

        $this->load->view('login_view');
    }

    public function register()
    {
        $data['defaultProfileImage'] = base_url('assets/images/Default.jpg');
        $this->load->view('register_view', $data);
    }

    public function sign_up()
    {
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('business_name', 'Business Name', 'required|trim');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            $data['defaultProfileImage'] = base_url('assets/images/Default.jpg');
            $this->load->view('register_view', $data);
            return;
        }

        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $plainPassword = $this->input->post('password');
        $businessName = trim((string) $this->input->post('business_name'));
        $profileImage = 'assets/images/Default.jpg';

        $exists = $this->db
            ->group_start()
            ->where('mobile', $mobile)
            ->or_where('email', $email)
            ->group_end()
            ->get('users')
            ->row();

        if ($exists) {
            $data['mobile_error'] = 'Mobile or email already registered';
            $data['defaultProfileImage'] = base_url('assets/images/Default.jpg');
            $this->load->view('register_view', $data);
            return;
        }

        $insertData = [
            'name' => $this->input->post('full_name'),
            'business_name' => $businessName,
            'mobile' => $mobile,
            'email' => $email,
            'address' => '',
            'profile_image' => $profileImage,
            'password' => md5($plainPassword),
            'normal_password' => $plainPassword,
            'role' => 1,
            'isActive' => 1,
            'created_on' => date('Y-m-d')
        ];

        if ($this->db->insert('users', $insertData)) {
            $userId = $this->db->insert_id();
            $sessionData = [
                'user_id' => $userId,
                'user_name' => $insertData['name'],
                'business_name' => $insertData['business_name'],
                'email' => $insertData['email'],
                'address' => $insertData['address'],
                'profile_image' => $insertData['profile_image'],
                'role' => 1,
                'logged_in' => true
            ];

            $this->session->set_userdata('admin', $sessionData);
            redirect('dashboard');
            return;
        }

        $this->session->set_flashdata('error', 'Registration failed');
        redirect('register');
    }

    public function logout()
    {
        $this->session->unset_userdata('admin');
        $this->session->sess_destroy();
        redirect('login');
    }
}
