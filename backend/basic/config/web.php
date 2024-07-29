<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yFRxTY6oVRl1x9yAWvHbUglH48wkl0QK',
            'parsers'=>[
                'application/json'=>'yii\web\JsonParser'
            ],
            'as cors' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['http://127.0.0.1:8080', 'http://localhost:8080'],
                    'Access-Control-Request-Method' => ['POST', 'GET', 'OPTIONS', 'PUT', 'DELETE'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600, // Cache (seconds)
                    'Access-Control-Allow-Headers' => ['authorization', 'X-Requested-With', 'content-type'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize'=>false,
                     'controller' => [
                        'apiv1/carrera',
                        'apiv1/profesor',
                        'apiv1/aula',
                        'apiv1/materia',
                        'apiv1/horariomateria',
                        'apiv1/reservaaula',
                        'GET apiv1/auth' => 'apiv1/test',
                        'POST apiv1/auth' => 'api/login',
                        'POST apiv1/signup' => 'apiv1/auth/signup',
                        'POST apiv1/logout' => 'api/logout',
                        ]
                ],
    

            ],
        ],
        
    ],
    'modules' => [
        'apiv1' => [
            'class' => 'app\modules\apiv1\Apiv1Module',
                ],
            'api' => [
            'class' => 'app\modules\api\ApiModule',
                    ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [($_ENV['PRODUCCION']?'127.0.0.1':'*')],
    ];
}

return $config;
