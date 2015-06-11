<?php
namespace TinyBS\Http;

use TinyBS\Http\Request\Method\GET;
use TinyBS\Http\Request\Method\POST;
use TinyBS\Http\Utils\RequestException;
use TinyBS\Http\Request\Method\PUT;
/**
 * Copy from Zendframework2
 */

/**
 * HTTP Request
 *
 * @link      http://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html#sec5
 */
class Request {
    
    /**#@+
     * @const string METHOD constant names
     */
    const METHOD_GET      = 'GET';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_HEAD     = 'HEAD';
    const METHOD_TRACE    = 'TRACE';
    const METHOD_CONNECT  = 'CONNECT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_PROPFIND = 'PROPFIND';
    
    const METHOD_COPY     = 'COPY';
    const METHOD_LINK     = 'LINK';
    const METHOD_UNLINK   = 'UNLINK';
    const METHOD_PURGE    = 'PURGE';
    
    const GET = 1;
    const POST = 2;
    const PUT = 4;
    const DELETE = 8;
    const OPTIONS = 16;
    const HEAD = 32;
    const TRACE = 64;
    const CONNECT = 128;
    const PATCH = 256;
    const PROPFIND = 512;
    
    const COPY = 1024;
    const LINK = 2048;
    const UNLINK = 4096;
    const PURGE = 8192;
    
    const XWWWFORMURLENCODED = 65536;
    const JSON = 131072;
    const XML = 262144;
    const FORMDATA = 524288;
    //1048576

    
    public function __construct(){
    	$this->method = $_SERVER['REQUEST_METHOD'];
    	if(!empty($_SERVER['CONTENT_TYPE']))
    		$this->contentType = $_SERVER['CONTENT_TYPE'];
    	
    }
    
    /**
     * 
     * @throws RequestException
     * @return \TinyBS\Http\Request\Utils\RequestMethodInterface
     */
    public function getMethodParameter(){
    	if(!$this->methodParameter) {
    		switch($this->getMethodENumber()) {
    			case self::GET :
    				$this->methodParameter = new GET();
    				break;
    			case self::POST :
    				$this->methodParameter = new POST($this);
    				break;
    			case self::PUT :
    				$this->methodParameter = new PUT($this);
    				break;
    			case self::DELETE :
    				throw new RequestException(
    					"The DELETE method requests that ".
    					"the origin server delete the resource ".
    					"identified by the Request-URI.");
    			default:
    				throw new RequestException(
    					'The method '.$this->getRequestMethod().
      					' with ContentType: "'.
    					$this->getRequestContentType().
    					'" is under construction!');
    		}
    	}
    	return $this->methodParameter;
    }

    public function getRequestContentType(){
    	return $this->contentType;
    }
    
    public function getRequestMethod(){
    	return $this->method;
    }

    public function analyzeContentType(){
    	$ct = $this->getRequestContentType();
    	if(stripos($ct, 'json'))
    		return self::JSON;
    	else if(stripos($ct, 'x-www-form-urlencoded'))
    		return self::XWWWFORMURLENCODED;
    	else if(stripos($ct, 'form-data'))
    		return self::FORMDATA;
    	else if(stripos($ct, 'xml'))
    		return self::XML;
    	else
    		return 0;
    }
    
    public function getMethodENumber(){
    	switch($this->getRequestMethod()){
    		case self::METHOD_GET:
    			return self::GET;
    		case self::METHOD_POST:
    			return self::POST;
    		case self::METHOD_PUT:
    			return self::PUT;
    		case self::METHOD_DELETE:
    			return self::DELETE;
    		case self::METHOD_OPTIONS:
    			return self::OPTIONS;
    		case self::METHOD_HEAD:
    			return self::HEAD;
    		case self::METHOD_TRACE:
    			return self::TRACE;
    		case self::METHOD_CONNECT:
    			return self::CONNECT;
    		case self::METHOD_PATCH:
    			return self::PATCH;
    		case self::METHOD_PROPFIND:
    			return self::PROPFIND;
    		case self::METHOD_COPY:
    			return self::COPY;
    		case self::METHOD_LINK:
    			return self::LINK;
    		case self::METHOD_UNLINK:
    			return self::UNLINK;
    		case self::METHOD_PURGE:
    			return self::PURGE;
    	}
    }
    
    /**
     * important Parameters
     * @var string
     */
    private $method;

    private $methodParameter;

    /**
     * @var string
     */
    private $contentType = 'text/plain;charset=UTF-8';
}