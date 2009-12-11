<?php
define(APPPATH,'/var/www/public_jobs/application/');
require_once('../application/vendor/doctrine/bootstrap.php');

$config = array('data_fixtures_path' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixtures',
				'models_path'		=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . '../application/models',
				'migrations_path'	=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'migrations',
				'sql_path'			=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sql',
				'yaml_schema_path'	=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'schema',
);

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);
?>
