<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 16:19
 */

    // Setting Romanian timezone
    date_default_timezone_set('Europe/Bucharest');
    setlocale(LC_TIME, array('ro.utf-8', 'ro_RO.UTF-8', 'ro_RO.utf-8', 'ro', 'ro_RO', 'ro_RO.ISO8859-2'));

    // Database credentials
    defined("DB_HOST") ? null : define('DB_HOST', 'localhost');
    defined("DB_USER") ? null : define('DB_USER', 'root');
    defined("DB_PASS") ? null : define('DB_PASS', '');
    defined("DB_NAME") ? null : define('DB_NAME', 'schedule_ace');

?>