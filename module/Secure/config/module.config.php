<?php

return [
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Secure\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'report' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/report[/:action][/:id][/:status]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'status' => '[A-Z]',
                    ),
                    'defaults' => array(
                        'controller' => 'Secure\Controller\Report',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => 'config/autoload/language/content',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => [
        'factories' => [
            'Secure\Controller\Index' => 'Secure\Factory\Controller\IndexControllerFactory',
        ],
        'invokables' => array(
            'Secure\Controller\Report' => 'Secure\Controller\ReportController',
        ),
    ],
    'view_manager' => [
         
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/error404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/loginLayout.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'error/error404' => __DIR__ . '/../view/error/error404.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ],
    'controller_plugins' => [
        'invokables' => [
             'CommonClass' => 'Secure\Controller\Plugin\CommonClass',
            'HttpClientRequest' => 'Secure\Controller\Plugin\HttpClient',
            'AwsGetAndPutPlugin' => 'Application\Controller\Plugin\AwsGetAndPutPlugin',
            'HttpClientRequest' => 'Secure\Controller\Plugin\HttpClient',
        ]
    ],
];
