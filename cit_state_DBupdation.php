<?php 

require_once('core/database.class.php');



//$sql_company        = "select * from companies where address IS NOT NULL and (city IS NULL or state IS NULL) limit 0,100";
$sql_company        = "select address,id from companies where address IS NOT NULL and (city IS NULL or state IS NULL) limit 0,100";
$query_company      = mysqli_query($link,$sql_company);
$count              =   0;
 while($res_company=mysqli_fetch_array($query_company))
 
 {
 
  /*echo $res_company['address'];*/
 
    $compnay_address   = explode(",",$res_company['address']);
    $countarray        = count($compnay_address);

    $res_company['address']."<br>";
  
    $city    = ltrim($compnay_address[$countarray-3]);
    $state  = substr($compnay_address[$countarray-2],1,2);
    $com_zipcode=explode(" ",$compnay_address[$countarray-2]);
    $zipcode= $com_zipcode[2];
    $country= ltrim($compnay_address[$countarray-1]);

     $sql_update="update  companies set city='$city',state='$state',zipcode='$zipcode',country='$country' where id=$res_company[id]";
    if(mysqli_query($link,$sql_update))
    { 
            $count=$count+1;
    }

}
/*echo $count."row successfully updated";
*/

?>