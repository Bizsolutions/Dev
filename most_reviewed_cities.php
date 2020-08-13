<?php 
/*
$db_name        = "topmovin_mymovingreviews";
$db_username    = "topmovin_mmr";
$db_password    = "m&[^y71j*a@&";
$link = @mysql_connect($db_server, $db_username, $db_password); 
mysql_select_db($db_name); 



$sql_city               = "SELECT city FROM `companies` GROUP by city ";
$query_city             = mysql_query($sql_city);

$str.="<table border=1><tr><td>City Name</td><td>Reviews Count</td></tr>"; 

while($res_city         = mysql_fetch_array($query_city)){
        $city_name      = $res_city['city'];//echo '<br>';
        $sql_review_cnt = "SELECT count(*) as review_cnt FROM companies inner join `reviews` on `reviews`.`company_id` = companies.id where companies.city like '%$city_name%'";
        
        $query_ct       = mysql_query($sql_review_cnt);
        while($res_rw   = mysql_fetch_array($query_ct)){
            $Reviews_Co = $res_rw['review_cnt'];
            $str.="<tr><td>$city_name</td> <td>$Reviews_Co</td></tr>";
        }
}

echo $str;
mysql_close($link);
*/