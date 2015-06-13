<?php
// Composer autoloading
if (($loaderPath = stream_resolve_include_path(__DIR__.'/vendor/autoload.php'))===false)
	throw new RuntimeException('An error occur on Composer initialization.');
return $loader = include $loaderPath;