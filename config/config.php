<?php

use App\Authentification\Actions\Authentification;
use Kernel\Extensions\CsrfExtension;
use Kernel\Extensions\Extensions;
use Kernel\Extensions\FlashExtension;
use Kernel\Extensions\FormExtension;
use Kernel\Extensions\ModuleExtension;
use Kernel\Extensions\PaginationExtension;
use Kernel\Extensions\TimeExtension;
use Kernel\Factory\MailerFactory;
use Kernel\Middlewares\CsrfMiddleware;
use Kernel\Renderer\Renderer;
use Kernel\Renderer\RendererFactory;
use Kernel\Router\Router;
use Kernel\Session\Session;
use Symfony\Component\Mailer\Mailer;
use function DI\factory;
use function DI\get;
use function DI\object;

return [
    'viewPath' => dirname(__DIR__) . '/views',
    'extensions' => [
        get(Extensions::class),
        get(PaginationExtension::class),
        get(TimeExtension::class),
        get(FlashExtension::class),
        get(FormExtension::class),
        get(CsrfExtension::class)
    ],
    Session::class => object(),
    ModuleExtension::class => object()->constructorParameter('auth', get(Authentification::class)),
    CsrfMiddleware::class => object()->constructor(get(Session::class)),
    Router::class => object(),
    Renderer::class => factory(RendererFactory::class),
    PDO::class => static function() {
        return new PDO('mysql:dbname=school;host=127.0.0.1', 'wilfried', '20022606');
    },
    Mailer::class => factory(MailerFactory::class)
];