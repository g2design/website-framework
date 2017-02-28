<?php

namespace Admin;

abstract class Section {
	/**
	 *
	 * @var \Admin; 
	 */
	private $admin = null;
	
	
	abstract function routes();
	
	/**
	 * Use this function to register your controllers and actions under the admin module
	 * 
	 * @param \Admin $admin
	 */
	abstract function init(\Admin $admin);
	
}
