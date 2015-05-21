<?php

namespace TinyBS\SimpleMvc\Controller\Utils;

/**
 * Implement this interface that framework call 
 * _preDispatch() before dispatch
 * @author Jiefzz
 */
interface PreDispatchInterface {
	/**
	 * Please don't echo anything here!
	 * Please don't return anything here!
	 * you can use ServiceManager here
	 */
	public function _preDispatch();
}