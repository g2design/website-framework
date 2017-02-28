<?php
error_reporting(E_ERROR);
ini_set('display_errors', 'on');
$loader = require_once '../vendor/autoload.php';

define('BASE_URL', G2Design\Utils\Functions::get_current_site_url());

\G2Design\G2App::register_modules_dir('../modules');
$app = \G2Design\G2App::init($loader);

//\G2Design\Config::load(DOCUMENT_ROOT.'/configs/default.json', true);


\G2Design\Config::load('../config/config.json', true);
//Database connection
G2Design\Database::setup(\G2Design\Config::get()->databases);


$instance = Website::loadFrom(G2_PROJECT_ROOT.'/themes/helloworld');
Admin::attach_template($instance->getPage('admin-template'));

//Register Components
$instance->register_component("\\Admin\\Component\\User");
$instance->register_component("\\Admin\\Component\\Backend");


$instance->attachTo($app);
//echo '<pre>';
//var_dump($app->router->getData());
$app->start();