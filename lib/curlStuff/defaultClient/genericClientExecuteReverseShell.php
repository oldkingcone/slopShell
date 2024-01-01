<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;

class genericClientExecuteReverseShell extends Client
{
    private array $revShellOpts;

    public function __construct(array $config = [], array $revShellOpts = [])
    {
        $this->revShellOpts = $revShellOpts;
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