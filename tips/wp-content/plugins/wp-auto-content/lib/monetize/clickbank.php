<?php
// include_once WPAUTOC_DIR.'/lib/libs/clickbank/clickbankProductRequest.php';

function wpautoc_monetize_clickbank( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
    // var_dump($monetize);
    $keyword = isset( $settings->keyword ) ? $settings->keyword : false;
    $popular = isset( $settings->popular ) ? $settings->popular : false;
	$show_price = isset( $settings->show_price ) ? $settings->show_price : -1;
    // $buy_btn_txt = ( isset( $settings->buy_button_txt ) && !empty( $settings->buy_button_txt ) ) ? trim( $settings->buy_button_txt ) : 'Buy Now';
	$category = isset( $settings->category ) ? $settings->category : 0;
	$ad_type = isset( $settings->ad_type ) ? intval( $settings->ad_type ) : 1;
	$num_ads = isset( $settings->num_ads ) ? intval( $settings->num_ads ) : 3;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	// $products = isset( $settings->products ) ? $settings->products : false;
	$header = isset( $settings->header ) ? $settings->header : false;

	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads per_row_'.$ads_per_row.'">';
    $products =  wpautoc_get_cb_products( $monetize->id, $monetize->campaign_id, $keyword, $category, $popular/*, $user_id*/ );
	shuffle( $products );
	// var_dump($products);
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++ ) {
            if( !isset( $products[$i] ) )
                continue;
	    	$product = $products[$i];
            // var_dump($product);
	    	// var_dump($products[$i]);
	    	$url = urldecode( $product['url'] );
	    	$image_url = $product['image'];
	    	$product_name = $product['title'];
	    	$product_desc = $product['description'];
	    	$sales_url = $product['sales_link'];
			if( $ad_type == 1 ) {
				// image
                if( !$show_price || $show_price == -1 )
                    $price = -1;
                else
                    $price = isset( $product['price'] ) ? $product['price'] : -1;
                // var_dump($product);
                // var_dump($price);
				$code .= wpautoc_viralad( $product_name, $product_desc, $url, $image_url, $price );
			}
			else
				$code .= wpautoc_textad( $product_name, $product_desc, $url, $sales_url );

	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_get_cb_products( $monetize_id, $campaign_id, $keyword, $category = 0, $popular = false ) {
    $transient_name = 'cb_products'.$monetize_id; //rmi353
    if ( false === ( $cb_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $cb_products = wpautoc_do_get_cb_products( $keyword, $category, $popular );
        set_transient( $transient_name, $cb_products, 6 * HOUR_IN_SECONDS );
    }
    return $cb_products;
}

function wpautoc_do_get_cb_products( $keyword, $category = 0, $popular = false ) {
    $clickbank_settings = wpautoc_get_settings( array( 'affiliate', 'clickbank' ) );
    $user_id = isset( $clickbank_settings['id'] ) && !empty( $clickbank_settings['id'] ) ? $clickbank_settings['id'] : '4147758';
    if( $keyword == 'popularcb' ) {
        $url = 'http://clickbankproads.com/xmlfeed/wp/main/cb_gravity.asp'
            . '?id='.$user_id
            . '&no_of_products=12';
    }
    else {
	$url = 'http://clickbankproads.com/xmlfeed/wp/main/cb_search.asp'
            . '?id='.$user_id
            . '&keywords='.rawurlencode($keyword)
            . '&start=0'
            . '&end=12';
    if( $category )
        $url .= '&cs_category='.$category;
    }
    // echo $url;
    $empty_answer = array();
    $rss = fetch_feed($url);
    if (is_wp_error($rss))
        return $empty_answer;
// echo "aa";
    if ( 0 == $rss->get_item_quantity(400) ) {
        // No products
        if( $popular ) {
            return wpautoc_do_get_cb_products( 'popularcb', true );
        }
        return $empty_answer;
    }
// echo "bb";

    $tmp = $rss->get_item()->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, 'totalp');
    $totalp = wpautoc_cdata($tmp[0]['data']);
    // $cat_link = wpautoc_get_products_page('cs_category', '');
    $count = 0;
    $item_list = array();
    $items = $rss->get_items(0, 400);
    if( !$items ) {
        if( $popular ) {
            return wpautoc_do_get_cb_products( 'popularcb', true );
        }
    }
    foreach ($items as $item) {
        // var_dump($item);
        // Title
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "title");
        $title = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
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
            $image = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/'
                . $imageFilename.'&resize=240&show_border=No';
            $image = htmlspecialchars($image);
            $imageFull = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/'.$imageFilename.'&resize=default&show_border=No';
            $imageFull = htmlspecialchars($imageFull);
        } else {
            unset($image, $imageFull);
        }
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "altimage");
        $altimageFilename = wpautoc_cdata($paths[0]['data']);
        if ($altimageFilename != '' && $altimageFilename != 'no') {
            $altimage = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/alter/'
                . $altimageFilename.'&resize=default&show_border=No';
            $altimage = htmlspecialchars($altimage);
            $altimageFull = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/alter/'
                . $altimageFilename.'&resize=240&show_border=No';
            $altimageFull = htmlspecialchars($altimageFull);
        } else {
            unset($altimage, $altimageFull);
        }
        
        // Price
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "price");
        $price = htmlspecialchars(wpautoc_cdata($paths[0]['data']));
        
        // Rank & Gravity
        /*$paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "rank");
        $rank = wpautoc_cdata($paths[0]['data']);
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "gravity");
        $gravity = wpautoc_cdata($paths[0]['data']);*/
        $list_item = array();
        $list_item['title'] = html_entity_decode( $title );
        $list_item['description'] = $description;
        $list_item['url'] = $link;
        $list_item['image'] = $image;
        $list_item['price'] = $price;
        $list_item['sales_link'] = $sales_link;
        $item_list[] = $list_item;
        $count++;
    }
    return $item_list;
}



?>