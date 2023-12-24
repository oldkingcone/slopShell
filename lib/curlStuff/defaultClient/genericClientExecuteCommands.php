<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class genericClientExecuteCommands extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function head($uri, array $options = []): ResponseInterface
    {
        return $this->head($uri);
    }
}