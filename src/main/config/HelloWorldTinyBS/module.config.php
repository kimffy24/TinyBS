<?php
return array (
		'service_manager' => array (
				'factories' => array (
						'FirstLibrary' => function ($sm) {
						    return new Kklib\FirstLibrary();
						} 
				) 
		),
);