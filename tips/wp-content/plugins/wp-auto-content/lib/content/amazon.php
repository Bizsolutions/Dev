<?php

define( 'WPAUTOC_AMAZON_PER_PAGE', 100 );
define( 'WPAUTOC_AMAZON_MAX_LOOPS', 25 );

/*
	Type: WPAUTOC_CONTENT_AMAZON (7)
	Unique id: video id
*/

function wpautoc_content_type_amazon( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'amazon' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Amazon product, you need a valid Amazon Key/Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Amazon details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// var_dump($settings);
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Amazon' );

	$categories = wpautoc_get_amazon_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_content['.$num.'][settings][category]', $categories, false );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Product Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link to the product at the end of the post so you can get affiliate commissions', '', 'bbtnon'  );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_content['.$num.'][settings][buy_button_txt]', false, 'Text for the Buy Button', 'Ex: Buy now from Amazon.com', '', '', 'bbtnon_row', !isset( $settings->buy_button ) );

	echo '</table>';
	// echo 'test;';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_amazon( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$amazon_settings = wpautoc_get_settings( array( 'affiliate', 'amazon' ) );
	$country = isset( $amazon_settings['country'] ) && !empty( $amazon_settings['country'] ) ? $amazon_settings['country'] : 'com';
	$tag = isset( $amazon_settings['tag'] ) && !empty( $amazon_settings['tag'] ) ? $amazon_settings['tag'] : '';
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : 'All' ;

	if( empty( $keyword ) )
		return 0;
	if ( empty( $amazon_settings[ 'key' ] ) || empty( $amazon_settings[ 'secret' ] ) )
		return 0;
	$amazon = new AmazonProductRequestAC5( $amazon_settings['key'], $tag, $amazon_settings['secret'], $country );
	// var_dump($amazon);
