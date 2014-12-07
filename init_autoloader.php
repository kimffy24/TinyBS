<?php
// Composer autoloading
if (file_exists(__DIR__.'/vendor/autoload.php')) {
	return $loader = include __DIR__.'/vendor/autoload.php';
} else
    throw new RuntimeException('An error occur on Composer initialization.');