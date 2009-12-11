<?php
define(APPPATH,dirname(__file__) . '/../../');
require_once('bootstrap.php');

$config = array('data_fixtures_path' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixtures',
				'models_path'		=>	APPPATH . 'models',
				'migrations_path'	=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'migrations',
				'sql_path'			=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sql',
				'yaml_schema_path'	=>	dirname(__FILE__) . DIRECTORY_SEPARATOR . 'schema',
);

//Doctrine::generateModelsFromDb();

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);
?>
