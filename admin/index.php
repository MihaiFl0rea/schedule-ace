<?php
    /**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 15:46
 */

    require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'bootloader.php';

    // instantiate twig
    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader);
    // define directories for twig
    define("BASE_URL", getcwd());
    define("ASSETS_URL", "views/assets/");

    if((!isset($_GET['m']))) {

        require_once 'controllers'.DS.'Controller_Authentication.php';

    } else {

        if(file_exists('controllers'.DS.'Controller_' . ucfirst($_GET['m']) . '.php')) {

            require_once 'controllers'.DS.'Controller_' . ucfirst($_GET['m']) . '.php';

        } else {

            require_once SITE_ROOT.DS.'admin'.DS.'views'.DS.'404.html';

        }
    }

?>