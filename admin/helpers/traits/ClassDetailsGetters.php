<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:10
 */

    trait ClassDetailsGetters {

        /**
         * Get evaluation type
         * @param $evaluationType
         * @return string
         */
        public function getEvaluationType($evaluationType)
        {
            switch($evaluationType){
                case '1':
                    $result = 'Examen';
                    break;
                case '2':
                    $result = 'Colocviu';
                    break;
                default:
                    $result = 'Proiect';
            }
            return $result;
        }

        /**
         * Get class type
         * @param $classType
         * @return string
         */
        public function getClassType($classType)
        {
            switch($classType){
                case '1':
                    $result = 'Curs';
                    break;
                case '2':
                    $result = 'Laborator';
                    break;
                case '3':
                    $result = 'Seminar';
                    break;
                default:
                    $result = 'Proiect';
            }
            return $result;
        }

        /**
         * Get duration
         * @param $duration
         * @return string
         */
        public function getDuration($duration)
        {
            switch($duration){
                case '1':
                    $result = '1 ora';
                    break;
                case '2':
                    $result = '2 ore';
                    break;
                default:
                    $result = '3 ore';
            }
            return $result;
        }

        /**
         * Get hall type
         * @param $hallType
         * @param $dedicatedHallId
         * @return mixed|string
         */
        public function getHallType($hallType,$dedicatedHallId)
        {
            switch($hallType){
                case '1':
                    $result = 'Sala normala';
                    break;
                case '2':
                    $result = 'Sala cu videoproiector';
                    break;
                case '3':
                    $result = 'Sala cu calculatoare';
                    break;
                case '4':
                    $result = 'Aula';
                    break;
                default:
                    $result = $this->get_dedicated_hall_name($dedicatedHallId);
            }
            return $result;
        }

        /**
         * Get frequency
         * @param $frequency
         * @return string
         */
        public function getFrequency($frequency)
        {
            if($frequency == '1'){
                $result = 'Saptamanal';
            } else {
                $result = 'La 2 saptamani';
            }
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

    }

?>