<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;

class genericClientExecuteReverseShell extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function executeReverseShell(array $options = [])
    {
        // code for executing reverse shell
        if (is_null(array_key_last($options))){
            throw new \InvalidArgumentException("\033[0;31moptions cannot be null.\033[0m");
        }
    }

}