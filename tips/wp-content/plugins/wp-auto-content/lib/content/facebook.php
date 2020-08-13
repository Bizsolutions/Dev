<?php

define( 'WPAUTOC_FACEBOOK_PER_PAGE', 100 );
define( 'WPAUTOC_FACEBOOK_MAX_LOOPS', 2 );

/*
	Type: WPAUTOC_CONTENT_FACEBOOK (7)
	Unique id: video id
*/

function wpautoc_content_type_facebook( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'facebook' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Facebook Posts, you need a valid App ID and Secret and Authenticate your app.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings, enter your Facebook details and authenticate.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'page_name', 'Page/Group id or url', 'wpautoc_content['.$num.'][settings][page_name]', false, 'Page/Group URL or ID', 'Enter The Page/Group ID or URL to import posts from it' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many facebook items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Content', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the content' );


	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source at the end of the content' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_facebook( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$page_name = isset( $settings->page_name ) && !empty( $settings->page_name ) ? $settings->page_name : false;
	// $num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $page_name ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_FACEBOOK_MAX_LOOPS )  ) {
		$imported = wpautoc_content_facebook_search( $page_name, WPAUTOC_FACEBOOK_PER_PAGE, $page,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_facebook_search( $page_name = 1, $per_page = 50, $page, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_facebook_search( $page_name, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_facebook_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_facebook_product( $campaign_id, $result, $product_id, $settings );
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

function wpautoc_get_fb_token() {
	$facebook_settings = wpautoc_get_settings( array( 'social', 'facebook' ) );
	if ( ! isset( $facebook_settings['app_id'] ) || empty( $facebook_settings['app_id'] ) || ! isset( $facebook_settings['app_secret'] ) || empty( $facebook_settings['app_secret'] )/* || ! isset( $facebook_settings['token'] ) || empty( $facebook_settings['token'] )*/ )
		return false;
	$url = $url = "https://graph.facebook.com/oauth/access_token?client_id=" . $facebook_settings['app_id'] . "&client_secret=" . $facebook_settings['app_secret'] . "&grant_type=client_credentials";
	$content = wpautoc_url_get( $url );
	$access_token = false;
	if (stristr($content, 'access_token=') !== FALSE) {
	    $expl         = explode('=', $content);
	    $access_token = $expl[1];
	}
	elseif (stristr($content, '"access_token":"') !== FALSE) {
	    $expl         = explode('"access_token":"', $content);
	    $access_token = $expl[1];
	    $access_token = substr($access_token, 0, strpos($access_token, '"'));
	}
	return $access_token;
}

function wpautoc_getFacebookId( $url, $access_token )
{
    $id   = substr(strrchr(trim($url, '/'), '/'), 1);
    $json = @file_get_contents('https://graph.facebook.com/' . $id . '?access_token=' . $access_token);
    if ($json == FALSE) {
        return FALSE;
    }
    $json = json_decode($json);
    if ($json == FALSE || !isset($json->id)) {
        return FALSE;
    }
    return $json->id;
}

function wpautoc_content_do_facebook_search( $page_name,  $page = 1, $per_page = 60 ) {

	require_once WPAUTOC_DIR . 'lib/libs/facebook/Facebook/autoload.php';
	$facebook_settings = wpautoc_get_settings( array( 'social', 'facebook' ) );
// var_dump($facebook_settings);
	// var_dump($settings);
	if ( ! isset( $facebook_settings['app_id'] ) || empty( $facebook_settings['app_id'] ) || ! isset( $facebook_settings['app_secret'] ) || empty( $facebook_settings['app_secret'] )/* || ! isset( $facebook_settings['token'] ) || empty( $facebook_settings['token'] )*/ )
		return false;

	if( ! isset( $facebook_settings['token'] ) || empty( $facebook_settings['token'] ) ) {
		$access_token = wpautoc_get_fb_token();
		// rmi353, store it!
	}

	$pos = strpos( $page_name, 'http' );
	if( $pos === 0 ) {
		$parts = explode( '/', $page_name );
		$page_name = end( $parts );
		// var_dump($parts);
		// var_dump($page_name);
		// $page_name = substr( $page_name, 0, $pos );
	}
// https://graph.facebook.com/oauth/access_token?client_id=198104187344130&client_secret=ff85fbaef0bcf0fb64fbc039c0035f59&grant_type=client_credentials

	// {"access_token":"198104187344130|NfF6_qwDzBNS2e5qUqYpwN9xkGk","token_type":"bearer"}
// id de casi noticias es 1662038100490471

	$url = 'https://graph.facebook.com/v2.10/'.$page_name.'/feed?access_token='.$access_token.'&fields=full_picture,picture,id,message,name,caption,description,updated_time,link,icon,from,privacy,type,status_type,application,object_id,story,story_tags,actions,attachments,created_time&limit='.$per_page;
	$content = wpautoc_url_get( $url );
	// var_dump($url);
	$posts = json_decode( $content );
	$posts = isset( $posts->data ) ? $posts->data : false;
	// var_dump($posts);
	// return;
	// https://graph.facebook.com/v2.10/1662038100490471/feed?access_token=198104187344130|NfF6_qwDzBNS2e5qUqYpwN9xkGk&fields=full_picture,picture,id,message,name,caption,description,updated_time,link,icon,from,privacy,type,status_type,application,object_id,story,story_tags,actions,attachments,created_time
	// var_dump($posts);return;
	$results = array();
	if( isset( $posts ) && !empty( $posts ) ) {
	    foreach($posts as $item) {
	    	// var_dump($item);
			$res = array();
    		$res['id'] = $item->id;
	        $res['title']   = empty( $item->name ) ? $item->message : $item->name;
	        $res['content']   = empty( $item->message ) ? $item->name : $item->message;
	        $query_str = parse_url( $item->full_picture, PHP_URL_QUERY );
	        parse_str( $query_str, $query_params );
	        if( isset( $query_params['url'] ) && !empty( $query_params['url']) )
	        	$res['image_url']  = $query_params['url'];
	        else {
	        	$res['image_url'] = $item->full_picture;
	        }
	        if( isset( $item->actions[0]->link ) )
	        	$res['url']  = $item->actions[0]->link;
	        else
	        	$res['url']  = $item->link;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;
}


function wpautoc_do_import_facebook_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = $product['content'];
// var_dump($content);
	if( $settings ) {
		if( isset( $settings->remove_links ) && $settings->remove_links ) {
			$content = wpautoc_remove_links( $content );
		}

		if( isset( $settings->spin_content ) && $settings->spin_content ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	if( isset( $settings->add_link ) && $settings->add_link ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-facebook-btn">View Original Post in Facebook.com</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_FACEBOOK, $thumbnail );

	return $product_id;
}

function wpautoc_facebook_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_FACEBOOK, $campaign_id );
}

// function wpautoc_facebook_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>