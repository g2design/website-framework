<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Model;

use G2Design\Database;

/**
 * Description of Post
 *
 * @author User
 */
class Post extends \G2Design\G2App\Model {

	function form(\RedBeanPHP\OODBBean $post) {

		$form = new \Form\Form(\G2Design\G2App\View::getInstance('forms/post')->render(true), 'post-add');

		if (!$form->is_posted()) {
			$form->set_data($post->export());
		}

		if ($form->is_posted() && $form->validate()) {
//			echo '<pre>';
//			var_dump($form->data());
			//Save post data
			foreach ($form->data() as $field => $value) {
				if (is_string($value) && !empty($field)) {
					$post->{$field} = $value;
				}
			}
			//Save the uploaded file to the files table and link to this post via shared entity
			$file = $form->data()['featuredimage']; /* @var $file \Form\File\Upload */

			if ($file->uploaded()) { // File was uploaded and is valid image
				$this->logger()->addAlert('test');
				$fileb = \Sites\Model\File::create_file($file, $post->site);
				$post->file = $fileb;
			}
			
			if (!$post->getID()) {
				$post->datecreated = date('Y-m-d H:i:s');
			}
			$post->datemodified = date('Y-m-d H:i:s');
			$post->status = 'published';


			Database::store($post);
			return true;


//			exit;
		}

		return $form->parse();
	}

}
