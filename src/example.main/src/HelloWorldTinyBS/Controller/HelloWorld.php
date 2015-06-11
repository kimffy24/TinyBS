<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

class HelloWorld extends TinyBsBaseController
{
	public function helloWorldAction(){
	    $firstLib = $this->getServiceLocator()->get('FirstLib');
		//$mc = $this->getServiceLocator()->get('HelloMemcached');
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    'getTrue()' => $firstLib->getTrue(),
		    'getHelloWorld()' => $firstLib->getHelloWorld(),
		    'now'=> date('Y-m-d h:M:s'),
		    'nowTimestamp'=> time(),
			/*'testMemcached' => $mc->getItem('foo'),
			'testMemcached->getTotalSpace' => $mc->getTotalSpace(),
			'testMemcached->getAvailableSpace' => $mc->getAvailableSpace()*/
		);
	}
	public function helloMcAction(){
		$mc = $this->getServiceLocator()->get('HelloMemcached');
		$mc->setItem('foo', 'bar');
		for($i=0; $i<10000; $i++){
			mt_rand(5, 15);
			$mc->setItem('foo'.$i, 'bar'.mt_rand(0, 999999999));
		}
	}
}