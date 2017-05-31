<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir
    )
)->registerNamespaces(
    array(
        'Phalcon'           => '/../../vendor/phalcon/icubator/Library/Phalcon/',
        'RW'                => $config->application->libraryDir,
        'RW\Controllers'    => $config->application->controllersDir,
        'RW\Controllers\Admin'    => $config->application->adminControllersDir,
        'RW\Controllers\Pos'      => $config->application->posControllersDir,
        'RW\Controllers\Services' => $config->application->servicesControllersDir,
        'RW\Controllers\Api' => $config->application->apiControllersDir,
        'RW\Models'         => $config->application->modelsDir,
        'RW\Plugin'         => $config->application->pluginDir,
    )
)->register();

require_once __DIR__ . '/../../vendor/autoload.php';
