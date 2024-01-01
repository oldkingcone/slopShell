<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ConnectException;

class genericClientExecuteCommands extends Client
{
    private CookieJar $cookieJar;

    public function __construct(array $config = [])
    {
        $config['http_errors'] = false;
        $config['debug'] = false;
        parent::__construct($config);
    }

    public function head($uri, array $options = []): ResponseInterface
    {
        try {
            return parent::head($uri, $options);
        }catch (ConnectException){
            throw new \Exception("\033[0;31mIt appears as though we were not able to connect properly. Please check the supplied information and try again. Are you sure your shell was successfully deployed?\033[0m");
        }
    }
}