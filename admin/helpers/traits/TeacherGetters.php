<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:24
 */

    trait TeacherGetters {

        /**
         * Get teachers
         * @param $idsGroupClasses
         * @return array
         */
        public function get_teachers_by_classes($idsGroupClasses) {
            global $db;

            $sql = "SELECT `profesor_materie`.`id_materie_specializare`,`profesor`.`id_profesor`,`profesor`.`nume`,`profesor`.`prioritate`,`materie_specializare`.`id_materie` FROM `profesor_materie` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `profesor_materie`.`id_profesor` LEFT JOIN `materie_specializare` ON `materie_specializare`.`id_materie_specializare` = `profesor_materie`.`id_materie_specializare` WHERE `profesor_materie`.`id_materie_specializare` IN (" . $idsGroupClasses . ") ORDER BY `profesor`.`prioritate`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

        /**
         * Get details of certain teacher
         * @param $idTeacher
         * @return mixed
         */
        public function get_teacher_details($idTeacher) {
            global $db;

            $sql = "SELECT `id_profesor`,`nume`,`prioritate` FROM `profesor` WHERE `id_profesor` = '" . $idTeacher . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        /**
         * Get teachers
         * @param $page
         * @return array
         */
        public function get_teachers($page) {
            global $db,$pagination;

            $sql = "SELECT * FROM `profesor`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 20;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT * FROM `profesor` ORDER BY `profesor`.`prioritate` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);


            foreach($result as $key => $value) {
                if($value['prioritate'] == '1') {
                    $result[$key]['prioritate'] = 'Ridicata';
                } elseif($value['prioritate'] == '2') {
                    $result[$key]['prioritate'] = 'Medie';
                } elseif($value['prioritate'] == '3') {
                    $result[$key]['prioritate'] = 'Scazuta';
                }
            }

            // get pagination details

            // check if there are 1 or 2 pages before or ahead
            $oneBack = (intval($pagination->current_page) - 1) > 0 ? true : false;
            $twoBack = (intval($pagination->current_page) - 2) > 0 ? true : false;
            $oneAhead = (intval($pagination->current_page) + 1) <= intval($pagination->total_pages()) ? true : false;
            $twoAhead = (intval($pagination->current_page) + 2) <= intval($pagination->total_pages()) ? true : false;

            $pageDetails = array("currentPage" => $pagination->current_page, "totalPages" => $pagination->total_pages(), "previousPage" => $pagination->previous_page(), "nextPage" => $pagination->next_page(), "hasPreviousPage" => $pagination->has_previous_page(), "hasNextPage" => $pagination->has_next_page(), "twoBack" => $twoBack, "twoAhead" => $twoAhead, "oneBack" => $oneBack, "oneAhead" => $oneAhead);
            //var_dump($pageDetails);
            // compose final array
            $data = array("result" => $result, "pageDetails" => $pageDetails);

            return $data;

        }

        /**
         * Get certain teacher
         * @param $id
         * @return mixed
         */
        public function get_teacher($id) {
            global $db;

            $sql = "SELECT * FROM `profesor` WHERE `id_profesor` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result[0];
        }

        /**
         * Get teacher name
         * @param $id
         * @return mixed
         */
        public function get_teacher_name($id) {
            global $db;

            $sql = "SELECT `nume` FROM `profesor` WHERE `id_profesor` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result['nume'];
        }


    }

?>