<?php

namespace Model;

class User extends \RedBeanPHP\SimpleModel {
	function dispense() {
		$this->bean->createdAt = date('Y-m-d H:i:s');
		
	}
}