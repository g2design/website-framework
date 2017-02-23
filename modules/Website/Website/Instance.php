<?php
namespace Website;

class Instance {
	var $folder = null, $process_folder = null;var $globals = [];
	/**
	 *
	 * @var \Twig_Environment 
	 */
	var $twig = null;
	private $pages;
	private $before;
	private $after;

	/**
	 * 
	 * @param type $theme
	 */
	public function __construct($theme) {
		$this->folder = $theme;
		$this->init_twig($theme);
		$this->load_pages();
		
	}
	
	private function init_twig($folder){
		$defaults = [
			'cache' => G2_PROJECT_ROOT . '/cache/twig',
			'process_folder' => G2_PROJECT_ROOT . '/cache/process'
		];



		//Merge config is declared
		if (( $conf = \G2Design\Config::get()->twig)) {
			//Convert to array;
			$conf = (array) $conf;
			$conf = array_merge($defaults, $conf);
		} else
			$conf = $defaults;

		//Create cache dir if not exist
		if (!is_dir(dirname($conf['cache']))) {
			mkdir(dirname($conf['cache']), 0777, true);
		}
		
		if (!is_dir($conf['process_folder'])) {
			mkdir($conf['process_folder'], 0777, true);
		}

		$this->process_folder = $conf['process_folder'];
		
		$loader = new \Twig_Loader_Filesystem($this->process_folder);
		$loader->addPath($folder);

		$this->twig = new \Twig_Environment($loader, array(
			'cache' => $conf['cache'],
			'auto_reload' => true,
			'autoescape' => false,
//			'debug' => true
		));
	}
	
	private function load_pages($pages = 'pages'){
		
		$pages_files = \G2Design\Utils\Functions::directoryToArray($this->folder.'/'.$pages, true);
		
		foreach($pages_files as $file) {
			$page = new Instance\Page($this, $file);
			$this->pages[] = $page;
		}
	}
	
	function before($callable) {
		$this->before[] = $callable;
	}
	
	function after($callable) {
		$this->after[] = $callable;
	}
	function &attachTo(\G2Design\G2App &$app) {
		foreach($this->pages as $page) { /* @var $page Instance\Page */
			foreach($this->before as $call) $page->before ($call);
			foreach($this->after as $call) $page->after ($call);
			
			$app->router->any($page->getRoute(), [$page,'render']);
		}
		return $this;
	}
	
	
	function _global($name, $value) {
		$this->globals[$name] = $value;
	}
}