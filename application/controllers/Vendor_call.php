<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_call extends CI_Controller
{
    public function go($token = '')
    {
        $this->load->library('whatsapp_sender');

        $id = $this->whatsapp_sender->parse_token($token);
        if (!$id) show_404();

        // Change 'id' if your users table primary key has a different name
        $row   = $this->db->select('mobile')->where('id', $id)->get('users')->row_array();
        $phone = $row ? $this->whatsapp_sender->normalize_phone($row['mobile']) : '';
        if ($phone === '') show_404();

        $tel = 'tel:+' . $phone;
        header('X-Robots-Tag: noindex');
        ?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Call Us</title>
<style>
body{font-family:Arial,sans-serif;display:flex;min-height:100vh;align-items:center;justify-content:center;background:#f5f7fa;margin:0}
.box{text-align:center;padding:24px}
a.btn{display:inline-block;background:#25D366;color:#fff;font-size:20px;font-weight:700;padding:16px 36px;border-radius:50px;text-decoration:none}
p{color:#555}
</style>
</head><body>
<div class="box">
  <p>Tap the button to call us</p>
  <a class="btn" href="<?= htmlspecialchars($tel) ?>">📞 Call Now</a>
</div>
<script>window.location.href = <?= json_encode($tel) ?>;</script>
</body></html>
        <?php
    }
}