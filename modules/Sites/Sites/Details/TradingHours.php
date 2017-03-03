<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Details;
use G2Design\Database;

/**
 * Description of TradingHours
 *
 * @author User
 */
class TradingHours extends Base {

	var $tradinghours = [];

	public function __construct($site) {
		parent::__construct($site);

		//Load trading hours for this site
		$this->tradinghours = $site->ownTradinghour;
	}

	function html() {
		return \G2Design\G2App\View::getInstance('component/trading-hours')
						->set('trading', $this->tradinghours)
						->render(true);
	}

	function form() {
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/trading-hours')->render(true));
		
		if(!$form->is_posted()) {
			//Convert trading hours to array to set data
			$times = [];
			foreach($this->tradinghours as $trading) {
				$times[$trading->day] = ['open' => $trading->open ? $trading->open : '', 'close' => $trading->close ? $trading->close : ''];
			}
			$form->set_data($times);
		}
		
		if($form->is_posted() && $form->validate()) {
			
			foreach($form->data() as $day => $times) {
				
				//Find the trading hour that corresponds to this $day
				$trading = Database::findOrCreate('tradinghour',['site_id' => $this->site->id, 'day' => $day]);
				if(empty($times['open'])) {
					
					Database::trash($trading);
					continue;
				}
				$trading->open = $times['open'];
				$trading->close = $times['close'];
				
				Database::store($trading);
			}
		}
		
		return $form->parse();
	}

}
