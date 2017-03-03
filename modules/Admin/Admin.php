<?php

class Admin extends G2Design\ClassStructs\Module {
	
	static $slug = 'admin';
			
	static $sections = [];
			
	/**
	 *
	 * @var Website\Instance\Page; 
	 */
	static $template = null;
	
	public function __construct() {
		//Create dashboard filter to wrap all content returned inside dashboard page;
		$admin = $this;
//		ini_set('display_errors', 'on');
//		$this->logger()->addDebug('test');
		G2Design\G2App::getInstance()->router->filter('dashboard', function($response) use ($admin){
			Admin\Component\Backend::content($response);
			// Render that page
			if(self::$template) {
				if(self::$template->config()->admin_slug) {
					\Admin::$slug = $self::$template->config()->admin_slug;
				}
				
				return self::$template->render();
			}
			
			return $response;
		});
	}
	
	public function init() {
		
		G2Design\G2App::getInstance()->router->get('admin/logout', function() {
			$um = new Admin\Model\User();
			$um->logout();
			$redirect_to = isset($_GET['r_to']) ? urldecode($_GET['r_to']) : G2Design\Utils\Functions::get_current_site_url() . \Admin::$slug . '/login';
			
			header('Location: '.$redirect_to);
		});
		
		
		
		// Create a helloworld example of a section
//		$this->logger()->addAlert('Test');
		if(Admin\Permission::has_permission('Access Users')) {
			self::add_section(Admin\Section\Navigation::getInstance('Users', 'user', "\Admin\Backend\Users")
					->add_link('Add User', 'user/edit/new')
					->add_controller('Permissions', 'permissions', "\Admin\Backend\Permissions")
					->add_controller('User Groups', 'groups', "\Admin\Backend\Groups"));
			
		}
		
		
		
		foreach(self::$sections as $section) {
			$section->init($this);
		}
	}
	
	
	public static function add_section(\Admin\Section $section) {
		self::$sections[] = $section;
		
	}
	
	final function controller($route, $controller) {
		$admin = $this;
		\G2Design\G2App::getInstance()->router->group(['after' => 'dashboard'], function(Phroute\Phroute\RouteCollector $router) use ($admin, $route, $controller) {
			$router->controller(\Admin::$slug.'/'.$route, $controller);
		});
		
	}
	
	function get_filters() {
		
	}
	
	function get($route, $handler) {
		$admin = $this;
		\G2Design\G2App::getInstance()->router->group(['after' => 'dashboard'], function(Phroute\Phroute\RouteCollector $router) use ($admin, $route, $handler) {
			$router->get(\Admin::$slug.'/'.$route, $handler);
		});
		
	}
	
	function post($route, $handler) {
		$admin = $this;
		\G2Design\G2App::getInstance()->router->group(['after' => 'dashboard'], function(Phroute\Phroute\RouteCollector $router) use ($admin, $route, $handler) {
			$router->post(\Admin::$slug.'/'.$route, $handler);
		});
		
	}
	
	function any($route, $handler) {
		$admin = $this;
		\G2Design\G2App::getInstance()->router->group(['after' => 'dashboard'], function(Phroute\Phroute\RouteCollector $router) use ($admin, $route, $handler) {
			$router->any(\Admin::$slug.'/'.$route, $handler);
		});
		
	}
	
	static function attach_template(Website\Instance\Page $page) {
		self::$template = $page;
	}
}