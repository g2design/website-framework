<?php

namespace Sites\Backend;

use G2Design\Database;

class Backoffice extends \G2Design\G2App\Controller {
	
	function getIndex() {
		$table = new \G2Design\DataTable();
		
		$table->add_exec_query("SELECT "
				. "site.id, "
				. "site.name, "
				. "site.email, "
				. "oauthclients.client_id, "
				. "oauthclients.client_secret "
				. "FROM site "
				. "INNER JOIN oauthclients on "
				. "oauthclients.site_id = site.id");
		
		$table->add_field('name', 'Site Name')
				->add_field('client_id', 'OAuth Key')
				->add_field('client_secret', 'OAuth Secret');
		
		$table->add_function(\Admin::$slug.'/sites/edit/[id]', 'edit');
		$table->add_function(\Admin::$slug.'/sites/start-management/[id]', 'Manage Site');
		$table->add_function(\Admin::$slug.'/sites/delete-site/[id]', 'Delete', ['confirm-delete']);
		
		return \Admin\Page::getInstance('All Sites')
				->add_content($table->render())
				->add_function("Add Site", \Admin::$slug.'/sites/edit/new')
				->render();
	}
	
	function anyEdit($id) {
		if(is_numeric($id)) {
			$site = Database::load('site', $id);
			
			if(!$site->getID()) {
				$this->redirect(\Admin::$slug.'/sites');
				return;
			}
		} else {
			$site = Database::dispense('site');
		}
		
		$sm = new \Sites\Model\Site();
		
		if(($result = $sm->form($site)) === true) {
			$this->redirect(\Admin::$slug.'/sites');
		}
		
		return \Admin\Page::getInstance('Site')
				->add_content($result)
				->render();
	}
	
	function getStartManagement($id) {
		if(is_numeric($id)) {
			$site = Database::load('site', $id);
			
			if(!$site->getID()) {
				$this->redirect(\Admin::$slug.'/sites');
				return;
			}
			
			$this->session()->set('current_site', $site->id);
			$this->redirect(\Admin::$slug.'/sites/manage/'. \G2Design\Utils\Functions::slugify($site->name));
			return;
		}
		
		$this->redirect(\Admin::$slug.'/sites');
	}
	
	function getDeleteSite($id){
		$site = Database::load('site', $id);
		Database::trash($site);
		$this->redirect(\Admin::$slug.'/sites');
	}
}