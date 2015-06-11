<?php

namespace TinyBS\Http\Request\Method;

use TinyBS\Http\Request\Utils\AbstractRequestMethod;
use TinyBS\Http\Request;
use TinyBS\Http\Request\ContentType\Json;
use TinyBS\Http\Request\ContentType\XWwwFormUrlEncoded;
use TinyBS\Http\Utils\RequestException;
use TinyBS\Http\Request\ContentType\FormData;
use TinyBS\Http\Request\ContentType\FormDataFile;

class POST extends AbstractRequestMethod{
	
	public function __construct(Request $r){
		parent::__construct($r);
	}
	
	public function getDataParameters(){
		if(!$this->data){
			$k = $this->getTbsRequest()->analyzeContentType();
			$content = null;
			if($k == Request::JSON)
				$content = new Json();
			else if($k == Request::XWWWFORMURLENCODED)
				$content = new XWwwFormUrlEncoded();
			else if($k == Request::FORMDATA)
				$content= new FormData();
			else 
				throw new RequestException("the http method you use is under construction!");

			$this->data = $content->getParameters();
		}
		return $this->data;
	}
	
	public function getFileParameters(){
		if(!$this->file){
			$k = $this->getTbsRequest()->analyzeContentType();
			if($k == Request::FORMDATA){
				$content= new FormDataFile();
				$this->file = $content->getParameters();
			} else $this->file = -1;
		}
		return ($this->file==-1)?array():$this->file;
	}
}