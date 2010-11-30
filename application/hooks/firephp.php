<?php 
// Procedural API
//require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "../libraries/FirePHP/fb.php");
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "../libraries/FirePHPCore/fb.php");
//require_once('phar://firephp.phar/FirePHP/fb.php');

// Object-Oriented API
//require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "../libraries/FirePHP/Init.php");
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "../libraries/FirePHPCore/FirePHP.class.php");
//require_once('phar://firephp.phar/FirePHP/Init.php');

// Configure FirePHP
define('INSIGHT_IPS', '*');
define('INSIGHT_AUTHKEYS', 'FA4B8AC4826D9DB42B0A8C2D9EC7FD2D');
define('INSIGHT_PATHS', __DIR__);
define('INSIGHT_SERVER_PATH', '/index.php'); // assumes /index.php exists on your hostname
// NOTE: Based on this configuration /index.php MUST include FirePHP

