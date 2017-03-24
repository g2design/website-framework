<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend;

use G2Design\Database as Database;

/**
 * Description of Posts
 *
 * @author User
 */
class Posts extends SiteControllerAbstract {

	//put your code here

	function getIndex() {

		$table = new \G2Design\DataTable();
		$table->add_query('post', 'site_id = :site', ['site' => $this->site->id]);
		$table->add_field('title')
				->add_field('datecreated')
				->add_field('datemodified')
				->add_field('status');
		
		$table->add_function($this->slug.'/post/[id]', 'edit');
		
		$table->add_function($this->slug . '/delete/[id]', 'Delete', ['confirm-delete']);

		return \Admin\Page::getInstance('News, Events And Competitions')
						->add_content($table->render())
						->add_function('Add Post', $this->slug . '/post/add')
						->render();
	}

	function anyPost($id) {
		if (is_numeric($id)) {
			$post = \G2Design\Database::load('post', $id);
		} else if ($id == 'add') {
			$post = \G2Design\Database::dispense('post');
			$post->site = $this->site;
		}

		$pm = new \Sites\Model\Post();

		if (($result = $pm->form($post)) === true) {
			$this->redirect($this->slug);
		}

		return \Admin\Page::getInstance('Post')
						->add_content($result)
						->render();
	}
	
	function anyDelete($id){
		$post = Database::load('post', $id);
		Database::trash($post);
		$this->redirect($this->slug);
	}
}
