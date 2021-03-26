<?php


$char_map_lower = array(
    "a" => "\x61",
    "b" => "\x62",
    "c" => "\x63",
    "d" => "\x64",
    "e" => "\x65",
    "f" => "\x66",
    "g" => "\x67",
    "h" => "\x68",
    "i" => "\x69",
    "j" => "\x6A",
    "k" => "\x6B",
    "l" => "\x6C",
    "m" => "\x6D",
    "n" => "\x6E",
    "o" => "\x6F",
    "p" => "\x70",
    "q" => "\x71",
    "r" => "\x72",
    "s" => "\x73",
    "t" => "\x74",
    "u" => "\x75",
    "v" => "\x76",
    "w" => "\x77",
    "x" => "\x78",
    "y" => "\x79",
    "z" => "\x7A",
    " " => "\x20",
    "" => "\x00",
    "!" => "\x21",
    "?" => "\x3F",
    "\"" => "",
    "'" => "",
    "\\" => "",
    "/" => "",
    "=" => "",
    ">" => "",
    "<" => "",
    ":" => "",
    ";" => "",
    "-" => "",
    "[" => "",
    "]" => "",
    "+" => "",
    ")" => "",
    "(" => "",
    "%" => "",
    "^" => "",
    "*" => "",
    "&" => "",
    "#" => "",
    "@" => "",
    "`" => "",
    "~" => "",
    "|" => "",
    "\r" => "\x0D",
    "\n" => "\x0A"
);


class dynamic_generator
{

    private function randomString(){
        return bin2hex(random_bytes(rand(5,25)));
    }

    private function junkCodeGen($code){
        if (is_array($code)){

        }
    }

    function begin_junk($file, $mode){
        if (!empty($file) and is_file($file) and !empty($mode)){

        }else{
            $required_params = array();
            if (empty($mode)){
                array_push($required_params, ("mode cannot be empty. Please use either O for obfuscation or P for plain."));
            }else if (empty($file)){
                array_push($required_params, "file cannot be empty.");
            }else if (!is_file($file)){
                array_push($required_params, "File needs to be of type file not string.");
            }
            throw new Exception("Please rectify the following errors: \n" . array_values($required_params). "\n");
        }
    }

}