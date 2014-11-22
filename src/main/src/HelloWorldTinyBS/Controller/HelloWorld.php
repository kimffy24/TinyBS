<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\BaseController;
use Kklib\FirstLibrary;

class HelloWorld extends BaseController
{
	public function helloWorldAction(){
	    $lib = new FirstLibrary();
	    var_dump($this->getServiceLocator());
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    '$lib->returnTrue()' => $lib->returnTrue(),
		    '$lib->returnFalse()' => $lib->returnFalse()
		);
	}
}