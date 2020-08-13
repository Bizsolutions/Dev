<?php
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function wpautoc_traffic_instagram( $post_id, $traffic, $settings ) {
	$res = wpautoc_share_instagram( get_the_title( $post_id ), wpautoc_get_the_excerpt( $post_id ), get_permalink( $post_id ), wpautoc_get_thumbnail( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_INSTAGRAM );
}

function wpautoc_share_instagram( $title, $text, $post_url, $image_url = false ) {
	$instagram_settings = wpautoc_get_settings( array( 'social', 'instagram' ) );

	if ( ! isset( $instagram_settings['email'] ) || empty( $instagram_settings['email'] ) || ! isset( $instagram_settings['pass'] ) || empty( $instagram_settings['pass'] ) ) {
		wpautoc_debug( 'Error with Instagram Credentials', 'error' );
		return false;
	}
	wpautoc_include_nextgen();

	$email = $instagram_settings['email'];
	$password = $instagram_settings['pass'];

	$imgFormat = 'E'; // 'E' (Extended) or 'C' (Cropped) or 'U' (Untouched)
	$nt = new nxsAPI_IG();
	$loginError = $nt->connect( $email, $password );
	if (!$loginError)
	  {
	    $result = $nt->post( wpautoc_before_upload( $title ).' '.$post_url, $image_url, $imgFormat );
	    if( $result ) {
			wpautoc_debug( 'Shared on Instagram', 'notice' );
	    	return true;
	    }
	  }
	else {
		wpautoc_debug( 'Instagram - error with login', 'error' );
		return false;
	}
	return false;
}

?>