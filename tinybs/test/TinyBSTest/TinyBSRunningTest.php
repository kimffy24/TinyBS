<?php
namespace TinyBSTest;

use TinyBS\BootStrap\BootStrap;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_TestCase;

class TinyBSRunningTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     * @author JiefzzLon
     */
	public function unitTestWillRun1()
	{
		$this->assertTrue(true);
	}
    /**
     * @test
     * @author JiefzzLon
     */
	public function unitTestWillRun2()
	{
		$core = BootstrapUnitTest::getCore();
		$this->assertNotNull($core);
		$this->assertTrue($core instanceof BootStrap);
		$this->assertTrue($core->getServiceManager() instanceof ServiceManager );
	}
}
