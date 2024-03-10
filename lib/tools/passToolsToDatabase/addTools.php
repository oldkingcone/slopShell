<?php

namespace tools\passToolsToDatabase;

use AllowDynamicProperties;

#[AllowDynamicProperties] class addTools
{
    function __construct()
    {
        $this->key = openssl_random_pseudo_bytes(32);
        $this->iv = openssl_random_pseudo_bytes(16);
        $this->nonce = openssl_random_pseudo_bytes(16);
        $this->additional_data = openssl_random_pseudo_bytes(16);
    }

    private function confirmToolOnDisk(string $toolPath): bool
    {
        return is_file($toolPath);
    }

    private function encryptTool(string $toolPath): array
    {
        $e = "";
        if (function_exists("openssl_encrypt")) {
            $f = fopen($toolPath, "r");
            while (!feof($f)) {
                foreach (str_split(fread($f, 4096), 1) as $ch) {
                    $e .= sodium_crypto_aead_chacha20poly1305_encrypt($ch, $this->additional_data, $this->nonce, $this->key);
                }
            }
            fclose($f);
            return [
                "encrypted_Data" => base64_encode($e),
                "toolPath" => $toolPath,
                "key" => base64_encode($this->key),
                "iv" => base64_encode($this->iv),
                "nonce" => base64_encode($this->nonce),
                "additional_data" => base64_encode($this->additional_data)
            ];
        }else{
            throw \Exception("Encryption functions are not enabled, cannot encrypt tool.");
        }
    }
}