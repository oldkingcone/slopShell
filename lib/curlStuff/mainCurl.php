<?php

namespace curlStuff;

use CurlHandle;

/**
 * @property CurlHandle|false $curlHandle
 * @property array|string[] $curlOpts
 */
class mainCurl
{
    function __construct(bool | null $use_tor, string | null $tor = null)
    {
        if (is_null($use_tor) or $use_tor === true){
            $this->setUseProxy($tor);
        }
        $this->curlHandle = curl_init();
        $this->curlOpts = [
            CURLOPT_USERAGENT => "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => true
        ];
    }

    function setUseProxy(string $tor): void
    {
        $this->curlOpts[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
        $this->curlOpts[CURLOPT_HTTPPROXYTUNNEL] = false;
        $this->curlOpts[CURLOPT_PROXY] = $tor;
        $this->curlOpts[CURLOPT_TIMEOUT] = 20;
        $this->curlOpts[CURLOPT_CONNECTTIMEOUT] = 20;
    }

    function executeCurlRequest(string | null $target, string | null $request_method = "GET", string | null $user_agent = "sp1.1", array | null $post_data=null): array
    {
        if (is_null($target)) {
            return [
                "Success" => false,
                "Data" => null
            ];
        }
        $this->curlOpts[CURLOPT_URL] = $target;
        $this->curlOpts[CURLOPT_USERAGENT] = $user_agent;
        if ($request_method === "POST" && !is_null($post_data)){
            $this->curlOpts[CURLOPT_POST] = true;
            $this->curlOpts[CURLOPT_POSTFIELDS] = $post_data;
        }
        curl_setopt_array($this->curlHandle, $this->curlOpts);
        $a = curl_exec($this->curlHandle);
        switch (curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE)){
              case 200:
                  return [
                      "Success" => false,
                      "Data" => $a,
                      "Target" => $target
                  ];
            case 404:
                $goods = curl_getinfo($this->curlHandle);
                if (isset($goods['X-Success']) && $goods['X-Success'] === 1){
                    foreach ($goods as $goodies => $yay){
                        if ($goodies === "Successful" && $yay === "HELLYEA"){
                            $new_name = $yay;
                        }
                    }
                }
                return [
                    "Success" => true,
                    "Data" => $a,
                    "NewFileName" => $new_name
                ];
        }
        return [
            "Success" => false,
            "Data" => null
        ];
    }

    function activateDropper()
    {

    }
}