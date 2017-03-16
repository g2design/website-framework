<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend;

/**
 * Description of SiteControllerAbstract
 *
 * @author User
 */
class SiteControllerAbstract extends \G2Design\G2App\Controller {
	var $site = null;
	protected $slug;
	
	function __construct(\RedBeanPHP\OODBBean $site, $slug) {
		if($site->getMeta('type') != 'site' && !$site->getID()) {
			throw new Exception("Must be loaded instance of site");
		}
		$this->slug  = $slug;
		$this->site = $site;
	}
	
	
}
