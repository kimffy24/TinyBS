<?php

namespace TinyBS\Utils;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class RuntimeLogger extends Logger{
	public function __construct(){
		parent::__construct();
		
		$writer = new Stream(TINYBSROOT.DS.'tinybs'.DS.'logs'.DS.'bootstrap.log');
		$this->addWriter($writer);
	}
}