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
        $validator = new Validator($params);
        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@account/signup');
        }
        $validator->required('username', 'email', 'password', 'password_confirm')
            ->length('username', 5)
            ->email('email')
            ->confirm('password')
            ->length('password', 4)
            ->unique('username', $this->userTable)
            ->unique('email', $this->userTable);
        if ($validator->isValid()) {
            $userParams = [
                'firstname' => $params['firstname'],
                'lastName' => $params['lastName'],
                'username' => $params['username'],
                'email' => $params['email'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT)
            ];
            $this->userTable->insert($userParams);
            $user = Hydrator::hydrate($userParams, UserEntity::class);
            $user->id = $this->userTable->getPdo()->lastInsertId();
            $this->auth->setUser($user);
            $this->flashMessage->success("Votre compte a bien été crée");
            return $this->redirect('account');
        }
        $errors = $validator->getErrors();
        return $this->renderer->render('@account/signup', [
            'errors' => $errors,
            'user' => [
                'username' => $params['username'],
                'email' => $params['email']
            ]
        ]);
    }
}