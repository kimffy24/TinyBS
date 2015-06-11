<?php
use DemoLib\Utils\FirstLib;

return array(
    'service_manager' => array(
        'factories' => array(
            'FirstLib' => function ($sm){
                return new FirstLib();
            }
        )
    )
);