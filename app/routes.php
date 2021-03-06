<?php
$router = new Phalcon\Mvc\Router(false);
$router->removeExtraSlashes(true);
/*
 ********************
 *   - Frontend -
 ********************
 */

// fb page
$router->add('/app/erros/showhide/:params', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Errors',
    'action'        => 'showHideError',
    'params'        => 1
));
$router->add('/app/testing', array(
    'namespace'     => 'RW\Controllers\Api',
    'controller'    => 'App',
    'action'        => 'Test',
    'params'        => 1
));
$router->add('/security/fb-app/checkUsers/:params', array(
    'namespace'     => 'RW\Controllers\Api',
    'controller'    => 'App',
    'action'        => 'CheckPlay',
    'params'        => 1
));
$router->add('/security/fb-app/TestCoupon/:params', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'TestCoupon',
    'params'        => 1
));
$router->add('/security/fb-app/updateVoucher', array(
    'namespace'     => 'RW\Controllers\Api',
    'controller'    => 'App',
    'action'        => 'updateVoucher',
));
$router->add('/security/fb-app/createnewdata', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'createNewFileNow',
));
$router->add('/security/fb-app/whatyourfavorite', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'facebook',
));
$router->add('/security/fb-app/checkdate', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'checkdate',
));
$router->add('/security/fb-app/getLink', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'getLink',
));
$router->add('/security/fb-app/getInformation/:params', array(
    'namespace'     => 'RW\Controllers\Api',
    'controller'    => 'App',
    'action'        => 'getInformation',
    'params'        => 1
));
// Categories ===============================================================

$router->add('/{categoryName:[a-zA-Z0-9\_\-]+}/{productName:[a-zA-Z0-9\_\-]+}.html', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'product',
));

$router->add('/{categoryName:[a-zA-Z0-9\_\-]+}', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Categories',
    'action'        => 'index',
));

// End Categories ===========================================================

$router->add('/pages/{pageName:[a-zA-Z0-9\_\-]+}', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Pages',
    'action'        => 'index',
));

$router->add('/faqs/{pageName:[a-zA-Z0-9\_\-]+}', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Pages',
    'action'        => 'faqs',
));

$router->add('/carts', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'index',
));
$router->add('/carts/check-out', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'checkout',
));
$router->add('/carts/do-check-out', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'Docheckout',
));
$router->add('/carts/remove-link-item/:params', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'removeLinkItem',
    'params'        => 1
));
$router->add('/carts/update-quantity', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'updateCart'
));
$router->add('/carts/save-address', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'saveAddress'
));
$router->add('/carts/detail', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'detail'
));
$router->add('/carts/pay-ment', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'payMent'
));
$router->add('/carts/processing', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'processing'
));
$router->add('/carts/view-order/{order_id}', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 'viewOrder'
));
$router->add('/carts/:action', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Carts',
    'action'        => 1
))->convert('action', function ($action) {
    return lcfirst(\Phalcon\Text::camelize($action));
});


$router->add('/indexs/:action', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Index',
    'action'        => 1
))->convert('action', function ($action) {
    return lcfirst(\Phalcon\Text::camelize($action));
});

$router->add('/products/:action/:params', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Products',
    'action'        => 1,
    'params'        => 2
))->convert('action', function ($action) {
    return lcfirst(\Phalcon\Text::camelize($action));
});


$router->add('/user/:action', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Users',
    'action'        => 1
))->convert('action', function ($action) {
    return lcfirst(\Phalcon\Text::camelize($action));
});
$router->add('/user/', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Users',
    'action'        => 'index'
));
$router->add('/users/:action', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Users',
    'action'        => 1
))->convert('action', function ($action) {
    return lcfirst(\Phalcon\Text::camelize($action));
});
$router->add('/users', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Users',
    'action'        => 'index'
));

$router->add('/', array(
    'namespace'     => 'RW\Controllers',
    'controller'    => 'Index',
    'action'        => 'index',
));

/*
 ********************
 * - End Frontend -
 ********************
 */
foreach(['Admin', 'Pos', 'Services','Api'] as $module) {
    $uri = strtolower($module);
    $router->add('/'. $uri, array(
        'namespace'     => 'RW\Controllers\\'.$module,
        'controller'    => 'Index',
        'action'        => 'index',
    ));

    $router->add('/'. $uri .'/:controller/:action/:params', array(
        'namespace'     => 'RW\Controllers\\'. $module,
        'controller'    => 1,
        'action'        => 2,
        // 'params'        => 3,
        'paramsList'        => 3,
    ))->convert('controller', function ($controller) {
        return \Phalcon\Text::camelize($controller);
    })->convert('action', function ($action) {
        return lcfirst(\Phalcon\Text::camelize($action));
    });

    $router->add('/'. $uri .'/:controller', array(
        'namespace'     => 'RW\Controllers\\'. $module,
        'controller'    => 1
    ))->convert('controller', function ($controller) {
        return \Phalcon\Text::camelize($controller);
    });
}
return $router;