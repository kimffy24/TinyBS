<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 5/25/15
 * Time: 4:32 PM
 */

namespace TinyBS\BootStrap;


class ExtendHandler
{
    static private function prepareConfigure() {
        self::$lastCore = BootStrap::getLastRequestBootstrapObject();
        $lastCore = self::$lastCore;
        if (!$lastCore)
            die('SomeError occur before TinyBS\BootStrap\BootStrap create or while TinyBS\BootStrap\BootStrap !');

        if (!$lastCore->getServiceManager()->has('TinyBS\RouteMatch\Route'))
            return;

        $routeMatch = $lastCore->getServiceManager()->get('TinyBS\RouteMatch\Route');
        $matchNamespace = $routeMatch->getMatchNamespace();
        if ($matchNamespace) {
            $config = $lastCore->getServiceManager()->get('config');

            if(isset($config['exception_switch'][$matchNamespace]) && $config['exception_switch'][$matchNamespace]==flase){
                exit(0);
            }

            self::$target = (isset($config['exception_handler'][$matchNamespace]) && is_callable($config['exception_handler'][$matchNamespace])) ?
                $config['exception_handler'][$matchNamespace] :
                null;
        }

    }

    static public function errorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        self::prepareConfigure();
        if (self::$target) {
            die(call_user_func_array(
                self::$target,
                array(BootStrap::getLastRequestBootstrapObject()->getServiceManager(), array($errorNo, $errorStr, $errorFile, $errorLine))
            ));
        }
        self::defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine);
    }

    static public function exceptionHandler($exception) {
        self::prepareConfigure();
        if (self::$target) {
            die(call_user_func_array(
                self::$target,
                array(BootStrap::getLastRequestBootstrapObject()->getServiceManager(), array($exception))
            ));
        }
        self::defaltExceptionHandler($exception);
    }


    static private function defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        switch ($errorNo) {
            case E_ERROR:
                echo "ERROR: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)";
                echo "程序已经停止运行，请联系管理员。";
                //遇到Error级错误时退出脚本
                exit;
                break;
            case E_WARNING:
                echo "WARNING: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)";
                break;
            default:
                //不显示Notice级的错误
                break;
        }

        return;
    }

    static private function defaltExceptionHandler($exception) {
        echo "Uncaught exception: ", $exception->getMessage(), "\n";
        die();
    }

    static private $lastCore = null;
    static private $target = null;
} 