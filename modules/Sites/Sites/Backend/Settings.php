<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend;

/**
 * Description of Settings
 *
 * @author User
 */
class Settings extends SiteControllerAbstract{
	
	function anyIndex() {
		
		$table = new \G2Design\DataTable();
		$table->add_query('sitesetting','site_id = :site', ['site' => $this->site->id]);
		
		$table->add_field('name','Site Setting');
		
		$model = new \Sites\Model\Setting();
		
		// Create a new setting form part of the page
		$new_setting = \G2Design\Database::dispense('sitesetting');
		$new_setting->site = $this->site;
		
		$new_form_render = $model->form($new_setting);
		
		//Edit exiting setting in table
		
		$renderer = new \G2Design\DataTable\Renderer('name');
		$redirect = $this->slug;
		$controller = $this;
		$renderer->set_function(function($field, $value, $data) use ($model, $redirect, $controller){
			
			$setting = $data;
			$key = md5("$setting->name $setting->id $setting->site_id");
			$form = $model->form($setting, $key);
			
			if($form === true) {
				$controller->redirect($controller->slug);
			}
			return $form;
			
		});
		$table->add_renderer($renderer);
		$table->add_function($this->slug.'/delete/[id]', 'Delete');
		
		
		if($new_form_render === true) {
			$this->redirect($this->slug);
		}
		
		
		return $page = \Admin\Page::getInstance("Site Settings: {$this->site->name}")
		->add_content($new_form_render,'Create New Setting')
		->add_content($table->render())
		->render();
	}
	
	function getDelete($id) {
		
		$sitesetting = \G2Design\Database::load('sitesetting', $id);
		if($sitesetting->getID()) {
			\G2Design\Database::trash($sitesetting);
		}
		$this->redirect($this->slug);
	}
	
}
