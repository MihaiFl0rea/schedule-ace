<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 30.06.2016
 * Time: 12:10
 */

    require_once BASE_URL . "/models/Model_Functions.php";

    $model = new Model_Functions();

    if(!isset($_SESSION['user_id_front'])){

        header('Location: ' . FRONT_AUTH_PATH);

    } else {

        $action = isset($_GET['case']) ? $_GET['case'] : '';

        switch ($action) {

            case 'edit_exception':

                $model->edit_exception($_POST['idException'], $_POST['day'], $_POST['hours']);

                break;

            case 'add_exception':

                $model->add_exception($_POST['idTeacher'],$_POST['day'],$_POST['hours']);

                break;

            case 'delete_exception':

                $model->delete_exception($_POST['id']);

                break;

        }
    }

?>