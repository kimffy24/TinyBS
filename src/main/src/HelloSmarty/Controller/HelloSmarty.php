<?php

namespace HelloSmarty\Controller;

use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

class HelloSmarty extends TinyBsBaseController
{
	public function helloSmartyAction(){
	    $smarty = $this->getServiceLocator()->get('HelloSmarty\Components\Smarty');

	    $smarty->assign('title', __CLASS__);
	    $smarty->assign('name', 'Jiefzz');
	    $smarty->display('test.tpl');
	    return;
	}
}