<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\BaseController;

class HelloWorld extends BaseController
{
	public function helloWorldAction(){
	    $lib = $this->getServiceLocator()->get('FirstLibrary');
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    '$lib->returnTrue()' => $lib->returnTrue(),
		    '$lib->returnFalse()' => $lib->returnFalse()
		);
	}
}