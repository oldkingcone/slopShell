<?php

namespace logos\art;

use phpseclib3\Math\BigInteger\Engines\PHP;

/**
 * @property array $color_pallet
 * @property bool $randomize_coloring
 */
class artisticStuff
{
    /**
     * @var array|string[]
     */
    private array $logo;

    function __construct(bool | null $randomize_coloring)
    {
        
        $this->color_pallet = [
            "red" => "\033[0;31m",
            "green" => "\033[0;32m",
            "yellow" => "\033[0;33m",
            "blue" => "\033[0;34m",
            "purple" => "\033[0;35m",
            "light_green" => "\033[0;36m",
        ];
        $this->logo = [
            " .▄▄ · ▄▄▌         ▄▄▄· ▄▄▄· ▄· ▄▌    .▄▄ ·  ▄ .▄▄▄▄ .▄▄▌  ▄▄▌ ",
            " ▐█ ▀. ██•  ▪     ▐█ ▄█▐█ ▄█▐█▪██▌    ▐█ ▀. ██▪▐█▀▄.▀·██•  ██• ",
            " ▄▀▀▀█▄██▪   ▄█▀▄  ██▀· ██▀·▐█▌▐█▪    ▄▀▀▀█▄██▀▐█▐▀▀▪▄██▪  ██▪ ",
            " ▐█▄▪▐█▐█▌▐▌▐█▌.▐▌▐█▪·•▐█▪·• ▐█▀·.    ▐█▄▪▐███▌▐▀▐█▄▄▌▐█▌▐▌▐█▌▐▌ ",
            "  ▀▀▀▀ .▀▀▀  ▀█▄▀▪.▀   .▀     ▀ •      ▀▀▀▀ ▀▀▀ · ▀▀▀ .▀▀▀ .▀▀▀  ",
            "gr33tz: Notroot && Johnny5\nH4ppy h4ck1ng".PHP_EOL.PHP_EOL
        ];
        if (is_null($randomize_coloring)) {
            $this->randomize_coloring = true;
        } else {
            $this->randomize_coloring = $randomize_coloring;
        }
    }
    private function setColor(string $logo_chunk): string{
        if ($this->randomize_coloring){
            return $this->color_pallet[array_rand($this->color_pallet)].$logo_chunk."\033[0m\n";
        }else{
            return $this->color_pallet['yellow']. $logo_chunk."\033[0m\n";
        }
    }

    public function displayLogo()
    {
        foreach ($this->logo as $line){
            echo $this->setColor($line);
        }
    }
}