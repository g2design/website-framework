<?php
namespace Admin\Section;

class Users extends \Admin\Section {
	
	public function init(\Admin $admin) {
		$admin->controller('user', "\Admin\Backend\Users");
	}

	public function routes() {
		return [
			[
				'label' => 'Users',
				'route' => 'user',
				'sub' => [
					[
						'label' => 'Add User',
						'route' => 'user/edit/add'
					]
				]
			]
		];
	}

}