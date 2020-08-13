<?php
function wpautoc_monetize_ebay( $content, $monetize ) {
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
	$products = wpautoc_get_ebay_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $num_ads );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$show_price || $show_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= wpautoc_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'ebay', $buy_btn_txt, $price );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_ebay_products( $monetize_id, $campaign_id, $keyword, $category ) {
    $transient_name = 'ebay_products'.$monetize_id;
    if ( false === ( $ebay_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $ebay_products = wpautoc_do_get_ebay_products( $keyword, $category );
         set_transient( $transient_name, $ebay_products, 6 * HOUR_IN_SECONDS );
    }
    // $ebay_products = wpautoc_do_get_ebay_products( $keyword, $category );
    // var_dump($ebay_products);
    return $ebay_products;
}

function wpautoc_do_get_ebay_products( $keyword, $category = 0, $num_ads = 6 ) {
	// API request variables
	$endpoint = 'https://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'RaulMell-EPNWpFee-PRD-b5d8a3c47-6f4553c5';  // Replace with your own AppID

	$search = $keyword;
	$ebay_settings = wpautoc_get_settings( array( 'affiliate', 'ebay' ) );

	$campid = isset( $ebay_settings['campaignid'] ) && !empty( $ebay_settings['campaignid'] ) ? $ebay_settings['campaignid'] : '5338235125';
	$country = isset( $ebay_settings['country'] ) && !empty( $ebay_settings['country'] ) ? $ebay_settings['country'] : 'US';
	$globalid = 'EBAY-'.$country;

	$SafeQuery = urlencode($keyword);  // Make the query URL-friendly
	// Construct the findItemsAdvanced call 
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=findItemsAdvanced";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&SECURITY-APPNAME=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&keywords=$SafeQuery";
	if( $category != 0 ){
		$apicall .= "&categoryId=".$category;
	}
	$apicall .= "&affiliate.networkId=9";
	$apicall .= "&affiliate.trackingId=$campid";
	// if($sort_by!=""){
	// $apicall .= "&sortOrder=$sort_by";
	// }
	$apicall .= "&descriptionSearch=false";
	$apicall .= "&paginationInput.entriesPerPage=$num_ads";
	// $apicall .= $urlfilter;
	$resp = simplexml_load_file($apicall);
	$results = array();
	if ( $resp->searchResult->item ) {
    foreach($resp->searchResult->item as $item) {
		$res = array();
        $res['image_url']   = (string) $item->galleryURL;
        $res['url']  = (string) $item->viewItemURL;
        $res['title'] = (string) $item->title;
		$res['price'] = (string) $item->sellingStatus->currentPrice;
		$currencyid = $item->sellingStatus->currentPrice['currencyId'];
		if($currencyid == "GBP"){
			$currencysign = "&pound;";
		} else if($currencyid == "USD") {
			$currencysign = "$";
		} else {
			$currencysign = "";
			$cidtext = $currencyid;
		}
		$results[] = $res;
	}
	}
	else
		return false;

	return $results;
	// var_dump($resp);
}

// function wpautoc_eeextract_products( $response ) {
// 	if( !$response ) return '';
// 	$array_ret = array();
// 	if( isset( $response['Items']['Item'] ) ) {
// 		foreach( $response['Items']['Item'] as $item ) {
// 			// var_dump($item['ItemAttributes']);
// 			$price = isset( $item['ItemAttributes']['ListPrice']['FormattedPrice'] ) ? $item['ItemAttributes']['ListPrice']['FormattedPrice'] : '';
// 			$discount_price = isset( $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] ) ? $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] : '';
// 			$array_ret[] = array(
// 				'name' => wpautoc_escape_input_txt( $item['ItemAttributes']['Title'] ),
// 				'price' =>  $price,
// 				'discount_price' =>  $discount_price,
// 				'image' => $item['MediumImage']['URL'],
// 				'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? rawurlencode( $item['DetailPageURL'] ) : ''
// 				);
// 		}
// 	}
// 	return $array_ret;
// }
?>