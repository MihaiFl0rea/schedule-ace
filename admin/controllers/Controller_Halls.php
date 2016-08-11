<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.06.2016
 * Time: 16:27
 */

    require_once BASE_URL . "/models/Model_Halls.php";

    $model = new Model_Halls();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('halls.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'halls':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $halls = $model->get_halls($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Sali de curs | ACE',
                    'statistics' => $statistics,
                    'case' => 'halls',
                    'halls' => $halls
                ));

                break;

            case 'add_hall':

                if(isset($_POST['addHall'])) {

                    if($model->add_hall($_POST['hallName'],$_POST['hallLocation'],$_POST['hallFacilities'])){
                        header('Location: index.php?m=halls&case=halls');
                    }

                } else {

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga sala de curs | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_hall'
                    ));
                }

                break;

            case 'edit_hall':

                if(isset($_POST['editHall'])) {

                    if($model->edit_hall($_POST['hallId'],$_POST['hallName'],$_POST['hallLocation'],$_POST['hallFacilities'])){
                        header('Location: index.php?m=halls&case=halls');
                    }

                } else {

                    $hallDetails = $model->get_hall($_GET['id']);

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Editeaza sala de curs | ACE',
                        'statistics' => $statistics,
                        'case' => 'edit_hall',
                        'hallDetails' => $hallDetails
                    ));
                }

                break;

        }

    }

?>