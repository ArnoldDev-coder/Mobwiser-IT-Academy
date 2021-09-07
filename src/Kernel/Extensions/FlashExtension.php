<?php

namespace Kernel\Extensions;

use Kernel\Session\FlashMessage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashExtension extends AbstractExtension
{
    private FlashMessage $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('flash', [$this, 'flash'])
        ];
    }
    public function flash(string $type): ?string
    {
        return $this->flashMessage->getFlash($type);
    }
}