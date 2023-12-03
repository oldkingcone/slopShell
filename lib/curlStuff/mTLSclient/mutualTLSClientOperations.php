<?php

namespace curlStuff\mTLSclient;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class mutualTLSClientOperations extends Client
{
    private bool $mTLS;
    private array $options;

    public function __construct(array $config = [], bool $useMtls = false, array $mTLSOpts = [])
    {
        parent::__construct($config);
        if ($useMtls){
            $this->mTLS = $useMtls;
            $this->options = $mTLSOpts;
        }
    }
    /**
     *Async get, also designed for parallelism, bulk operations for compromised hosts.
     */
    private function getAsync($uri, array $options = []): PromiseInterface
    {
    }

    /**
     * Function to check if we still control the compromised boxes.
     * This will check for two headers being set in the response objects.
     * Designing this to be Async in nature, as many hosts can be in a database.
     * */
    private function headAsync($uri, array $options = []): PromiseInterface
    {

    }

    /**
     * Async post, designed for parallelism, bulk operations for compromised hosts.
     */
    private function postAsync($uri, array $options = []): PromiseInterface
    {
    }

    /**
     *One single call to manage all operations, need will be determined at calling.
     */
    public function mutualTLSClientOpts()
    {
        return;
    }
}