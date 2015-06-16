<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

class HelloWorld extends TinyBsBaseController
{
	public function helloWorldAction(){
	    $firstLib = $this->getServiceLocator()->get('FirstLib');
		return array(
			'matchController' => get_class($this->getServiceLocator()->get('matchController')),
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    'getTrue()' => $firstLib->getTrue(),
		    'getHelloWorld()' => $firstLib->getHelloWorld(),
		    'now'=> date('Y-m-d h:M:s'),
		    'nowTimestamp'=> time()
		);
	}
}