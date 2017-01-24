<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 17.06.2016
 * Time: 13:03
 */

    class Model_Teachers {

        use commonTasks, TeacherGetters, ClassGetters;

        /**
         * Add teacher
         * @param $name
         * @param $password
         * @param $priority
         * @return bool
         */
        public function add_teacher($name,$password,$priority) {
            global $db,$handler;

            $sql = "INSERT INTO `profesor` (`nume`,`parola`,`prioritate`) VALUES (?,?,?)";

            $password = password_hash($password, PASSWORD_DEFAULT);

            if($handler == 'PDO') {

                /* Prepared Statement built with PDO */

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $name);
                    $stmt->bindParam(2, $password);
                    $stmt->bindParam(3, $priority);

                    if ($stmt->execute()) {
                        return true;
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

                $stmt->bind_param("ssi", $name, $password, $priority);

                if ($stmt->execute()) {
                    return true;
                }

            }
        }

        /**
         * Edit teacher
         * @param $id
         * @param $name
         * @param $password
         * @param $priority
         * @return bool
         */
        public function edit_teacher($id,$name,$password,$priority) {
            global $db;

            $extraQuery = $password != '' ? " `parola` = '" . password_hash($password, PASSWORD_DEFAULT) . "'," : "";

            $sql = "UPDATE `profesor` SET `nume` = '" . $name . "'," . $extraQuery . " `prioritate` = '" . $priority . "' WHERE `id_profesor` = '" . $id . "'";

            if($db->execute_query($sql)) {
                return true;
            }
        }

        /**
         * Get classes of teacher
         * @param $page
         * @param $id
         * @return array
         */
        public function get_teacher_classes($page,$id) {
            global $db,$pagination;

            $sql = "SELECT * FROM `profesor_materie` WHERE `id_profesor` = '" . $id. "'";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 5;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT `profesor_materie`.`id_profesor_materie`,`materie`.`nume`,`materie`.`tip_materie`,`materie_specializare`.`subgrupe` FROM `profesor_materie` LEFT JOIN `materie_specializare` ON `materie_specializare`.`id_materie_specializare` = `profesor_materie`.`id_materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie` WHERE `profesor_materie`.`id_profesor` = '" . $id . "' LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);


            foreach($result as $key => $value) {
                if($value['tip_materie'] == '1') {
                    $result[$key]['tip_materie'] = 'Curs';
                } elseif($value['tip_materie'] == '2') {
                    $result[$key]['tip_materie'] = 'Laborator';
                } elseif($value['tip_materie'] == '3') {
                    $result[$key]['tip_materie'] = 'Seminar';
                } elseif($value['tip_materie'] == '4') {
                    $result[$key]['tip_materie'] = 'Proiect';
                }

                $jsonArray = json_decode($value['subgrupe']);
                $subgroupsString = '';
                foreach($jsonArray as $key2 => $value2) {
                    $subgroupsString .= $value2 . ' | ';
                }
                $subgroupsString = rtrim($subgroupsString, ' | ');
                $result[$key]['subgroups'] = $subgroupsString;
            }

            // get pagination details

            // check if there are 1 or 2 pages before or ahead
            $oneBack = (intval($pagination->current_page) - 1) > 0 ? true : false;
            $twoBack = (intval($pagination->current_page) - 2) > 0 ? true : false;
            $oneAhead = (intval($pagination->current_page) + 1) <= intval($pagination->total_pages()) ? true : false;
            $twoAhead = (intval($pagination->current_page) + 2) <= intval($pagination->total_pages()) ? true : false;

            $pageDetails = array("currentPage" => $pagination->current_page, "totalPages" => $pagination->total_pages(), "previousPage" => $pagination->previous_page(), "nextPage" => $pagination->next_page(), "hasPreviousPage" => $pagination->has_previous_page(), "hasNextPage" => $pagination->has_next_page(), "twoBack" => $twoBack, "twoAhead" => $twoAhead, "oneBack" => $oneBack, "oneAhead" => $oneAhead);

            // compose final array
            $data = array("result" => $result, "pageDetails" => $pageDetails);

            return $data;
        }

        /**
         * Add teacher class
         * @param $id
         * @param $classes
         * @return bool
         */
        public function add_teacher_class($id,$classes) {
            global $db,$handler;

            $sql = "INSERT INTO `profesor_materie` (`id_profesor`,`id_materie_specializare`) VALUES (?,?)";

            if($handler == 'PDO') {
                // Prepared Statement built with PDO

                try {
                    $stmt = $db->prepare($sql);

                    foreach($classes as $key=>$value) {
                        $idTeacher = $id;
                        $idClassGroup = $value;

                        $stmt->bindParam(1, $idTeacher);
                        $stmt->bindParam(2, $idClassGroup);

                        $stmt->execute();
                    }

                    return true;

                } catch (PDOException $e) {

                    $html =  'There is a problem on database!' . '<br/><br/>';
                    $html .=  "<i>Last SQL query</i>: <b>" . $sql . "</b><br/><br/>";
                    $html .=  "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                    echo $html;

                }

            } else {

                // Prepared Statement built with MySQLi
                $stmt = $db->prepare($sql);

                foreach($classes as $key=>$value) {
                    $idTeacher = $id;
                    $idClassGroup = $value;

                    $stmt->bind_param("ii", $idTeacher, $idClassGroup);

                    $stmt->execute();
                }

                return true;

            }
        }

    }

?>