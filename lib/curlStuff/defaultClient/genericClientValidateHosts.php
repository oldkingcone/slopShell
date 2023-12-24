<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;

class genericClientValidateHosts extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}