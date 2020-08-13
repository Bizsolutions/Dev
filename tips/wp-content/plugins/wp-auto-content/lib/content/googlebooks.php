<?php

define( 'WPAUTOC_GOOGLEBOOKS_PER_PAGE', 40 );
define( 'WPAUTOC_GOOGLEBOOKS_MAX_LOOPS', 20 );

/*
	Type: WPAUTOC_CONTENT_GOOGLEBOOKS (7)
	Unique id: video id
*/

function wpautoc_content_type_googlebooks( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'googlebooks' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Google Books, you need a valid Google Books API Key .</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Google Books details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Google Books' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many googlebooks items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'embed', 'Embed Book in Post', 'wpautoc_content['.$num.'][settings][embed]', false, 'If checked, it will embed the book within the post' );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source at the end of the content' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_googlebooks( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_GOOGLEBOOKS_MAX_LOOPS )  ) {
		$imported = wpautoc_content_googlebooks_search( $page, WPAUTOC_GOOGLEBOOKS_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_googlebooks_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_googlebooks_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_googlebooks_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_googlebooks_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_googlebooks_search( $keyword,  $page = 1, $per_page = 60 ) {
	$googlebooks_settings = wpautoc_get_settings( array( 'content', 'googlebooks' ) );
	$apikey = isset( $googlebooks_settings['apikey'] ) && !empty( $googlebooks_settings['apikey'] ) ? $googlebooks_settings['apikey'] : '';
// echo "a";

	if( empty( $apikey ) )
		return false;
// echo "b";

	// $offset = ( $page - 1 ) * $per_page;

	$args = array(
		'timeout' => 30,
		'redirection' => 5
    );
	$page--;
	$res = wp_remote_get( 'https://www.googleapis.com/books/v1/volumes?q='.urlencode( $keyword ).'&appid='.$apikey.'&startIndex='.$page.'&maxResults='.$per_page, $args );

	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	// var_dump($res);
	$results = array();
	if( isset( $res->items ) && !empty( $res->items ) ) {
	    foreach( $res->items as $item) {
	    	// echo '<pre>';
	    	// print_r($item);
	    	// echo '</pre>';
	    	// return;
	    	// var_dump($item->image_urls);
			$res = array();
    		// $res['id'] = $item->volumeInfo->industryIdentifiers[0]->identifier;
    		$res['id'] = $item->id;
	        $res['title']   = $item->volumeInfo->title;
	        $res['content']   = empty( $item->volumeInfo->description ) ? $item->volumeInfo->title : $item->volumeInfo->description;
	        if( isset( $item->volumeInfo->imageLinks->thumbnail ) )
	        	$thumbnail = $item->volumeInfo->imageLinks->thumbnail;
	        else if( isset( $item->volumeInfo->imageLinks->smallThumbnail ) )
	        	$thumbnail = $item->volumeInfo->imageLinks->smallThumbnail;
	        else
	        	$thumbnail = false;
	        $res['image_url']  = $thumbnail;
	        $res['url']  = $item->volumeInfo->infoLink;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


function wpautoc_do_import_googlebooks_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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

		if( isset( $settings->embed ) && $settings->embed ) {
			$content .= '<p>'.wpautoc_googlebooks_embed( $product['id'] ).'</p>';
		}

		if( isset( $settings->buy_button ) && $settings->buy_button ) {
			// var_dump($product);
			$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
			if( $url )
				$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-googlebooks-btn">Buy Now from Envato</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_GOOGLEBOOKS, $thumbnail );

	return $product_id;
}

function wpautoc_googlebooks_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_GOOGLEBOOKS, $campaign_id );
}

function wpautoc_googlebooks_embed( $isbn, $width = 500, $height = 400 ) {
	return '<script type="text/javascript" src="http://books.google.com/books/previewlib.js"></script>
	       <script type="text/javascript">
	       GBS_insertEmbeddedViewer("'.$isbn.'", '.$width.','.$height.');
	       </script>';
}
// function wpautoc_googlebooks_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>