<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'FirstLib' => function ($sm){
                return new DemoLib\Utils\FirstLib();
            }
        )
    )
);