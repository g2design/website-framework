<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of Settings
 *
 * @author User
 */
class Settings extends ApiController{
	//put your code here
	
	function getIndex($setting = false) {
		
		if($setting) {
			$setting = \G2Design\Database::findOne('sitesetting','site_id = :site AND name = :setting',['site' => $this->site->id, 'setting' => $setting]);
			
			if($setting) {
				return $setting->value;
			} else {
				//Create this setting in database
				$sitesetting = \G2Design\Database::dispense('sitesetting');
				$sitesetting->site = $this->site;
				$sitesetting->setting = $setting;
				$sitesetting->value = false;
				
				\G2Design\Database::store($sitesetting);
			}
			return false;
		}
		
		$all_settings = \G2Design\Database::findAll('sitesetting','site_id = :site',['site' => $this->site->id]);
		
		$processed = [];
		foreach($all_settings as $setting) {
			$processed[] = $setting->export();
		}
		
		return $processed;
	}
}
