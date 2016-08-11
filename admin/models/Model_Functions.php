<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 03.06.2016
 * Time: 19:39
 */

    class Model_Functions {

        function add_year_subgroup($name,$id) {
            global $db,$handler;

            $sql = "INSERT INTO `specializare_an_subgrupa` (`id_specializare_an`,`nume`) VALUES (?,?)";

            if($handler == 'PDO') {

                /* Prepared Statement built with PDO */

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $id);
                    $stmt->bindParam(2, $name);

                    if ($stmt->execute()) {
                        $insertedId = $db->insert_id();

                        $outputArray = array('id_specializare_an_subgrupa' => $insertedId, 'id_specializare_an' => $id, 'nume' => $name);
                        $outputArray = json_encode($outputArray);

                        return $outputArray;
                    }

                } catch (PDOException $e) {

                    $html =  'There is a problem on database!' . '<br/><br/>';
                    $html .=  "<i>Last SQL query</i>: <b>" . $sql . "</b><br/><br/>";
                    $html .=  "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                    echo $html;

                }

            } else {

                /* Prepared Statement built with MySQLi */

                $stmt = $db->prepare($sql);

                $stmt->bind_param("is", $id, $name);

                if ($stmt->execute()) {
                    $insertedId = $db->insert_id();

                    $outputArray = array('id_specializare_an_subgrupa' => $insertedId, 'id_specializare_an' => $id, 'nume' => $name);
                    $outputArray = json_encode($outputArray);

                    return $outputArray;
                }

            }

        }

        function edit_year_subgroup($name,$id) {
            global $db;

            $sql = "UPDATE `specializare_an_subgrupa` SET `nume` = '" . $name . "' WHERE `id_specializare_an_subgrupa` = '" . $id . "'";

            if($db->execute_query($sql)) {

                $outputArray = array('id_specializare_an_subgrupa' => $id, 'nume' => $name);
                $outputArray = json_encode($outputArray);

                return $outputArray;

            }
        }

        function delete_year_subgroup($id) {
            global $db;

            $sql = "DELETE FROM `specializare_an_subgrupa` WHERE `id_specializare_an_subgrupa` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_teacher($id) {
            global $db;

            $sql = "DELETE FROM `profesor` WHERE `id_profesor` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_teacher_class($id) {
            global $db;

            $sql = "DELETE FROM `profesor_materie` WHERE `id_profesor_materie` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_hall($id) {
            global $db;

            $sql = "DELETE FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_class($id) {
            global $db;

            $sql = "DELETE FROM `materie` WHERE `id_materie` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_groupClass($id) {
            global $db;

            $sql = "DELETE FROM `materie_specializare` WHERE `id_materie_specializare` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_exception($id) {
            global $db;

            $sql = "DELETE FROM `profesor_exceptii` WHERE `id_profesor_exceptie` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_group_exception($id) {
            global $db;

            $sql = "DELETE FROM `subgrupa_exceptii` WHERE `id_subgrupa_exceptii` = '" . $id . "'";

            $db->execute_query($sql);
        }

        function delete_schedule() {
            global $db;

            $sql = "TRUNCATE `orar_zi_subgrupa`";

            $db->execute_query($sql);
        }

    }

?>