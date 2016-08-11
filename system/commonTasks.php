<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 18.03.2016
 * Time: 9:18
 */

trait CommonTasks {

    // generic function for generating the number of rows from a certain database table
    public function get_total_of($tableName) {
        global $db;

        $sql = "SELECT COUNT(*) AS `total` FROM `" . $tableName . "`";

        $result = $db->execute_query($sql);

        $result = $db->fetch_row($result);

        return $result['total'];

    }

    // getter functions built with the function defined above

    public function get_groups_number() {

        return $this->get_total_of('specializare');

    }

    public function get_teachers_number() {

        return $this->get_total_of('profesor');

    }

    public function get_classes_number() {

        return $this->get_total_of('materie');

    }

    public function get_halls_number() {

        return $this->get_total_of('sala_curs');

    }

    public function get_class_groups_number() {

        return $this->get_total_of('materie_specializare');

    }

    public function get_teacher_exceptions_number() {

        return $this->get_total_of('profesor_exceptii');

    }

    public function get_groups_exceptions_number() {

        return $this->get_total_of('subgrupa_exceptii');

    }

    // function to use in controllers for a fast asignment in twig variables
    public function create_counters_array() {

        $statistics = array("groups" => $this->get_groups_number(), "teachers" => $this->get_teachers_number(), "classes" => $this->get_classes_number(), "halls" => $this->get_halls_number(), "class_groups" => $this->get_class_groups_number(), "teacher_exceptions" => $this->get_teacher_exceptions_number(), "groups_exceptions" => $this->get_groups_exceptions_number());

        return $statistics;

    }

}

?>