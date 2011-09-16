<?php
$scriptExecutiontime = microtime(true);
define('iNH_EXEC', true);
define('APP_PATH', dirname(__FILE__).'/');

// load the configuration
require 'inc/config.php';
// load default set of functions
require 'inc/functions.php';
// load the Zend's Framework autoloader
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
// Auto-incarcam alte resurse
$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    'namespace' => '',
    'basePath' => APP_PATH
));
// Auto-incarcare modele
$resourceLoader->addResourceType('model', 'models/', 'Model');
$resourceLoader->addResourceType('controller', 'controllers/', 'Controller');

// email setup
$mailTransport = new Zend_Mail_Transport_Smtp(
	'smtp.googlemail.com', 	
	array(
        'auth'     => 'login',
        'username' => 'web@toxik.us',
        'password' => 'oparolaminunata',
        'ssl'      => 'tls',
        'port' => 587
    )
);
Zend_Mail::setDefaultTransport($mailTransport);
Zend_Mail::setDefaultFrom('web@toxik.us', 'php.toxik.us');
Zend_Mail::setDefaultReplyTo('alex@navigheaza.ro', 'Alexandru Georoceanu');

// deschidem o sesiune
//session_start();

// setam management-ul erorilor
if ($_SERVER['APPLICATION_ENV'] == 'development') {
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors', 'stdout');
} else 
	error_reporting(0);

// load the routing system
require 'inc/router.php';

// time the app
$scriptExecutiontime = microtime(true) - $scriptExecutiontime ;
if (!isset($page['noMeasure']) && !isset($page['barebone']))
	echo '<!-- timp executie: ' . $scriptExecutiontime . ' sec-->';
