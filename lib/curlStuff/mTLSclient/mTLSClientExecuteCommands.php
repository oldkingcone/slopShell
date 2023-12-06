<?php

namespace curlStuff\mTLSclient;

use GuzzleHttp\Client;

class mTLSClientExecuteCommands extends Client
{
    public function __construct(array $config = [], array $mtlsOpts = [])
    {
        foreach ($mtlsOpts as $opts => $o){
            $config[$opts] = $o;
        }
        parent::__construct($config);
    }

}