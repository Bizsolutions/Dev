<?php
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once WPAUTOC_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function wpautoc_traffic_reddit( $post_id, $traffic, $settings ) {
	$subreddit = $settings->subreddit;
	$res = wpautoc_share_reddit( $subreddit, get_the_title( $post_id), wpautoc_get_the_excerpt( $post_id ), get_permalink( $post_id ), wpautoc_get_thumbnail( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_REDDIT );
}

function wpautoc_share_reddit( $subreddit, $title, $text, $post_url, $image_url = false ) {

	$reddit_settings = wpautoc_get_settings( array( 'social', 'reddit' ) );

	if ( ! isset( $reddit_settings['email'] ) || empty( $reddit_settings['email'] ) || ! isset( $reddit_settings['pass'] ) || empty( $reddit_settings['pass'] ) ) {
		wpautoc_debug( 'Error with Reddit Credentials', 'error' );
		return false;
	}
	wpautoc_include_nextgen();

	// var_dump($reddit_settings);
	$email = $reddit_settings['email'];
	$password = $reddit_settings['pass'];

	$nt         = new nxsAPI_RD();
	$loginError = $nt->connect( $email, $password );
// var_dump($loginError);
	if ( !$loginError ) {
		//$msg, $title, $sr, $url
	  $return = $nt->post( $text, $title, $subreddit, $post_url );
		wpautoc_debug( 'Shared on Reddit', 'notice' );
	  return true;
	}
	wpautoc_debug( 'Error connecting with Reddit', 'error' );
	return false;
}

function wpautoc_getsubreddits() {
	return wpautoc_do_get_subreddits( );
    $transient_name = 'wpautoc_subreddits';
    if ( false === ( $subreddits = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $subreddits = wpautoc_do_get_subreddits( );
         set_transient( $transient_name, $subreddits, 3 * HOUR_IN_SECONDS );
    }
    return $subreddits;
}

function wpautoc_do_get_subreddits() {
	$reddit_settings = wpautoc_get_settings( array( 'social', 'reddit' ) );

	if ( ! isset( $reddit_settings['email'] ) || empty( $reddit_settings['email'] ) || ! isset( $reddit_settings['pass'] ) || empty( $reddit_settings['pass'] ) )
		return false;

	// var_dump($reddit_settings);
	$email = $reddit_settings['email'];
	$password = $reddit_settings['pass'];

	$nt         = new nxsAPI_RD();
	$loginError = $nt->connect( $email, $password );
// var_dump($loginError);
	if ( !$loginError ) {
		return $nt->getSubReddits();
	}
	return array();
}
?>