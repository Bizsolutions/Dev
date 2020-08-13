<?php
	@set_time_limit ( 360 );
	@ini_set('max_execution_time', 360 );
	require_once( '../../../wp-load.php' );

	if( isset( $_GET['do_debug'] ) )
		define( 'WPAUTOC_CRON_DEBUG', 1 );
	if( isset( $_GET['force_import'] ) )
		define( 'WPAUTOC_FORCE_IMPORT', 1 );
	if( isset( $_GET['cid'] ) )
		define( 'WPAUTOC_ONLY_CAMPAIGN', intval( $_GET['cid'] ) );
	do_action( 'wpautoc_campaign_importing' );
?>