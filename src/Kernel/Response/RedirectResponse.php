<?php

namespace Kernel\Response;

use GuzzleHttp\Psr7\Response;

class RedirectResponse extends Response
{
    public function __construct($url)
    {
        parent::__construct(301, ['Location' => $url]);
    }

}