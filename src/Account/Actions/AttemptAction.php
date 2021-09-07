<?php

namespace App\Account\Actions;

use App\Authentification\Actions\Authentification;
use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Actions\RouterAware;
use Kernel\Session\FlashMessage;
use Kernel\Session\Session;
use Psr\Http\Message\ResponseInterface;

class AttemptAction
{
    private $messages = [

        'success' => "Vous etes connecté",
        'error' => "Identifiant ou mot de passe incorrect"
    ];
    use RouterAware;

    public function __construct(
        private Authentification $auth,
        private Session          $session,
        private FlashMessage     $flashMessage)
    {
    }

    public function __invoke(ServerRequest $request): ResponseInterface
    {
        $params = $request->getParsedBody();
        $user = $this->auth->login($params['username'], $params['password']);
        if ($user) {
            if ($user->roles === 'admin') {
                $this->flashMessage->success('Vous etes connecté en tant qu\'admin');
                return $this->redirect('dashboard');
            }
            $this->flashMessage->success($this->messages['success']);
            $this->session->delete('auth.redirect');
            return $this->redirect('account');
        }
        return $this->redirect('auth.login');

    }
}