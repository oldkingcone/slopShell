<?php

namespace curlStuff\mTLSclient;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class mTLSClientValidateHosts extends Client
{
    public function __construct(array $config = [], array $mTLSOpts = [])
    {
        foreach ($mTLSOpts as $mTLSOpt => $opt){
            $config[$mTLSOpt] = $opt;
        }
        parent::__construct($config);
    }

    /**
     * 50 requests concurrently executing to validate all hosts in the database.
     * think of this as a ping/pong function.
     */
    public function mutualTLSClientOptsValidateHost(array $targets): array
    {
        $datas = [];
        $handleResponse = function ($response, $index) use (&$datas) {
            $headers = $response->getHeaders();
            foreach ($headers as $name => $values) {
                if (str_contains($name, "Successful")) {
                    foreach ($values as $value) {
                        if (str_contains($value, "HELLYEAH")) {
                            echo "We still own {$response->getEffectiveUrl()}\n";
                            $datas[$response->getEffectiveUrl()] = [
                                "NewShellName" => $headers['NewName'][0],
                                "Successful" => true
                            ];
                        }
                    }
                }
            }
        };
        $handleRejected = function ($reason, $index) {
            echo "Request at index: $index failed because of: $reason\n";
        };
        $promises = [];
        foreach ($targets as $singleTarget) {
            $request = new Request($singleTarget['method'], $singleTarget['uri']);
            for ($i = 0; $i < count($targets); $i++) {
                $promises[] = $this->sendAsync($request)
                    ->then($handleResponse)
                    ->otherwise($handleRejected);
            }
        }
        PromiseInterface\all($promises)->wait();
        return $datas;
    }
}