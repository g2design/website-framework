<?php

namespace Admin\Model;

use G2Design\Database;
use G2Design\G2App\View\Pug as PugView;

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

				$group = Database::findOrCreate('group', ['name' => 'Super Admin', 'default' => true]);

				$group->sharedUser = [$account];

				Database::store($group);
			} else {
				//Check if the password is correct
				if ($this->hash_password($password) != $account->password) {
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



			$user = Database::load('user', $id);

			if ($user->getID())
				return $user;
		}

		return false;
	}

	function form(\RedBeanPHP\OODBBean $user) {

		$view = new \G2Design\G2App\View('forms/user');
		$form = new \Form\Form($view->render(true));
		if (!$form->is_posted()) {
			$data = $user->export();
			unset($data['password']);
			$form->set_data($data);
		}

		if ($form->is_posted() && $form->validate()) {
			$data = $form->data();
			if (!empty($data['password'])) {
				$data['password'] = $this->hash_password($data['password']);
			} else {
				unset($data['password']);
			}

			foreach ($data as $field => $value) {
				if ($field == 'email') {
					$value = strtolower($value);
				}

				$user->{$field} = $value;
			}

			Database::store($user);
			return true;
		}

		return $form->parse();
	}

	function logout() {
		$this->session()->clear();
	}

	function get_groups() {
		return Database::findAll('group');
	}

	function get_users() {
		return Database::findAll('user');
	}

	function group_users($group) {
		$view = new PugView("forms/group-users");
		$view->users = $this->get_users();
		$view->group = $group;

		$form = new \Form\Form($view->render(true));

		if (!$form->is_posted()) {
			$data = [];
			foreach ($group->sharedUser as $admin) {
				$data[$admin->id] = "on";
			}

			$form->set_data($data);
		}

		if ($form->is_posted()) {

			$admins = [];
			foreach ($form->data() as $key => $value) {
				if ($value) {
					$user = Database::load('user', $key);
					$admins[] = $user;
				}
			}
			$group->sharedUser = $admins;

			Database::store($group);

			return true;
		}

		return $form->parse();
	}

	function form_group($group) {
		$view = new PugView('forms/group');
		$form = new \Form\Form($view->render(true));
		if (!$form->is_posted()) {
			$data = $group->export();
			unset($data['password']);
			$form->set_data($data);
		}

		if ($form->is_posted() && $form->validate()) {
			$data = $form->data();

			foreach ($data as $field => $value) {
				$group->{$field} = $value;
			}

			Database::store($group);
			return true;
		}

		return $form->parse();
	}

}
