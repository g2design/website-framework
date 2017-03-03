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

	protected $site;

	public function __construct(\RedBeanPHP\OODBBean $site) {
		$this->site = $site;
	}
	//put your code here
}
