<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 16.03.2016
 * Time: 15:00
 */

    require_once "core/config.php";

    // Helper Class
    class mysqliHandler {

        private $db;
        public $last_query;
        //private $magic_quotes_active;
        //private $real_escape_string_exists;

        function __construct() {
            $this->open_db();
            //$this->db->query("SET NAMES utf8");
            //$this->magic_quotes_active = get_magic_quotes_gpc();
            //$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );
        }

        public function open_db() {
            $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($this->db->connect_error) {
                die('Database connection failed: ' . $this->db->connect_error . '<br/>' .  $this->db->connect_errno);
            }
            else {
                if(!$this->db->select_db(DB_NAME)){
                    die('Selection of database failed: ' . $this->db->connect_error . '<br/>' .  $this->db->connect_errno);
                }
            }
        }

        public function close_db() {
            if(isset($this->db)) {
                mysqli_close($this->db);
                unset($this->db);
            }
        }

        public function execute_query($sql) {
            $this->last_query = $sql;
            $result = $this->confirm_query($sql);
            return $result;
        }

        private function confirm_query($sql) {

            try {
                $result = $this->db->query($sql);
                if($result)
                    return $result;
                else
                    throw new Exception($this->db->error);
            } catch(Exception $e) {
                $html = 'Database query failed!' . '<br/><br/>';
                $html .= "<i>Last SQL query</i>: <b>" . $this->last_query . "</b><br/><br/>";
                $html .= "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                echo $html;
            }

        }

        public function prepare($sql) {
            $this->last_query = $sql;
            try {
                $stmt = $this->db->prepare($sql);
                if($stmt)
                    return $stmt;
                else
                    throw new Exception($this->db->error);
            } catch(Exception $e) {
                $html = 'Preparation of query failed!' . '<br/><br/>';
                $html .= "<i>Last SQL query</i>: <b>" . $this->last_query . "</b><br/><br/>";
                $html .= "<i>Error</i>: <b>" . $e->getMessage() . "</b><br/>";
                echo $html;
            }

        }

        // "database-neutral" methods
        public function fetch_array($result_set) {
            $data = array();
            while($row = $result_set->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }

        public function fetch_row($result_set) {
            $data = $result_set->fetch_assoc();
            return $data;
        }

        public function num_rows($result_set) {
            // returns the number of rows in the result set (SELECT query)
            return $result_set->num_rows;
        }

        public function affected_rows() {
            // returns the number of rows affected by the last INSERT, UPDATE or DELETE query
            return $this->db->affected_rows;
        }

        public function insert_id() {
            // get the last id inserted over the current db connection
            return $this->db->insert_id;
        }

    }

    $db = new mysqliHandler();

?>