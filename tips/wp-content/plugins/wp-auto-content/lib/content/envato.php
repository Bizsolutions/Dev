<?php

define( 'WPAUTOC_ENVATO_PER_PAGE', 60 );
define( 'WPAUTOC_ENVATO_MAX_LOOPS', 20 );

/*
	Type: WPAUTOC_CONTENT_ENVATO (7)
	Unique id: video id
*/

function wpautoc_content_type_envato( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'envato' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Envato Products, you need a valid Envato API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Envato API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Envato' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many envato items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link to the product at the end of the post so you can get affiliate commissions', '', 'bbtnon'  );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_content['.$num.'][settings][buy_button_txt]', false, 'Text for the Buy Button', 'Ex: Buy now from Envato', '', '', 'bbtnon_row', !isset( $settings->buy_button ) );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_envato( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_ENVATO_MAX_LOOPS )  ) {
		$imported = wpautoc_content_envato_search( $page, WPAUTOC_ENVATO_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_envato_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_envato_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_envato_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_envato_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_envato_search( $keyword,  $page = 1, $per_page = 60 ) {
	$envato_settings = wpautoc_get_settings( array( 'affiliate', 'envato' ) );
	$apikey = isset( $envato_settings['apikey'] ) && !empty( $envato_settings['apikey'] ) ? $envato_settings['apikey'] : '';
	$username = isset( $envato_settings['username'] ) && !empty( $envato_settings['username'] ) ? $envato_settings['username'] : 'raulmellado';
// echo "a";

	if( empty( $apikey ) )
		return false;
// echo "b";

	$offset = ( $page - 1 ) * $per_page;

	$args = array(
		'timeout' => 30,
		'redirection' => 5,
		'headers' => array("Authorization" => "Bearer ".$apikey)
    );

	$res = wp_remote_get( 'https://api.envato.com/v1/discovery/search/search/item?term='.urlencode( $keyword ).'&page='.$page.'&pagesize='.$per_page, $args );

	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	$results = array();
	if( isset( $res->matches ) && !empty( $res->matches ) ) {
	    foreach($res->matches as $item) {
	    	// var_dump($item->image_urls);
			$res = array();
    		$res['id'] = $item->id;
	        $res['title']   = $item->name;
	        $res['content']   = empty( $item->description ) ? $item->name : $item->description;
	        if( isset( $item->previews->landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->landscape_preview->landscape_url;
	        else if( isset( $item->previews->icon_with_landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->icon_with_landscape_preview->landscape_url;
	        else
	        	$thumbnail = $item->author_image;
	        $res['image_url']  = $thumbnail;
	        $res['url']  = add_query_arg( 'ref', $username, $item->url );
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


function wpautoc_do_import_envato_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
		// if( $url )
			// $content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-envato-btn">Buy Now from Envato</a></p>';
		if( $url ) {
			$button_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? $settings->buy_button_txt : 'Buy Now from Envato';
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-envato-btn">'.$button_txt.'</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_ENVATO, $thumbnail );

	return $product_id;
}

function wpautoc_envato_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_ENVATO, $campaign_id );
}

// function wpautoc_envato_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>