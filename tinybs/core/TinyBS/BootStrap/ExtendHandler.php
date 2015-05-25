<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 5/25/15
 * Time: 4:32 PM
 */

namespace TinyBS\BootStrap;


class ExtendHandler {
    static public function prepareConfigure(){
        self::$lastCore = BootStrap::getLastRequestBootstrapObject();
        $lastCore = self::$lastCore;
        if(!$lastCore)
            die('TinyBS Environment Error!');

        if(!$lastCore->getServiceManager())
            die('ServiceManager construct false!');

        if(!$lastCore->getServiceManager()->has('TinyBS\RouteMatch\Route'))
            die('do route match false!');

        $routeMatch = $lastCore->getServiceManager()->get('TinyBS\RouteMatch\Route');
        $matchNamespace = $routeMatch->getMatchNamespace();
        if($matchNamespace){
            $config = $lastCore->getServiceManager()->get('config');
            self::$target = (isset($config['exception_handler'][$matchNamespace]) && is_callable($config['exception_handler'][$matchNamespace]))?
                $config['exception_handler'][$matchNamespace]:
                null;
        }

    }
    static public function errorHandler($errorNo, $errorStr, $errorFile, $errorLine){
        self::prepareConfigure();
        if(self::$target){
            die(call_user_func_array(
                self::$target,
                array(BootStrap::getLastRequestBootstrapObject()->getServiceManager(), array($errorNo, $errorStr, $errorFile, $errorLine))
            ));
        }
        self::defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine);
    }

    static public function exceptionHandler($exception){
        self::prepareConfigure();
        if(self::$target){
            die(call_user_func_array(
                self::$target,
                array(BootStrap::getLastRequestBootstrapObject()->getServiceManager(), array($exception))
            ));
        }
        self::defaltExceptionHandler($exception);
    }


    static public function defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine){
        echo "error #".(isset($errorNo)?$errorNo:-1).' occur!<br />';
        echo "description: ".(isset($errorStr)?$errorStr:null).' <br />';
        echo "on: file ".$errorFile." line ".$errorLine.' <br />';
        die();
    }

    static public function defaltExceptionHandler($exception){
        echo "Uncaught exception: " , $exception->getMessage(), "\n";
        die();
    }

    static private $lastCore = null;
    static private $target = null;
} 