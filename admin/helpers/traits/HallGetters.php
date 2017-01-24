<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:12
 */

    trait HallGetters {

        /**
         * Get free hall for given period
         * @param $hallTypeId
         * @param $hour
         * @param $day
         * @param $semester
         * @param $year
         * @return mixed
         */
        public function get_hall($hallTypeId,$hour,$day,$semester,$year) {
            global $db;

            $sql = "SELECT `id_sala_curs`,`nume` FROM `sala_curs` WHERE `facilitati` = '" . $hallTypeId . "' AND `id_sala_curs` NOT IN (SELECT `id_sala_curs` FROM `orar_zi_subgrupa` WHERE `ziua` = '" . $day . "' AND `ora` = '" . $hour . ":00' AND `semestru` = '" . $semester . "' AND `an` = '" . $year . "') LIMIT 1";

            $result = $db->execute_query($sql);

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");
            fwrite($fileName, $db->last_query);
            fclose($fileName);

            $result = $db->fetch_row($result);

            return $result;

        }

        /**
         * Get hall by id
         * @param $id
         * @return mixed
         */
        public function get_hall_by_id($id) {
            global $db;

            $sql = "SELECT `id_sala_curs`,`nume` FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        /**
         * Get dedicated hall name
         * @param $id
         * @return mixed
         */
        public function get_dedicated_hall_name($id) {
            global $db;

            $sql = "SELECT `nume` FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result['nume'];
        }

        /**
         * Get dedicated halls
         * @return array
         */
        public function get_dedicated_halls() {
            global $db;

            $sql = "SELECT * FROM `sala_curs` WHERE `facilitati` = '5'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

    }

?>