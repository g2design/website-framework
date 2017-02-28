<?php

namespace Admin\Section;

class Helloworld extends \Admin\Section {
	
	public function routes() {
		return [
			[
				'label' => 'Helloworld',
				'route' => 'helloworld'
			]
		];
	}
	
	public function init(\Admin $admin) {
		$admin->any('helloworld', function(){
			return 'Helloworld';
		});
	}

}