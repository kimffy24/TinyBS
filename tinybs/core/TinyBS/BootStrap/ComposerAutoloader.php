<?php

namespace TinyBS\BootStrap;


class ComposerAutoloader {
	static private $composerAutoloader=null;
	/**
	 * @return \Composer\Autoload\ClassLoader
	 */
	static public function getComposerAutoloader() {
		return self::$composerAutoloader;
	}

	/**
	 * @param \TinyBS\BootStrap\ComposerAutoloader $composerAutoloader
	 */
	static public function setComposerAutoloader($composerAutoloader) {
		self::$composerAutoloader = $composerAutoloader;
	}
}