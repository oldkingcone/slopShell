<?php

class dynamic_generator
{
    private function randomString(){
        $a = '';
        $types = array(
            1 => "array",
            2 => "string",
            3 => "int"
        );
        $loop_types = array(
            1 => "while",
            2 => "if"
        );
        $operations = array(
            1 => "!",
            2 => "or",
            3 => "and",
            4 => "||",
            5 => "&&"
        );
        $allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
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
                    "1" => substr(str_shuffle($allowed_chars), 0, rand(3,15)),
                    "2" => base64_encode(substr(str_shuffle($allowed_chars), 0, rand(3,15))),
                    "3" => bin2hex(random_bytes(rand(5,10)))
                );
                $a = "\$". substr(str_shuffle($allowed_chars), 0, rand(3,15)) . " = \"". $junked[rand(1,3)] . "\";\n";
                break;
            case 2|4|6|8|10:
                $a = "define('" . bin2hex(random_bytes(rand(3, 10))) . "', \"" . bin2hex(random_bytes(rand(5, 100))) . "\");\n";
                break;
            case 11:
                $a = "function ". substr(str_shuffle($allowed_chars), 0, rand(3,15)). "(".$types[rand(1,3)] ." \$". substr(str_shuffle($allowed_chars), 0, rand(3,15)). ")\n{\n";
                $a .= "\t".$loop_types[rand(1,2)]." (".substr(str_shuffle($allowed_chars), 0, rand(3,15))."){\n\t";
                $a .= "\t\$".substr(str_shuffle($allowed_chars), 0, rand(3,15))."= \"". base64_encode(substr(str_shuffle($allowed_chars), 0, rand(3,15))) . "\";\n\t";
                $a .= "\tbreak;\n\t}\n}\n";
                break;
            case 12:
                $a = "define('". bin2hex(random_bytes(rand(1,35))) . "', \"" . bin2hex(random_bytes(rand(5,100))) . "\");\n";
                break;
            case 13:
                $a = "\$tmp = tmpfile();\nfwrite(\$tmp,\"".substr(str_shuffle($allowed_chars), 0, rand(3,15))."\");\n";
                for ($i = 0; $i <= rand(10,15); $i++) {
                    $a .= "fwrite(\$tmp, \"" . base64_encode(substr(str_shuffle($allowed_chars), 0, rand(3, 15))) . "\");\n";
                }
                $a .= "fseek(\$tmp, 0);\n";
                $a .= "\$".substr(str_shuffle($allowed_chars), 0, rand(1,10))." = file(\$tmp);\n";
                $a .= "fclose(\$tmp);\n";
                break;
            case 14:
                $a = "// why is it not launching??????\n";
                break;
            case 15:
                $yy = rand(1997,(int)date("Y"));
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
                    $key = bin2hex(random_bytes(rand(32,64)));
                    echo "Key: {$key}\nKey length: ". strlen($key)."\n";
                    $allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    fputs(fopen($out, "a+"), "<?php\n");
                    for ($i = 0; $i <= $depth; $i++) {
                        fputs(fopen($out, "a"), $this->randomString());
                    }
                    $returned = $this->randomString();
                    fputs(fopen($out, "a"), $returned);
                    $i = 0;
                    $encrypted = '';
                    $text = base64_encode(implode("", $b_encoded));
                    foreach (str_split($text) as $char) {
                        $encrypted .= chr(ord($char) ^ ord($key{$i++ % strlen($key)}));
                    }
                    $b = base64_encode($encrypted);
                    $fun = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $ad = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $da = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $f_name = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $values = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $chars = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    $iterator = substr(str_shuffle($allowed_chars), 0, rand(3,15));
                    if (!is_null($key)){
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
    base64_decode("ZXZhbCg=")."\"".base64_decode(\$$da)."\"".base64_decode("KQ==");
}
$fun(base64_decode("$b"));
FULL;
                    fputs(fopen($out, "a"), $do);
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