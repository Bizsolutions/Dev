<?php

define( 'WPAUTOC_YELP_PER_PAGE', 50 );
define( 'WPAUTOC_YELP_MAX_LOOPS', 20 );

/*
	Type: WPAUTOC_CONTENT_YELP (7)
	Unique id: video id
*/

function wpautoc_content_type_yelp( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'yelp' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Yelp reviews, you need a valid Client ID and Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Yelp details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'location', 'Location', 'wpautoc_content['.$num.'][settings][location]', false, 'Location', 'Ex: New York, USA' );

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Yelp (ex: sushi bar)' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many yelp items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link at the end of the content to the original Yelp review page' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_yelp( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$location = isset( $settings->location ) && !empty( $settings->location ) ? $settings->location : false;
	// $num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_YELP_MAX_LOOPS )  ) {
		$imported = wpautoc_content_yelp_search( $page, WPAUTOC_YELP_PER_PAGE, $keyword, $location, $campaign_id, $settings, $num_posts );
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

function wpautoc_content_yelp_search( $page = 1, $per_page = 50, $keyword, $location = false, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_yelp_search( $keyword, $location, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_yelp_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_yelp_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_yelp_search( $keyword, $location = false, $page = 1, $per_page = 60 ) {
	$yelp_settings = wpautoc_get_settings( array( 'content', 'yelp' ) );
	// var_dump($yelp_settings);
	if ( ! isset( $yelp_settings['client_id'] ) || empty( $yelp_settings['client_id'] ) || ! isset( $yelp_settings['client_secret'] ) || empty( $yelp_settings['client_secret'] ) )
	    return false;
	require_once WPAUTOC_DIR.'/lib/libs/yelp/yelp.php';

	$bearer_token = wpautoc_obtain_bearer_token( $yelp_settings['client_id'], $yelp_settings['client_secret'] );
	$result = explode('{yelpoamatic_separator}', $bearer_token);
	if(!isset($result[1]))
		return false;
	$access_token = $result[0];
	// var_dump($access_token);
	$offset = ($page-1) * $per_page;
	$res = wpautoc_yelp_search( $access_token, $keyword, $location, $per_page, $offset );
	$res = json_decode( $res );
	// var_dump($res);
	// return;
	// wpautoc_yelp_search( $bearer_token, $term, $location );
	// var_dump($res);return;
	$results = array();
	if( isset( $res->businesses ) && !empty( $res->businesses ) ) {
	    foreach($res->businesses as $item) {
	    	// var_dump($item->categories);
	    	$cat_str = '';
	    	if( $item->categories ) {
	    		foreach( $item->categories as $num => $category ) {
	    			if( $num++ )
	    			$cat_str .= ', ';
	    			$cat_str .= $category->title;
	    		}
	    	}
	    	$content = '';
	    	$content .= '<p>Categories: '.$cat_str.'</p>';
	    	$content .= '<p>Rating: '.$item->rating.'</p>';
	    	$content .= '<p>Location: '.implode( '<br/>', $item->location->display_address ).'</p>';
	    	$content .= '<p>Phone: '.$item->display_phone.'</p><br/>';
	    	$content .= '<iframe width="100%" height="450" frameborder="0" style="border:0" src = "https://maps.google.com/maps?q='.$item->coordinates->latitude.','.$item->coordinates->longitude.'&hl=en;z=14&amp;output=embed"></iframe>';
	    	// var_dump($content);
	    	// return;
			$res = array();
    		$res['id'] = $item->id;
	        $res['title']   = $item->name;
	        $res['content']   = $content;
	        $res['image_url']  = $item->image_url;
	        $res['url']  = $item->url;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


function wpautoc_do_import_yelp_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = $product['content'];
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
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-yelp-btn">More Info</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_YELP, $thumbnail );

	return $product_id;
}

function wpautoc_yelp_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_YELP, $campaign_id );
}

// function wpautoc_yelp_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>