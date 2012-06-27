Code for zombie game.

Installation instructions (work in progress)
//obvious
Install mysql
Install a web server (I use MAMP for mac)
Clone ZombieSource repo to the root of your webserver

//not so obvious
run /www/db/ddl.sql on your local mysql, this will create an empty database zsource_dev
edit /www/application/config.php so that $config['base_url'] is set to the path to your zombie installation
  e.g. the default on master is $config['base_url']  = 'http://localhost:8888/ZombieSource/www/';
  but I change mine to $config['base_url']  = 'http://localhost:8888/www/';

ddls with actual user data for testing can be obtained on request if we like you.

edit /www/application/database.php with the login credentials for your local mysql installation.
  Probably something like this
  $db['default']['hostname'] = '127.0.0.1';
  $db['default']['username'] = 'root';
  $db['default']['password'] = 'root';
  $db['default']['database'] = 'zsource_dev';
  $db['default']['dbdriver'] = 'mysql';
  $db['default']['port'] = 8889; // note the port addition, that's not in the config by default

Right now our workflow is NOT to commit the changes to either of these files. ever. Keep them local, everyone will have different settings.

Work on your own branch, do not push to master without a code review. Instead do a pull request from your branch to master on github.
