<?php

define( 'WPAUTOC_EBAY_PER_PAGE', 60 );
define( 'WPAUTOC_EBAY_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_EBAY (7)
	Unique id: video id
*/

function wpautoc_content_type_ebay( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Wikipedia' );

	$cats = wpautoc_get_ebay_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_content['.$num.'][settings][category]', $cats, false, 'Search products only in this category', '', '' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link to the product at the end of the post so you can get affiliate commissions', '', 'bbtnon'  );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_content['.$num.'][settings][buy_button_txt]', false, 'Text for the Buy Button', 'Ex: Buy now from Ebay.com', '', '', 'bbtnon_row', !isset( $settings->buy_button ) );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_ebay( $campaign_id, $settings, $num_posts = 0 ) {
	// include WPAUTOC_DIR.'/lib/libs/vimeo/vimeo.php';

	$num_imported = 0;

	$ebay_settings = wpautoc_get_settings( array( 'affiliate', 'ebay' ) );
	// $country = isset( $ebay_settings['country'] ) && !empty( $ebay_settings['country'] ) ? $ebay_settings['country'] : 'US';
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : 'All' ;

	if( empty( $keyword ) )
		return 0;

	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_EBAY_MAX_LOOPS )  ) {
		$imported = wpautoc_content_ebay_search( $page, WPAUTOC_EBAY_PER_PAGE, $keyword, $category, $campaign_id, $settings, $num_posts );
		// var_dump($imported);
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	return $num_imported;
}

function wpautoc_content_ebay_search( $page = 1, $per_page = 50, $keyword, $category, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_ebay_search( $keyword, $category, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_ebay_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_ebay_product( $campaign_id, $result, $product_id, $settings );
				$imported++;
				if( $imported >= $num_posts )
					return $imported;
			}
		}
		return $imported;
	}
	else
		return -1;

}


function wpautoc_content_do_ebay_search( $keyword, $category, $page = 1, $per_page = 60 ) {

	// echo 'llamo a do search';
	// API request variables
	$endpoint = 'https://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$def_appid = 'RaulMell-EPNWpFee-PRD-b5d8a3c47-6f4553c5';  // Replace with your own AppID

	$search = $keyword;
	$ebay_settings = wpautoc_get_settings( array( 'affiliate', 'ebay' ) );

	$campid = isset( $ebay_settings['campaignid'] ) && !empty( $ebay_settings['campaignid'] ) ? $ebay_settings['campaignid'] : '5338235125';
	$appid = isset( $ebay_settings['appid'] ) && !empty( $ebay_settings['appid'] ) ? $ebay_settings['appid'] : $def_appid;
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
	$apicall .= "&outputSelector=PictureURLSuperSize";
	// if($sort_by!=""){
	// $apicall .= "&sortOrder=$sort_by";
	// }
	$apicall .= "&descriptionSearch=false";
	$apicall .= "&paginationInput.pageNumber=".intval( $page );
	$apicall .= "&paginationInput.entriesPerPage=".intval( $per_page );
	// $apicall .= $urlfilter;
	$resp = simplexml_load_file($apicall);
	$results = array();
	if ( $resp->searchResult->item ) {

	    foreach( $resp->searchResult->item as $item ) {
	    	// var_dump($item);
	    	// wpautoc_content_do_ebay_search_single( $item->itemId );
			$res = array();
	        $res['id']  = (string) $item->itemId;
	        $img_url = ( isset( $item->pictureURLSuperSize ) && !empty( $item->pictureURLSuperSize ) ) ? ( (string) $item->pictureURLSuperSize ) : ( (string) $item->galleryURL );
	        $res['image_url']   = $img_url;
	        $res['url']  = (string) $item->viewItemURL;
	        $res['title'] = (string) $item->title;
			// $res['price'] = (string) $item->sellingStatus->currentPrice;
			// $currencyid = $item->sellingStatus->currentPrice['currencyId'];
			// if($currencyid == "GBP"){
			// 	$currencysign = "&pound;";
			// } else if($currencyid == "USD") {
			// 	$currencysign = "$";
			// } else {
			// 	$currencysign = "";
			// 	$cidtext = $currencyid;
			// }
			$results[] = $res;
		}
	}
	else
		return false;

	return $results;
}


function wpautoc_content_do_ebay_search_single( $product_id ) {
// echo 'llamo a single';

	// API request variables
	$endpoint = 'http://open.api.ebay.com/shopping';  // URL to call
	$version = '967';  // API version supported by your application
	$appid = 'RaulMell-EPNWpFee-PRD-b5d8a3c47-6f4553c5';  // Replace with your own AppID

	$ebay_settings = wpautoc_get_settings( array( 'affiliate', 'ebay' ) );

	$campid = isset( $ebay_settings['campaignid'] ) && !empty( $ebay_settings['campaignid'] ) ? $ebay_settings['campaignid'] : '5338235125';
	$country = isset( $ebay_settings['country'] ) && !empty( $ebay_settings['country'] ) ? $ebay_settings['country'] : 'US';
	$globalid = 'EBAY-'.$country;

	// Construct the findItemsAdvanced call 
	$apicall = "$endpoint?";
	$apicall .= "callname=GetSingleItem&";
	$apicall .= "&version=$version";
	$apicall .= "&appid=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&ItemID=$product_id";
	// if( $category != 0 ){
	// 	$apicall .= "&categoryId=".$category;
	// }
	$apicall .= "&affiliate.networkId=9";
	$apicall .= "&affiliate.trackingId=$campid";
	$apicall .= "&IncludeSelector=TextDescription";
	// if($sort_by!=""){
	// $apicall .= "&sortOrder=$sort_by";
	// }
	// $apicall .= "&descriptionSearch=false";
	// $apicall .= "&paginationInput.pageNumber=".intval( $page );
	// $apicall .= "&paginationInput.entriesPerPage=".intval( $per_page );
	// $apicall .= $urlfilter;
	$resp = simplexml_load_file($apicall);
	$results = array();
	// var_dump($resp);
	if ( $resp->Item ) {
		// var_dump($item);
		return (string) $resp->Item->Description;
	}
	else
		return '';
}

/*

'name' => wpautoc_escape_input_txt( $item['ItemAttributes']['Title'] ),
// 'price' =>  $price,
'image' => $item['LargeImage']['URL'],
'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? rawurlencode( $item['DetailPageURL'] ) : '',
'review' => ( isset( $item['EditorialReviews'] ) && !empty( $item['EditorialReviews'] ) ) ? $item['EditorialReviews']['EditorialReview']['Content']  : '',
'text' => ( isset( $item['ItemAttributes']['Feature'] ) && !empty( $item['ItemAttributes']['Feature'] ) && is_array( $item['ItemAttributes']['Feature'] ) ) ? '<p>'.implode( '</p><p>', $item['ItemAttributes']['Feature'] ).'</p>' : '',
*/
function wpautoc_do_import_ebay_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = nl2br( wpautoc_content_do_ebay_search_single( $product['id'] ) );
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		if( isset( $settings->spin_content ) && $settings->spin_content ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	if( isset( $settings->buy_button ) && $settings->buy_button ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		// if( $url )
			// $content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-ebay-btn">Buy Now from ebay</a></p>';
		if( $url ) {
			$button_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? $settings->buy_button_txt : 'Buy Now from ebay';
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-ebay-btn">'.$button_txt.'</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_EBAY, $thumbnail );

	return $product_id;
}

function wpautoc_ebay_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_EBAY, $campaign_id );
}

// function wpautoc_ebay_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>