<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 19.06.2016
 * Time: 17:50
 */

    class Model_GroupClasses {

        use commonTasks, ClassDetailsGetters;

        function get_classes(){
            global $db;

            $sql = "SELECT `id_materie`,`nume`,`tip_materie` FROM `materie`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            foreach($result as $key => $value) {
                $result[$key]['tip_materie'] = $this->getClassType($result[$key]['tip_materie']);
            }

            return $result;
        }

        function get_subgroups() {
            global $db;

            $sql = "SELECT `specializare_an_subgrupa`.`id_specializare_an_subgrupa`,`specializare_an_subgrupa`.`nume`,`specializare_an`.`nr_identificare`,`specializare`.`acronim` FROM `specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an_subgrupa`.`id_specializare_an` = `specializare_an`.`id_specializare_an` LEFT JOIN `specializare` ON `specializare`.`id_specializare` = `specializare_an`.`id_specializare`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            foreach($result as $key => $value) {
                $result[$key]['denumire'] = $value['acronim'] . ', ' . $value['nr_identificare'] . ', ' . $value['nume'];
            }

            return $result;
        }

        function add_groupClass($classId,$groupAsigned) {
            global $db,$handler;

            /* After */
            // This flow works with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), subgrupe(text)
            $sql = "INSERT INTO `materie_specializare` (`id_materie`,`subgrupe`) VALUES (?,?)";

            // convert subgroups array to json format
            $jsonArray = array();
            $counter = 0;
            foreach($groupAsigned as $key => $value) {

                $currentArray = explode('|',$value);
                array_push($jsonArray,$currentArray[1]);
                $counter++;

            }
            $jsonArray = json_encode($jsonArray);

            if($handler == 'PDO') {

                // Prepared Statement built with PDO

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $classId);
                    $stmt->bindParam(2, $jsonArray);

                    if($stmt->execute()){
                        return true;
                    }

                } catch (PDOException $e) {

                    $html =  'There is a problem on database!' . '<br/><br/>';
                    $html .=  "<i>Last SQL query</i>: <b>" . $sql . "</b><br/><br/>";
                    $html .=  "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                    echo $html;

                }

            } else {

                // Prepared Statement built with MySQLi

                $stmt = $db->prepare($sql);

                $stmt->bind_param("is", $classId, $jsonArray);

                if($stmt->execute()){
                    return true;
                }

            }
        }

        function get_groups_classes($page) {
            global $db,$pagination;

            /* After */
            // This flow works with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), subgrupe(text)
            $sql = "SELECT * FROM `materie_specializare`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 20;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT `materie`.`id_materie`,`materie`.`nume`,`materie`.`tip_materie`,`materie_specializare`.`id_materie_specializare`,`materie_specializare`.`subgrupe` FROM `materie_specializare` LEFT JOIN `materie` ON `materie_specializare`.`id_materie` = `materie`.`id_materie` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);

            foreach($result as $key => $value) {
                $result[$key]['tip_materie'] = $this->getClassType($result[$key]['tip_materie']);

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

        function get_groupClass($id) {
            global $db;

            /* After */
            // This flow works with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), subgrupe(text)

            $sql = "SELECT `materie`.`nume`,`materie`.`tip_materie`,`materie_specializare`.`id_materie`,`materie_specializare`.`id_materie_specializare`,`materie_specializare`.`subgrupe` FROM `materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie` WHERE `materie_specializare`.`id_materie_specializare` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            $result['tip_materie'] = $this->getClassType($result['tip_materie']);

            $jsonArray = json_decode($result['subgrupe']);
            $subgroupsArray = array();
            foreach($jsonArray as $key => $value) {
                array_push($subgroupsArray,$value);
            }

            $data = array('classId' => $result['id_materie_specializare'], 'subgroups' => $subgroupsArray, 'className' => $result['nume'] . ' - ' . $result['tip_materie']);

            return $data;
        }

        function edit_groupClass($classId,$groupAsigned) {
            global $db;

            /* After */
            // This flow works with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), subgrupe(text)

            // edit json array for 'subgrupe' field
            $subgroups = json_encode($groupAsigned);

            $sql = "UPDATE `materie_specializare` SET `subgrupe` = '" . $subgroups . "' WHERE `id_materie_specializare` = '" . $classId . "'";

            if($db->execute_query($sql)) {
                return true;
            }

        }

    }

?>