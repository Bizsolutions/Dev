<?php
/**
Plugin Name: WP Robot 5
Plugin URI: http://www.wprobot.net/
Version: 5.38
Description: Automatically post content related to any topic of your choice to your weblog.
Author: WP Robot
Author URI: http://www.wprobot.net/
License: Commercial. For personal use only. Not to give away or resell
Text Domain: wprobot
*/

error_reporting(E_ERROR);

$wpr5_version = "5.38";

define('wpr5_DIRPATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );
define('wpr5_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
define( 'EDD_WPR_STORE_URL', 'http://wprobot.net' );
define( 'EDD_WPR_ITEM_NAME', 'WP Robot 5' );

$wpr5_plugin_url = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function wpr5_edd_sl_sample_plugin_updater() {
	global $wpr5_version;
	$license_key = trim( get_option( 'wpr5_license_final' ) );
	$edd_updater = new EDD_SL_Plugin_Updater( EDD_WPR_STORE_URL, __FILE__, array(
			'version' 	=> $wpr5_version, 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => EDD_WPR_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Thomas Hoefter'  // author of this plugin
		)
	);
}
add_action( 'admin_init', 'wpr5_edd_sl_sample_plugin_updater', 0 );

add_action( 'init', 'myplugin_load_textdomain' );
function myplugin_load_textdomain() {
  load_plugin_textdomain( 'wprobot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

@require_once("info_sources_details.php");
@require_once("wpr_page_automation.php");
@require_once("wpr_page_curation.php");
@require_once("wpr_page_options.php");

if(file_exists(wpr5_DIRPATH."wpr_page_affbuilder.php")) {
	define( 'WPR5_AFFBUILDER_INSTALLED', true );
	@require_once("wpr_page_affbuilder.php");
}

/*if(function_exists("wpr5_affbuilder_page")) {
	define( 'WPR5_AFFBUILDER_INSTALLED', true );
}*/

@require_once("wpr_page_create_campaign.php");
@require_once("wpr_editor.php");
@require_once("wpr_shortcode.php");

function wpr5_delete_options_remote() {

	$lkey = get_option("wpr5_license_final");

	$api_params = array(
		'action' => "delete_options",
		'itemname' => EDD_WPR_ITEM_NAME,
		'license' => $lkey,
		'url' => home_url()
	);	
	
	$response = wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=delopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

	if ( is_wp_error( $response ) ) {
		return array("error" => $response->get_error_message());		
	}
	
	$result = json_decode( wp_remote_retrieve_body( $response ), true );

	if(!empty($result["error"])) {
		echo '<div class="updated error"><p>'.__('Error deleting options: ', 'wprobot').$options["error"].'</p></div>';
		return false;
	} else {
		return true;
	}
}

function wpr5_save_options_remote($options) {

	$lkey = get_option("wpr5_license_final");

	$api_params = array(
		'action' => "save_options",
		'itemname' => EDD_WPR_ITEM_NAME,
		'options' => addslashes(serialize($options)),
		'license' => $lkey,
		'url' => home_url()
	);	
	
	$response = wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=saveopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

	if ( is_wp_error( $response ) ) {
		return array("error" => $response->get_error_message());		
	}
	
	$result = json_decode( wp_remote_retrieve_body( $response ), true );
	//echo "LALAlALAALAL";print_r($response);
	
	if(!empty($result["error"])) {
		echo '<div class="updated error"><p>'.__('Error saving options: ', 'wprobot').$result["error"].'</p></div>';
		return false;
	} else {
		return true;
	}
}

function wpr5_import_options() { /*
	$lkey = get_option("wpr5_license_final");

	$api_params = array(
		'action' => "load_sites",
		'itemname' => EDD_WPR_ITEM_NAME,
		'license' => $lkey
	);	
	
	$response = wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=impopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

	if ( is_wp_error( $response ) ) {
		echo '<div class="updated error"><p>'.__('Error loading available sites: ', 'wprobot').$response->get_error_message().'</p></div>';
		return false;				
	}
	
	$result = json_decode( wp_remote_retrieve_body( $response ), true );
	//echo "LALAlALAALAL";print_r($result);
	
	if(!empty($result["error"])) {
		echo '<div class="updated error"><p>'.__('Error loading available sites: ', 'wprobot').$result["error"].'</p></div>';
		return false;
	} else {
		$vals = "";
		foreach($result as $siteurl) {
			$vals .= '<option value="'.$siteurl.'">'.$siteurl.'</option>';
		}
		
		// output load options form for other sites
		echo '<div class="updated"><p>'.__('Select a site below and click the import button to load its options:', 'wprobot').'</p>
		<form method="post" name="wpr5_importoptions" id="wpr5_importoptions-options">	
			<select name="site_to_import">'.$vals.'</select>			
			<input type="submit" value="Import" name="import_option_from_site" class="button-primary">
		</form>
		</div>';	
		return true;
	} */
}

function wpr5_load_options_remote($firstload = 0, $url = "") {

	$lkey = get_option("wpr5_license_final");

	if(empty($url)) {$url = home_url();}
	
	if(get_option("wpr5_is_trial") == true) {$is_trial = true;} else {$is_trial = false;}
	
	$api_params = array(
		'action' => "options",
		'itemname' => EDD_WPR_ITEM_NAME,
		'firstload' => $firstload,
		'sources' => $sources,
		'license' => $lkey,
		'url' => $url,
		'trial' => $is_trial
	);	
	
	//echo "<pre>";print_r($api_params);echo "</pre>";	
	//$response = wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=loadopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	
	$response = wp_remote_post( "http://wprobot.net/robotpal/new-opt-return/?r=loadopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	
	//echo "<pre>";print_r($response);echo "</pre>";	
	if ( is_wp_error( $response ) ) {
		echo '<div class="updated error"><p>'.__('Error loading available sites: ', 'wprobot').$response->get_error_message().'</p></div>';
		return false;		
	}

	if($firstload == 1) {
		$options = json_decode( wp_remote_retrieve_body( $response ), true );
	} else {
		$options = json_decode( wp_remote_retrieve_body( $response ), true );
		if(!is_array($options)) {
			$options =  unserialize( $options );
		}	
	}

	//echo "dadadada";print_r($response);
	
	if (strpos($options["error"], "Your license key is not valid or has expired") !== false) {
		update_option("wpr5_license_expired", true);
	}
	if (strpos($options["error"], "Your trial license has ended") !== false) {
		
		$esent = get_option("wpr5_trial_expired_email");
		if($esent != true) {
			//$headers[] = 'From: Thomas Hoefter <thomas@wprobot.net>';
			$email = get_bloginfo( "admin_email" );			
			$emsg = "Hi admin,\r\n\r\nthis is to notify you that the trial period of WP Robot 5 has ended on your site ".home_url()."\r\n\r\nTo continue using WP Robot and for autoposting to resume you need to upgrade to the full version here and then enter your license key: http://wprobot.net/order/ \r\n\r\nIf you have any questions or need help do not hesitate to get in touch with me here: http://wprobot.net/contact/\r\n\r\nBest regards,\nThomas Hoefter\r\nhttp://wprobot.net/";
			$tt = wp_mail( $email, 'Your WP Robot 5 trial period has ended. Upgrade now!', $emsg, $headers );				
			update_option("wpr5_trial_expired_email", true);	
		}
		update_option("wpr5_trial_expired", true);
	}	
	
	if(!empty($options["error"])) {
		echo '<div class="updated error"><p>'.__('Error loading options: ', 'wprobot').$options["error"].'</p></div>';
		return false;
	} else {
		return $options;
	}
}

function wpr5_load_options_beta() {
/*
	$lkey = get_option("wpr5_license");
	$url = home_url();
	
	$api_params = array(
		'action' => "options",
		'itemname' => "WP Robot 5 Beta",
		'firstload' => 0,
		'license' => $lkey,
		'url' => $url
	);	
	
	$response = wp_remote_post( "http://default-environment.kpeavsmdqj.us-west-2.elasticbeanstalk.com/aws-license-check/?r=loadopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

	if ( is_wp_error( $response ) ) {
		echo '<div class="updated error"><p>'.__('Error loading options: ', 'wprobot').$response->get_error_message().'</p></div>';
		return false;		
	}

	$options = json_decode( wp_remote_retrieve_body( $response ), true );
	if(!is_array($options)) {
		$options =  unserialize( $options );
	}	

	if(!empty($options["error"])) {
		echo '<div class="updated error"><p>'.__('Error loading options: ', 'wprobot').$options["error"].'</p></div>';
		return false;
	} else {
		return $options;
	}*/
}

function wpr5_get_and_delete_options($lkey) {

	$url = home_url();
	
	$api_params = array(
		'action' => "options_gad",
		'itemname' => EDD_WPR_ITEM_NAME,
		'license' => $lkey,
		'url' => $url
	);	
	
	$response = wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=loadanddelopt", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

	if ( is_wp_error( $response ) ) {
		echo '<div class="updated error"><p>'.__('Error loading options: ', 'wprobot').$response->get_error_message().'</p></div>';
		return false;		
	}

	$options = json_decode( wp_remote_retrieve_body( $response ), true );
	if(!is_array($options)) {
		$options =  unserialize( $options );
	}	

	if(!empty($options["error"])) {
		echo '<div class="updated error"><p>'.__('Error loading options: ', 'wprobot').$options["error"].'</p></div>';
		return false;
	} else {
		return $options;
	}
}

function wpr5_get_options() {

	$opt = get_option( 'wpr5_options' );

	if(!empty($opt) && is_array($opt)) {
		return $opt;
	}
	
	if(empty($opt)) {
		$opt = get_transient( 'wpr5_opt_transient' );
		if(!empty($opt) && is_array($opt)) {
			update_option('wpr5_options', $opt );
			return $opt;
		}
	}
	
	if(empty($opt)) {
		$opt = get_option( 'wpr5_opt_transient' );
		if(!empty($opt) && is_array($opt)) {
			update_option('wpr5_options', $opt );
			return $opt;
		}
	}
	
	if(!empty($opt) && is_array($opt)) {
		return $opt;
	} else {
		//$opt = wpr5_load_options_remote(0);
		//if(!empty($opt) && is_array($opt)) {update_option('wpr5_options', $opt );}
		return false;
	}
}

function wpr5_add_options($options) {
	delete_transient( 'wpr5_opt_transient' );
	return update_option('wpr5_options', $options);
	//return wpr5_save_options_remote($options);
}

function wpr5_update_options($options) {
	delete_transient( 'wpr5_opt_transient' );
	return update_option('wpr5_options', $options);
	//return wpr5_save_options_remote($options);
}

function wpr5_delete_options() {
	delete_option("wpr5_options");
	delete_transient( 'wpr5_opt_transient' );
	
	return 1;//wpr5_delete_options_remote();
}

function wpr5_verify_license_key($license, $nt = 0) {

	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode( EDD_WPR_ITEM_NAME ),
		'url'       => home_url()
	);

	$response = wp_remote_post( EDD_WPR_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) ) {
		return array("error" => $response->get_error_message());
	}	

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );	
	//print_r($license_data);

	if($license_data->license == "valid") {
	
		if($license_data->license_limit == 1 && $nt == 1) {
			return "nt";
		}

		if($license_data->license_limit == 1 && is_email( $license_data->customer_email )) {
			
			update_option("wpr5_is_trial", true);	
			$headers[] = 'From: Thomas Hoefter <thomas@wprobot.net>';
			$emsg = 'Hi ' .$license_data->customer_name. ",\r\n\r\nthank you for just installing the WP Robot 5 trial on your WordPress site at ".home_url()."\r\n\r\nOur trial version allows you to test all features of WP Robot 5 over the next 14 days. Afterwards the plugin will stop working but you can easily upgrade to the full version here and keep all your settings: http://wprobot.net/order/ \r\n\r\nSee here to find the online documentation for WP Robot 5: http://wprobot.net/wp-robot-5-documentation\r\n\r\nShould you have any questions or run into any problems with using WP Robot you can reach me by replying to this email.\r\n\r\nBest regards,\nThomas Hoefter\r\nhttp://wprobot.net/";
			$tt = wp_mail( $license_data->customer_email, 'Thanks for activating your WP Robot 5 trial.', $emsg, $headers );			
		}
	
		return true;
	} elseif($license_data->error == "no_activations_left") {
		return array("error" => "You have reached the site activation limit for your license. Please either uninstall WP Robot 5 on another site before activating it here or contact support about upgrading your license.");
	} else {
		return false;
	}
}
	
function wpr5_check_license_key() {
	global $wpr5_source_infos;

	if(isset($_POST["wpr_license_save"]) && check_admin_referer( 'cmsc-license-form')) {

		$lkey = sanitize_text_field($_POST["wpr_license_key"]);
		if(!empty($lkey)) {
			$verify = wpr5_verify_license_key($lkey);
			if($verify === true) {
				update_option("wpr5_license_final", $lkey);
				delete_option("wpr5_license_expired");
				echo '<div class="updated"><p>'.__('Thank you! The installation of WP Robot 5 is now complete.', 'wprobot').'</p>
				<p>'.__('<strong>Next step:</strong> Please go to the "<a href="admin.php?page=wpr5-options">Options</a>" page. There you can activate the content sources you intend to use on this site and configure your settings for them.', 'wprobot').'</p>
				</div>';
				
				// save first options
				$options = array();

				foreach($wpr5_source_infos["sources"] as $module => $moduledata) {
					$options["options"][$module]["name"] = $moduledata["name"];
					$options["options"][$module]["disabled"] = 1; // ...disable all modules...
				}

				wpr5_add_options($options);				
				
			} elseif(!empty($verify["error"])) {	
				echo '<div class="updated error"><p>'.__('Error: ', 'wprobot').$verify["error"].'</p></div>';
			} else {
				echo '<div class="updated error"><p>'.__('Error: Your license key could not be verified. Please make sure to copy it exactly from the email you received. If the problem persists please contact support.', 'wprobot').'</p></div>';
			}			
		} else {
			echo '<div class="updated error"><p>'.__('Error: Please enter your license key.', 'wprobot').'</p></div>';
		}
	}
	
	if(isset($_POST["wpr_license_replace"]) && check_admin_referer( 'cmsc-license-form')) {

		$lkey = sanitize_text_field($_POST["wpr_license_key"]);
		if(!empty($lkey)) {
			$verify = wpr5_verify_license_key($lkey);
			if($verify === true) {
				update_option("wpr5_license_final", $lkey);
				delete_option("wpr5_license_expired");
				delete_option("wpr5_is_trial");	
				
				// options import
				//$options = wpr5_load_options_beta();						
				//if(is_array($options)) {wpr5_update_options($options);}
				
				delete_option("wpr5_license");
				echo '<div class="updated"><p>'.__('Thank you! The installation of WP Robot 5 is now complete.', 'wprobot').'</p></div>';
			} elseif(!empty($verify["error"])) {	
				echo '<div class="updated error"><p>'.__('Error: ', 'wprobot').$verify["error"].'</p></div>';
			} else {
				echo '<div class="updated error"><p>'.__('Error: Your license key could not be verified. Please make sure to copy it exactly from the email you received. If the problem persists please contact support.', 'wprobot').'</p></div>';
			}			
		} else {
			echo '<div class="updated error"><p>'.__('Error: Please enter your license key.', 'wprobot').'</p></div>';
		}
	}

	if(isset($_POST["wpr_license_renew"]) && check_admin_referer( 'cmsc-license-form')) {
		$lkey = sanitize_text_field($_POST["wpr_license_key"]);
		$oldkey = get_option("wpr5_license_final");
		if(!empty($lkey)) {
			$verify = wpr5_verify_license_key($lkey, 1);

			if($verify === true) {
				update_option("wpr5_license_final", $lkey);
				delete_option("wpr5_license_expired");
				delete_option("wpr5_trial_expired");
				delete_option("wpr5_is_trial");	
				
				// options import
				if($lkey != $oldkey) {
					$options = wpr5_get_and_delete_options($oldkey);					
					if(is_array($options)) {wpr5_update_options($options);}				
				}
				
				echo '<div class="updated"><p>'.__('Thank you! Your license has been renewed and you can continue to use WP Robot.', 'wprobot').'</p></div>';
			} elseif($verify === "nt") {	
				echo '<div class="updated error"><p>'.__('Error: You cannot use another trial key on this site. Please enter a full version license.', 'wprobot').'</p></div>';
			} elseif(!empty($verify["error"])) {	
				echo '<div class="updated error"><p>'.__('Error: ', 'wprobot').$verify["error"].'</p></div>';
			} else {
				echo '<div class="updated error"><p>'.__('Error: Your license key could not be verified. Please make sure to copy it exactly from the email you received. If the problem persists please contact support.', 'wprobot').'</p></div>';
			}			
		} else {
			echo '<div class="updated error"><p>'.__('Error: Please enter your license key.', 'wprobot').'</p></div>';
		}
	}

	$lkey = get_option("wpr5_license_final");
	
	$betakey = get_option("wpr5_license");

	$license_expired = get_option("wpr5_license_expired");
	$trial_expired = get_option("wpr5_trial_expired");
		
	if(empty($lkey) && !empty($betakey)) {
		echo '<div class="updated"><h3>'.__('Beta To Full Version Upgrade', 'wprobot').'</h3><p>'.__('Thank you for participating in the WP Robot 5 beta. The <strong>beta test has now ended</strong> and to continue using WP Robot 5 you need to upgrade to the launch version by entering your new license key below. If you do not have one <a href="http://wprobot.net/order/">you can obtain one here</a>.', 'wprobot').'</p>
		<p><form method="post" name="wpr5_import" id="fluency-wpr5_import">'.wp_nonce_field( 'cmsc-license-form' ).'
		<input placeholder="'.__('Your License Key', 'wprobot').'" type="text" value="" name="wpr_license_key" style="background:#fff;" size="40">
		<input type="submit" value="Save" name="wpr_license_replace" class="button-primary"></form></p></div>';	
		die();	
	} elseif(empty($lkey)) {	
		echo '<div class="updated" style="display:block;"><h3>'.__('Finish Installation', 'wprobot').'</h3><p>'.__('Thank you for activating WP Robot 5! To finish the installation please enter your license key below and press the save button.', 'wprobot').'</p>
		<p><form method="post" name="wpr5_import" id="fluency-wpr5_import">'.wp_nonce_field( 'cmsc-license-form' ).'
		<input placeholder="'.__('Your License Key', 'wprobot').'" type="text" value="" name="wpr_license_key" style="background:#fff;" size="40">
		<input type="submit" value="Save" name="wpr_license_save" class="button-primary"></form></p></div>';	
		die();
	} elseif($license_expired == true) {
		echo '<div class="updated"><h3>'.__('Renew Your License', 'wprobot').'</h3><p>'.__('Your license key has expired. Please <a href="http://wprobot.net/checkout/?edd_license_key='.$lkey.'&download_id=9456" target="_blank">renew your license for WP Robot 5 here</a> and then press the button below. Alternatively you can also enter a new license key below.' , 'wprobot').'</p>
		<p><form method="post" name="wpr5_import" id="fluency-wpr5_import">'.wp_nonce_field( 'cmsc-license-form' ).'
		<input placeholder="'.__('Your License Key', 'wprobot').'" type="text" value="'.$lkey.'" name="wpr_license_key" style="background:#fff;" size="40">
		<input type="submit" value="Save" name="wpr_license_renew" class="button-primary"></form></p></div>';	
		die();
	} elseif($trial_expired == true) {
		echo '<div class="updated"><h3>'.__('Renew Your License', 'wprobot').'</h3><p>'.__('Your WP Robot 5 trial period has ended. Please <a href="http://wprobot.net/order/" target="_blank">order the full version of WP Robot 5 here</a> to continue using the plugin.' , 'wprobot').'</p>
		<p><form method="post" name="wpr5_import" id="fluency-wpr5_import">'.wp_nonce_field( 'cmsc-license-form' ).'
		<input placeholder="'.__('Your License Key', 'wprobot').'" type="text" value="" name="wpr_license_key" style="background:#fff;" size="40">
		<input type="submit" value="Save" name="wpr_license_renew" class="button-primary"></form></p></div>';	
		die();
	}

}

function wpr5_plugin_init () {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'wprobot', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );
}
add_action('init', 'wpr5_plugin_init');

$wpr5_table_posts = $wpdb->prefix . "wpr5_posts";	
//$wpr5_table_errors = $wpdb->prefix . "wpr5_errors";	
$wpr5_table_posted = $wpdb->prefix . "wpr5_history";

$wpr5_file   = basename( __FILE__ );
$wpr5_folder = basename( dirname( __FILE__ ) );
$wpr5_plugin_dir = WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__));



function wpr5_activate() {

	wp_schedule_event(time(), 'sixmin', 'wpr5_autoposting');
	
    global $wpdb, $wpr5_table_posts, $wpr5_table_posted;// $wpr5_table_errors
   
	$wpr5_table_posts = $wpdb->prefix . "wpr5_posts";	
	//$wpr5_table_errors = $wpdb->prefix . "wpr5_errors";	
	$wpr5_table_posted = $wpdb->prefix . "wpr5_history";   
   
   $wpr5_db_ver = 504;
   
	if(get_option('wpr5_db_ver') != $wpr5_db_ver) {

		if ( !empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

		$sql[] = "CREATE TABLE ".$wpr5_table_posts." (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			campaign BIGINT(20) NOT NULL,
			keyword VARCHAR(255) NOT NULL,		
			module VARCHAR(255) NOT NULL,
			unique_id longtext NOT NULL,
			time VARCHAR(255) NOT NULL
			) {$charset_collate};";		

		$sql[] = "CREATE TABLE ".$wpr5_table_posted." (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			cid BIGINT(20) NOT NULL,
			pid BIGINT(20) NOT NULL,
			module_errors longtext NOT NULL,
			title VARCHAR(255) NOT NULL,
			keyword VARCHAR(255) NOT NULL,
			template VARCHAR(25) NOT NULL,
			time VARCHAR(255) NOT NULL
			) {$charset_collate};";			

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);	
		
		update_option('wpr5_db_ver',$wpr5_db_ver);
	
	}		
}
register_activation_hook(__FILE__, 'wpr5_activate');

function wpr5_add_pages_toadmin() {

    add_menu_page('WP Robot 5', 'WP Robot 5', "manage_options", 'wpr5-automation', 'wpr5_automation_page', 'none');
    $autohook = add_submenu_page('wpr5-automation', __('Campaigns', 'wprobot'), __('Campaigns', 'wprobot'), "manage_options", 'wpr5-automation', 'wpr5_automation_page');
 	if(isset($_GET['page']) && $_GET['page'] == 'wpr5-automation' ) {
		add_action('admin_head', 'wpr5_automation_page_head');	
		add_action( "admin_print_scripts-$autohook", 'wpr5_automation_page_print_scripts' );	
	}   
	
	$ccphook = add_submenu_page('wpr5-automation', __('Create Campaign', 'wprobot'), __('Create Campaign', 'wprobot'), "manage_options", 'wpr5-create-campaign', 'wpr5_create_campaign_page');
	if(isset($_GET['page']) && $_GET['page'] == 'wpr5-create-campaign' ) {
		add_action('admin_head', 'wpr5_create_campaign_page_head');	
		add_action( "admin_print_scripts-$ccphook", 'wpr5_create_campaign_page_print_scripts' );	
	}		

    $curatehook = add_submenu_page('wpr5-automation', __('Curation', 'wprobot'), __('Curation', 'wprobot'), "manage_options", 'wpr-curation', 'wpr5_curation_page');	
	if(isset($_GET['page']) && $_GET['page'] == 'wpr-curation' ) {
		add_action('admin_head', 'wpr5_curation_page_head');	
		add_action( "admin_print_scripts-$curatehook", 'wpr5_curation_page_print_scripts' );	
	}	

	if(WPR5_AFFBUILDER_INSTALLED === true) {
		$affehook = add_submenu_page('wpr5-automation', __('Comparisons', 'wprobot'), __('Comparisons', 'wprobot'), "manage_options", 'wpr-affbuilder', 'wpr5_affbuilder_page');	
		if(isset($_GET['page']) && $_GET['page'] == 'wpr-affbuilder' ) {
			add_action('admin_head', 'wpr5_affbuilder_page_head');	
			add_action( "admin_print_scripts-$affehook", 'wpr5_affbuilder_page_print_scripts' );	
		}		
	}	
	
	$disablehook = add_submenu_page('wpr5-automation', __('Options', 'wprobot'), __('Options', 'wprobot'), "manage_options", 'wpr5-options', 'wpr5_options_page');
	if(isset($_GET['page']) && $_GET['page'] == 'wpr5-options' ) {
		add_action('admin_head', 'wpr5_disable_page_head');	
		add_action( "admin_print_scripts-$disablehook", 'wpr5_disable_page_print_scripts' );	
	}


	
    //add_submenu_page('wpr5-automation', '', '', "manage_options", 'wpr-single', 'wpr5_single');	
		
}
add_action('admin_menu', 'wpr5_add_pages_toadmin');

add_action( 'admin_head', 'wpr5_option_page_icon' );
function wpr5_option_page_icon() {
    ?>
    <style>        

        #wprobot.icon32 {
			float: left;
			height: 33px;
			margin: 9px 8px 0 0;
			width: 36px;		
			display: block;
			background-position: -137px -5px;
            background: url(<?php echo wpr5_URLPATH; ?>images/icon-adminpage32.png) no-repeat left top !important;
        }
    </style>
<?php }

function wpr5_create_posts($args) {

	require_once("classes/Comments.php");
	require_once("classes/Post.php");

	$po = new WPR5_Post(); 
	$ids = array();		
	foreach($args["post"] as $arg) {
		$id = $po->create($arg);

		if(is_array($id) && !empty($id["error"])) {
			return $id;
		}
		
		$ids[] = $id;
		
		if(!empty($arg['comments']) && is_numeric($id)) {
			$com = new WPR5_Comments(); 
			foreach($arg['comments'] as $comment) {					
				$cid = $com->create(array("post_id" => $id, "postdate" => "", "comment" => array("content" => $comment["content"], "author" => $comment["author"])));
			}
		}
		
		if(!empty($arg['replies']) && is_numeric($id)) {
			
			$post = get_post($id);
			$title="Reply To: " . $post->post_title;
			foreach($arg['replies'] as $comment) {		
			
				$my_post = array(
						 'post_title' => $title,
						 'post_content' => $comment["content"],
						 'post_status' => 'publish',
						 'post_author' => $comment["author"],
						 'post_type' => 'reply',
						 'post_date' => $arg['post_date'],
						 'post_date_gmt' => $arg['post_date_gmt'],
						 'post_parent' => $post->ID
				); 				
			
				$args2 = array();
				$args2['post_data']['post_data'] = $my_post;	
				$args2['post_data']['content'] = $my_post;				
				$repl = $po->create($args2);
			}
		}
	}
	return array("success" => $ids);	
}

add_action('wpr5_autoposting', 'wpr5_autopost_function');
function wpr5_autopost_function($log = 0) {

	$upd = 0;
	$climit = 2;$ccount = 0;
	$campaigns = get_option("wpr5_campaigns");
	
	$is_expr = get_option("wpr5_license_expired");
	$is_expr_trial = get_option("wpr5_trial_expired");
	
	if($is_expr == true || $is_expr_trial == true) {die();}
	$cran = array();	
	
	foreach($campaigns as $cid => $campaign) {	
		if($campaign["paused"] == 1) {continue;}
	
		$lastrun = $campaign["last_run"];
		$interval = $campaign["main"]["interval"];
		$percentage = $campaign["main"]["perc"];
		if(empty($percentage)) {$percentage = 100;}

		
		$period = $campaign["main"]["period"];
		$postnum = $campaign["main"]["num"];
		if(empty($postnum)) {$postnum = 1;}
		
		if($log == 1) {echo "--- CID $cid LAST: $lastrun INT: $interval PER: $period <br>";}
		
		if($period == "hours") {
			$intv = $interval * 3600;
		} elseif($period == "minutes") {
			$intv = $interval * 60;		
		} else {
			$intv = $interval * 3600 * 24;
		}
		
		if(empty($lastrun) || $intv + $lastrun < time()) {
			// RUN AUTOPOSTING
			$ccount++;
			
			if(!empty($percentage) && is_numeric($percentage) && $percentage < 100) {
				$prand = rand(1, 100);
				if($prand < $percentage) {
					$cran[] = $cid;
					continue;
				}
			}
			
			if($log == 1) {echo "--- run campaign <br>";}			
			for ($i = 1; $i <= $postnum; $i++) {
				$result = wpr5_run_campaign($cid, 0);
			}

			//if($result["reason"] != "duplicate") {
				$upd = 1;
				$cran[] = $cid;
			//}

			if($ccount > $climit) {break;}
		}
	}
	
	if($upd == 1) {
		$campaigns = get_option("wpr5_campaigns");
		foreach($cran as $ucid) {
			if($log == 1) {echo "--- updateing $ucid <br>";}	
			$campaigns[$ucid]["last_run"] = time();		
		}
		if($log == 1) {echo "--- update options <br>";}			
		update_option("wpr5_campaigns", $campaigns);
	}
}

function wpr5_cron_schedule( $schedules ) {
	$schedules['sixmin'] = array(
		'interval' => 180,
		'display' => __('Every Six Minutes')
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'wpr5_cron_schedule' ); 

function wpr5_update_errors($id, $error_log) {

	global $wpdb, $wpr5_table_posted;
	
	if(empty($error_log) || !is_array($error_log)) {return false;} else {
		if(!empty($error_log["commissionjunction"])) {$error_log["commissionjunction"] = (array) $error_log["commissionjunction"];$error_log["commissionjunction"] = $error_log["commissionjunction"][0];}

		$error_log = serialize($error_log);
		$wpdb->update(
			$wpr5_table_posted, 
			array( 
				'module_errors' => $error_log, 
			),
			array( 'time' => $id ) 
		);			
	}
}

function wpr5_update_post_status($id, $savekw, $savetpl, $reason, $status, $message, $display = 0, $pid = 0) {

	global $wpdb, $wpr5_table_posted;

	$wpdb->update(
		$wpr5_table_posted, 
		array( 
			'pid' => $pid, 
			'keyword' => $savekw, 
			'template' => $savetpl, 			
			'title' => $status 
		),
		array( 'time' => $id ) 
	);
	
	if(!empty($pid)) {
		if($display == 1) {
			echo '<div class="updated error"><p>'.$message.'</p></div>';		
		}	
		return array("success" => true, "pid" => $pid);
	}

	if($display == 1) {
		echo '<div class="updated error"><p>'.$message.'</p></div>';		
	} else {
		return array("error" => $message, "reason" => $reason);
	}
	
	return false;
}

function wpr5_check_unique($unique) {
	global $wpdb,$wpr5_table_posts;
	
	if(empty($unique)) {return false;}
	
	$unique = $wpdb->escape($unique);
	$check = $wpdb->get_var("SELECT unique_id FROM ".$wpr5_table_posts." WHERE unique_id = '$unique' LIMIT 1");

	if($check != false) {
		return $check;
	} else {
		return false;			
	}
}

function wpr5_run_campaign($cid, $debug = 0) {
	global $wpdb, $wpr5_source_infos, $wpr5_table_posts, $wpr5_table_errors, $wpr5_table_posted;

	$options = wpr5_get_options();
	$campaigns = get_option("wpr5_campaigns");
	$campaign = $campaigns[$cid];
	
	if($options === false) {
		return array("error" => "Options could not be loaded.");	
	}
	
	if(empty($campaign)) {
		return array("error" => "Campaign not found.");
	}
	
	if(empty($campaign["keywords"]) && empty($campaign["feeds"])) {
		return array("error" => "Campaign has no keywords.");
	}	
	
	if(empty($campaign["templates"])) {
		return array("error" => "Campaign has no templates.");
	}		
	
	// insert 
	$runtime = time();
	$wpdb->insert( 
		$wpr5_table_posted, 
		array( 
			'cid' => $cid, 
			'time' => $runtime 
		)
	);		

	if(!empty($campaign["feeds"]) && is_array($campaign["feeds"])) {
		$keywords = $campaign["feeds"];
		$rndkw = array_rand($keywords);	
		$keyword = $keywords[$rndkw];	// array, with name, count and category		
		$feed = $keyword["feed"];
	} else {
		$keywords = $campaign["keywords"];
		$rndkw = array_rand($keywords);	
		$keyword = $keywords[$rndkw];	// array, with name, count and category	
	}

	$savekw = $keyword["name"];

	$categories = "";
	if(is_array($keyword["category"])) {
		foreach($keyword["category"] as $cat) {
			$categories .= $cat["name"].",";
		}
	}
	$categories = rtrim($categories, ",");
	
	if(empty($categories) && !empty($campaign["main"]["category"]) && $campaign["main"]["category"] != "multi") {
		$categories = $campaign["main"]["category"];
		
		if(stripos($categories, "id:") !== false) {
			$category_ID = trim(str_replace("id:", "", $categories));
			$cid = get_term_by( "id", $category_ID, $cat_tax, ARRAY_A );	
			$categories = $cid["name"];			
		}
	}
	
	if(empty($categories)) {
		if(is_array($keywords[0]["category"])) {
			foreach($keywords[0]["category"] as $cat) {
				$categories .= $cat["name"].",";
			}
		}
		$categories = rtrim($categories, ",");	
	}
	
	$kw_counts = $keyword["count"];
	if(empty($kw_counts)) {$kw_counts = array();}
	
	if($debug == 1) {echo "<br> --- Keyword --- <br/>";print_r($keyword);}
	
	$templates = $campaign["templates"];
	$rndtm = array_rand($templates);
	$savetpl = $rndtm;
	$template = $templates[$rndtm];	// array, with content, title
	
	if(empty($template)) {
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "no content", "No content found.", __('Error: No template found. Edit your campaign and make sure at least one post template is added.', 'wprobot'));
	}
	
	if($debug == 1) {echo "<br> --- Template --- <br/>";print_r($template);}
	
	require_once("api.class.php");	
	
	$maintitle = $template["title"];
	$content = $template["content"];
	
	$raz[0] = "{";$raz[1] = "}";

	$finished = 0;$retry = 1;
	$error_log = array();$unique_log = array();$module_count = 0;$duplicate_log = array();$title_modules = array();$comment_array = array();$cfarray = array();
	while ($finished != 1) {

		preg_match_all("/\\".$raz[0]."[^\\".$raz[1]."]+\\".$raz[1]."/s", $content, $matches);

		if(empty($matches[0])) {
			$finished = 1;
			break;
		}	
		
		if($debug == 1) {echo "<br> ### API CALL RETRY $retry ### <br/>";}		
			
		foreach($matches[0] as $mid => $match) {
			$match2 = str_replace(array("{", "}"), "", $match);
			$matchx = explode("|", $match2);
			
			// check if ($matchx[0] is valid module else continue/unset
			$modmch = $matchx[0];
			if(!is_array($wpr5_source_infos["sources"][$modmch])) {
				if($debug == 1) {echo "<br> --- module not found: $modmch  --- <br/>";}
				unset($matches[0][$mid]);
				continue;
			}
			
			// RANDOM MODULE SETTING
			$randval = (int) $matchx[1];
			if(!empty($randval) && $randval < 100) { // random setting
				if(rand(0, 100) > $matchx[1]) { // remove...
					unset($matches[0][$mid]);
					$pos = strpos($content, $match);
					if ($pos !== false) {
						$content = substr_replace($content, "", $pos, strlen($match));
					}				
					continue;
				} else {
					$replacenum = $matchx[0] . "|" . $matchx[1] . "|" . $matchx[2];
					$replacewith = $matchx[0] . "|100|" . $matchx[2];
					$pos = strpos($content, $replacenum);
					if ($pos !== false) {
						$content = substr_replace($content, $replacewith, $pos, strlen($replacenum));
					}
				}
			}
			
			// MODULE KEYWORD TYPE SETTING
			if($matchx[2] == "title") {
				$title_modules[$modmch] = array("start" => 1, "count" => 10);
				unset($matches[0][$mid]);
				continue;
			}
			
			$matches[0][$mid] = $matchx[0];
		}
		
		if($module_count == 0) {
			$module_count = count($matches[0]);
			if($debug == 1) {echo "<br> --- Module Count --- <br/>";print_r($module_count);}			
		}		
		
		if($debug == 1) {echo "<br> --- Matches --- <br/>";print_r($matches);}		
		$counts = array_count_values  (  $matches[0]  ); //echo $counts["{amazon}"];		
		if($debug == 1) {echo "<br> --- Matches Count --- <br/>";print_r($counts);}		
		
		$module_request = array();
		foreach($counts as $mod => $mcount) {
			if(empty($kw_counts[$mod])) {$tstart = 1;} else {$tstart = $kw_counts[$mod];}
			$module_request[$mod] = array("count" => $mcount, "start" => $tstart);
			if($mod == "rss") {
				$module_request[$mod]["feed"] = $feed;
			}
		}

		if($debug == 1) {echo "<br> --- Matches Count + Start --- <br/>";print_r($module_request);}		

		$overrider = array();
		if(!empty($campaign["override"]) && is_array($campaign["override"])) {			
			foreach($campaign["override"] as $mod => $settings) {
				foreach($settings as $set => $val) {
					$overrider[$mod . "_" . $set] = $val;
				}	
			}
		}
		
		if(!empty($keyword["ytnxt"])) {
			$overrider["youtube_next"] = $keyword["ytnxt"];
		}
		
		$api = new API_request();
		if($debug === 1) {$api->debug = 1;}
		$contents = $api->api_content_bulk($keyword["name"], $module_request, $overrider, "", "", $campaign["settings"]["cfs"]); 
		//if($debug == 1) {echo "<br> --- CONTNT --- <br/>";print_r($contents);}
		
		if(empty($contents) || !is_array($contents)) {
			$retry++;
			if($retry > 2) {$finished = 1;$unfinished = 1;$nocontent = 1;}		
			continue;					
		}
		
		foreach($contents as $module => $result) {
		
			if(!empty($result["duplicates"])) {
				// duplicate result handling
				$duplicate_log[$module]++;
				if($retry > 2) {$error_log[$module] = __('Skipping module because of duplicate content. Will retry next time', 'wprobot');}
				$kw_counts[$module] = $kw_counts[$module] + 1;
	
				if($module == "youtube" && !empty($result["ytnext"])) {
					$ytnextpage = $result["ytnext"];
					$keyword["ytnxt"] = $result["ytnext"];
				}
		
			} elseif(!empty($result["error"])) {
				//$error_log[] = array("module" => $module, "error" => $result["error"]);
				$error_log[$module] = $result["error"];
				if($debug == 1) {echo "<br> --- error --- <br/>".$result["error"];}	

				if($retry == 3) {
					// remove module tag
					$needle = "{" . $module . "|100|campaign}";
					$pos = strpos($content, $needle);
					if ($pos !== false) {
						$content = substr_replace($content, "", $pos, strlen($needle));
					}					
				}
			} else {
				$save_comments = 0;
				foreach($result as $res) {
					$uid = $res["unique"];
					$title = $res["title"];
					
					if($module == "amazon" && $options["options"]["amazon"]["options"]["shortcode"]["value"] == 1) {
						preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $res["content"], $image);

						$regn = $options["options"]["amazon"]["options"]["region"]["value"];
						$modcontent = '[wpr5-amazon asin="'.$uid.'" region="'.$regn.'"]';
						if(!empty($image['src']) && strpos($image['src'],"remote-buy-box") === false) {
							$modcontent .= ' <div style="display:none;"><img src="'.$image['src'].'" /></div>';
						}
		
					} else {
						$modcontent = $res["content"];
					}
	
					if($module == "youtube" && !empty($res["ytnext"])) {$ytnextpage = $res["ytnext"];}
					
					//$dcheck = wpr5_check_unique($uid);
					//if($dcheck == false) {
					
						// remove cfields
						if(!empty($modcontent) && strpos($modcontent, "[customfields:") !== false) {
							$modcontent = explode("[customfields:", $modcontent);
							$modcontent = $modcontent[0];
						}
						
						$needle = "{" . $module . "}";
						$pos = strpos($content, $needle);
						if ($pos !== false) {
							// title module
							$title_module_kw = $title;
							$maintitle = str_replace("{title}", $title, $maintitle);
							$content = substr_replace($content, $modcontent, $pos, strlen($needle));
							$unique_log[] = array("module" => $module, "uid" => $uid);
							$kw_counts[$module] = $kw_counts[$module] + 1;
							$save_comments = 1;
						} else {
							$needle = "{" . $module . "|100|campaign}";
							$pos = strpos($content, $needle);
							if ($pos !== false) {
								$content = substr_replace($content, $modcontent, $pos, strlen($needle));
								$unique_log[] = array("module" => $module, "uid" => $uid);
								$kw_counts[$module] = $kw_counts[$module] + 1;
								$save_comments = 1;
							}
						}					
					/*} else {
						$kw_counts[$module] = $kw_counts[$module] + 1;
						if($debug == 1) {echo "<br> --- duplicate skipped for $module  --- <br/>";}						
						$duplicate_log[$module]++;
					}*/
				}			
				if($save_comments == 1 && !empty($res["comments"]) && is_array($res["comments"])) {					// SAVE COMMENTS			
					foreach($res["comments"] as $comm) {
						if(!empty($comm["author"]) && !empty($comm["content"])) {
							$comment_array[] = $comm;
						}
					}	
				}
		
				if(!empty($res["customfields"]) && is_array($res["customfields"])) {			
					foreach($res["customfields"] as $customfields) {
						if(!empty($customfields["name"]) && !empty($customfields["value"]) && strpos($customfields["value"], "{") === false) {
							$cfarray[] = array("name" => $customfields["name"], "value" => $customfields["value"]);
						}
					}	
				}				
			}
		}	
		
		
		$retry++;
		if($retry > 2) {$finished = 1;$unfinished = 1;}
	}
	
	// SAVE YOUTUBE NEXT PAGE
	if(!empty($ytnextpage)) {
		$campaigns[$cid]["keywords"][$rndkw]["ytnxt"] = $ytnextpage;	
		update_option("wpr5_campaigns", $campaigns);	
	}		


	// SEARCH AND REPLACE TITLE MODULES
	if(!empty($title_module_kw) && !empty($title_modules)) {
		if($debug == 1) {echo "<br> --- TITLE MODULE REQUESTS --- <br/>".$title_module_kw;print_r($title_modules);}
		$contents2 = $api->api_content_bulk($title_module_kw, $title_modules);

		// TODO: better error handling if request fails
		
		if(is_array($contents2)) {
			foreach($contents2 as $module => $result) {
				if(!empty($result["error"])) {
					$needle = "{" . $module . "|100|title}";
					$pos = strpos($content, $needle);
					if ($pos !== false) {
						$content = substr_replace($content, "", $pos, strlen($needle));
					}					
				} else {
					foreach($result as $res) {

						$modcontent = $res["content"];
						
						$needle = "{" . $module . "|100|title}";
						$pos = strpos($content, $needle);
						if ($pos !== false) {
							$content = substr_replace($content, $modcontent, $pos, strlen($needle));
						}					
					}
				}
			}	
		}
	}
	
	if($debug == 1) {echo "<br> --- CONTNT --- <br/>".$content;}	

	// SAVE ERROR LOG
	wpr5_update_errors($runtime, $error_log);		
	
	if($unfinished == 1) {

		if(!empty($duplicate_log)) {
			foreach($duplicate_log as $dmod => $dco) {
				if(!empty($dmod) && is_numeric($dco) && $dco > 0) {
					$kw_counts[$dmod] = $kw_counts[$dmod] + $dco;
				}
			}
			if(!empty($feed)) {
				$campaigns[$cid]["feeds"][$rndkw]["count"] = $kw_counts;			
			} else {
				$campaigns[$cid]["keywords"][$rndkw]["count"] = $kw_counts;			
			}

			update_option("wpr5_campaigns", $campaigns);

			if(empty($unique_log)) {
				return wpr5_update_post_status($runtime, $savekw, $savetpl, "duplicate", "Duplicate content protection", __('Skipping post because all content was already posted to your site. Will retry next time.', 'wprobot'));		
			}
		}
		
		if($nocontent == 1) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "no content", "No content found.", __('Error: No content found.', 'wprobot'));		
		}
		
		if(strpos($maintitle, "{title}") !== false) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "main module failed", "Title module request failed", __('Error: Post could not be created because main module failed.', 'wprobot'));		
		}
		
		if(count($error_log) >= $module_count) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "main module failed", "Title module request failed", __('Error: Post could not be created.', 'wprobot'));		
		}
		
		if(empty($unique_log)) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "no content", "No content found", __('Error: Skipping post because no content was found.', 'wprobot'));				
		}		
	}
	
	// REQUIRE + EXCLUDE
	$reqfound = 0;
	$requires = $campaign["settings"]["require"];
	
	if(!empty($requires) && is_array($requires)) {	
		foreach($requires as $require) {
			if(!empty($require)) {$eee = 1;}
			if (stripos($content, $require) !== false || stripos($maintitle, $exclude) !== false) {
				$reqfound = 1;$reqkw = $require;
			}
		}
		if($eee != 1) {$reqfound = 1;}
	} else {$reqfound = 1;}
	
	if($reqfound != 1) {
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "required not found", "Required keyword not found.", __('Post skipped because required keyword has not been found.', 'wprobot'));				
	}
	
	$xclfound = 0;
	$excludes = $campaign["settings"]["exclude"];
	if(!empty($excludes) && is_array($excludes)) {	
		foreach($excludes as $exclude) {
			if (stripos($content, $exclude) !== false || stripos($maintitle, $exclude) !== false) {
				$xclfound = 1;$xclkw = $exclude;
			}
		}
	}
	if($xclfound == 1) {
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "excluded found", "Excluded keyword was found.", __('Post skipped because exclude keyword has been found.', 'wprobot'));				
	}	

	// REPLACES
	$replaces = $campaign["settings"]["replace"];
	if(!empty($replaces) && is_array($replaces)) {	
		foreach($replaces as $replace) {
			$replace = explode("|", $replace);			
			if($replace[2] >= rand(1,100)) {
				$replace[0] = trim($replace[0]);
				$replace[1] = trim($replace[1]);
				if($replace[3] == "1") {
					$content = str_replace($replace[0], $replace[1], $content);
					$maintitle = str_replace($replace[0], $replace[1], $maintitle);					
				} else {
					$content = str_replace(" ".$replace[0], " ".$replace[1], $content);
					$maintitle = str_replace(" ".$replace[0], " ".$replace[1], $maintitle);				
				}
			}
		}
	}	
	
	// STRIP LINKS
	$strip_links = $campaign["settings"]["strip_links"];	
	if($strip_links == 1) {
		$content = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $content);
	}
	
	// TRANSLATION
	$translation = $campaign["settings"]["translate"];	
	if(function_exists("wpr5_translate_partial")) {
		$maintitle = wpr5_translate_partial($maintitle);
	}	
	if(!empty($translation["from"]) && $translation["from"] != "no" && !empty($translation["to1"]) && $translation["to1"] != "no") {
		if($translation["which"] == "yandex" && empty($translation["key"])) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", __('Error: Yandex API key is required for automatic translation. Please edit your campaign and enter the key.', 'wprobot'), __('Error: Yandex API key is required for automatic translation. Please edit your campaign and enter the key.', 'wprobot'));
		}
		if($translation["which"] == "deepl" && empty($translation["deepl_key"])) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", __('Error: DeepL API key is required for automatic translation. Please edit your campaign and enter the key.', 'wprobot'), __('Error: DeepL API key is required for automatic translation. Please edit your campaign and enter the key.', 'wprobot'));
		}
		
		@require_once("wpr_translation.php");
		if(strlen($content) > 3998) {
			$newcontent = "";
			$contents = str_split($content, 3950);
			
			foreach($contents as $totranslate) {
				$translatedcontent = wpr5_translate($totranslate,$translation["from"],$translation["to1"],$translation["to2"],$translation["to3"],$translation["key"],$translation["deepl_key"],$translation["which"]);			
			
				if(!empty($translatedcontent["error"]["reason"])) {
					return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", "Translation failed with error: ".$translatedcontent["error"]["message"], __('Translation failed with error:', 'wprobot').$translatedcontent["error"]["message"]);
				}

				if(empty($translatedcontent)) {
					return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", "Translation failed.", __('Error: The post could not be translated.', 'wprobot'));
				}				
			
				$newcontent .= $translatedcontent;
			}
			
			if(empty($newcontent)) {
				return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", "Translation failed.", __('Error: The post could not be translated.', 'wprobot'));			
			} else {
				$content = $newcontent;
				$translationtitle = wpr5_translate($maintitle,$translation["from"],$translation["to1"],$translation["to2"],$translation["to3"],$translation["key"],$translation["deepl_key"],$translation["which"]);
				if(empty($translationtitle["error"]["reason"]) && !empty($translationtitle)) {$maintitle = $translationtitle;}				
			}
		} else {
			$translationcontent = wpr5_translate($content,$translation["from"],$translation["to1"],$translation["to2"],$translation["to3"],$translation["key"],$translation["deepl_key"],$translation["which"]);
		
			if($debug == 1) {echo "<br/><br> --- translated --- <br/>";print_r($translationcontent);}	
			if(!empty($translationcontent["error"]["reason"])) {
				return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", "Translation failed with error: ".$translationcontent["error"]["message"], __('Translation failed with error:', 'wprobot').$translationcontent["error"]["message"]);
			}
			
			$translationcontent = trim($translationcontent);
			if(empty($translationcontent)) {
				return wpr5_update_post_status($runtime, $savekw, $savetpl, "translation failed", "Translation failed.", __('Error: The post could not be translated.', 'wprobot'));
			} else {
				$content = $translationcontent;
				$translationtitle = wpr5_translate($maintitle,$translation["from"],$translation["to1"],$translation["to2"],$translation["to3"],$translation["key"],$translation["deepl_key"],$translation["which"]);
				if(empty($translationtitle["error"]["reason"]) && !empty($translationtitle)) {$maintitle = $translationtitle;}
			}		
		}
	}	
	
	// REWRITING
	$rewriting = $campaign["settings"]["rewrite"];	
	if(!empty($rewriting)) {
		@require_once("wpr_rewriter.php");
		$rwcontent = wpr5_rewrite($content, $rewriting, $options);

		if($debug == 1) {echo "<br/><br> --- rewriting --- <br/>";print_r($rwcontent);}	
		
		if(is_array($rwcontent) && !empty($rwcontent["error"])) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "rewriting failed", "Rewriting failed with error: ".$rwcontent["error"], __('Rewriting failed with error:', 'wprobot').$rwcontent["error"]);
		} elseif(empty($rwcontent)) {
			return wpr5_update_post_status($runtime, $savekw, $savetpl, "rewriting failed", "Rewriting failed.", __('Error: The post could not be rewritten. Empty response received', 'wprobot'));
		} else {
			$content = $rwcontent;
			$rwtitle = wpr5_rewrite($maintitle, $rewriting, $options);
			if(empty($rwtitle["error"]) && !empty($rwtitle)) {$maintitle = $rwtitle;}
		}
	}
	
	$maintitle = str_replace("{keyword}", $keyword["name"], $maintitle);
	$maintitle = trim($maintitle);
	if(empty($maintitle) || empty($content)) {
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "no content", "No content found. Empty title or post body.", __('Error: No content found. Empty title or post body.', 'wprobot'));	
	}
	
	$po = array();
	$po['title'] = $maintitle;
	$po['post_title'] = $maintitle;
	$po['post_content'] = $content;	
	$po["post_author"] = $campaign["main"]["author"];
	$po["post_status"] = $campaign["main"]["post_status"];
	$po["post_type"] = $campaign["main"]["post_type"];
	$po['tax_input'] = array("post_tag" => $keyword["name"]);	
	
	$args = array();
	$args['params']["post"][0]['post_data']['post_extras']['post_categories'] = $categories;
	
	$args['params']["post"][0]['post_data']['post_data'] = $po;	
	$args['params']["post"][0]['content'] = $po;
	$args['params']["post"][0]['comments'] = $comment_array;

	// FEATURED IMAGE
	// MISSING: setting to select which module should produce featured image when adding campaign?
	preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
	if($debug == 1) {echo "<br/><br> --- FEAT IMG --- <br/>";echo $image['src'];}		
	if(!empty($image['src']) && strpos($image['src'], "www.bing.com/th") === false) {
		$args['params']["post"][0]['post_data']['post_extras']['featured_img'] = $image['src'];
	}
	
	if(!empty($cfarray) && is_array($cfarray)) {
		foreach($cfarray as $cf) {		
			$cfname = $cf["name"];
			$args['params']["post"][0]['post_data']['post_extras']['post_meta'][$cfname] = array($cf["value"]);
		}
	}				

	if($debug == 1) {echo "<br/><br> --- comment_array --- <br/>";print_r($comment_array);}		
	
	$result = wpr5_create_posts($args['params']);

	if($debug == 1) {echo "<br/><br> --- result --- <br/>";print_r($result);}			
	
	if (!empty($result["success"])) {
		// UPDATE COUNTERS
		if(empty($campaigns[$cid]["count"])) {
			$campaigns[$cid]["count"] = 1;
		} else {
			$campaigns[$cid]["count"] = $campaigns[$cid]["count"] + 1;
		}
		
		// SET WOOCOMMERCE TERM
		if($campaign["main"]["post_type"] == "product") {
			$de = wp_set_object_terms( $result["success"][0], "external", "product_type", true );
			
			foreach($keyword["category"] as $cat) {
				wp_set_object_terms( $result["success"][0], $cat["name"], 'product_cat', true );
			}			
		}

		$campaigns[$cid]["keywords"][$rndkw]["count"] = $kw_counts;
		$del = update_option("wpr5_campaigns", $campaigns);

		if($debug == 1) {echo "<br> --- updated counts --- <br/>";print_r($campaigns[$cid]["keywords"][$rndkw]);}	
		
		// update DUPLICATE PROTECTION (item IDs in db)
		$usql = "INSERT INTO ".$wpr5_table_posts." ( campaign, keyword, module, unique_id, time ) VALUES";
		$ekeyword = $wpdb->escape($keyword["name"]);
		$time = time();
		foreach($unique_log as $ulo) {
			$unique = $wpdb->escape($ulo["uid"]);
			$modulname = $wpdb->escape($ulo["module"]);
			$usql .= " ( '$cid', '$ekeyword', '$modulname', '$unique', '$time' ),";
		}
		$usql = substr_replace($usql ,";",-1);
		$results = $wpdb->query($usql);

		// UPDATE LOG
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "success", $maintitle, __('Article has been created successfully.', 'wprobot'), 0, $result["success"][0]);				
	} else {
		return wpr5_update_post_status($runtime, $savekw, $savetpl, "inserting failed", "Inserting post failed.", __('Error: Inserting post failed. ', 'wprobot').esc_html($result["error"]));					
	}
}

