<?php

//database class 
class db_class {

    //init the time zone setting, should run as soon as the class is instanced
    var $db_server; // database server
    var $db_username; // database username
    var $db_password; // database password
    var $db_name; // database name
    var $conn;

    function __construct() {
        $this->db_server = "localhost";
        //Local server
        /* 	
          $this->db_username = "root";
          $this->db_password = "";
          $this->db_name = "topmoversreview"; */


        // Arvix Server
        /* 	$this->db_name = "bishara7_topmovingreview";
          $this->db_username = "bishara7_review";
          $this->db_password = "CV&[wi#1ceK)"; */

        // Arvix Server

       $this->db_name = "topmovin_mymovingreviews";

		$this->db_username = "topmovin_mmr";

		$this->db_password = "m&[^y71j*a@&";
    }

    //connect function
    function db_connect() {

        $this->conn = mysqli_connect($this->db_server, $this->db_username, $this->db_password);
        if ($this->conn) {
//   			mysqli_select_db($this->db_name,$link);
            $this->conn->select_db($this->db_name);
        }
        return $this->conn;
    }

    //disconnect from database
    function db_disconnect($link) {

        if ($link) {
            mysqli_close($link);
        }
    }

    function base_url() {
        return 'http://localhost/Dev/';
//        $protocol = filter_input(INPUT_SERVER, 'HTTPS');
//        if (empty($protocol)) {
//            $protocol = "http";
//        }
//
//        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
//
//        $request_uri_full = filter_input(INPUT_SERVER, 'REQUEST_URI');
//        $last_slash_pos = strrpos($request_uri_full, "/");
//        if ($last_slash_pos === FALSE) {
//            $request_uri_sub = $request_uri_full;
//        } else {
//            $request_uri_sub = substr($request_uri_full, 0, $last_slash_pos + 1);
//        }
//
//        return $protocol . "://" . $host . $request_uri_sub;
    }

}

$connection = new db_class(); //i created a new object
$connection->db_connect();
?>
