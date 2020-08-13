<?php

define( 'WPAUTOC_TWITTER_PER_PAGE', 20 );
define( 'WPAUTOC_TWITTER_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_TWITTER (7)
	Unique id: video id
*/

function wpautoc_content_type_twitter( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'twitter' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Tweets, you need a valid Consumer Key and Oauth Token.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Twitter details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Twitter' );

	wpautoc_ifield_text( $settings, 'users', 'Users', 'wpautoc_content['.$num.'][settings][users]', false, 'User names (optional)', 'Optional; if you enter a list of usernames, only posts from these users will be imported. Usernames must be separated by comma. Ex: username1,username2,username3' );

	wpautoc_ifield_checkbox( $settings, 'text_only', 'Show Text Only', 'wpautoc_content['.$num.'][settings][text_only]', false, 'If checked, it will only display the tweet contents (not the twitter card)' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many tweets will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the twitter item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_twitter( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$users = isset( $settings->users ) && !empty( $settings->users ) ? $settings->users : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_TWITTER_MAX_LOOPS )  ) {
		$imported = wpautoc_content_twitter_search( $page, WPAUTOC_TWITTER_PER_PAGE, $keyword, $users, $campaign_id, $settings, $num_posts, $num_items );
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

function wpautoc_content_twitter_search( $page = 1, $per_page = 50, $keyword, $users = false, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_twitter_search( $keyword, $users, $page, $per_page );
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
			if( !wpautoc_twitter_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_twitter_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

				// wpautoc_do_import_twitter_item( $campaign_id, $result, $item_id, $settings );
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


function wpautoc_content_do_twitter_search( $keyword,  $users = false, $page = 1, $per_page = 60 ) {
		require_once WPAUTOC_DIR.'/lib/libs/twitter/TwitterAPIExchange.php';
		$twitter_settings = wpautoc_get_settings( array( 'social', 'twitter' ) );

		// var_dump($twitter_settings);
		if ( ! isset( $twitter_settings['key'] ) || empty( $twitter_settings['key'] )
			|| ! isset( $twitter_settings['secret'] ) || empty( $twitter_settings['secret'] )
			|| ! isset( $twitter_settings['oauth_token'] ) || empty( $twitter_settings['oauth_token'] )
			|| ! isset( $twitter_settings['oauth_secret'] ) || empty( $twitter_settings['oauth_secret'] )
	)
			return false;

		$settings = array(
		    'consumer_key' => $twitter_settings['key'],
		    'consumer_secret' => $twitter_settings['secret'],
		    'oauth_access_token' => $twitter_settings['oauth_token'],
		    'oauth_access_token_secret' => $twitter_settings['oauth_secret'],
		);

		$query = '?lang=en&tweet_mode=extended&count=100'.'&q='.$keyword;
		if( !empty( $users ) ) {
			$query .= '+from:'.str_replace( ',', '+OR+from:', $users );
		}
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange( $settings );
		$code = $twitter->setGetfield( $query )
		->buildOauth( $url, $requestMethod )
		             ->performRequest();
		             // var_dump($code);
		// return;
     $items = json_decode( $code );
     $items = $items->statuses;
    // var_dump(count($items));
    // var_dump($items);
    // return;
    $ret = array();
    if( $items ) {
    	foreach( $items as $item ) {
    		// var_dump($item);
    		$res = array();
    		$title = $item->full_text;
    		// var_dump($title);
    		$pos = strpos( $title, ': ');
    		if( $pos !== false )
    			$title = substr( $title, $pos+2 );
    		$pos = strpos( $title, ' http');
    		if( $pos != false )
    			$title = substr( $title, 0, $pos );
    		// var_dump($title);
    		$res['id'] = $item->id_str;
    		$res['title'] = $title;
    		$res['content'] = $item->full_text;
    		$media_url = $item->user->profile_image_url_https;
    		if (isset($item->extended_entities->media)) {
    		    foreach ($item->extended_entities->media as $media) {
    		        $media_url = $media->media_url_https;
    		        break;
    		    }
    		}
    		// var_dump($media_url);
    		// var_dump($item->extended_entities);
    		$res['url'] = 'https://twitter.com/'.$item->user->screen_name.'/status/'.$item->id_str;
		// $content .= '<p>https://twitter.com/syedbalkhi/status/441336208476868608'.'</p>';

    		$res['image_url'] = $media_url;
    		$ret[] = $res;
    	}
    }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_twitter_items( $campaign_id, $items, $settings = false ) {
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
			$content .= '<p>'.$item['content']['url'].'</p>';
		// $content .= '<p>https://twitter.com/syedbalkhi/status/441336208476868608'.'</p>';
		$item_ids[] = $item['id'];
		if( isset( $settings->add_link ) && $settings->add_link ) {
			// var_dump($product);
			$url = ( isset( $item['content']['url'] ) && !empty( $item['content']['url'] ) ) ?  $item['content']['url'] : false;
			if( $url )
				$content .= '<p><a href="'.$url.'" target="_blank" class="wpac-twitter-btn">Read More...</a></p>';
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
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-twitter-btn">Read More...</a></p>';
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

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_TWITTER, $thumbnail );

	return $item_ids;
}

function wpautoc_twitter_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_TWITTER, $campaign_id );
}

// function wpautoc_twitter_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>