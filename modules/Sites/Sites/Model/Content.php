<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Model;

use G2Design\Database;
/**
 * Description of Content
 *
 * @author User
 */
class Content {
	
	function form(\RedBeanPHP\OODBBean $entity) {
		
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/content')->render(TRUE));
		
		if(!$form->is_posted()) {
			$form->data($entity->export());
		}
		
		
		if($form->is_posted() && $form->validate()) {
			
			foreach($form->data() as $field => $value) {
				$entity->$field = $value;
			}
			
			//Create a slug for this title
			$entity->slug = \G2Design\Utils\Functions::slugify($entity->name);
			
			Database::store($entity);
			
			return true;
		}
		
		return $form->parse();
		
	}
	
	
	static function getContent($entity, $slug) {
		$entity_name = $entity->getMeta('type').'_id';
		$result = Database::findOne('content', "$entity_name = :$entity_name AND slug = :slug", [ $entity_name => $entity->id, 'slug' => $slug ]);
		
		return ($result) ? $result->content : false;
	}
	
}
