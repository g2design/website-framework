<?php

namespace Sites\Model;

use G2Design\Database;

/**
 * Description of File
 *
 * @author User
 */
class File extends \G2Design\G2App\Model {

	//put your code here
	static function create_file(\Form\File\Upload $file, $site = false) {

		//Prepare public directory location
		$base = 'public/media/images';
		$unq_name = md5('file_' . time()) . '.' . \G2Design\Utils\Functions::get_extension($file->__get('name'));

//		//Move the file to new location
		$file->move(G2_PROJECT_ROOT . $base . '/' . $unq_name);


		//Save file to file db entry
		$fileb = Database::dispense('file');
		$fileb->url = BASE_URL . '' . $base . '/' . $unq_name;
		$fileb->uri = $base . '/' . $unq_name;
		$fileb->uploaded = date('Y-m-d H:i:s');
		$fileb->mime = $file->type;
		if ($site) {
			$fileb->site = $site;
		}

		Database::store($fileb);

		return $fileb;
	}

	function file_create(\Form\File\Upload $file, \RedBeanPHP\OODBBean $site) {

		//Prepare public directory location
		$base = 'public/media/images';
		$unq_name = md5('file_' . time()) . '.' . \G2Design\Utils\Functions::get_extension($file->__get('name'));

//		//Move the file to new location
		$file->move(G2_PROJECT_ROOT . $base . '/' . $unq_name);


		//Save file to file db entry
		$fileb = Database::dispense('file');
		$fileb->url = BASE_URL . '' . $base . '/' . $unq_name;
		$fileb->uri = $base . '/' . $unq_name;
		$fileb->uploaded = date('Y-m-d H:i:s');
		$fileb->mime = $file->type;
		$fileb->site = $site;

		Database::store($fileb);

		return $fileb;
	}

	static function getAssetLink(\RedBeanPHP\OODBBean $parent, $name, $create = false) {
		
		$links = $parent->ownAsset;
		foreach ($links as $link) {
			if ($link->name == $name) {
				return $link;
			}
		}


		if ( $create ) {
			$link = Database::dispense('asset');
			$type = $parent->getMeta('type');
			$link->$type = $parent;
			$link->name = $name;
			$link->slug = \G2Design\Utils\Functions::slugify($name);
			return $link;
		}

		return false;
	}

	static function getAssetFile(\RedBeanPHP\OODBBean $parent, $name) {
		$link = self::getAssetLink($parent, $name);
		return $link && $link->file ? $link->file : false;
	}
	
	/**
	 * 
	 * 
	 * @param \RedBeanPHP\OODBBean $parent
	 * @return type
	 */
	static function getAssets(\RedBeanPHP\OODBBean $parent) {
		return $parent->ownAsset;
	}

	/**
	 * Links a file asset to a entity via intersection entity
	 * 
	 * @param \RedBeanPHP\OODBBean $parent
	 * @param type $name
	 * @param \RedBeanPHP\OODBBean $file
	 */
	static function createAsset(\RedBeanPHP\OODBBean $parent, $name, \RedBeanPHP\OODBBean $file) {
		$link = self::getAssetLink($parent, $name, true);
		$link->file = $file;
		

		Database::store($link);
	}
	
	function form(\RedBeanPHP\OODBBean $asset) {
		
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/asset')->render(true));
		
		if($form->is_posted()) {
			$file = $form->data()['file']; /* @var $file \Form\File\Upload */
			
			if($file->uploaded()) {
				$file = self::create_file($file);
				$asset->file = $file;
				$asset->name = $form->data()['name'];
				$asset->slug = \G2Design\Utils\Functions::slugify($asset->name);
				
				Database::store($asset);
				return true;
			}
		}
		
		return $form->parse();
	}

}
