<?php

namespace TinyBS\Http\Request\Utils;

use TinyBS\Http\Utils\RequestException;
use TinyBS\Http\Request;

abstract class AbstractRequestMethod implements RequestMethodInterface {

	public function __construct(Request $r){
		$this->tbsRequest = $r;
	}
	
	public function getDataParameters(){
		throw new RequestException(__METHOD__." should be rewrite!");
	}
	
	public function getFileParameters(){
		throw new RequestException(__METHOD__." \$fileParameters is not available now!");
	}
	
	
	private $data=null;
	private $file=null;
	private $tbsRequest;	
	/**
	 * 
	 * @return \TinyBS\Http\Request
	 */
	protected function getTbsRequest(){
		return $this->tbsRequest;
	}
}