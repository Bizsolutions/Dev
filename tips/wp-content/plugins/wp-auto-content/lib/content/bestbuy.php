<?php

define( 'WPAUTOC_BESTBUY_PER_PAGE', 25 );
define( 'WPAUTOC_BESTBUY_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_BESTBUY (7)
	Unique id: video id
*/

function wpautoc_content_type_bestbuy( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'bestbuy' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Bestbuy products, you need a valid Bestbuy API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Bestbuy API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Bestbuy' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many bestbuy items will be shown per post' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link to the product at the end of the post so you can get affiliate commissions', '', 'bbtnon'  );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_content['.$num.'][settings][buy_button_txt]', false, 'Text for the Buy Button', 'Ex: Buy now from BestBuy', '', '', 'bbtnon_row', !isset( $settings->buy_button ) );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_bestbuy( $campaign_id, $settings, $num_posts = 0 ) {
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
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_BESTBUY_MAX_LOOPS )  ) {
		$imported = wpautoc_content_bestbuy_search( $page, WPAUTOC_BESTBUY_PER_PAGE, $keyword,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_bestbuy_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_bestbuy_search( $keyword, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_bestbuy_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_bestbuy_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_bestbuy_search( $keyword,  $page = 1, $per_page = 60 ) {
	$bestbuy_settings = wpautoc_get_settings( array( 'affiliate', 'bestbuy' ) );
		$api_key = isset( $bestbuy_settings['apikey'] ) && !empty( $bestbuy_settings['apikey'] ) ? $bestbuy_settings['apikey'] : '0ckoDt0yU9C8Vb8Ca4x9RyJH';

		$url = 'https://api.bestbuy.com/v1/products((search='. $keyword .'))?apiKey='.$api_key.'&sort=bestSellingRank.asc&show=sku,image,name,longDescription,url,salePrice,bestSellingRank,features&format=json';
// echo $url;
		$response = wp_remote_get( $url );
		if ( is_array( $response ) ) {
		  $header = $response['headers']; // array of http header lines
		  $body = $response['body']; // use the content
		  $prods = json_decode( $body );
		  // var_dump($prods);
		  $products = array();
		  if( isset( $prods->total ) && $prods->total > 0 ) {
		  	foreach( $prods->products as $item ) {
		  		// var_dump($item);
		  		$prod = array();
				$prod['id'] = (string) $item->sku;
				$prod['title'] = (string) $item->name;
				$prod['content'] = (string) $item->longDescription;
				$features = false;
				if( !empty( $item->features ) ) {
					// var_dump($item->features);
					$filter = function($tag){ return '<p>' . $tag->feature . '</p>'; };
					$features = array_map($filter, $item->features);
					$features = implode( '', $features );
					// $features = "<p>" . implode("</p><p>", $item->features ) . "</p>";
				}
				if( $features )
					$prod['content'] .= $features;
				$prod['url']  = (string) $item->url;
				// rmi353, probablemente es productTrackingUrl
				$prod['image_url']   = (string) $item->image;
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


function wpautoc_do_import_bestbuy_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
		/*if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-bestbuy-btn">Buy Now from Bestbuy</a></p>';*/
		if( $url ) {
			$button_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? $settings->buy_button_txt : 'Buy Now from Bestbuy';
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-bestbuy-btn">'.$button_txt.'</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_BESTBUY, $thumbnail );

	return $product_id;
}

function wpautoc_bestbuy_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_BESTBUY, $campaign_id );
}

// function wpautoc_bestbuy_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>