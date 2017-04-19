<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of Social
 *
 * @author User
 */
class Social extends ApiController {

	//put your code here
	var $model = null;

	public function __construct(\RedBeanPHP\OODBBean $site) {
		parent::__construct($site);
		$this->model = new \Sites\Model\Socials($this->site);
	}

	function getFacebook($action, $params = false) {


		//Check if facebook page is saved
		$page = \Sites\Model\Setting::program_setting($this->site, 'facebook-page');
		
		if (!$page)
			return false;

		//Run the action requested by client
		$action = $page . "/$action";
		if ($params) {
			$action .= "?$params";
		}
		
		try {
//			return $action;
			$res = $this->model->facebook()->get($action);
			
			return json_decode($res->getBody());
		} catch (Exception $ex) {
			return ['success'=> false,'message' => $ex->getMessage()];
		}
	}

}
