<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Model;

/**
 * Description of Socials
 *
 * @author User
 */
class Socials {
	
	var $site = null;
	
	public function __construct(\RedBeanPHP\OODBBean $site) {
		$this->site = $site;
	}
	
	
	/**
	 * Get instance of facebook api
	 * 
	 * @return \Facebook\Facebook
	 */
	function facebook() {
		$fb = new \Facebook\Facebook([
			'app_id' => \G2Design\Config::get()->facebook->app_id,
			'app_secret' => \G2Design\Config::get()->facebook->app_secret,
			'default_graph_version' => \G2Design\Config::get()->facebook->default_graph_version,
		]);
		
		$fb->setDefaultAccessToken(\G2Design\Config::get()->facebook->app_id . '|' . \G2Design\Config::get()->facebook->app_secret);
		
		return $fb;
	}
}
