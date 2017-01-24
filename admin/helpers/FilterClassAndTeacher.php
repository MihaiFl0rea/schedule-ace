<?php

/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:08
 */
class FilterClassAndTeacher
{

    use ClassGetters, HallGetters, ScheduleCheckers, TeacherGetters;

    /**
     * Filter class and teacher
     * @param $valueSubgroup
     * @param $valueClass
     * @param $hourCounter
     * @param $dayCounter
     * @param $dayDuration
     * @param $semester
     * @param $year
     * @return array
     */
    public function filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year) {
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

}