<?php
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function wpautoc_traffic_googleplus( $post_id, $traffic, $settings ) {
	$page_id = $settings->page_id;
	$res = wpautoc_share_googleplus( $page_id, get_the_title( $post_id ), wpautoc_get_the_excerpt( $post_id ), get_permalink( $post_id ), wpautoc_get_thumbnail( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_GOOGLEPLUS );
}

function wpautoc_share_googleplus( $page_id, $title, $text, $post_url, $image_url = false ) {
	wpautoc_include_nextgen();

	$googleplus_settings = wpautoc_get_settings( array( 'social', 'googleplus' ) );

	if ( ! isset( $googleplus_settings['email'] ) || empty( $googleplus_settings['email'] ) || ! isset( $googleplus_settings['pass'] ) || empty( $googleplus_settings['pass'] ) ) {
		wpautoc_debug( 'Error with Google+ Credentials', 'error' );
		return false;
	}

	// var_dump($googleplus_settings);
	$email = $googleplus_settings['email'];
	$password = $googleplus_settings['pass'];

	  $nt = new nxsAPI_GP();
	  $loginError = $nt->connect($email, $password);
	  if (!$loginError)
		{
		  $lnk = array( 'img'=>$image_url );
		  if( !empty( $page_id ) )
		  	$result = $nt -> postGP( wpautoc_before_upload( $title ), $post_url, $page_id );
		  else
		  	$result = $nt -> postGP( wpautoc_before_upload( $title ), $post_url );
		  // var_dump($result);
		  if (!empty($result) && is_array($result) && !empty($result['postURL'])) {
			wpautoc_debug( 'Shared on Google+', 'notice' );
			return true;
		}
		else {
			wpautoc_debug( 'Error Sharing on Google+', 'error' );
			return false;
		}
		}
	  else {
		wpautoc_debug( 'Google+ - error with login', 'error' );
	  	return false;
	  }
	return false;
}

?>