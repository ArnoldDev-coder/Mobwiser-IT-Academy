<?php
require 'public/index.php';

$migrations = [];
$seeds = [];

foreach ($app->getModules() as $module) {
    if ($module::MIGRATIONS) {
        $migrations[] = $module::MIGRATIONS;
    }
    if ($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}

return
    [
        'paths' => [
            'migrations' => $migrations,
            'seeds' => $seeds
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => '127.0.0.1',
                'name' => 'school',
                'user' => 'wilfried',
                'pass' => '20022606',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
    ];