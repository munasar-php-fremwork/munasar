<?php 

require __DIR__. '/../vendor/autoload.php';
session_start();
use Respect\Validation\Validator as v;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'Your Databse',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]
    ],
]);



$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();

$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig( __DIR__. '/../resource/views', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->getEnvironment()->addGlobal('flash',$container->flash);

    return $view;
};

$container['HomeController'] = function ($container) {
    return new \Munasar\Controllers\HomeController($container);
};

$container['validator'] = function ($container) {
    return new Munasar\Validation\Validator;
};


$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

//Override the default Not Found Handler after App
unset($app->getContainer()['notFoundHandler']);
$app->getContainer()['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $response = new \Slim\Http\Response(404);
        return require __DIR__.'/../resource/404/error.php';
    };
};

$app->add(new Munasar\Middleware\ValidationErrorsMiddleware($container));
$app->add(new Munasar\Middleware\OldInputMiddleware($container));
$app->add(new Munasar\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);
v::with('Munasar\\Validation\\Rules\\');
require __DIR__ . '/../app/routes.php';