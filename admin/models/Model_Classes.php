<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.06.2016
 * Time: 22:06
 */

    class Model_Classes {

        use commonTasks, HallGetters, ClassGetters, ClassDetailsGetters;

        function add_class($classProperties, $classDedicatedHall) {
            global $db,$handler;

            $sql = "INSERT INTO `materie` (`nume`,`an`,`semestru`,`descriere`,`tip_evaluare`,`nr_credite`,`tip_materie`,`durata`,`tip_sala_curs`,`id_sala_dedicata`,`frecventa`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

            if($handler == 'PDO') {

                /* Prepared Statement built with PDO */

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $classProperties['className']);
                    $stmt->bindParam(2, $classProperties['classYear']);
                    $stmt->bindParam(3, $classProperties['classSemester']);
                    $stmt->bindParam(4, $classProperties['classDescription']);
                    $stmt->bindParam(5, $classProperties['classEvaluation']);
                    $stmt->bindParam(6, $classProperties['classCredits']);
                    $stmt->bindParam(7, $classProperties['classType']);
                    $stmt->bindParam(8, $classProperties['classDuration']);
                    $stmt->bindParam(9, $classProperties['classHall']);
                    $stmt->bindParam(10, $classDedicatedHall);
                    $stmt->bindParam(11, $classProperties['classFrequency']);

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

                $stmt->bind_param("siisiiiiiii", $classProperties['className'], $classProperties['classYear'], $classProperties['classSemester'], $classProperties['classDescription'], $classProperties['classEvaluation'], $classProperties['classCredits'], $classProperties['classType'], $classProperties['classDuration'], $classProperties['classHall'], $classDedicatedHall, $classProperties['classFrequency']);

                if ($stmt->execute()) {
                    return true;
                }

            }
        }

        function get_classes($page) {
            global $db,$pagination;

            $sql = "SELECT * FROM `materie`";
            $resultAll = $db->execute_query($sql);
            $rowsNumber = $db->num_rows($resultAll);

            $pagination->per_page = 20;
            $pagination->total_count = $rowsNumber;
            $pagination->current_page = $page;

            // get final result
            $sqlLimit = "SELECT * FROM `materie` LIMIT " . $pagination->per_page . " OFFSET " . $pagination->offset();

            $result = $db->execute_query($sqlLimit);
            $result = $db->fetch_array($result);


            foreach($result as $key => $value) {
                $result[$key]['tip_evaluare'] = $this->getEvaluationType($result[$key]['tip_evaluare']);

                $result[$key]['tip_materie'] = $this->getClassType($result[$key]['tip_materie']);

                $result[$key]['durata'] = $this->getDuration($result[$key]['durata']);

                $result[$key]['tip_sala_curs'] = $this->getHallType($result[$key]['tip_sala_curs'],$value['id_sala_dedicata']);

                $result[$key]['frecventa'] = $this->getFrequency($result[$key]['frecventa']);
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

        function get_class($id) {
            global $db;

            $sql = "SELECT * FROM `materie` WHERE `id_materie` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result[0];
        }

        function edit_class($classProperties,$dedicatedHall) {
            global $db;

            $sql = "UPDATE `materie` SET `nume` = '" . $classProperties['className'] . "', `an` = '" . $classProperties['classYear'] . "', `semestru` = '" . $classProperties['classSemester'] . "', `descriere` = '" . $classProperties['classDescription'] . "', `tip_evaluare` = '" . $classProperties['classEvaluation'] . "', `nr_credite` = '" . $classProperties['classCredits'] . "', `tip_materie` = '" . $classProperties['classType'] . "', `durata` = '" . $classProperties['classDuration'] . "', `tip_sala_curs` = '" . $classProperties['classHall'] . "', `id_sala_dedicata` = '" . $dedicatedHall . "', `frecventa` = '" . $classProperties['classFrequency'] . "' WHERE `id_materie` = '" . $classProperties['classId'] . "'";

            if($db->execute_query($sql)) {
                return true;
            }
        }

    }

?>