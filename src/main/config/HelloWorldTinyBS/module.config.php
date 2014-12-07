<?php
return array(
    'router' => array(
        'routes' => array(
            'helloworld' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/helloworld[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'HelloWorldTinyBS\Controller',
                        'controller' => 'HelloWorld',
                        'action' => 'helloWorld'
                    )
                )
            )
        )
    ),
    'tbs_view' => array(
        'actor' => function($renderResult){
            return (new \HelloWorldTinyBS\Service\TbsRender\Render())->render($renderResult);
        }
    )
);