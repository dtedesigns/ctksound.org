<?php

// bootstrap.php

/**
 * Bootstrap Doctrine.php, register autoloader specify
 * configuration attributes and load models.
 */
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Doctrine.php');
define('DOCTRINE',dirname(__FILE__));
define('DOCTRINE_MODELS',APPPATH . 'models');

spl_autoload_register(array('Doctrine', 'autoload'));
$manager = Doctrine_Manager::getInstance();

$dsn = 'mysql:dbname=sermons;host=127.0.0.1';
$user = 'sermons';
$password = 'sermons';

$dbh = new PDO($dsn, $user, $password);
$conn = Doctrine_Manager::connection($dbh);

$conn->setOption('username', $user);
$conn->setOption('password', $password);

//Doctrine::generateModelsFromDb('models', array('kohana'), array('generateTableClasses' => true));

$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
$manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);

// Enable DQL Callbacks (required for some behaviors to work)
$manager->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);

Doctrine::loadModels(APPPATH . 'models/generated');
Doctrine::loadModels(APPPATH . 'models');


