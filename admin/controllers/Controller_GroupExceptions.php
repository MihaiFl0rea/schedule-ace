<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 03.07.2016
 * Time: 18:21
 */

    require_once BASE_URL . "/models/Model_GroupExceptions.php";

    $model = new Model_GroupExceptions();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('group-exceptions.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'groupExceptions':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $exceptions = $model->get_exceptions($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Exceptii subgrupe | ACE',
                    'statistics' => $statistics,
                    'case' => 'groupExceptions',
                    'exceptions' => $exceptions
                ));

                break;

            case 'add_exception':

                if(isset($_POST['addException'])) {

                    if($model->add_exception($_POST['groupAsigned'],$_POST['exceptionDay'],$_POST['startException'],$_POST['endException'])){
                        header('Location: index.php?m=groupExceptions&case=groupExceptions');
                    }

                } else {

                    $subgroups = $model->get_subgroups();

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga exceptie | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_exception',
                        'subgroups' => $subgroups
                    ));
                }

                break;

            /*case 'edit_exception':

                if(isset($_POST['editException'])) {

                    if($model->edit_exception($_POST['exceptionId'],$_POST['exceptionDay'],$_POST['startException'],$_POST['endException'])){
                        header('Location: index.php?m=groupExceptions&case=groupExceptions');
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

                break;*/

        }

    }

?>