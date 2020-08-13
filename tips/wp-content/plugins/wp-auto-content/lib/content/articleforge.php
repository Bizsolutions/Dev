<?php

define( 'WPAUTOC_AFORGE_PER_PAGE', 10 );
define( 'WPAUTOC_AFORGE_MAX_LOOPS', 1 );

/*
	Type: WPAUTOC_CONTENT_AFORGE (7)
	Unique id: video id

	https://members.bigarticlescraper.com/api/articles_get_by_search_term?username=raulmellado@gmail.com&api_key=46785a03-2936-4941-af8c-2a105dc2a3c7&search_term=trump&count=20
*/

function wpautoc_content_type_aforge( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'aforge' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import <a href="https://wpautocontent.com/support/articleforge" target="_blank">ArticleForge.com</a> articles, you need a valid e-mail and API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your ArticleForge details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in BigArticleScraper.com' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many aforge items will be shown per post' );

	/*wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );*/

	// wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a buy button at the end of the content (so you can get affiliate commissions)' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_aforge( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_AFORGE_MAX_LOOPS )  ) {
		$imported = wpautoc_content_aforge_search( $page, WPAUTOC_AFORGE_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_aforge_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_aforge_search( $keyword, $page, min( $per_page, $num_posts) , $campaign_id );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_aforge_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_aforge_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_aforge_search( $keyword,  $page = 1, $per_page = 60, $campaign_id = 0 ) {
	$aforge_settings = wpautoc_get_settings( array( 'content', 'aforge' ) );
		$username = isset( $aforge_settings['username'] ) && !empty( $aforge_settings['username'] ) ? $aforge_settings['username'] : '';
		$api_key = isset( $aforge_settings['apikey'] ) && !empty( $aforge_settings['apikey'] ) ? $aforge_settings['apikey'] : '';

		if( empty( $username ) || empty( $api_key ) )
			return false;
/*		$url = 'https://members.bigarticlescraper.com/api/articles_get_by_search_term?username='.$username.'&api_key='.$api_key.'&search_term='.urlencode( $keyword ).'&count='.$per_page;

		$ids_to_skip = wpautoc_aforge_uids( $campaign_id );
		// var_
		$args = array();
		if( !empty( $ids_to_skip ) ) {
			$url .= '&ids_to_skip='.json_encode( $ids_to_skip );
		}

		$response = wp_remote_get( $url );*/
		/*
			1. Order Articles
		*/
		$initiate = min( $per_page, 10 );
		$url = 'https://af.articleforge.com/api/initiate_article';
		$ids = array();
		if( 1 ) {
			for( $i = 0; $i < $initiate; $i++ ) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				// curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
				$payload = array( "keyword"=> $keyword, "key"=> $api_key, 'title' => 1, 'image' => 0.6, 'video' => 0.6  );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
				# Return response instead of printing.
				$result = curl_exec($ch);
				curl_close($ch);
				// var_dump($result);
				$res = json_decode( $result );
				// var_dump($res);
				if( $res && isset( $res->status ) && ( $res->status == 'Success' ) )
					$ids[] = $res->ref_key;
				else if( $res && isset( $res->status ) && ( $res->status == 'Fail' ) ) {
					$ids = false;
					break;
				}
			}
		}
		// die();
		//var_dump($ids);
		if( !empty( $ids ) ) {
			wpautoc_aforge_store( $campaign_id, $ids );
			return false;
		}
		// die();
		/*if( $res && isset( $res->id ) && !empty( $res->id ) )
			wpautoc_aforge_store( $res->id );
		wpautoc_aforge_store( 1, 9800);*/
		/* 2. Extract and get articles */

		$job_ids = wpautoc_aforge_extract( $campaign_id, $initiate );
		//$job_ids = array( /*39826172, 39826195*/ 39835050, 39835073 );
		// var_dump($job_ids);
		if( !$job_ids )
			return;
		// exit();
		// var_dump($res);exit();
		// echo($result);
		// if ( $prods ) {
		  // var_dump($prods);
		  // die();
		  $products = array();
		  // if( isset( $prods->articles ) && count( $prods->articles ) > 0 ) {
		  	// foreach( $prods->articles as $item ) {
		  		// var_dump($item);
		  for( $i = 0; $i < count( $job_ids ); $i++ ) {
		  		$item = wpautoc_aforge_get_article( $job_ids[$i], $api_key );
		  		$pos = strpos( $item, '</h1>' );
		  		if( $pos !== false ) {
		  			$title = trim( substr( $item, 5, $pos-5 ) );
		  			$content = trim( substr( $item, $pos+6 ) );
		  		}
		  		else {
		  			$title = $keyword;
		  			$content = $item;
		  		}
		  		// var_dump($title);
		  		// var_dump($content);
		  		// exit();
		  		$prod = array();
				$prod['id'] = $job_ids[$i];
				$prod['title'] = $title;
				$prod['content'] = $content;
				$prod['url']  = '';
				$prod['image_url']   = false;
				$products[] = $prod;
			}
		  	// }
		  // }
		// }
		// else
			// return false;

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


function wpautoc_do_import_aforge_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-aforge-btn">Buy Now from Bestbuy</a></p>';
	}*/

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	else if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_AFORGE, $thumbnail );

	return $product_id;
}

function wpautoc_aforge_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_AFORGE, $campaign_id );
}

// add_action( 'init' , 'test34');
// function test34() {
// 	wpautoc_aforge_uids( 1 );
// }

function wpautoc_aforge_store( $campaign_id, $job_ids ) {
	$curr = get_option( 'wpacaforge_'.$campaign_id );
	// var_dump($curr);
	if( !empty( $curr ) && ( $curr != '' ) ) {
		$curr = array_merge( $curr, $job_ids );
	}
	else
		$curr = $job_ids;
	update_option( 'wpacaforge_'.$campaign_id, $curr );
}

function wpautoc_aforge_extract( $campaign_id, $number = 1 ) {
	$curr = get_option( 'wpacaforge_'.$campaign_id );
	// var_dump($curr);
	if( !empty( $curr ) && ( $curr != '' ) ) {
		$items = array_slice( $curr, 0, $number );
		$curr = array_slice( $curr, $number+1 );
		//$item = $curr[0];
		//$curr = array_shift( $curr ); // extract the 1st one
	}
	else
		return false;
	update_option( 'wpacaforge_'.$campaign_id, $curr );
	return $items;
}

function wpautoc_aforge_get_article( $article_id, $api_key = '' ) {
	$url = 'https://af.articleforge.com/api/view_spin';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	// curl_setopt($ch, CURLOPT_USERPWD, "$username:$api_key");
	$payload = array( "article_id"=> $article_id, "key"=> $api_key );
	// var_dump($payload);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	# Return response instead of printing.
	$result = curl_exec($ch);
	curl_close($ch);
	// var_dump($result);
	$res = json_decode( $result );
	if( $res->status == 'Success' )
		return $res->data;
	return false;
	// var_dump($res);
	// return $res;
}

function wpautoc_aforge_uids( $campaign_id ) {
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
				'value' => WPAUTOC_CONTENT_AFORGE
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
// function wpautoc_aforge_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>