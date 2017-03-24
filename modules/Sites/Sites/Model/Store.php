<?php
namespace Sites\Model;

/**
 * Description of Store
 *
 * @author User
 */
class Store  extends \G2Design\G2App\Model {
	
	function form(\RedBeanPHP\OODBBean $store) {
//		$this->logger()->addAlert('test');
		$cm = new Category();
		$categories = $cm->getCategories($store->site);
		
		//Process available categories and create json array for use in form
		$processed = [];
		foreach($categories as $cat) {
			$processed[$cat->name] = $cat->file->url;
		}
		
		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/store')
				->set('categories', json_encode($processed))
				->set('logo', File::getAssetFile($store, 'picture'))
				->render(true)
				);
		
		if( !$form->is_posted() ) {
			$form->data(['name' => $store->name]);
			$form->data(['tel' => $store->tel]);
			$form->data(['description' => $store->description]);
			
			$cats = [];
			
			//Convert categories to json
			foreach($store->sharedStorecategory as $cat) {
				$cats[] = ['tag' => $cat->name];
			}
			
			$form->data(['categories' => json_encode($cats)]);
		}
		
		if($form->is_posted() && $form->validate()) {
			
			foreach ($form->data() as $field => $value) {
				
				if ( $field != 'categories' && is_string($value) && !empty($field)) {
					$store->{$field} = $value;
				}
			}
			
			//Process the Supplied Categories to respective entities
			try {
				$cats = json_decode($form->data()['categories']);
				foreach($cats as $category) {
					$catob = $cm->findCategory($store, $category->tag);
					if($catob) {
						$store->sharedStorecategory[] = $catob;
					}
				}
			} catch (Exception $ex) {

			}
			
			$file = $form->data()['picture']; /* @var $file \Form\File\Upload */
			
			\G2Design\Database::store($store);
			
			if($file->uploaded()) {
				$file = File::create_file($file);
				File::createAsset($store, 'picture', $file);
				
			}
			
			return true;
			
		}
		
		
		
		return $form->parse();
	}
}
