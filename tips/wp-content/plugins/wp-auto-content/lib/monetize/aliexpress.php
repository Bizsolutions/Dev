<?php

define( 'WPAUTOC_EPN_API_URL', 'http://api.epn.bz/json' );
define( 'WPAUTOC_EPN_CLIENT_API_VERSION', 2 );

function wpautoc_monetize_aliexpress( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	// var_dump($monetize);
	$keyword = isset( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : false;
	$show_price = isset( $settings->show_price ) ? $settings->show_price : -1;
	$buy_btn_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? trim( $settings->buy_button_txt ) : 'Buy Now';
	$num_ads = isset( $settings->num_ads ) ? intval( $settings->num_ads ) : 3;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	// $products = isset( $settings->products ) ? $settings->products : false;
	$header = isset( $settings->header ) ? $settings->header : false;

	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads per_row_'.$ads_per_row.'">';
	// shuffle( $products );
	$products = wpautoc_get_aliexpress_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'aliexpress', $buy_btn_txt, $price );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_aliexpress_products( $monetize_id, $campaign_id, $keyword, $category = false ) {
    $transient_name = 'aliexpress_products'.$monetize_id;
    if ( false === ( $aliexpress_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $aliexpress_products = wpautoc_do_get_aliexpress_products( $keyword, $category );
         set_transient( $transient_name, $aliexpress_products, 6 * HOUR_IN_SECONDS );
    }
    // $aliexpress_products = wpautoc_do_get_aliexpress_products( $keyword, $category );
    // var_dump($aliexpress_products);
    return $aliexpress_products;
}

function wpautoc_do_get_aliexpress_products( $keyword, $category = 0, $num_ads = 6 ) {
	// API request variables
	$aliexpress_settings = wpautoc_get_settings( array( 'affiliate', 'aliexpress' ) );

	$apikey = isset( $aliexpress_settings['apikey'] ) && !empty( $aliexpress_settings['apikey'] ) ? $aliexpress_settings['apikey'] : '0fabfb08cb1146181b566df18a055f5a';
	$hash = isset( $aliexpress_settings['hash'] ) && !empty( $aliexpress_settings['hash'] ) ? $aliexpress_settings['hash'] : 'p1n2wjs55jg8t38o1r3aqhetb9k8m0o3';

	$data = array(
		'user_api_key' => $apikey,
		'user_hash' => $hash,
		'api_version' => WPAUTOC_EPN_CLIENT_API_VERSION,
		'requests' => array( 'search_1' => array( 'query' => urlencode( $keyword ), 'action' => 'search', 'limit' => $num_ads, 'offset' => 0 ) )
	);

	$args = array(
		'method' => 'POST',
		'timeout' => 30,
		'redirection' => 5,
		'headers' => array("Content-Type" => "text/plain"),
		'body' => json_encode( $data ),
    );

	$res = wp_remote_post( WPAUTOC_EPN_API_URL, $args );
	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );

	$results = array();
	if( isset( $res->results->search_1->offers ) && !empty( $res->results->search_1->offers ) ) {
	    foreach($res->results->search_1->offers as $item) {
	    	// var_dump($item);
			$res = array();
	        $res['title']   = $item->name;
	        // $res['description']   = $item->description;
	        $res['image_url']   = $item->picture;
	        $res['url']  = $item->url;
			$res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;

	return $results;
	// var_dump($resp);
}

?>