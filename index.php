<?php
    /**
     * Created by PhpStorm.
     * User: Dumbledore
     * Date: 02.03.2016
     * Time: 15:46
     */

    // load bootloader
    require_once __DIR__.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'bootloader.php';

    // instantiate twig
    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader);
    // define directories for twig
    define("BASE_URL", getcwd());
    define("ASSETS_URL", "views/assets/");

    if((!isset($_GET['m']))) {

        // if m doesn't exists as a GET parameter, require the content of login page
        require_once 'controllers'.DS.'Controller_Authentication.php';

    } else {

        // check if specified controller really exists
        if(file_exists('controllers'.DS.'Controller_' . ucfirst($_GET['m']) . '.php')) {

            // if exists, require the content
            require_once 'controllers'.DS.'Controller_' . ucfirst($_GET['m']) . '.php';

        } else {

            // redirect to 404 page when is not an existing controller
            require_once SITE_ROOT.DS.'views'.DS.'404.html';

        }
    }

?>