<?php
function wpautoc_monetize_walmart( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	// var_dump($monetize);
	$keyword = isset( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : 'All';
	$show_price = isset( $settings->show_price ) ? $settings->show_price : -1;
	$buy_btn_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? trim( $settings->buy_button_txt ) : 'Buy Now';
	$num_ads = isset( $settings->num_ads ) ? intval( $settings->num_ads ) : 3;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	// $products = isset( $settings->products ) ? $settings->products : false;
	$header = isset( $settings->header ) ? $settings->header : false;

	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads per_row_'.$ads_per_row.'">';
	// shuffle( $products );
	$products = wpautoc_get_walmart_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'walmart', $buy_btn_txt, $price );

	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_walmart_products( $monetize_id, $campaign_id, $keyword, $category ) {
    $transient_name = 'walmart_products'.$monetize_id;
    if ( false === ( $walmart_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $walmart_products = wpautoc_do_get_walmart_products( $keyword, $category );
         set_transient( $transient_name, $walmart_products, 6 * HOUR_IN_SECONDS );
    }
    return $walmart_products;
}

function wpautoc_do_get_walmart_products( $keyword, $category = 0, $num_ads = 6 ) {
	$walmart_settings = wpautoc_get_settings( array( 'affiliate', 'walmart' ) );
	$api_key = isset( $walmart_settings['apikey'] ) && !empty( $walmart_settings['apikey'] ) ? $walmart_settings['apikey'] : 'nd4cev46pk9xf9vr8cfxuqt4';
	$aff_id = isset( $walmart_settings['aff_id'] ) && !empty( $walmart_settings['aff_id'] ) ? $walmart_settings['aff_id'] : '2512587';

	$url = 'http://api.walmartlabs.com/v1/search?query='.urlencode( $keyword ).'&format=json&apiKey='.$api_key.'&lsPublisherId='.$aff_id;

	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  $prods = json_decode( $body );
	  $products = array();
	  if( $prods->numItems > 0 ) {
	  	foreach( $prods->items as $item ) {
	  		// var_dump($item);
	  		$prod = array();
			$prod['title'] = (string) $item->name;
			$prod['description'] = (string) $item->shortDescription;
			$prod['url']  = (string) $item->productUrl;
			// rmi353, probablemente es productTrackingUrl
			$prod['image_url']   = (string) $item->mediumImage;
			$prod['price'] = (string) $item->salePrice;
			$products[] = $prod;
	  	}
	  }
	}
	else
		return false;

	return $products;
}


?>