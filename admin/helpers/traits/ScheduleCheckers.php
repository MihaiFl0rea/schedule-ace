<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:13
 */

    trait ScheduleCheckers {

        /**
         * Check given time exception
         * @param $for
         * @param $id
         * @param $day
         * @return array|bool
         */
        public function check_time_exception($for,$id,$day) {
            global $db;

            // NOTE: Just one exception per day is allowed!

            // for -> 0 - subgroup, 1 - teacher

            $sql = $for == 1 ? "SELECT * FROM `profesor_exceptii` WHERE `id_profesor` = '" . $id . "' AND `zi` = '" . $day . "' LIMIT 1" : "SELECT * FROM `subgrupa_exceptii` WHERE `id_specializare_an_subgrupa` = '" . $id . "' AND `zi` = '" . $day . "' LIMIT 1";

            $result = $db->execute_query($sql);

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");
            fwrite($fileName, $db->last_query);
            fclose($fileName);

            $result = $db->fetch_row($result);

            if($result) {

                // check hour
                $hours = explode('-',$result['ora']);

                $startHour = substr($hours[0],0,-3);
                $endHour = substr($hours[1],0,-3);

                $period = array('start' => $startHour, 'end' => $endHour);

                return $period;

            } else {
                return false;
            }
        }

        /**
         * Check if 8h limit has been reached
         * @param $for
         * @param $id
         * @param $day
         * @param $semester
         * @param $year
         * @return bool|int
         */
        public function check_8h_limit($for,$id,$day,$semester,$year) {
            global $db;

            // for -> 0 - subgroup, 1 - teacher
            $where = $for == 0 ? "`orar_zi_subgrupa`.`id_specializare_an_subgrupa` = '" . $id . "'" : "`orar_zi_subgrupa`.`id_profesor` = '" . $id . "'";

            $sql = "SELECT `materie`.`durata`,`orar_zi_subgrupa`.`ora` FROM `orar_zi_subgrupa` LEFT JOIN `materie` ON `materie`.`id_materie` = `orar_zi_subgrupa`.`id_materie` WHERE " . $where . " AND `orar_zi_subgrupa`.`ziua` = '" . $day . "' AND `orar_zi_subgrupa`.`semestru` = '" . $semester . "' AND `orar_zi_subgrupa`.`an` = '" . $year . "'";

            $result = $db->execute_query($sql);

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");
            fwrite($fileName, $db->last_query);
            fclose($fileName);

            $result = $db->fetch_array($result);

            if($result) {

                $hoursTotal =  0;

                foreach($result as $key => $value) {

                    //$startHour = substr($value['ora'],0,-3);

                    $hoursTotal += intval($value['durata']);

                }
                // return the total hours of this day
                return $hoursTotal;

            } else {
                return false;
            }
        }

        /**
         * Check if certain existence has been already added to schedule
         * @param $for
         * @param $classTime
         * @param $id
         * @param $day
         * @param $hour
         * @param $semester
         * @param $year
         * @return bool|int
         */
        public function check_existence_in_schedule($for,$classTime,$id,$day,$hour,$semester,$year) {
            global $db;

            // for -> 0 - teacher, 1 - hall
            $where = $for == 0 ? "`id_profesor` = '" . $id . "'" : "`id_sala_curs` = '" . $id . "'";

            $sql = "SELECT * FROM `orar_zi_subgrupa` WHERE " . $where . " AND `ziua` = '" . $day . "' AND `semestru` = '" . $semester . "' AND `an` = '" . $year . "'";

            $result = $db->execute_query($sql);

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");
            fwrite($fileName, $db->last_query . PHP_EOL);


            $result = $db->fetch_array($result);

            if($result) {

                /*$validStartHour = 0;
                $validStart = 0;
                $validEnd = 0;*/

                foreach($result as $key => $value) {

                    $classDetails = $this->get_class_details($value['id_materie']);

                    $currentStartHour = substr($value['ora'],0,-3);
                    fwrite($fileName, 'Hour(parameter):' . intval($hour) . ' | Duration(parameter): ' . intval($classTime) . ' | Duration(loop): ' . intval($classDetails['durata']) . ' | Hour(loop): ' . intval($currentStartHour) . PHP_EOL);
                    /*if($validStart != 0) {

                        if(intval($currentStartHour) >= (intval($validStart) + intval($classTime))){
                            return $validStart;
                        }

                    } else {

                        if(intval($classDetails['durata']) + intval($currentStartHour) <= intval($hour)) {
                            $validStart = intval($classDetails['durata']) + intval($currentStartHour);
                        } elseif(intval($currentStartHour) >= (intval($hour) + intval($classTime))) {
                            $validStart = intval($hour);
                        } elseif((intval($currentStartHour) > intval($hour)) && (intval($hour) + intval($classTime) < intval($classDetails['durata']) + intval($currentStartHour))){
                            $validStart = intval($classDetails['durata']) + intval($currentStartHour);
                        } elseif((intval($currentStartHour) < intval($hour)) && (intval($hour) + intval($classTime) > intval($classDetails['durata']) + intval($currentStartHour))) {
                            $validStart = intval($classDetails['durata']) + intval($currentStartHour);
                        }

                    }*/

                    if((intval($hour) + intval($classTime)) <= (intval($classDetails['durata']) + intval($currentStartHour))){

                        $validStart = intval($classDetails['durata']) + intval($currentStartHour);

                        return $validStart;
                    }

                }
                fclose($fileName);
            } else {
                return false;
            }


        }

    }

?>