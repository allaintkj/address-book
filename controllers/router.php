<?php

/**
 * Route incoming requests to appropriate controller
 */
spl_autoload_register(function($className) {
    // automatically include classes
    list($filename , $suffix) = explode('_' , $className);
    $file = SERVER_ROOT . '/models/' . strtolower($filename) . '.php';

    if (file_exists($file)) {
        include_once($file);
    } else {
        die("File '$filename' containing class '$className' not found.");
    }
});

// http://domain.com/index.php?login&key=value&key=value...
// string sanitize everything after '?'
$request = filter_var($_SERVER['QUERY_STRING'], FILTER_SANITIZE_STRING);
$parsed = explode('&', $request);
// index.php?login -- 'login' is page
$page = array_shift($parsed);
$gets = array();
$target = SERVER_ROOT . '/controllers/' . $page . '.php';

// parse rest of request
foreach ($parsed as $get) {
    // &key=value&key=value...
    list($key, $value) = explode('=', $get);
    $gets[$key] = $value;
}

// get target
if (file_exists($target)) {
    include_once($target);

    // modify page string to fit class naming convention
    $class = ucfirst($page) . '_Controller';

    // instantiate the appropriate class
    if (class_exists($class)) {
        $controller = new $class;
    } else {
        die('class does not exist!');
    }
} else {
    include_once('controllers/login.php');
    $controller = new Login_Controller();

    if (isset($_POST)) {
        $controller->main($_POST);
        die();
    }
}

// execute default function
// pass any GET varaibles to the main method
$controller->main($gets);

?>
