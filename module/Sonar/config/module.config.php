<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Sonar\Controller\Sonar' => 'Sonar\Controller\SonarController',
						'Sonar\Controller\Auth' => 'Sonar\Controller\AuthController'
				) 
		),
		
		// The following section is new and should be added to your file
		'router' => array (
				'routes' => array (
						'sonar' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/sonar[/][:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Sonar\Controller\Sonar',
												'action' => 'index' 
										) 
								) 
						),
						'auth' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/auth[/][:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Sonar\Controller\Auth',
												'action' => 'index'
										)
								)
						)
				) 
		),
		
		'console' => array(
				'router' => array(
						'routes' => array(
								'calcTD' => array(
										'type'    => 'simple',
										'options' => array(
												'route'    => 'calc TD <project_id>',
												'defaults' => array(
														'controller' => 'Sonar\Controller\Sonar',
														'action'     => 'calc'
												)
										)
								)
						)
				)
		),
		
		'view_manager' => array (
				'template_path_stack' => array (
						'sonar' => __DIR__ . '/../view' 
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		'doctrine' => array (
				'driver' => array (
						'application_entities' => array (
								'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
								'cache' => 'array',
								'paths' => array (
										__DIR__ . '/../src/Sonar/Entity' 
								) 
						),
						'orm_default' => array (
								'drivers' => array (
										'Sonar\Entity' => 'application_entities' 
								) 
						) 
				) 
		)
		
);
