<?php
/**
 * set some const parameter
 * check library ZendFramework2 is loaded
 * load TinyBS core file
 */
define('DS', DIRECTORY_SEPARATOR);
define('TINYBSROOT', __DIR__);
define('USER_CONFIG_DIR', TINYBSROOT.DS.'config');
define('TINY_CONFIG_DIR', TINYBSROOT.DS.'tinybs'.DS.'config');
define('MODULECONFIG', __DIR__.DS.'src'.DS.'main'.DS.'conf');

$composerAutoload = require 'init_autoloader.php';
$composerAutoload->add('TinyBS', TINYBSROOT.DS.'tinybs'.DS.'core');
TinyBS\BootStrap\ComposerAutoloader::setComposerAutoloader($composerAutoload);

if (!class_exists('Zend\ServiceManager\ServiceManager'))
	throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install`.');
return var_dump((TinyBS\BootStrap\BootStrap::initialize()->getServiceManager()));