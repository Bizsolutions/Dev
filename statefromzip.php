<?php ob_start(); 

require_once('core/database.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();


$zipcode=$_GET['q'];

		
	 $sql2="SELECT b.name FROM zipcodes a , states b where a.State=b.state_code    and  a.ZipCode='$zipcode' and b.usa_state=1 Group By a.State limit 0,1";
	$query2=mysql_query($sql2);
	$info=mysql_fetch_array($query2);?>

			  <input name="state" id="state" type="text" style="width:340px;height:35px;" value="<?php echo $info[0];?>"  />
			   
