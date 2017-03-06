<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of TradingHours
 *
 * @author User
 */
class TradingHours extends ApiController {
	
	
	/**
	 * Returns all trading hours for this site
	 */
	function getIndex() {
		$tradinghours = $this->site->ownTradinghour;
		
		$tr_parsed = [];
		
		foreach($tradinghours as $hour) {
			$tr_parsed[] = $hour->export();
		}
		
		return $tr_parsed;
	}
	
}
