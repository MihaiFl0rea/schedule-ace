<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 05.07.2016
 * Time: 12:33
 */

    class Model_Schedule {

        use commonTasks;

        function get_active_subgroups() {
            global $db;

            $sql = "SELECT * FROM `materie_specializare`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $compatibleSubgroups = array();
            $counter = 0;

            foreach($result as $key => $value) {

                $jsonArray = json_decode($value['subgrupe']);

                foreach($jsonArray as $key2 => $value2) {

                    $subgroupDetails = explode(', ',$value2);

                    if(!in_array($subgroupDetails[1], $compatibleSubgroups)) {
                        array_push($compatibleSubgroups, $subgroupDetails[1]);

                        $data[$counter]['group'] = $subgroupDetails[0];
                        $data[$counter]['subgroup'] = $subgroupDetails[1];

                        $counter++;
                    }

                }
            }

            return $data;

        }

        function check_schedule_existence($group,$semester) {
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

        function get_schedule($group,$semester) {
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

        /* GENERATE SCHEDULE */

        /* Main Function */
        function generate_schedule($group,$semester) {
            global $db;

            // Setting up day and hour counters
            $dayCounter = 1; // Monday
            $hourCounter = 8; // 8 AM
            $dayDuration = 0; // number of hours (classes) in one day
            $year = date('Y');

            // #0. Get subgroups of the group we're working on
            $yearSubgroups = $this->get_year_subgroups($group);
            //var_dump($yearSubgroups); die();
            // #1. Get classes asigned to this group and their teachers ids
            $classesAndTeachers = $this->get_asigned_classes($group,$semester);

            // some counters needed
            $counterPriority2 = 0;
            $counterPriority3 = 0;

            $coursesClasses = array();

            // #2. Start to iterate through our subgroups array (#0)
            foreach($yearSubgroups as $keySubgroup => $valueSubgroup) {

                // #3. Start to iterate through our classes and teachers array (#1)
                // at this point, we already have filtered the classes, so we'll go through courses first
                foreach($classesAndTeachers as $keyClass => $valueClass) {

                    // save the details of our current teacher
                    $currentTeacher = $this->get_teacher_details($valueClass['id_profesor']);
                    //var_dump($currentTeacher);
                    if(($valueClass['tip_materie'] == '1') || ($valueClass['tip_materie'] == '3')) {

                        if(isset($pastFirstSubgroup)) {

                            $coursesClasses[$valueClass['id_materie']]['id_specializare_an_subgrupa'] = $valueSubgroup['id_specializare_an_subgrupa'];
                            $currentCourse = $coursesClasses[$valueClass['id_materie']];

                            // insert data into database
                            $this->insert_into_schedule($currentCourse);

                            $dayCounter = $coursesClasses[$valueClass['id_materie']]['dayIncremented'];
                            $hourCounter = $coursesClasses[$valueClass['id_materie']]['hourIncremented'];
                            $dayDuration = $coursesClasses[$valueClass['id_materie']]['dayDuration'];

                        } else {

                            $courseData = $this->filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year);

                            $hourCounter = intval($courseData['hourIncremented']);
                            $dayCounter = intval($courseData['dayIncremented']);
                            $dayDuration = intval($courseData['dayDuration']);

                            // insert data into database
                            $this->insert_into_schedule($courseData);

                            $coursesClasses[$valueClass['id_materie']] = $courseData;
                        }
                    } else {
                        if($currentTeacher['prioritate'] == '1') {

                            $classData = $this->filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year);

                            $hourCounter = intval($classData['hourIncremented']);
                            $dayCounter = intval($classData['dayIncremented']);
                            $dayDuration = intval($classData['dayDuration']);

                            // insert data into database
                            $this->insert_into_schedule($classData);

                        } elseif($currentTeacher['prioritate'] == '2') {

                            $classesAndTeachersP2[$counterPriority2] = $classesAndTeachers[$keyClass];
                            $counterPriority2++;

                        } else {

                            $classesAndTeachersP3[$counterPriority3] = $classesAndTeachers[$keyClass];
                            $counterPriority3++;

                        }

                    }

                }

                if(isset($classesAndTeachersP2)){
                    foreach($classesAndTeachersP2 as $keyClass => $valueClass) {

                        $classData = $this->filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year);

                        $hourCounter = intval($classData['hourIncremented']);
                        $dayCounter = intval($classData['dayIncremented']);
                        $dayDuration = intval($classData['dayDuration']);

                        // insert data into database
                        $this->insert_into_schedule($classData);

                    }
                }

                if(isset($classesAndTeachersP3)) {
                    foreach($classesAndTeachersP3 as $keyClass => $valueClass) {

                        $classData = $this->filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year);

                        $hourCounter = intval($classData['hourIncremented']);
                        $dayCounter = intval($classData['dayIncremented']);
                        $dayDuration = intval($classData['dayDuration']);

                        // insert data into database
                        $this->insert_into_schedule($classData);

                    }
                }

                // reset counters
                $hourCounter = 8;
                $dayCounter = 1;
                $dayDuration = 0;
                $counterPriority2 = 0;
                $counterPriority3 = 0;

                // first subgroup has passed
                $pastFirstSubgroup = true;
            }

            // mark all exceptions as solved
            $this->update_exceptions();

            return true;

        }

        /* Helper / Secondary function, called in the main function from above */

        function filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year) {
            global $db;

            // TO DO: laboratoare si proiecte, ore schimbate ... fixare sali si ore laboratoare si proiecte in fucntie de para / impara ... exceptii - verificare ... linia 370 - variabila globala in care adun numarul de ore(duratele) si las <= 20

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");

            // set debugging
            $debugging = 1; // 0 -> no, 1 -> yes

            // day, hour and duration counters
            $currentDay = intval($dayCounter);
            $currentHour = intval($hourCounter);
            $dayDuration = intval($dayDuration);

            // save the details of our current teacher
            $currentTeacher = $this->get_teacher_details($valueClass['id_profesor']);

            // Debugging Purpose
            $debuggingString = 'Parameters (' . date('d-m-Y H:i:s') . ') ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume']  . ' | Day Duration: ' . $dayDuration . PHP_EOL . PHP_EOL ;

            /*// check_8h_limit - subgroup
            $eightHoursLimitSubgroup = $this->check_8h_limit(0,$valueSubgroup['id_specializare_an_subgrupa'],$currentDay,$semester,$year);
            if((intval($eightHoursLimitSubgroup) > 8) || ((intval($eightHoursLimitSubgroup) + intval($valueClass['durata'])) > 8)) {
                $currentDay++; // Tuesday
                $currentHour = 8; // 8 AM
            }

            // Debugging Purpose
            $debuggingString .= '8h limit checker - subgroup ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . PHP_EOL .PHP_EOL ;*/

            // check_8h_limit - teacher
            $eightHoursLimitTeacher = $this->check_8h_limit(1,$valueClass['id_profesor'],$currentDay,$semester,$year);
            if((intval($eightHoursLimitTeacher) > 8) || ((intval($eightHoursLimitTeacher) + intval($valueClass['durata'])) > 8)) {
                $currentDay++; // Tuesday
                $currentHour = 8; // 8 AM
            }

            // Debugging Purpose
            $debuggingString .= '8h limit checker - teacher ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: '  . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . PHP_EOL .PHP_EOL ;

            // check_time_exception ... 0 - subgroup
            $subgroupException = $this->check_time_exception(0,$valueSubgroup['id_specializare_an_subgrupa'],$currentDay);
            if($subgroupException) {
                if($currentHour + intval($valueClass['durata']) > intval($subgroupException['start'])) {
                    if(intval($subgroupException['end']) + intval($valueClass['durata']) > 18 ) {
                        $currentDay++; // Tuesday
                        $currentHour = 8; // 8 AM
                    } else {
                        $currentHour = intval($subgroupException['end']);
                    }
                }
            }

            // Debugging Purpose
            $debuggingString .= 'subgroup exception checker ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . PHP_EOL .PHP_EOL ;

            if($valueClass['tip_materie'] != '1') {
                // check_time_exception ... 1 - teacher
                $teacherException = $this->check_time_exception(1,$currentTeacher['id_profesor'],$currentDay);

                if($teacherException) {
                    if($currentHour + intval($valueClass['durata']) > intval($teacherException['start'])) {
                        if(intval($teacherException['end']) + intval($valueClass['durata']) > 18 ) {
                            $currentDay++; // Tuesday
                            $currentHour = 8; // 8 AM
                        } else {
                            $currentHour = intval($teacherException['end']);
                        }
                    }
                }

                // Debugging Purpose
                $debuggingString .= 'teacher exception checker ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . PHP_EOL .PHP_EOL ;
            }

            // check_teacher_in_schedule
            $teacherCheck = $this->check_existence_in_schedule(0,$valueClass['durata'],$currentTeacher['id_profesor'],$currentDay,$currentHour,$semester,$year);
            if($teacherCheck) {
                $currentHour = intval($teacherCheck);
            }

            // Debugging Purpose
            $debuggingString .= 'teacher in schedule checker ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . PHP_EOL .PHP_EOL ;

            // get_hall
            if($valueClass['id_sala_dedicata'] != '0') {
                $hall = $this->get_hall_by_id($valueClass['id_sala_dedicata']);
                // check_hall_in_schedule
                $hallCheck = $this->check_existence_in_schedule(1,$valueClass['durata'],$valueClass['id_sala_dedicata'],$currentDay,$currentHour,$semester,$year);
                if($hallCheck) {
                    $currentHour = intval($hallCheck);
                }
            } else {
                $hall = $this->get_hall($valueClass['tip_sala_curs'],$currentHour,$currentDay,$semester,$year);
            }

            // Debugging Purpose
            $debuggingString .= 'Hall getter ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Hour: ' . $currentHour . ' | Subgroup: ' . $valueSubgroup['nume'] . ' | Hall: ' . $hall['nume'] .  PHP_EOL .PHP_EOL ;

            $initialHour = intval($currentHour);
            $initialDay = intval($currentDay);

            if(($currentHour + intval($valueClass['durata']) >= 20) || ($dayDuration + intval($valueClass['durata']) >= 8)) {
                $currentHour = 8;
                $currentDay++;
                $dayDuration = 0;
            } else {
                $currentHour += intval($valueClass['durata']);
                $dayDuration += intval($valueClass['durata']);
            }

            $classData = array('id_specializare_an_subgrupa' => $valueSubgroup['id_specializare_an_subgrupa'],'id_materie' => $valueClass['id_materie'], 'id_sala_curs' => $hall['id_sala_curs'], 'id_profesor' => $currentTeacher['id_profesor'], 'ziua' => $initialDay, 'ora' => $initialHour, 'semestru' => $semester, 'an' => $year, 'dayIncremented' => $currentDay, 'hourIncremented' => $currentHour, 'dayDuration' => $dayDuration);

            // Debugging Purpose
            $debuggingString .= 'Final ' . PHP_EOL . ' Class: '.$valueClass['nume'] . ', type: ' . $valueClass['tip_materie'] . ' | Teacher: ' . $currentTeacher['nume'] . PHP_EOL . ' Day: ' . $currentDay . ' | Subgroup: ' . $valueSubgroup['nume'] . ' | Hour: ' . $currentHour . ' | Hall: ' . $hall['nume'] .  PHP_EOL .PHP_EOL ;

            $debuggingString .= '-------------------------------------------------------------------' . PHP_EOL;

            $debugging == 1 ? fwrite($fileName,$debuggingString) : '';

            fclose($fileName);

            return $classData;

        }

        /* Getters / Checkers Functions */

        function get_year_subgroups($idNumber) {
            global $db;

            $sql = "SELECT `specializare_an_subgrupa`.`id_specializare_an_subgrupa`,`specializare_an_subgrupa`.`nume` FROM `specializare_an` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an` = `specializare_an`.`id_specializare_an` WHERE `specializare_an`.`nr_identificare` = '" . $idNumber . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

        function get_asigned_classes($groupIdNumber,$semester) {
            global $db;

            $sql = "SELECT * FROM `materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie` LEFT JOIN `profesor_materie` ON `profesor_materie`.`id_materie_specializare` = `materie_specializare`.`id_materie_specializare` WHERE `materie`.`semestru` = '" . $semester . "' ORDER BY `materie`.`tip_materie`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            $counterArray = 0;
            $groupFound = false;
            $idsGroupClasses = '';

            foreach($result as $key => $value) {

                $jsonArray = json_decode($value['subgrupe']);

                foreach($jsonArray as $key2 => $value2) {

                    $subgroupDetails = explode(', ',$value2);

                    if($subgroupDetails[1] == $groupIdNumber) {

                        if($groupFound == false) {
                            $data[$counterArray] = $this->get_class_details($value['id_materie']);
                            $data[$counterArray]['id_profesor'] = $value['id_profesor'];
                            //$idsGroupClasses .= $value['id_materie_specializare'] . ',';

                            $counterArray++;

                            $groupFound = true;
                        }
                    }

                }

                $groupFound = false;

            }

            //$idsGroupClasses = rtrim($idsGroupClasses,',');

            //$data = array('data' => $data, 'idsGroupClasses' => $idsGroupClasses);

            return $data;
        }

        function get_class_details($idClass) {
            global $db;

            $sql = "SELECT * FROM `materie` WHERE `id_materie` = '" . $idClass . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        function get_teachers($idsGroupClasses) {
            global $db;

            $sql = "SELECT `profesor_materie`.`id_materie_specializare`,`profesor`.`id_profesor`,`profesor`.`nume`,`profesor`.`prioritate`,`materie_specializare`.`id_materie` FROM `profesor_materie` LEFT JOIN `profesor` ON `profesor`.`id_profesor` = `profesor_materie`.`id_profesor` LEFT JOIN `materie_specializare` ON `materie_specializare`.`id_materie_specializare` = `profesor_materie`.`id_materie_specializare` WHERE `profesor_materie`.`id_materie_specializare` IN (" . $idsGroupClasses . ") ORDER BY `profesor`.`prioritate`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

        function get_teacher_details($idTeacher) {
            global $db;

            $sql = "SELECT `id_profesor`,`nume`,`prioritate` FROM `profesor` WHERE `id_profesor` = '" . $idTeacher . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        function get_hall($hallTypeId,$hour,$day,$semester,$year) {
            global $db;

            $sql = "SELECT `id_sala_curs`,`nume` FROM `sala_curs` WHERE `facilitati` = '" . $hallTypeId . "' AND `id_sala_curs` NOT IN (SELECT `id_sala_curs` FROM `orar_zi_subgrupa` WHERE `ziua` = '" . $day . "' AND `ora` = '" . $hour . ":00' AND `semestru` = '" . $semester . "' AND `an` = '" . $year . "') LIMIT 1";

            $result = $db->execute_query($sql);

            // file used for debugging purpose
            $fileName=fopen("debugging.txt","a+");
            fwrite($fileName, $db->last_query);
            fclose($fileName);

            $result = $db->fetch_row($result);

            return $result;

        }

        function get_hall_by_id($id) {
            global $db;

            $sql = "SELECT `id_sala_curs`,`nume` FROM `sala_curs` WHERE `id_sala_curs` = '" . $id . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        function check_time_exception($for,$id,$day) {
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

        function check_8h_limit($for,$id,$day,$semester,$year) {
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

        function check_existence_in_schedule($for,$classTime,$id,$day,$hour,$semester,$year) {
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

        function insert_into_schedule($data) {
            global $db;

            $sql = "INSERT INTO `orar_zi_subgrupa`(`id_specializare_an_subgrupa`,`id_materie`,`id_sala_curs`,`id_profesor`,`ziua`,`ora`,`semestru`,`an`) VALUES ('" . $data['id_specializare_an_subgrupa'] . "','" . $data['id_materie'] . "','" . $data['id_sala_curs'] . "','" . $data['id_profesor'] . "','" . $data['ziua'] . "','" . $data['ora'] . ":00" . "','" . $data['semestru'] . "','" . $data['an'] . "')";

            $result = $db->execute_query($sql);

            if($result) {
                return true;
            }
        }

        function update_exceptions() {
            global $db;

            $sql = "UPDATE `profesor_exceptii` SET `status` = '1'";

            $db->execute_query($sql);

            $sql = "UPDATE `subgrupa_exceptii` SET `status` = '1'";

            $db->execute_query($sql);
        }

    }

?>