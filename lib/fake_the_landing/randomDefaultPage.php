<?php

namespace fake_the_landing;

use config\defaultConfig;
// look at using react PHP event loops for this.

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
        $conf = new defaultConfig();
        $this->characters = (new defaultConfig())->getAllowedChars();
        if (is_null($target_link)){
            for ($i = 0; $i < rand(8, 24); $i++){
                $this->target_link .= $this->characters[$i];
            }
        }else{
            $this->target_link = $target_link;
        }
        if (is_null($serving_directory)){
            $this->serving_directory = sprintf("%s/servable", $conf->slop_home);
        }else{
            $this->serving_directory = $serving_directory;
        }
        if (!is_dir($this->serving_directory)){
            mkdir($this->serving_directory, 0777, true);
        }
    }

    private function whyNot()
    {
        echo "In order for this to work, you will need to create a symbolic link from the slop directory in /opt/slop/servable to the HTTP server's served directory.";
        echo "Or w/e serving directory it is that you want to have the randomized home page be served from.";
        echo "To reduce the chances for exploits against PHP itself, this random home page will be in HTML";

    }

    private function prepRandomPhrases()
    {

    }

    private function flushToDisk()
    {

    }

}