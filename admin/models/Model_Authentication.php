<?php

/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 17:45
 */

    class Model_Authentication
    {

        function login($email,$password) {
            global $session;
            $errors = $email == 'admin@ace.ucv.ro' ? '' : '<span style="color:red;">Wrong email!</span><br/>';
            $errors .= $password == 'parola' ? '' : '<span style="color:red;">Wrong password!</span><br/>';

            if(($email == 'admin@ace.ucv.ro') && ($password == 'parola')) {
                $session->login('admin',0);
            }

            die($errors);
        }

        function logout() {
            global $session;

            $session->logout(0);
            die('1');
        }

    }

?>