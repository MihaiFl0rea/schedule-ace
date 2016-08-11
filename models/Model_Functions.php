<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 30.06.2016
 * Time: 12:14
 */

    class Model_Functions {

        function edit_exception($idException, $day, $hours) {
            global $db;

            $sql = "UPDATE `profesor_exceptii` SET `zi` = '" . $day . "', `ora` = '" . $hours . "' WHERE `id_profesor_exceptie` = '" . $idException . "'";

            if($db->execute_query($sql)) {
                die('1');
            } else {
                die('0');
            }
        }

        function add_exception($idTeacher, $day, $hours) {
            global $db;

            $sql = "INSERT INTO `profesor_exceptii` (`id_profesor`,`zi`,`ora`) VALUES ('" . $idTeacher . "', '" . $day . "', '" . $hours . "')";

            if($db->execute_query($sql)) {

                die($db->insert_id());

            } else {
                die('0');
            }
        }

        function delete_exception($id) {
            global $db;

            $sql = "DELETE FROM `profesor_exceptii` WHERE `id_profesor_exceptie` = '" .$id . "'";

            $db->execute_query($sql);
        }

    }

?>