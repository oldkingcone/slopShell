<?php

namespace curlStuff\validateMeMore;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception;
use GuzzleHttp\Pool;


/**
 * @property array|array[] $headers
 * @property Client $client
 */
class talkToMeDamnit
{
    /**
     * @throws \HttpUrlException
     * @throws \Exception
     */
    function __construct(array | null $headers=null, string | null $url=null)
    {
        if (is_null($headers)){
            throw new \Exception("Headers cannot be null.");
        }
        if (is_null($url)){
            throw new \HttpUrlException("URL cannot be null.");
        }
        $this->headers = $headers;
        $this->client = new Client(["headers" => $headers, "timeout" => 8, "base_uri" => $url]);
    }

    /**
     * @throws GuzzleException
     */
    function checkHost(string | null $uri=null): array | \Exception | GuzzleException
    {
        if (is_null($uri)){
            throw new \Exception("Cannot get with supplied URI, please double check your info and make sure its correct.");
        }
        $response = $this->client->get($uri);
        if ($response->getHeader('X-Success') === "1"){
            $newFile = $response->getHeader("NewName");
            $response_data = $response->getBody();
        }
        return ["New Filename" => $newFile, "Response Data" => $response_data];
    }

    /**
     * @throws GuzzleException
     */
    function checkMultiHost(array | null $targets=null): array | \Error | GuzzleException
    {
        if (is_null($targets)){
            throw new \Error("Targets cannot be null.");
        }
        foreach ($targets as $tList){
            $this->headers['User-Agent'] = $tList['User-Agent'];
            $this->client = new Client(["headers" => $this->headers]);
            $response_Data = $this->client->get($tList['target']);
        }
        return [];
    }

}