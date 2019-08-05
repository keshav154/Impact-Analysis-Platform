<?php

return array(
    'db' => array(
        // for primary db adapter that called
        'username' => 'root',
        'password' => 'root',
    ),
    'zf-mvc-auth' => array(
        'authentication' => array(
            'adapters' => array(
                'veative-auth' => array(
                    'adapter' => 'ZF\\MvcAuth\\Authentication\\HttpAdapter',
                    'options' => array(
                        'accept_schemes' => array(
                            0 => 'basic',
                        ),
                        'realm' => 'api',
                        'htpasswd' => 'public/.htpasswd',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_exceptions' => true,
        'display_not_found_reason' => true,
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
    ),
);
