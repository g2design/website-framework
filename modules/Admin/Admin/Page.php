<?php

namespace Admin;

use G2Design\G2App\View;

class Page extends \G2Design\G2App\Base {

	var $vars = [];
	/**
	 * 
	 * @param type $title
	 * @return \Admin\Page
	 */
	public static function getInstance($title){
		$class = get_class();
		return new $class($title);
	}
	

	public function __construct($title) {
		$this->vars['title'] = $title;
		$this->vars['buttons'] = [];
	}
	
	public function &set_title($title) {
		$this->vars['title'] = $title;
		return $this;
	}

	function &add_function($label, $action) {
		$this->vars['buttons'][] = ['label' => $label, 'action' => $action];
		return $this;
	}

	function &add_content($content, $title = false) {
		$this->vars['content'][] = ['content' => $content, 'title' => $title];
		return $this;
	}
	
	function &add_dashitem($title, $html, $actions = [], $image = false) {
		$this->vars['dashitems'][] = ['content' => $html, 'title' => $title, 'actions' => $actions, 'image' => $image];
		return $this;
	}

	function render($return = true) {
		$view = new View('templates/page');
		foreach ($this->vars as $var => $value) {
			$view->{$var} = $value;
		}

		return $view->render($return);
	}

}
