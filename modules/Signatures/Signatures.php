<?php

class Signatures extends G2Design\ClassStructs\Module {
	
	
	public function init() {
		\G2Design\G2App::getInstance()->console('signature', '\Signatures\Console');
	}

}