<?php

namespace crypto\decryptShellResponses;

class decryptor
{
    private array $responseData;

    function __construct(array $responseData = [])
    {
        $this->responseData = $responseData;
    }

}