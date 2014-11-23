<?php

namespace TinyBS\BootStrap;

class ComposerAutoloader {
	static private $composerAutoloader=null;
	/**
	 * @return \Composer\Autoload\ClassLoader
	 */
	static public function getComposerAutoloader() {
		return ComposerAutoloader::$composerAutoloader;
	}

	/**
	 * @param field_type $composerAutoloader
	 */
	static public function setComposerAutoloader($composerAutoloader) {
		ComposerAutoloader::$composerAutoloader = $composerAutoloader;
	}
}