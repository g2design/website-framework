<?php

namespace Admin\Backend;

use \G2Design\Database,
	\Admin\Permission;

class Permissions extends \G2Design\G2App\Controller {

	var $page;

	function __construct() {
		$this->page = new \Admin\Page('Permissions');
	}

	function anyIndex() {
		$table = new \G2Design\DataTable();

		$table->set_data(Database::findAll('permission'), 5);
		$table->set_fields([['label' => 'Permission', 'name' => 'name']]);
		$table->add_function( \Admin::$slug. '/permissions/edit/[id]', 'Edit Allowed Groups');

		$this->page->add_content($table->render(),'Registered System Permissions');
		return $this->page->render();
	}

	function anyEdit($id) {
		$permission = Database::load('permission', $id);

		if (($form = Permission::getInstance()->edit_permission($permission) ) === true) {
			$this->redirect('admin/permissions');
		}

		$this->page->add_content($form, "Permission: $permission->name");
		
		return $this->page->render();
	}

}
