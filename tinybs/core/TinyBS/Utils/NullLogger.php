<?php

namespace TinyBS\Utils;

use Zend\Log\LoggerInterface;

class NullLogger implements LoggerInterface{
	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::emerg()
	 */
	public function emerg($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::alert()
	 */
	public function alert($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::crit()
	 */
	public function crit($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::err()
	 */
	public function err($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::warn()
	 */
	public function warn($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::notice()
	 */
	public function notice($message, $extra = array()) {
		// TODO Auto-generated method stub
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::info()
	 */
	public function info($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Log\LoggerInterface::debug()
	 */
	public function debug($message, $extra = array()) {
		// TODO Auto-generated method stub
        return $this;
	}

    public function log($level, $message, $extra = array()){
        return $this;
    }
}