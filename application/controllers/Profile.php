<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'core/My_Controller.php');

class Profile extends My_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
    }

    public function index()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $user = $this->db->where('id', $adminId)->get('users')->row_array();

        if (empty($user)) {
            show_404();
        }

        $data = $this->buildViewData($user);
        $this->load->view('header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('footer', $data);
    }

    public function update()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $user = $this->db->where('id', $adminId)->get('users')->row_array();

        if (empty($user)) {
            show_404();
        }

        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('business_name', 'Business Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('instagram', 'Instagram', 'trim');
        $this->form_validation->set_rules('facebook', 'Facebook', 'trim');

        if ($this->form_validation->run() === false) {
            $data = $this->buildViewData($user);
            $this->load->view('header', $data);
            $this->load->view('profile_view', $data);
            $this->load->view('footer', $data);
            return;
        }

        $email = trim((string) $this->input->post('email'));
        $mobile = trim((string) $this->input->post('mobile'));

        $exists = $this->db
            ->group_start()
            ->where('mobile', $mobile)
            ->or_where('email', $email)
            ->group_end()
            ->where('id !=', $adminId)
            ->get('users')
            ->row();

        if ($exists) {
            $data = $this->buildViewData($user);
            $data['profileError'] = 'Mobile or email already registered for another user';
            $this->load->view('header', $data);
            $this->load->view('profile_view', $data);
            $this->load->view('footer', $data);
            return;
        }

        $profileImage = $user['profile_image'] ?? 'assets/images/Default.jpg';

        if (!empty($_FILES['profile_image']['name'])) {
            $uploadPath = FCPATH . 'uploads/profile/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $config = [
                'upload_path' => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size' => 2048,
                'encrypt_name' => true
            ];

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_image')) {
                $data = $this->buildViewData($user);
                $data['profileError'] = strip_tags($this->upload->display_errors('', ''));
                $this->load->view('header', $data);
                $this->load->view('profile_view', $data);
                $this->load->view('footer', $data);
                return;
            }

            $uploadData = $this->upload->data();
            $profileImage = $uploadData['file_name'] ?? $profileImage;
        }

        $updateData = [
            'name'          => trim((string) $this->input->post('full_name')),
            'business_name' => trim((string) $this->input->post('business_name')),
            'email'         => $email,
            'mobile'        => $mobile,
            'address'       => trim((string) $this->input->post('address')),
            'instagram'     => trim((string) $this->input->post('instagram')),
            'facebook'      => trim((string) $this->input->post('facebook')),
            'profile_image' => $profileImage
        ];

        $this->db->where('id', $adminId)->update('users', $updateData);

        // Update session
        $sessionData = $this->admin;
        $sessionData['user_name']     = $updateData['name'];
        $sessionData['business_name'] = $updateData['business_name'];
        $sessionData['email']         = $updateData['email'];
        $sessionData['phone']         = $updateData['mobile'];

        $sessionData['address']       = $updateData['address'];
        $sessionData['instagram']     = $updateData['instagram'];
        $sessionData['facebook']      = $updateData['facebook'];
        $sessionData['profile_image'] = $updateData['profile_image'];
        $this->session->set_userdata('admin', $sessionData);

        $this->session->set_flashdata('success', 'Profile updated successfully');
        redirect('profile');
    }

    /**
     * Change Password
     */
   public function change_password()
{
    $adminId = (int) ($this->admin['user_id'] ?? 0);
    $user = $this->db->where('id', $adminId)->get('users')->row_array();

    if (empty($user)) {
        show_404();
    }

    $this->form_validation->set_rules('current_password', 'Current Password', 'required');
    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

    if ($this->form_validation->run() === false) {
        $data = $this->buildViewData($user);
        $data['activeTab'] = 'password';
        $this->load->view('header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('footer', $data);
        return;
    }

    $currentPassword = md5($this->input->post('current_password'));
    $newPassword     = $this->input->post('new_password');
    $newPasswordMd5  = md5($newPassword);

    // ✅ Check current password
    if ($currentPassword !== $user['password']) {
        $data = $this->buildViewData($user);
        $data['activeTab'] = 'password';
        $data['passwordError'] = 'Current password is incorrect';
        $this->load->view('header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('footer', $data);
        return;
    }

    // ✅ Prevent same password
    if ($newPasswordMd5 === $user['password']) {
        $data = $this->buildViewData($user);
        $data['activeTab'] = 'password';
        $data['passwordError'] = 'New password cannot be same as current password';
        $this->load->view('header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('footer', $data);
        return;
    }

    // ✅ Update password
    $this->db->where('id', $adminId)->update('users', [
        'password' => $newPasswordMd5,
        'normal_password' => $newPassword // (since you are storing it already)
    ]);

    $this->session->set_flashdata('password_success', 'Password changed successfully');
    redirect('profile');
}

    private function buildViewData(array $user): array
    {
        $profileImage = $this->resolveProfileImage($user['profile_image'] ?? '');

        return [
            'user'         => $user,
            'userName'     => $user['name'] ?? 'Admin',
            'userEmail'    => $user['email'] ?? '',
            'businessName' => $user['business_name'] ?? 'Bills Book',
            'role'         => (!empty($user['role']) && (string) $user['role'] !== '1') ? (string) $user['role'] : 'admin',
            'profileImage' => $profileImage,
            'profileUrl'   => base_url('profile'),
            'pageTitle'    => 'Profile',
            'activeTab'    => 'profile',
        ];
    }

    private function resolveProfileImage(string $profileSource): string
    {
        if ($profileSource === '') {
            return base_url('assets/images/Default.jpg');
        }

        if (preg_match('#^https?://#i', $profileSource)) {
            return $profileSource;
        }

        if (strpos($profileSource, 'uploads/') === 0 || strpos($profileSource, 'assets/') === 0) {
            return base_url($profileSource);
        }

        return base_url('uploads/profile/' . ltrim($profileSource, '/'));
    }
}