<?php

namespace Admin\Backend;

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
		$um = new \Admin\Model\User();
		
		if(($result = $um->login()) === true) {
			//Get current admin
			
			//Redirect to this admins roles prefered location
			// Redirect to this admins redirect screen if saved
			
		}
			
		echo $result;
	}
	
	function getDashboard() { // Dashboard display
		
	}
	
	
}