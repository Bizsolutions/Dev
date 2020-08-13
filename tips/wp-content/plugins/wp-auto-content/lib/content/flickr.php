<?php

function wpautoc_flickr_get_image( $keyword = false ) {
	$flickr_settings = wpautoc_get_settings( array( 'images', 'flickr' ) );
	$apikey = isset( $flickr_settings['apikey'] ) ? trim( $flickr_settings['apikey'] ) : '';
	if( empty( $apikey ) ) {
		return false;
	}
	$page = 1;

	$req_args = array(
	    'per_page'       => 50,
	    'page'           => 1,
	    'safe_search'    => 1,
	    /*'content_type'   =>  $content_type, // Search for photos only 1: photos, 3: cliparts*/
	    'privacy_filter' => 1, // Search for public photos
	    'sort'           => 'relevance',
	    'license'        => '7,8,9,10',
	    'text'           => trim( $keyword ),
	    'media'           => 'photos',
	    'api_key'        => trim( $apikey ),
	    'method'         => 'flickr.photos.search',
	    'format'         => 'json',
	    'extras'        => 'url_o,url_l,url_m',
	    'nojsoncallback'=> 1
	);

	$url = add_query_arg( $req_args, 'https://api.flickr.com/services/rest/' );
	// echo $url;
	$data = wpautoc_remote_get( $url );
	$imgs = json_decode( $data );
	// var_dump($imgs);
	// return;
	if( isset( $imgs->photos->total ) && $imgs->photos->total ) {
		// hay respuesta
		// var_dump($imgs->photos->photo[0]);
		return $imgs->photos->photo[ rand( 0, ( count ( $imgs->photos ) -1 ) ) ]->url_l;
	}
	return false;
	// var_dump($imgs);
	// https://flickr.com/api/?key=19464-0da5f3dcacf8d02964ef930f8&q=yellow+flowers&image_type=photo&pretty=true
}

?>