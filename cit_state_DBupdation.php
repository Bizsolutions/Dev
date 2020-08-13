<?php 

require_once('core/database.class.php');



//$sql_company        = "select * from companies where address IS NOT NULL and (city IS NULL or state IS NULL) limit 0,100";
$sql_company        = "select id,address from companies where address IS NOT NULL and (city IS NULL or state IS NULL) limit 0,100";
$query_company      = $connection->conn->query($sql_company);
$count              =   0;
 while($res_company=mysqli_fetch_array($query_company))
 
 {
 
    $compnay_address   = explode(",",$res_company['address']);
    $countarray        = count($compnay_address);

    $res_company['address']."<br>";
    
   ;
  
    $city    = ltrim(isset($compnay_address[$countarray-3])?$compnay_address[$countarray-3]:0);
    $state  = substr($compnay_address[$countarray-2],1,2);
    $com_zipcode=explode(" ",$compnay_address[$countarray-2]);
    $zipcode= isset($com_zipcode[2])?$com_zipcode[2]:'';
    $country= ltrim($compnay_address[$countarray-1]);

     $sql_update="update  companies set city='$city',state='$state',zipcode='$zipcode',country='$country' where id=$res_company[id]";
    if($connection->conn->query($sql_update))
    { 
            $count=$count+1;
    }

}
/*echo $count."row successfully updated";
*/

?>