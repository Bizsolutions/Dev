<?php


function getNearbyMoversByCity($city_name,$state_code, $distance, $max_distance, $min_nr_of_companies)
{
	// Getting the city coordinates from the database
	$sql = "SELECT * FROM cities_extended WHERE city='".$city_name."' AND state_code='".$state_code."' LIMIT 0,1";
	
	$query_coorinates = mysql_query($sql);

		if(mysql_num_rows($query_coorinates) < 1)
	{
		return false;
	}

	$city_data= mysql_fetch_object($query_coorinates);
	

	$sql = "SELECT *, ( 3959 * acos( cos( radians(".$city_data->latitude.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$city_data->longitude.") ) + sin( radians(".$city_data->latitude.") ) * sin( radians( latitude ) ) ) ) AS distance FROM cities_extended GROUP BY city HAVING distance < ".$distance." ORDER BY distance LIMIT 0 , 20";
	/*var_dump($sql);*/

	$query_cities = mysql_query($sql);
	
	
	if(mysql_num_rows($query_cities) < 1)
	{
		return false;
	}

	// Getting the companies based on cities in the radius
	$company_list = array();

	while ($obj = mysql_fetch_object($query_cities)) 
	{
		// Getting companies in the nearby city
		 $sql = "SELECT companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.rating > 3 AND city='".$obj->city."' group by companies.id";
		$query_companies = mysql_query($sql);

		if(mysql_num_rows($query_companies) > 0)
		{
			while ($company_obj = mysql_fetch_object($query_companies)) 
			{
				$company_list[] = $company_obj;

				if(count($company_list) >= $min_nr_of_companies)
				{ 
					return $company_list;
				}
			}
		}
	}

	if($distance > $max_distance)
	{
		return $company_list;
	}

	if(count($company_list) < $min_nr_of_companies)
	{
		$distance = $distance+10;
		return getNearbyMoversByCity($city_name,$state_code, $distance, $max_distance, $min_nr_of_companies);
	}
	
	
	return $company_list;
}
?>