// echo "a";
	if( empty( $amazon ) )
		return 0;


	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_AMAZON_MAX_LOOPS )  ) {
		$imported = wpautoc_content_amazon_search( $amazon, $page, WPAUTOC_AMAZON_PER_PAGE, $keyword, $category, $campaign_id, $settings, $num_posts );
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

function wpautoc_content_amazon_search( $amazon, $page = 1, $per_page = 50, $keyword, $category, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	// $amazon->setConfigResponseFormat( 'array' );
	// $amazon->setConfigDelay( true );
	// var_dump($amazon);
	try {
		$search_results = $amazon->searchItems( $keyword, $category, $page );
	// $search_results = $amazon->resetParams()
	//     ->setResponseGroup( 'Medium' )
	//     ->setItemPage( $page )
	//     ->setSearchIndex( $category )
	//     ->itemSearch( $keyword );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($search_results);
	$results = $search_results;
	// $results = isset( $search_results['Items']['Item'] ) ? $search_results['Items']['Item'] : false;
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			// var_dump($result['ItemInfo']['ProductInfo']);die('');
			// var_dump($result['ItemInfo']['ContentInfo']);die('');
			$product_id = $result['ASIN'];/*wpautoc_amazon_extract_id( $video['uri'] )*/;
			if( !wpautoc_amazon_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_amazon_product( $campaign_id, $result, $product_id, $settings );
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

/*

'name' => wpautoc_escape_input_txt( $item['ItemAttributes']['Title'] ),
// 'price' =>  $price,
'image' => $item['LargeImage']['URL'],
'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? rawurlencode( $item['DetailPageURL'] ) : '',
'review' => ( isset( $item['EditorialReviews'] ) && !empty( $item['EditorialReviews'] ) ) ? $item['EditorialReviews']['EditorialReview']['Content']  : '',
'text' => ( isset( $item['ItemAttributes']['Feature'] ) && !empty( $item['ItemAttributes']['Feature'] ) && is_array( $item['ItemAttributes']['Feature'] ) ) ? '<p>'.implode( '</p><p>', $item['ItemAttributes']['Feature'] ).'</p>' : '',
*/
function wpautoc_do_import_amazon_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
	// echo '<pre>';
	// print_r($product);
	// echo '</pre>';
	// return;
// 	var_dump($product['ItemAttributes']);
// var_dump($product['ItemAttributes']['Feature']);
// die();
	$itemInfo = isset( $product['ItemInfo'] ) ? $product['ItemInfo'] : array();
// var_dump($itemInfo['Features']);
	$name = wpautoc_escape_input_txt( $itemInfo['Title']['DisplayValue'] );
	$content = ( isset( $itemInfo['Features']['DisplayValues'] ) && !empty( $itemInfo['Features']['DisplayValues'] ) && is_array( $itemInfo['Features']['DisplayValues'] ) ) ? '<p>'.implode( '</p><p>', $itemInfo['Features']['DisplayValues'] ).'</p>' : '';
	/*if( empty( $content ) ) {
		if( isset( $product['EditorialReviews']['EditorialReview']['Content'] ) && !empty( isset( $product['EditorialReviews']['EditorialReview']['Content'] ) ) )
			$content = $product['EditorialReviews']['EditorialReview']['Content'];
	}*/
	// $review = ( isset( $product['EditorialReviews'] ) && !empty( $product['EditorialReviews'] ) ) ? $product['EditorialReviews']['EditorialReview']['Content']  : '';
	// if( empty( $content ) )
	// 	$content = $review;

	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }
// var_dump($settings);die();
		if( isset( $settings->spin_content ) && $settings->spin_content ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	if( isset( $settings->buy_button ) && $settings->buy_button ) {
		$url = ( isset( $product['DetailPageURL'] ) && !empty( $product['DetailPageURL'] ) ) ?  $product['DetailPageURL'] : false;
		if( $url ) {
			$button_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? $settings->buy_button_txt : 'Buy Now from Amazon';
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-amazon-btn">'.$button_txt.'</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['Images']['Primary']['Large']['URL'] ) && !empty(  $product['Images']['Primary']['Large']['URL'] ) ) {
			// $thumbnail = wpautoc_amazon_pick_thumbnail( $video['pictures']['sizes'] );
			// $thumbnail = substr( $thumbnail, 0, strpos( $thumbnail, '?' ) );
			$thumbnail =  $product['Images']['Primary']['Large']['URL'];
		}
	}
// var_dump($product['LargeImage']);
// var_dump($thumbnail);
	// $video_tags = false;
	// if( isset( $settings->video_tags ) && $settings->video_tags ) {
	// 	if( isset( $video['tags'] ) && !empty( $video['tags'] ) )
	// 	$video_tags = wpautoc_amazon_get_tags( $video['tags'] );
	// }

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_AMAZON, $thumbnail );

	return $product_id;
}

function wpautoc_amazon_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_AMAZON, $campaign_id );
}

function wpautoc_amazon_extract_id( $link ) {
	$parts = explode( '/', $link );
	if( $parts )
		return end( $parts );
	return 0;
}

/////////////////////////////////////////////

function wpautoc_extract_products_monetize( $response, $max_prods = 0 ) {
	if( !$response ) return '';
	$array_ret = array();
	if( isset( $response['Items']['Item'] ) ) {
		$i = 0;
		foreach( $response['Items']['Item'] as $item ) {
			// var_dump($item);
			// return;
			// Id es $item['ASIN']
			// var_dump($item['ItemAttributes']);
			// die();
			$array_ret[] = array(
				'name' => wpautoc_escape_input_txt( $item['ItemAttributes']['Title'] ),
				// 'price' =>  $price,
				'image' => $item['LargeImage']['URL'],
				'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? rawurlencode( $item['DetailPageURL'] ) : '',
				'review' => ( isset( $item['EditorialReviews'] ) && !empty( $item['EditorialReviews'] ) ) ? $item['EditorialReviews']['EditorialReview']['Content']  : '',
				'text' => ( isset( $item['ItemAttributes']['Feature'] ) && !empty( $item['ItemAttributes']['Feature'] ) && is_array( $item['ItemAttributes']['Feature'] ) ) ? '<p>'.implode( '</p><p>', $item['ItemAttributes']['Feature'] ).'</p>' : '',
				);
			if( $i++ > $max_prods )
				break;
		}
	}
	return $array_ret;
}


?>