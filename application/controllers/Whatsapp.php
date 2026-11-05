<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'core/My_Controller.php');

class Whatsapp extends My_Controller {

    // ✅ GET QR
 public function qr($userId = null)
{
    if (!$userId) {
        $userId = (int) ($this->admin['user_id'] ?? 0);
    }

    $url = "https://wa.visiontechnolabs.com/qr/" . $userId;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        log_message('error', 'WA QR Curl Error: ' . curl_error($ch));
        echo json_encode(['status' => false, 'qr' => ""]);
        curl_close($ch);
        return;
    }

    curl_close($ch);

    echo $response;
}

    // ✅ GET REAL STATUS FROM NODE
    public function status($userId = null)
    {
        if (!$userId) {
            $userId = (int) ($this->admin['user_id'] ?? 0);
        }

        $url = "https://wa.visiontechnolabs.com/status/" . $userId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'WA Status Curl Error: ' . curl_error($ch));
            echo json_encode(['status' => false, 'connected' => false]);
            curl_close($ch);
            return;
        }

        curl_close($ch);

        echo $response;
    }

    // ✅ LOGOUT
    public function logout($userId = null)
    {
        if (!$userId) {
            $userId = (int) ($this->admin['user_id'] ?? 0);
        }

        if (!$userId) {
            echo json_encode(['status' => false, 'msg' => 'Invalid user']);
            return;
        }

        $url = "https://wa.visiontechnolabs.com/logout/" . $userId;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'WA Logout Curl Error: ' . curl_error($ch));
            echo json_encode(['status' => false]);
            curl_close($ch);
            return;
        }

        curl_close($ch);

        log_message('error', 'WA Logout Response: ' . $response);

        echo $response;
    }
}