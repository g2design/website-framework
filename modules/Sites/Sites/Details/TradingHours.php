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

	function form(\RedBeanPHP\OODBBean $tradinghour, $key = false) {
		if($tradinghour->getID()) {
			$unid = md5('trad-'.$tradinghour->id);
		} else {
			$unid = md5('trad-');
		}
		
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/trading-hours')
				->set('unid', $unid)
				->render(true), $key);
		
		if(!$form->is_posted()) {
			$form->set_data($tradinghour->export());
		}
		
		if($form->is_posted() && $form->validate()) {
			
			foreach($form->data() as $field => $value) {
				$tradinghour->{$field} = $value;
			}
			Database::store($tradinghour);
		}
		
		return $form->parse();
	}

}
