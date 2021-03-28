<?php

class dynamic_generator
{
    private function randomString(){
        $a = '';
        $allowed_chars = "_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        switch (rand(0,15)){
            case 0:
                $f_name = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                $a = "function ". $f_name . "(string \$". substr(str_shuffle($allowed_chars), 0, rand(3,15)) ."){\n";
                for ($i = 0; $i <= rand(1,10); $i++) {
                    $a .= "\t\$" . substr(str_shuffle($allowed_chars), 0, rand(3, 15)) . " = \"" . bin2hex(random_bytes(rand(3, 10))) . "\";\n";
                }
                $a .= "\treturn false;\n}\n\n";
                $a .= "{$f_name}('" . substr(str_shuffle($allowed_chars), 0, rand(3,15)) . "');\n";
                break;
            case 1|3|5|7|9:
                $junked = array(
                    "1" => "a",
                    "2" => "caasdf",
                    "3" => bin2hex(random_bytes(rand(5,10)))
                );
                $a = "\$". substr(str_shuffle($allowed_chars), 0, rand(3,15)) . " = \"". $junked[rand(1,3)] . "\";\n";
                break;
            case 2|4|6|8|10:
                $a = "define('". bin2hex(random_bytes(rand(5,10))) . "', \"" . bin2hex(random_bytes(rand(5,100))) . "\");\n";
                break;
            case 11:
                $a = "#define('". bin2hex(random_bytes(rand(5,10))) . "', \"" . bin2hex(random_bytes(rand(5,100))) . "\");\n";
                break;
            case 12:
                $a = "// define('". bin2hex(random_bytes(rand(5,10))) . "', \"" . bin2hex(random_bytes(rand(5,100))) . "\");\n";
                break;
            case 13:
                $a = "\$tmp = tmpfile();\nfwrite(\$tmp,\"".substr(str_shuffle($allowed_chars), 0, rand(3,15))."\");\n";
                for ($i = 0; $i <= rand(10,15); $i++) {
                    $a .= "fwrite(\$tmp, base64_encode(\"" . substr(str_shuffle($allowed_chars), 0, rand(3, 15)) . "\"));\n";
                }
                $a .= "fseek(\$tmp, 0);\n";
                $a .= "fclose(\$tmp);\n";
                break;
            case 14:
                $a = "// why is it not launching??????\n";
                break;
            case 15:
                $yy = 20 . rand(1997,date("Y"));
                $mo = rand(1,12);
                $dd = rand(1,31);
                $a = "//created: \n". date("Y/m/d - l", mktime(null, null, null, $mo, $dd, $yy));
                break;
        }
        return $a;
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
        if (!empty($file) and is_file($file) and !empty($depth)){
            foreach (file($file) as $letter){
                foreach (str_split($letter) as $word) {
                    array_push($b_encoded, $char_map_lower[strtolower($word)]);
                }
            }
            switch (strtolower($mode)){
                case "ob":
                    fputs(fopen($out, "a+"), "<?php\n");
                    for ($i = 0; $i <= $depth; $i++) {
                        fputs(fopen($out, "a"), $this->randomString());
                    }
                    $key = bin2hex(random_bytes(rand(32,64)));
                    echo "Key: {$key}\nKey length: ". strlen($key)."\n";
                    $i = 0;
                    $encrypted = '';
                    $text = base64_encode(implode("", $b_encoded));
                    foreach (str_split($text) as $char) {
                        $encrypted .= chr(ord($char) ^ ord($key{$i++ % strlen($key)}));
                    }
                    fputs(fopen($out, "a"), "eval(base64_decode(\"" . base64_encode($encrypted) . "\"));");
                    break;
                default:
                    fputs(fopen($out, "a+"), "<?php\neval(base64_decode(\"" .base64_encode(implode("", $b_encoded)) . "\"));\n");
            }
        }else{
            $required_params = array();
            if (empty($mode)){
                array_push($required_params, ("mode cannot be empty. Please use either ob for obfuscation or n for plain."));
            }else if (empty($file)){
                array_push($required_params, "file cannot be empty.");
            }else if (!is_file($file)){
                array_push($required_params, "File needs to be of type file not string.(think fopen)");
            }
            throw new Exception("Please rectify the following errors: \n" . print_r($required_params) . "\n");
        }
    }

}