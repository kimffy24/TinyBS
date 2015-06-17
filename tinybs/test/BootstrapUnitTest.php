<?php

namespace TinyBSTest;

use TinyBS\BootStrap\BootStrap;
use TinyBS\BootStrap\ComposerAutoloader;

/**
 * Test bootstrap, for setting up autoloading
*/
class BootstrapUnitTest
{
	protected static $serviceManager;
	protected static $core;

	public static function init(){
		$core = BootStrap::initialize();
		self::$serviceManager = $core->getServiceManager();
        self::$core = $core;
	}
	
	/**
	 * 
	 * @return \TinyBS\BootStrap\BootStrap
	 * @author JiefzzLon
	 */
	public static function getCore(){
		return self::$core;
	}

	/**
	 * @return \Zend\ServiceManager\ServiceManager $serviceManager;
	 * @author JiefzzLon
	 */
	public static function getServiceManager(){
		return self::$serviceManager;
	}
}



error_reporting(E_ALL | E_STRICT);
define('TINYBSROOT', dirname(dirname(__DIR__)));
require 'init_phpunit.php';

chdir(dirname(dirname(__DIR__)));
BootstrapUnitTest::init();