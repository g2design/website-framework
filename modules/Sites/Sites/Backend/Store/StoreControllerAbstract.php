<?php
namespace Sites\Backend\Store;


/**
 * Description of StoreControllerAbstract
 *
 * @author User
 */
class StoreControllerAbstract extends \Sites\Backend\SiteControllerAbstract {
	//put your code here
	var $store = null;
	
	public function __construct(\RedBeanPHP\OODBBean $site, $store, $slug) {
		parent::__construct($site, $slug);
		$this->store = $store;
	}
	
}
