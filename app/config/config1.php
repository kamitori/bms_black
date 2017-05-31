<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

/**
 * Ultility functions & constants
 */
require_once APP_PATH . "/app/ultility.php";

require_once APP_PATH . "/app/system_defined.php";

require_once APP_PATH . "/app/other_config.php";

return new \Phalcon\Config(array(
    'database' => array(
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'root',
            'password'    => '',
            'dbname'      => 'bmsblack',
            'charset'     => 'utf8',
    ),
    'databases' => array(
        'bmsblack' => array(
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'root',
            'password'    => '',
            'dbname'      => 'bmsblack',
            'charset'     => 'utf8',
        ),
        'banhmisub' => array(
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'banhmisub_web',
            'password'    => '@AnvyTeam5',
            'dbname'      => 'banhmisub_web',
            'charset'     => 'utf8',
        ),
        'bmsweb_bmsblack'=>array(
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'bmsweb_banhmisub',
            'password'    => '123456',
            'dbname'      => 'bmsweb_bmsblack',
            'charset'     => 'utf8',
        ),
        'vimpact_demo' => array(
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'vimpact_demoweb',
            'password'    => '@AnvyTeam5',
            'dbname'      => 'vimpact_demoweb',
            'charset'     => 'utf8',
        )
    ),
    'application' => array(
        'appDir'         => APP_PATH . '/app/',
        'controllersDir' => APP_PATH . '/app/controllers/frontend',
        'adminControllersDir' => APP_PATH . '/app/controllers/admin',
        'posControllersDir'   => APP_PATH . '/app/controllers/pos',
        'servicesControllersDir' => APP_PATH . '/app/controllers/services',
        'apiControllersDir' => APP_PATH . '/app/controllers/api',
        'modelsDir'      => APP_PATH . '/app/models/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'pluginDir'      => APP_PATH . '/app/plugin/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'baseUri'        => URL,
        'cryptSalt'      => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'viewCacheTime'  => 86400
    ),
    'smtp' => array(
            'driver'     => 'smtp',
            'host'     => 'smtp.gmail.com',
            'port'        => 587,
            'username'    => 'banhmisub2016@gmail.com',
            'password'    => 'bmsadmin2016',
            'encryption' => 'tls',
            'from'       => [
                'email' => 'banhmisub2016@gmail.com',
                'name'  => 'Banh Mi SUB'
            ]
    ),
    
));
