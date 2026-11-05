<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['wa_official_enabled']  = TRUE;
$config['wa_official_token']    = 'EAAOVQGlfCXYBS1Y9Poqw7J3BkGpycV69E6ZAocEH0b6JIxz4AXtlNNAGjdJVn4WQQZCXsEkwEhNXKlmDRtkwlQSpZCNYbHsMrxBVregFCcft9qjnBtSaivsZAoUBSzDngjrLoKrUATEsqX8XJgkUvBtLhSOlU9KKXMxpA0FVCj21ZCZBvPdfsQAhHGpYRDmrZB7CuBX2OTdJJJjNiE2nEfKHgJOIgRZBq9Bodh91';
$config['wa_official_phone_id'] = '1376712872198728';
$config['wa_official_version']  = 'v21.0';
$config['wa_official_template'] = 'invoice_preview_v1_'; 
$config['wa_official_lang']     = 'en';
$config['wa_default_header_img'] = 'https://billbook.visiontechnolabs.com/uploads/default_invoice.jpg';

// Random string, used to sign the "Touch With Us" button links
$config['wa_token_secret']      = 'aZ9#kL72@pQx!8mN4$vR6*TyW3&cB1_fH5+JdK9%uE2^sX7!nP4@qM8#vL3&zR6_Ab1*Yt9$wQ5!cD2^kF8@pN7%hJ4_mX6+eT3#rV9&bK1!sP5@dW8^qL2*zM7';

// Base URL of the call page (must match the template's button URL)
$config['wa_call_base']         = 'https://billbook.visiontechnolabs.com/c/';