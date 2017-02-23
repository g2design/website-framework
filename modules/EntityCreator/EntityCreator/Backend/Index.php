<?php

namespace EntityCreator\Backend;

class Index extends \G2Design\G2App\Controller {
	
	// Determine the correct location of current session
	function getIndex(){
		
		if(\Backend::logged_in('admin')) {
			$this->redirect('admin/dashboard');
		} else {
			$this->redirect('admin/login');
		}
		
		
	}
	
	
	function getLogin() { // Login Form
		\G2Design\G2App\View::getInstance('layouts/rest-based')->render();
	}
	
	function getDashboard() { // Dashboard display
		
	}
	
	
}