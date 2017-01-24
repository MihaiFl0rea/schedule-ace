<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:13
 */

    trait Schedule {

        /**
         * Check existence in predefined schedule
         * @param $group
         * @param $semester
         * @return string
         */
        public function check_schedule_existence($group,$semester) {
            global $db;

            $sql = "SELECT `orar_zi_subgrupa`.* FROM `orar_zi_subgrupa` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an_subgrupa` = `orar_zi_subgrupa`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` WHERE `specializare_an`.`nr_identificare` = '" . $group . "' AND `orar_zi_subgrupa`.`semestru` = '" . $semester . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);
            if($result) {
                return 'true';
            } else {
                return 'false';
            }
        }

        /**
         * Get certain schedule
         * @param $group
         * @param $semester
         * @return mixed
         */
        public function get_schedule($group,$semester) {
            global $db;

            $sql = "SELECT `orar_zi_subgrupa`.`ziua`,`orar_zi_subgrupa`.`ora`,`materie`.`id_materie`,`materie`.`durata`,`materie`.`nume` AS `numeMaterie`,`materie`.`tip_materie`,`materie`.`frecventa`,`specializare_an_subgrupa`.`nume` AS `numeSubgrupa`,`specializare_an_subgrupa`.`id_specializare_an_subgrupa`,`profesor`.`nume` AS `numeProfesor`,`sala_curs`.`nume` AS `numeSala` FROM `orar_zi_subgrupa` LEFT JOIN `materie` ON `materie`.`id_materie` = `orar_zi_subgrupa`.`id_materie` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `orar_zi_subgrupa`.`id_profesor` LEFT JOIN `sala_curs` ON `sala_curs`.`id_sala_curs` = `orar_zi_subgrupa`.`id_sala_curs` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an_subgrupa` = `orar_zi_subgrupa`.`id_specializare_an_subgrupa` LEFT JOIN `specializare_an` ON `specializare_an`.`id_specializare_an` = `specializare_an_subgrupa`.`id_specializare_an` WHERE `specializare_an`.`nr_identificare` = '" . $group . "' AND `orar_zi_subgrupa`.`semestru` = '" . $semester . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $hourCounter = 0;
            $lastDayCounter = 0;

            $twiceAWeek = array();

            foreach($result as $key => $value) {

                $hourCounter = $hourCounter == 4 ? 0 : $hourCounter;

                switch($value['ziua']) {
                    case '1':
                        $day = 'luni';
                        break;
                    case '2':
                        $day = 'marti';
                        break;
                    case '3':
                        $day = 'miercuri';
                        break;
                    case '4':
                        $day = 'joi';
                        break;
                    case '5':
                        $day = 'vineri';
                        break;
                }

                if($value['ziua'] != $lastDayCounter) {
                    $hourCounter = 0;
                    $lastDayCounter = $value['ziua'];
                }

                switch($value['tip_materie']) {
                    case '1':
                        $type = 'Curs';
                        break;
                    case '2':
                        $type = 'Laborator';
                        break;
                    case '3':
                        $type = 'Seminar';
                        break;
                    case '4':
                        $type = 'Proiect';
                        break;
                }

                $endingHour = (intval(substr($value['ora'],0,-3)) + intval($value['durata'])) . ':00';

                $data[$value['id_specializare_an_subgrupa']]['subgrupa'] = $value['numeSubgrupa'];

                $frequency = '';

                if($value['frecventa'] == '2') {
                    if(isset($twiceAWeek[$value['id_materie']])) {
                        $frequency = "<b style=\"color: black;\">Saptamana impara</b> <br/>";
                    } else {
                        $twiceAWeek[$value['id_materie']] = $value['frecventa'];
                        $frequency = "<b style=\"color: black;\">Saptamana para</b> <br/>";
                    }
                }

                $data[$value['id_specializare_an_subgrupa']]['materii'][$hourCounter][$day] = $frequency . $value['ora'] . '-' . $endingHour . ' / ' . $value['numeMaterie'] . "<br/>" . $type . ' (' .  $value['numeSala'] . ') - ' . $value['numeProfesor'] ;

                $hourCounter++;

            }

            if(isset($data)) {
                return $data;
            }
        }

    }

?>