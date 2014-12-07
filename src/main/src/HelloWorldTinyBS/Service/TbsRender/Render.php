<?php
namespace HelloWorldTinyBS\Service\TbsRender;

/**
 * Demo Render
 * @author JiefzzLon
 *
 */
class Render
{
    static private $instance;
    /**
     * @desc implement the singleton design model;
     */
    public function getInstance(){
        if(!static::$instance)
            static::$instance = new Render();
        return static::$instance;
    }
    public function render($dispathResult){
        var_dump($dispathResult);
        return;
    }
}