<?php

require '../core/database.class.php';



if(!empty($_POST["MovingFrom"])) {

  $sql_service ="SELECT distinct * FROM zipcodes WHERE  zipcode like '" . $_POST["MovingFrom"] . "%' ORDER BY zipcode LIMIT 0,6";

  $query = mysqli_query($link,$sql_service);



?>

<ul id="country-list" style="background:#FFFFFF; list-style:none;margin-top: 1px;">

<?php

while($res_service=mysqli_fetch_array($query)) {

?>

<li style="padding:5px; cursor:pointer;" onClick="selectCountry('<?php echo $res_service["ZipCode"]; ?>');"><?php echo $res_service["ZipCode"]; ?></li>

<?php } ?>

</ul>

<?php }  



if(!empty($_POST["MovingTo"])) {







?>

<ul id="country-list" style="background:#FFFFFF; list-style:none;margin-top: 1px;">

<?php

if(is_numeric($_POST["MovingTo"])) {

 $sql_service ="SELECT  distinct * FROM zipcodes WHERE city like '" . $_POST["MovingTo"] . "%' or state like '" . $_POST["MovingTo"] . "%' or zipcode like '" . $_POST["MovingTo"] . "%' ORDER BY city,state,zipcode LIMIT 0,10"; 

$query = mysqli_query($link,$sql_service);



while($res_service=mysqli_fetch_array($query)) {

 ?>



<li style="padding:5px; cursor:pointer;" onClick="selectCountry1('<?php echo $res_service["City"].",".$res_service["State"].",".$res_service["ZipCode"]; ?>');"><?php echo $res_service["City"].", ".$res_service["State"].", ".$res_service["ZipCode"]; ?></li>

<?php } } else { 





$sql_service ="SELECT  distinct City,State FROM zipcodes WHERE city like '" . $_POST["MovingTo"] . "%' or state like '" . $_POST["MovingTo"] . "%' group by city,state ORDER BY city,state LIMIT 0,10"; 

$query = mysqli_query($link,$sql_service);



while($res_service=mysqli_fetch_array($query)) {

 ?>





<li style="padding:5px; cursor:pointer;" onClick="selectCountry1('<?php echo $res_service["City"].",".$res_service["State"]; ?>');"><?php echo $res_service["City"].", ".$res_service["State"]; ?></li>



<?php } } ?>

</ul>

<?php }  







?>

































