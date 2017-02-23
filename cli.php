<?php

$loader = require_once './vendor/autoload.php';
error_reporting(E_ERROR);
\G2Design\G2App::register_modules_dir('modules');
$app = \G2Design\G2App::init($loader);



$app->command('helloworld', function($name){
	print $name;
});

$app->cli();