<?php
namespace Sites\Backend\MultiEntity;
/**
 * Description of MultiEntityControllerAbstract
 *
 * @author User
 */
abstract class MultiEntityControllerAbstract extends \G2Design\G2App\Controller {
	
	/**
	 *
	 * @var \RedBeanPHP\OODBBean 
	 */
	var $entity = null;
	protected $slug;
	
	function __construct(\RedBeanPHP\OODBBean $entity, $slug) {
		$this->slug  = $slug;
		$this->entity = $entity;
	}
	
	
}
