<?php

define( 'WPAUTOC_TUMBLR_PER_PAGE', 60 );
define( 'WPAUTOC_TUMBLR_MAX_LOOPS', 20 );

/*
	Type: WPAUTOC_CONTENT_TUMBLR (7)
	Unique id: video id
*/

function wpautoc_content_type_tumblr( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'tumblr' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Tumblr posts, you need a valid Tumblr Consumer Key and Oauth Tokens.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Tumblr details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'blogs', 'Blog Id', 'wpautoc_content['.$num.'][settings][blogs]', false, 'The Tumblr blog id', 'Enter The Tumblr blog id to import posts from https://blogid.tumblr.com (only the blogid) part' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many tumblr items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Content', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the content' );


	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source at the end of the content' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_tumblr( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$blogs = isset( $settings->blogs ) && !empty( $settings->blogs ) ? $settings->blogs : false;
	// $num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $blogs ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_TUMBLR_MAX_LOOPS )  ) {
		$imported = wpautoc_content_tumblr_search( $page, WPAUTOC_TUMBLR_PER_PAGE, $blogs,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_tumblr_search( $page = 1, $per_page = 50, $blogs, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_tumblr_search( $blogs, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_tumblr_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_tumblr_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_tumblr_search( $blog,  $page = 1, $per_page = 60 ) {

	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Util.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Request.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Token.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Consumer.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/SignatureMethod.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/HmacSha1.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/RequestException.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/RequestHandler.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/Client.php';
	$tumblr_settings = wpautoc_get_settings( array( 'social', 'tumblr' ) );

	if ( ! isset( $tumblr_settings['key'] ) || empty( $tumblr_settings['key'] ) || ! isset( $tumblr_settings['secret'] ) || empty( $tumblr_settings['secret'] ) ||
	! isset( $tumblr_settings['oauth_token'] ) || empty( $tumblr_settings['oauth_token'] ) || ! isset( $tumblr_settings['oauth_secret'] ) || empty( $tumblr_settings['oauth_secret'] )
	 )
		return false;

	$client = new Tumblr\API\Client( $tumblr_settings['key'], $tumblr_settings['secret'], $tumblr_settings['oauth_token'], $tumblr_settings['oauth_secret'] );

	$posts = $client->getBlogPosts( $blog );
	// var_dump($posts);
	$posts = $posts->posts;
	// var_dump($posts);return;
	$results = array();
	if( isset( $posts ) && !empty( $posts ) ) {
	    foreach($posts as $item) {
	    	// var_dump($item->image_urls);
			$res = array();
    		$res['id'] = $item->id;
	        $res['title']   = $item->title;
	        $res['content']   = empty( $item->description ) ? $item->title : $item->description;
	        $res['image_url']  = '';
	        $res['url']  = $item->post_url;
			// $res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;
}


function wpautoc_do_import_tumblr_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-tumblr-btn">View Original Article in Tumblr.com</a></p>';
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	else if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_TUMBLR, $thumbnail );

	return $product_id;
}

function wpautoc_tumblr_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_TUMBLR, $campaign_id );
}

// function wpautoc_tumblr_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>