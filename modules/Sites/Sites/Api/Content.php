<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Api;

/**
 * Description of Content
 *
 * @author User
 */
class Content extends ApiController{
	
	function getIndex($slug) {
		return \Sites\Model\Content::getContent($this->site, $slug);
	}
	
}
