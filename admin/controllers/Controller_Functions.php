<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 03.06.2016
 * Time: 19:38
 */

    require_once BASE_URL . "/models/Model_Functions.php";

    $model = new Model_Functions();

    if($_SESSION['user_id'] != 'admin'){

        header('Location: ' . ADMIN_AUTH_PATH);

    } else {

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        switch($action) {

            /* Page Groups */

            case 'add_subgroup' :

                $name = $_POST['name'];
                $id = $_POST['id'];

                $subgroup = $model->add_year_subgroup($name,$id);

                print($subgroup);

                break;

            case 'edit_subgroup':

                $name = $_POST['name'];
                $id = $_POST['id'];

                $newSubgroup = $model->edit_year_subgroup($name,$id);

                print($newSubgroup);

                break;

            case 'delete_subgroup':

                $id = $_POST['id'];

                $model->delete_year_subgroup($id);

                break;

            /* Page Teachers */

            case 'delete_teacher':

                $id = $_POST['id'];

                $model->delete_teacher($id);

                break;

            case 'delete_teacher_class':

                $id = $_POST['id'];

                $model->delete_teacher_class($id);

                break;

            /* Page Halls */

            case 'delete_hall':

                $id = $_POST['id'];

                $model->delete_hall($id);

                break;

            /* Page Classes */

            case 'delete_class':

                $id = $_POST['id'];

                $model->delete_class($id);

                break;

            /* Page Group Classes */

            case 'delete_groupClass':

                $id = $_POST['id'];

                $model->delete_groupClass($id);

                break;

            /* Page Exceptions */

            case 'delete_exception':

                $id = $_POST['id'];

                $model->delete_exception($id);

                break;

            /* Page Group Exceptions */

            case 'delete_group_exception':

                $id = $_POST['id'];

                $model->delete_group_exception($id);

                break;

            /* Page Schedule */

            case 'delete_schedule':

                $model->delete_schedule();

                break;
        }

    }

?>