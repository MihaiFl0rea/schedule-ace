<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 16:17
 */

    defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

    defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(dirname(__DIR__)));

    defined('SYSTEM_PATH') ? null : define('SYSTEM_PATH', SITE_ROOT.DS.'system');
    defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'system'.DS.'core');
    defined('ADMIN_PATH') ? null : define('ADMIN_PATH', SITE_ROOT.DS.'admin');
    defined('ADMIN_HELPERS_PATH') ? null : define('ADMIN_HELPERS_PATH', ADMIN_PATH.DS.'helpers');
    defined('ADMIN_HELPERS_TRAITS_PATH') ? null : define('ADMIN_HELPERS_TRAITS_PATH', ADMIN_PATH.DS.'helpers'.DS.'traits');

    // load config file first
    require_once(CORE_PATH.DS.'config.php');

    // set and then load database handler class
    $handler = 'PDO'; // mysqli / PDO
    $handler == 'PDO' ? require_once(SYSTEM_PATH.DS.'PDOHandler.php') : require_once(SYSTEM_PATH.DS.'mysqliHandler.php') ;

    // load core classes
    require_once(SYSTEM_PATH.DS.'Session.php');
    require_once(SYSTEM_PATH.DS.'Pagination.php');

    // load helper classes (traits)
    require_once (SYSTEM_PATH.DS.'commonTasks.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'ClassGetters.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'ClassDetailsGetters.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'HallGetters.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'Schedule.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'ScheduleCheckers.php');
    require_once (ADMIN_HELPERS_TRAITS_PATH.DS.'TeacherGetters.php');

    // load schedule helper
    require_once (ADMIN_HELPERS_PATH.DS.'FilterClassAndTeacher.php');

    // load twig
    require_once(SITE_ROOT.DS.'views'.DS.'assets'.DS.'plugins'.DS.'twig'.DS.'vendor'.DS.'autoload.php');

    // admin module paths
    defined('ADMIN_AUTH_PATH') ? null : define('ADMIN_AUTH_PATH', ADMIN_HELPERS_PATH.DS.'index.php?m=authentication');
    defined('ADMIN_HOME_PATH') ? null : define('ADMIN_HOME_PATH', ADMIN_HELPERS_PATH.DS.'index.php?m=home');

    // front module paths
    defined('FRONT_AUTH_PATH') ? null : define('FRONT_AUTH_PATH', SITE_ROOT.DS.'index.php?m=authentication');
    defined('FRONT_HOME_PATH') ? null : define('FRONT_HOME_PATH', SITE_ROOT.DS.'index.php?m=home');

?>