<?php

namespace Kernel\Factory;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

class MailerFactory
{
    public function __invoke(): Mailer
    {
        $eventDispatcher = new EventDispatcher();
        $transport = Transport::fromDsn('smtp://localhost', $eventDispatcher);
        return new Mailer($transport, null, $eventDispatcher);

    }
}