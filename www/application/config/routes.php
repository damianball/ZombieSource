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
$route['overview'] = "game_overview_controller";
$route['overview/(:any)'] = "game_overview_controller/$1";

$route['user/:num'] = "profile_controller/public_profile";
$route['team/edit/:any'] = "profile_controller/edit_team_profile";
$route['team/:any'] = "profile_controller/team_public_profile";

$route['game'] = "game_controller";
$route['game/(:any)/teams'] = "game_controller/teams";
$route['game/(:any)/stats'] = "game_controller/stats";
$route['game/(:any)/register_kill'] = "game_controller/register_kill";
$route['game/(:any)/register_new_team'] = "game_controller/register_new_team";
$route['game/(:any)/join_team'] = "game_controller/join_team";
$route['game/(:any)/leave_team'] = "game_controller/leave_team";
$route['game/(:any)'] = "game_controller";

$route['auth'] = "auth_controller";
$route['auth/(:any)'] = "auth_controller/$1";

$route['home'] = "home_controller";
$route['home/(:any)'] = "home_controller/$1";

$route['profile'] = "profile_controller";
$route['profile/(:any)'] = "profile_controller/$1";

$route['admin'] = "admin_controller";
$route['admin/(:any)'] = "admin_controller/$1";

$route['default_controller'] = "home_controller";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
