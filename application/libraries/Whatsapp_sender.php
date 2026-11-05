<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_sender
{
    private $CI;
    const GATEWAY = 'https://wa.visiontechnolabs.com';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('whatsapp_official', TRUE);
    }

    private function cfg($key)
    {
        return $this->CI->config->item($key, 'whatsapp_official');
    }

    /* ---------- Public: main entry ---------- */
    // $d = business_name, customer_name, invoice_no, amount, instagram
    // Returns: 'connected' | 'official' | 'failed' | 'skipped'
    public function send_invoice($adminId, $phone, $pdfUrl, array $d)
    {
        $phone = $this->normalize_phone($phone);
        if ($phone === '' || empty($pdfUrl)) {
            log_message('error', 'WA skipped: bad phone or missing pdf url');
            return 'skipped';
        }

        // Route 1: vendor's own connected WhatsApp
        if ($this->is_connected($adminId)) {
            if ($this->send_connected($adminId, $phone, $pdfUrl, $d)) {
                log_message('error', 'WA route: connected (admin ' . $adminId . ')');
                return 'connected';
            }
            log_message('error', 'WA connected send failed, falling back to official');
        }

        // Route 2: official Meta number
        if ($this->cfg('wa_official_enabled')) {
            if ($this->send_official($adminId, $phone, $pdfUrl, $d)) {
                log_message('error', 'WA route: official (admin ' . $adminId . ')');
                return 'official';
            }
        }

        return 'failed';
    }

    /* ---------- Connected number ---------- */
    public function is_connected($adminId)
    {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET', 'timeout' => 2, 'ignore_errors' => true
        ]]);
        $res = @file_get_contents(self::GATEWAY . '/status/' . (int)$adminId, false, $ctx);
        if ($res === false) return false;
        $data = json_decode($res, true);
        return !empty($data['connected']);
    }

    private function send_connected($adminId, $phone, $pdfUrl, array $d)
    {
        $caption = "Greetings from {$d['business_name']} 👏\n\n"
                 . "Dear {$d['customer_name']},\n"
                 . "We are pleased to have you as a valued customer.\n\n"
                 . "📄 Invoice No: *{$d['invoice_no']}*\n"
                 . "💰 Amount: *₹{$d['amount']}*\n\n"
                 . "Please find attached your invoice.\n"
                 . "Thank you for your business 🙏";

        $payload = json_encode([
            'userId'   => (string)$adminId,
            'number'   => $phone,
            'document' => $pdfUrl,
            'caption'  => $caption,
        ]);

        $ch = curl_init(self::GATEWAY . '/send-document');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_TIMEOUT        => 12,
        ]);
        $res  = curl_exec($ch);
        $err  = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err || $code < 200 || $code >= 300) {
            log_message('error', "WA Send Error (connected): code=$code err=$err res=$res");
            return false;
        }
        log_message('error', 'WA PDF Response: ' . $res);
        return true;
    }

    /* ---------- Official Meta Cloud API ---------- */
 private function send_official($adminId, $phone, $pdfUrl, array $d)
{
    $imageUrl = !empty($d['image_url']) ? $d['image_url'] : $this->cfg('wa_default_header_img');
    $pdfFile  = !empty($d['pdf_file'])  ? $d['pdf_file']  : '';

    if ($pdfFile === '' || empty($imageUrl)) {
        log_message('error', 'WA official skipped: pdf_file or image_url missing');
        return false;
    }

    $url = 'https://graph.facebook.com/' . $this->cfg('wa_official_version')
         . '/' . $this->cfg('wa_official_phone_id') . '/messages';

    $payload = [
        'messaging_product' => 'whatsapp',
        'to'   => $phone,
        'type' => 'template',
        'template' => [
            'name'     => $this->cfg('wa_official_template'),
            'language' => ['code' => $this->cfg('wa_official_lang')],
            'components' => [
                ['type' => 'header', 'parameters' => [
                    ['type' => 'image', 'image' => ['link' => $imageUrl]],
                ]],
                ['type' => 'body', 'parameters' => [
                    ['type' => 'text', 'text' => $this->clean($d['business_name'], 'Store')],
                    ['type' => 'text', 'text' => $this->clean($d['customer_name'], 'Customer')],
                    ['type' => 'text', 'text' => $this->clean($d['invoice_no'], '-')],
                    ['type' => 'text', 'text' => $this->clean($d['amount'], '0')],
                    ['type' => 'text', 'text' => $this->clean_instagram($d['instagram'])],
                ]],
                ['type' => 'button', 'sub_type' => 'url', 'index' => '0', 'parameters' => [
                    ['type' => 'text', 'text' => $pdfFile],
                ]],
                ['type' => 'button', 'sub_type' => 'url', 'index' => '1', 'parameters' => [
                    ['type' => 'text', 'text' => $this->make_token($adminId)],
                ]],
            ],
        ],
    ];
log_message('error', 'WA official img=' . $imageUrl . ' pdf=' . $pdfFile);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $this->cfg('wa_official_token'),
            'Content-Type: application/json',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $res  = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode($res, true);
    if ($err || $code < 200 || $code >= 300 || empty($json['messages'][0]['id'])) {
        log_message('error', "WA Official Error: code=$code err=$err res=$res");
        return false;
    }
    log_message('error', 'WA Official OK: ' . $json['messages'][0]['id']);
    return true;
}
public function make_preview_image($pdfPath, $outBasePath)
{
    if (!function_exists('exec') || !is_file($pdfPath)) return false;
    $cmd = 'pdftoppm -jpeg -r 80 -f 1 -l 1 -singlefile '
         . escapeshellarg($pdfPath) . ' ' . escapeshellarg($outBasePath) . ' 2>&1';
    exec($cmd, $out, $rc);
    $jpg = $outBasePath . '.jpg';
    if ($rc !== 0 || !is_file($jpg)) {
        log_message('error', 'WA preview failed rc=' . $rc . ' ' . implode(' ', $out));
        return false;
    }
    return $jpg;
}
    /* ---------- Helpers ---------- */
    public function normalize_phone($phone)
    {
        $raw = preg_replace('/\D/', '', (string)$phone);
        if (strlen($raw) === 10) return '91' . $raw;
        if (strlen($raw) === 12 && substr($raw, 0, 2) === '91') return $raw;
        return '';
    }

    // Meta rejects empty values, newlines, tabs and 4+ consecutive spaces
  private function clean($v, $fallback = '-')
{
    if (is_array($v) || is_object($v)) $v = '';
    $v = preg_replace('/[\r\n\t]+/', ' ', (string)$v);
    $v = trim(preg_replace('/ {2,}/', ' ', $v));
    return $v === '' ? $fallback : $v;
}
    private function clean_instagram($v)
    {
        $v = trim((string)$v);
        $v = preg_replace('#^https?://(www\.)?instagram\.com/#i', '', $v);
        $v = trim(ltrim($v, '@'), '/ ');
        return $v === '' ? 'instagram' : $v;
    }

    /* ---------- Signed token for the "Touch With Us" button ---------- */
    public function make_token($adminId)
    {
        $id = (int)$adminId;
        return $id . '-' . substr(hash_hmac('sha256', (string)$id, $this->cfg('wa_token_secret')), 0, 10);
    }

    public function parse_token($token)
{
    $token = urldecode((string)$token);
    $token = str_replace('{{1}}', '', $token);   // tolerate the bad template URL

    $parts = explode('-', $token);
    if (count($parts) !== 2 || !ctype_digit($parts[0])) return 0;
    $expected = substr(hash_hmac('sha256', $parts[0], $this->cfg('wa_token_secret')), 0, 10);
    return hash_equals($expected, $parts[1]) ? (int)$parts[0] : 0;
}
}