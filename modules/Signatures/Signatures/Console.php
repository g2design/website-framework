<?php

namespace Signatures;

class Console extends \G2Design\G2App\CMD {

	protected $options = [
		'cell' => '012345679',
		'title' => 'Employee Title',
		'name' => 'Employee Name',
		'filelocation' => 'test.html'
	];

	function create($template) {


		if (isset($this->options['data-from']) && is_file($this->options['data-from'])) { // Process the file and run for each instance
			//Load data from file
			$file = $this->options['data-from'];
			$rows = array_map('str_getcsv', file($file));
			$header = array_shift($rows);
			$csv = array();
			foreach ($rows as $row) {
				$options = array_combine($header, $row);
//				$csv[] = array_combine($header, $row);
//				print_r($options);exit;
				$sigs[] = ['html' => $this->html($template, array_merge($this->options, $options)), 'options' => array_merge($this->options, $options)];
			}
			
			
		} else {
			$signature = $this->html($template, $this->options);
			$sigs[] = ['html' => $signature, 'options' => $this->options];
		}



		$zip = new \G2Design\Utils\Zipper();
		foreach ($sigs as $data) {
			$signature = $data['html'];
			$options = $data['options'];
			
			if (isset($this->options['zip'])) { // Create zip file in specified location
				$zipfile = $this->options['zip'];
				if (!isset($this->options['resources']) || !is_dir($this->options['resources'])) {
					print 'When zip the resource folder must be specified and should exist';
					die();
				}
				$resources = $this->options['resources'];


				print "Creating Zip";
				$zip->add_content($options['name'] . '-signature.html', $signature);


				//Read all files in resources folder and also add it to zip
			}
		}
		if (isset($this->options['zip'])) {

			foreach (\G2Design\Utils\Functions::directoryToArray($resources, true) as $file) {
				if (is_file($file)) {
					$zip->addFile($file);
				}
			}

			$zip->store($zipfile);
		}
	}

	private function html($template, $params) {
		if (is_file($template)) {
			$template = file_get_contents($template);

			print "signature created";
			return \G2Design\G2App\View::getInstance($template)->set('options', $params)->render_string($template);
		} else {
			print "File does not exist";
			return false;
		}
	}

}