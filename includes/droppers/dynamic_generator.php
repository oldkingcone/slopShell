<?php


$char_map = array(
    "a" => "",
    "b" => "",
    "c" => "",
    "d" => "d",
    "e" => "e",

);

class dynamic_generator
{
    private $junked = array();

    private function randomString(){
        return bin2hex(random_bytes(rand(5,25)));
    }

    private function junkCodeGen($code){
        if (is_array($code)){

        }
    }

}