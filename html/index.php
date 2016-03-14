<?php
//die("Pagina en mantenci&oacute;n");

/*if (
	!isset($_SERVER['PHP_AUTH_USER'])
	|| !isset($_SERVER['PHP_AUTH_PW'])	
) 
{
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso denegado';
    exit;
} 
else 
{
	if ($_SERVER['PHP_AUTH_USER'] != "netred" || $_SERVER['PHP_AUTH_PW'] != "portillo")
	{
		echo "Pagina en mantenci&oacute;n";
		exit;
	}
}
*/
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));




set_include_path(
APPLICATION_PATH.'/../library'.PATH_SEPARATOR.
APPLICATION_PATH.'/../library/Zend'
);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap()
            ->run();

/*$frontController->setParam('useDefaultControllerAlways', true);
$frontController->dispatch();*/
            
