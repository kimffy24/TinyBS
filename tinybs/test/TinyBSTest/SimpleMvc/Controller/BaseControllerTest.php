<?php
namespace TinyBSTest\SimpleMvc\Controller;

use TinyBS\SimpleMVC\Controller\BaseController;
use TinyBSTest\BootstrapUnitTest;

class BaseControllerTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     * @author JiefzzLon
     */
    public function serviceLocatorAttribute(){
        $core = BootstrapUnitTest::getCore();
        $testClass = new BaseControllerForTest;
        $this->assertTrue($testClass instanceof BaseController);
        $testClass->setServiceLocator($core->getServiceManager());
        $this->assertEquals($core->getServiceManager(), $testClass->getServiceLocator());
    }
}

class BaseControllerForTest extends BaseController {
}