<?php

namespace curlStuff\validateMeMore;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception;
use GuzzleHttp\Cookie\CookieJar;


/**
 * @property array|array[] $headers
 * @property Client $client
 */
class talkToMeDamnit
{
    function __construct(string | null $url=null)
    {
        $this->headers = [];
        $this->client = new Client(["timeout" => 8, "base_uri" => $url]);
    }

    /**
     * @throws \Exception
     */
    function checkHost(string | null $uri=null): array | \Exception
    {
        if (is_null($uri)){
            throw new \Exception("Cannot get with supplied URI, please double check your info and make sure its correct.");
        }
        $response = $this->client->get($uri);
        if (str_contains($response->getHeader('I-Am-Alive'), "Yes") !== false){
            $newFile = $response->getHeader("NewName");
            $response_data = $response->getBody();
        }
        return ["New Filename" => $newFile, "Response Data" => $response_data];
    }

    /**
     * @throws \Exception
     */
    function checkMultiHost(array | null $targets=null): array | \Error
    {
        if (is_null($targets)){
            throw new \Error("Targets cannot be null.");
        }
        foreach ($targets as $tList){
            $jar = CookieJar::fromArray([
                "{$tList[2]}" => "{$tList[3]}"
            ], null);
            $this->headers['User-Agent'] = $tList[1];
            $this->headers['Cookie'] = $jar->getCookieByName($tList[2]);
            print_r($this->headers);
//            $this->client = new Client(["headers" => $this->headers]);
//            $response_Data = $this->client->get($tList['target']);
        }
        return [];
    }

}