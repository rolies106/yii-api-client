<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('bootstrap', 'protected/extensions/bootstrap');

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Sample API Client',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'Enter Your Password Here',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths'=>array(
                'ext.bootstrap.gii',
            ),            
        ),
        
    ),

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        
        'bootstrap'=>array(
            'class'=>'ext.bootstrap.components.Bootstrap',
        ),
    
        'rest' => array(
            'class' => 'application.components.request.Rest',
            'api_host'  =>'http://api-php.local/',
            'app_id'    =>'1234567890',
            'app_secret'=>'1234567890',
            'signed_request_key' => '1234567890'
        ),
    
        # Url Management
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                # Social Networking Auth
                // 'auth'=>'hybridauth',
                // 'auth/login'=>'hybridauth/default/login',
                // 'auth/login/<action:(callback)>/<provider:\w+>'=>'hybridauth/default/<action>/provider/<provider>',
                // 'auth/login/<provider:\w+>'=>'hybridauth/default/login/provider/<provider>',
                // 'auth/<controller:\w+>/<action:\w+>'=>'hybridauth/<controller>/<action>',
                
                # General Page
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
            ),
            'showScriptName' => false,
        ),
        'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        /*
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        */
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'webmaster@example.com',
    ),
    'theme' => 'bootstrap'
);