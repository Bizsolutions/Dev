<?php
include_once WPAUTOC_DIR.'/lib/libs/amazon2/AmazonProductRequest5.php';

function wpautoc_monetize_amazon( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$keyword = isset( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : 'All';
	$num_ads = isset( $settings->num_ads ) ? intval( $settings->num_ads ) : 3;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	$show_price = isset( $settings->show_price ) ? $settings->show_price : -1;
	$buy_btn_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? trim( $settings->buy_button_txt ) : 'Buy Now';

	// $products = isset( $settings->products ) ? $settings->products : false;
	$header = isset( $settings->header ) ? $settings->header : false;
	// if( empty( $products ) )
	// 	return $content;
	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads per_row_'.$ads_per_row.'">';
	// var_dump($products);
	//shuffle( $products );
	$products = wpautoc_get_amazon_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	// var_dump($product);
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['name'], $product['url'], $product['image'], 'amazon', $buy_btn_txt, $price );
	    }
	}
	/*if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	$url = urldecode( $product->url );
	    	$image_url = $product->image;
	    	$product_name = $product->name;
	    	$price = isset( $product->discount_price ) ? $product->discount_price : 0;
			$code .= wpautoc_monetize_prod( $product_name, $url, $image_url, 'amazon' );
	    }
	}*/
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_amazon_products( $monetize_id, $campaign_id, $keyword, $category ) {
    $transient_name = 'amazon_products'.$monetize_id;
    if ( false === ( $amazon_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $amazon_products = wpautoc_update_amazon_data( $keyword, $category );
         set_transient( $transient_name, $amazon_products, 6 * HOUR_IN_SECONDS );
    }
    // $amazon_products = wpautoc_do_get_amazon_products( $keyword, $category );
    // var_dump($amazon_products);
    return $amazon_products;
}

function wpautoc_update_amazon_data( $keyword, $category = 'All' ) {
		try
		{
			// $plugin_options = wpautoc_get_plugin_options();
			$amazon_settings = wpautoc_get_settings( array( 'affiliate', 'amazon' ) );
			$country = isset( $amazon_settings['country'] ) && !empty( $amazon_settings['country'] ) ? $amazon_settings['country'] : 'com';

		    $request = new AmazonProductRequestAC5($amazon_settings['key'], $amazon_settings['tag'], 
		                                        $amazon_settings['secret'], $country);

	    	$response = $request->searchItems( $keyword, $category );
		    /* Set our response to array format. */
		    // $request->setConfigResponseFormat('array');
		    // $request->setConfigDelay(true);

		    // $response = $request->resetParams()
		    //     ->setResponseGroup('Medium')
		    //     ->setItemPage(1)
		    //     ->setSearchIndex($category)
		    //     ->itemSearch( $keyword );
		    $products = wpautoc_extract_products( $response );
		    return $products;
	    }
	    catch (Exception $e)
	    {
	        print $e->getMessage();
	        return false;
	        die();
	    }
}

function wpautoc_extract_products( $response ) {
	if( !$response ) return '';
	$array_ret = array();
	// var_dump($response);
	if( isset( $response ) && !empty( $response ) ) {
		foreach( $response as $item ) {
			// var_dump();
			$itemInfo = isset( $item['ItemInfo'] ) ? $item['ItemInfo'] : array();

			// var_dump($item['ItemAttributes']);
			$price = isset( $item['Offers']['Listings'][0]['Price']['DisplayAmount'] ) ? $item['Offers']['Listings'][0]['Price']['DisplayAmount'] : '';
			// $discount_price = isset( $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] ) ? $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] : '';
			// var_dump($item['ItemLinks']);
			$array_ret[] = array(
				'name' => wpautoc_escape_input_txt( $itemInfo['Title']['DisplayValue'] ),
				'price' =>  $price,
				'discount_price' =>  0/*$discount_price*/,
				'image' => isset( $item['Images']['Primary']['Medium']['URL'] ) ? $item['Images']['Primary']['Medium']['URL'] : ( isset( $item['Images']['Primary']['Large']['URL'] ) ? $item['Images']['Primary']['Large']['URL'] : false ),
				'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? /*rawurlencode(*/ $item['DetailPageURL'] /*)*/ : ''
				);
		}
	}
	return $array_ret;
}
?>