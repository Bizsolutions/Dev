<?php

define( 'WPAUTOC_CLICKBANK_PER_PAGE', 60 );
define( 'WPAUTOC_CLICKBANK_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_CLICKBANK (7)
	Unique id: video id
*/

function wpautoc_content_type_clickbank( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Wikipedia' );

	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a link to the product at the end of the post so you can get affiliate commissions from Clickbank', '', 'bbtnon'  );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_content['.$num.'][settings][buy_button_txt]', false, 'Text for the Buy Button', 'Ex: Buy Now', '', '', 'bbtnon_row', !isset( $settings->buy_button ) );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_clickbank( $campaign_id, $settings, $num_posts = 0 ) {
	// include WPAUTOC_DIR.'/lib/libs/vimeo/vimeo.php';

	$num_imported = 0;

	$clickbank_settings = wpautoc_get_settings( array( 'affiliate', 'clickbank' ) );
	// $country = isset( $clickbank_settings['country'] ) && !empty( $clickbank_settings['country'] ) ? $clickbank_settings['country'] : 'US';
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$category = isset( $settings->category ) ? $settings->category : false ;

	if( empty( $keyword ) )
		return 0;

	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_CLICKBANK_MAX_LOOPS )  ) {
		$imported = wpautoc_content_clickbank_search( $page, WPAUTOC_CLICKBANK_PER_PAGE, $keyword, $category, $campaign_id, $settings, $num_posts );
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

function wpautoc_content_clickbank_search( $page = 1, $per_page = 50, $keyword, $category, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_clickbank_search( $keyword, $category, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_clickbank_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_clickbank_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_clickbank_search( $keyword, $category, $page = 1, $per_page = 60 ) {
		if( $page ) {
			$start = ( $page -1 ) * $per_page;
		}
		else {
			$start = 0;
		}
		$end = $per_page;
	    $clickbank_settings = wpautoc_get_settings( array( 'affiliate', 'clickbank' ) );
	    $user_id = isset( $clickbank_settings['id'] ) && !empty( $clickbank_settings['id'] ) ? $clickbank_settings['id'] : '4147758';
		$url = 'http://clickbankproads.com/xmlfeed/wp/main/cb_search.asp'
	            . '?id='.$user_id
	            . '&keywords='.rawurlencode( $keyword )
	            . '&start='.$start
	            . '&end='.$end;

	    $empty_answer = array();
	    $rss = fetch_feed( $url );
	    // var_dump($url);
	    if (is_wp_error($rss)) return $empty_answer;

	    if (0 == $rss->get_item_quantity(400)) return $empty_answer;

	    $tmp = $rss->get_item()->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, 'totalp');
	    $totalp = wpautoc_cdata($tmp[0]['data']);
	    // $cat_link = wpautoc_get_products_page('cs_category', '');
	    $count = 0;
	    $item_list = array();
	    $items = $rss->get_items( 0, WPAUTOC_CLICKBANK_PER_PAGE );
	    if( $items ) {
	    foreach ($items as $item) {
	        // var_dump($item);
	        // return;
	        // Title
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "title");
	        $title = htmlspecialchars(wpautoc_cdata( $paths[0]['data'] ) );
	        // URL
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "affiliate");
	        $mem = wpautoc_cdata($paths[0]['data']);
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "ids");
	        $tar = wpautoc_cdata($paths[0]['data']);
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "niche");
	        $niche = wpautoc_cdata($paths[0]['data']);
	        $link = htmlspecialchars('http://clickbankproads.com/xmlfeed/wp/main/tracksf.asp'
	            . '?memnumber='.$user_id
	            . '&mem='.$mem
	            . '&tar='.$tar
	            . '&niche='.$niche);

	        // $id = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "ids");
	        // $sales_link = htmlspecialchars(wpautoc_cdata($ids[0]['data']));

	        // Link
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "link");
	        $sales_link = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
	        // Descriptions
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "description");
	        $description = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "mdescr");
	        $mdescr = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
	        
	        // Images
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "images");
	        $imageFilename = wpautoc_cdata($paths[0]['data']);
	        if ($imageFilename != '' && $imageFilename != 'no') {
	            $image = 'http://cbproads/cbproads.com/cbbanners/'
	                . $imageFilename;
	            $image = htmlspecialchars($image);
	            $imageFull = 'http://cbproads.com/cbbanners/'.$imageFilename;
	            // $imageFull = htmlspecialchars($imageFull);
	        } else {
	            unset($image, $imageFull);
	        }
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "altimage");
	        $altimageFilename = wpautoc_cdata($paths[0]['data']);
	        if ($altimageFilename != '' && $altimageFilename != 'no') {
	            $altimage = 'http://cbproads.com/cbbanners/alter/'
	                . $altimageFilename;
	            $altimage = htmlspecialchars($altimage);
	            $altimageFull = 'http://cbproads.com/cbbanners/alter/'
	                . $altimageFilename;
	            // $altimageFull = htmlspecialchars($altimageFull);
	        } else {
	            unset($altimage, $altimageFull);
	        }
	        
	        // Price
	        // $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "price");
	        // $price = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
	        
	        // Rank & Gravity
	        /*$paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "rank");
	        $rank = wpautoc_cdata($paths[0]['data']);
	        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "gravity");
	        $gravity = wpautoc_cdata($paths[0]['data']);*/
	        $list_item = array();
	        $list_item['id'] = $tar;
	        $list_item['title'] = $title;
	        if( $mdescr != $description )
	        	$list_item['content'] = $mdescr.'<br/>'.$description;
	        else
	        	$list_item['content'] = $mdescr;
	        $list_item['url'] = $link;
	        $list_item['image_url'] = isset( $altimageFull ) ? $altimageFull : $imageFull ;
	        // $list_item['sales_link'] = $sales_link;
	        // var_dump($list_item);
	        $item_list[] = $list_item;
	        $count++;
	    }
    }
    else
    	return array();
    return $item_list;
}


