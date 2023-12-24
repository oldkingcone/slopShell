<?php

namespace bots\bot_coms;
use curlStuff\mTLSclient\mTLSClientExecuteCommands;
use curlStuff\defaultClient;
class listenToMe
{
    private array $cert_data;
    private mTLSClientExecuteCommands $client;

    function __construct(array $cert_data, bool $useTLS)
    {
        if (count($cert_data) === 0 and $useTLS !== false){
            throw new \InvalidArgumentException("[ !! ] Length of cert data is 0, cannot communicate over MTLS, review data supplied and try again. [ !! ]");
        }else {
            $this->cert_data = $cert_data;
            $this->client = new  mTLSClientExecuteCommands();
        }
    }

    function enumerateSystem(array $target)
    {
        $this->client
    }
}