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
|	http://codeigniter.com/user_guide/general/routing.html
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
// if(FALSE) // closed
// {
// 	$route['default_controller'] = 'main_page/closed';
// 	$route['404_override'] = 'main_page/closed';
// }else{

$route['default_controller'] = 'tests';
// $route['tests/get_last_news'] = 'tests/get_last_news';
$route['404_override'] = 'api/error_404';
$route['404'] = 'api/error_404';
$route['error'] = 'main_page/error';
$route['tos'] = 'main_page/tos';
$route['faq'] = 'main_page/faq';
$route['top'] = 'main_page/top';

$route['news/(:num)'] = 'news/post/$1';
$route['news/(:num)/like'] = 'news/like/$1';
$route['news/(:num)/unlike'] = 'news/unlike/$1';

$route['comments/delete/(:num)'] = 'comments/delete/$1';

$route['comments/(:num)/like'] = 'comments/like/$1';
$route['comments/(:num)/unlike'] = 'comments/unlike/$1';

$route['ref(:any)'] = 'partner/bestref/$1';

$route['translate_uri_dashes'] = FALSE;

//Localization routification

$reserved = ['default_controller', '404_override', 'translate_uri_dashes'];

//$langPrefix = "(?:ru|en|fr|cn|de|pt|pl|br|es|tr|ja)/";
//
//$tmpRoute = $route;
//$route = [];
//
//foreach (array_keys($tmpRoute) as $r)
//{
//    if (in_array($r, $reserved))
//        $route[$r] = $tmpRoute[$r];
//    else
//    {
//        $route[$langPrefix . $r] = $tmpRoute[$r];
//    }
//}
//
//$route['^ru$'] = $route['default_controller'];
//$route['^en$'] = $route['default_controller'];
//
//$route['^fr$'] = $route['default_controller'];
//$route['^cn$'] = $route['default_controller'];
//$route['^de$'] = $route['default_controller'];
//$route['^pl$'] = $route['default_controller'];
//$route['^ja$'] = $route['default_controller'];
//$route['^es$'] = $route['default_controller'];
//$route['^tr$'] = $route['default_controller'];
//$route['^pt$'] = $route['default_controller'];
//$route['^br$'] = $route['default_controller'];
//
//
//$route['^ru/(.+)$'] = "$1";
//$route['^en/(.+)$'] = "$1";
//
//$route['^fr/(.+)$'] = "$1";
//$route['^cn/(.+)$'] = "$1";
//$route['^de/(.+)$'] = "$1";
//$route['^pl/(.+)$'] = "$1";
//$route['^ja/(.+)$'] = "$1";
//$route['^es/(.+)$'] = "$1";
//$route['^tr/(.+)$'] = "$1";
//$route['^pt/(.+)$'] = "$1";
//$route['^br/(.+)$'] = "$1";



$route['admin/account'] = 'admin/user_list/1';
$route['admin/account/(:num)'] = 'admin/user_list/$1';
$route['admin/account/edit/(:num)'] = 'admin/user_edit/$1';
$route['admin/account/ban/(:num)'] = 'admin/user_ban/$1';
$route['admin/account/money/(:num)'] = 'admin/user_money/$1';
$route['admin/account/money/(:num)/(:num)'] = 'admin/user_money/$1/$2';
$route['admin/account/history/(:num)'] = 'admin/user_history/$1';
$route['admin/account/transaction'] = 'admin/user_transaction/$1';
$route['admin/account/transaction/(:num)'] = 'admin/user_transaction/$1';
$route['admin/account/promo/(:num)'] = 'admin/user_promo/$1';
$route['admin/account/inventory/(:num)'] = 'admin/user_inventory/$1';
$route['admin/account/login_log/(:num)'] = 'admin/login_log/$1';


