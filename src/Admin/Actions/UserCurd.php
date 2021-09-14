<?php

namespace App\Admin\Actions;

use App\Account\Table\UserTable;
use GuzzleHttp\Psr7\ServerRequest;
use JetBrains\PhpStorm\Pure;
use Kernel\Actions\CrudAction;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Kernel\Session\FlashMessage;
use Kernel\Validator;


class UserCurd extends CrudAction
{
    public string $viewPath = '@admin/users';
    public string $routePrefix = 'admin.users';



    #[Pure] public function __construct(
        Renderer     $renderer,
        UserTable    $table,
        Router       $router,
        FlashMessage $flashMessage,

    )
    {
        parent::__construct($renderer, $table, $router, $flashMessage);
    }

    public function getParams(ServerRequest $request, $item): array
    {
        $params = $request->getParsedBody();

        return array_filter($params, function ($key) {
            $params = [
                'username',
                'who_invite',
                'email',
                'name',
                'last_name',
                'due'
            ];
            return in_array($key, $params);
        }, ARRAY_FILTER_USE_KEY);
    }
}