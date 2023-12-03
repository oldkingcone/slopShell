<?php

namespace crypto\certMaker;

class mutualTLS
{
    private function makeCertificate()
    {

        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        //You can get the public key from the private key
        $publicKey = openssl_pkey_get_details($privateKey);
        $publicKey = $publicKey["key"];

        //Create a new OpenSSL configuration from the default template.
        $config = [
            "digest_alg" => "sha256",
            "x509_extensions" => "v3_ca",
            "req_extensions" => "v3_req",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "encrypt_key" => false,
        ];

        //Create the certificate
        $dn = [
            "countryName" => "US",
            "stateOrProvinceName" => "Your State",
            "localityName" => "Your City",
            "organizationName" => "Your Organization",
            "organizationalUnitName" => "Your Unit Name",
            "commonName" => "Your Domain",
        ];

        $csr = openssl_csr_new($dn, $privateKey, $config);
        $cert = openssl_csr_sign($csr, null, $privateKey, 365, $config);
        openssl_x509_export_to_file($cert, 'certificate.pem');
        openssl_pkey_export_to_file($privateKey, 'privateKey.pem');
        }
}