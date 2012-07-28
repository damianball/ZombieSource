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
$route['overview'] = "Game_overview_controller";
$route['user/:num'] = "Profile_controller/public_profile";
$route['team/edit/:any'] = "Profile_controller/edit_team_profile";
$route['team/:any'] = "Profile_controller/team_public_profile";
$route['team/new'] = "Game_controller/register_new_team";
$route['game/teams'] = "Game_controller/teams";

$route['game'] = "Game_controller";
$route['game/(:any)'] = "Game_controller";

$route['auth'] = "Auth_controller";
$route['auth/(:any)'] = "Auth_controller/$1";

$route['home'] = "Home_controller";
$route['home/(:any)'] = "Home_controller/$1";

$route['profile'] = "Profile_controller";
$route['profile/(:any)'] = "Profile_controller/$1";

$route['admin'] = "Admin_controller";
$route['admin/(:any)'] = "Admin_controller/$1";

$route['default_controller'] = "Home_controller";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */