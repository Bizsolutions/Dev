<?php
/**
 * Plugin Name: WP Auto Content
 * Plugin URI: http://wpautocontent.com/
 * Description: Get fresh new content for your WordPress site and monetize it, all on autopilot
 * Author: Ankur Shukla
   Version: 1.40
 * Author URI: https://mpresso.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WPAUTOC_VERSION', '1.40' );
if ( !isset( $wp_version ) )
    global $wp_version;
define( 'WPAUTOC_WPVERSION', $wp_version );

// define( 'WPAUTOC_DEBUG_MODE', 0 );

define( 'WPAUTOC_PLUGIN_NAME', dirname(plugin_basename(__FILE__) ) );
define( 'WPAUTOC_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPAUTOC_URL', plugins_url( WPAUTOC_PLUGIN_NAME ) );

include WPAUTOC_DIR.'/lib/common.php';
include WPAUTOC_DIR.'/lib/utils.php';
include WPAUTOC_DIR.'/lib/importing.php';
include WPAUTOC_DIR.'/lib/settings.php';
include WPAUTOC_DIR.'/lib/libs/autoresponders/autoresponders.php';
include_once WPAUTOC_DIR.'lib/traffic/traffic.php';

if ( is_admin() ) {
	include WPAUTOC_DIR.'/lib/ui.php';
	include WPAUTOC_DIR.'/lib/log.php';
	include WPAUTOC_DIR.'/lib/view/campaign_view.php';
	include WPAUTOC_DIR.'/lib/view/content_view.php';
	include WPAUTOC_DIR.'/lib/view/monetization_view.php';
	include WPAUTOC_DIR.'/lib/view/traffic_view.php';
	include WPAUTOC_DIR.'/lib/styles/dashboard.php';
    add_action( 'wp_ajax_wpautoc_remove_lead', 'wpautoc_remove_lead_ajax' );
}
else {
	include WPAUTOC_DIR.'lib/frontend.php';
	include WPAUTOC_DIR.'lib/data/campaign_data.php';
	include WPAUTOC_DIR.'lib/monetize/monetize.php';
	include WPAUTOC_DIR.'lib/data/monetize_data.php';
}

include_once WPAUTOC_DIR.'lib/monetize/optin.php';

add_action ( 'wp_ajax_wpac_optin_submit', 'wpautoc_add_optin_ajax' );
add_action ( 'wp_ajax_nopriv_wpac_optin_submit', 'wpautoc_add_optin_ajax' );

register_activation_hook ( __FILE__, 'wpautoc_activation' );

/* Automatic Updates */
require WPAUTOC_DIR.'/lib/plugin-updates/plugin-update-checker.php';
$puchcker = new Puc_v4p9_Plugin_UpdateChecker(
    ( wpautoc_is_pro() ? 'https://knighterrant.s3-us-west-2.amazonaws.com/software/wp-auto-content/plugin-updates/1b-pro/info_wpac1b.json' :
    'https://knighterrant.s3-us-west-2.amazonaws.com/software/wp-auto-content/plugin-updates/1-main/info_wpac1.json' ),
    WPAUTOC_DIR.WPAUTOC_PLUGIN_NAME.'.php'
);
?>