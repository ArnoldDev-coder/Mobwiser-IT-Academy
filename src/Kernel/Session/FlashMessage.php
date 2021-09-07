<?php
namespace Kernel\Session;

final class FlashMessage
{
    private Session $session;
    private string $sessionKey = 'flash';
    private array|null $message = null;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    public function success(string $message):void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }
    public function error(string $message):void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }
    public function getFlash(string $type): ?string
    {
        if (is_null($this->message)){
            $this->message = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if(array_key_exists($type, $this->message)){
            return $this->message[$type];
        }return null;
    }
}