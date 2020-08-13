<?php

define( 'WPAUTOC_BIGCS_PER_PAGE', 20 );
define( 'WPAUTOC_BIGCS_MAX_LOOPS', 1 );

/*
	Type: WPAUTOC_CONTENT_BIGCS (7)
	Unique id: video id

	https://members.bigcontentsearch.com/api/articles_get_by_search_term?username=raulmellado@gmail.com&api_key=46785a03-2936-4941-af8c-2a105dc2a3c7&search_term=trump&count=20
*/

function wpautoc_content_type_bigcs( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'bigcs' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import <a href="https://wpautocontent.com/support/bigcontentsearch" target="_blank">BigContentSearch.com</a> articles, you need a valid e-mail and API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Big Content Search API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in BigContentSearch.com' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many bigcs items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a buy button at the end of the content (so you can get affiliate commissions)' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_bigcs( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_BIGCS_MAX_LOOPS )  ) {
		$imported = wpautoc_content_bigcs_search( $page, WPAUTOC_BIGCS_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_bigcs_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_bigcs_search( $keyword, $page, min( $per_page, $num_posts) , $campaign_id );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_bigcs_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_bigcs_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_bigcs_search( $keyword,  $page = 1, $per_page = 60, $campaign_id = 0 ) {
	$bigcs_settings = wpautoc_get_settings( array( 'content', 'bigcs' ) );
		$username = isset( $bigcs_settings['username'] ) && !empty( $bigcs_settings['username'] ) ? $bigcs_settings['username'] : '';
		$api_key = isset( $bigcs_settings['apikey'] ) && !empty( $bigcs_settings['apikey'] ) ? $bigcs_settings['apikey'] : '';

		if( empty( $username ) || empty( $api_key ) )
			return false;
		$url = 'https://members.bigcontentsearch.com/api/articles_get_by_search_term?username='.$username.'&api_key='.$api_key.'&search_term='.urlencode( $keyword ).'&count='.$per_page;
		// $url = 'https://members.bigcontentsearch.com/api/articles_get_by_search_term';
		// rmi353, $prods->response  (ojo a count = 1 )
		$ids_to_skip = wpautocs_bigcs_uids( $campaign_id );
		// var_
		$args = array();
		if( !empty( $ids_to_skip ) ) {
			/*$body1 = array( 'ids_to_skip ' =>  $ids_to_skip );
			$body1 = json_encode( $body1 );
			$args = array(
			    'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
			    'body'      => $body1 ),
			    'method'    => 'POST'
			);*/
			$url .= '&ids_to_skip='.json_encode( $ids_to_skip );
		}
// echo $url.'<br/>';
// var_dump($args);
		$response = wp_remote_get( $url/*, $args*/ );
		if ( is_array( $response ) ) {
		  $header = $response['headers']; // array of http header lines
		  $body = $response['body']; // use the content
		  $prods = json_decode( $body );
		  // var_dump($prods);
		  // die();
		  $products = array();
		  if( ( !$prods->status_code) && count( $prods->response ) > 0 ) {
		  	foreach( $prods->response as $item ) {
		  		// var_dump($item);
		  		$prod = array();
				$prod['id'] = (string) $item->uid;
				$prod['title'] = (string) $item->title;
				$prod['content'] = (string) $item->text;
				$prod['url']  = '';
				// rmi353, probablemente es productTrackingUrl
				$prod['image_url']   = false;
				// $prod['price'] = (string) $item->salePrice;
				$products[] = $prod;
		  	}
		  }
		}
		else
			return false;

		return $products;


	// $results = array();
	// if( isset( $res->results->search_1->offers ) && !empty( $res->results->search_1->offers ) ) {
	//     foreach($res->results->search_1->offers as $item) {
	//     	// var_dump($item);
	// 		$res = array();
 //    		$res['id'] = $item->product_id;
	//         $res['title']   = $item->name;
	//         $res['content']   = empty( $item->description ) ? $item->name : $item->description;
	//         $res['image_url']   = $item->picture;
	//         $res['url']  = $item->url;
	// 		// $res['price'] = $item->sale_price;
	// 		$results[] = $res;
	// 	}
	// }
	// else
	// 	return false;
	// return $results;

}


function wpautoc_do_import_bigcs_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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

	/*if( isset( $settings->buy_button ) && $settings->buy_button ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-bigcs-btn">Buy Now from Bestbuy</a></p>';
	}*/

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	else if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_BIGCS, $thumbnail );

	return $product_id;
}

function wpautoc_bigcs_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_BIGCS, $campaign_id );
}

// add_action( 'init' , 'test34');
// function test34() {
// 	wpautocs_bigcs_uids( 1 );
// }

function wpautocs_bigcs_uids( $campaign_id ) {
	$rd_args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			/*array(
				'key' => '_wpac_cntid',
				'value' => $content_id
			),*/
			array(
				'key' => '_wpac_cnttype',
				'value' => WPAUTOC_CONTENT_BIGCS
			),
			array(
				'key' => '_wpac_cid',
				'value' => $campaign_id
			),
		)
	);

	$uids = array();
	$posts = get_posts( $rd_args );
	if( $posts ) {
		foreach( $posts as $post ) {
			$uid = get_post_meta( $post->ID, '_wpac_cntid', true );
			if( !empty( $uid ) )
				$uids[] = $uid;
		}
	}
	// var_dump($uids);
	return $uids;
	// $rd_query = new WP_Query( $rd_args );
	// var_dump( $rd_query );
	// return $rd_query->found_posts ? 0 : 1 ;
}
// function wpautoc_bigcs_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>