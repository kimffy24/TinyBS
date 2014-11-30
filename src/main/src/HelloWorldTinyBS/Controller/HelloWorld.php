<?php

namespace HelloWorldTinyBS\Controller;

use TinyBS\SimpleMvc\Controller\BaseController;

class HelloWorld extends BaseController
{
	public function helloWorldAction(){
		return array(
		    'msg' => "HelloWorldTinyBS: Bootstrap is Ok.",
		);
	}
}