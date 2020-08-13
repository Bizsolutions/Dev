<?php
function wpautoc_traffic_tumblr( $post_id, $traffic, $settings ) {
	$res = wpautoc_share_tumblr( get_the_title( $post_id), wpautoc_get_content_by_id( $post_id ), get_permalink( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_TUMBLR );
}

// TO-DO tags, imagen
function wpautoc_share_tumblr( $title, $text, $post_url, $image_url = false ) {
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Util.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Request.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Token.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/Consumer.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/SignatureMethod.php';
	require_once WPAUTOC_DIR.'/lib/libs/oauth/Eher/Oauth/HmacSha1.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/RequestException.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/RequestHandler.php';
	require_once WPAUTOC_DIR.'/lib/libs/tumblr/Client.php';
	$tumblr_settings = wpautoc_get_settings( array( 'social', 'tumblr' ) );

	if ( ! isset( $tumblr_settings['key'] ) || empty( $tumblr_settings['key'] ) || ! isset( $tumblr_settings['secret'] ) || empty( $tumblr_settings['secret'] ) ||
	! isset( $tumblr_settings['oauth_token'] ) || empty( $tumblr_settings['oauth_token'] ) || ! isset( $tumblr_settings['oauth_secret'] ) || empty( $tumblr_settings['oauth_secret'] )
	 ) {
		wpautoc_debug( 'Error with Tumblr Credentials', 'error' );
		return false;
	}

	$client = new Tumblr\API\Client( $tumblr_settings['key'], $tumblr_settings['secret'], $tumblr_settings['oauth_token'], $tumblr_settings['oauth_secret'] );

	$user_blogs = $client->getUserInfo()->user->blogs;
	if( $user_blogs && isset( $user_blogs[0] ) && !empty( $user_blogs[0] ) ) {
		$blog = $user_blogs[0];
		$blog_name = $blog->name;
	    $data = array(
	    	'type' => 'text',
	        'format' => 'html',
	        'title' => $title,
	        'body' => wpautoc_before_upload( $text ).'<br/>Read more at: <a href="'.$post_url.'">'.$post_url.'</a>'
	    );

	    $post = $client->createPost( $blog_name, $data );
		wpautoc_debug( 'Shared on Tumblr', 'notice' );
	    return true;
	}
	return false;
}

?>