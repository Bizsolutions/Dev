

			<table width="330" border="1">
  <tr>
    <td width="127">Company Name</td>
    <td width="111">Address</td>
    <td width="70">Date</td>
  </tr>
<?php
require 'core/database.class.php';
 	
$sql="SELECT id,title,address, date_saved  FROM  companies";
 $query=mysqli_query($link,$sql);
 while($res=mysqli_fetch_array($query)){		   

$company_id= $res['id'] ;
  $mmrimg1 = "mmr_images/logos/logo_".$company_id.".jpg";
		
		if(file_exists($mmrimg1) && filesize($mmrimg1)=='870')
	      {
		
			?>
			

  <tr>
    <td><?php echo $res['title'];  ?></td>
    <td><?php echo $res['address'];  ?></td>
    <td><?php echo $res['date_saved'];  ?></td>
  </tr>
  <?php } } ?>
  
</table>
