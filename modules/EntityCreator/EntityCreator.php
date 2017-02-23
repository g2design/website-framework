<?php

class EntityCreator extends G2Design\ClassStructs\Module {
	public function init() {
		\G2Design\G2App::getInstance()->router->controller('admin', '\\EntityCreator\\Backend\\Index');
	}
}