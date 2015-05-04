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
        echo 'use '.__METHOD__.' to render page.';
        var_dump($dispathResult);
        echo '<br />'.memory_get_usage().' Bytes.';
        return;
    }
}