<?php

// require db config here
$db_config = require_once('config.php');

// include db config in constants
// makes connections easier to manage
define('APP_TITLE', 'PHP Contact Management System');
define('DB_CONN', 'mysql:host=' . $db_config->host . ';dbname=' . $db_config->db);
define('DB_PASS', $db_config->password);
define('DB_USER', $db_config->username);
define('SERVER_ROOT', stream_resolve_include_path('./'));
define('SITE_ROOT', 'http://school.localhost/php/project.1/src/');

// start session
session_start();

// require router
require_once(SERVER_ROOT . '/controllers/router.php');

?>
