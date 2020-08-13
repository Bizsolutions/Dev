<?php
	error_reporting(E_ALL);
	require 'core/database.class.php';

	$sql  = "SELECT * FROM `companies` WHERE logo LIKE '%mymovingreviews.com%'";
	$query = mysql_query($sql);

	$path = "/home/topmovingreviews/public_html/company/logos/";

	if(mysql_num_rows($query) > 0)
	{
		echo "Processing Logos....";
		while ($obj = mysql_fetch_object($query)) 
		{
			if(strlen($obj->logo) > 0 && strpos($obj->logo, "mymovingreviews.com") > 0)
			{
				echo $obj->logo."<br>";
				$logo_file = file_get_contents($obj->logo);
				$logo_name_explode = explode("/", $obj->logo);
				$nr_logo = count($logo_name_explode)-1;
				file_put_contents($path.$logo_name_explode[$nr_logo], $logo_file);

				if(is_file($path.$logo_name_explode[$nr_logo]))
				{
					$sql = "UPDATE companies SET logo='https://www.topmovingreviews.com/company/logos/".$logo_name_explode[$nr_logo]."' WHERE id='".$obj->id."'";
					mysql_query($sql);
				}
			}
		}
	}
?>