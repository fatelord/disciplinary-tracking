<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/oracle_conn.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessIps' => ['127.0.0.1', '192.168.*'],
            // Role or list of roles with access to the viewer, null for everyone (if the user matches)
            'accessRoles' => ['admin'],
            // User ID or list of user IDs with access to the viewer, null for everyone (if the role matches)
            'accessUsers' => [1, 2, 200,100],
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'tracking' => [
            'class' => 'app\modules\tracking\tracker',

        ],
        'setups' => [
            'class' => 'app\modules\setup\setups',
        ],
        'report' => [
            'class' => 'app\modules\reporting\report',
            'defaultRoute' => 'report/report-case', //default controller
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DDihOSMwiDx4wt5-vW9v7a3eSgOGwlen',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\USER',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
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
        'formatter' => [
            'dateFormat' => 'dd/MMM/yyyy',
            'timeFormat'=>'h:mm:ss a',
            'datetimeFormat'=>'dd/MMM/yyyy H:mm:ss',
            //'defaultTimeZone'=>'America/Chicago'
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,//YII_DEBUG ? true : false,
            'showScriptName' => false,
            'rules' => [
                //default rules

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                //custom rules
                '/' => 'site/index',
                'discipline' => 'disciplinarytype/index',
                'casetypes' => 'casetype/index',
                'incident' => 'report/report/report-case',
                'first-case' => 'report/report/first-case',
                'first-office' => 'report/progress/first-office'

            ],
        ]


    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '41.89.65.170'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['127.0.0.1', '::1', '41.89.65.170'],
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => ['mymodel' => '@app/mygenerators/model/beforesave']
            ]
        ]
    ];
}

return $config;
