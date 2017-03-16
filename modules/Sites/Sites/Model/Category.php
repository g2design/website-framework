<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Model;
use \Sites\Model\File as FileModel;
use G2Design\Database;
/**
 * Description of Category
 *
 * @author User
 */
class Category extends \G2Design\G2App\Model{
	
	
	function form(\RedBeanPHP\OODBBean $category) {
		
		
		$form =  new \Form\Form(\G2Design\G2App\View::getInstance('forms/category')->render(true));
		
		if($form->is_posted() != true) {
			$form->data($category->export());
			
			//
			
		}
		
		if($form->is_posted() && $form->validate()) {
			foreach($form->data() as $field => $value) {
				if(is_string($value) && !empty($field)) {
					$category->{$field} = $value;
				}
			}
			
			$category->slug = \G2Design\Utils\Functions::slugify($category->name);
			
			//Store the file and connect to this post
			$file = $form->data()['categoryimage'];
			
			if($file && $file->uploaded()) {
				$fm = new FileModel();
				$fileb = $fm->file_create($file, $category->site);
				$category->file = $fileb;
			}
			
			\G2Design\Database::store($category);
			return true;
		}
		
		return $form->parse();
		
	}
	
	/**
	 * Find Active Categories
	 * 
	 * @param \RedBeanPHP\OODBBean $store
	 * @return type
	 */
	function getCategories(\RedBeanPHP\OODBBean $store) {
		return $store->ownStorecategory;
	}
	
	function findCategory(\RedBeanPHP\OODBBean $store, $cat) {
		return Database::findOne('storecategory', 'site_id = :site AND name LIKE :cat ', ['site' => $store->site->id, 'cat' => $cat]);
		
	}
	
	
}
