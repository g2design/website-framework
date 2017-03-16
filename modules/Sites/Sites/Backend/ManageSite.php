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
class ManageSite extends SiteControllerAbstract {
	var $dashboard_items = [];

	/**
	 * Function that will add a panel item to dashboard
	 * @param type $html
	 */
	function addItem($title, $html, $actions = []) {
		$this->dashboard_items[] = ['title' => $title, 'html', 'actions' => $actions];
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
		
		$table = new \G2Design\DataTable();
		$table->set_data($this->site->ownTradinghour, 10);
		
		$table->add_field('day');
		$trm =  new \Sites\Details\TradingHours($this->site);
		$site = $this->site;
		$table->render_field_as('day', function($field,$value,$data) use($trm, $site){
			// Create special key for this entry
			$key =  md5($data->id.$site->id);
			$form = $trm->form($data, $key);
			
			return $form;
			
		});
		
		//Add a normal add for also
		$tradinghour = \G2Design\Database::dispense('tradinghour');
		$tradinghour->site = $this->site;
		$form = $trm->form($tradinghour);
		
		if($form === true) {
			$this->redirect($this->slug.'/trading-hours');
			return;
		}
		
		return \Admin\Page::getInstance("Trading Hours: {$this->site->name}")
				->add_content($table->render())
				->add_content($form,'Add another tradinghour entry')
				->render();
	}
	
}
