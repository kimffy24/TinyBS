<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
// Bootstrap the project
require 'init.php';
TinyBS\BootStrap\BootStrap::run();