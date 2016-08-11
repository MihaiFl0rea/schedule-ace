<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.05.2016
 * Time: 20:38
 */

    require_once BASE_URL . "/models/Model_Home.php";

    $model = new Model_Home();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $statistics = $model->create_counters_array();

        $template = $twig->loadTemplate('index.html');
        echo $template->render(array(
            'url'   =>  ASSETS_URL,
            'slash' =>  DIRECTORY_SEPARATOR,
            'year'  =>  date('Y'),
            'name' => 'Acasa | ACE',
            'statistics' => $statistics,
        ));

    }

?>