<?php

define( 'WPAUTOC_ETSY_PER_PAGE', 20 );
define( 'WPAUTOC_ETSY_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_ETSY (7)
	Unique id: video id
*/

function wpautoc_content_type_etsy( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'etsy' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Etsy products, you need a valid Etsy API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Etsy API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Etsy' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many etsy items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description of the article  <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the Etsy item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_etsy( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_ETSY_MAX_LOOPS )  ) {
		$imported = wpautoc_content_etsy_search( $page, WPAUTOC_ETSY_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_etsy_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_etsy_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_etsy_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_etsy_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_etsy_search( $keyword,  $page = 1, $per_page = 60 ) {
	$end = $per_page;
    $etsy_settings = wpautoc_get_settings( array( 'content', 'etsy' ) );
    $apikey = isset( $etsy_settings['apikey'] ) && !empty( $etsy_settings['apikey'] ) ? $etsy_settings['apikey'] : false;
    if( !$apikey )
    	return array();
    $url = 'https://openapi.etsy.com/v2/listings/active.js?keywords='.trim( urlencode( $keyword ) ).'&limit=1&includes=Images:1&api_key='.trim( $apikey );
    // $url = 'https://etsyapi.org/v2/everything?q='.trim( urlencode( $keyword ) ).'&apiKey='.trim( $apikey ).'&language=en&page='.$page;
    $data = wpautoc_remote_get( $url );
    $data = substr( $data, 5);
    $data = substr( $data, 0, -2);
    // var_dump($data);
    $items = json_decode( $data );
    // var_dump($items);
    // return;
    $items = $items->results;
    // var_dump($items);
    // var_dump($data);
    // return;
    $ret = array();
    if( $items ) {
    	foreach( $items as $item ) {
    		// var_dump($item);
    		$res = array();
    		$res['id'] = $item->listing_id;
    		$res['title'] = $item->title;
    		$res['content'] = $item->description;
    		$res['url'] = $item->url;
    		$res['image_url'] = $item->Images[0]->url_570xN;
    		$ret[] = $res;
    	}
    	// return;
    }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_etsy_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = /*wpautoc_escape_input_txt(*/ $product['title'] /*)*/;
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

	if( isset( $settings->add_link ) && $settings->add_link ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-etsy-btn">Read More...</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_ETSY, $thumbnail );

	return $product_id;
}

function wpautoc_etsy_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_ETSY, $campaign_id );
}

// function wpautoc_etsy_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>