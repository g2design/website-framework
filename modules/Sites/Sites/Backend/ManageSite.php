<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend;

/**
 * Description of ManageSite
 *
 * @author User
 */
class ManageSite extends \G2Design\G2App\Controller {
	var $site = null;
	var $dashboard_items = [];
	private $slug;

	/**
	 * Function that will add a panel item to dashboard
	 * @param type $html
	 */
	function addItem($title, $html, $actions = []) {
		$this->dashboard_items[] = ['title' => $title, 'html', 'actions' => $actions];
	}
	
	function __construct(\RedBeanPHP\OODBBean $site, $slug) {
		if($site->getMeta('type') != 'site' && !$site->getID()) {
			throw new Exception("Must be loaded instance of site");
		}
		$this->slug  = $slug;
		$this->site = $site;
	}
	
	function getIndex() {
		$trading = new \Sites\Details\TradingHours($this->site);
		return \Admin\Page::getInstance("Manage Site: {$this->site->name}")
				->add_dashitem('Trading Hours',$trading->html(), [['label' => 'Update', 'url' => $this->slug.'/trading-hours']])
				->render();
	}
	
	/**
	 * 
	 */
	function anyTradingHours() {
		
		$trm = new \Sites\Details\TradingHours($this->site);
		
		
		if(($result = $trm->form()) === true) {
			$this->redirect($this->slug);
			return;
		}
		
		
		
		return \Admin\Page::getInstance("Trading Hours: {$this->site->name}")
				->add_content($result,'Edit Trading Hours')
				->render();
	}
	
}
