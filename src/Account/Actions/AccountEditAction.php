<?php

namespace App\Account\Actions;

use App\Account\Table\UserTable;
use App\Authentification\Actions\Authentification;
use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Renderer\Renderer;
use Kernel\Response\RedirectResponse;
use Kernel\Session\FlashMessage;
use Kernel\Validator;

class AccountEditAction
{
    private UserTable $userTable;
    private FlashMessage $flashMessage;
    private Authentification $auth;
    private Renderer $renderer;

    public function __construct(Renderer $renderer, Authentification $auth, UserTable $userTable, FlashMessage $flashMessage)
    {
        $this->userTable = $userTable;
        $this->flashMessage = $flashMessage;
        $this->auth = $auth;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequest $request): string|RedirectResponse
    {
        $user = $this->auth->getUser();
        $params = $request->getParsedBody();
        $validator = (new Validator($params))
            ->required('name', 'last_name', 'username')
        ->confirm('password');
        if ($validator->isValid()) {
            $userParams =[
                'name' => $params['name'],
                'username' => $params['username'],
                'last_name' => $params['last_name'],
            ];
            if (!empty($params['password'])) {
                    $userParams['password']= password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $this->userTable->update($user->id, $userParams );
            $this->flashMessage->success('Votre compte a bien été mis à jour !');
            return new RedirectResponse($request->getUri()->getPath());
        }
        $errors = $validator->getErrors();
        return $this->renderer->render('@account/profile', compact('user', 'errors'));
    }
}