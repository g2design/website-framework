<?php

namespace Admin;

use Admin\Model\User as Amodel,
	\G2Design\Database,
	\G2Design\G2App\View\Pug as PugView;

class Permission extends \G2Design\ClassStructs\Singleton {

	static function has_permission($permission) {
		$instance = self::getInstance();

		$permO = $instance->load_permission($permission);

		if (!$permO)
			return false;

		$am = new Amodel();
		$current_admin = $am->get_current_user();
		if (!$current_admin)
			return false;

		foreach ($permO->sharedGroup as $must_be) {

			foreach ($must_be->sharedUser as $user) {
				if ($user->id == $current_admin->id) {
					return true;
				}
			}
		}

		return false;
	}

	function load_permission($permission) {
		$permission = Database::findOrCreate('permission', ['name' => $permission]);
		$groups = $permission->sharedGroup;
		if (empty($groups)) { // find the default group and assign it to this permission
			$group = Database::findLike('group', ['default' => true]);
			if (!$group) {
				return false;
			}
			$permission->sharedGroup = $group;

			Database::store($permission);
		}

		return $permission;
	}

	function edit_permission($permission) {
		$view = new PugView('forms/permission');
		$view->groups = Database::findAll('group');
		$view->permission = $permission;

		$form = new \Form\Form($view->render(true));

		$groups = $permission->sharedGroup;
		if (!$form->is_posted()) {

			$data = [];
			foreach ($groups as $group) {
				$data[$group->id] = 'on';
			}

			$form->set_data($data);
		}

		if ($form->is_posted() && $form->validate()) {

			$groups = [];
			foreach ($form->data() as $id => $on) {
				if (is_numeric($id) && $on) {
					$groups[] = Database::load('group', $id);
				}
			}

			$permission->sharedGroup = $groups;

			Database::store($permission);
			//Popup::info('Permission updated');
			return true;
		}



		return $form->parse();
	}

}
