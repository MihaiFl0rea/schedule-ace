<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.06.2016
 * Time: 22:06
 */

    class Model_Classes {

        use commonTasks;

        function add_class($className,$classDescription,$classCredits,$classEvaluation,$classType,$classDuration,$classHall,$classFrequency,$dedicatedHall,$classYear,$classSemester) {
            global $db,$handler;

            $sql = "INSERT INTO `materie` (`nume`,`an`,`semestru`,`descriere`,`tip_evaluare`,`nr_credite`,`tip_materie`,`durata`,`tip_sala_curs`,`id_sala_dedicata`,`frecventa`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

            if($handler == 'PDO') {

                /* Prepared Statement built with PDO */

                try {

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(1, $className);
                    $stmt->bindParam(2, $classYear);
                    $stmt->bindParam(3, $classSemester);
                    $stmt->bindParam(4, $classDescription);
                    $stmt->bindParam(5, $classEvaluation);
                    $stmt->bindParam(6, $classCredits);
                    $stmt->bindParam(7, $classType);
                    $stmt->bindParam(8, $classDuration);
                    $stmt->bindParam(9, $classHall);
                    $stmt->bindParam(10, $dedicatedHall);
                    $stmt->bindParam(11, $classFrequency);

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

                $stmt->bind_param("siisiiiiiii", $className, $classYear, $classSemester, $classDescription, $classEvaluation, $classCredits, $classType, $classDuration, $classHall, $dedicatedHall, $classFrequency);

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
                if($value['tip_evaluare'] == '1') {
                    $result[$key]['tip_evaluare'] = 'Examen';
                } elseif($value['tip_evaluare'] == '2') {
                    $result[$key]['tip_evaluare'] = 'Colocviu';
                } elseif($value['tip_evaluare'] == '3') {
                    $result[$key]['tip_evaluare'] = 'Proiect';
                }

                if($value['tip_materie'] == '1') {
                    $result[$key]['tip_materie'] = 'Curs';
                } elseif($value['tip_materie'] == '2') {
                    $result[$key]['tip_materie'] = 'Laborator';
                } elseif($value['tip_materie'] == '3') {
                    $result[$key]['tip_materie'] = 'Seminar';
                } elseif($value['tip_materie'] == '4') {
                    $result[$key]['tip_materie'] = 'Proiect';
                }

                if($value['durata'] == '1') {
                    $result[$key]['durata'] = '1 ora';
                } elseif($value['durata'] == '2') {
                    $result[$key]['durata'] = '2 ore';
                } elseif($value['durata'] == '3') {
                    $result[$key]['durata'] = '3 ore';
                }

                if($value['tip_sala_curs'] == '1') {
                    $result[$key]['tip_sala_curs'] = 'Sala normala';
                } elseif($value['tip_sala_curs'] == '2') {
                    $result[$key]['tip_sala_curs'] = 'Sala cu videoproiector';
                } elseif($value['tip_sala_curs'] == '3') {
                    $result[$key]['tip_sala_curs'] = 'Sala cu calculatoare';
                } elseif($value['tip_sala_curs'] == '4') {
                    $result[$key]['tip_sala_curs'] = 'Aula';
                } elseif($value['tip_sala_curs'] == '5') {
                    //$result[$key]['tip_sala_curs'] = 'Sala dedicata';
                    $result[$key]['tip_sala_curs'] = $this->get_dedicated_hall_name($value['id_sala_dedicata']);
                }

                if($value['frecventa'] == '1') {
                    $result[$key]['frecventa'] = 'Saptamanal';
                } elseif($value['frecventa'] == '2') {
                    $result[$key]['frecventa'] = 'La 2 saptamani';
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

        function get_dedicated_hall_name($id) {
            global $db;

            $sql = "SELECT `nume` FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result['nume'];
        }

        function get_dedicated_halls() {
            global $db;

            $sql = "SELECT * FROM `sala_curs` WHERE `facilitati` = '5'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

        function get_class($id) {
            global $db;

            $sql = "SELECT * FROM `materie` WHERE `id_materie` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result[0];
        }

        function edit_class($classId,$className,$classDescription,$classCredits,$classEvaluation,$classType,$classDuration,$classHall,$classFrequency,$dedicatedHall,$classYear,$classSemester) {
            global $db;

            $sql = "UPDATE `materie` SET `nume` = '" . $className . "', `an` = '" . $classYear . "', `semestru` = '" . $classSemester . "', `descriere` = '" . $classDescription . "', `tip_evaluare` = '" . $classEvaluation . "', `nr_credite` = '" . $classCredits . "', `tip_materie` = '" . $classType . "', `durata` = '" . $classDuration . "', `tip_sala_curs` = '" . $classHall . "', `id_sala_dedicata` = '" . $dedicatedHall . "', `frecventa` = '" . $classFrequency . "' WHERE `id_materie` = '" . $classId . "'";

            if($db->execute_query($sql)) {
                return true;
            }
        }

    }

?>