<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 24.01.2017
 * Time: 15:11
 */

    trait ClassGetters {

        /**
         * Get subgroups of certain year
         * @param $idNumber
         * @return array
         */
        public function get_year_subgroups($idNumber) {
            global $db;

            $sql = "SELECT `specializare_an_subgrupa`.`id_specializare_an_subgrupa`,`specializare_an_subgrupa`.`nume` FROM `specializare_an` LEFT JOIN `specializare_an_subgrupa` ON `specializare_an_subgrupa`.`id_specializare_an` = `specializare_an`.`id_specializare_an` WHERE `specializare_an`.`nr_identificare` = '" . $idNumber . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            return $result;
        }

        /**
         * Get classes assigned to a certain group
         * @param $groupIdNumber
         * @param $semester
         * @return mixed
         */
        public function get_asigned_classes($groupIdNumber,$semester) {
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

        /**
         * Get details of given class
         * @param $idClass
         * @return mixed
         */
        public function get_class_details($idClass) {
            global $db;

            $sql = "SELECT * FROM `materie` WHERE `id_materie` = '" . $idClass . "'";

            $result = $db->execute_query($sql);

            $result = $db->fetch_row($result);

            return $result;
        }

        /**
         * Get class groups
         * @return array
         */
        public function get_class_groups() {
            global $db;

            $sql = "SELECT `materie_specializare`.`id_materie_specializare`,`materie_specializare`.`subgrupe`,`materie`.`nume`,`materie`.`tip_materie` FROM `materie_specializare` LEFT JOIN `materie` ON `materie`.`id_materie` = `materie_specializare`.`id_materie`";

            $result = $db->execute_query($sql);

            $result = $db->fetch_array($result);

            foreach($result as $key => $value) {
                if($value['tip_materie'] == '1') {
                    $result[$key]['tip_materie'] = 'Curs';
                } elseif($value['tip_materie'] == '2') {
                    $result[$key]['tip_materie'] = 'Laborator';
                } elseif($value['tip_materie'] == '3') {
                    $result[$key]['tip_materie'] = 'Seminar';
                } elseif($value['tip_materie'] == '4') {
                    $result[$key]['tip_materie'] = 'Proiect';
                }

                $jsonArray = json_decode($value['subgrupe']);
                $subgroupsString = '';
                foreach($jsonArray as $key2 => $value2) {
                    $subgroupsString .= $value2 . ' | ';
                }
                $subgroupsString = rtrim($subgroupsString, ' | ');
                $result[$key]['subgroups'] = $subgroupsString;
            }

            return $result;
        }

    }

?>