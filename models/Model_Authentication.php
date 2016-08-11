<?php

/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 02.03.2016
 * Time: 17:45
 */

    class Model_Authentication {

        function login($name,$password) {
            global $session, $db;

            $errors = '';

            $sql = "SELECT `id_profesor`,`nume`,`parola` FROM `profesor` WHERE `nume` = '" . $name . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            if($result) {

                if(password_verify($password, $result['parola'])) {

                    $sqlInsert = "UPDATE `profesor` SET `ultima_logare` = '" . date('Y-m-d H:i:s') . "' WHERE `id_profesor` = '" . $result['id_profesor'] . "'";

                    if($db->execute_query($sqlInsert)) {

                        $session->login($result['id_profesor'],1);

                        $_SESSION['user_name'] = $result['nume'];

                    }

                } else {
                    $errors .= 'Parola incorecta! Va rugam sa reincercati.';
                }

            } else {
                $errors .= 'Nume incorect! Va rugam sa reincercati.';
            }

            die($errors);
        }

        function logout() {
            global $session;

            $session->logout(1);

            unset($_SESSION['user_name']);

            die('1');
        }

    }

?>