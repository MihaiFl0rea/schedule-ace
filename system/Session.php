<?php
/**
 * Created by PhpStorm.
 * User: Dumbledore
 * Date: 16.03.2016
 * Time: 15:03
 */

    // Helper Class
    class Session {

        private $logged_in=false;
        public $user_id;
        public $user_id_front;
        public $message;

        function __construct() {
            session_start();
            //$this->check_message();
            $this->check_login();
            /*if($this->logged_in) {
                // actions to take right away if user is logged in
            } else {
                // actions to take right away if user is not logged in
            }*/
        }

        public function is_logged_in() {
            return $this->logged_in;
        }

        public function login($user,$location) {
            // database should find user based on username/password
            if($user != ''){
                // $location => 0 - admin, 1 - front
                if($location == 0) {
                    $this->user_id = $user;
                    $_SESSION['user_id'] = $user;
                } elseif($location == 1) {
                    $this->user_id_front = $user;
                    $_SESSION['user_id_front'] = $user;
                }
                $this->logged_in = true;
            }
        }

        public function logout($type) {
            if($type == 0) { // Admin Logout
                unset($_SESSION['user_id']);
                unset($this->user_id);
            } elseif($type == 1) { // Front Logout
                unset($_SESSION['user_id_front']);
                unset($this->user_id_front);
            }

            $this->logged_in = false;
        }

        public function message($msg="") {
            if(!empty($msg)) {
                // then this is "set message"
                $_SESSION['message'] = $msg;
            } else {
                // then this is "get message"
                return $this->message;
            }
        }

        private function check_login() {
            if(isset($_SESSION['user_id'])) {
                $this->user_id = $_SESSION['user_id'];
                $this->logged_in = true;
            } elseif(isset($_SESSION['user_id_front'])) {
                $this->user_id_front = $_SESSION['user_id_front'];
                $this->logged_in = true;
            } else {
                unset($this->user_id);
                $this->logged_in = false;
            }
        }

        private function check_message() {
            // Is there a message stored in the session?
            if(isset($_SESSION['message'])) {
                // Add it as an attribute and erase the stored version
                $this->message = $_SESSION['message'];
                unset($_SESSION['message']);
            } else {
                $this->message = "";
            }
        }

    }

    $session = new Session();
    //$message = $session->message();

?>