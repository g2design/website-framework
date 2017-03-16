<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend\Store;

/**
 * Description of StoreManager
 *
 * @author User
 */
class StoreManager extends StoreControllerAbstract {

	/**
	 * Function that will add a panel item to dashboard
	 * @param type $html
	 */
	function addItem($title, $html, $actions = []) {
		$this->dashboard_items[] = ['title' => $title, 'html', 'actions' => $actions];
	}

	function getIndex() {
		$trading = new \Sites\Details\TradingHours($this->store);
		return \Admin\Page::getInstance("Manage Store: {$this->store->name}")
						->add_dashitem('Trading Hours', $trading->html(), [['label' => 'Update', 'url' => $this->slug . '/trading-hours']], \Sites\Model\File::getAssetFile($this->store, 'picture')->url)
						->add_dashitem('Manage Store Details', "<p>{$this->store->name} at {$this->site->name}</p> <a href='$this->slug/edit'>Update Details</a>",[],\Sites\Model\File::getAssetFile($this->store, 'picture')->url)
						->render();
	}

	/**
	 * 
	 */
	function anyTradingHours() {
		//If no trading hours is save for this store. Duplicate the parents trading hours
		if (!$this->store->ownTradinghour) {
			$hours = [];
			foreach ($this->site->ownTradinghour as $hour) {
				$hour = \G2Design\Database::duplicate($hour);
				$hour->store = $this->store;
				$hour->site = null;
				$hours[] = $hour;
			}

			\G2Design\Database::storeAll($hours);
		} else {
			$hours = $this->store->ownTradinghour;
		}


		$table = new \G2Design\DataTable();
		$table->set_data($hours, 10);

		$table->add_field('day');
		$trm = new \Sites\Details\TradingHours($this->site);
		$site = $this->site;
		$table->render_field_as('day', function($field, $value, $data) use($trm, $site) {
			// Create special key for this entry
			$key = md5($data->id . $site->id);
			$form = $trm->form($data, $key);

			return $form;
		});

		//Add a normal add for also
		$tradinghour = \G2Design\Database::dispense('tradinghour');
		$tradinghour->store = $this->store;
		$form = $trm->form($tradinghour);
		if ($form === true) {
			$this->redirect($this->slug . '/trading-hours');
			return;
		}

		return \Admin\Page::getInstance("Trading Hours: {$this->site->name}")
						->add_content($table->render())
						
						->add_content($form, 'Add another tradinghour entry')
						->render();
	}

	function anyEdit() {
		$sm = new \Sites\Model\Store();

		if (($result = $sm->form($this->store)) === true) {
			$this->redirect($this->slug);
		}

		return \Admin\Page::getInstance('Store')
						->add_content($result)
						->add_function('Back to '.$this->store->name, $this->slug)
						->render();
	}

}
