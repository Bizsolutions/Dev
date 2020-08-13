<?php

include WPAUTOC_DIR.'lib/traffic/backlinkmachine.php';
include WPAUTOC_DIR.'lib/traffic/linkindexers.php';
include WPAUTOC_DIR.'lib/traffic/twitter.php';
include WPAUTOC_DIR.'lib/traffic/facebook.php';
include WPAUTOC_DIR.'lib/traffic/pinterest.php';
include WPAUTOC_DIR.'lib/traffic/instagram.php';
include WPAUTOC_DIR.'lib/traffic/googleplus.php';
include WPAUTOC_DIR.'lib/traffic/reddit.php';
include WPAUTOC_DIR.'lib/traffic/stumbleupon.php';
include WPAUTOC_DIR.'lib/traffic/medium.php';
include WPAUTOC_DIR.'lib/traffic/tumblr.php';
include WPAUTOC_DIR.'lib/traffic/linkedin.php';
include WPAUTOC_DIR.'lib/traffic/buffer.php';


add_action( 'wpautoc_insert_post' , 'wpautoc_new_post', 10 , 2 );
function wpautoc_new_post( $post_id , $campaign_id )
{
	if ( !wpautoc_is_traffic() )
		return;

	if( get_post_type( $post_id ) != 'post' )
		return;

	if ( wp_is_post_revision( $post_id ) )
		return;

	// if ( $post->post_date == $post->post_modified )
	// {

	$campaign_id = get_post_meta( $post_id, '_wpac_cid', true );
	if( !$campaign_id )
		return;
	// New post, get traffic!

	$traffics = wpautoc_get_traffic_elements( $campaign_id );
	if( !$traffics )
		return $post_id;
	$i = 0;
// $settings = json_decode( $content->settings );

	foreach( $traffics as $traffic ) {
		if( !isset( $traffic->settings ) )
			continue;
		$settings = wpautoc_json_decode_nice( $traffic->settings );
		switch ( $traffic->type ) {
			case WPAUTOC_TRAFFIC_BACKLINKMACHINE:
				$done = wpautoc_traffic_backlinkmachine( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_ILI:
				$done = wpautoc_traffic_ili( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_BLI:
				$done = wpautoc_traffic_bli( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_TWITTER:
				$done = wpautoc_traffic_twitter( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_FACEBOOK:
				$done = wpautoc_traffic_facebook( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_PINTEREST:
				$done = wpautoc_traffic_pinterest( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_STUMBLEUPON:
				$done = wpautoc_traffic_stumbleupon( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_MEDIUM:
				$done = wpautoc_traffic_medium( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_TUMBLR:
				$done = wpautoc_traffic_tumblr( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_LINKEDIN:
				$done = wpautoc_traffic_linkedin( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_BUFFER:
				$done = wpautoc_traffic_buffer( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_REDDIT:
				$done = wpautoc_traffic_reddit( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_INSTAGRAM:
				$done = wpautoc_traffic_instagram( $post_id, $traffic, $settings );
				break;
			case WPAUTOC_TRAFFIC_GOOGLEPLUS:
				$done = wpautoc_traffic_googleplus( $post_id, $traffic, $settings );
				break;
			default:
				break;
		}
	}
	// }
	return;
}

function wpautoc_log_traffic( $post_id, $type ) {
	add_post_meta( $post_id, 'wpac_traf', $type, false );
}

function wpautoc_get_traffic( $post_id ) {
	return get_post_meta( $post_id, 'wpac_traf', false );
}
?>