<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'config',
        //'bitrix24',
        //'queue'
    ],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'config' => [
            'class' => 'abhimanyu\config\components\Config', // Class (Required)
            'db' => 'db',                                 // Database Connection ID (Optional)
            'tableName' => '{{%config}}',                        // Table Name (Optioanl)
            'cacheId' => 'cache',                              // Cache Id. Defaults to NULL (Optional)
            'cacheKey' => 'config.cache',                       // Key identifying the cache value (Required only if cacheId is set)
            'cacheDuration' => 100                                   // Cache Expiration time in seconds. 0 means never expire. Defaults to 0 (Optional)
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'mutex' => \yii\mutex\MysqlMutex::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
        ],
        'bitrix24' => [
            'class' => 'app\components\Bitrix24',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                'migrations',
                'vendor/abhi1693/yii2-config/migrations'
            ],
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
