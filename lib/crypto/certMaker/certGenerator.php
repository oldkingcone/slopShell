<?php

namespace crypto\certMaker;

use Faker\Factory;

class certGenerator
{
    private string $sloppyHome;
    protected string $key;
    protected bool $useCert;

    public function __construct(string $sloppyHome, string | null $key, bool $use509)
    {
        if (is_null($key)){
            throw new \InvalidArgumentException("Key cannot be null");
        }
        $this->key = $key;
        $this->sloppyHome = $sloppyHome;
        $this->useCert = $use509;
    }
    protected function generateCrypto(): array
    {
        $fakeTheLandings = Factory::create();
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        ]);
        $keydeets = openssl_pkey_get_details($privateKey);
        $publicKey = $keydeets["key"];
        $config = [
            "digest_alg" => "sha512",
            "x509_extensions" => "v3_ca",
            "req_extensions" => "v3_req",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "encrypt_key" => false
        ];
        $dn = [
            "countryName" => $fakeTheLandings->countryCode(),
            "stateOrProvinceName" => trim($fakeTheLandings->state),
            "localityName" => trim($fakeTheLandings->city),
            "organizationName" => trim($fakeTheLandings->word()),
            "organizationalUnitName" => trim($fakeTheLandings->word()),
            "commonName" => trim($fakeTheLandings->word())
        ];
        $csr = openssl_csr_new($dn, $privateKey, $config);
        $cert = openssl_csr_sign($csr, null, $privateKey, 365, $config);
        if (!is_dir("{$this->sloppyHome}/certs/")){
            mkdir("{$this->sloppyHome}/certs/");
            mkdir("{$this->sloppyHome}/certs/pem");
            mkdir("{$this->sloppyHome}/certs/csr");
            mkdir("{$this->sloppyHome}/certs/private");
        }
        $pemFile = sprintf("%s/certs/private/%s.pem", $this->sloppyHome, bin2hex(openssl_random_pseudo_bytes(10)));
        $pkeyFile =  sprintf("%s/certs/pem/%s.pem", $this->sloppyHome, bin2hex(openssl_random_pseudo_bytes(10)));
        openssl_x509_export_to_file($cert, $pemFile);
        openssl_pkey_export_to_file($privateKey, $pkeyFile);
        return [
            "pkey" => $pkeyFile,
            "x509" => $pemFile,
            "cert" => $cert,
            "privateKey" => $privateKey
        ];
    }
}