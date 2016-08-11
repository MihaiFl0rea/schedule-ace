<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 01.06.2016
 * Time: 15:12
 */

    class Model_groups {

        use commonTasks;

        function get_groups() {
            global $db;

            $sql = "SELECT * FROM `specializare`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $counter = 0;
            foreach($result as $key => $value) {
                $counter = $counter == 2 ? 0 : $counter;
                $result[$key]['display'] = $counter == 0 ? 'info' : 'warning';
                $counter++;
            }

            return $result;

        }

        function get_groups_by_year($id) {
            global $db;

            $sql = "SELECT * FROM `specializare_an` WHERE `id_specializare` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $counter = 0;
            foreach($result as $key => $value) {
                $counter = $counter == 2 ? 0 : $counter;
                $result[$key]['display'] = $counter == 0 ? 'success' : 'danger';
                $counter++;
            }

            return $result;

        }

        function get_group_name($id){
            global $db;

            $sql = "SELECT `nume` FROM `specializare` WHERE `id_specializare` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result['nume'];

        }

        function get_group_details($id) {
            global $db;

            $sql = "SELECT `specializare_an`.`id_specializare_an`,`specializare_an`.`id_specializare`,`specializare`.`nume`,`specializare_an`.`an`,`specializare_an`.`nr_identificare` FROM `specializare_an` LEFT JOIN `specializare` ON `specializare_an`.`id_specializare` = `specializare`.`id_specializare` WHERE `specializare_an`.`id_specializare_an` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result[0];

        }

        function get_subgroups_by_year($id){
            global $db;

            $sql = "SELECT `id_specializare_an_subgrupa`,`nume` FROM `specializare_an_subgrupa` WHERE `id_specializare_an` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

    }

?>