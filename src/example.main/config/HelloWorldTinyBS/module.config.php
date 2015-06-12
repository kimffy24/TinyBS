<?php
use HelloWorldTinyBS\Service\TbsRender\Render;

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
		'tbs_view' => array (
				'HelloWorldTinyBS' => array (
						'actor' => function ($renderResult) {
							$view = new Render ();
							return $view->render ( $renderResult );
						} 
				) 
		),
		'service_manager' => array (
				'factories' => array (
				) 
		) 
);