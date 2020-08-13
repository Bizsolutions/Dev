<?php
function wpautoc_monetize_banner( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$image = isset( $settings->image ) ? $settings->image : false;
	$link = isset( $settings->link ) ? $settings->link : false;
	$new_win = isset( $settings->new_win ) ? $settings->new_win : false;

	$target = $new_win ? ' target="_blank" ':'';
	$banner_el = '<a href="'.$link.'"'.$target.'>
		<img style="max-width:100%;height:auto" src="'.$image.'"/>
	</a>';

	return wpautoc_add_element_in_content( $banner_el, $content, $settings );
}

function wpautoc_monetize_adsense( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$code = isset( $settings->code ) ? $settings->code : '';
	$code = stripslashes( html_entity_decode( $code ) );
	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_monetize_adcode( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	// print_r($monetize->settings);
	$monetize->settings = str_replace( "\\r\\n", "<br/>", $monetize->settings );
	$monetize->settings =  htmlspecialchars_decode(  stripslashes ( nl2br( $monetize->settings ) ) );
	// print_r($monetize->settings);
	$settings = wpautoc_json_decode_nice( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	// die('kk');
	// $html = isset( $settings->html ) ? nl2br( htmlspecialchars_decode( stripslashes( $settings->html ) ) ) : '';
	$html = isset( $settings->html ) ?  $settings->html  : '';
// var_dump($html);
	return wpautoc_add_element_in_content( $html, $content, $settings );
}


function wpautoc_monetize_viralads( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	$ads = isset( $settings->ads ) ? (array) $settings->ads : false;
	$header = isset( $settings->header ) ? $settings->header : false;
// var_dump($ads);
	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads viralads per_row_'.$ads_per_row.'">';
	if( $ads ) {
	    foreach ( $ads as $ad ) {
	    	// $ad = $ads[$i];
	    	// var_dump($products[$i]);
	    	$url = urldecode( $ad->url );
	    	$image_url = $ad->image;
	    	$title = $ad->title;
	    	$description = $ad->description;
			$code .= wpautoc_viralad( $title, $description, $url, $image_url );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_monetize_textads( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	$ads = isset( $settings->ads ) ? (array) $settings->ads : false;
	$header = isset( $settings->header ) ? $settings->header : false;
// var_dump($ads);
	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads textads per_row_'.$ads_per_row.'">';
	if( $ads ) {
	    foreach ( $ads as $ad ) {
	    	// $ad = $ads[$i];
	    	// var_dump($products[$i]);
	    	$url = urldecode( $ad->url );
	    	$title = $ad->title;
	    	$description = $ad->description;
			$code .= wpautoc_textad( $title, $description, $url );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_add_element_in_content( $element, $content, $settings ) {
	$float = isset( $settings->float ) ? $settings->float : 1;
	$margin = isset( $settings->margin ) ? intval( $settings->margin ) : 0;
	$float_str = '';
	if( $float == 2 )
		$float_str = 'float:left';
	else if( $float == 3 )
		$float_str = 'float:right';
	$wrapper_open = '<div style="'.$float_str.';margin: '.$margin.'px!important">';
	if( $float == 1 )
		$wrapper_close = '</div><div style="clear:both"></div>';
	else
		$wrapper_close = '</div>';

	if( !isset( $settings->position ) || ( empty( $settings->position ) ) || ( $settings->position == 1 ) ) {
		// beginning of post
		return $wrapper_open.$element.$wrapper_close.$content;
	}
	else if( $settings->position == 2 ) {
		// end of post
		return $content.$wrapper_open.$element.$wrapper_close.'<div style="clear:both"></div>';
	}
	else if( $settings->position == 3 ) {
		// middle
	    $content = explode( '</p>', $content );
	    $new_content = '';
	    $num_paragraphs = count( $content );
	    $paragraphAfter = round( $num_paragraphs / 2 );
	    for ( $i = 0; $i < $num_paragraphs; $i++ ) {
	        if ( $i == $paragraphAfter ) {
	        	$new_content .= $wrapper_open.$element.$wrapper_close;
	        }

	        $new_content .= $content[$i] . '</p>';
	    }
	    return $new_content;
	}
	else if( $settings->position == 4 ) {
		// after x paragraphs
		$paragraphAfter = isset( $settings->paragraph ) ? intval( $settings->paragraph ) : 0; //Enter number of paragraphs to display ad after.
		if( !$paragraphAfter )
			return $content;
	    $content = explode( '</p>', $content );
	    $new_content = '';
	    $num_paragraphs = count( $content );
	    for ( $i = 0; $i < $num_paragraphs; $i++ ) {
	        if ( $i == $paragraphAfter ) {
	        	$new_content .= $wrapper_open.$element.$wrapper_close;
	        }

	        $new_content .= $content[$i] . '</p>';
	    }
	    return $new_content;
	}
}

function wpautoc_monetize_clickbankads( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$ads_per_row = isset( $settings->ads_per_row ) ? intval( $settings->ads_per_row ) : 3;
	$ads = isset( $settings->ads ) ? (array) $settings->ads : false;
	$header = isset( $settings->header ) ? $settings->header : false;
	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads viralads per_row_'.$ads_per_row.'">';
	if( $ads ) {
	    foreach ( $ads as $ad ) {
	    	$url = urldecode( $ad->url );
	    	$image_url = $ad->image;
	    	$title = $ad->title;
	    	$description = $ad->description;
			$code .= wpautoc_viralad( $title, $description, $url, $image_url );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );
}

function wpautoc_monetize_popup_content( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$html = isset( $settings->html ) ? html_entity_decode( stripslashes( $settings->html ) ) : '';
	$str = '<div id="autoc-content-modal-front" style="display:none">'.$html.'</div>';
	return $content.$str;
	/*$ads = isset( $settings->ads ) ? (array) $settings->ads : false;
	$header = isset( $settings->header ) ? $settings->header : false;
// var_dump($ads);
	$code = empty( $header ) ? '' : '<h3>'.$header.'</h3>';
	$code .= '<div class="wpac_am_ads viralads per_row_'.$ads_per_row.'">';
	if( $ads ) {
	    foreach ( $ads as $ad ) {
	    	// $ad = $ads[$i];
	    	// var_dump($products[$i]);
	    	$url = urldecode( $ad->url );
	    	$image_url = $ad->image;
	    	$title = $ad->title;
	    	$description = $ad->description;
			$code .= wpautoc_viralad( $title, $description, $url, $image_url );
	    }
	}
    $code .= '</div>';

	return wpautoc_add_element_in_content( $code, $content, $settings );*/
}

add_action( 'wp_footer', 'chochete', 120 );

function chochete() {
?>
	<script>
	jQuery(document).ready( function($) {
		// console.log("modal llamado")
		try {
        	jQuery( "#autoc-content-modal-front" ).acmodal();
        }
        catch(err) {
            console.log( err.message );
        }
	});
	</script>
<?php
}
?>