<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "sites";
$route['404_override'] = 'builder404';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = "auth/login";
$route['logout'] = "auth/logout";

$route['sites/([0-9]+?)'] = "sites/site/$1";
$route['sites/([0-9]+?)/([0-9]+?)/([0-9]+?)'] = "sites/site/$1/$2/$3";

$route['sites/create/([0-9]+?)/([0-9]+?)'] = "sites/create/$1/$2";

$route['preview/([0-9]+?)'] = "preview/index/$1";

$route['site/([a-zA-Z0-9]+?)'] = "temple/preview/$1";

$route['settings'] = "configuration/index";

$route['temple/([0-9]+?)'] = "temple/index/$1";

$route['authlogin'] = "login/login";

$route['cwp'] = "login/update";


/* End of file routes.php */
/* Location: ./application/config/routes.php */