<?php

namespace TinyBS\Http\Request\ContentType;

use TinyBS\Http\Request\Utils\ContentTypeInterface;

class XWwwFormUrlEncoded implements ContentTypeInterface {
	public function getParameters(){
		parse_str(file_get_contents('php://input'), $arguments);
		return $arguments;
	}
}