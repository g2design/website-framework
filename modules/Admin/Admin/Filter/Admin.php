<?php

namespace Admin\Filter;

class Admin {
	
	/**
	 * Redirects user to login if 
	 * 
	 * @param type $page
	 */
	static function login_redirect($page) {
		$user = $page->comp('user');
		
		if (!$user->logged_in()) {
			$page->redirect(isset($page->config()->login_url) ? $page->config()->login_url : 'admin/login');
			exit;
		}
	}

}
