<?php 
$pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '',
[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
return [
    'paths' => [
        'migrations' => __DIR__ .'/db/migrations',
        'seeds' => __DIR__ .'/db/seeds',
    ],

    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',

        'development' => [
            'name' => 'mvc',
            'connection' => $pdo
        ]
   
    ],

];