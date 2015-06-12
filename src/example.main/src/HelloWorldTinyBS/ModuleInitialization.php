<?php

namespace HelloWorldTinyBS;

use TinyBS\SimpleMvc\Utils\AbstractModuleInitialization;
use TinyBS\SimpleMvc\SpecialServiceManagerConfigInterface;

use DemoLib\Utils\FirstLib;

class ModuleInitialization
	extends AbstractModuleInitialization
	implements SpecialServiceManagerConfigInterface {
	
	public function getServiceManagerConfigArray(){
		return array(
	        'factories' => array(
	            'FirstLib' => function ($sm){
	                return new FirstLib();
	            }
	        )
		);
	}
}