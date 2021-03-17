<?php


class dynamic_generator
{
    private $junked = array();

    private function randomString(){
        return bin2hex(random_bytes(rand(5,25)));
    }

    private function junkCodeGen(){

    }

}