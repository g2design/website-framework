<?php

class Sites extends G2Design\ClassStructs\Module {

	/**
	 *
	 * @var \OAuth2\Server 
	 */
	static $auth_server = null;

	public function __construct() {
		\Admin::add_section(Admin\Section\Navigation::getInstance('Sites', 'sites', "\Sites\Backend\Backoffice"));
	}

	public function init() {

		$db = G2Design\Config::get()->databases->main;
		$dsn = "mysql:dbname={$db->database};host={$db->host}";
		$username = $db->username;
		$password = $db->password;

		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password), ['client_table' => 'oauthclients']);

		// Pass a storage object or array of storage objects to the OAuth2 server class
		$server = new OAuth2\Server($storage);

		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

		// Add the "Authorization Code" grant type (this is where the oauth magic happens)
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

		self::$auth_server = $server;

		G2Design\G2App::getInstance()->router->controller('api', "\Sites\Controller\Api");

		$site_id = $this->session()->get('current_site');
		if ($site_id) {
			$site = G2Design\Database::load('site', $site_id);
//			var_dump($site);exit;
			$site_slug =  'sites/manage/' . G2Design\Utils\Functions::slugify($site->name);
			$section = Admin\Section\Navigation::getInstance("Manage $site->name", $site_slug, new Sites\Backend\ManageSite($site, \Admin::$slug."/".$site_slug));
			
			//Write code to add sections that can be modified for this site
			
			
			
			/**
			 * @todo Creation of section class that allows addition of different sections. Can extend the Navigation section from admin
			 * Eg a controller for news, events, Store Directory etc.
			 * 
			 */
			
			
			$admin = G2Design\G2App::__get_module('Admin');
//			var_dump($admin);exit;
			$section->init($admin);
			Admin::add_section($section);
		}
	}

	static function is_auth() {
		if (!\Sites::$auth_server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
			\Sites::$auth_server->getResponse()->send();
			die;
		}

		return true;
	}

	static function authed_site() {
		if (self::is_auth()) {
			//Connect this token to site
			$token_data = self::$auth_server->getAccessTokenData(\OAuth2\Request::createFromGlobals());

			$client = \G2Design\Database::findLike('oauthclients', ['client_id' => $token_data['client_id']]);
			$site = current($client)->site;
			// Retrieve site information
			return $site;
		}

		return false;
	}

}
