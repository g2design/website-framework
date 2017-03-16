<?php

namespace Sites\Controller;

/**
 * Description of Api
 *
 * @author User
 */
class Api extends \G2Design\G2App\Controller {
	
	/**
	 *
	 * @var \OAuth2\Server
	 */
	var $server = null;
	
	public function __construct() {
		
		$this->server = \Sites::$auth_server;
	}
	
	
	function anyToken() {
		$this->server->handleTokenRequest(\OAuth2\Request::createFromGlobals())->send();
		exit;
	}

	function getResource() {
		
		if($this->is_auth()) {
			$token_data = $this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals());
			echo json_encode($token_data);
		}
		
		exit;
	}
	
	function getSite() {
		if(\Sites::is_auth()) {
			$site = \Sites::authed_site();

			echo json_encode($site->export());
			die();
			
		}
	}
	
	
	function is_auth() {
		if (!\Sites::$auth_server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
			\Sites::$auth_server->getResponse()->send();
			die;
		}
		
		return true;
	}

}
