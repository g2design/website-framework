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
//		$this->logger()->addAlert('test');
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

		$this->section_init();

		//register api resource controllers if api is verified

		if (self::auth()) {
			$this->api_controllers();
		}
	}

	static function is_auth() {
		if (!self::auth()) {
			\Sites::$auth_server->getResponse()->send();
			die;
		}

		return true;
	}

	static function auth() {
		if (!\Sites::$auth_server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
			return false;
		}

		return true;
	}

	static function authed_site() {
		if (self::auth()) {
			//Connect this token to site
			$token_data = self::$auth_server->getAccessTokenData(\OAuth2\Request::createFromGlobals());

			$client = \G2Design\Database::findLike('oauthclients', ['client_id' => $token_data['client_id']]);
			$site = current($client)->site;
			// Retrieve site information
			return $site;
		}

		return false;
	}

	function api_controllers() {

		$site = self::authed_site();
		G2Design\G2App::getInstance()->router->filter('json_response', "\Sites\Filter\RouteFilter::afterJson");
		G2Design\G2App::getInstance()->router->group(['after' => 'json_response'], function($router) use ($site) {
			/* @var $router \Phroute\Phroute\RouteCollector */
			$router->controller('api/resources/tradinghours', Sites\Api\TradingHours::getInstance($site));
			$router->controller('api/resources/posts', Sites\Api\Posts::getInstance($site));
			$router->controller('api/resources/content', Sites\Api\Content::getInstance($site));
			$router->controller('api/settings', Sites\Api\Settings::getInstance($site));
			$router->controller('api/directory', Sites\Api\Directory::getInstance($site));
			$router->controller('api/socials', Sites\Api\Social::getInstance($site));
		});
	}

	function section_init() {
		$site_id = $this->session()->get('current_site');
		if ($site_id) {
			$site = G2Design\Database::load('site', $site_id);
			$admin = G2Design\G2App::__get_module('Admin');
//			var_dump($site);exit;
			$site_slug = 'sites/manage/' . G2Design\Utils\Functions::slugify($site->name);
			$sections = [];
			$sections[] = Admin\Section\Navigation::getInstance("Manage: $site->name", $site_slug, new Sites\Backend\ManageSite($site, \Admin::$slug . "/" . $site_slug))
					->add_controller('Site Content', $site_slug . '/content', new Sites\Backend\MultiEntity\Content($site, \Admin::$slug . "/" . $site_slug . "/content"))
					->add_controller('Site Assets', $site_slug . '/assets', new Sites\Backend\MultiEntity\Assets($site, \Admin::$slug . "/" . $site_slug . "/assets"))
					->add_controller('News, Events and Competitions', $site_slug . '/posts', new Sites\Backend\Posts($site, \Admin::$slug . "/" . $site_slug . "/posts"))
					->add_controller('Store Directory', $site_slug . '/store-directory', new Sites\Backend\StoreDirectory($site, \Admin::$slug . "/" . $site_slug . "/store-directory"))
					->add_controller('Settings', $site_slug . '/settings', new Sites\Backend\Settings($site, \Admin::$slug . "/" . $site_slug . "/settings"))
					->add_controller('Social Media Settings', $site_slug. '/socials', new \Sites\Backend\Socials\Index($site, $site_slug. '/socials'))
					;


			//Register all stores controller that is on this site
			foreach ($site->ownStore as $store) {
				$shortend = $site_slug . "/store-directory/" . G2Design\Utils\Functions::slugify($store->name);
				$store_slug = \Admin::$slug . "/" . $shortend;
				$store->admin = $store_slug;
				G2Design\Database::store($store);
				
				$admin->controller($shortend, new Sites\Backend\Store\StoreManager($site, $store, $store_slug));
//				$admin->controller($shortend.'/assets', new Sites\Backend\MultiEntity\Assets($store, $shortend.'/assets'));
			}


			//Write code to add sections that can be modified for this site

			/**
			 * @todo Creation of section class that allows addition of different sections. Can extend the Navigation section from admin
			 * Eg a controller for news, events, Store Directory etc.
			 * 
			 */
//			var_dump($admin);exit;
			foreach ($sections as $section) {
				$section->init($admin);
				Admin::add_section($section);
			}
		}
	}

}
