<?php

namespace fake_the_landing;

use config\defaultConfig;
use Faker\Factory;

// look at using react PHP event loops for this.

/**
 * @property string $characters
 * @property string|null $target_link
 * @property string $serving_directory
 * @property array|array[] $data
 */
class randomDefaultPage
{
    /**
     * @throws \Exception
     */
    function __construct(string|null $target_link, string|null $serving_directory)
    {
        $this->fake_it = Factory::create();
        $this->data = [
            'basic' =>
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
                sprintf('Phone: %s. Our friendly customer support team is available to assist you with any inquiries or concerns.', $this->fake_it::$phoneNumber),
                sprintf('Email: %s. Feel free to reach out to us via email, and we\'ll get back to you as soon as possible.', $this->fake_it::$safeEmail),
                sprintf('Address: %s. Visit our office during business hours to speak with a representative in person.', $this->fake_it::$address),
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
                "<!--#title#-->",
                "<!--#header#-->",
                "<!--#text-about#-->",
                "<!--#title-services#-->",
                "<!--#title-contact#-->",
                "<!--#title-faq#-->",
            ],
            "particles" => "/*pjs_config*/"
        ];
        $conf = new defaultConfig();
        $this->characters = (new defaultConfig())->getAllowedChars();
        if (is_null($target_link)) {
            for ($i = 0; $i < rand(8, 24); $i++) {
                $this->target_link .= $this->characters[$i];
            }
        } else {
            $this->target_link = $target_link;
        }
        if (is_null($serving_directory)) {
            $this->serving_directory = sprintf("%s/servable", $conf->slop_home);
        } else {
            $this->serving_directory = $serving_directory;
        }
        if (!is_dir($this->serving_directory)) {
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