<?php

namespace TinyBS\Http\Request\Method;

use TinyBS\Http\Request\Utils\RequestMethodInterface;
use TinyBS\Http\Utils\RequestException;

class GET implements RequestMethodInterface{
	public function getDataParameters(){
		return $_GET;
	}
	
	public function getFileParameters(){
		throw new RequestException("Http Get could not use FileParameters!");
	}
}