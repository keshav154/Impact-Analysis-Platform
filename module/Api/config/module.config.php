<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Api\\V1\\Rest\\AppActivity\\AppActivityResource' => 'Api\\V1\\Rest\\AppActivity\\AppActivityResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'api.rest.app-activity' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/app-activity[/:app_activity_id]',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rest\\AppActivity\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'api.rest.app-activity',
        ),
    ),
    'zf-rest' => array(
        'Api\\V1\\Rest\\AppActivity\\Controller' => array(
            'listener' => 'Api\\V1\\Rest\\AppActivity\\AppActivityResource',
            'route_name' => 'api.rest.app-activity',
            'route_identifier_name' => 'app_activity_id',
            'collection_name' => 'app_activity',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Api\\V1\\Rest\\AppActivity\\AppActivityEntity',
            'collection_class' => 'Api\\V1\\Rest\\AppActivity\\AppActivityCollection',
            'service_name' => 'AppActivity',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Api\\V1\\Rest\\AppActivity\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Api\\V1\\Rest\\AppActivity\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Api\\V1\\Rest\\AppActivity\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Api\\V1\\Rest\\AppActivity\\AppActivityEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.app-activity',
                'route_identifier_name' => 'app_activity_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Api\\V1\\Rest\\AppActivity\\AppActivityCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.app-activity',
                'route_identifier_name' => 'app_activity_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Api\\V1\\Rest\\AppActivity\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
        ),
    ),
    'zf-content-validation' => array(),
    'input_filter_specs' => array(
        'Api\\V1\\Rest\\Regisration\\Validator' => array(),
    ),
);
