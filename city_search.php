<?php  	 	
require 'core/database.class.php';
 $state=$_GET['q'];
 if(isset($_GET['city'])){
 $city_zip=explode("-",$_GET['city']);
 $city=$city_zip[0];
 $zipcode=$city_zip[1];
 if(strlen($zipcode)==4)
$zipcode="0".$zipcode;
if(strlen($zipcode)==3)
$zipcode="00".$zipcode;
}
?>

<input name="city_name" type="hidden" value="<?php echo $row_city['city'] ;?>" />
<select  class="txtSelect" name="selCity" id="selCity" onChange="return showCity_State(this.value)">
		<option value="">Select City</option>
		<?php  	 	
		$sql_city = "SELECT distinct(city),zipcode FROM companies where state='$state'";
	 	$query_city = mysqli_query($link,$sql_city);

	while($row_city = mysqli_fetch_array($query_city))

	{ ?>
                <option value="<?php echo $row_city['city']."-".$row_city['zipcode']; ?>" <?php if($row_city['city']== $city) {?> selected="selected"  <?php  	 	
				}
				?>><?php echo $row_city['city'] ;?></option>
				<?php  	 	
				}
				?>
              </select> 
			<?php if($city<>"") {				?>
<button type="button"  onclick="window.location.href='https://topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",$city)."-".$state."-".$zipcode; ?>/'">
                                        <!--<img src="images/ico4.jpg">-->
                                        &nbsp;Find
                                    </button><?php }  ?>