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


		if ($create) {
			$link = Database::dispense('asset');
			$type = $parent->getMeta('type');
			$link->$type = $parent;
			$link->name = $name;

			return $link;
		}

		return false;
	}

	static function getAssetFile(\RedBeanPHP\OODBBean $parent, $name) {
		$link = self::getAssetLink($parent, $name);
		return $link && $link->file ? $link->file : false;
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

}
