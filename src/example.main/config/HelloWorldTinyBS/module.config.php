<?php
return array (
		'router' => array (
				'routes' => array (
						'helloworld' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/helloworld[/:action]',
										'defaults' => array (
												'__NAMESPACE__' => 'HelloWorldTinyBS\Controller',
												'controller' => 'HelloWorld',
												'action' => 'helloWorld' 
										) 
								) 
						) 
				) 
		),
		'service_manager' => array (
				'factories' => array (
				) 
		) 
);