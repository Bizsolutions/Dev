<?php
function wpautoc_monetize_envato( $content, $monetize ) {
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
	$products = wpautoc_get_envato_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'envato', $buy_btn_txt, $price );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_envato_products( $monetize_id, $campaign_id, $keyword, $category ) {
    $transient_name = 'envato_products'.$monetize_id;
    if ( false === ( $envato_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $envato_products = wpautoc_do_get_envato_products( $keyword, $category );
         set_transient( $transient_name, $envato_products, 6 * HOUR_IN_SECONDS );
    }
    return $envato_products;
}

function wpautoc_do_get_envato_products( $keyword, $category = 0, $num_ads = 6 ) {
	// $envato_settings = wpautoc_get_settings( array( 'affiliate', 'envato' ) );
	// $apikey = isset( $envato_settings['apikey'] ) && !empty( $envato_settings['apikey'] ) ? $envato_settings['apikey'] : '';
	// $username = isset( $envato_settings['username'] ) && !empty( $envato_settings['username'] ) ? $envato_settings['username'] : 'raulmellado';

	$apikey = '6qj2tndmtblrbdjxv2eakolyss1lat07';
	$username = 'raulmellado';
// echo "a";

	if( empty( $apikey ) )
		return false;
// echo "b";

	// $args = array(
	// 	'timeout' => 30,
	// 	'redirection' => 5,
	// 	'headers' => array("Authorization" => "Bearer ".$apikey)
 //    );

	$res = wp_remote_get( 'https://api.envato.com/v1/discovery/search/search/item?term='.urlencode( $keyword ), $args );

	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	$results = array();
	if( isset( $res->matches ) && !empty( $res->matches ) ) {
	    foreach($res->matches as $item) {
	    	// var_dump($item->image_urls);
	    	// var_dump($item);
			$res = array();
	        $res['title']   = $item->name;
	        // $res['description']   = empty( $item->description ) ? $item->name : $item->description;
	        if( isset( $item->previews->icon_with_landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->icon_with_landscape_preview->landscape_url;
	        else if( isset( $item->previews->landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->landscape_preview->landscape_url;
	        else
	        	$thumbnail = $item->author_image;
	        $res['image_url']  = $thumbnail;
	        $res['url']  = add_query_arg( 'ref', $username, $item->url );
			$res['price'] = round( $item->price_cents / 100 ) ;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


?>