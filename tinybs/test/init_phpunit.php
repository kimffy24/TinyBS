<?php
/**
 * set some const parameter
 * check library ZendFramework2 is loaded
 * load TinyBS core file
 */
define('DS', DIRECTORY_SEPARATOR);

define('USER_CONFIG_DIR', TINYBSROOT.DS.'config');
define('TINY_CONFIG_DIR', TINYBSROOT.DS.'tinybs'.DS.'config');

$composerAutoload = require TINYBSROOT.DS.'init_autoloader.php';
if (!class_exists('Zend\ServiceManager\ServiceManager'))
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install`.');

$composerAutoload->add('TinyBS', TINYBSROOT.DS.'tinybs'.DS.'core');
TinyBS\BootStrap\ComposerAutoloader::setComposerAutoloader($composerAutoload);