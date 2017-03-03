<?php

namespace Admin\Backend;

class Users extends \G2Design\G2App\Controller {
	
	

	function getIndex() {
		$page = new \Admin\Page('Users');

		$table = new \G2Design\DataTable();
		$table->add_query('user');
		$table->add_field('email')
				->add_field('name', 'Full Name');
		
		$table->add_function(\Admin::$slug.'/user/edit/[id]', 'Edit User');

		$page->add_content($table->render(), 'Users');
		$page->add_function('Create User', \Admin::$slug.'/user/edit/new');

		return $page->render();
	}
	
	function anyEdit($id) {
		
		if(is_numeric($id)) {
			$user = \G2Design\Database::load('user', $id);
		} else {
			$user = \G2Design\Database::dispense('user');
		}
		
		$page = new \Admin\Page('Users');
		
		$um = new \Admin\Model\User();
		if((  $result = $um->form($user) ) === true ) {
			$this->redirect(\Admin::$slug.'/user');
			return;
		} 
		$page->add_content($result, $user->name);
		
		return $page->render();
	}

}
