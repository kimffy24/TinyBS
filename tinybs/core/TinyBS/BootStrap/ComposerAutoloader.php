<?php

namespace TinyBS\BootStrap;


class ComposerAutoloader {
	const PSR_0_CONFIG_NAME = 'config.psr0.php';
	const PSR_4_CONFIG_NAME = 'config.psr4.php';
	const CLASSMAP_CONFIG_NAME = 'config.classmap.php';

	static private $preLoadConfigFiles = array (
			self::PSR_0_CONFIG_NAME,
			self::PSR_4_CONFIG_NAME,
			self::CLASSMAP_CONFIG_NAME
	);
	
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
        spl_autoload_register(array($composerAutoloader, 'loadClass'), true, true);
		self::$composerAutoloader = $composerAutoloader;
		self::prepareComposerAutoload();
	}

	/**
	 * load {,tinybs/}config/config.{psr0,psr4,classmap}.php into ComposerAutoloader
	 * @author JiefzzLon
	 * @return null
	 */
	static private function prepareComposerAutoload(){
		foreach ( self::$preLoadConfigFiles as $v )
			self::setConfigIntoComposerAutoloader ( $v, true );
		foreach ( self::$preLoadConfigFiles as $v )
			self::setConfigIntoComposerAutoloader ( $v );
	}

	/**
	 * general the path to Absolutely Path or TINYBSROOT
	 *
	 * @param unknown $path
	 * @return unknown
	 */
	static private function generalPath($path) {
		if (is_string ( $path )) {
			if ($path [0] == '/')
				return $path;
			else if ($path [0] == '.') {
				if ($path [1] == '.')
					return self::generalPath ( TINYBSROOT . DS . substr ( $path, 2 ) );
				else if ($path [1] == DS)
					return self::generalPath ( TINYBSROOT . DS . 'config' . substr ( $path, 1 ) );
				else
					return self::generalPath ( TINYBSROOT . DS . 'config' . DS . $path );
			} else if (preg_match ( '/^[a-zA-Z0-9]\\:/', $path ))
				return $path;
		}
		return $path;
	}
	
	/**
	 * set config into ComposerAutoloader
	 * @param string $configName
	 * @param boolean $isSysConfig
	 * @author JiefzzLon
	 */
	static private function setConfigIntoComposerAutoloader($configName, $isSysConfig = false) {
		$config = ($isSysConfig)?
		TINY_CONFIG_DIR . DS . $configName:
		USER_CONFIG_DIR . DS . $configName;
		if( ( $rConfig = stream_resolve_include_path( $config ) ) !== false )
			$keys = require $rConfig;
		else
			return;
	
		$composerAutoloader = self::getComposerAutoloader ();
	
		if (is_array ( $keys ) and count ( $keys ))
			switch ($configName) {
				case self::PSR_0_CONFIG_NAME :
					foreach ( $keys as $k => $v )
						$composerAutoloader->set ( $k, self::generalPath ( $v ) );
						break;
				case self::PSR_4_CONFIG_NAME :
					foreach ( $keys as $k => $v )
						$composerAutoloader->setPsr4 ( $k, self::generalPath ( $v ) );
						break;
				case self::CLASSMAP_CONFIG_NAME :
					$composerAutoloader->addClassMap ( $keys );
					break;
		}
	}
	
}