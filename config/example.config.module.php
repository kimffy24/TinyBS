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
 *  the config file are located at /path/to/TinyBS/src/main/config/{$ModuleName}/module.config.php and
 *  the class files are located at /path/to/TinyBS/src/main/src
 *   * notice : this folder is already load into composer autoloader
 *
 *  
 *  if you offer an array like following
 *      return array(
 *  	   array('ModuleName', '/path/to/module/root'),
 *  	   array(
 *  			'module_name' => 'ModuleName', 
 *  			'module_path' => '/path/to/module/root'
 *  		),
 *      );
 *  it contains two module outside tinybs.
 *  Information provided with per array.
 *  The first case is main that first element is the module name and second element is the path of emodule.
 *  The order could not change.
 *  The other case is provides module name with the key module_name and provides module path with the key module_path. 
 */
return array(
	'HelloWorldTinyBS',
	'HelloSmarty',
    //array('ExampleModule', '/server/project/ExampleModule')
    // please layout like this!
    //    ExampleModule/
    //    ├── config/
    //    │   └── module.config.php
    //    └── src/
    //        └── ExampleModule/
    //            ├── Controller/
    //            └── Model/
);