<?php

define( 'WPAUTOC_NASA_PER_PAGE', 100 );
define( 'WPAUTOC_NASA_MAX_LOOPS', 20 );

/*
	Type: WPAUTOC_CONTENT_NASA (7)
	Unique id: video id
*/

function wpautoc_content_type_nasa( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in the NASA archives' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many nasa items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link at the end of the content to the original course' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_nasa( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_NASA_MAX_LOOPS )  ) {
		$imported = wpautoc_content_nasa_search( $page, WPAUTOC_NASA_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_nasa_search( $page = 1, $per_page = 50, $keyword,  $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_nasa_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_nasa_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_nasa_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_nasa_search( $keyword,  $page = 1, $per_page = 60 ) {
	$url = 'https://images-api.nasa.gov/search?q='.urlencode($keyword).'&page='.$page;
	$res = wpautoc_url_get( $url );
	$res = json_decode( $res );
	// var_dump($res);
	// return;
	// wpautoc_nasa_search( $bearer_token, $term, $location );
	// var_dump($res);return;
	$results = array();
	if( isset( $res->collection->items ) && !empty( $res->collection->items ) ) {
	    foreach($res->collection->items as $item) {
	    	// var_dump($item);
			$res = array();
    		$res['id'] = $item->data[0]->nasa_id;
	        $res['title']   = $item->data[0]->title;
	        $res['content']   = nl2br($item->data[0]->description);
	        $res['image_url']  = $item->links[0]->href;
	        $res['url']  = $item->links[0]->href;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;

}


function wpautoc_do_import_nasa_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-nasa-btn">More Info</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	if( !$thumbnail && $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_NASA, $thumbnail );

	return $product_id;
}

function wpautoc_nasa_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_NASA, $campaign_id );
}

// function wpautoc_nasa_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>