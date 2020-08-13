<?php

define( 'WPAUTOC_WHIO_PER_PAGE', 100 );
define( 'WPAUTOC_WHIO_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_WHIO (7)
	Unique id: video id
*/

function wpautoc_content_type_whio( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'whio' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Webhose.io news items, you need a valid API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Webhose.io details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Wikipedia' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many whio items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the whio item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_whio( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_WHIO_MAX_LOOPS )  ) {
		$imported = wpautoc_content_whio_search( $page, WPAUTOC_WHIO_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts, $num_items );
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

function wpautoc_content_whio_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_whio_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	$items = array();
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$item_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_whio_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_whio_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

				// wpautoc_do_import_whio_item( $campaign_id, $result, $item_id, $settings );
				// $imported++;
				// if( $imported >= $num_posts )
					// return $imported;
			}
		}
		return $imported;
	}
	else
		return -1;

}


function wpautoc_content_do_whio_search( $keyword,  $page = 1, $per_page = 60 ) {
	$end = $per_page;
    $whio_settings = wpautoc_get_settings( array( 'content', 'whio' ) );
    $apikey = isset( $whio_settings['apikey'] ) && !empty( $whio_settings['apikey'] ) ? $whio_settings['apikey'] : false;
    if( !$apikey )
    	return array();
    /*http://webhose.io/filterWebContent?token=64a81750-58cb-42a7-a93f-f32a4edf29e0&format=json&sort=crawled&q=%22donald%20trump%22%20language%3Aenglish'*/

    $url = ' http://webhose.io/filterWebContent?q='.trim( urlencode( $keyword ) ).'&token='.trim( $apikey ).'&format=json&sort=relevancy&language=english&from='.$page;
    // echo $url;
    $data = wpautoc_remote_get( $url );
    // var_dump($data);
    $items = json_decode( $data );
    // var_dump($items);
    // return;
    $items = $items->posts;
    // var_dump($data);
    // return;
    $ret = array();
    if( $items ) {
    	foreach( $items as $item ) {
    		// echo '<pre>';
    		// print_r($item);
    		// echo '</pre>';
    		// return;
    		$res = array();
    		$res['id'] = $item->uuid;
    		$res['title'] = $item->title;
    		// $text = '<p>' . preg_replace( array( '/(\r?\n){2,}/', '/\r?\n/' ), array( '</p><p>', '<br />' ), $item->text ) . '</p>';  
    		$text = wpautop( $item->text, false );
    		$res['content'] = wpautop( $text );
    		$res['url'] = $item->url;
    		$res['image_url'] = isset( $item->thread->main_image ) ? $item->thread->main_image : false;
    		$ret[] = $res;
    	}
    }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_whio_items( $campaign_id, $items, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $items[0]['content']['title'] );
	$content = '';
	$item_ids = array();
	foreach( $items as $item ) {
		$content .= '<h3>'.$item['content']['title'].'</h3>';
		$content .= $item['content']['content'];
		$item_ids[] = $item['id'];
		if( isset( $settings->add_link ) && $settings->add_link ) {
			// var_dump($product);
			$url = ( isset( $item['content']['url'] ) && !empty( $item['content']['url'] ) ) ?  $item['content']['url'] : false;
			if( $url )
				$content .= '<p><a href="'.$url.'" target="_blank" class="wpac-whio-btn">Read More...</a></p>';
		}
	}
	// $content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		if( isset( $settings->spin_content ) && $settings->spin_content ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	// if( isset( $settings->add_link ) && $settings->add_link ) {
	// 	// var_dump($product);
	// 	$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
	// 	if( $url )
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-whio-btn">Read More...</a></p>';
	// }

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	foreach( $items as $item ) {
		if( isset( $item['content']['image_url'] ) && !empty( $item['content']['image_url'] ) ) {
			$thumbnail = $item['content']['image_url'];
			break;
		}
	}
	if( !$thumbnail && $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_WHIO, $thumbnail );

	return $item_ids;
}

function wpautoc_whio_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_WHIO, $campaign_id );
}

// function wpautoc_whio_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>