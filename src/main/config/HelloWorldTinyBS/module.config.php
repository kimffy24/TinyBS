<?php
use Zend\Cache\StorageFactory;
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
							$view = new Render();
							return $view->render ( $renderResult );
						} 
				) 
		),
		'service_manager' => array (
				'factories' => array (
						'HelloMemcached' => function ($sm) {
							return StorageFactory::factory ( array (
									'adapter' => array (
											'name' => 'memcached',
											'options' => array (
													'ttl' => 3600,
													'servers' => array(
															array(
																	'127.0.0.1',//服务器域名或ip
																	11211       //服务器tcp端口号，默认值是11211
															)
													),
													'namespace' => 'MYMEMCACHEDNAMESPACE',
													'liboptions' => array(
															'COMPRESSION' => true,
															'binary_protocol' => true,
															'no_block' => true,
															'connect_timeout' => 100
													)
											),
									),
									'plugins' => array (
											'exception_handler' => array (
													'throw_exceptions' => false 
											) 
									) 
							) );
						} 
				) 
		) 
);