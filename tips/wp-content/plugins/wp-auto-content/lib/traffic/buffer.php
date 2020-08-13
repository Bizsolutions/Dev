<?php
function wpautoc_traffic_buffer( $post_id, $traffic, $settings ) {
	$res = wpautoc_share_buffer( get_the_title( $post_id), wpautoc_get_content_by_id( $post_id ), get_permalink( $post_id ), wpautoc_get_thumbnail( $post_id ) );
	if( $res )
		wpautoc_log_traffic( $post_id, WPAUTOC_TRAFFIC_BUFFER );
}

function wpautoc_share_buffer( $title, $text, $post_url, $image_url = false ) {
	require_once WPAUTOC_DIR.'/lib/libs/buffer/buffer.php';
	$buffer_settings = wpautoc_get_settings( array( 'social', 'buffer' ) );
	// var_dump($buffer_settings);
	if ( ! isset( $buffer_settings['client_id'] ) || empty( $buffer_settings['client_id'] ) || ! isset( $buffer_settings['client_secret'] ) || empty( $buffer_settings['client_secret'] ) || ! isset( $buffer_settings['token'] ) || empty( $buffer_settings['token'] ) ) {
		wpautoc_debug( 'Error with Buffer Credentials', 'error' );
		return false;
	}

	$buffer = new BufferApp( $buffer_settings['client_id'], $buffer_settings['client_secret'], admin_url('/admin.php?page=wp-auto-content-settings&buffer_auth=true'), $buffer_settings['token'] );

	$profiles = $buffer->go('/profiles');
	if ( is_array($profiles ) ) {
	    foreach ($profiles as $profile) {
	        //this creates a status on each one
	        $update_post = array(
	            'text' => wpautoc_before_upload( $title ).' '.$post_url,
	            'profile_ids[]' => $profile->id,
	            'shorten'=>true,
	            'now'=>true,
	            'top'=>true
	        );

	        if( $image_url ) {
	        	$update_post['media[picture]'] = $image_url;
	        	$update_post['media[thumbnail]'] = $image_url;
	        	$update_post['media[title]'] = $title;
	        	$update_post['media[link]'] = $post_url;
	        }
	        $buffer->go('/updates/create', $update_post );
	    }
	    return true;
    }
	wpautoc_debug( 'Error Sharing on Buffer', 'error' );
	return false;
}

add_action( 'init', 'wpautoc_try_buffer_auth' );

function wpautoc_try_buffer_auth() {
    require_once WPAUTOC_DIR.'/lib/libs/buffer/buffer.php';
    $buffer_settings = wpautoc_get_settings( array( 'social', 'buffer' ) );
    // var_dump($buffer_settings);
    if ( ! isset( $buffer_settings['client_id'] ) || empty( $buffer_settings['client_id'] ) || ! isset( $buffer_settings['client_secret'] ) || empty( $buffer_settings['client_secret'] ) )
        return false;

	if( isset( $_GET['buffer_auth'] ) ) {
        $buffer = new BufferApp( $buffer_settings['client_id'], $buffer_settings['client_secret'], admin_url('/admin.php?page=wp-auto-content-settings&buffer_auth=true') );
        if ( !$buffer->ok ) {
            echo 'Error with Buffer Login.';
        } else {
            $settings = wpautoc_get_plugin_settings();
            // $buffer_settings = wpautoc_get_settings( array( 'social', 'buffer' ) );
            $token = $buffer->get_access_token();
            $settings['social']['buffer']['token'] = $token;
            wpautoc_save_plugin_settings( $settings );
        }
    }
}
?>