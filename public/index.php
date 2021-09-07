<?php

use App\Account\AccountModule;
use App\Admin\AdminModule;
use App\Authentification\AuthModule;
use App\Contact\ContactModule;
use App\Home\HomeModule;
use GuzzleHttp\Psr7\ServerRequest;
use Kernel\App;
use Kernel\Middlewares\CsrfMiddleware;
use Kernel\Middlewares\DispatcherMiddleware;
use Kernel\Middlewares\ForbiddenMiddleware;
use Kernel\Middlewares\MethodMiddleware;
use Kernel\Middlewares\NotFoundMiddleware;
use Kernel\Middlewares\RoleMiddleware;
use Kernel\Middlewares\RouterMiddleware;
use Kernel\Middlewares\TrailingSlashMiddleware;
use Middlewares\Whoops;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function Http\Response\send;

require dirname(__DIR__) . '/vendor/autoload.php';

$whoops = (new Run())->pushHandler(new PrettyPageHandler())->register();
$app = (new App(dirname(__DIR__) . '/config/config.php'))
    ->addModule(HomeModule::class)
    ->addModule(ContactModule::class)
    ->addModule(AdminModule::class)
    ->addModule(AccountModule::class)
    ->addModule(AuthModule::class);

$container = $app->getContainer();

$app->make(Whoops::class)
    ->make(TrailingSlashMiddleware::class)
    ->make(RouterMiddleware::class)
    ->make(CsrfMiddleware::class)
    ->make(MethodMiddleware::class)
    ->make(ForbiddenMiddleware::class)
    ->make(RoleMiddleware::class, $container->get('admin.prefix'))
    ->make(DispatcherMiddleware::class)
    ->make(NotFoundMiddleware::class);

$response = $app->run(ServerRequest::fromGlobals());
send($response);

