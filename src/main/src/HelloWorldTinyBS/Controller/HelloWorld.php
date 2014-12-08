<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

class HelloWorld extends TinyBsBaseController
{
	public function helloWorldAction(){
	    $firstLib = $this->getFirstLib();
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    'getTrue()' => $firstLib->getTrue(),
		    'getHelloWorld()' => $firstLib->getHelloWorld()
		);
	}
	/**
	 * 
     * @return \DemoLib\Utils\FirstLib
	 */
	private function getFirstLib(){
	    return $this->getServiceLocator()->get('FirstLib');
	}
}