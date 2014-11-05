<?php

namespace TinyBSTest;

class TinyBSRunningTest extends \PHPUnit_Framework_TestCase {
	public function testUnitTestWillRun()
	{
		$core = BootstrapUnitTest::getCore();
		$this->assertNotNull($core);
		$this->assertTrue($core instanceof \TinyBS\BootStrap\BootStrap);
		$this->assertType($core->getServiceManager() instanceof \Zend\ServiceManager\ServiceManager );
	}
	public function testUnitTestWillRun2()
	{
		return true;
	}
}