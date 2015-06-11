<?php

namespace TinyBS\Http\Request\Utils;

interface RequestMethodInterface {
	public function getDataParameters();
	public function getFileParameters();
}