function wpautoc_do_import_clickbank_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
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
		// if( $url )
			// $content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-clickbank-btn">Buy Now from clickbank</a></p>';
		if( $url ) {
			$button_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? $settings->buy_button_txt : 'Buy Now';
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-clickbank-btn">'.$button_txt.'</a></p>';
		}
	}

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) ) {
			$thumbnail = $product['image_url'];
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_CLICKBANK, $thumbnail );

	return $product_id;
}

function wpautoc_clickbank_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_CLICKBANK, $campaign_id );
}

// function wpautoc_clickbank_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

function wpautoc_get_cb_categories() {
    return array(
        array( 'value' => 0, 'label' => 'ALL' ),
        array( 'value' => 1, 'label' => 'Arts &amp; Entertainment' ),
        array( 'value' => 2, 'label' => 'Business / Investing' ),
        array( 'value' => 3, 'label' => 'Computers / Internet' ),
        array( 'value' => 4, 'label' => 'Cooking, Food &amp; Wine' ),
        array( 'value' => 5, 'label' => 'E-business &amp; E-marketing' ),
        array( 'value' => 6, 'label' => 'Employment &amp; Jobs' ),
        array( 'value' => 7, 'label' => 'Fiction' ),
        array( 'value' => 8, 'label' => 'Games' ),
        array( 'value' => 9, 'label' => 'Green Products' ),
        array( 'value' => 10, 'label' => 'Health &amp; Fitness' ),
        array( 'value' => 11, 'label' => 'Education' ),
        array( 'value' => 12, 'label' => 'Home &amp; Garden' ),
        array( 'value' => 13, 'label' => 'Languages' ),
        array( 'value' => 14, 'label' => 'Mobile' ),
        array( 'value' => 15, 'label' => 'Parenting &amp; Families' ),
        array( 'value' => 16, 'label' => 'Politics / Current Events' ),
        array( 'value' => 17, 'label' => 'Reference' ),
        array( 'value' => 18, 'label' => 'Self-Help' ),
        array( 'value' => 19, 'label' => 'Software &amp; Services' ),
        array( 'value' => 20, 'label' => 'Spirituality, New Age &amp; Alternative Beliefs' ),
        array( 'value' => 21, 'label' => 'Sports' ),
        array( 'value' => 22, 'label' => 'Travel' ),
        array( 'value' => 23, 'label' => 'Betting Systems' ),
        array( 'value' => 24, 'label' => 'As Seen On TV' )
    );
}
?>