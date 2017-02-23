<?php

use G2Design\Database;


class Backend extends \G2Design\ClassStructs\Module {
	
	public function init() {
		
	}
	
	/**
	 * Returns the logged in user
	 */
	static function logged_in($usertype){
		return false;
	}
	
	/**
	 * Returns Form Html for login
	 * returns user object if login successful
	 * @param type $usertype
	 */
	static function login_form($usertype = 'user' ) {
		//Generate login form and processing for
		
	}
	
	static function create_login($username, $password, $data = []) {
		return Database::dispense('user');
	}

}