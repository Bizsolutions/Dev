<?php
function wpautoc_traffic_medium( $post_id, $traffic, $settings ) {
	$res = wpautoc_share_medium( get_the_title( $post_id), wpautoc_get_content_by_id( $post_id ), get_permalink( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_MEDIUM );
}

// TO-DO tags, imagen
function wpautoc_share_medium( $title, $text, $post_url, $image_url = false ) {
	require_once WPAUTOC_DIR.'/lib/libs/medium/MediumClient.php';
	require_once WPAUTOC_DIR.'/lib/libs/medium/Medium.php';
	$medium_settings = wpautoc_get_settings( array( 'social', 'medium' ) );

	if ( ! isset( $medium_settings['token'] ) || empty( $medium_settings['token'] ) ) {
		wpautoc_debug( 'Error with Medium Credentials', 'error' );
		return false;
	}
	$medium = new Medium( $medium_settings['token'] );

	$user = $medium->getAuthenticatedUser();

    $data = array(
        'title' => $title,
        'contentFormat' => 'html',
        'content' => $text.'<br/>Read more at: <a href="'.$post_url.'">'.$post_url.'</a>',
        'publishStatus' => 'public',
        'canonicalUrl' => $post_url,
        'notifyFollowers' => true
    );

    $post = $medium->createPost( $user->id, $data );
    if( $post )
    	return true;
    return false;
}
?>