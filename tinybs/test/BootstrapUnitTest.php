<?php

namespace TinyBSTest;

use TinyBS\BootStrap\BootStrap;
use TinyBS\BootStrap\ComposerAutoloader;
use RuntimeException;
use TinyBS;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
*/
class BootstrapUnitTest
{
	protected static $serviceManager;
	protected static $core;

	public static function init(){
		if (($path = static::findParentPath('tinybs'))) {
			$skeletonPath = dirname($path);
			require $skeletonPath.'/init.php';
			/*define('DS', DIRECTORY_SEPARATOR);
			define('TINYBSROOT', $skeletonPath);
			define('USER_CONFIG_DIR', TINYBSROOT.DS.'config');
			define('TINY_CONFIG_DIR', TINYBSROOT.DS.'tinybs'.DS.'config');
			define('MODULECONFIG', TINYBSROOT.DS.'src'.DS.'main'.DS.'conf');
			
			$composerAutoloader = require $skeletonPath.DS.'vendor'.DS.'autoload.php';
			$composerAutoloader -> add('TinyBS', TINYBSROOT.DS.'tinybs'.DS.'core');
			$composerAutoloader -> add('TinyBSTest', __DIR__);
			ComposerAutoloader::setComposerAutoloader($composerAutoloader);*/
		} else throw new RuntimeException("Couldn't load TinyBS Core");
		$core = BootStrap::initialize();
		BootStrap::loadUserConfig($core);
		static::$serviceManager = $core->getServiceManager();
		static::$core = $core;
	}
	
	/**
	 * 
	 * @return \TinyBS\BootStrap\BootStrap
	 * @author JiefzzLon
	 */
	public static function getCore(){
		return static::$core;
	}

	/**
	 * @return \Zend\ServiceManager\ServiceManager $serviceManager;
	 * @author JiefzzLon
	 */
	public static function getServiceManager(){
		return static::$serviceManager;
	}

	public static function chroot(){
		$rootPath = dirname(static::findParentPath('tinybs'));
		chdir(dirname($rootPath));
	}

	protected static function findParentPath($path)
	{
		$dir = __DIR__;
		$previousDir = '.';
		while (!is_dir($dir . '/' . $path)) {
			$dir = dirname($dir);
			if ($previousDir === $dir) {
				return false;
			}
			$previousDir = $dir;
		}
		return $dir . '/' . $path;
	}
}

BootstrapUnitTest::chroot();
BootstrapUnitTest::init();