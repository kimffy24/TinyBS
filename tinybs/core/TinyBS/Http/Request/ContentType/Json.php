<?php

namespace TinyBS\Http\Request\ContentType;

use TinyBS\Http\Request\Utils\ContentTypeInterface;

class Json implements ContentTypeInterface {
	public function getParameters(){
		return json_decode(file_get_contents('php://input'), true);
	}
}