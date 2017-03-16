<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Details;

/**
 * Description of Base
 *
 * @author User
 */
class Base extends \G2Design\G2App\Base {

	protected $entity;

	public function __construct(\RedBeanPHP\OODBBean $entity) {
		$this->entity = $entity;
	}
	//put your code here
}
