<?php

namespace App\Contact\Actions;

use Kernel\Renderer\Renderer;
use Kernel\Response\RedirectResponse;
use Kernel\Session\FlashMessage;
use Kernel\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


class ContactAction
{
    private array $message = [
        'success' => 'Merci pour votre message nous vous contacterons le plus vite possible  !',
        'error' => 'Message suite aux erreurs rencontrées, Merci de les corriger !',
        'mailError' => "Une erreur est survenue lors de l'envoie d'email"
    ];
    private $renderer;
    private FlashMessage $flashMessage;
    private Mailer $mailer;

    public function __construct(ContainerInterface $container, Mailer $mailer)
    {
        $this->renderer = $container->get(Renderer::class);
        $this->flashMessage = $container->get(FlashMessage::class);
        $this->mailer = $mailer;
    }

    public function __invoke(ServerRequestInterface $request): string|RedirectResponse
    {

        if ($request->getMethod() === 'GET') {
            return $this->renderer->render('@contact/contact');
        }
        $validator = new Validator($request->getParsedBody());
        $validator->required('name', 'email', 'content')
            ->length('name', 4)
            ->email('email')
            ->length('content', 5, 20);
        if ($validator->isValid()){
            return $this->sendMail($request);
        }else{
            $errors = $validator->getErrors();
            $this->flashMessage->error($this->message['error']);
            return  $this->renderer->render('@contact/contact', compact('errors'));
        }
    }
    private function sendMail(ServerRequestInterface $request): RedirectResponse
    {
        $params = $request->getParsedBody();
        try {
            $email = (new Email())
                ->from($params['email'])
                ->to('you@example.com')
                //->replyTo('contact@buzz.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Formulaire de contact')
                ->text($this->renderer->render('@contact/email/message', $params))
                ->html($this->renderer->render('@contact/email/message', $params));
            $this->mailer->send($email);
            $this->flashMessage->success($this->message['success']);
        }catch (TransportExceptionInterface $e) {
            $this->flashMessage->error($this->message['mailError']);
        }
        return new redirectResponse((string)$request->getUri());
    }
}