<?php
defined('BASEPATH') or exit('No direct script access allowed');

define('OTP_FIXED_MODE', true);

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require_once FCPATH . 'vendor/autoload.php';

class Api extends CI_Controller
{
    private $jwt_secret = 'b7c1f3e9a2d64c58f19a8e73d0bcb52f8edc6b31a9f71e48d9a7e2f3c1a5b8e9';
    private $jwt_expiry = 365 * 24 * 60 * 60; // 1 year
    private $request_data = null;

    /*=======================================================================
    | CONSTRUCTOR
    |=======================================================================*/

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['General_model', 'Sales_model']);
        $this->load->library(['form_validation']);
        $this->load->helper(['url', 'form']);

        $this->db->query("CREATE TABLE IF NOT EXISTS invoice_templates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            primary_color VARCHAR(7) NOT NULL DEFAULT '#4F46E5',
            accent_color VARCHAR(7) NOT NULL DEFAULT '#10B981',
            text_color VARCHAR(7) NOT NULL DEFAULT '#1F2937',
            font VARCHAR(100) NOT NULL DEFAULT 'Poppins',
            layout VARCHAR(50) NOT NULL DEFAULT 'minimal',
            logo_position VARCHAR(20) NOT NULL DEFAULT 'left',
            is_custom TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );");

        // Load configs safely - fail gracefully if they do not exist
        $this->config->load('razorpay', FALSE, TRUE);
        $this->config->load('shiprocket', FALSE, TRUE);

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Content-Type: application/json; charset=UTF-8');

        // Handle browser preflight request
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

    }

    /*=======================================================================
    | CORE / SHARED HELPERS
    |=======================================================================*/


    private function send_response(bool $status, string $message, $data = null, int $http_code = 200): void
    {
        http_response_code($http_code);
        echo json_encode([
            'status'  => $status,
            'code'    => $http_code,
            'message' => $message,
            'data'    => $data,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function require_method(string $method): void
    {
        if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
            $this->send_response(false, 'Method not allowed. Please use ' . strtoupper($method) . '.', null, 405);
        }
    }

    private function request_data(): array
    {
        if ($this->request_data !== null) {
            return $this->request_data;
        }

        if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
            $this->request_data = $this->input->get() ?: [];
            return $this->request_data;
        }

        $json = json_decode($this->input->raw_input_stream, true);
        $this->request_data = array_merge(
            $this->input->post() ?: [],
            is_array($json) ? $json : []
        );

        return $this->request_data;
    }

    /**
     * Read one field: JSON body → POST → GET (priority order).
     */
    private function input_value(string $key, string $default = ''): string
    {
        $data = $this->request_data();
        return array_key_exists($key, $data) ? trim((string) $data[$key]) : $default;
    }


    private function resolve_profile_image(?string $profileSource): string
    {
        if (empty($profileSource)) {
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

    private function resolve_product_image(?string $image): string
    {
        if (empty($image)) {
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

    /*=======================================================================
    | AUTH / TOKEN HELPERS
    |=======================================================================*/

    private function validate_token(bool $with_meta = false): object
    {
        $header = $this->input->get_request_header('Authorization', true);

        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $this->send_response(false, 'Authorization header missing. Please login first.', null, 401);
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_secret, 'HS256'));
        } catch (Exception $e) {
            $this->send_response(false, 'Token is invalid or expired. Please login again.', null, 401);
        }

        if (
            $this->db->table_exists('token_blacklist') &&
            $this->db
            ->where('token', $token)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->count_all_results('token_blacklist') > 0
        ) {
            $this->send_response(false, 'Token has been logged out. Please login again.', null, 401);
        }

        if (empty($decoded->data->id)) {
            $this->send_response(false, 'Token data is invalid.', null, 401);
        }

        if ($with_meta) {
            $decoded->token = $token;
            return $decoded;
        }

        return $decoded->data;
    }

    private function require_token_user_id(): int
    {
        $token_data = $this->validate_token();
        $user_id = (int) ($token_data->id ?? 0);

        if ($user_id <= 0) {
            $this->send_response(false, 'Token data is invalid.', null, 401);
        }

        $user = $this->db->get_where('users', [
            'id'       => $user_id,
            'isActive' => 1,
        ])->row();

        if (!$user) {
            $this->send_response(false, 'User account not found or inactive.', null, 401);
        }

        return $user_id;
    }

    private function generate_token($user): string
    {
        $user = (object) $user;

        $payload = [
            'iss'  => base_url(),
            'iat'  => time(),
            'exp'  => time() + $this->jwt_expiry,
            'data' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'mobile' => $user->mobile ?? '',
                'role'   => $user->role,
            ],
        ];

        return JWT::encode($payload, $this->jwt_secret, 'HS256');
    }

    private function send_otp_via_sms(string $mobileNo, string $otp): bool
    {
        if (OTP_FIXED_MODE) {
            log_message('info', '[DEV] OTP for ' . $mobileNo . ' is: ' . $otp);
            return true;
        }

        $message = "Hi $mobileNo\n\nYour Verification OTP is $otp Do not share this OTP with anyone for security reasons.\n\nRegards\nOMKARENT";

        $params = [
            'user'     => 'Fitcketsp',
            'key'      => '81a6b2f99cXX',
            'mobile'   => '91' . $mobileNo,
            'message'  => $message,
            'senderid' => 'OENTER',
            'accusage' => '1',
            'entityid' => '1401487200000053882',
            'tempid'   => '1407168611506367587',
        ];

        $url = 'http://mobicomm.dove-sms.com/submitsms.jsp?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'OTP SMS cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        log_message('info', "OTP sent to $mobileNo. Response: $response");

        return true;
    }

    /*=======================================================================
    | PUBLIC AUTH ENDPOINTS
    |=======================================================================*/

    /*-----------------------------------------------------------------------
    | REGISTER (Sends registration OTP)
    | POST /api/register
    | Body: { "email", "mobile" }
    |-----------------------------------------------------------------------*/
    public function register(): void
    {
        $this->require_method('POST');

        $email         = $this->input_value('email');
        $mobile        = $this->input_value('mobile');

        // Validate basic fields
        if (empty($email) || empty($mobile)) {
            $this->send_response(false, 'Email and mobile are required.', null, 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->send_response(false, 'Please enter a valid email address.', null, 400);
        }

        if (!is_numeric($mobile) || strlen($mobile) !== 10) {
            $this->send_response(false, 'Mobile number must be exactly 10 digits.', null, 400);
        }

        // Check duplicate email / mobile
        if ($this->db->get_where('users', ['email' => $email])->row()) {
            $this->send_response(false, 'This email address is already registered.', null, 400);
        }

        if ($this->db->get_where('users', ['mobile' => $mobile])->row()) {
            $this->send_response(false, 'This mobile number is already registered.', null, 400);
        }

        // Generate default OTP
        $otp = '123456';

        // Clear old registration OTPs for this mobile
        $this->db->where('mobile', $mobile)->delete('user_registration_otps');

        // Store OTP (WITHOUT user_data)
        $this->db->insert('user_registration_otps', [
            'mobile'     => $mobile,
            'otp'        => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ]);

        // Send SMS
        $this->send_otp_via_sms($mobile, $otp);

        $this->send_response(true, 'OTP sent successfully to your mobile number.', [
            'masked_mobile' => '*******' . substr($mobile, -4),
            'expires_in'    => '5 minutes',
            'otp'           => $otp
        ]);
    }

    /*-----------------------------------------------------------------------
    | VERIFY REGISTRATION OTP
    | POST /api/register_verify_otp
    | Body: { "mobile", "otp", "full_name", "business_name", "email" } or { "mobile", "otp", "name", "shop_name", "email" }
    |-----------------------------------------------------------------------*/
    public function register_verify_otp(): void
    {
        $this->require_method('POST');

        $mobile        = $this->input_value('mobile');
        $entered_otp   = $this->input_value('otp');
        $name          = $this->input_value('full_name', $this->input_value('name'));
        $business_name = $this->input_value('business_name', $this->input_value('shop_name'));
        $email         = $this->input_value('email');

        // Validate OTP and mobile input
        if (empty($mobile) || empty($entered_otp)) {
            $this->send_response(false, 'Both mobile number and OTP are required.', null, 400);
        }

        // Validate user details are present for insertion
        if (empty($name) || empty($business_name) || empty($email)) {
            $this->send_response(false, 'full_name, business_name, and email are required to complete registration.', null, 400);
        }

        // Sanitise mobile
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        $mobile = substr($mobile, -10);

        // Find OTP
        $otp_row = $this->db
            ->where('mobile', $mobile)
            ->where('otp', $entered_otp)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('user_registration_otps')
            ->row();

        if (!$otp_row) {
            $this->send_response(false, 'OTP is incorrect or has expired. Please request a new OTP.', null, 400);
        }

        // Check duplicate email / mobile before inserting to users
        $exists = $this->db
            ->group_start()
            ->where('mobile', $mobile)
            ->or_where('email', $email)
            ->group_end()
            ->get('users')
            ->row();

        if ($exists) {
            if ($exists->mobile === $mobile) {
                $this->send_response(false, 'Mobile number already registered.', null, 400);
            } else {
                $this->send_response(false, 'Email address already registered.', null, 400);
            }
        }

        // Create user
        $insertData = [
            'name'            => $name,
            'business_name'   => $business_name,
            'mobile'          => $mobile,
            'email'           => $email,
            'address'         => '',
            'profile_image'   => 'assets/images/Default.jpg',
            'password'        => md5('123456'), // Default password
            'normal_password' => '123456',     // Default normal password
            'role'            => 1,
            'isActive'        => 1,
            'created_on'      => date('Y-m-d')
        ];

        if (!$this->db->insert('users', $insertData)) {
            $this->send_response(false, 'Registration failed. Please try again.', null, 500);
        }

        $new_user_id = $this->db->insert_id();

        // Cleanup used OTP
        $this->db->where('id', $otp_row->id)->delete('user_registration_otps');

        // Get full user object for token
        $user = $this->db->get_where('users', ['id' => $new_user_id])->row();

        // Generate JWT
        $token = $this->generate_token($user);

        $this->send_response(true, 'Registration successful. You are now logged in.', [
            'token' => $token,
            'user'  => [
                'id'            => (int) $new_user_id,
                'name'          => $user->name,
                'email'         => $user->email,
                'mobile'        => $user->mobile,
                'business_name' => $user->business_name,
                'shop_name'     => $user->business_name, // alias
                'profile_image' => base_url($user->profile_image),
                'image'         => base_url($user->profile_image), // alias
                'role'          => (int) $user->role,
            ],
        ], 201);
    }

    /*-----------------------------------------------------------------------
    | SEND OTP (Login)
    | POST /api/send_otp
    | Body: { "mobile" }
    |-----------------------------------------------------------------------*/
    public function send_otp(): void
    {
        $this->require_method('POST');

        $mobile = $this->input_value('mobile');

        // Validate
        if (empty($mobile)) {
            $this->send_response(false, 'Mobile number is required.', null, 400);
        }

        if (!is_numeric($mobile) || strlen($mobile) !== 10) {
            $this->send_response(false, 'Please enter a valid 10-digit mobile number.', null, 400);
        }

        // Check user exists
        $user = $this->db->get_where('users', ['mobile' => $mobile])->row();

        if (!$user) {
            $this->send_response(false, 'This mobile number is not registered. Please register first.', null, 404);
        }

        if ((int) $user->isActive !== 1) {
            $this->send_response(false, 'Your account is inactive. Please contact support.', null, 403);
        }

        // Generate default OTP
        $otp = '123456';

        // Clear old OTPs for this mobile
        $this->db->where('mobile', $mobile)->delete('user_login_otps');

        // Insert new OTP against mobile (not user_id)
        $this->db->insert('user_login_otps', [
            'mobile'     => $mobile,
            'otp'        => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ]);

        // Send SMS
        $this->send_otp_via_sms($mobile, $otp);

        $this->send_response(true, 'OTP sent successfully to your mobile number.', [
            'masked_mobile' => '*******' . substr($mobile, -4),
            'expires_in'    => '5 minutes',
            'otp'           => $otp
        ]);
    }

    /*-----------------------------------------------------------------------
    | RESEND OTP (Login & Register)
    | POST /api/resend_otp
    | Body: { "mobile", "type" } // type is 'login' or 'register'
    |-----------------------------------------------------------------------*/
    public function resend_otp(): void
    {
        $this->require_method('POST');

        $mobile = $this->input_value('mobile');
        $type   = $this->input_value('type', 'login'); // 'login' or 'register'

        // Validate
        if (empty($mobile)) {
            $this->send_response(false, 'Mobile number is required.', null, 400);
        }

        if (!is_numeric($mobile) || strlen($mobile) !== 10) {
            $this->send_response(false, 'Please enter a valid 10-digit mobile number.', null, 400);
        }

        if ($type === 'register') {
            // Check duplicate email / mobile
            if ($this->db->get_where('users', ['mobile' => $mobile])->row()) {
                $this->send_response(false, 'This mobile number is already registered.', null, 400);
            }

            // Generate default OTP
            $otp = '123456';

            // Clear old registration OTPs for this mobile
            $this->db->where('mobile', $mobile)->delete('user_registration_otps');

            // Store OTP
            $this->db->insert('user_registration_otps', [
                'mobile'     => $mobile,
                'otp'        => $otp,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            ]);

            // Send SMS
            $this->send_otp_via_sms($mobile, $otp);

            $this->send_response(true, 'OTP resent successfully to your mobile number.', [
                'masked_mobile' => '*******' . substr($mobile, -4),
                'expires_in'    => '5 minutes',
                'otp'           => $otp
            ]);
        } else {
            // Login type
            // Check user exists
            $user = $this->db->get_where('users', ['mobile' => $mobile])->row();

            if (!$user) {
                $this->send_response(false, 'This mobile number is not registered. Please register first.', null, 404);
            }

            if ((int) $user->isActive !== 1) {
                $this->send_response(false, 'Your account is inactive. Please contact support.', null, 403);
            }

            // Generate default OTP
            $otp = '123456';

            // Clear old OTPs for this mobile
            $this->db->where('mobile', $mobile)->delete('user_login_otps');

            // Insert new OTP against mobile
            $this->db->insert('user_login_otps', [
                'mobile'     => $mobile,
                'otp'        => $otp,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            ]);

            // Send SMS
            $this->send_otp_via_sms($mobile, $otp);

            $this->send_response(true, 'OTP resent successfully to your mobile number.', [
                'masked_mobile' => '*******' . substr($mobile, -4),
                'expires_in'    => '5 minutes',
                'otp'           => $otp
            ]);
        }
    }

    /*-----------------------------------------------------------------------
    | VERIFY OTP (Login)
    | POST /api/verify_otp
    | Body: { "mobile", "otp" }
    |-----------------------------------------------------------------------*/
    public function verify_otp(): void
    {
        $this->require_method('POST');

        $mobile      = $this->input_value('mobile');
        $entered_otp = $this->input_value('otp');

        // Validate
        if (empty($mobile) || empty($entered_otp)) {
            $this->send_response(false, 'Both mobile number and OTP are required.', null, 400);
        }

        // Sanitise mobile
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        $mobile = substr($mobile, -10);

        // Look up OTP by mobile
        $otp_row = $this->db
            ->where('mobile', $mobile)
            ->where('otp', $entered_otp)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('user_login_otps')
            ->row();

        if (!$otp_row) {
            $this->send_response(false, 'OTP is incorrect or has expired. Please request a new OTP.', null, 400);
        }

        // Find user by mobile
        $user = $this->db->get_where('users', [
            'mobile'   => $mobile,
            'isActive' => 1,
        ])->row();

        if (!$user) {
            $this->send_response(false, 'User not found or account is inactive.', null, 404);
        }

        // Generate JWT
        $token = $this->generate_token($user);

        // Cleanup used OTP row
        $this->db->where('id', $otp_row->id)->delete('user_login_otps');

        $this->send_response(true, 'OTP verified. Login successful.', [
            'token' => $token,
            'user'  => [
                'id'            => (int) $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'mobile'        => $user->mobile,
                'business_name' => $user->business_name,
                'shop_name'     => $user->business_name, // alias
                'profile_image' => base_url($user->profile_image),
                'image'         => base_url($user->profile_image), // alias
                'role'          => (int) $user->role,
            ],
        ]);
    }

    /*-----------------------------------------------------------------------
    | LOGOUT USER
    | POST /api/logout
    |-----------------------------------------------------------------------*/
    public function logout(): void
    {
        $this->require_method('POST');

        $decoded = $this->validate_token(true);
        $token = $decoded->token;
        $expires_at = date('Y-m-d H:i:s', $decoded->exp);

        $blacklistData = [
            'token'      => $token,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert('token_blacklist', $blacklistData)) {
            $this->send_response(true, 'Logged out successfully.');
        } else {
            $this->send_response(false, 'Logout failed. Please try again.', null, 500);
        }
    }

    /*=======================================================================
    | PROFILE ENDPOINTS (Get / Update Profile)
    |=======================================================================*/

    /*-----------------------------------------------------------------------
    | GET MY PROFILE
    | GET /api/get_my_profile
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function get_my_profile(): void
    {
        $this->require_method('GET');
        $user_id = $this->require_token_user_id();

        $user = $this->db->get_where('users', ['id' => $user_id])->row();

        if (!$user) {
            $this->send_response(false, 'User not found.', null, 404);
        }

        // Hide password hash
        unset($user->password);
        unset($user->normal_password);

        // Resolve absolute image URL
        $user->profile_image = $this->resolve_profile_image($user->profile_image ?? '');
        $user->image = $user->profile_image; // alias

        $this->send_response(true, 'Profile fetched successfully.', $user);
    }

    /*-----------------------------------------------------------------------
    | UPDATE MY PROFILE (Partial Update)
    | POST /api/update_my_profile
    | Headers: Authorization: Bearer <token>
    | Body: { "full_name" (opt), "business_name" (opt), "email" (opt), "mobile" (opt), "address" (opt), "instagram" (opt), "facebook" (opt) }
    | Files: profile_image (opt)
    |-----------------------------------------------------------------------*/
    public function update_my_profile(): void
    {
        $this->require_method('POST');
        $user_id = $this->require_token_user_id();

        $request = $this->request_data();
        $updateData = [];

        // Check name
        if (array_key_exists('full_name', $request)) {
            $updateData['name'] = trim((string) $request['full_name']);
        } elseif (array_key_exists('name', $request)) {
            $updateData['name'] = trim((string) $request['name']);
        }

        // Check business name
        if (array_key_exists('business_name', $request)) {
            $updateData['business_name'] = trim((string) $request['business_name']);
        } elseif (array_key_exists('shop_name', $request)) {
            $updateData['business_name'] = trim((string) $request['shop_name']);
        }

        // Check address
        if (array_key_exists('address', $request)) {
            $updateData['address'] = trim((string) $request['address']);
        }

        // Check instagram
        if (array_key_exists('instagram', $request)) {
            $updateData['instagram'] = trim((string) $request['instagram']);
        }

        // Check facebook
        if (array_key_exists('facebook', $request)) {
            $updateData['facebook'] = trim((string) $request['facebook']);
        }

        // Check email (need unique validation if modified)
        if (array_key_exists('email', $request)) {
            $email = trim((string) $request['email']);
            if (empty($email)) {
                $this->send_response(false, 'Email cannot be empty.', null, 400);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->send_response(false, 'Please enter a valid email address.', null, 400);
            }
            
            // Check if duplicate
            $duplicate = $this->db
                ->where('email', $email)
                ->where('id !=', $user_id)
                ->get('users')
                ->row();
            if ($duplicate) {
                $this->send_response(false, 'This email address is already registered to another user.', null, 400);
            }
            $updateData['email'] = $email;
        }

        // Check mobile (need unique validation if modified)
        if (array_key_exists('mobile', $request)) {
            $mobile = trim((string) $request['mobile']);
            if (empty($mobile)) {
                $this->send_response(false, 'Mobile cannot be empty.', null, 400);
            }
            if (!is_numeric($mobile) || strlen($mobile) !== 10) {
                $this->send_response(false, 'Mobile number must be exactly 10 digits.', null, 400);
            }

            // Check if duplicate
            $duplicate = $this->db
                ->where('mobile', $mobile)
                ->where('id !=', $user_id)
                ->get('users')
                ->row();
            if ($duplicate) {
                $this->send_response(false, 'This mobile number is already registered to another user.', null, 400);
            }
            $updateData['mobile'] = $mobile;
        }

        // Check if file upload is provided
        $profileImageFile = null;
        if (!empty($_FILES['profile_image']['name'])) {
            $profileImageFile = 'profile_image';
        } elseif (!empty($_FILES['image']['name'])) {
            $profileImageFile = 'image';
        }

        if ($profileImageFile) {
            $uploadPath = FCPATH . 'uploads/profile/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($profileImageFile)) {
                $this->send_response(false, strip_tags($this->upload->display_errors('', '')), null, 400);
            }

            $uploadData = $this->upload->data();
            $updateData['profile_image'] = 'uploads/profile/' . $uploadData['file_name'];
        }

        if (empty($updateData)) {
            $this->send_response(false, 'No fields provided for update.', null, 400);
        }

        // Perform db update
        $this->db->where('id', $user_id)->update('users', $updateData);

        // Fetch updated user record
        $user = $this->db->get_where('users', ['id' => $user_id])->row();

        // Hide password fields
        unset($user->password);
        unset($user->normal_password);

        // Resolve absolute image URL
        $user->profile_image = $this->resolve_profile_image($user->profile_image ?? '');
        $user->image = $user->profile_image; // alias

        $this->send_response(true, 'Profile updated successfully.', $user);
    }

    /*=======================================================================
    | CATEGORY & PRODUCT ENDPOINTS
    |=======================================================================*/

    /*-----------------------------------------------------------------------
    | GET CATEGORY LIST
    | GET /api/get_category_list
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function get_category_list(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $categories = $this->db
            ->select('category.*, COUNT(product.id) AS productCount')
            ->from('category')
            ->join('product', 'product.category_id = category.id AND product.isActive = 1', 'left')
            ->where('category.admin_id', $adminId)
            ->where('category.isActive', 1)
            ->group_by('category.id')
            ->order_by('category.id', 'DESC')
            ->get()
            ->result_array();

        $this->send_response(true, 'Categories fetched successfully.', $categories);
    }

    /*-----------------------------------------------------------------------
    | GET CATEGORY DETAIL
    | GET /api/get_category_detail
    | Headers: Authorization: Bearer <token>
    | Query params: category_id
    |-----------------------------------------------------------------------*/
    public function get_category_detail(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $categoryId = (int) $this->input_value('category_id');

        if ($categoryId <= 0) {
            $this->send_response(false, 'category_id is required.', null, 400);
        }

        $category = $this->db
            ->where('id', $categoryId)
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->get('category')
            ->row_array();

        if (empty($category)) {
            $this->send_response(false, 'Category not found.', null, 404);
        }

        $this->send_response(true, 'Category details fetched successfully.', $category);
    }

    /*-----------------------------------------------------------------------
    | GET PRODUCTS BY CATEGORY
    | GET /api/get_products_by_category
    | Headers: Authorization: Bearer <token>
    | Query params: category_id
    |-----------------------------------------------------------------------*/
    public function get_products_by_category(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $categoryId = (int) $this->input_value('category_id');

        if ($categoryId <= 0) {
            $this->send_response(false, 'category_id is required.', null, 400);
        }

        // Verify category exists and belongs to the admin
        $categoryExists = $this->db
            ->where('id', $categoryId)
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->count_all_results('category') > 0;

        if (!$categoryExists) {
            $this->send_response(false, 'Category not found.', null, 404);
        }

        $products = $this->db
            ->select('product.*, category.name AS category_name')
            ->from('product')
            ->join('category', 'category.id = product.category_id', 'left')
            ->where('product.category_id', $categoryId)
            ->where('product.admin_id', $adminId)
            ->where('product.isActive', 1)
            ->order_by('product.id', 'DESC')
            ->get()
            ->result_array();

        // Resolve absolute image URLs
        foreach ($products as &$product) {
            $product['image'] = $this->resolve_product_image($product['image'] ?? '');
        }

        $this->send_response(true, 'Products fetched successfully.', $products);
    }

    /*-----------------------------------------------------------------------
    | GET PRODUCT LIST
    | GET /api/get_product_list
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function get_product_list(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $products = $this->db
            ->select('product.*, category.name AS category_name')
            ->from('product')
            ->join('category', 'category.id = product.category_id', 'left')
            ->where('product.admin_id', $adminId)
            ->where('product.isActive', 1)
            ->order_by('product.id', 'DESC')
            ->get()
            ->result_array();

        // Resolve absolute image URLs
        foreach ($products as &$product) {
            $product['image'] = $this->resolve_product_image($product['image'] ?? '');
        }

        $this->send_response(true, 'Products fetched successfully.', $products);
    }

    /*-----------------------------------------------------------------------
    | GET PRODUCT DETAIL
    | GET /api/get_product_detail
    | Headers: Authorization: Bearer <token>
    | Query params: product_id
    |-----------------------------------------------------------------------*/
    public function get_product_detail(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $productId = (int) $this->input_value('product_id');

        if ($productId <= 0) {
            $this->send_response(false, 'product_id is required.', null, 400);
        }

        $product = $this->db
            ->select('product.*, category.name AS category_name')
            ->from('product')
            ->join('category', 'category.id = product.category_id', 'left')
            ->where('product.id', $productId)
            ->where('product.admin_id', $adminId)
            ->where('product.isActive', 1)
            ->get()
            ->row_array();

        if (empty($product)) {
            $this->send_response(false, 'Product not found.', null, 404);
        }

        $product['image'] = $this->resolve_product_image($product['image'] ?? '');

        $this->send_response(true, 'Product details fetched successfully.', $product);
    }

    public function add_category(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $name = trim($this->input_value('name'));
        $status = trim($this->input_value('status', 'active'));

        if (empty($name)) {
            $this->send_response(false, 'Category name is required.', null, 400);
        }

        $insertData = [
            'admin_id'    => $adminId,
            'name'        => $name,
            'isActive'    => $status === 'inactive' ? 0 : 1,
            'created_on'  => date('Y-m-d')
        ];

        $this->db->insert('category', $insertData);
        $categoryId = $this->db->insert_id();

        $newCategory = $this->db->get_where('category', ['id' => $categoryId])->row_array();
        $newCategory['description'] = '';
        $newCategory['icon'] = 'bi-tag';
        $newCategory['color'] = '#4F46E5';
        $newCategory['productCount'] = 0;
        $newCategory['status'] = $newCategory['isActive'] == 0 ? 'inactive' : 'active';

        $this->send_response(true, 'Category added successfully.', $newCategory);
    }

    public function update_category(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $id = (int) $this->input_value('id');
        $name = trim($this->input_value('name'));
        $status = trim($this->input_value('status'));

        if ($id <= 0) {
            $this->send_response(false, 'Category ID is required.', null, 400);
        }

        $category = $this->db->get_where('category', ['id' => $id, 'admin_id' => $adminId])->row_array();
        if (empty($category)) {
            $this->send_response(false, 'Category not found.', null, 404);
        }

        $updateData = [];
        if ($name !== null && $name !== '') $updateData['name'] = $name;
        if ($status !== null && $status !== '') $updateData['isActive'] = $status === 'inactive' ? 0 : 1;

        if (!empty($updateData)) {
            $this->db->where('id', $id)->where('admin_id', $adminId)->update('category', $updateData);
        }

        $updatedCategory = $this->db->get_where('category', ['id' => $id])->row_array();
        $updatedCategory['description'] = '';
        $updatedCategory['icon'] = 'bi-tag';
        $updatedCategory['color'] = '#4F46E5';
        $productCount = $this->db->where('category_id', $id)->where('isActive', 1)->count_all_results('product');
        $updatedCategory['productCount'] = $productCount;
        $updatedCategory['status'] = $updatedCategory['isActive'] == 0 ? 'inactive' : 'active';

        $this->send_response(true, 'Category updated successfully.', $updatedCategory);
    }

    public function delete_category(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $id = (int) $this->input_value('id');
        if ($id <= 0) {
            $id = (int) $this->input_value('category_id');
        }

        if ($id <= 0) {
            $this->send_response(false, 'Category ID is required.', null, 400);
        }

        $category = $this->db->get_where('category', ['id' => $id, 'admin_id' => $adminId])->row_array();
        if (empty($category)) {
            $this->send_response(false, 'Category not found.', null, 404);
        }

        $this->db->where('id', $id)->where('admin_id', $adminId)->update('category', ['isActive' => 0]);
        $this->db->where('category_id', $id)->where('admin_id', $adminId)->update('product', ['isActive' => 0]);

        $this->send_response(true, 'Category deleted successfully.', ['id' => $id]);
    }

    public function add_product(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $name = trim($this->input_value('name'));
        $categoryId = (int) $this->input_value('category');
        if ($categoryId <= 0) {
            $categoryId = (int) $this->input_value('category_id');
        }
        $description = trim($this->input_value('description'));
        $salePrice = (float) $this->input_value('price');
        $purchasePrice = (float) $this->input_value('costPrice');
        $quantity = (int) $this->input_value('stock');
        $isActive = trim($this->input_value('status', 'active')) === 'inactive' ? 0 : 1;

        if (empty($name)) {
            $this->send_response(false, 'Product name is required.', null, 400);
        }
        if ($categoryId <= 0) {
            $this->send_response(false, 'Valid category ID is required.', null, 400);
        }

        $productImageFile = null;
        if (!empty($_FILES['product_image']['name'])) {
            $productImageFile = 'product_image';
        } elseif (!empty($_FILES['image']['name'])) {
            $productImageFile = 'image';
        }

        $imagePath = null;
        if ($productImageFile) {
            $uploadPath = FCPATH . 'uploads/product/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload($productImageFile)) {
                $uploadData = $this->upload->data();
                $imagePath = $uploadData['file_name'];
            }
        }

        $insertData = [
            'admin_id'       => $adminId,
            'category_id'    => $categoryId,
            'name'           => $name,
            'description'    => $description,
            'purchase_price' => $purchasePrice,
            'sale_price'     => $salePrice,
            'price'          => $salePrice,
            'quantity'       => $quantity,
            'image'          => $imagePath,
            'isActive'       => $isActive,
            'created_on'     => date('Y-m-d')
        ];

        $this->db->insert('product', $insertData);
        $productId = $this->db->insert_id();

        $newProduct = $this->db
            ->select('product.*, category.name AS category_name')
            ->from('product')
            ->join('category', 'category.id = product.category_id', 'left')
            ->where('product.id', $productId)
            ->get()
            ->row_array();

        $newProduct['image'] = $this->resolve_product_image($newProduct['image'] ?? '');

        $this->send_response(true, 'Product added successfully.', $newProduct);
    }

    public function update_product(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $id = (int) $this->input_value('id');
        if ($id <= 0) {
            $id = (int) $this->input_value('product_id');
        }

        if ($id <= 0) {
            $this->send_response(false, 'Product ID is required.', null, 400);
        }

        $product = $this->db->get_where('product', ['id' => $id, 'admin_id' => $adminId])->row_array();
        if (empty($product)) {
            $this->send_response(false, 'Product not found.', null, 404);
        }

        $name = $this->input_value('name');
        $categoryId = (int) $this->input_value('category');
        if ($categoryId <= 0) {
            $categoryId = (int) $this->input_value('category_id');
        }
        $description = $this->input_value('description');
        $salePriceStr = $this->input_value('price');
        $purchasePriceStr = $this->input_value('costPrice');
        $quantityStr = $this->input_value('stock');
        $status = $this->input_value('status');

        $updateData = [];
        if ($name !== null && trim($name) !== '') $updateData['name'] = trim($name);
        if ($categoryId > 0) $updateData['category_id'] = $categoryId;
        if ($description !== null) $updateData['description'] = trim($description);
        if ($salePriceStr !== null && $salePriceStr !== '') {
            $updateData['sale_price'] = (float)$salePriceStr;
            $updateData['price'] = (float)$salePriceStr;
        }
        if ($purchasePriceStr !== null && $purchasePriceStr !== '') $updateData['purchase_price'] = (float)$purchasePriceStr;
        if ($quantityStr !== null && $quantityStr !== '') $updateData['quantity'] = (int)$quantityStr;
        if ($status !== null && $status !== '') $updateData['isActive'] = trim($status) === 'inactive' ? 0 : 1;

        $productImageFile = null;
        if (!empty($_FILES['product_image']['name'])) {
            $productImageFile = 'product_image';
        } elseif (!empty($_FILES['image']['name'])) {
            $productImageFile = 'image';
        }

        if ($productImageFile) {
            $uploadPath = FCPATH . 'uploads/product/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload($productImageFile)) {
                $uploadData = $this->upload->data();
                $updateData['image'] = $uploadData['file_name'];
            }
        }

        if (!empty($updateData)) {
            $this->db->where('id', $id)->where('admin_id', $adminId)->update('product', $updateData);
        }

        $updatedProduct = $this->db
            ->select('product.*, category.name AS category_name')
            ->from('product')
            ->join('category', 'category.id = product.category_id', 'left')
            ->where('product.id', $id)
            ->get()
            ->row_array();

        $updatedProduct['image'] = $this->resolve_product_image($updatedProduct['image'] ?? '');

        $this->send_response(true, 'Product updated successfully.', $updatedProduct);
    }

    public function delete_product(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $id = (int) $this->input_value('id');
        if ($id <= 0) {
            $id = (int) $this->input_value('product_id');
        }

        if ($id <= 0) {
            $this->send_response(false, 'Product ID is required.', null, 400);
        }

        $product = $this->db->get_where('product', ['id' => $id, 'admin_id' => $adminId])->row_array();
        if (empty($product)) {
            $this->send_response(false, 'Product not found.', null, 404);
        }

        $this->db->where('id', $id)->where('admin_id', $adminId)->update('product', ['isActive' => 0]);

        $this->send_response(true, 'Product deleted successfully.', ['id' => $id]);
    }

    /*=======================================================================
    | BACKWARD COMPATIBLE WRAPPER METHODS
    |=======================================================================*/

    public function login(): void
    {
        $this->verify_otp();
    }

    /*=======================================================================
    | SALES CRUD ENDPOINTS
    |=======================================================================*/

    /*-----------------------------------------------------------------------
    | CUSTOMER LOOKUP BY PHONE
    | GET /api/customer_lookup
    | Headers: Authorization: Bearer <token>
    | Query params: phone
    |-----------------------------------------------------------------------*/
    public function customer_lookup(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $phone = preg_replace('/\D+/', '', (string) $this->input_value('phone'));

        if ($phone === '') {
            $this->send_response(false, 'Phone number is required.', null, 400);
        }

        $customer = $this->db
            ->where('admin_id', $adminId)
            ->where('phone', $phone)
            ->where('isActive', 1)
            ->get('customers')
            ->row_array();

        if ($customer) {
            $this->send_response(true, 'Customer details fetched successfully.', $customer);
        } else {
            $this->send_response(false, 'Customer not found.', null, 404);
        }
    }

    /*-----------------------------------------------------------------------
    | GET PARTIES LIST
    | GET /api/get_parties_list
    | Headers: Authorization: Bearer <token>
    | Query params: search, type
    |-----------------------------------------------------------------------*/
    public function get_parties_list(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $search = trim((string) $this->input_value('search'));
        $type = trim((string) $this->input_value('type'));

        $this->db->select('customers.*, 
            COALESCE(SUM(sales.grand_total), 0) AS total_business,
            COALESCE(SUM(CASE WHEN sales.status != "paid" THEN sales.grand_total ELSE 0 END), 0) AS outstanding_amount')
            ->from('customers')
            ->join('sales', 'sales.customer_id = customers.id', 'left')
            ->where('customers.admin_id', $adminId)
            ->where('customers.isActive', 1);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('customers.name', $search)
                ->or_like('customers.phone', $search)
                ->or_like('customers.email', $search)
                ->or_like('customers.company', $search)
                ->group_end();
        }

        if (!empty($type) && $type !== 'all') {
            $this->db->where('customers.type', $type);
        }

        $parties = $this->db->group_by('customers.id')
            ->order_by('customers.id', 'DESC')
            ->get()
            ->result_array();

        foreach ($parties as &$p) {
            $p['id'] = (int)$p['id'];
            $p['total_business'] = (float)$p['total_business'];
            $p['outstanding_amount'] = (float)$p['outstanding_amount'];
        }

        $this->send_response(true, 'Parties list fetched successfully.', $parties);
    }

    /*-----------------------------------------------------------------------
    | ADD PARTY
    | POST /api/add_party
    | Headers: Authorization: Bearer <token>
    | Body: { "name", "type", "phone", "email", "company", "gstin", "address", "city", "state", "country", "pincode", "notes", "status" }
    |-----------------------------------------------------------------------*/
    public function add_party(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();
        $request = $this->request_data();

        $name = trim((string)($request['name'] ?? ''));
        if (empty($name)) {
            $this->send_response(false, 'Name is required.', null, 400);
        }

        $phone = trim((string)($request['phone'] ?? ''));
        if (!empty($phone) && !preg_match('/^[0-9]{10,15}$/', $phone)) {
            $this->send_response(false, 'Invalid phone number format.', null, 400);
        }

        $data = [
            'admin_id' => $adminId,
            'type' => trim((string)($request['type'] ?? 'customer')),
            'name' => $name,
            'company' => trim((string)($request['company'] ?? '')),
            'gstin' => trim((string)($request['gstin'] ?? '')),
            'email' => trim((string)($request['email'] ?? '')),
            'phone' => $phone,
            'address' => trim((string)($request['address'] ?? '')),
            'city' => trim((string)($request['city'] ?? '')),
            'state' => trim((string)($request['state'] ?? '')),
            'country' => trim((string)($request['country'] ?? 'India')),
            'pincode' => trim((string)($request['pincode'] ?? '')),
            'notes' => trim((string)($request['notes'] ?? '')),
            'status' => trim((string)($request['status'] ?? 'active')),
            'created_on' => date('Y-m-d'),
            'isActive' => 1
        ];

        $this->db->insert('customers', $data);
        $partyId = $this->db->insert_id();

        if ($partyId) {
            $data['id'] = (int)$partyId;
            $data['total_business'] = 0.0;
            $data['outstanding_amount'] = 0.0;
            $this->send_response(true, 'Party created successfully.', $data, 201);
        } else {
            $this->send_response(false, 'Failed to create party.', null, 500);
        }
    }

    /*-----------------------------------------------------------------------
    | UPDATE PARTY
    | POST /api/update_party
    | Headers: Authorization: Bearer <token>
    | Body: { "id", "name", "type", "phone", "email", "company", "gstin", "address", "city", "state", "country", "pincode", "notes", "status" }
    |-----------------------------------------------------------------------*/
    public function update_party(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();
        $request = $this->request_data();

        $partyId = (int) ($request['id'] ?? 0);
        if ($partyId <= 0) {
            $this->send_response(false, 'Valid ID is required.', null, 400);
        }

        $name = trim((string)($request['name'] ?? ''));
        if (empty($name)) {
            $this->send_response(false, 'Name is required.', null, 400);
        }

        $phone = trim((string)($request['phone'] ?? ''));
        if (!empty($phone) && !preg_match('/^[0-9]{10,15}$/', $phone)) {
            $this->send_response(false, 'Invalid phone number format.', null, 400);
        }

        $existing = $this->db->where('id', $partyId)->where('admin_id', $adminId)->get('customers')->row_array();
        if (empty($existing)) {
            $this->send_response(false, 'Party not found.', null, 404);
        }

        $data = [
            'type' => trim((string)($request['type'] ?? $existing['type'])),
            'name' => $name,
            'company' => trim((string)($request['company'] ?? '')),
            'gstin' => trim((string)($request['gstin'] ?? '')),
            'email' => trim((string)($request['email'] ?? '')),
            'phone' => $phone,
            'address' => trim((string)($request['address'] ?? '')),
            'city' => trim((string)($request['city'] ?? '')),
            'state' => trim((string)($request['state'] ?? '')),
            'country' => trim((string)($request['country'] ?? 'India')),
            'pincode' => trim((string)($request['pincode'] ?? '')),
            'notes' => trim((string)($request['notes'] ?? '')),
            'status' => trim((string)($request['status'] ?? $existing['status'])),
        ];

        $this->db->where('id', $partyId)->where('admin_id', $adminId)->update('customers', $data);

        $updated = $this->db->select('customers.*, 
            COALESCE(SUM(sales.grand_total), 0) AS total_business,
            COALESCE(SUM(CASE WHEN sales.status != "paid" THEN sales.grand_total ELSE 0 END), 0) AS outstanding_amount')
            ->from('customers')
            ->join('sales', 'sales.customer_id = customers.id', 'left')
            ->where('customers.id', $partyId)
            ->where('customers.admin_id', $adminId)
            ->group_by('customers.id')
            ->get()
            ->row_array();

        if ($updated) {
            $updated['id'] = (int)$updated['id'];
            $updated['total_business'] = (float)$updated['total_business'];
            $updated['outstanding_amount'] = (float)$updated['outstanding_amount'];
            $this->send_response(true, 'Party updated successfully.', $updated);
        } else {
            $this->send_response(false, 'Failed to retrieve updated party.', null, 500);
        }
    }

    /*-----------------------------------------------------------------------
    | DELETE PARTY
    | POST /api/delete_party
    | Headers: Authorization: Bearer <token>
    | Body: { "id" }
    |-----------------------------------------------------------------------*/
    public function delete_party(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();
        $request = $this->request_data();

        $partyId = (int) ($request['id'] ?? 0);
        if ($partyId <= 0) {
            $this->send_response(false, 'Valid ID is required.', null, 400);
        }

        $existing = $this->db->where('id', $partyId)->where('admin_id', $adminId)->get('customers')->row_array();
        if (empty($existing)) {
            $this->send_response(false, 'Party not found.', null, 404);
        }

        $this->db->where('id', $partyId)->where('admin_id', $adminId)->update('customers', ['isActive' => 0]);
        $this->send_response(true, 'Party deleted successfully.');
    }

    /*-----------------------------------------------------------------------
    | GET SALES LIST
    | GET /api/get_sales_list
    | Headers: Authorization: Bearer <token>
    | Query params: search, date_from, date_to, page
    |-----------------------------------------------------------------------*/
    public function get_sales_list(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $filters = [
            'search'    => trim((string) $this->input_value('search')),
            'date_from' => trim((string) $this->input_value('date_from')),
            'date_to'   => trim((string) $this->input_value('date_to'))
        ];

        $page = max(1, (int) $this->input_value('page', '1'));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalRows = $this->Sales_model->get_sales_count($adminId, $filters);
        $sales = $this->Sales_model->get_sales($adminId, $filters, $limit, $offset);

        // Fetch totals (grand total revenue) for search context
        $revenueQuery = $this->db
            ->select_sum('grand_total')
            ->from('sales')
            ->where('admin_id', $adminId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $revenueQuery->join('customers', 'customers.id = sales.customer_id', 'left')
                ->group_start()
                ->like('sales.invoice_number', $search)
                ->or_like('customers.name', $search)
                ->or_like('customers.phone', $search)
                ->group_end();
        }
        if (!empty($filters['date_from'])) {
            $revenueQuery->where('DATE(sales.sale_date) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $revenueQuery->where('DATE(sales.sale_date) <=', $filters['date_to']);
        }
        $totalRevenueRow = $revenueQuery->get()->row();
        $totalRevenue = (float) ($totalRevenueRow->grand_total ?? 0);

        $this->send_response(true, 'Sales list fetched successfully.', [
            'sales'         => $sales,
            'total_rows'    => $totalRows,
            'total_pages'   => ceil($totalRows / $limit),
            'current_page'  => $page,
            'total_revenue' => $totalRevenue
        ]);
    }

    /*-----------------------------------------------------------------------
    | GET SALE DETAIL
    | GET /api/get_sale_detail
    | Headers: Authorization: Bearer <token>
    | Query params: sale_id
    |-----------------------------------------------------------------------*/
    public function get_sale_detail(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $saleId = (int) $this->input_value('sale_id');

        if ($saleId <= 0) {
            $this->send_response(false, 'sale_id is required.', null, 400);
        }

        $sale = $this->Sales_model->get_sale_detail($saleId, $adminId);

        if (empty($sale)) {
            $this->send_response(false, 'Sale not found.', null, 404);
        }

        $this->send_response(true, 'Sale details fetched successfully.', $sale);
    }

    /*-----------------------------------------------------------------------
    | ADD SALE
    | POST /api/add_sale
    | Headers: Authorization: Bearer <token>
    | Body: { "customer_name", "customer_phone", "customer_address", "sale_date", "notes", "items" }
    |-----------------------------------------------------------------------*/
    public function add_sale(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $request = $this->request_data();

        $items = $request['items'] ?? null;
        if (empty($items) || !is_array($items)) {
            $this->send_response(false, 'Items list is required and must be an array.', null, 400);
        }

        $customerPhone = trim((string)($request['customer_phone'] ?? ''));
        if (!empty($customerPhone) && !preg_match('/^[0-9]{10,15}$/', $customerPhone)) {
            $this->send_response(false, 'Invalid phone number format.', null, 400);
        }

        try {
            $saleData = $this->Sales_model->create_sale($adminId, $request);

            $pdfUrl = null;
            try {
                $pdfUrl = $this->Sales_model->generate_invoice_pdf($saleData['sale_id'], $adminId);
            } catch (Exception $e) {
                log_message('error', 'API PDF generation failed: ' . $e->getMessage());
            }

            $sale = $this->Sales_model->get_sale_detail($saleData['sale_id'], $adminId);

            $this->send_response(true, 'Sale created successfully.', [
                'sale' => $sale,
                'invoice_pdf_url' => $pdfUrl
            ], 201);

        } catch (Exception $e) {
            $this->send_response(false, $e->getMessage(), null, 400);
        }
    }

    /*-----------------------------------------------------------------------
    | UPDATE SALE
    | POST /api/update_sale
    | Headers: Authorization: Bearer <token>
    | Body: { "sale_id", "customer_name", "customer_phone", "customer_address", "sale_date", "notes", "items" }
    |-----------------------------------------------------------------------*/
    public function update_sale(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $request = $this->request_data();

        $saleId = (int) ($request['sale_id'] ?? $request['id'] ?? 0);

        if ($saleId <= 0) {
            $this->send_response(false, 'Valid sale_id is required.', null, 400);
        }

        $items = $request['items'] ?? null;
        if (empty($items) || !is_array($items)) {
            $this->send_response(false, 'Items list is required and must be an array.', null, 400);
        }

        $customerPhone = trim((string)($request['customer_phone'] ?? ''));
        if (!empty($customerPhone) && !preg_match('/^[0-9]{10,15}$/', $customerPhone)) {
            $this->send_response(false, 'Invalid phone number format.', null, 400);
        }

        try {
            $saleData = $this->Sales_model->update_sale($saleId, $adminId, $request);

            $pdfUrl = null;
            try {
                $pdfUrl = $this->Sales_model->generate_invoice_pdf($saleId, $adminId);
            } catch (Exception $e) {
                log_message('error', 'API PDF regeneration failed: ' . $e->getMessage());
            }

            $sale = $this->Sales_model->get_sale_detail($saleId, $adminId);

            $this->send_response(true, 'Sale updated successfully.', [
                'sale' => $sale,
                'invoice_pdf_url' => $pdfUrl
            ]);

        } catch (Exception $e) {
            $this->send_response(false, $e->getMessage(), null, 400);
        }
    }

    /*-----------------------------------------------------------------------
    | DELETE SALE
    | POST /api/delete_sale
    | Headers: Authorization: Bearer <token>
    | Body: { "sale_id" }
    |-----------------------------------------------------------------------*/
    public function delete_sale(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $request = $this->request_data();
        $saleId = (int) ($request['sale_id'] ?? $request['id'] ?? 0);

        if ($saleId <= 0) {
            $this->send_response(false, 'Valid sale_id is required.', null, 400);
        }

        try {
            $this->Sales_model->delete_sale($saleId, $adminId);
            $this->send_response(true, 'Sale deleted and stock restored successfully.');
        } catch (Exception $e) {
            $this->send_response(false, $e->getMessage(), null, 400);
        }
    }

    /*=======================================================================
    | INVOICE TEMPLATE SETTINGS ENDPOINTS
    |=======================================================================*/

    /*-----------------------------------------------------------------------
    | GET INVOICE SETTINGS
    | GET /api/get_invoice_settings
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function get_invoice_settings(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

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

        $settings['signature_url'] = !empty($settings['signature_image'])
            ? base_url('uploads/signatures/' . $settings['signature_image'])
            : null;

        $this->send_response(true, 'Invoice settings fetched successfully.', $settings);
    }

    /*-----------------------------------------------------------------------
    | SAVE INVOICE SETTINGS
    | POST /api/save_invoice_settings
    | Headers: Authorization: Bearer <token>
    | Body: { "template_id", "primary_color", "accent_color" }
    |-----------------------------------------------------------------------*/
    public function save_invoice_settings(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $templateId   = (int) $this->input_value('template_id', '1');
        $primaryColor = $this->input_value('primary_color', '#0f172a');
        $accentColor  = $this->input_value('accent_color', '#2563eb');

        if ($templateId < 1) {
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
            ->count_all_results('invoice_settings') > 0;

        $settingsData = [
            'template_id'   => $templateId,
            'primary_color' => $primaryColor,
            'accent_color'  => $accentColor,
        ];

        if ($exists) {
            $this->db->where('admin_id', $adminId)->update('invoice_settings', $settingsData);
        } else {
            $settingsData['admin_id'] = $adminId;
            $this->db->insert('invoice_settings', $settingsData);
        }

        $settings = $this->db
            ->where('admin_id', $adminId)
            ->get('invoice_settings')
            ->row_array();

        $settings['signature_url'] = !empty($settings['signature_image'])
            ? base_url('uploads/signatures/' . $settings['signature_image'])
            : null;

        $this->send_response(true, 'Invoice settings updated successfully.', $settings);
    }

    /*-----------------------------------------------------------------------
    | UPLOAD SIGNATURE
    | POST /api/upload_signature
    | Headers: Authorization: Bearer <token>
    | Files: signature_image
    |-----------------------------------------------------------------------*/
    public function upload_signature(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        if (empty($_FILES['signature_image']['name'])) {
            $this->send_response(false, 'signature_image file is required.', null, 400);
        }

        $uploadPath = FCPATH . 'uploads/signatures/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $config = [
            'upload_path'   => $uploadPath,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 512,
            'file_name'     => 'sig_' . $adminId . '_' . time()
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('signature_image')) {
            $this->send_response(false, strip_tags($this->upload->display_errors('', '')), null, 400);
        }

        $uploadData = $this->upload->data();
        $fileName = $uploadData['file_name'];

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
            ->count_all_results('invoice_settings') > 0;

        if ($exists) {
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

        $settings = $this->db
            ->where('admin_id', $adminId)
            ->get('invoice_settings')
            ->row_array();

        $settings['signature_url'] = base_url('uploads/signatures/' . $fileName);

        $this->send_response(true, 'Signature uploaded successfully.', $settings);
    }

    /*-----------------------------------------------------------------------
    | REMOVE SIGNATURE
    | POST /api/remove_signature
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function remove_signature(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

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

        $settings['signature_url'] = null;

        $this->send_response(true, 'Signature removed successfully.', $settings);
    }

    /*-----------------------------------------------------------------------
    | GET INVOICE TEMPLATES
    | GET /api/get_invoice_templates
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function get_invoice_templates(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        $defaultTemplates = [
            [
                'id' => 1,
                'name' => 'Minimalist Slate',
                'description' => 'A clean, typography-focused template suited for professional billing.',
                'primaryColor' => '#4F46E5',
                'accentColor' => '#6366F1',
                'textColor' => '#1F2937',
                'font' => 'Poppins',
                'layout' => 'minimal',
                'logoPosition' => 'left',
                'thumbnail' => 'minimal',
                'isCustom' => false
            ],
            [
                'id' => 2,
                'name' => 'Professional Teal',
                'description' => 'A structured classic theme with a prominent teal banner and clean grids.',
                'primaryColor' => '#0D9488',
                'accentColor' => '#0F766E',
                'textColor' => '#1F2937',
                'font' => 'Inter',
                'layout' => 'classic',
                'logoPosition' => 'right',
                'thumbnail' => 'classic',
                'isCustom' => false
            ],
            [
                'id' => 3,
                'name' => 'Modern Coral',
                'description' => 'Vibrant, styled headers with unique design touches and angled accents.',
                'primaryColor' => '#F43F5E',
                'accentColor' => '#E11D48',
                'textColor' => '#1F2937',
                'font' => 'Outfit',
                'layout' => 'creative',
                'logoPosition' => 'left',
                'thumbnail' => 'creative',
                'isCustom' => false
            ],
            [
                'id' => 4,
                'name' => 'Compact Retail',
                'description' => 'Highly dense layout optimized to fit maximum line items on a single sheet.',
                'primaryColor' => '#1E293B',
                'accentColor' => '#334155',
                'textColor' => '#1F2937',
                'font' => 'Roboto',
                'layout' => 'compact',
                'logoPosition' => 'left',
                'thumbnail' => 'compact',
                'isCustom' => false
            ],
            [
                'id' => 5,
                'name' => 'Elegant Indigo',
                'description' => 'Features indigo-to-cyan gradient headers for modern businesses and tech firms.',
                'primaryColor' => '#4F46E5',
                'accentColor' => '#06B6D4',
                'textColor' => '#1F2937',
                'font' => 'Poppins',
                'layout' => 'gradient',
                'logoPosition' => 'left',
                'thumbnail' => 'gradient',
                'isCustom' => false
            ],
            [
                'id' => 6,
                'name' => 'Thermal Receipt',
                'description' => 'A 80mm dotted roll theme ideal for quick retail checkouts and point-of-sale bills.',
                'primaryColor' => '#374151',
                'accentColor' => '#6B7280',
                'textColor' => '#1F2937',
                'font' => 'Courier New',
                'layout' => 'receipt',
                'logoPosition' => 'left',
                'thumbnail' => 'receipt',
                'isCustom' => false
            ]
        ];

        $dbCustom = $this->db
            ->where('admin_id', $adminId)
            ->get('invoice_templates')
            ->result_array();

        foreach ($dbCustom as $row) {
            $defaultTemplates[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'primaryColor' => $row['primary_color'],
                'accentColor' => $row['accent_color'],
                'textColor' => $row['text_color'],
                'font' => $row['font'],
                'layout' => $row['layout'],
                'logoPosition' => $row['logo_position'],
                'thumbnail' => $row['layout'],
                'isCustom' => true
            ];
        }

        $this->send_response(true, 'Invoice templates fetched successfully.', $defaultTemplates);
    }

    /*-----------------------------------------------------------------------
    | ADD INVOICE TEMPLATE
    | POST /api/add_invoice_template
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function add_invoice_template(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $name = trim((string) $this->input_value('name'));
        $description = trim((string) $this->input_value('description'));
        $primaryColor = trim((string) $this->input_value('primaryColor', '#4F46E5'));
        $accentColor = trim((string) $this->input_value('accentColor', '#10B981'));
        $textColor = trim((string) $this->input_value('textColor', '#1F2937'));
        $font = trim((string) $this->input_value('font', 'Poppins'));
        $layout = trim((string) $this->input_value('layout', 'minimal'));
        $logoPosition = trim((string) $this->input_value('logoPosition', 'left'));

        if (empty($name)) {
            $this->send_response(false, 'Template name is required.', null, 400);
        }

        $insertData = [
            'admin_id' => $adminId,
            'name' => $name,
            'description' => $description,
            'primary_color' => $primaryColor,
            'accent_color' => $accentColor,
            'text_color' => $textColor,
            'font' => $font,
            'layout' => $layout,
            'logo_position' => $logoPosition,
            'is_custom' => 1
        ];

        $this->db->insert('invoice_templates', $insertData);
        $insertId = $this->db->insert_id();

        $inserted = [
            'id' => $insertId,
            'name' => $name,
            'description' => $description,
            'primaryColor' => $primaryColor,
            'accentColor' => $accentColor,
            'textColor' => $textColor,
            'font' => $font,
            'layout' => $layout,
            'logoPosition' => $logoPosition,
            'thumbnail' => $layout,
            'isCustom' => true
        ];

        $this->send_response(true, 'Custom template created successfully.', $inserted);
    }

    /*-----------------------------------------------------------------------
    | DELETE INVOICE TEMPLATE
    | POST /api/delete_invoice_template
    | Headers: Authorization: Bearer <token>
    |-----------------------------------------------------------------------*/
    public function delete_invoice_template(): void
    {
        $this->require_method('POST');
        $adminId = $this->require_token_user_id();

        $id = (int) $this->input_value('id');

        $exists = $this->db
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->where('is_custom', 1)
            ->count_all_results('invoice_templates') > 0;

        if (!$exists) {
            $this->send_response(false, 'Template not found or not customizable.', null, 404);
        }

        $this->db
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->delete('invoice_templates');

        $this->send_response(true, 'Custom template deleted successfully.', ['id' => $id]);
    }

    /*-----------------------------------------------------------------------
    | GET DASHBOARD DATA
    | GET /api/dashboard
    | Headers: Authorization: Bearer <token>
    | Query params: filter (day/week/month/year), month_picker (YYYY-MM), year_picker (YYYY)
    |-----------------------------------------------------------------------*/
    public function dashboard(): void
    {
        $this->require_method('GET');
        $adminId = $this->require_token_user_id();

        // ── Read filter inputs ─────────────────────────────────────────────
        $filter      = trim((string) ($this->input_value('filter')));
        $monthPicker = trim((string) ($this->input_value('month_picker'))); // "YYYY-MM"
        $yearPicker  = (int) ($this->input_value('year_picker'));

        if (!in_array($filter, ['day', 'week', 'month', 'year'], true)) {
            $filter = 'day';
        }

        // Resolve active mode
        if (!empty($monthPicker) && preg_match('/^\d{4}-\d{2}$/', $monthPicker)) {
            $activeMode = 'custom_month';
        } elseif ($yearPicker >= 2000 && $yearPicker <= 2100) {
            $activeMode = 'custom_year';
        } else {
            $activeMode = $filter;
        }

        // ── Product count (always total active — no date filter) ───────────
        $productCount = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->count_all_results('product');

        // ── Customer count (filtered) ──────────────────────────────────────
        $custQ = $this->db->where('admin_id', $adminId)->where('isActive', 1);
        $this->apply_date_filter($custQ, $activeMode, 'created_on', $monthPicker, $yearPicker);
        $customerCount = $custQ->count_all_results('customers');

        // ── Total Sales — TODAY only (never changes with filter) ───────────
        $todaySalesRow   = $this->db
            ->select('SUM(grand_total) AS total_sales', false)
            ->from('sales')
            ->where('admin_id', $adminId)
            ->where('DATE(sale_date)', date('Y-m-d'))
            ->get()->row_array();
        $todayTotalSales = (float) ($todaySalesRow['total_sales'] ?? 0);

        // ── Total Profit — TODAY only (never changes with filter) ──────────
        $todayProfitRow = $this->db
            ->select('SUM(si.line_total - (p.purchase_price * si.quantity)) AS profit', false)
            ->from('sale_items si')
            ->join('sales s', 's.id = si.sale_id')
            ->join('product p', 'p.id = si.product_id', 'left')
            ->where('s.admin_id', $adminId)
            ->where('DATE(s.sale_date)', date('Y-m-d'))
            ->get()->row_array();

        $todayTotalProfit = (float) ($todayProfitRow['profit'] ?? 0);

        // ── Total Purchase (filtered) ──────────────────────────────────────
        $purchQ = $this->db
            ->select('SUM(purchase_price * quantity) AS total_purchase', false)
            ->where('admin_id', $adminId)
            ->where('isActive', 1);
        $this->apply_date_filter($purchQ, $activeMode, 'created_on', $monthPicker, $yearPicker);
        $purchRow              = $purchQ->get('product')->row_array();
        $filteredTotalPurchase = (float) ($purchRow['total_purchase'] ?? 0);

        // ── Low Stock (always qty <= 5 — no date filter) ───────────────────
        $lowStockProducts = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->where('quantity <=', 5)
            ->order_by('quantity', 'ASC')
            ->order_by('id', 'DESC')
            ->get('product')
            ->result_array();

        foreach ($lowStockProducts as &$prod) {
            $prod['image'] = $this->resolve_product_image($prod['image'] ?? '');
        }

        // ── Recent Sales (always latest 5) ────────────────────────────────
        $recentSales = $this->db
            ->select('sales.invoice_number, sales.grand_total, sales.sale_date, customers.name AS customer_name')
            ->from('sales')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.admin_id', $adminId)
            ->order_by('sales.id', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();

        // ── Human-readable filter label ────────────────────────────────────
        $filterLabels = [
            'day'   => 'Today',
            'week'  => 'This Week',
            'month' => 'This Month',
            'year'  => 'This Year',
        ];

        if ($activeMode === 'custom_month' && !empty($monthPicker)) {
            $filterLabel = date('F Y', strtotime($monthPicker . '-01'));
        } elseif ($activeMode === 'custom_year' && $yearPicker) {
            $filterLabel = 'Year ' . $yearPicker;
        } else {
            $filterLabel = $filterLabels[$filter] ?? 'Today';
        }

        // ── Chart Data ─────────────────────────────────────────────────────
        // ── Sales per day ──────────────────────────────────────────────────
        $sQ = $this->db->from('sales')->where('admin_id', $adminId);
        $this->apply_date_filter($sQ, $activeMode, 'sale_date', $monthPicker, $yearPicker);
        $salesRows = $sQ
            ->select('DATE(sale_date) AS date, SUM(grand_total) AS total')
            ->group_by('DATE(sale_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result_array();

        // ── Profit per day ─────────────────────────────────────────────────
        $pQ = $this->db
            ->from('sale_items si')
            ->join('sales s', 's.id = si.sale_id')
            ->where('s.admin_id', $adminId);
        $this->apply_date_filter($pQ, $activeMode, 's.sale_date', $monthPicker, $yearPicker);
        $profitRows = $pQ
            ->join('product p', 'p.id = si.product_id', 'left')
            ->select('DATE(s.sale_date) AS date, 
                SUM(si.line_total - (p.purchase_price * si.quantity)) AS profit')
            ->group_by('DATE(s.sale_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result_array();

        // Index profit by date for fast lookup
        $profitByDate = [];
        foreach ($profitRows as $row) {
            $profitByDate[$row['date']] = (float) $row['profit'];
        }

        $labels = $salesValues = $profitValues = [];
        foreach ($salesRows as $row) {
            $labels[]       = date('d M', strtotime($row['date']));
            $salesValues[]  = (float) $row['total'];
            $profitValues[] = $profitByDate[$row['date']] ?? 0.0;
        }

        $chartData = [
            'labels'       => $labels,
            'sales_values' => $salesValues,
            'profit_values'=> $profitValues,
            'total_sales'  => array_sum($salesValues),
            'total_profit' => array_sum($profitValues),
        ];

        // ── Final output structure ─────────────────────────────────────────
        $response = [
            'filter'                  => $filter,
            'active_mode'             => $activeMode,
            'filter_label'            => $filterLabel,
            'month_picker'            => $monthPicker,
            'year_picker'             => $yearPicker ?: null,
            'product_count'           => $productCount,
            'customer_count'          => $customerCount,
            'today_total_sales'       => $todayTotalSales,
            'today_total_profit'      => $todayTotalProfit,
            'filtered_total_purchase' => $filteredTotalPurchase,
            'low_stock_count'         => count($lowStockProducts),
            'low_stock_products'      => $lowStockProducts,
            'recent_sales'            => $recentSales,
            'chart_data'              => $chartData,
        ];

        $this->send_response(true, 'Dashboard data fetched successfully.', $response);
    }

    private function apply_date_filter($q, string $mode, string $col, string $monthPicker = '', int $yearPicker = 0): void
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
        }
    }
}
