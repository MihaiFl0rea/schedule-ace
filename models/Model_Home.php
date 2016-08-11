<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 28.06.2016
 * Time: 18:17
 */

    class Model_Home {

        function get_exceptions($idTeacher) {
            global $db;

            $sql = "SELECT * FROM `profesor_exceptii` WHERE `id_profesor` = '" . $idTeacher . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            if($result) {

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

                    $hours = explode('-',$value['ora']);
                    $result[$key]['startException'] = $hours[0];
                    $result[$key]['endException'] = $hours[1];
                }

                return $result;
            }

            return '';
        }

        function get_teacher_schedule($idTeacher) {
            global $db;

            // calculate semester
            if((date('n') >= 10) || (date('n') <= 3)) {
                $semester = 1;
            } elseif((date('n') <= 9) || (date('n') >= 4)) {
                $semester = 2;
            }

            $sql = "SELECT `orar_zi_subgrupa`.`id_orar_zi_subgrupa`,`orar_zi_subgrupa`.`ziua`,`orar_zi_subgrupa`.`ora`,`materie`.`id_materie`,`materie`.`durata`,`materie`.`nume` AS `numeMaterie`,`materie`.`tip_materie`,`specializare_an_subgrupa`.`nume` AS `numeSubgrupa`,`specializare_an_subgrupa`.`id_specializare_an_subgrupa`,`profesor`.`nume` AS `numeProfesor`,`sala_curs`.`nume` AS `numeSala`,`specializare_an`.`nr_identificare` FROM `orar_zi_subgrupa` LEFT JOIN `materie` ON `materie`.`id_materie` = `orar_zi_subgrupa`.`id_materie` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `orar_zi_subgrupa`.`id_profesor` LEFT JOIN `sala_curs` ON `sala_curs`.`id_sala_curs` = `orar_zi_subgrupa`.`id_sala_curs` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an_subgrupa` = `orar_zi_subgrupa`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` WHERE `orar_zi_subgrupa`.`id_profesor` = '" . $idTeacher . "' AND `orar_zi_subgrupa`.`semestru` = '" . $semester . "' ORDER BY `orar_zi_subgrupa`.`ziua`,`orar_zi_subgrupa`.`ora` ";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $data = array();
            $courses = array();

            foreach($result as $key => $value) {

                switch($value['ziua']) {
                    case '1':
                        $result[$key]['ziua'] = 'Luni';
                        break;
                    case '2':
                        $result[$key]['ziua'] = 'Marti';
                        break;
                    case '3':
                        $result[$key]['ziua'] = 'Miercuri';
                        break;
                    case '4':
                        $result[$key]['ziua'] = 'Joi';
                        break;
                    case '5':
                        $result[$key]['ziua'] = 'Vineri';
                        break;
                }

                switch($value['tip_materie']) {
                    case '1':
                        $result[$key]['tip_materie'] = 'Curs';
                        break;
                    case '2':
                        $result[$key]['tip_materie'] = 'Laborator';
                        break;
                    case '3':
                        $result[$key]['tip_materie'] = 'Seminar';
                        break;
                    case '4':
                        $result[$key]['tip_materie'] = 'Proiect';
                        break;
                }

                $result[$key]['ora'] = $value['ora'] . ' - ' . (intval(substr($value['ora'],0,-3)) + intval($value['durata'])) . ':00';

                if(($value['tip_materie'] == '1') && isset($courses[$value['numeMaterie']][$value['tip_materie']])) {
                    $data[$courses[$value['numeMaterie']][$value['tip_materie']]]['grupa'] = $data[$courses[$value['numeMaterie']][$value['tip_materie']]]['nr_identificare'];
                } else {
                    $courses[$value['numeMaterie']][$value['tip_materie']] = $key;
                    $data[$key] = $result[$key];
                    $data[$key]['grupa'] = $data[$key]['nr_identificare'] . ', ' . $data[$key]['numeSubgrupa'];
                }

            }

            return $data;
        }

    }

?>