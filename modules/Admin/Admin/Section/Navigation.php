<?php

namespace Admin\Section;

class Navigation extends \Admin\Section {

	var $controllers = [];
	var $subs = [];
	var $title = '';
	var $route = '';
	static $init = false;

	public function __construct($title, $route, $controller = false) {
		$this->title = $title;
		$this->route = $route;

		$this->default_controller = $controller;
	}

	/**
	 * Creates an instance of the navigation section
	 * 
	 * @param type $title
	 * @param type $route
	 * @param type $controller
	 * 
	 * @return \Admin\Section\Navigation
	 */
	public static function getInstance($title, $route, $controller = false) {
		$class = get_class();
		return new $class($title, $route, $controller);
	}

	public function init(\Admin $admin) {

		if (!isset(self::$init[$this->title]) || !self::$init[$this->title]) {
			//Init default controller
			if ($this->default_controller) {
				$admin->controller($this->route, $this->default_controller);
			}

			//Init all other controllers
			foreach ($this->controllers as $controller) {
				$admin->controller($controller['slug'], $controller['controller']);
			}

			self::$init[$this->title] = true;
		}
	}

	/**
	 * 
	 * @param type $label
	 * @param type $link
	 * @return Navigation
	 */
	function &add_link($label, $link) {
		$this->subs[] = [
			'label' => $label,
			'route' => $link
		];

		return $this;
	}

	function &add_controller($label, $slug, $controller) {
		$this->controllers[] = [
			'slug' => $slug,
			'controller' => $controller
		];

		$this->add_link($label, $slug);
		return $this;
	}

	public function routes() {
		return [
			[
				'label' => $this->title,
				'route' => $this->route,
				'sub' => $this->subs
			]
		];
	}

}
