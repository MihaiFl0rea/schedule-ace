<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 27.06.2016
 * Time: 16:41
 */

    class Model_Exceptions {

        use commonTasks;

        function get_exceptions($page) {
            global $db, $pagination;

            $sql = "SELECT * FROM `profesor_exceptii`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 5;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT `profesor_exceptii`.`id_profesor_exceptie`,`profesor_exceptii`.`status`,`profesor_exceptii`.`zi`,`profesor_exceptii`.`ora`,`profesor`.`nume` FROM `profesor_exceptii` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `profesor_exceptii`.`id_profesor` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

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
            //var_dump($pageDetails);
            // compose final array
            $data = array("result" => $result, "pageDetails" => $pageDetails);

            return $data;
        }

        function get_exception($id) {
            global $db;

            $sql = "SELECT `profesor_exceptii`.`id_profesor_exceptie`,`profesor_exceptii`.`zi`,`profesor_exceptii`.`ora`,`profesor`.`nume` FROM `profesor_exceptii` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `profesor_exceptii`.`id_profesor` WHERE `profesor_exceptii`.`id_profesor_exceptie` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            $hours = explode('-',$result['ora']);

            $result['startException'] = $hours[0];
            $result['endException'] = $hours[1];

            return $result;
        }

        function edit_exception($id,$day,$start,$end) {
            global $db;

            $hour = $start . '-' . $end;
            $sql = "UPDATE `profesor_exceptii` SET `zi` = '" . $day . "', `ora` = '" . $hour . "' WHERE `id_profesor_exceptie` = '" . $id . "'";

            if($db->execute_query($sql)) {
                return true;
            }

        }

    }

?>