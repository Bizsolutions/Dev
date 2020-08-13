<?php

define( 'WPAUTOC_GOOGLEPLUS_PER_PAGE', 20 );
define( 'WPAUTOC_GOOGLEPLUS_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_GOOGLEPLUS (7)
	Unique id: video id
*/

function wpautoc_content_type_googleplus( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'googleplusc' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Google+ posts, you need to enter your Google+ API Key first.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Google+ details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Google+' );

	// wpautoc_ifield_text( $settings, 'users', 'Users', 'wpautoc_content['.$num.'][settings][users]', false, 'User names (optional)', 'Optional; if you enter a list of usernames, only posts from these users will be imported. Usernames must be separated by comma. Ex: username1,username2,username3' );

	wpautoc_ifield_checkbox( $settings, 'text_only', 'Show Text Only', 'wpautoc_content['.$num.'][settings][text_only]', false, 'If checked, it will only display the tweet contents (not the Google+ card)' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many Google+ items will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the googleplus item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_googleplus( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$users = isset( $settings->users ) && !empty( $settings->users ) ? $settings->users : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page_token = false;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_GOOGLEPLUS_MAX_LOOPS )  ) {
		$ret = wpautoc_content_googleplus_search( $page_token, WPAUTOC_GOOGLEPLUS_PER_PAGE, $keyword, $users, $campaign_id, $settings, $num_posts, $num_items );
		// var_dump($imported);
		$imported = $ret[0];
		$page_token = $ret[1];
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		// $page++;
		$i++;
	}

	return $num_imported;
}

function wpautoc_content_googleplus_search( $page_token = false, $per_page = 50, $keyword, $users = false, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_googleplus_search( $keyword, $users, $page_token, $per_page );
		$page_token = $results[1];
		$results = $results[0];
	} catch (Exception $e) {
	    return array( 0, false );
	}
// var_dump($results);
	$items = array();
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$item_id = $result['id'];
			// var_dump($item_id);
			if( !wpautoc_googleplus_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_googleplus_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return array( $imported, $page_token );
				}

				// wpautoc_do_import_googleplus_item( $campaign_id, $result, $item_id, $settings );
				// $imported++;
				// if( $imported >= $num_posts )
					// return $imported;
			}
		}
		return array( $imported, $page_token );
		// return $imported;
	}
	else
		return array( -1, false );

}


function wpautoc_content_do_googleplus_search( $keyword,  $users = false, $page_token = false, $per_page = 60 ) {
	$googleplus_settings = wpautoc_get_settings( array( 'social', 'googleplus' ) );

	// var_dump($googleplus_settings);
	if ( ! isset( $googleplus_settings['key'] ) || empty( $googleplus_settings['key'] )
	)
		return false;

	$args = array(
		'timeout' => 30,
		'redirection' => 5
    );

	$url = "https://www.googleapis.com/plus/v1/activities?key=" . $googleplus_settings['key'] . "&language=en-US&query=" . urlencode(trim($keyword)) . "&maxResults=" . $per_page;
	if( $page_token )
		$url .= '&pageToken='.$page_token;
	$res = wp_remote_get( $url , $args );
    // var_dump(count($items));
	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	$page_token = isset( $res->nextPageToken ) ? $res->nextPageToken : false;
	// var_dump($res);
	// return;
	$items = $res->items;
    // return;
    $ret = array();
    $i = 0;
    if( $items ) {
    	foreach( $items as $item ) {
    		// if( !$i++ ) {
    			/*echo '<pre>';
    			print_r($item);
    			echo '</pre>';*/
    		// }
    		if( $item->access->description != 'Public' ) {
    			// var_dump($item->access->description);
    			// echo $item->title;
    			continue;
    		}
    		$res = array();
    		$res['id'] = $item->id;
    		$res['title'] = empty( $item->title ) ? $item->actor->displayName.' says' : $item->title;
    		$res['content'] = isset( $item->object->content ) ? $item->object->content : '' ;
    		$res['url'] = $item->url;

    		$media_url = '';
    		if (isset($item->object->attachments)) {
    		    foreach($item->object->attachments as $attach)
    		    {
		        	if(isset($attach->fullImage->url))
		        	{
		        	    $media_url   = $attach->fullImage->url;
		        	    break;
		        	}
		        	elseif(isset($attach->image->url))
		        	{
		        	    $media_url   = $attach->image->url;
		        	    break;
		        	}
    		    }
    		}
    		$res['image_url'] = $media_url;
    		$ret[] = $res;
    	}
    }
    else
    	return array( false, false );
    return array( $ret, $page_token );
}


function wpautoc_do_import_googleplus_items( $campaign_id, $items, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $items[0]['content']['title'] );
	$content = '';
	$item_ids = array();
	foreach( $items as $item ) {
		// $content .= '<h3>'.$item['content']['title'].'</h3>';
		// $content .= $item['content']['content'];
		if( isset( $settings->text_only ) && $settings->text_only )
			$content .= '<p>'.$item['content']['content'].'</p>';
		else
			$content .= '<div class="g-post" data-href="'.$item['content']['url'].'"></div>';
		// $content .= '<p>https://googleplus.com/syedbalkhi/status/441336208476868608'.'</p>';
		$item_ids[] = $item['id'];
		if( isset( $settings->add_link ) && $settings->add_link ) {
			// var_dump($product);
			$url = ( isset( $item['content']['url'] ) && !empty( $item['content']['url'] ) ) ?  $item['content']['url'] : false;
			if( $url )
				$content .= '<p><a href="'.$url.'" target="_blank" class="wpac-googleplus-btn">Read More...</a></p>';
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
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-googleplus-btn">Read More...</a></p>';
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

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_GOOGLEPLUS, $thumbnail );

	return $item_ids;
}

function wpautoc_googleplus_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_GOOGLEPLUS, $campaign_id );
}

// function wpautoc_googleplus_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>