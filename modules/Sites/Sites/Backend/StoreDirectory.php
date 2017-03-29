<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Backend;

use G2Design\Database as Database,
	\G2Design\Utils\Functions as Functions;

/**
 * Description of StoreDirectory
 *
 * @author User
 */
class StoreDirectory extends SiteControllerAbstract {

	function getIndex() {

		$table = new \G2Design\DataTable();
		$table->add_query('store', 'site_id = :site', ['site' => $this->site->id]);

		$table->add_field('name', 'Store');
		$table->add_function('[admin]', 'Manage');
		$table->add_function($this->slug . '/store/[id]', 'Edit');

		$site_id = $this->site->id;
		if ($site_id) {
			$site = Database::load('site', $site_id);
			$site_slug = 'sites/manage/' . Functions::slugify($site->name);
			$table->add_function('admin/' . $site_slug . '/store-directory/delete-store/[id]', 'Delete Store', ['confirm-delete']);
			
		}

		return \Admin\Page::getInstance('Store Directory')
						->add_function('Categories', $this->slug . '/categories')
						->add_function('Add Store', $this->slug . '/store/add')
						->add_content($table->render())
						->render();
	}

	function anyStore($id) {
		if ($id == 'add') {
			$store = \G2Design\Database::dispense('store');
			$store->site = $this->site;
		} else if (is_numeric($id)) {
			$store = \G2Design\Database::load('store', $id);
			if ($store->site->id != $this->site->id) {
				unset($store);
			}
		}

		if (!isset($store)) {
			$this->redirect($this->slug);
		}

		$sm = new \Sites\Model\Store();

		if (($result = $sm->form($store)) === true) {
			$this->redirect($this->slug);
		}

		return \Admin\Page::getInstance('Store')
						->add_content($result)
						->render();
	}

	function anyCategories($id = false) {

		// Category add form
		if ($id === false) {
			$cat = \G2Design\Database::dispense('storecategory');
			$cat->site = $this->site;
		} else if (is_numeric($id)) {
			$cat = \G2Design\Database::load('storecategory', $id);
			if (!$cat->getID() || $cat->site->id != $this->site->id) {
				$this->redirect($this->slug);
			}
		}

		$cm = new \Sites\Model\Category();
//		
		if (($result = $cm->form($cat)) === true) {
			$this->redirect($this->slug . '/categories');
		}


		$cats = \G2Design\Database::findAll('storecategory', 'site_id = :site', ['site' => $this->site->id]);
		$string = '';

		foreach ($cats as $cat) {
			$image = $cat->file->url ? $cat->file->url : '';
			$string .= "<div class='chip'>
				<img src='{$image}' alt='Image'>
				<span>$cat->name</span>
					<a href='{$this->slug}/categories/$cat->id' >edit </a>
					<a class='async-link confirm-delete' href='{$this->slug}/delete-category/{$cat->id}'><i class='close material-icons'>close</i></a>
			  </div>";
		}



		return \Admin\Page::getInstance("Sites Categories: {$this->site->name}")
						->add_function('Back to store list', $this->slug)
						->add_content($string, "This stores Categories")
						->add_content($result)
						->render();
	}

	function getDeleteCategory($id) {
		$cat = \G2Design\Database::load('storecategory', $id);
		if ($cat->getID() && $cat->site->id == $this->site->id) {
			\G2Design\Database::trash($cat);
		}
		$this->redirect($this->slug . '/categories');
	}

	function anyCategory($id) {
		if ($id == 'add') {
			$cat = \G2Design\Database::dispense('storecategory');
			$cat->site = $this->site;
		} else if (is_numeric($id)) {
			$cat = \G2Design\Database::load('storecategory', $id);
			if (!$cat->getID() || $cat->site->id != $this->site->id) {
				$this->redirect($this->slug);
			}
		} else {
			$this->redirect($this->slug . '/slugs');
		}

		$cm = new \Sites\Model\Category();
//		
		if (($result = $cm->form($cat)) === true) {
			$this->redirect($this->slug . '/categories');
		}

		return \Admin\Page::getInstance('Category')
						->add_content($result)
						->render();
	}

	function getDeleteStore($id) {
		$store = Database::load('store', $id);
		Database::trash($store);
		$site_id = $this->session()->get('current_site');
		if ($site_id) {
			$site = Database::load('site', $site_id);
			$site_slug = 'sites/manage/' . Functions::slugify($site->name);
		}
		$this->redirect('admin/' . $site_slug . '/store-directory');
	}

}
