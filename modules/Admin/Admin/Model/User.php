<?php

namespace Admin\Model;

use G2Design\Database;
/**
 * Description of User
 *
 * @author User
 */
class User extends \G2Design\G2App\Model {
	
	const PASS_SALT = 'U$eRS0Ut__WeN';
	
	function load_user($email, $password) {
		$email = strtolower($email);
		if ($email == 'stephan@g2design.co.za') {
			$account = Database::findOrCreate('user', ['email' => $email,]);

			if (!$account->password) {
				$account->password = $this->hash_password($password);

				Database::store($account);
				
				$group = Database::findOrCreate('group',['name' => 'Super Admin', 'default' => true]);
				
				$group->sharedAdmin = [$account];
				
				Database::store($group);
			} else {
				//Check if the password is correct
				if($this->hash_password($password) != $account->password) {
					return false;
				}
			}
		} else {
			$account = current(Database::findLike('user', ['email' => $email, 'password' => $this->hash_password($password)]));
		}
		
		// If not create a group

		return $account ? $account : false;
	}
	
	function hash_password($password) {
		return md5(self::PASS_SALT . $password . self::PASS_SALT);
	}
	
	
	/**
	 * The Login Form
	 */
	function login() {
		$login = \G2Design\G2App\View::getInstance('components/login')->render(true);
		$form = new \Form\Form($login);

		if ($form->is_posted() && $form->validate()) {
			
			$account = $this->load_user($form->data()['email'], $form->data()['password']);
			if (!$account) {
				$form->invalidate('email', 'Login Details incorrect');
			} else {
				$this->set_logged_in($account);
				return true;
			}
		}

		return $form->parse();
	}
	
	function set_logged_in($account) {
		$this->session()->set('user_k', $account->id);
	}
	
	/**
	 * 
	 * @return \RedBeanPHP\OODBBean
	 */
	function get_current_user() {
		
		
		if (( $id = $this->session()->get('user_k') ) !== null) {

			return Database::load('user_k', $id);
		}
		
		return false;
	}
	
	function logout() {
		$this->session()->clear();
	}
	
}
