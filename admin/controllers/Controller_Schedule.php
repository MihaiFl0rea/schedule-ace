<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 05.07.2016
 * Time: 12:32
 */

    require_once BASE_URL . "/models/Model_Schedule.php";

    $model = new Model_Schedule();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('schedule.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'activeGroups':

                $activeSubgroups = $model->get_active_subgroups();

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Orar - Grupe | ACE',
                    'statistics' => $statistics,
                    'case' => 'activeGroups',
                    'activeSubgroups' => $activeSubgroups
                ));

                break;

            case 'display':

                if(isset($_POST['generateSchedule'])) {
                    if($model->generate_schedule($_POST['subgroup'],$_POST['semester'])){
                        header('Location: index.php?m=schedule&case=display&group=' . $_POST['subgroup'] . '&sem=' . $_POST['semester']);
                    }

                } else {

                    $scheduleExistence = $model->check_schedule_existence($_GET['group'],$_GET['sem']);

                    $scheduleOutput =  $model->get_schedule($_GET['group'],$_GET['sem']);

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Afisare orar | ACE',
                        'statistics' => $statistics,
                        'case' => 'display',
                        'groupId' => $_GET['group'],
                        'semester' => $_GET['sem'],
                        'scheduleExistence' => $scheduleExistence,
                        'scheduleOutput' => $scheduleOutput
                    ));
                }

                break;

        }

    }

?>