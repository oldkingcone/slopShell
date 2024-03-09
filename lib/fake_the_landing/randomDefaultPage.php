<?php

namespace fake_the_landing;

use AllowDynamicProperties;
use config\defaultConfig;
use Faker\Factory;

// look at using react PHP event loops for this.

/**
 * @property string $characters
 * @property string|null $target_link
 * @property string $serving_directory
 * @property array|array[] $data
 */
#[AllowDynamicProperties] class randomDefaultPage
{
    /**
     * @throws \Exception
     */
    function __construct()
    {
        $this->fake_it = Factory::create();
        $this->data = [
            'title' =>
                [
                    'Welcome to Our Website',
                    'Discover Our Services',
                    'Learn More About Us',
                    'Get in Touch With Us',
                    'Explore Our Products',
                    'Join Our Community',
                    'Stay Updated With Us',
                    "Hello World!!!1",
                    "Under Construction",
                    "Coming Soon",
                    "My First Website!!!!1",
                    "Learning JavaScripts"
                ],
            'faq' =>
                [
                    'How can I contact support? Our dedicated support team can be reached via email, phone, or live chat.',
                    'What payment methods do you accept? We accept all major credit cards, PayPal, and bank transfers for your convenience.',
                    'How do I create an account? Simply click on the "Sign Up" button and fill out the required information to create your account.',
                    'Is there a refund policy? Yes, we offer a 30-day money-back guarantee on all our products and services.',
                    'Do you offer customization options? Absolutely! We provide customizable solutions tailored to meet your specific needs.',
                    'What are your shipping options? We offer standard and expedited shipping options to ensure your order reaches you on time.',
                    'Are there any hidden fees? No, we believe in transparency. All costs are clearly stated upfront.',
                ],
            'about' =>
                [
                    'We are dedicated to providing quality service. Our mission is to exceed customer expectations and deliver excellence in every interaction.',
                    'Learn about our journey and mission. From humble beginnings, we have grown into a trusted leader in our industry, driven by passion and innovation.',
                    'Meet the team behind our success. Our talented and experienced team members are committed to delivering the best solutions and services to our clients.',
                    'Discover our company values and principles. Integrity, professionalism, and customer satisfaction are at the core of everything we do.',
                    'Find out what sets us apart from the rest. With a focus on innovation and continuous improvement, we strive to stay ahead of the curve.',
                    'Explore our commitment to excellence. We hold ourselves to the highest standards to ensure that our clients receive nothing but the best.',
                    'Join us on our quest for innovation. We are constantly pushing the boundaries and exploring new possibilities to better serve our clients.',
                ],
            'contact' => [
                sprintf('Phone: %s. Our friendly customer support team is available to assist you with any inquiries or concerns.', $this->fake_it->phoneNumber()),
                sprintf('Email: %s. Feel free to reach out to us via email, and we\'ll get back to you as soon as possible.', $this->fake_it->safeEmail()),
                sprintf('Address: %s. Visit our office during business hours to speak with a representative in person.', $this->fake_it->address()),
                'Visit us during office hours. We welcome visitors to stop by and learn more about our products and services.',
                'Connect with us on social media. Follow us on Facebook, Twitter, and Instagram for news, updates, and special offers.',
                'Fill out the contact form for inquiries. Have a question? Fill out our online form, and we\'ll get back to you promptly.',
                'Get in touch with our dedicated team. Our knowledgeable team members are here to assist you with any questions or concerns.',
            ],
            'services' =>
                [
                    'Professional web design and development. We create stunning websites that are not only visually appealing but also user-friendly and functional.',
                    'Customized solutions tailored to your needs. Whether you need software development, IT consulting, or digital marketing services, we have the expertise to help.',
                    'Marketing strategies to boost your business. Our team of experts can help you develop and implement effective marketing campaigns to reach your target audience and drive results.',
                    'Innovative solutions for your IT challenges. From cloud computing to cybersecurity, we offer cutting-edge solutions to keep your business ahead of the competition.',
                    'Quality products at competitive prices. We source the highest quality materials and products to ensure the best value for our customers.',
                    'Reliable support for all your needs. Our dedicated support team is available around the clock to assist you with any technical issues or questions you may have.',
                    'Efficient and cost-effective solutions. We understand the importance of staying within budget while still delivering high-quality solutions that meet your needs.',
                ]

        ];
        $this->replacement_markers = [
            "all" => [
                "title"=>"<!--#title#-->",
                "header" =>"<!--#header#-->",
                "about" => "<!--#text-about#-->",
                "services" => "<!--#text-services#-->",
                "contact" => "<!--#text-contact#-->",
                "faq" => "<!--#text-faq#-->",
            ],
            "particles" => "/*pjs_config*/"
        ];
        $conf = new defaultConfig();
        $this->characters = (new defaultConfig())->getAllowedChars();
        $this->serving_directory = sprintf("%s/servable", $conf->slop_home);
        if (!is_dir($this->serving_directory)) {
            mkdir($this->serving_directory, 0777, true);
        }
    }

    public function whyNot()
    {
        if (rand(2, 65535) %2 === 0){
            $useParticles = false;
            $template = "templates/static_html/generic/index_template.html";
        }else{
            $useParticles = true;
            $template = "templates/static_html/particles/base_particles.html";
        }
        $originalFile = fopen($template, 'r');
        $tempFile = fopen(sys_get_temp_dir()."/template.temp", 'w');
        $replacements = $this->prepRandomPhrases();
        if ($originalFile && $tempFile) {
            $new_content = '';
            while (($line = fgets($originalFile)) !== false) {
                $line_to_write = $line;
                foreach ($this->replacement_markers["all"] as $markerName => $marker) {
                    if (str_contains($line, $marker)) {
                        $line_to_write = str_replace($marker, $replacements[$markerName], $line);
                        break;
                    }
                }

                if ($useParticles && str_contains($line, $this->replacement_markers['particles'])) {
                    $line_to_write = str_replace($this->replacement_markers["particles"], $this->particlesJS(), $line);
                }

                $new_content .= $line_to_write;
            }
            fwrite($tempFile, $new_content);

            fclose($originalFile);
            fclose($tempFile);
            if (file_exists(sprintf("%s/%s", $this->serving_directory, "index.html"))){
                unlink(sprintf("%s/%s", $this->serving_directory, "index.html"));
            }
            rename(sys_get_temp_dir()."/template.temp", sprintf("%s/%s", $this->serving_directory, "index.html"));
            if (file_exists(sprintf("%s/%s", $this->serving_directory, "index.html"))){
                unlink(sys_get_temp_dir()."/template.temp");
                echo "Fake landing page set to: ".PHP_EOL;
                echo sprintf("%s/%s", $this->serving_directory, "index.html").PHP_EOL;
                echo implode("\n", file(sprintf("%s/%s", $this->serving_directory, "index.html"))).PHP_EOL;
//                symlink();
            }
        } else {
            // Error opening the file.
            echo "Error processing the file.";
        }
    }

    private function prepRandomPhrases(): array
    {
        $title_and_header = $this->fake_it->randomElement($this->data['title']);
        return [
            "faq" => $this->fake_it->randomElement($this->data['faq']),
            "services" => $this->fake_it->randomElement($this->data['services']),
            "contact" => $this->fake_it->randomElement($this->data['contact']),
            "about" => $this->fake_it->randomElement($this->data['about']),
            "title" => $title_and_header,
            "header" => $title_and_header,
        ];
    }


    private function particlesJS(): string
    {
        $particles_config_list = [
            "basic",
            "nyan_cat",
            "polygon_shapes",
            "snow",
            "sus"
        ];
        return file_get_contents(sprintf("templates/static_html/particles/configs/%s", $this->fake_it->randomElement($particles_config_list)));
    }


}