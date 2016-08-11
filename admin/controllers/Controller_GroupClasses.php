<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 19.06.2016
 * Time: 17:49
 */

    require_once BASE_URL . "/models/Model_GroupClasses.php";

    $model = new Model_GroupClasses();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('group-classes.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'groupClasses':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $groupsClasses = $model->get_groups_classes($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Materii per specializare | ACE',
                    'statistics' => $statistics,
                    'case' => 'groupClasses',
                    'groupsClasses' => $groupsClasses
                ));

                break;

            case 'add_groupClass':

                if(isset($_POST['addGroupClass'])) {
                    if($model->add_groupClass($_POST['classId'],$_POST['groupAsigned'])) {
                        header('Location: index.php?m=groupClasses&case=groupClasses');
                    }
                } else {

                    $classes = $model->get_classes();
                    $subgroups = $model->get_subgroups();

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Asigneaza materie | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_groupClass',
                        'classes' => $classes,
                        'subgroups' => $subgroups
                    ));
                }

                break;

            case 'edit_groupClass':

                if(isset($_POST['editGroupClass'])) {

                    if($model->edit_groupClass($_POST['classId'],$_POST['groupAsigned'])){
                        header('Location: index.php?m=groupClasses&case=groupClasses');
                    }

                } else {

                    $groupClassDetails = $model->get_groupClass($_GET['id']);
                    $subgroups = $model->get_subgroups();

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Editeaza asignare | ACE',
                        'statistics' => $statistics,
                        'case' => 'edit_groupClass',
                        'subgroups' => $subgroups,
                        'groupClassDetails' => $groupClassDetails
                    ));
                }

                break;

        }

    }

?>