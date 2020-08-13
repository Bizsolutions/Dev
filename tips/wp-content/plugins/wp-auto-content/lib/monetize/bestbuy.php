<?php
function wpautoc_monetize_bestbuy( $content, $monetize ) {
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
	$products = wpautoc_get_bestbuy_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'bestbuy', $buy_btn_txt, $price );

	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_bestbuy_products( $monetize_id, $campaign_id, $keyword, $category ) {
    $transient_name = 'bestbuy_products'.$monetize_id;
    if ( false === ( $bestbuy_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $bestbuy_products = wpautoc_do_get_bestbuy_products( $keyword, $category );
         set_transient( $transient_name, $bestbuy_products, 6 * HOUR_IN_SECONDS );
    }
    return $bestbuy_products;
}

function wpautoc_do_get_bestbuy_products( $keyword, $category = 0, $num_ads = 6 ) {
	$bestbuy_settings = wpautoc_get_settings( array( 'affiliate', 'bestbuy' ) );
	$api_key = isset( $bestbuy_settings['apikey'] ) && !empty( $bestbuy_settings['apikey'] ) ? $bestbuy_settings['apikey'] : '0ckoDt0yU9C8Vb8Ca4x9RyJH';

	$url = 'https://api.bestbuy.com/v1/products((search='.urlencode( $keyword ).'))?apiKey='.$api_key.'&sort=bestSellingRank.asc&show=description,image,name,shortDescription,url,salePrice,bestSellingRank&format=json';

	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  $prods = json_decode( $body );
	  // var_dump($prods);
	  $products = array();
	  if( $prods->total > 0 ) {
	  	foreach( $prods->products as $item ) {
	  		// var_dump($item);
	  		$prod = array();
			$prod['title'] = (string) $item->name;
			$prod['description'] = (string) $item->shortDescription;
			$prod['url']  = (string) $item->url;
			// rmi353, probablemente es productTrackingUrl
			$prod['image_url']   = (string) $item->image;
			$prod['price'] = (string) $item->salePrice;
			$products[] = $prod;
	  	}
	  }
	}
	else
		return false;

	return $products;
	// var_dump($resp);
}

?>