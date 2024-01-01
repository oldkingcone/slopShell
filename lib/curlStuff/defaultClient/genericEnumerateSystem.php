<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;

class genericEnumerateSystem extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

}