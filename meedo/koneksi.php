<?php

use Medoo\Medoo;
use appclas\AppConfig;

AppConfig::$DATABASE = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => AppConfig::$DB_NAME,
	'server' => AppConfig::$DB_HOST,
	'username' => AppConfig::$DB_USERNAME,
	'password' => AppConfig::$DB_PASSWORD,
	'charset' => 'utf8',

 	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	],

	// [optional] Medoo will execute those commands after connected to the database for initialization
	'command' => [
		'SET SQL_MODE=ANSI_QUOTES'
	]
]);


?>
