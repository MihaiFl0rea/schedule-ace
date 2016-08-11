<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 17.06.2016
 * Time: 12:50
 */

    require_once BASE_URL . "/models/Model_Teachers.php";

    $model = new Model_Teachers();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $template = $twig->loadTemplate('teachers.html');

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        $statistics = $model->create_counters_array();

        switch($action) {

            case 'teachers':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $teachers = $model->get_teachers($page);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Profesori | ACE',
                    'statistics' => $statistics,
                    'case' => 'teachers',
                    'teachers' => $teachers
                ));

                break;

            case 'add_teacher':

                if(isset($_POST['addTeacher'])) {

                    if($model->add_teacher($_POST['teacherName'],$_POST['teacherPassword'],$_POST['teacherPriority'])){
                        header('Location: index.php?m=teachers&case=teachers');
                    }

                } else {

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga profesor | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_teacher'
                    ));
                }

                break;

            case 'edit_teacher':

                if(isset($_POST['editTeacher'])) {

                    if($model->edit_teacher($_POST['teacherId'],$_POST['teacherName'],$_POST['teacherPassword'],$_POST['teacherPriority'])){
                        header('Location: index.php?m=teachers&case=teachers');
                    }

                } else {

                    $teacherDetails = $model->get_teacher($_GET['id']);

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Editeaza profesor | ACE',
                        'statistics' => $statistics,
                        'case' => 'edit_teacher',
                        'teacherDetails' => $teacherDetails
                    ));
                }

                break;

            case 'teacher_classes':

                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $teacherClasses = $model->get_teacher_classes($page,$_GET['id']);

                $teacherName = $model->get_teacher_name($_GET['id']);

                echo $template->render(array(
                    'url' => ASSETS_URL,
                    'slash' => DIRECTORY_SEPARATOR,
                    'year' => date('Y'),
                    'name' => 'Materii predate | ACE',
                    'statistics' => $statistics,
                    'case' => 'teacher_classes',
                    'teacherClasses' => $teacherClasses,
                    'teacherId' => $_GET['id'],
                    'teacherName' => $teacherName
                ));

                break;

            case 'add_teacher_class':

                if(isset($_POST['addTeacherClass'])) {

                    if($model->add_teacher_class($_POST['teacherId'],$_POST['teacherClasses'])){
                        header('Location: index.php?m=teachers&case=teacher_classes&id=' . $_POST['teacherId']);
                    }

                } else {

                    $classGroups = $model->get_class_groups();

                    $teacherName = $model->get_teacher_name($_GET['id']);

                    echo $template->render(array(
                        'url' => ASSETS_URL,
                        'slash' => DIRECTORY_SEPARATOR,
                        'year' => date('Y'),
                        'name' => 'Adauga materie | ACE',
                        'statistics' => $statistics,
                        'case' => 'add_teacher_class',
                        'teacherId' => $_GET['id'],
                        'teacherName' => $teacherName,
                        'classGroups' => $classGroups
                    ));
                }

                break;

        }

    }

?>