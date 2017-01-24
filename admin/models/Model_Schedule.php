<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 05.07.2016
 * Time: 12:33
 */

    class Model_Schedule extends FilterClassAndTeacher{

        use commonTasks, Schedule;

        // Setting up day and hour counters
        private $dayCounter; // Monday
        private $hourCounter; // 8 AM
        private $dayDuration; // number of hours (classes) in one day
        private $year;

        public function __construct($dayCounter,$hourCounter,$dayDuration,$year)
        {
            // Setting up day and hour counters
            $this->dayCounter = $dayCounter; // Monday
            $this->hourCounter = $hourCounter; // 8 AM
            $this->dayDuration = $dayDuration; // number of hours (classes) in one day
            $this->year = $year;
        }

        /**
         * Get active subgroups
         * @return mixed
         */
        public function get_active_subgroups() {
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

        /**
         * Generate schedule of certain group and semester
         * @param $group
         * @param $semester
         * @return bool
         */
        function generate_schedule($group,$semester) {
            global $db;

            // #0. Get subgroups of the group we're working on
            $yearSubgroups = $this->get_year_subgroups($group);
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
                    /*
                     * Insert courses and seminars first into schedule */
                    if(($valueClass['tip_materie'] == '1') || ($valueClass['tip_materie'] == '3')) {

                        if(isset($pastFirstSubgroup)) {

                            $coursesClasses[$valueClass['id_materie']]['id_specializare_an_subgrupa'] = $valueSubgroup['id_specializare_an_subgrupa'];
                            $currentCourse = $coursesClasses[$valueClass['id_materie']];

                            // insert data into database
                            $this->insert_into_schedule($currentCourse);

                            $this->dayCounter = $coursesClasses[$valueClass['id_materie']]['dayIncremented'];
                            $this->hourCounter = $coursesClasses[$valueClass['id_materie']]['hourIncremented'];
                            $this->dayDuration = $coursesClasses[$valueClass['id_materie']]['dayDuration'];

                        } else {
                            $courseData = $this->compose_and_update_schedule($valueSubgroup,$valueClass,$this->hourCounter,$this->dayCounter,$this->dayDuration,$semester,$this->year);
                            $coursesClasses[$valueClass['id_materie']] = $courseData;
                        }
                    } else {
                        /*
                         * Compose schedule for teachers with priority 1*/
                        if($currentTeacher['prioritate'] == '1') {
                            $this->compose_and_update_schedule($valueSubgroup,$valueClass,$this->hourCounter,$this->dayCounter,$this->dayDuration,$semester,$this->year);
                        } elseif($currentTeacher['prioritate'] == '2') {
                            // save teachers with priority 2
                            $classesAndTeachersP2[$counterPriority2] = $classesAndTeachers[$keyClass];
                            $counterPriority2++;
                        } else {
                            // save teachers with priority 3
                            $classesAndTeachersP3[$counterPriority3] = $classesAndTeachers[$keyClass];
                            $counterPriority3++;
                        }
                    }

                }

                /*
                 * Compose schedule for teachers with priority 2*/
                if(isset($classesAndTeachersP2)){
                    foreach($classesAndTeachersP2 as $keyClass => $valueClass) {
                        $this->compose_and_update_schedule($valueSubgroup,$valueClass,$this->hourCounter,$this->dayCounter,$this->dayDuration,$semester,$this->year);
                    }
                }

                /*
                 * Compose schedule for teachers with priority 3*/
                if(isset($classesAndTeachersP3)) {
                    foreach($classesAndTeachersP3 as $keyClass => $valueClass) {
                        $this->compose_and_update_schedule($valueSubgroup,$valueClass,$this->hourCounter,$this->dayCounter,$this->dayDuration,$semester,$this->year);
                    }
                }

                // reset counters
                $this->hourCounter = 8;
                $this->dayCounter = 1;
                $this->dayDuration = 0;
                $counterPriority2 = 0;
                $counterPriority3 = 0;

                // first subgroup has passed
                $pastFirstSubgroup = true;
            }

            // mark all exceptions as solved
            $this->update_exceptions();

            return true;
        }

        /**
         * Compose and update schedule
         * @param $valueSubgroup
         * @param $valueClass
         * @param $hourCounter
         * @param $dayCounter
         * @param $dayDuration
         * @param $semester
         * @param $year
         * @return array
         */
        private function compose_and_update_schedule($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year) {
            $classData = $this->filter_class_and_teacher($valueSubgroup,$valueClass,$hourCounter,$dayCounter,$dayDuration,$semester,$year);

            $this->hourCounter = intval($classData['hourIncremented']);
            $this->dayCounter = intval($classData['dayIncremented']);
            $this->dayDuration = intval($classData['dayDuration']);

            // insert data into database
            $this->insert_into_schedule($classData);
            return $classData;
        }

        /**
         * Insert verified data into schedule
         * @param $data
         * @return bool
         */
        private function insert_into_schedule($data) {
            global $db;

            $sql = "INSERT INTO `orar_zi_subgrupa`(`id_specializare_an_subgrupa`,`id_materie`,`id_sala_curs`,`id_profesor`,`ziua`,`ora`,`semestru`,`an`) VALUES ('" . $data['id_specializare_an_subgrupa'] . "','" . $data['id_materie'] . "','" . $data['id_sala_curs'] . "','" . $data['id_profesor'] . "','" . $data['ziua'] . "','" . $data['ora'] . ":00" . "','" . $data['semestru'] . "','" . $data['an'] . "')";

            $result = $db->execute_query($sql);

            if($result) {
                return true;
            }
        }

        /**
         * Update exception, if found
         */
        private function update_exceptions() {
            global $db;

            $sql = "UPDATE `profesor_exceptii` SET `status` = '1'";

            $db->execute_query($sql);

            $sql = "UPDATE `subgrupa_exceptii` SET `status` = '1'";

            $db->execute_query($sql);
        }

    }

?>