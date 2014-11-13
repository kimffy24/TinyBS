<?php

namespace TinyBSTest;

class TinyBSRunningTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     * @author JiefzzLon
     */
	public function unitTestWillRunFirst()
	{
		$this->assertTrue(true);
	}
    /**
     * @test
     * @author JiefzzLon
     */
	public function unitTestWillRun()
	{
		$core = BootstrapUnitTest::getCore();
		$this->assertNotNull($core);
		$this->assertTrue($core instanceof \TinyBS\BootStrap\BootStrap);
		$this->assertTrue($core->getServiceManager() instanceof \Zend\ServiceManager\ServiceManager );
	}
}
