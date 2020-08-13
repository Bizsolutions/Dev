<?php

include WPAUTOC_DIR.'lib/monetize/ads.php';
include WPAUTOC_DIR.'lib/monetize/amazon.php';
include WPAUTOC_DIR.'lib/monetize/clickbank.php';
include WPAUTOC_DIR.'lib/monetize/ebay.php';
include WPAUTOC_DIR.'lib/monetize/aliexpress.php';
include WPAUTOC_DIR.'lib/monetize/gearbest.php';
include WPAUTOC_DIR.'lib/monetize/walmart.php';
include WPAUTOC_DIR.'lib/monetize/bestbuy.php';
include WPAUTOC_DIR.'lib/monetize/envato.php';
include WPAUTOC_DIR.'lib/monetize/links.php';
include WPAUTOC_DIR.'lib/monetize/optin.php';

add_filter( 'the_content', 'wpautoc_content_filter' );
function wpautoc_content_filter( $content ) {
	/*if ( !wpautoc_is_monetize() )
		return $content;*/
	if ( !is_single() || !is_main_query() )
		return $content;
	$post_id = get_the_id();
	$campaign_id = wpautoc_get_post_campaign( $post_id );
	if( !$campaign_id )
		return $content;
	$monetization = wpautoc_get_monetize_elements( $campaign_id, true );
	if( !$monetization )
		return $content;
	$i = 0;
	foreach( $monetization as $monetize ) {
		// var_dump($monetize);
		// var_dump( $monetize->type);
		if( !wpautoc_is_monetize() ) {
			if( !in_array( $monetize->type, array( WPAUTOC_MONETIZE_LINKS, WPAUTOC_MONETIZE_BANNER, WPAUTOC_MONETIZE_ADSENSE, WPAUTOC_MONETIZE_ADCODE ) ) ) {
				// die('kk');
				continue;
			}
		}
		switch ( $monetize->type ) {
			case WPAUTOC_MONETIZE_LINKS:
				$content = wpautoc_monetize_links_replace( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_INTEXT:
				$content = wpautoc_monetize_intext_replace( $content, $monetize, $i++ );
				break;
			case WPAUTOC_MONETIZE_BANNER:
				$content = wpautoc_monetize_banner( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_ADSENSE:
				$content = wpautoc_monetize_adsense( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_ADCODE:
				$content = wpautoc_monetize_adcode( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_AMAZON:
				$content = wpautoc_monetize_amazon( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_ALIEXPRESS:
				$content = wpautoc_monetize_aliexpress( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_GEARBEST:
				$content = wpautoc_monetize_gearbest( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_OPTIN:
				$content = wpautoc_monetize_optin( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_SOCIALLOCK:
				$content = wpautoc_monetize_sociallock( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_VIRALADS:
				$content = wpautoc_monetize_viralads( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_TEXTADS:
				$content = wpautoc_monetize_textads( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_CLICKBANK:
				$content = wpautoc_monetize_clickbank( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_EBAY:
				$content = wpautoc_monetize_ebay( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_WALMART:
				$content = wpautoc_monetize_walmart( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_BESTBUY:
				$content = wpautoc_monetize_bestbuy( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_ENVATO:
				$content = wpautoc_monetize_envato( $content, $monetize );
				break;
			case WPAUTOC_MONETIZE_POPUP:
				$content = wpautoc_monetize_popup_content( $content, $monetize );
				break;
			default:
				break;
		}
	}
	return $content;
}


function wpautoc_monetize_prod( $title, $url, $image_url, $source = 'amazon', $button_txt = 'Buy Now', $price = -1 ) {
	$code = '<div class="wpac_am">
			<a class="nounder" href="'.$url.'" target="_blank">
				<div class="wpac_thumb">
					<img class="wpac_am_i" src="'.$image_url.'"/>
				</div>
				<div class="wpac_am_t">'.$title.'</div>';
			// var_dump($price);
	if( ( $price != -1 ) && ( !empty( $price ) ) )
		$code .= '<div class="wpac_am_p">'.$price.' $</div>';
			$code .= '
				<div class="wpac_am_src"><img src="'.WPAUTOC_URL.'/img/logo-'.$source.'.png" class="wpac_am_il" /></div>
			</a>
			<div class="wpac_buybtn">
    			<a class="wpac_am_l" href="'.$url.'" target="_blank">'.$button_txt.'</a>
    		</div>
    	</div>
	';
	return $code;
}

function wpautoc_viralad( $title, $description, $url, $image_url, $price = -1 ) {
	$code = '<div class="wpac_am">
			<a class="nounder" href="'.$url.'" target="_blank">
				<div class="wpac_thumb">
					<img class="wpac_am_i" src="'.$image_url.'"/>
				</div>
				<div class="wpac_am_desc">'.$description.'</div>
			<div class="wpac_am_t">'.$title.'</div>';
			// var_dump($price);
	if( ( $price != -1 ) && ( !empty( $price ) ) )
		$code .= '<div class="wpac_am_p">'.$price.' $</div>';
			$code .= '
			</a>
    	</div>
	';
	return $code;
}

function wpautoc_textad( $title, $description, $url, $display_url = false ) {
	if( empty( $display_url ) )
		$display_url = $url;
	$code = '<div class="wpac_am">
				<div class="wpac_am_t"><a class="nounder" href="'.$url.'" target="_blank">'.$title.'</a></div>
				<div class="wpac_am_desc">'.$description.'</div>
				<div class="wpac_am_url"><a class="nounder" href="'.$url.'" target="_blank">'.$display_url.'</a></div>
    	</div>
	';
	return $code;
}

?>