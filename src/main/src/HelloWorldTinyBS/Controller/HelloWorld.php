<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

class HelloWorld extends TinyBsBaseController
{
	public function helloWorldAction(){
	    $firstLib = $this->getServiceLocator()->get('FirstLib');
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    'getTrue()' => $firstLib->getTrue(),
		    'getHelloWorld()' => $firstLib->getHelloWorld()
		);
	}
}