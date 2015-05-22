<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 5/22/15
 * Time: 11:12 AM
 */
namespace TinyBS\SimpleMvc\Controller\Plugins\Utils;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use TinyBS\SimpleMvc\Controller\Plugins\ControllerPluginsUrl;

class ControllerPluginsUrlFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null){
        return new ControllerPluginsUrl($serviceLocator);
    }
} 