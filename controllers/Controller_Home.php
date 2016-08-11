<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 28.06.2016
 * Time: 18:16
 */

    require_once BASE_URL . "/models/Model_Home.php";

    $model = new Model_Home();

    if(!isset($_SESSION['user_id_front'])){

        header('Location: ' . FRONT_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('index.html');

        $exceptions = $model->get_exceptions($_SESSION['user_id_front']);

        $schedule = $model->get_teacher_schedule($_SESSION['user_id_front']);

        echo $template->render(array(
            'url'   =>  ASSETS_URL,
            'slash' =>  DIRECTORY_SEPARATOR,
            'year'  =>  date('Y'),
            'name' => 'Orar | ACE',
            'teacherName' => $_SESSION['user_name'],
            'exceptions' => $exceptions,
            'teacherId' => $_SESSION['user_id_front'],
            'schedule' => $schedule
        ));

    }

?>