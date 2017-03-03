<?php

namespace Sites\Model;

class Site extends \G2Design\G2App\Model {
	
	function form(\RedBeanPHP\OODBBean $site) {
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/site')->render(TRUE));
		
		if($site->getID() && !$form->is_posted()) {
			$form->set_data($site->export());
		}
		
		if($form->is_posted() && $form->validate()) {
			foreach ($form->data() as $field => $value) {
				$site->{$field} = $value;
			}
			
			\G2Design\Database::store($site);
			
			$consumer = [
				'requester_name' => $site->name, 
				'requester_email' => $site->email
			];
			
			
			// Create oauth_client_entry for this user
			if(!isset($site->oauth_clients)) {
				$oclient = \G2Design\Database::dispense('oauthclients');
				$oclient->client_id = md5($site->name);
				$oclient->client_secret = md5(time().$site->email);
				$oclient->redirect_uri = \G2Design\Utils\Functions::get_current_site_url().'/api';
				$oclient->site = $site;
				\G2Design\Database::store($oclient);
			}
			
			return true;
		}
		
		return $form->parse();
	}
	
}