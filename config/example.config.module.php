<?php
/**
 * please offer your module name here.
 * 
 *  like this:
 *      return array(
 *  	   'demo',
 *  	   'album'
 *      );
 *  it contains two module name;
 *  
 *  if you offer an array like following
 *      return array(
 *  	   array('ModuleName', '/path/to/module/root'),
 *  	   array('module_name' => 'ModuleName', 'module_path' => '/path/to/module/root'),
 *      );
 *  it contains two module outside tinybs.
 *  Infomation provided with per array.
 *  The first case is main that first element is the module name and second element is the path of emodule.
 *  The order could not change.
 *  The other case is provides module name with the key module_name and provides module path with the key module_path. 
 */
return array(
	//'demo',
	//'album'
	'HelloWorldTinyBS',
    array('Order', '/server/project/Order/Order')
);