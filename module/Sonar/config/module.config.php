<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Sonar\Controller\Sonar' => 'Sonar\Controller\SonarController',
         ),
     ),
     
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'album' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/sonar[/][:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Sonar\Controller\Sonar',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     'view_manager' => array(
         'template_path_stack' => array(
             'sonar' => __DIR__ . '/../view',
         ),
     ),
);
