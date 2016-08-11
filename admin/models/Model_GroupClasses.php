<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 19.06.2016
 * Time: 17:50
 */

    class Model_GroupClasses {

        use commonTasks;

        function get_classes(){
            global $db;

            $sql = "SELECT `id_materie`,`nume`,`tip_materie` FROM `materie`";

            $result = $db->execute_query($sql);

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

            /* Before
            // This flow worked with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), id_specializare_an_subgrupa(int)
            $sql = "INSERT INTO `materie_specializare` (`id_materie`,`id_specializare_an_subgrupa`) VALUES (?,?)";

            if($handler == 'PDO') {

                // Prepared Statement built with PDO

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $idClass);
                    $stmt->bindParam(2, $idSubgroup);

                    foreach($groupAsigned as $key=>$value) {
                        $idClass = $classId;
                        $idSubgroup = $value;
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

                $stmt->bind_param("ii", $idClass, $idSubgroup);

                foreach($groupAsigned as $key=>$value) {
                    $idClass = $classId;
                    $idSubgroup = $value;
                    $stmt->execute();
                }

                return true;

            }
            */

            // ---------------------------------------------------------------------------------------------------------

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

                    /* // trick for inserting multiple values at once
                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $idClass);
                    $stmt->bindParam(2, $subgroups);

                    //for($i = 1; $i < 28; $i++) {
                    for($i = 28; $i < 57; $i++) {
                        $idClass = $i;
                        $subgroups = $jsonArray;
                        $stmt->execute();
                    }

                    return true;*/

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

            /* Before
            // This flow worked with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), id_specializare_an_subgrupa(int)
            $sql = "SELECT * FROM `materie_specializare` GROUP BY `id_materie`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 5;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT `materie`.`id_materie`,`materie`.`nume`,`materie`.`tip_materie`,GROUP_CONCAT(`specializare_an_subgrupa`.`nume`) AS `nume_subgrupe`,GROUP_CONCAT(`specializare_an`.`nr_identificare`) AS `nr_identificare`,GROUP_CONCAT(`specializare`.`acronim`) AS `acronime` FROM `materie_specializare` LEFT JOIN `materie` ON `materie_specializare`.`id_materie` = `materie`.`id_materie` LEFT JOIN `specializare_an_subgrupa` ON `materie_specializare`.`id_specializare_an_subgrupa` = `specializare_an_subgrupa`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` LEFT JOIN `specializare` ON `specializare`.`id_specializare` = `specializare_an`.`id_specializare` GROUP BY `materie_specializare`.`id_materie` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);

            foreach($result as $key => $value) {
                if($value['tip_materie'] == '1') {
                    $result[$key]['tip_materie'] = 'Curs';
                    $value['tip_materie'] = 'Curs';
                } elseif($value['tip_materie'] == '2') {
                    $result[$key]['tip_materie'] = 'Laborator';
                    $value['tip_materie'] = 'Laborator';
                } elseif($value['tip_materie'] == '3') {
                    $result[$key]['tip_materie'] = 'Seminar';
                    $value['tip_materie'] = 'Seminar';
                } elseif($value['tip_materie'] == '4') {
                    $result[$key]['tip_materie'] = 'Proiect';
                    $value['tip_materie'] = 'Proiect';
                }

                // how it looks like so far
                // nume tip_materie nume_subgrupe nr_identificare acronime

                    // Tehnologii Web
                    // 1
                    // B,F,B,B,C,B
                    // 10309,10102,10409,10102,10102,10409
                    // ISM,AIA,ISM,AIA,AIA,ISM

                    // Aplicatii Internet
                    // 1
                    // F,E,D
                    // 10102,10102,10102
                    // AIA,AIA,AIA

                    // Bazele Electrotehnicii
                    // 1
                    // B,A,C
                    // 10309,10309,10102
                    // ISM,ISM,AIA

                $subgroups = explode(',',$value['nume_subgrupe']);
                $idNumbers = explode(',',$value['nr_identificare']);
                $acronims = explode(',',$value['acronime']);

                $counter = 0;
                foreach($subgroups as $keySubgroup => $valueSubgroup) {
                    $data[$counter]['name'] = $valueSubgroup;
                    $counter++;
                }
                $counter = 0;
                foreach($idNumbers as $keyNumber => $valueNumber) {
                    $data[$counter]['idNumber'] = $valueNumber;
                    $counter++;
                }
                $counter = 0;
                foreach($acronims as $keyAcronim => $valueAcronim) {
                    $data[$counter]['acronim'] = $valueAcronim;
                    $counter++;
                }

                // and now it will come to this
                // 'grupe' => string 'AIA,10102,F | AIA,10102,E | AIA,10102,D | AIA,10102,B | AIA,10102,C | ISM,10409,B'
                $details = '';
                foreach($data as $keyData => $valueData) {
                    $details .= $valueData['acronim'] . ',' . $valueData['idNumber'] . ',' . $valueData['name'] . ' | ';
                }
                $details = rtrim($details,' | ');
                $result[$key]['grupe'] = $details;
            }
            */

            // ---------------------------------------------------------------------------------------------------------

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

        function get_groupClass($id) {
            global $db;

            /* Before
            // This flow worked with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), id_specializare_an_subgrupa(int)
            $sql = "SELECT `materie`.`nume`,`materie`.`tip_materie`,`materie_specializare`.`id_materie`,GROUP_CONCAT(`materie_specializare`.`id_specializare_an_subgrupa`) AS `id_specializare_an_subgrupa` FROM `materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie` WHERE `materie_specializare`.`id_materie` = '" . $id . "' GROUP BY `materie_specializare`.`id_materie`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            if($result[0]['tip_materie'] == '1') {
                $result[0]['tip_materie'] = 'Curs';
            } elseif($result[0]['tip_materie'] == '2') {
                $result[0]['tip_materie'] = 'Laborator';
            } elseif($result[0]['tip_materie'] == '3') {
                $result[0]['tip_materie'] = 'Seminar';
            } elseif($result[0]['tip_materie'] == '4') {
                $result[0]['tip_materie'] = 'Proiect';
            }

            $subgroups = explode(',',$result[0]['id_specializare_an_subgrupa']);

            $data = array('classId' => $result[0]['id_materie'], 'subgroups' => $subgroups, 'className' => $result[0]['nume'] . ' - ' . $result[0]['tip_materie']);
            */

            // ---------------------------------------------------------------------------------------------------------

            /* After */
            // This flow works with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), subgrupe(text)

            $sql = "SELECT `materie`.`nume`,`materie`.`tip_materie`,`materie_specializare`.`id_materie`,`materie_specializare`.`id_materie_specializare`,`materie_specializare`.`subgrupe` FROM `materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie` WHERE `materie_specializare`.`id_materie_specializare` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            if($result['tip_materie'] == '1') {
                $result['tip_materie'] = 'Curs';
            } elseif($result['tip_materie'] == '2') {
                $result['tip_materie'] = 'Laborator';
            } elseif($result['tip_materie'] == '3') {
                $result['tip_materie'] = 'Seminar';
            } elseif($result['tip_materie'] == '4') {
                $result['tip_materie'] = 'Proiect';
            }

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

            /* Before
            // This flow worked with the following table:
            // materie_specializare: id_materie_specializare(int), id_materie(int), id_specializare_an_subgrupa(int)
            $sql = "DELETE FROM `materie_specializare` WHERE `id_materie` = '" . $classId . "'";

            if($db->execute_query($sql)) {
                if($this->add_groupClass($classId,$groupAsigned)) {
                    return true;
                }
            }
            */

            // ---------------------------------------------------------------------------------------------------------

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