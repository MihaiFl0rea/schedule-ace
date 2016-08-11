<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.06.2016
 * Time: 16:28
 */

    class Model_Halls {

        use commonTasks;

        function add_hall($name,$location,$facilities) {
            global $db,$handler;

            $sql = "INSERT INTO `sala_curs` (`nume`,`locatie`,`facilitati`) VALUES (?,?,?)";

            if($handler == 'PDO') {

                /* Prepared Statement built with PDO */

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $name);
                    $stmt->bindParam(2, $location);
                    $stmt->bindParam(3, $facilities);

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

                $stmt->bind_param("ssi", $name, $location, $facilities);

                if ($stmt->execute()) {
                    return true;
                }

            }
        }

        function get_halls($page) {
            global $db,$pagination;

            $sql = "SELECT * FROM `sala_curs`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 10;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT * FROM `sala_curs` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);


            foreach($result as $key => $value) {
                if($value['facilitati'] == '1') {
                    $result[$key]['facilitati'] = 'Sala normala';
                } elseif($value['facilitati'] == '2') {
                    $result[$key]['facilitati'] = 'Sala cu videoproiector';
                } elseif($value['facilitati'] == '3') {
                    $result[$key]['facilitati'] = 'Sala cu calculatoare';
                } elseif($value['facilitati'] == '4') {
                    $result[$key]['facilitati'] = 'Aula';
                } elseif($value['facilitati'] == '5') {
                    $result[$key]['facilitati'] = 'Sala dedicata';
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

        function get_hall($id) {
            global $db;

            $sql = "SELECT * FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result[0];
        }

        function edit_hall($id,$name,$location,$facilities) {
            global $db;

            $sql = "UPDATE `sala_curs` SET `nume` = '" . $name . "', `locatie` = '" . $location . "', `facilitati` = '" . $facilities . "' WHERE `id_sala_curs` = '" . $id . "'";

            if($db->execute_query($sql)) {
                return true;
            }
        }

    }

?>