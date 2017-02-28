<?php

namespace Admin\Component;

class Backend extends \Website\Component {
	static $content = null;
	
	public function globalName() {
		return 'admin';
	}

	public function init() {
		$this->add_function('backend_content', function(){
			return \Admin\Component\Backend::content() ? \Admin\Component\Backend::content() : "Nothing to show";
		});
		
		$this->add_function('backend_sidebar', [$this, 'sidebar']);
	}
	
	static function content($content = false) {
		if($content) {
			self::$content = $content;
		} else {
			return self::$content;
		}
	}
	
	function sidebar() {
		$sections = \Admin::$sections;
		$routes = [
			[
				'label' => 'Home',
				'route' => \Admin::$slug
			]
		];
		
		foreach($sections as $section) {
			
			$s_routes = $section->routes();
			foreach($s_routes as &$route) {
				$route['route'] = \Admin::$slug. '/' . $route['route'];
				
				if(isset($route['sub'])) {
					foreach( $route['sub'] as &$sub ) {
						$sub['route'] = \Admin::$slug. '/' . $sub['route'];
					}
				}
			}
			
			$routes = array_merge($routes,$s_routes);
		}
		
		return $routes;
	}

}