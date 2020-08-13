<?php

	function wpautoc_get_plugin_settings() {
		return get_option( 'wpautoc_settings' );
	}

	function wpautoc_save_plugin_settings( $settings ) {
		return update_option( 'wpautoc_settings', $settings );
	}

	function wpautoc_get_settings( $path = array() ) {
		$settings = wpautoc_get_plugin_settings();
		if( empty( $path ) )
			return array();
		$val = $settings;
		foreach( $path as $field ) {
			$val = isset( $val[ $field ] ) ? $val[ $field ] : false;
		}
		return $val;
	}
	/* Activation / Deactivation */

	function wpautoc_activation() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate ();

		$table_name = $wpdb->prefix . "autoc_campaigns";
		$sql1 = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			name varchar(128) NOT NULL,
			start_date date NOT NULL,
			end_date date DEFAULT NULL,
			per_day tinyint(4) NOT NULL DEFAULT '0',
		   	status tinyint(4) NOT NULL DEFAULT '1',
			settings text,
		   PRIMARY KEY id (id)
		   ) $charset_collate;";

		$table_name = $wpdb->prefix . "autoc_campaign_content";
		$sql2 = "CREATE TABLE IF NOT EXISTS $table_name (
		   	id int(11) NOT NULL AUTO_INCREMENT,
		   	campaign_id int(11) NOT NULL,
		   	type tinyint(4) NOT NULL DEFAULT '0',
		   	settings text,
		      PRIMARY KEY id (id)
	      	) $charset_collate;";

		$table_name = $wpdb->prefix . "autoc_campaign_traffic";
		$sql3 = "CREATE TABLE IF NOT EXISTS $table_name (
		   	id int(11) NOT NULL AUTO_INCREMENT,
		   	campaign_id int(11) NOT NULL,
		   	type tinyint(4) NOT NULL DEFAULT '0',
		   	settings text,
		      PRIMARY KEY id (id)
		  	) $charset_collate;";

		$table_name = $wpdb->prefix . "autoc_campaign_monetization";
		$sql4 = "CREATE TABLE IF NOT EXISTS $table_name (
		   	id int(11) NOT NULL AUTO_INCREMENT,
		   	campaign_id int(11) NOT NULL,
		   	type tinyint(4) NOT NULL DEFAULT '0',
		   	settings text,
		      PRIMARY KEY id (id)
		  	) $charset_collate;";

		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta ( $sql1 );
		dbDelta ( $sql2 );
		dbDelta ( $sql3 );
		dbDelta ( $sql4 );
	}



/* Ajax */
add_action ( 'wp_ajax_wpautoc_add_content', 'wpautoc_add_content_block_ajax' );
add_action ( 'wp_ajax_wpautoc_add_monetization', 'wpautoc_add_monetize_block_ajax' );
add_action ( 'wp_ajax_wpautoc_add_traffic', 'wpautoc_add_traffic_block_ajax' );
add_action ( 'wp_ajax_wpautoc_activate_campaign', 'wpautoc_activate_campaign_ajax' );
add_action ( 'wp_ajax_wpautoc_delete_campaign', 'wpautoc_delete_campaign_ajax' );
add_action ( 'wp_ajax_wpautoc_dismiss_notice', 'wpautoc_dismiss_notice_ajax' );
add_action ( 'wp_ajax_wpautoc_dismiss_notice2', 'wpautoc_dismiss_notice_ajax2' );

function wpautoc_dismiss_notice_ajax() {
	update_option( 'wpautoc-ndis', 1 );
	echo 1;
	exit();
}

function wpautoc_dismiss_notice_ajax2() {
	update_option( 'wpautoc2-ndis2', 1 );
	echo 1;
	exit();
}

function wpautoc_activate_campaign_ajax( ) {
	$campaign_id = isset( $_POST['campaign_id'] ) ? intval( $_POST['campaign_id'] ) : 0;
	$status = isset( $_POST['status'] ) ? intval( $_POST['status'] ) : 0;
	if( $campaign_id ) {
		wpautoc_activate_campaign( $campaign_id, $status );
	}
	echo "1";
	exit();
}

function wpautoc_delete_campaign_ajax( ) {
	$campaign_id = isset( $_POST['campaign_id'] ) ? intval( $_POST['campaign_id'] ) : 0;
	if( $campaign_id ) {
		wpautoc_delete_campaign( $campaign_id );
	}
	echo "1";
	exit();
}

function wpautoc_is_plugin_there($plugin_dir) {
	$plugins = get_plugins($plugin_dir);
	if ($plugins) return true;
	return false;
}

function wpautoc_get_the_excerpt( $post_id ) {
  global $post;
  $save_post = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt($post);
  $post = $save_post;
  return $output;
}

function wpautoc_get_content_by_id( $post_id ) {
	return apply_filters('the_content', get_post_field('post_content', $post_id));
}

function wpautoc_get_thumbnail( $post_id, $size = 'full' ) {
	$image_url = has_post_thumbnail( $post_id ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ) : false;
	if( $image_url ) {
		if( isset( $image_url[0] ) && !empty( $image_url[0] ) )
			return $image_url[0];
	}
	return false;
}

function wpautoc_short( $text, $max_len = 140 ) {
	return wpautoc_shorten_text( $text, $max_len, false );
}

function wpautoc_before_upload( $text ) {
	return html_entity_decode( $text );
}

function wpautoc_shorten_text( $input, $length = 140, $ellipses = true, $strip_html = true ) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    if($last_space !== false) {
        $trimmed_text = substr($input, 0, $last_space);
    } else {
        $trimmed_text = substr($input, 0, $length);
    }
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

function wpautoc_get_yesno() {
	return array( array( 'label' => 'No', 'value' => 0 ),
		array( 'label' => 'Yes', 'value' => 1 ) );
}

function wpautoc_get_poststatus() {
	return array( array( 'label' => 'Published', 'value' => 0 ),
		array( 'label' => 'Draft', 'value' => 1 ) );
}

function wpautoc_include_nextgen() {
	require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
	require_once WPAUTOC_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
	require_once WPAUTOC_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';
}

function wpautoc_debug( $text, $level = 'notice' ) {
	if( defined( 'WPAUTOC_CRON_DEBUG' ) )
		echo '<p>'.$text.'</p>';
}
/* enable sessions */
if ( !function_exists( 'wpautoc_session_enable' ) ) {
	function wpautoc_session_enable() {
	 	if(!session_id()){
	    	session_start();
    	}
    }
}
add_action( 'init','wpautoc_session_enable', 1 );

function wpautoc_is_monetize() {
	return function_exists( 'wpautocm_check_main_plugin' );
}

function wpautoc_is_traffic() {
	return function_exists( 'wpautoct_check_main_plugin' );
}

function wpautoc_is_pro() {
	// return true;
	return false;
}
?>