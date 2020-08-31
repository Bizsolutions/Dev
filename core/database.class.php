<?php


//database class 
class db_class {
	
	
	//init the time zone setting, should run as soon as the class is instanced
	var $db_server; // database server
	var $db_username; // database username
	var $db_password;	// database password
	var $db_name; // database name
	
	
	function __construct()
    {
        $this->db_server = "localhost";
	//Local server
		
		$this->db_username = "root";
		$this->db_password = "admin";
		$this->db_name = "topmoversreview";
		
		
		// Arvix Server
	/*	$this->db_name = "bishara7_topmovingreview";
		$this->db_username = "bishara7_review";
		$this->db_password = "CV&[wi#1ceK)";*/
	
						// Arvix Server

		/*$this->db_name = "topmovin_mymovingreviews";

		$this->db_username = "topmovin_mmr";

		$this->db_password = "m&[^y71j*a@&";*/
		
    }
	
	
	//connect function
	function db_connect() {
		global $link;
		 $link = mysqli_connect($this->db_server, $this->db_username, $this->db_password); 
		if($link) { 
   			mysqli_select_db($link,$this->db_name);
		}
		return $link;	
	}
	
	//disconnect from database
	function db_disconnect($link) {
		
		if($link) { 
			mysqli_close($link);
		}
		
	}
	
	public function baseUrl() {
	/*return 'https://topmovingreviews.com/';*/
        return 'http://localhost/Dev/';
    }
	
		
}
$connection = new db_class(); //i created a new object
$connection->db_connect();


?>