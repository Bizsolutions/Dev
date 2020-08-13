<?php

define( 'WPAUTOC_CRAIGSLIST_PER_PAGE', 20 );
define( 'WPAUTOC_CRAIGSLIST_MAX_LOOPS', 3 );

/*
	Type: WPAUTOC_CONTENT_CRAIGSLIST (7)
	Unique id: video id
*/

function wpautoc_content_type_craigslist( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'url', 'URL', 'wpautoc_content['.$num.'][settings][url]', false, 'Craigslist URL', 'Enter the url to check posts from, ex: https://houston.craigslist.org/search/mcy' );

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Optional: Enter one keyword to search' );

	// wpautoc_ifield_checkbox( $settings, 'text_only', 'Show Text Only', 'wpautoc_content['.$num.'][settings][text_only]', false, 'If checked, it will only display the tweet contents (not the craigslist card)' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many craigslist items will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the Craigslist item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_craigslist( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$url = isset( $settings->url ) && !empty( $settings->url ) ? $settings->url : false;
	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $url ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_CRAIGSLIST_MAX_LOOPS )  ) {
		$imported = wpautoc_content_craigslist_search( $page, WPAUTOC_CRAIGSLIST_PER_PAGE, $url, $keyword, $campaign_id, $settings, $num_posts, $num_items );
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

function wpautoc_content_craigslist_search( $page = 1, $per_page = 50, $url, $keyword = false, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_craigslist_search( $url, $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	$items = array();
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$item_id = $result['id'];
			// var_dump($item_id);
			if( !wpautoc_craigslist_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_craigslist_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

				// wpautoc_do_import_craigslist_item( $campaign_id, $result, $item_id, $settings );
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


function wpautoc_content_do_craigslist_search( $url, $keyword = false, $page = 1, $per_page = 60 ) {
	if( stristr( $url, 'http') === false || stristr($url, 'craigslist.org') === false)
		return false;
	$feed_uri = $url;
	if( stristr($feed_uri, '?') === false)
	{
	    if( $keyword )
	        $feed_uri.= '?query=' . urlencode($keyword);
	    else
	        $feed_uri.= '?query=';
	}
	else
	{
	    if($keyword !== '')
	        $feed_uri.= '&query=' . urlencode($keyword);
	}
	$feed_uri .= '&format=rss';
    $feed_uri .= '&s=1';
    // $data = wpautoc_url_get( $feed_uri );
// var_dump($feed_uri);

    $rss = fetch_feed( $feed_uri );
    $items = $rss->get_items( 0, 50 );
    $ret = array();

    if( $items ) {
	    foreach ($items as $item) {
	    	$media_url = false;
	    	$encs = $item->get_item_tags( 'http://purl.oclc.org/net/rss_2.0/enc#', 'enclosure' );
// var_dump($encs);
    	    // if ( !isset( $encs ) )
    	    //     continue;
    	    if( isset( $encs ) && $encs ) {
    	    	$enclosure = $encs[0];
    	    	// var_dump($enclosure);
    	    	$media_url = $enclosure['attribs']['']['resource'];
    	    }
	    	    // foreach ( $encs as $enclosure){

	    	    //     if ( !isset( $enclosure['attribs'] ) )
	    	    //         continue;

	    	    //     foreach ( $enclosure['attribs'] as $attr ) { 
	    	    //         echo "\n" . $attr['resource'];
	    	    //     }
	    	    // }
	    	// var_dump($item);
	    	// $enclosures = $item->get_enclosures();
	    	// var_dump($enclosures);

	    	// if ($enclosure = $item->get_enclosure())
	    	// 	{
	    	// 		echo $enclosure->get_link();
	    	// 	}
	    	// 	// var_dump($enclosure);

	    	// 	$paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_10, 'enc');
	    	// 	$rrr = wpautoc_cdata( $paths[0]['data'] );
	    	// 		var_dump($rrr);
	    	$paths = $item->get_item_tags( SIMPLEPIE_NAMESPACE_RSS_10, "title" );
	    	$title = wpautoc_before_upload( wpautoc_cdata( $paths[0]['data'] ) );

	    	$paths = $item->get_item_tags( SIMPLEPIE_NAMESPACE_RSS_10, "link" );
	    	$link = htmlspecialchars(wpautoc_cdata( $paths[0]['data'] ) );

	    	$paths = $item->get_item_tags( SIMPLEPIE_NAMESPACE_RSS_10, "description" );
	    	$description = wpautoc_before_upload(wpautoc_cdata( $paths[0]['data'] ) );

	    	$parts = explode( '/', $link );
	    	$id = end( $parts );

    		$res = array();
    		$res['id'] = $id;
    		$res['title'] = $title;
    		$res['content'] = $description;
    		// var_dump($media_url);
    		// var_dump($item->extended_entities);
    		$res['url'] = $link;
		// $content .= '<p>https://craigslist.com/syedbalkhi/status/441336208476868608'.'</p>';

    		$res['image_url'] = $media_url;
    		$ret[] = $res;
    	}
    }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_craigslist_items( $campaign_id, $items, $settings = false ) {
// echo 'llamo a import';
	$name = $items[0]['content']['title'];
	$content = '';
	$item_ids = array();
	foreach( $items as $item ) {
		if( count( $items ) > 1 )
			$content .= '<h3>'.$item['content']['title'].'</h3>';
		// $content .= $item['content']['content'];
		$content .= '<p>'.$item['content']['content'].'</p>';
		// $content .= '<p>https://craigslist.com/syedbalkhi/status/441336208476868608'.'</p>';
		$item_ids[] = $item['id'];
		if( isset( $settings->add_link ) && $settings->add_link ) {
			// var_dump($product);
			$url = ( isset( $item['content']['url'] ) && !empty( $item['content']['url'] ) ) ?  $item['content']['url'] : false;
			if( $url )
				$content .= '<p><a href="'.$url.'" target="_blank" class="wpac-craigslist-btn">Read More...</a></p>';
		}
	}
	// $content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	// if( isset( $settings->add_link ) && $settings->add_link ) {
	// 	// var_dump($product);
	// 	$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
	// 	if( $url )
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-craigslist-btn">Read More...</a></p>';
	// }

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		foreach( $items as $item ) {
			if( isset( $item['content']['image_url'] ) && !empty( $item['content']['image_url'] ) ) {
				$thumbnail = $item['content']['image_url'];
				break;
			}
		}
	}
	if( !$thumbnail && $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_CRAIGSLIST, $thumbnail );

	return $item_ids;
}

function wpautoc_craigslist_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_CRAIGSLIST, $campaign_id );
}

// function wpautoc_craigslist_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>