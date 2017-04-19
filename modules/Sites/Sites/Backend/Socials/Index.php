<?php

namespace Sites\Backend\Socials;

class Index extends \Sites\Backend\SiteControllerAbstract {
	
	/**
	 *
	 * @var \Facebook\Facebook 
	 */
	var $facebook = null;
	var $token = null;

	public function __construct(\RedBeanPHP\OODBBean $site, $slug) {
		parent::__construct($site, $slug);

		//Load Facebook api
		$this->facebook = new \Facebook\Facebook([
			'app_id' => \G2Design\Config::get()->facebook->app_id,
			'app_secret' => \G2Design\Config::get()->facebook->app_secret,
			'default_graph_version' => \G2Design\Config::get()->facebook->default_graph_version,
		]);
		$this->token =  \G2Design\Config::get()->facebook->app_id . '|' . \G2Design\Config::get()->facebook->app_secret;
	}

	/**
	 * 
	 */
	function anyIndex() {
		$bld = new \G2Design\FormBuilder();
		$bld->add_field(
				$bld->create_field('facebook-page')
						->set_label('Facebook Page')
		);
		
		$form = $bld->get_form_object();
		
		$form->set_data(['facebook-page' => \Sites\Model\Setting::program_setting($this->site, 'facebook-page')] );
		
		if($form->is_posted() && $form->validate()) {
			$facebook_page = $form->data()['facebook-page']; 
//			echo $facebook_page;exit;
			\Sites\Model\Setting::program_setting($this->site, 'facebook-page', $facebook_page );
			
		}
		
//		$response = $this->facebook->get('stellenboschsquare/feed', $this->token);
		
//		var_dump($response->getBody());
//		exit;
		
		return \Admin\Page::getInstance('Social Media Settings')
						->add_content($bld->render(true))
						->render();
		
	}

}
