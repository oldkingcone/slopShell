<?php

namespace proxyWorks;
use curlStuff\mainCurl;

/**
 * @property \CurlHandle|false $curlHandle
 */
class confirmProxy
{
    function __construct()
    {
        $this->curlHandle = new mainCurl(null, null);
    }
    public function checkIpAddress(string | null $test_site)
    {
        if (!is_bool($this->curlHandle)){
            curl_setopt($this->curlHandle, CURLOPT_URL, $test_site);
            $confirmIP = curl_exec($this->curlHandle);
            $status_code = curl_getinfo($this->curlHandle);
            switch ($status_code['Status']['http_code']){
                case 200:
                    curl_reset($this->curlHandle);
                    return $confirmIP;
                default:
                    curl_reset($this->curlHandle);
                    return "\033[0;31mIP PULL FAILED!!!!!!\033[0m";
            }
        }
        curl_reset($this->curlHandle);
        return "\033[0;31m[ !! ] FAILED TO EXECUTE CURL REQUEST!!! [ !! ]\033[0M";
    }

}