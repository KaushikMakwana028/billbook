<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';

// $route['login'] = 'login/index';
$route['register'] = 'login/register';
$route['sign-up'] = 'login/sign_up';
$route['logout'] = 'login/logout';
$route['dashboard'] = 'dashboard/index';
$route['profile'] = 'profile/index';
$route['profile/update'] = 'profile/update';
$route['category'] = 'category/index';
$route['category/store'] = 'category/store';
$route['category/edit/(:num)'] = 'category/edit/$1';
$route['category/update/(:num)'] = 'category/update/$1';
$route['category/delete/(:num)'] = 'category/delete/$1';
$route['product'] = 'product/index';
$route['product/add'] = 'product/add';
$route['product/store'] = 'product/store';
$route['product/edit/(:num)'] = 'product/edit/$1';
$route['product/update/(:num)'] = 'product/update/$1';
$route['product/delete/(:num)'] = 'product/delete/$1';
$route['sales'] = 'sales/index';
$route['sales/add'] = 'sales/add';
$route['sales/store'] = 'sales/store';
$route['sales/customer-lookup'] = 'sales/customer_lookup';
$route['sales/download/(:num)'] = 'sales/download/$1';
$route['whatsapp-connect'] = 'sales/whatsapp_connect';
$route['whatsapp/logout/(:num)'] = 'whatsapp/logout/$1';
$route['c/(:any)'] = 'vendor_call/go/$1';

// API Routes
$route['api/send-otp'] = 'api/send_otp';
$route['api/resend-otp'] = 'api/resend_otp';
$route['api/login'] = 'api/login';
$route['api/register'] = 'api/register';
$route['api/register-verify-otp'] = 'api/register_verify_otp';
$route['api/verify-otp'] = 'api/verify_otp';
$route['api/logout'] = 'api/logout';
$route['api/dashboard'] = 'api/dashboard';
$route['api/get-my-profile'] = 'api/get_my_profile';
$route['api/update-my-profile'] = 'api/update_my_profile';
$route['api/get-category-list'] = 'api/get_category_list';
$route['api/get-category-detail'] = 'api/get_category_detail';
$route['api/get-products-by-category'] = 'api/get_products_by_category';
$route['api/get-product-list'] = 'api/get_product_list';
$route['api/get-product-detail'] = 'api/get_product_detail';
$route['api/add-category'] = 'api/add_category';
$route['api/update-category'] = 'api/update_category';
$route['api/delete-category'] = 'api/delete_category';
$route['api/add-product'] = 'api/add_product';
$route['api/update-product'] = 'api/update_product';
$route['api/delete-product'] = 'api/delete_product';
$route['api/generate-barcode'] = 'api/generate_barcode';
$route['api/get-barcode-sheet'] = 'api/get_barcode_sheet';
$route['api/customer-lookup'] = 'api/customer_lookup';
$route['api/get-parties-list'] = 'api/get_parties_list';
$route['api/add-party'] = 'api/add_party';
$route['api/update-party'] = 'api/update_party';
$route['api/delete-party'] = 'api/delete_party';
$route['api/get-sales-list'] = 'api/get_sales_list';
$route['api/get-sale-detail'] = 'api/get_sale_detail';
$route['api/add-sale'] = 'api/add_sale';
$route['api/update-sale'] = 'api/update_sale';
$route['api/delete-sale'] = 'api/delete_sale';
$route['api/get-invoice-settings'] = 'api/get_invoice_settings';
$route['api/save-invoice-settings'] = 'api/save_invoice_settings';
$route['api/upload-signature'] = 'api/upload_signature';
$route['api/remove-signature'] = 'api/remove_signature';
$route['api/get-invoice-templates'] = 'api/get_invoice_templates';
$route['api/add-invoice-template'] = 'api/add_invoice_template';
$route['api/delete-invoice-template'] = 'api/delete_invoice_template';
$route['api/whatsapp-status'] = 'api/whatsapp_status';
$route['api/whatsapp/status'] = 'api/whatsapp_status';
$route['api/whatsapp-qr'] = 'api/whatsapp_qr';
$route['api/whatsapp/qr'] = 'api/whatsapp_qr';
$route['api/whatsapp-logout'] = 'api/whatsapp_logout';
$route['api/whatsapp/logout'] = 'api/whatsapp_logout';
$route['api/send-invoice-whatsapp'] = 'api/send_invoice_whatsapp';
$route['api/send_invoice_whatsapp'] = 'api/send_invoice_whatsapp';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

