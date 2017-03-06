<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of ApiController
 *
 * @author User
 */
class ApiController {
	var $site = null;
	
	public function __construct(\RedBeanPHP\OODBBean $site) {
		$this->site = $site;
	}
	
	/**
	 * 
	 * @param \RedBeanPHP\OODBBean $site
	 * @return \Sites\Api\ApiController
	 */
	static function getInstance(\RedBeanPHP\OODBBean $site) {
		$class = get_called_class();
		return new $class($site);
	}
	
}
