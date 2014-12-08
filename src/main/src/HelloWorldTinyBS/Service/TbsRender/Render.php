<?php
namespace HelloWorldTinyBS\Service\TbsRender;

/**
 * Demo Render
 * @author JiefzzLon
 *
 */
class Render
{
    public function render($dispathResult){
    	echo 'testSelfRender';
        var_dump($dispathResult);
        return;
    }
}