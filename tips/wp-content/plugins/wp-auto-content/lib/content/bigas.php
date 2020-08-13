<?php

define( 'WPAUTOC_BIGAS_PER_PAGE', 10 );
define( 'WPAUTOC_BIGAS_MAX_LOOPS', 1 );

/*
	Type: WPAUTOC_CONTENT_BIGAS (7)
	Unique id: video id

	https://members.bigarticlescraper.com/api/articles_get_by_search_term?username=raulmellado@gmail.com&api_key=46785a03-2936-4941-af8c-2a105dc2a3c7&search_term=trump&count=20
*/

function wpautoc_content_type_bigas( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'bigas' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import <a href="https://wpautocontent.com/support/bigarticlescraper" target="_blank">BigArticleScraper.com</a> articles, you need a valid e-mail and API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Big Article Scraper API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in BigArticleScraper.com' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many bigas items will be shown per post' );

	/*wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );*/

	// wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a buy button at the end of the content (so you can get affiliate commissions)' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_bigas( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_BIGAS_MAX_LOOPS )  ) {
		$imported = wpautoc_content_bigas_search( $page, WPAUTOC_BIGAS_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_bigas_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_bigas_search( $keyword, $page, min( $per_page, $num_posts) , $campaign_id );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_bigas_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_bigas_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_bigas_search( $keyword,  $page = 1, $per_page = 60, $campaign_id = 0 ) {
	$bigas_settings = wpautoc_get_settings( array( 'content', 'bigas' ) );
		$username = isset( $bigas_settings['username'] ) && !empty( $bigas_settings['username'] ) ? $bigas_settings['username'] : '';
		$api_key = isset( $bigas_settings['apikey'] ) && !empty( $bigas_settings['apikey'] ) ? $bigas_settings['apikey'] : '';

		if( empty( $username ) || empty( $api_key ) )
			return false;
/*		$url = 'https://members.bigarticlescraper.com/api/articles_get_by_search_term?username='.$username.'&api_key='.$api_key.'&search_term='.urlencode( $keyword ).'&count='.$per_page;

		$ids_to_skip = wpautoc_bigas_uids( $campaign_id );
		// var_
		$args = array();
		if( !empty( $ids_to_skip ) ) {
			$url .= '&ids_to_skip='.json_encode( $ids_to_skip );
		}

		$response = wp_remote_get( $url );*/
		/*
			1. Order Articles
		*/
		$url = 'http://app.bigarticlescraper.com/api/v1.0/jobs';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
		$payload = json_encode( array( "keywords"=> array($keyword), 'article_count' => 10, 'ids_to_skip' => wpautoc_bigas_uids( $campaign_id ) ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		# Return response instead of printing.
		$result = curl_exec($ch);
		curl_close($ch);
		// var_dump($result);
		$res = json_decode( $result );
		// var_dump($res);
		if( $res && isset( $res->id ) && !empty( $res->id ) )
			wpautoc_bigas_store( $campaign_id, $res->id );
		// wpautoc_bigas_store( 1, 9800);
		/* 2. Extract and get articles */

		$job_id = wpautoc_bigas_extract( $campaign_id );
		// var_dump($job_id);
		if( !$job_id )
			return;

		$url = 'http://app.bigarticlescraper.com/api/v1.0/jobs/'.$job_id;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
		/*$payload = json_encode( array( "keywords"=> array($keyword), 'article_count' => 10, 'ids_to_skip' => wpautoc_bigas_uids() ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));*/
		# Return response instead of printing.
		$result = curl_exec($ch);
		curl_close($ch);
		// var_dump($result);
		$prods = json_decode( $result );
		// var_dump($res);exit();
		// echo($result);
		if ( $prods ) {
		  // var_dump($prods);
		  // die();
		  $products = array();
		  if( isset( $prods->articles ) && count( $prods->articles ) > 0 ) {
		  	foreach( $prods->articles as $item ) {
		  		// var_dump($item);
		  		$prod = array();
				$prod['id'] = (string) $item->uuid;
				$prod['title'] = (string) $item->title;
				$prod['content'] = (string) $item->text;
				$prod['url']  = '';
				$prod['image_url']   = false;
				$products[] = $prod;
		  	}
		  }
		}
		else
			return false;

		if( !empty( $products ) && ( $per_page < count( $products ) ) ) {
			// remove a few
			shuffle( $products );
			$products = array_slice( $products, 0, $per_page ); 
		}
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


function wpautoc_do_import_bigas_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		/*if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}*/
	}

	/*if( isset( $settings->buy_button ) && $settings->buy_button ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-bigas-btn">Buy Now from Bestbuy</a></p>';
	}*/

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	else if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_BIGAS, $thumbnail );

	return $product_id;
}

function wpautoc_bigas_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_BIGAS, $campaign_id );
}

// add_action( 'init' , 'test34');
// function test34() {
// 	wpautoc_bigas_uids( 1 );
// }

function wpautoc_bigas_store( $campaign_id, $job_id = 0 ) {
	$curr = get_option( 'wpacbigas_'.$campaign_id );
	// var_dump($curr);
	if( !empty( $curr ) && ( $curr != '' ) ) {
		if( !is_array( $curr ) )
			$curr = array( $curr );
		$curr = array_merge( $curr, array( $job_id ) );
	}
	else
		$curr = array( $job_id );
	update_option( 'wpacbigas_'.$campaign_id, $curr );
}

function wpautoc_bigas_extract( $campaign_id ) {
	$curr = get_option( 'wpacbigas_'.$campaign_id );
	// var_dump($curr);
	if( !empty( $curr ) && ( $curr != '' ) ) {
		$item = $curr[0];
		$curr = array_shift( $curr ); // extract the 1st one
	}
	else
		return false;
	update_option( 'wpacbigas_'.$campaign_id, $curr );
	return $item;
}

function wpautoc_bigas_uids( $campaign_id ) {
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
				'value' => WPAUTOC_CONTENT_BIGAS
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
// function wpautoc_bigas_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>