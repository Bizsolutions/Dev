<?php
//require 'core/database.class.php';
$db_server = "localhost";

$db_name = "topmovin_mymovingreviews";

$db_username = "topmovin_mmr";

$db_password = "7Ld7jqEUSU";
mysql_connect($db_server,$db_username,$db_password);
mysql_select_db($db_name);

require 'functions.php';

$city_name = "Queens";
$state_code = "NY";
$result = getNearbyMoversByCity($city_name,$state_code,30,200,10);

var_dump($result);

?>