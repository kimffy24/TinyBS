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
define('MODULECONFIG', TINYBSROOT.DS.'src'.DS.'main'.DS.'config');
define('MODULELOCATION', TINYBSROOT.DS.'src'.DS.'main'.DS.'src');

$composerAutoload = require 'init_autoloader.php';
$composerAutoload->add('TinyBS', TINYBSROOT.DS.'tinybs'.DS.'core');
TinyBS\BootStrap\ComposerAutoloader::setComposerAutoloader($composerAutoload);

if (!class_exists('Zend\ServiceManager\ServiceManager'))
	throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install`.');