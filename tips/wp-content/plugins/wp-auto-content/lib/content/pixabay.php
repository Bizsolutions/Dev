<?php

define( 'WPAUTOC_PIXABAY_USER', 'canvakala' );
define( 'WPAUTOC_PIXABAY_APIKEY', '1260271-c62621822e9870b3968a53ac3' );

function wpautoc_pixabay_get_image( $keyword = false ) {
	$pixabay_settings = wpautoc_get_settings( array( 'images', 'pixabay' ) );
	$apikey = isset( $pixabay_settings['apikey'] ) ? trim( $pixabay_settings['apikey'] ) : WPAUTOC_PIXABAY_APIKEY;
	if( empty( $apikey ) ) {
		return false;
	}
	$page = 1;
	$url = 'https://pixabay.com/api/?q='.trim( urlencode( $keyword ) ).'&key='.trim( $apikey ).'&per_page=50&page='.$page;
	// echo $url;
	$data = wpautoc_remote_get( $url );
	$imgs = json_decode( $data );
	// var_dump($imgs);
	if( isset( $imgs->totalHits ) && $imgs->totalHits ) {
		// hay respuesta
		return $imgs->hits[ rand( 0, ( count ( $imgs->hits ) -1 ) ) ]->largeImageURL;
	}
	return false;
	// var_dump($imgs);
	// https://pixabay.com/api/?key=19464-0da5f3dcacf8d02964ef930f8&q=yellow+flowers&image_type=photo&pretty=true
}


/* Generic image settings */

function wpautoc_campaign_images( $campaign_id = 0 ) {
	$campaign = wpautoc_get_campaign( $campaign_id );
	if( $campaign ) {
		$settings = isset( $campaign->settings ) ? json_decode( $campaign->settings ) : false;
		$imgs = isset( $settings->imgs ) ? intval($settings->imgs) : 0;
		if( $imgs ) {
			$img_keywords = isset( $settings->img_keywords ) ? trim($settings->img_keywords) : '';
			if( !empty( $img_keywords ) )
				return $img_keywords;
			return false;
		}
		else
			return false;
	}
}

function wpautoc_get_campaign_thumbnail( $keywords = false ) {
	if( empty( $keywords ) )
		return false;

	$pixabay_settings = wpautoc_get_settings( array( 'images', 'pixabay' ) );
	$pixabay_apikey = isset( $pixabay_settings['apikey'] ) ? trim( $pixabay_settings['apikey'] ) : WPAUTOC_PIXABAY_APIKEY;

	$flickr_settings = wpautoc_get_settings( array( 'images', 'flickr' ) );
	$flickr_apikey = isset( $flickr_settings['apikey'] ) ? trim( $flickr_settings['apikey'] ) : '';

	if( empty( $pixabay_apikey ) && empty( $flickr_apikey ) )
		return false;
	else if( !empty( $pixabay_apikey ) && empty( $flickr_apikey ) )
		return wpautoc_pixabay_get_image( $keywords );
	else if( empty( $pixabay_apikey ) && !empty( $flickr_apikey ) )
		return wpautoc_flickr_get_image( $keywords );
	else {
		return ((bool)random_int(0, 1)) ? wpautoc_pixabay_get_image( $keywords ) : wpautoc_flickr_get_image( $keywords );
	}
}
?>