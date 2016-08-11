<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.06.2016
 * Time: 22:06
 */

    require_once BASE_URL . "/models/Model_Classes.php";

    $model = new Model_Classes();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('classes.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'classes':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $classes = $model->get_classes($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Materii | ACE',
                    'statistics' => $statistics,
                    'case' => 'classes',
                    'classes' => $classes
                ));

                break;

            case 'add_class':

                if(isset($_POST['addClass'])) {
                    $classDedicatedHall = $_POST['classHall'] == '5' ? $_POST['classDedicatedHall'] : 0;
                    if($model->add_class($_POST['className'],$_POST['classDescription'],$_POST['classCredits'],$_POST['classEvaluation'],$_POST['classType'],$_POST['classDuration'],$_POST['classHall'],$_POST['classFrequency'],$classDedicatedHall,$_POST['classYear'],$_POST['classSemester'])){
                        header('Location: index.php?m=classes&case=classes');
                    }

                } else {

                    $dedicatedHalls = $model->get_dedicated_halls();

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga materie | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_class',
                        'dedicatedHalls' => $dedicatedHalls
                    ));
                }

                break;

            case 'edit_class':

                if(isset($_POST['editClass'])) {
                    $classDedicatedHall = $_POST['classHall'] == '5' ? $_POST['classDedicatedHall'] : 0;
                    if($model->edit_class($_POST['classId'],$_POST['className'],$_POST['classDescription'],$_POST['classCredits'],$_POST['classEvaluation'],$_POST['classType'],$_POST['classDuration'],$_POST['classHall'],$_POST['classFrequency'],$classDedicatedHall,$_POST['classYear'],$_POST['classSemester'])){
                        header('Location: index.php?m=classes&case=classes');
                    }

                } else {

                    $classDetails = $model->get_class($_GET['id']);
                    $dedicatedHalls = $model->get_dedicated_halls();

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Editeaza materie | ACE',
                        'statistics' => $statistics,
                        'case' => 'edit_class',
                        'classDetails' => $classDetails,
                        'dedicatedHalls' => $dedicatedHalls
                    ));
                }

                break;

        }

    }

?>