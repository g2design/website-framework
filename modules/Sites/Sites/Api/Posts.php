<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of Posts
 *
 * @author User
 */
class Posts extends ApiController {
	
	function getIndex() {
		$posts = $this->site->ownPost;
		
		$posts_prcessed = [];
		foreach($posts as $post) {
			$post->file;
			$post->slug = \G2Design\Utils\Functions::slugify($post->title);
			
			$posts_prcessed[] = $post->export();
		}
		
		return $posts_prcessed;
	}
}
