<?php

define( 'WPAUTOC_GEARBEST_PER_PAGE', 20 );
define( 'WPAUTOC_GEARBEST_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_GEARBEST (7)
	Unique id: video id
*/

function wpautoc_content_type_gearbest( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'gearbest' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Gearbest products, you need a valid Deeplink ID, App ID and App Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Gearbest Affiliate details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Gearbest' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many gearbest items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a buy button at the end of the content (so you can get affiliate commissions)' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_gearbest( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	// $num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_GEARBEST_MAX_LOOPS )  ) {
		$imported = wpautoc_content_gearbest_search( $page, WPAUTOC_GEARBEST_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_gearbest_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_gearbest_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_gearbest_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_gearbest_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_gearbest_search( $keyword,  $page = 1, $per_page = 60 ) {
	$gearbest_settings = wpautoc_get_settings( array( 'affiliate', 'gearbest' ) );
	$apikey = isset( $gearbest_settings['appid'] ) && !empty( $gearbest_settings['appid'] ) ? $gearbest_settings['appid'] : '';
	$appsecret = isset( $gearbest_settings['appsecret'] ) && !empty( $gearbest_settings['appsecret'] ) ? $gearbest_settings['appsecret'] : '';
	$deeplink = isset( $gearbest_settings['deeplink'] ) && !empty( $gearbest_settings['deeplink'] ) ? $gearbest_settings['deeplink'] : '';
// echo "a";

	if( 1 || empty( $apikey ) || empty( $appsecret ) || empty( $deeplink ) )
		return false;
// echo "b";

	$offset = ( $page - 1 ) * $per_page;
	$data = array(
		'user_api_key' => $apikey,
		'user_hash' => $hash,
		'api_version' => WPAUTOC_EPN_CLIENT_API_VERSION,
		'requests' => array( 'search_1' => array( 'query' => urlencode( $keyword ), 'action' => 'search', 'limit' => $per_page, 'offset' => $offset ) )
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
    		$res['id'] = $item->product_id;
	        $res['title']   = $item->name;
	        $res['content']   = empty( $item->description ) ? $item->name : $item->description;
	        $res['image_url']   = $item->picture;
	        $res['url']  = $item->url;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


function wpautoc_do_import_gearbest_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	if( isset( $settings->buy_button ) && $settings->buy_button ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-gearbest-btn">Buy Now from Gearbest.com</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_GEARBEST, $thumbnail );

	return $product_id;
}

function wpautoc_gearbest_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_GEARBEST, $campaign_id );
}

// function wpautoc_gearbest_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>