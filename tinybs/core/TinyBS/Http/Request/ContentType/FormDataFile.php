<?php

namespace TinyBS\Http\Request\ContentType;

use TinyBS\Http\Request\Utils\ContentTypeInterface;

class FormDataFile implements ContentTypeInterface{
	public function getParameters(){
		return (isset($_FILES) && !empty($_FILES))?$_FILES:-1;
	}
}