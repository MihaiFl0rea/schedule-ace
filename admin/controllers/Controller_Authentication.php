<?php

/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 17:32
 */

    require_once BASE_URL . "/models/Model_Authentication.php";

    $model = new Model_Authentication();

    if((isset($_SERVER['REQUEST_METHOD'])) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

        if($_POST['action'] == 'login') {

            $model->login($_POST['email'],$_POST['password']);

        } elseif($_POST['action'] == 'logout') {

            $model->logout();

        }

    } else {

        if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 'admin')) {

            header('Location: ' . ADMIN_HOME_PATH);

        } else {

            $template = $twig->loadTemplate('signin.html');

            echo $template->render(array(
                'url'   =>  ASSETS_URL,
                'slash' =>  DIRECTORY_SEPARATOR,
                'year'  =>  date('Y'),
                'name' => 'Autentificare | ACE'
            ));

        }

    }

?>