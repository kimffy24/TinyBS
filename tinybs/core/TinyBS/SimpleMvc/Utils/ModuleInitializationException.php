<?php
namespace TinyBS\SimpleMvc\Utils;

use Exception;

class ModuleInitializationException extends Exception {
	public function __construct($message="There some exception occur while Initialize module!", $code=-1, $previous=null){
		parent::__construct($message, $code, $previous);
	}
}