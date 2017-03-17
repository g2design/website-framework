<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend\MultiEntity;
use G2Design\Database;
/**
 * Description of Content
 *
 * @author User
 */
class Content  extends MultiEntityControllerAbstract {
	//put your code here
	
	function getIndex() {
		
		$table = new \G2Design\DataTable();
		$table->set_data($this->entity->ownContent, 5);
		
		$table->add_field('name');
		$table->add_field('slug');
		$table->add_function($this->slug.'/edit/[id]', 'Edit');
		
		return \Admin\Page::getInstance('Site Content')
				->add_function('Create Content', $this->slug.'/edit/add')
				->add_content($table->render())
				->render();
	}
	
	function anyEdit($id = false) {
		if(is_numeric($id)) {
			$content = Database::load('content', $id);
		} else if( $id == 'add' ) {
			$content = Database::dispense('content');
			$content->{$this->entity->getMeta('type')} = $this->entity;
		} else {
			$this->redirect($this->slug);
			return;
		}
		
		$cm = new \Sites\Model\Content();
		
		if(($result = $cm->form($content)) === true) {
			$this->redirect($this->slug);
			return;
		}
		
		return \Admin\Page::getInstance('Edit Content')
				->add_content($result)
				->render();
	}
}
