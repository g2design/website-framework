<?php

namespace Admin\Backend;

use Admin\Model\User as AModel,
	\G2Design\Database;

class Groups extends \G2Design\G2App\Controller {

	var $page;

	function __construct() {
		$this->page = new \Admin\Page('User Groups');
		if (\Admin\Permission::has_permission('User management')) {
			$this->page->add_function('Add Group', 'admin/groups/group/add');
		}
	}

	function anyIndex() {
		$am = new AModel();

		$table = new \G2Design\DataTable();
		$table->set_data($am->get_groups(), 5);
		$table->set_fields(
				[
					['label' => 'group', 'name' => 'name'],
				]
		);
		$table->add_function('admin/groups/group/[id]', 'Edit Group');
		$table->add_function('admin/groups/users/[id]', 'View Groups Users');

		$this->page->add_content($table->render());
		return $this->page->render();
	}

	function anyGroup($id) {
		$am = new \Admin\Model\User();
		if ($id == 'add') {
			$group = Database::dispense('group');
		} elseif ($id) {
			$group = Database::load('group', $id);
		} else {
			$this->redirect('admin/groups');
		}

		if (($form = $am->form_group($group)) === true) {
			$this->redirect('admin/groups');
		}
		$this->page->add_content($form);
		return $this->page->render();
	}

	function anyUsers($group_id) {
		$group = Database::load('group', $group_id);

		$am = new AModel();
		if(($result = $am->group_users($group)) === true) {
			$this->redirect('admin/groups');
		}
		
		$this->page->add_content($result, "User in Group: $group->name");
		return $this->page->render();
	}

}
