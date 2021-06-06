<?php
define('allowed_chars', "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

class dynamic_generator
{

    private function genCert(int $CertStrength, string $certAlgo, string $keyType, string $digest, array $common)
    {
        if (!is_null($CertStrength) and is_int($CertStrength) and !is_null($certAlgo) and count($common) > 0) {
            if ($CertStrength < 4096) {
                echo "Are you sure you want such a small cert?\n";
                echo "This is for client auth and mutual tls, make sure its a bit higher.\n";
                return 0;
            } else {
                switch (strpos($keyType, "curve") or strpos($keyType, "prime") or strpos($keyType, "secp")) {
                    case true:
                        $keyInfo = array(
                            "private_key_type" => OPENSSL_KEYTYPE_EC,
                            "curve_name" => $keyType
                        );
                        break;
                    default:
                        $keyInfo = array(
                            "private_key_type" => $keyType,
                            "private_key_bits" => $CertStrength
                        );
                        break;
                }
                $privKey = openssl_pkey_new($keyInfo);
                $csr = openssl_csr_new($common, $privKey, array("digest_alg" => $digest));
                $x509 = openssl_csr_sign($csr, null, $privKey, $days = 365, array("digest_alg" => $digest));
                openssl_csr_export_to_file($csr, "YOURCSR.csr");
                openssl_x509_export_to_file($x509, "YOURX509CERT.crt");
                openssl_pkey_export_to_file($privKey, "YOURPRIVKEY.pem");
                echo "It is very important for you to know these are not password protected.\n";
                while (($e = openssl_error_string()) !== false) {
                    echo $e . "\n";
                }
            }
        } else {
            throw new ErrorException("Missing Needed information. {$CertStrength} - {$certAlgo}");
        }
        return 0;
    }

    private function junkLoops(int $needsleep, int $sleep_depth): string
    {
        $for_looper = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $iterator = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $iterator_second = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $container = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $container_two = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $foreach_key = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $foreach_value = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
        $types = array(
            1 => "array",
            2 => "string",
            3 => "int"
        );
        $loop_types = array(
            1 => "while",
            2 => "if",
            3 => "for",
            4 => "foreach"
        );
        $operations = array(
            1 => "or",
            2 => "and",
            3 => "||",
            4 => "&&"
        );
        $checks = array(
            1 => "is_null",
            2 => "empty",
            3 => "isset",
        );
        $pos_neg = array(
            1 => "!",
            2 => ""
        );
        $sleeper = null;
        switch ($needsleep) {
            case 1:

                if ($sleep_depth >= 4.6) {
                    $sleep_length = rand(10000, 50000);
                } else {
                    $sleep_length = rand(1000, 9000);
                }
                $sleeper = <<<SLEEPER
    $loop_types[4] ( \$$for_looper as \$$foreach_key => \$$foreach_value ){
        for (\$$iterator = 0; \$$iterator < $sleep_length; \$$iterator++){
            \$$container = $sleep_length/2;
            \$$container_two = array();
            if (\$$iterator == \$$container and array_count_values(\$$container_two) != 10){
                array_push(\$$container_two, rand(1,500));
                \$$iterator = 0;
            }
            \$$iterator_second.=str_repeat("$iterator_second", 50);
        }
        return hash('whirlpool', \$_SERVER['SCRIPT_FILENAME'], FALSE);
    }
SLEEPER;
                break;
            case 2:
                $loo = $loop_types[rand(1, 2)];
                $che = $checks[rand(1, 3)];
                $opp = $operations[rand(1, 4)];
                $ty = $types[rand(1, 3)];
                $po = $pos_neg[rand(1, 2)];
                $sleeper = <<<SLEEPER2
    $loo($po$che(\$$iterator) $opp $po$che(\$$iterator_second) $opp ($ty)\$$container){
        $loo(\$$iterator $opp \$$iterator_second){
            return true;
        }
        $loo($po$che(\$$iterator_second)){
            return false;
        }
        $loo($po$che(\$$iterator)){
            return true;
        }
    }\n
SLEEPER2;
                break;
        }
        return $sleeper;
    }

    private function randomString()
    {
        $a = '';
        $f_name = substr(str_shuffle(allowed_chars), 0, rand(3, 45));
        switch (rand(0, 15)) {
            case 0:
                $a = "function " . $f_name . "(string \$" . substr(str_shuffle(allowed_chars), 0, rand(3, 45)) . "){\n";
                for ($i = 0; $i <= rand(1, 15); $i++) {
                    $a .= "\t\$" . substr(str_shuffle(allowed_chars), 0, rand(3, 45)) . " = \"" . bin2hex(random_bytes(rand(3, 70))) . "\";\n";
                }
                $a .= "\treturn false;\n}\n\n";
                $a .= "{$f_name}('" . substr(str_shuffle(allowed_chars), 0, rand(3, 45)) . "');\n";
                break;
            case 1 | 3 | 5 | 7 | 9:
                $junked = array(
                    1 => substr(str_shuffle(allowed_chars), 0, rand(3, 80)),
                    2 => base64_encode(substr(str_shuffle(allowed_chars), 0, rand(3, 80))),
                    3 => bin2hex(random_bytes(rand(5, 80)))
                );
                $a = "\$" . substr(str_shuffle(allowed_chars), 0, rand(3, 15)) . " = \"" . $junked[rand(1, 3)] . "\";\n";
                break;
            case 2 | 4 | 6 | 8 | 10:
                $a = "define('" . bin2hex(random_bytes(rand(3, 90))) . "', \"" . bin2hex(random_bytes(rand(5, 100))) . "\");\n";
                break;
            case 11:

                break;
            case 12:
                $a = "function " . $f_name . "(string \$" . substr(str_shuffle(allowed_chars), 0, rand(3, 45)) . "){\n";
                $a .= $this->junkLoops(rand(1, 2), rand(1, 5));
                $a .= "\t\n}\n\n";
                $a .= "{$f_name}('" . substr(str_shuffle(allowed_chars), 0, rand(3, 45)) . "');\n";
                break;
            case 13:
                $obfs_tmp = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
                $a = "\$" . $obfs_tmp . " = tmpfile();\nfwrite(\$" . $obfs_tmp . ",\"" . substr(str_shuffle(allowed_chars), 0, rand(3, 15)) . "\");\n";
                for ($i = 0; $i <= rand(1, 10); $i++) {
                    $a .= "fwrite(\$" . $obfs_tmp . ", \"" . base64_encode(substr(str_shuffle(allowed_chars), 0, rand(3, 15))) . "\");\n";
                }
                $a .= "fseek(\$" . $obfs_tmp . ", 0);\n";
                $a .= "\$" . substr(str_shuffle(allowed_chars), 0, rand(1, 90)) . " = file(\$" . $obfs_tmp . ");\n";
                $a .= "fclose(\$" . $obfs_tmp . ");\n";
                break;
            case 14:
                $a = "// why is it not launching??????\n";
                break;
            case 15:
                $yy = rand(1997, (int)date("Y"));
                $mo = rand(1, 12);
                $dd = rand(1, 31);
                $a .= "//created: \n" . date("Y/m/d - l", mktime(null, null, null, $mo, $dd, $yy));
                break;
        }
        return $a;
    }

    private function encryptFile($filename)
    {
        echo "Coming soon!";
    }

    function begin_junk($file, $depth, $out, $mode)
    {
        $char_map_lower = array(
            "a" => "\\x61",
            "b" => "\\x62",
            "c" => "\\x63",
            "d" => "\\x64",
            "e" => "\\x65",
            "f" => "\\x66",
            "g" => "\\x67",
            "h" => "\\x68",
            "i" => "\\x69",
            "j" => "\\x6A",
            "k" => "\\x6B",
            "l" => "\\x6C",
            "m" => "\\x6D",
            "n" => "\\x6E",
            "o" => "\\x6F",
            "p" => "\\x70",
            "q" => "\\x71",
            "r" => "\\x72",
            "s" => "\\x73",
            "t" => "\\x74",
            "u" => "\\x75",
            "v" => "\\x76",
            "w" => "\\x77",
            "x" => "\\x78",
            "y" => "\\x79",
            "z" => "\\x7A",
            " " => "\\x20",
            "" => "\\x00",
            "!" => "\\x21",
            "?" => "\\x3F",
            "\"" => "\\x22",
            "'" => "\\x27",
            "\\" => "\\x5C",
            "/" => "\\x2F",
            "=" => "\\x3D",
            ">" => "\\x3E",
            "<" => "\\x3C",
            ":" => "\\x3A",
            ";" => "\\x3B",
            "-" => "\\x2D",
            "[" => "\\x5B",
            "]" => "\\x5D",
            "+" => "\\x2B",
            ")" => "\\x29",
            "(" => "\\x28",
            "%" => "\\x25",
            "^" => "\\x5E",
            "*" => "\\x2A",
            "&" => "\\x26",
            "#" => "\\x23",
            "@" => "\\x40",
            "`" => "\\x60",
            "~" => "\\x5F",
            "|" => "\\x7C",
            "}" => "\\x7D",
            "{" => "\\x7B",
            "\r" => "\\x0D",
            "\n" => "\\x0A",
            "$" => "\\x24",
            "_" => "\\x5F",
            "," => "\\x2C",
            "." => "\\x2E",
            "0" => "\\x30",
            "1" => "\\x31",
            "2" => "\\x32",
            "3" => "\\x33",
            "4" => "\\x34",
            "5" => "\\x35",
            "6" => "\\x36",
            "7" => "\\x37",
            "8" => "\\x38",
            "9" => "\\x39",
        );
        $b_encoded = array();
        if (!empty($file) and is_file($file) and !empty($depth)) {
            foreach (file($file) as $letter) {
                foreach (str_split($letter) as $word) {
                    array_push($b_encoded, $char_map_lower[strtolower($word)]);
                }
            }
            switch (strtolower($mode)) {
                case "n":
                    $d = "<?php\neval(base64_decode(\"" . base64_encode(implode("", $b_encoded)) . "\"));\n";
                    fputs(fopen($out, "w"), $d, strlen($d));
                    break;
                case "ob":
                    $out_file = fopen($out, "w");
                    $key = bin2hex(random_bytes(rand(32, 64)));
                    echo "Key: {$key}\nKey length: " . strlen($key) . "\n";
                    fputs($out_file, "<?php\n", strlen("<?php\n"));
                    for ($i = 0; $i <= $depth; $i++) {
                        $ax = $this->randomString();
                        fputs($out_file, $ax, strlen($ax));
                    }
                    $returned = $this->randomString();
                    fputs($out_file, $returned, strlen($returned));
                    $i = 0;
                    $encrypted = '';
                    $text = base64_encode(implode("", $b_encoded));
                    foreach (str_split($text) as $char) {
                        $encrypted .= chr(ord($char) ^ ord($key{$i++ % strlen($key)}));
                    }
                    $b = base64_encode($encrypted);
                    $fun = substr(str_shuffle(allowed_chars), 0, rand(3, 25));
                    $ad = substr(str_shuffle(allowed_chars), 0, rand(3, 16));
                    $da = substr(str_shuffle(allowed_chars), 0, rand(3, 17));
                    $f_name = substr(str_shuffle(allowed_chars), 0, rand(3, 18));
                    $values = substr(str_shuffle(allowed_chars), 0, rand(3, 19));
                    $chars = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
                    $iterator = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
                    $tt = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
                    $tt_name = substr(str_shuffle(allowed_chars), 0, rand(3, 15));
                    if (!is_null($key)) {
                        $a = "\$" . $f_name . " = \"" . (string)$key . "\";";
                    }
                    $do = <<<FULL
function $fun(string \$$values)
{
    $a
    \$$iterator = 0;
    if (!empty(\$$values)){
        \$$ad = \$$values;
        foreach (str_split(\$$ad) as \$$chars){
            \$$da .= chr(ord(\$$f_name{\$$iterator++ % strlen(\$$f_name)}) ^ ord(\$$chars));
        }
    }
    \$$tt = tempnam(sys_get_temp_dir(),"$tt_name");
    fwrite(fopen(\$$tt, "a+"), base64_decode(\$$da));
    if (substr(php_uname(), 0, 7) == 'Windows') {
        system("start /b php -F \$$tt");
    }else{
        system("nohup php -F \$$tt &");
    }
}
$fun(base64_decode("$b"));
FULL;
                    fputs($out_file, $do, strlen($do));
                    fclose($out_file);
                    break;
            }
        } else {
            $required_params = array();
            if (empty($mode)) {
                array_push($required_params, ("mode cannot be empty. Please use either ob for obfuscation or n for plain."));
            } else if (empty($file)) {
                array_push($required_params, "file cannot be empty.");
            } else if (!is_file($file)) {
                array_push($required_params, "File needs to be of type file not string.(think fopen)");
            }
            throw new Exception("Please rectify the following errors: \n" . print_r($required_params) . "\n");
        }
    }

}