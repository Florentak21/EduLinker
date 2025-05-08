<?php 
$pdo = require __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
return [
    'paths' => [
        'migrations' => __DIR__ .'/database/migrations',
        'seeds' => __DIR__ .'/database/seeders',
    ],

    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',

        'development' => [
            'name' => 'edu_linker',
            'connection' => $pdo
        ]
   
    ],

];