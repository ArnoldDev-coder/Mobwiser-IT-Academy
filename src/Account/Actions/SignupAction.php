<?php

namespace App\Account\Actions;

use App\Account\Entity\UserEntity;
use App\Account\Table\UserTable;
use App\Authentification\Actions\Authentification;
use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Actions\RouterAware;
use Kernel\Database\Hydrator;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Kernel\Session\FlashMessage;
use Kernel\Validator;
use Psr\Http\Message\ResponseInterface;

class SignupAction
{

    use RouterAware;

    public function __construct(private Renderer         $renderer,
                                private UserTable        $userTable,
                                private FlashMessage     $flashMessage,
                                private Router           $router,
                                private Authentification $auth)
    {
    }

    public function __invoke(ServerRequest $request): string|ResponseInterface
    {
        $params = $request->getParsedBody();
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@account/signup');
        }
        if ($this->validate($request)->isValid()) {
            $userParams = [
                'name' => $params['name'],
                'last_name' => $params['last_name'],
                'username' => $params['username'],
                'email' => $params['email'],
                'who_invite' =>$params['who_invite'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT)
            ];
            $this->userTable->insert($userParams);
            $user = Hydrator::hydrate($userParams, UserEntity::class);
            $user->id = $this->userTable->getPdo()->lastInsertId();
            $this->auth->setUser($user);
            $this->flashMessage->success("Votre compte a bien été crée");
            return $this->redirect('account');
        }
        $errors = $this->validate($request)->getErrors();
        return $this->renderer->render('@account/signup', [
            'errors' => $errors,
            'user' => [
                'name' => $params['name'],
                'last_name' => $params['last_name'],
                'username' => $params['username'],
                'email' => $params['email'],
                'who_invite' => $params['who_invite']
            ]
        ]);
    }

    public function validate(ServerRequest $request, \PDO $pdo = null): Validator
    {
        $params = $request->getParsedBody();
        $validator = new Validator($params);
        return $validator->required(
            'username',
            'who_invite',
            'email',
            'name',
            'last_name',
            'password',
            'password_confirm'
        )->length('username', 5)
            ->email('email')
            ->exists('who_invite', $this->userTable->getTable(), $this->userTable->getPdo())
            ->length('name', 6)
            ->length('last_name', 6)
            ->confirm('password')
            ->length('password', 4)
            ->unique('username', $this->userTable)
            ->unique('email', $this->userTable);

    }
}