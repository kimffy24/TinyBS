<?php

namespace TinyBS\Utils;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
//use Zend\Log\Writer\Noop;

class RuntimeLogger extends Logger{
	public function __construct(){
		parent::__construct();
		
		$writer = null;
		
		if(is_file(TINYBSROOT.DS.'development'))
			$writer = new Stream(TINYBSROOT.DS.'tinybs'.DS.'logs'.DS.'bootstrap.log');
		else {
			$writer = new Stream('php://stderr');
			// for Php7 and Zendframework2.4
			//$writer = new Noop();
		}
		$this->addWriter($writer);
	}
}