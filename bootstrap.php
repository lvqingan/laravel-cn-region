<?php

/**
 * Register composer auto  loader
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Initialize Capsule
 */
$capsule = new Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => ':memory:',
    'prefix'   => '',
]);

$capsule->setEventDispatcher(new Illuminate\Events\Dispatcher(new Illuminate\Container\Container));

$capsule->bootEloquent();

$capsule->setAsGlobal();
