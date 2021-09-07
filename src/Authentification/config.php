<?php

use App\Account\Entity\UserEntity;
use App\Account\Table\UserTable;
use App\Authentification\Actions\Authentification;
use App\Authentification\Extensions\AuthExtension;
use Kernel\Middlewares\ForbiddenMiddleware;
use Kernel\Middlewares\RoleMiddleware;
use function DI\add;
use function DI\get;
use function DI\object;

return [
    'auth.login' => '/login',
    'auth.logout' => '/logout',
    'auth' => '/login/user',
    'auth.entity' => UserEntity::class,
    'extensions' => add([
        get(AuthExtension::class)
    ]),
    'role.admin' => 'admin',
    RoleMiddleware::class => object()->constructorParameter('role', get('role.admin')),
    Authentification::class => object(),
    UserTable::class => object()->constructorParameter('entity', get('auth.entity')),
    ForbiddenMiddleware::class => object()->constructorParameter('loginPath', get('auth.login'))
];