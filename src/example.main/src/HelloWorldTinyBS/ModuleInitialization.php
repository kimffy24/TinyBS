<?php

namespace HelloWorldTinyBS;

use TinyBS\SimpleMvc\Utils\AbstractModuleInitialization;
use TinyBS\SimpleMvc\SpecialServiceManagerConfigInterface;

class ModuleInitialization
	extends AbstractModuleInitialization
	implements SpecialServiceManagerConfigInterface {
	
	public function getServiceManagerConfigArray(){
		return array(
	        'factories' => array(
	        )
		);
	}
}