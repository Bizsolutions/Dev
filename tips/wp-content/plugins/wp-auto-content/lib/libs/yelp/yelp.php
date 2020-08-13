<?php
$API_HOST = "https://api.yelp.com";
$SEARCH_PATH = "/v3/businesses/search";
$BUSINESS_PATH = "/v3/businesses/";
$REVIEWS_PATH1 = "/v3/businesses/";
$REVIEWS_PATH2 = "/reviews";
$TOKEN_PATH = "/oauth2/token";
$GRANT_TYPE = "client_credentials";
function wpautoc_obtain_bearer_token($client_id, $client_secret) {
    try {
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');
        $postfields = "client_id=" . $client_id .
            "&client_secret=" . $client_secret .
            "&grant_type=" . $GLOBALS['GRANT_TYPE'];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $GLOBALS['API_HOST'] . $GLOBALS['TOKEN_PATH'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);
        curl_close($curl);
    } catch(Exception $e) {
        wpautoc_log_to_file('Exception thrown in OAuth token obtaining: ' . $e->getMessage());
        return false;
    }
    $body = json_decode($response);
    if(!isset($body->access_token) || !isset($body->expires_in))
    {
        wpautoc_log_to_file('Failed to parse response in access_token get: ' . print_r($body, true));
        return false;
    }
    $bearer_token = $body->access_token . '{yelpoamatic_separator}' . $body->expires_in;
    return $bearer_token;
}

function wpautoc_yelp_request($bearer_token, $host, $path, $url_params = array()) {
    try {
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');
        $url = $host . $path . "?" . http_build_query($url_params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $bearer_token,
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);
        curl_close($curl);
    } catch(Exception $e) {
        if(stristr($e->getMessage(), 'The requested business was not found') !== false)
        {
            return 'No results found!';
        }
        return 'Curl failed with error ' . $e->getCode() .  ' - ' . $e->getMessage();
    }
    return $response;
}

function wpautoc_yelp_search($bearer_token, $term, $location, /*$lat, $lon, $radius, $category, $locale, $max, $sort_by, $price, $open_now, $open_at, $attributes,*/ $max, $offset = '') {
    $url_params = array();
    if($offset != '')
    {
        $url_params['offset'] = $offset;
    }
    $url_params['term'] = $term;
    $url_params['limit'] = $max;
    // if($open_at != '')
    // {
    //     $unix = strtotime($open_at);
    //     if($unix !== false)
    //     {
    //         $url_params['open_at'] = $unix;
    //     }
    // }
    // if($attributes != '' && $attributes != 'any')
    // {
    //     $url_params['attributes'] = $attributes;
    // }
    // if($open_now != '' && $open_now == '1')
    // {
    //     $url_params['open_now'] = 'true';
    // }
    // if($sort_by != '' && $sort_by != 'best_match')
    // {
    //     $url_params['sort_by'] = $sort_by;
    // }
    // if($price != '' && $price != 'any')
    // {
    //     $url_params['price'] = $price;
    // }
    // if($locale != '' && $locale != 'default')
    // {
        // $url_params['locale'] = $locale;
        // $url_params['locale'] = 'en';
    // }
    // if($category != '' && $category != 'all')
    // {
    //     $url_params['categories'] = $category;
    // }
    if($location != '')
    {
        $url_params['location'] = $location;
    }
    else
    {
        $url_params['latitude'] = $lat;
        $url_params['longitude'] = $lon;
        
    }
    // if($radius != '' && is_numeric($radius))
    // {
    //     $url_params['radius'] = $radius;
    // }
    $result = wpautoc_yelp_request($bearer_token, $GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
    return $result;
}

function wpautoc_get_business($bearer_token, $business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);
    return wpautoc_yelp_request($bearer_token, $GLOBALS['API_HOST'], $business_path);
}

function wpautoc_get_reviews($bearer_token, $locale, $business_id) {
    $reviews_path = $GLOBALS['REVIEWS_PATH1'] . urlencode($business_id) . $GLOBALS['REVIEWS_PATH2'];
    $url_params = array();
    if($locale != '' && $locale != 'default')
    {
        $url_params['locale'] = $locale;
    }
    return wpautoc_yelp_request($bearer_token, $GLOBALS['API_HOST'], $reviews_path, $url_params);
}

function wpautoc_query_api($term, $location, $lat, $lon, $radius, $category, $locale, $max, $sort_by, $price, $open_now, $open_at, $attributes, $bearer_token, $offset = '') {     
    $ret = wpautoc_search($bearer_token, $term, $location, $lat, $lon, $radius, $category, $locale, $max, $sort_by, $price, $open_now, $open_at, $attributes, $offset);
    if($ret == 'No results found!')
    {
        return $ret;
    }
    if(stristr($ret, 'Curl failed with error') !== false)
    {
        return $ret;
    }
    $response = json_decode($ret);
    
    return $response;
}
?>