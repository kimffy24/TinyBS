<?php
use HelloSmarty\Components\Smarty4psr0;

return array(
    'service_manager' => array(
        'factories' => array(
            'HelloSmarty\Components\Smarty' => function ($sm){
            	$smarty = new Smarty4psr0();
            	$smarty->assign('url', $sm->get('TinyBS\View\Helper\Url'));
                return $smarty;
            }
        )
    ),
    'router' => array(
        'routes' => array(
            'hellosmarty' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/hellosmarty[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'HelloSmarty\Controller',
                        'controller' => 'HelloSmarty',
                        'action' => 'helloSmarty'
                    )
                )
            )
        )
    ),
    'tbs_view' => array(
    	'HelloSmarty' => array (
	        'actor' => function($rr){
	            //we use smarty to render view page!
	            echo memory_get_usage().'Bytes';
	            return;
	        }
    	)
    )
);