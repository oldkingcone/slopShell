<?php

define("o", "droppers/dynamic/obfuscated");
define("p", "droppers/dynamic/raw");
class dynamic_generator
{
    private function randomString(){
        return "#" . bin2hex(random_bytes(rand(5,25)));
    }

    private function junkCodeGen($seek_to_line){
        if (is_array($seek_to_line)){

        }
    }

    function begin_junk($file, $depth, $out)
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
            fputs(fopen($out, "a+"), "<?php eval(base64_decode(\"" .base64_encode(implode("", $b_encoded)) . "\"));");
        }else{
            $required_params = array();
            if (empty($mode)){
                array_push($required_params, ("mode cannot be empty. Please use either O for obfuscation or P for plain."));
            }else if (empty($file)){
                array_push($required_params, "file cannot be empty.");
            }else if (!is_file($file)){
                array_push($required_params, "File needs to be of type file not string.(think fopen)");
            }
            throw new Exception("Please rectify the following errors: \n" . array_values($required_params). "\n");
        }
    }

}