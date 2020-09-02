<?php

//$link is the database connection string


function getNearbyMoversByCity($city_name, $state_code, $distance, $max_distance, $min_nr_of_companies, $link) {

    // Getting the city coordinates from the database

    $sql = "SELECT * FROM cities_extended WHERE city='" . $city_name . "' AND state_code='" . $state_code . "' LIMIT 0,1";
    $query_coorinates = mysqli_query($link, $sql);


    if (mysqli_num_rows($query_coorinates) < 1) {
        return false;
    }

    $city_data = mysqli_fetch_object($query_coorinates);

    $sql = "SELECT *, ( 3959 * acos( cos( radians(" . $city_data->latitude . ") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(" . $city_data->longitude . ") ) + sin( radians(" . $city_data->latitude . ") ) * sin( radians( latitude ) ) ) ) AS distance FROM cities_extended GROUP BY city HAVING distance < " . $distance . " ORDER BY distance LIMIT 0 , 20";
    /* var_dump($sql); */
    $query_cities = mysqli_query($link, $sql);
    if (mysqli_num_rows($query_cities) < 1) {
        return false;
    }
    // Getting the companies based on cities in the radius

    $company_list = array();
    while ($obj = mysqli_fetch_object($query_cities)) {

        // Getting companies in the nearby city

        $sql = "SELECT companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.rating > 3 AND city='" . $obj->city . "' group by companies.id";

        $query_companies = mysqli_query($link, $sql);

        if (mysqli_num_rows($query_companies) > 0) {

            while ($company_obj = mysqli_fetch_object($query_companies)) {
                $company_list[] = $company_obj;
                if (count($company_list) >= $min_nr_of_companies) {

                    return $company_list;
                }
            }
        }
    }



    if ($distance > $max_distance) {

        return $company_list;
    }

    if (count($company_list) < $min_nr_of_companies) {

        $distance = $distance + 10;

        return getNearbyMoversByCity($city_name, $state_code, $distance, $max_distance, $min_nr_of_companies, $link);
    }
    return $company_list;
}

function NearbyMoversByCity($city_name, $state_code, $distance, $max_distance, $min_nr_of_companies, $link) {
    $sql = "SELECT * FROM cities_extended WHERE city='" . $city_name . "' AND state_code='" . $state_code . "' LIMIT 0,1";
    $query_coorinates = mysqli_query($link, $sql);


    if (mysqli_num_rows($query_coorinates) < 1) {
        return false;
    }
    $getCity = mysqli_fetch_object($query_coorinates);

    $sql = "SELECT city, (
                    3959 * acos (
                      cos ( radians($getCity->latitude) )
                      * cos( radians( latitude ) )
                      * cos( radians( longitude ) - radians($getCity->longitude) )
                      + sin ( radians($getCity->latitude) )
                      * sin( radians( latitude ) )
                    )
                ) AS distance
                FROM cities_extended
                GROUP BY city
                HAVING distance < $distance
                ORDER BY distance";
    $query_cities = mysqli_query($link, $sql);
    if (mysqli_num_rows($query_cities) < 1) {
        return false;
    }

    $cities = array_column($query_cities->fetch_all(MYSQLI_ASSOC), 'city');
    $city = implode(',', array_map('quote', $cities));
    $sql1 = "SELECT companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.rating > 3 AND LOWER(TRIM(city)) In ($city) group by companies.id";
    $query_companies = mysqli_query($link, $sql1);
    return $query_companies->fetch_all(MYSQLI_ASSOC);
}

function quote($str) {
    return sprintf("'%s'", strtolower(trim($str)));
}

?>