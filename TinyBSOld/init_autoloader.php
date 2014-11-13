<?php
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
	return $loader = include 'vendor/autoload.php';
} else
    throw new RuntimeException('An error occur on Composer initialization.');