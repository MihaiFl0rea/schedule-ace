<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 03.07.2016
 * Time: 18:21
 */

    class Model_GroupExceptions {

        use commonTasks;

        function get_exceptions($page) {
            global $db, $pagination;

            $sql = "SELECT * FROM `subgrupa_exceptii`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 5;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT `specializare`.`acronim`,`specializare_an`.`nr_identificare`,`specializare_an_subgrupa`.`nume`,`subgrupa_exceptii`.`id_subgrupa_exceptii`,`subgrupa_exceptii`.`status`,`subgrupa_exceptii`.`zi`,`subgrupa_exceptii`.`ora` FROM `subgrupa_exceptii` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an_subgrupa` = `subgrupa_exceptii`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` LEFT JOIN `specializare` ON `specializare`.`id_specializare` = `specializare_an`.`id_specializare` ORDER BY `subgrupa_exceptii`.`id_subgrupa_exceptii` ASC LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);


            foreach($result as $key => $value) {
                if($value['zi'] == '1') {
                    $result[$key]['zi'] = 'Luni';
                } elseif($value['zi'] == '2') {
                    $result[$key]['zi'] = 'Marti';
                } elseif($value['zi'] == '3') {
                    $result[$key]['zi'] = 'Miercuri';
                } elseif($value['zi'] == '4') {
                    $result[$key]['zi'] = 'Joi';
                } elseif($value['zi'] == '5') {
                    $result[$key]['zi'] = 'Vineri';
                }

                if($value['status'] == '0') {
                    $result[$key]['status'] = '<span class="regenerate-schedule" style="color: red; cursor: pointer;">Neaprobat (<b>Click pentru regenerare orar</b>)</span>';
                } elseif($value['status'] == '1') {
                    $result[$key]['status'] = '<span style="color: green;">Aprobat</span>';
                }
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

        function add_exception($subgroups,$day,$start,$end) {
            global $db,$handler;

            $sql = "INSERT INTO `subgrupa_exceptii` (`id_specializare_an_subgrupa`,`zi`,`ora`) VALUES (?,?,?)";

            if($handler == 'PDO') {

                // Prepared Statement built with PDO

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $idSubgroup);
                    $stmt->bindParam(2, $dayException);
                    $stmt->bindParam(3, $hourException);

                    foreach($subgroups as $key=>$value) {
                        $idSubgroup = $value;
                        $dayException = $day;
                        $hourException = $start . '-' . $end;
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

                $stmt->bind_param("iis", $idSubgroup, $dayException, $hourException);

                foreach($subgroups as $key=>$value) {
                    $idSubgroup = $value;
                    $dayException = $day;
                    $hourException = $start . '-' . $end;
                    $stmt->execute();
                }

                return true;

            }
        }

        /*function get_exception($id) {
            global $db;

            $sql = "SELECT `specializare`.`acronim`,`specializare_an`.`nr_identificare`,`specializare_an_subgrupa`.`nume`,`subgrupa_exceptii`.`id_subgrupa_exceptii`,`subgrupa_exceptii`.`zi`,`subgrupa_exceptii`.`ora` FROM `subgrupa_exceptii` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an_subgrupa` = `subgrupa_exceptii`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` LEFT JOIN `specializare` ON `specializare`.`id_specializare` = `specializare_an`.`id_specializare_an` WHERE `subgrupa_exceptii`.`id_subgrupa_exceptii` '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            $hours = explode('-',$result['ora']);

            $result['startException'] = $hours[0];
            $result['endException'] = $hours[1];

            return $result;
        }*/

        /*function edit_exception($id,$day,$start,$end) {
            global $db;

            $hour = $start . '-' . $end;
            $sql = "UPDATE `subgrupa_exceptii` SET `zi` = '" . $day . "', `ora` = '" . $hour . "' WHERE `id_subgrupa_exceptii` = '" . $id . "'";

            if($db->execute_query($sql)) {
                return true;
            }

        }*/


    }

?>