<?php

namespace Admin\Component;

/**
 * Description of User
 *
 * @author User
 */
class User extends \Website\Component{
	//put your code here
	public function init() {
		$this->add_function('loginForm', [$this, 'login_form']);
	}

	public function login_form($redirect_to = 'dashboard'){
		$um = new \Admin\Model\User();
		
		if(($result = $um->login()) === true) {
			if($um->get_current_user()->redirect) {
				$this->redirect($um->get_current_user()->redirect);
				exit;
			}
			
			$this->redirect($redirect_to);
			exit;
		}
			
		return $result;
	}
	
	function logged_in() {
		$um = new \Admin\Model\User();
		return $um->get_current_user() ? true : false;
	}

	public function globalName() {
		return 'user';
	}

}
