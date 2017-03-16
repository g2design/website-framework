<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Model;

use G2Design\Database;
/**
 * Description of Setting
 *
 * @author User
 */
class Setting {
	
	function form(\RedBeanPHP\OODBBean $setting, $form_key = false) {
		
		
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/setting')->render(true), $form_key);
		
		
		if(!$form->is_posted()) {
			$form->data($setting->export());
		}
		
		if($form->is_posted() && $form->validate()) {
			
			foreach($form->data() as $field => $value) {
				$setting->{$field} = $value; 
			}
			
			//Before storing. Make sure that there is not another setting with the same name
			$other = \G2Design\Database::findOne('sitesetting','site_id = :site AND name = :setting',['site' => $setting->site->id, 'setting' => $setting->name]);
			
			if( !$other || $other->id == $setting->id) {
				Database::store($setting);
				return true;
			}
			$form->invalidate('name', 'This setting Already Exists');
			
			
		}
		
		return $form->parse();
	}
	
}
