<?php

namespace fake_the_landing;

use config\defaultConfig;


/**
 * @property string $characters
 * @property string|null $target_link
 * @property string $serving_directory
 */
class randomDefaultPage
{
    /**
     * @throws \Exception
     */
    function __construct(string | null $target_link, string | null $serving_directory)
    {
        $this->characters = (new defaultConfig())->getAllowedChars();
        if (is_null($target_link)){
            for ($i = 0; $i < rand(8, 24); $i++){
                $this->target_link .= $this->characters[$i];
            }
        }else{
            $this->target_link = $target_link;
        }
        if (is_null($serving_directory)){
            throw new \Exception(PHP_EOL."\033[0;31mServing directory cannot be null. Please review the config file for your web server to determine which directory the server defaults to. Also ensure that PHP-FPM is installed, running, and is properly configured.\033[0m".PHP_EOL);
        }else{
            $this->serving_directory = $serving_directory;
        }
    }

    private function whyNot()
    {

    }

}