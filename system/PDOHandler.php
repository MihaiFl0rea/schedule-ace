<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 04.04.2016
 * Time: 9:38
 */

    // To see mysql and sql lite support, uncomment the line below
    // print_r(PDO::getAvailableDrivers());

    class PDOHandler {

        private $db;
        public $last_query;

        function __construct() {
            $this->open_connection();
        }

        function open_connection() {
            try {
                $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        function close_connection() {
            if(isset($this->db)) {
                $this->db = null;
            }
        }

        public function execute_query($sql) {
            $this->last_query = $sql;
            $result = $this->confirm_query($sql);
            return $result;
        }

        private function confirm_query($sql) {
            try {
                $stmt = $this->db->query($sql);
                if($stmt)
                    return $stmt;
            } catch(PDOException $e) {
                $html = 'Database query failed!' . '<br/><br/>';
                $html .= "<i>Last SQL query</i>: <b>" . $this->last_query . "</b><br/><br/>";
                $html .= "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                echo $html;
            }
        }

        public function prepare($sql) {
            $stmt = $this->db->prepare($sql);
            return $stmt;
        }

        // "database-neutral" methods
        public function fetch_array($result_set) {
            return $result_set->fetchAll(PDO::FETCH_ASSOC);
        }

        public function fetch_row($result_set) {
            return $result_set->fetch(PDO::FETCH_ASSOC);
        }

        public function num_rows($result_set) {
            // returns the number of rows in the result set (SELECT query)
            return $result_set->rowCount();
        }

        /*public function affected_rows() {
            // returns the number of rows affected by the last INSERT, UPDATE or DELETE query
            return $this->db->affected_rows;
        }*/

        public function insert_id() {
            // get the last id inserted over the current db connection
            return $this->db->lastInsertId();
        }

    }

    $db = new PDOHandler();
?>