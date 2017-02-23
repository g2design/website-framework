<?php
error_reporting(E_ERROR);
ini_set('display_errors', 'on');
$loader = require_once '../vendor/autoload.php';

define('BASE_URL', G2Design\Utils\Functions::get_current_site_url());

\G2Design\G2App::register_modules_dir('../modules');
$app = \G2Design\G2App::init($loader);

//\G2Design\Config::load(DOCUMENT_ROOT.'/configs/default.json', true);

$instance = Website::loadFrom(G2_PROJECT_ROOT.'/themes/helloworld');

$instance->before(function(){
	
});

$instance->attachTo($app);
//echo '<pre>';
//var_dump($app->router->getData());
$app->start();