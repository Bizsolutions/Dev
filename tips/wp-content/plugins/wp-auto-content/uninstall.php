<?php
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

global $wpdb;

// delete options
$myplugin_options = array(  );

foreach( $myplugin_options as $myplugin_option ) {
	delete_option( $myplugin_option );
}
// $table_name = $wpdb->prefix . "blmachine_requests";
// $wpdb->query( "DROP TABLE $table_name");
?>