function wpr5_ebay_handler($atts, $content = null) {

	$campID = $atts['cid'];	
	
	$lang = $atts['lang'];
	if (empty($lang)) {$lang="en-US";}			
	
	$country = $atts['country'];
	if (empty($country)) {$country=0;}		
	
	$sortby = $atts['sort'];
	if (empty($sortby)) {$sortby="bestmatch";}	
	
	$ebaycat = $atts["ebcat"];
	if (empty($ebaycat) || $ebaycat == "all") {$ebaycat="-1";}		
	
	$number = $atts['num'];
	if(empty($number)) {$number = rand(1,30);}	
	
	$arrFeeds = array();

	require_once ( ABSPATH . WPINC .  '/rss.php' );	

	if($country == 0) {$program = 1;}
	elseif($country == 205) {$program = 2;}
	elseif($country == 16) {$program = 3;}
	elseif($country == 15) {$program = 4;}
	elseif($country == 23) {$program = 5;}
	elseif($country == 2) {$program = 7;}
	elseif($country == 71) {$program = 10;}
	elseif($country == 77) {$program = 11;}
	elseif($country == 101) {$program = 12;}
	elseif($country == 186) {$program = 13;}
	elseif($country == 193) {$program = 14;}
	elseif($country == 3) {$program = 15;}
	elseif($country == 146) {$program = 16;}
	else {$program = $country;}	
	$rssurl= "http://rest.ebay.com/epn/v1/find/item.rss?keyword=" . str_replace(" ","+", ($atts['kw']))."&campaignid=" . urlencode($campID) . "&sortOrder=BestMatch&programid=".$program."";	
	
	if(!empty($ebaycat) && $ebaycat != -1){
		$rssurl.="&categoryId1=".$ebaycat;
	}		
	
	
	$therss = fetch_rss($rssurl);
	
	if($therss->items != "" && $therss->items != null) {
		foreach ($therss->items as $item) { 
			$itemRSS = array (
				'title' => $item['title'],
				'desc' => $item['description'],
				'link' => $item['link'],
				'date' => $item['pubDate']
				);
			array_push($arrFeeds, $itemRSS);
		}
	}

	$ebcontent = "<strong>".$arrFeeds[$number]['title']."</strong>".$arrFeeds[$number]['desc'];	
	if($arrFeeds[$number]['title'] != "") {
	} else {$ebcontent = "";}

	return $ebcontent;
}
add_shortcode('wpr5_ebay', 'wpr5_ebay_handler' );

add_action( 'admin_head', 'wpr5_option_page_icon2' );
function wpr5_option_page_icon2() {
    ?>
    <style>
        /* Admin Menu - 16px
           Use only if you put your plugin or option page in the top level via add_menu_page()
        */
        #toplevel_page_wpr5-automation .wp-menu-image {
            background: url(<?php echo wpr5_URLPATH; ?>images/icon-adminmenu16-sprite.png) no-repeat 10px 9px !important;
        }
        /* We need to hide the generic.png img element inserted by default */
        #toplevel_page_wpr5-automation .wp-menu-image img {
            display: none;
        }
        /*#toplevel_page_wpr5-automation:hover .wp-menu-image, #toplevel_page_wpr5-automation.wp-has-current-submenu .wp-menu-image {
            background-position: 11px -23px !important;
        }*/


    </style>
<?php 
}
?>