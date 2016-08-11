<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 27.06.2016
 * Time: 16:41
 */

    require_once BASE_URL . "/models/Model_Exceptions.php";

    $model = new Model_Exceptions();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('exceptions.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'exceptions':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $exceptions = $model->get_exceptions($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Exceptii profesori | ACE',
                    'statistics' => $statistics,
                    'case' => 'exceptions',
                    'exceptions' => $exceptions
                ));

                break;

            /*case 'add_exception':

                if(isset($_POST['addException'])) {
                    var_dump($_POST); die();
                    if($model->add_exception($_POST['hallId'],$_POST['hallName'],$_POST['hallLocation'],$_POST['hallFacilities'])){
                        header('Location: index.php?m=exceptions&case=exceptions');
                    }

                } else {

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga exceptie | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_exception'
                    ));
                }

                break;*/

            case 'edit_exception':

                if(isset($_POST['editException'])) {

                    if($model->edit_exception($_POST['exceptionId'],$_POST['exceptionDay'],$_POST['startException'],$_POST['endException'])){
                        header('Location: index.php?m=exceptions&case=exceptions');
                    }

                } else {

                    $exceptionDetails = $model->get_exception($_GET['id']);

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Editeaza exceptie | ACE',
                        'statistics' => $statistics,
                        'case' => 'edit_exception',
                        'exceptionDetails' => $exceptionDetails
                    ));
                }

                break;

        }

    }

?>