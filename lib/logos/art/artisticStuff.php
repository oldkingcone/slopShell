<?php

namespace logos\art;

use phpseclib3\Math\BigInteger\Engines\PHP;

class artisticStuff
{
    private array $logo;
    private array $color_pallet;
    public bool $randomize_coloring;
    private array $frames;

    public function __construct(bool | null $randomize_coloring)
    {
        $this->frames = [];
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
            "gr33tz: Notroot && Johnny5\nH4ppy h4ck1ng".PHP_EOL.PHP_EOL,
        ];
        if (is_null($randomize_coloring)) {
            $this->randomize_coloring = true;
        } else {
            $this->randomize_coloring = $randomize_coloring;
        }
    }
    public function prepareFrames(): void
    {
        $this->frames = [
            $this->createFrame(),
            $this->createFrame(),
            $this->createFrame(),
            $this->createFrame(),
        ];
    }

    private function createFrame(): array
    {
        $frame = [];
        $colors = array_keys($this->color_pallet);
        for ($i = 0; $i < count($this->logo); $i++) {
            $colorName = $colors[array_rand($colors)];
            $frame[] = $this->color_pallet[$colorName] . $this->logo[$i] . "\033[0m";
        }

        return $frame;
    }

    public function displayLogo(): void
    {
        $iterationCount = 0;
        while ($iterationCount < 4) {
            foreach ($this->frames as $key => $frame) {
                echo "\033[2J\033[;H";

                foreach ($frame as $line) {
                    echo $line . PHP_EOL;
                }

                usleep(200000);
                if ($key == count($this->frames) - 1){
                    $iterationCount++;
                }
            }
        }
    }

    public function displayStaticAsciiLogo(): void
    {
        echo implode("\n", $this->frames[array_key_last($this->frames)]).PHP_EOL;
    }
}