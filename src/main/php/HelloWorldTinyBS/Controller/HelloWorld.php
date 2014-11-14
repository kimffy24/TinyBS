<?php

namespace HelloWorldTinyBS\Controller;
use Kklib\FirstLibrary;

class HelloWorld {
	public function helloWorldAction(){
	    $lib = new FirstLibrary();
	    
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		    '$lib->returnTrue()' => $lib->returnTrue(),
		    '$lib->returnFalse()' => $lib->returnFalse()
		);
	}
}