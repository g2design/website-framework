<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of Directory
 *
 * @author User
 */
class Directory extends ApiController {
	
	function getCategories(){
		$categories = $this->site->ownStorecategory;
		$processed = [];
		
		foreach($categories as $cat) {
			if(!$cat->sharedStoreList) {
				continue;
			}
			$pcat = $cat->export();
			$pcat['image'] = $cat->file->url;
			$pcat['slug'] = \G2Design\Utils\Functions::slugify($cat->name);
			$processed[] = $pcat;
		}
		
		return $processed;
	}
	
	function getCategory($category) {
		//Category is equal to slug of name of category thus:
		// Implement a fast loader that uses slug field
		$catob = \G2Design\Database::findOne('storecategory','slug = :slug AND site_id = :site',['slug' => $category, 'site' => $this->site->id ]);
		
		if(!$catob) { // Implement fallback that uses foreach search loop
			$categories  = \G2Design\Database::findAll('storecategory','site_id = :site', ['site' => $this->site->id]);
			
			foreach($categories as $catob) {
				if(\G2Design\Utils\Functions::slugify($catob->name) == $category) {
					break;
				}
			}
		}
		// We found the object
		if($catob->getID()) {
			
			//update script to apply slug to database if not isset
			if(!$catob->slug || \G2Design\Utils\Functions::slugify($catob->name) != $catob->slug) {
				$catob->slug = \G2Design\Utils\Functions::slugify($catob->name);
				\G2Design\Database::store($catob);
			}
			
			//Get All stores connected to this category
			$stores = $catob->sharedStoreList;
			
			$processed = [];
			
			foreach($stores as $store) {
				//Convert Assests to properties
				$storeitem = [
					'name' => $store->name,
					'description' => $store->description,
					'trading_hours' => $store->ownTradinghour
				];
				$assets = [];
				foreach($store->ownAsset as $assetBean) {
					$assets[$assetBean->name] = \Sites\Model\File::getAssetFile($store, $assetBean->name)->url;
				}
				$storeitem['assets'] = $assets;
				
				$processed[] = $storeitem;
				
			}
			
			$return = [
				'name' => $catob->name,
				'image' => $catob->file->url,
				'slug' => $catob->slug,
				'stores' => $processed
			];
			
			return $return;
		}
		
		return false;
		
	}
	
}
