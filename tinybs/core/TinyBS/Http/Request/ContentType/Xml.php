<?php

namespace TinyBS\Http\Request\ContentType;

use TinyBS\Http\Request\Utils\ContentTypeInterface;
use TinyBS\Http\Utils\RequestException;

class Xml implements ContentTypeInterface {
	public function getParameters(){
		throw new RequestException("xml request is not support now!");
	}
}