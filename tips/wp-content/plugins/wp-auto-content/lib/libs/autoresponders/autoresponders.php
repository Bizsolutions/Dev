<?php

define ('WPAUTOC_AR_AWEBER', 1);
define ('WPAUTOC_AR_GETRESPONSE', 2);
define ('WPAUTOC_AR_MAILCHIMP', 3);
define ('WPAUTOC_AR_CCONTACT', 4);
define ('WPAUTOC_AR_ICONTACT', 5);
define ('WPAUTOC_AR_SENDREACH', 6);
define ('WPAUTOC_AR_SENDY', 7);
define ('WPAUTOC_AR_ACTIVECAMPAIGN', 8);
define ('WPAUTOC_AR_MAILIT', 9);
define ('WPAUTOC_AR_OTHER', 10);
define ('WPAUTOC_AR_SENDLANE', 11);
define ('WPAUTOC_AR_MYMAILIT', 12);
define ('WPAUTOC_AR_WPLEADS', 20);


function wpautoc_get_autoresponder_types() {
	return array(
		array('label' => 'Local Only', 'value' => 0),
		array('label' => 'Aweber', 'value' => WPAUTOC_AR_AWEBER),
		array('label' => 'Getresponse', 'value' => WPAUTOC_AR_GETRESPONSE),
		array('label' => 'Mailchimp', 'value' => WPAUTOC_AR_MAILCHIMP),
		array('label' => 'Constant Contact', 'value' => WPAUTOC_AR_CCONTACT),
		array('label' => 'iContact', 'value' => WPAUTOC_AR_ICONTACT),
		array('label' => 'Sendreach', 'value' => WPAUTOC_AR_SENDREACH),
		array('label' => 'Mailit Plugin', 'value' => WPAUTOC_AR_MAILIT),
		array('label' => 'Active Campaign', 'value' => WPAUTOC_AR_ACTIVECAMPAIGN),
		array('label' => 'Sendlane', 'value' => WPAUTOC_AR_SENDLANE),
		array('label' => 'My Mailit', 'value' => WPAUTOC_AR_MYMAILIT),
		/*,
		array('label' => 'Other (HTML Form)', 'value' => WPAUTOC_AR_OTHER)*/
	);
}

function wpautoc_subscribe_user_ajax() {
	$action_id = isset( $_POST['action_id'] ) ? $_POST['action_id'] : 0;
	if (!$action_id) {
		echo "2";
		exit();
	}
	$action = wpautoc_get_action( $action_id );
	$action = json_decode( $action->settings );
	// var_dump($action);

	$ar_type = $action->ar_type;
	if( !$ar_type )
		$ar_type = 20;
	if ( $ar_type ) {
		$list = $action->ar_list;
		$name = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$email = isset( $_POST['email'] ) ? $_POST['email'] : false;

		// subscribe user;
		if ( /*$list && !empty( $list ) &&*/ $email && !empty( $email )  ) {
			// echo "todo ok;";
			wpautoc_signup_customer_autoresponder( $ar_type, $list, $email, $name );
		}
	}

	$webinar_type = $action->webinar_type;
	if ($webinar_type) {
		// subscribe user to webinar
		$webinar = $action->webinar_name;
		$name = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$email = isset( $_POST['email'] ) ? $_POST['email'] : false;
		if ( $webinar && !empty( $webinar ) && $email && !empty( $email ) )
			wpautoc_signup_customer_webinar ($webinar_type, $webinar, $email, $name );
	}
	echo "1";
	exit();
}


/*define ('WPAUTOC_AR_AWEBER', 1);
define ('WPAUTOC_AR_GETRESPONSE', 2);
define ('WPAUTOC_AR_MAILCHIMP', 3);
define ('WPAUTOC_AR_CCONTACT', 4);
define ('WPAUTOC_AR_ICONTACT', 5);
define ('WPAUTOC_AR_SENDREACH', 6);
define ('WPAUTOC_AR_SENDY', 7);*/

function wpautoc_signup_customer_autoresponder ($ar_system, $list_id, $email, $first_name='', $last_name = ''/*, $tag='', $tag_value=''*/) {
	switch($ar_system) {
		case 1:
		case '1':
			// aweber
			return wpautoc_ar_aweber_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 2:
		case '2':
			// getresponse
			return wpautoc_ar_getresponse_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 3:
		case '3':
			// mailchimp
			return wpautoc_ar_mailchimp_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 5:
		case '5':
			// icontact
		return wpautoc_ar_icontact_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 4:
		case '4':
			// constant contact
			return wpautoc_ar_constcontact_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 6:
		case '6':
			// sendreach
			return wpautoc_ar_sendreach_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 7:
		case '7':
			// sendy
			return wpautoc_ar_sendy_subscribe_user($list_id, $email, $first_name, $last_name/*, $tag, $tag_value*/);
			break;
		case 8:
		case '8':
			// activecampaign
			return wpautoc_ar_activecampaign_subscribe_user($list_id, $email, $first_name, $last_name, $tag, $tag_value);
			break;
		case 9:
		case '9':
			// mailit
			return wpautoc_ar_mailit_subscribe_user($list_id, $email, $first_name, $last_name, $tag, $tag_value);
			break;
		case 11:
		case '11':
			// sendlane
			return wpautoc_ar_sendlane_subscribe_user($list_id, $email, $first_name, $last_name, $tag, $tag_value);
			break;
		case 12:
		case '12':
			// mymailit
			return wpautoc_ar_mymailit_subscribe_user($list_id, $email, $first_name, $last_name, $tag, $tag_value);
			break;
		case 20:
		case '20':
			// internal leads
			return wpautoc_ar_wpleads_subscribe_user( $email, $first_name );
			break;
		default:
			break;
	}		
}


function wpautoc_signup_customer_webinar ($webinar_system, $webinar_id, $email, $first_name='', $last_name='') {
	if ($last_name)
		$name = $first_name.' '.$last_name;
	else
		$name = $first_name;

	switch($webinar_system) {
		case 1:
			// gotowebinar
			return wpautoc_gtw_subscribe_user($webinar_id, $email, $first_name, $last_name);
			break;
		case 2:
			// hangouts plugin
			return wpautoc_hangouts_subscribe_user($webinar_id, $email, $name);
			break;
		case 3:
			// webinar express
			return wpautoc_webexpr_subscribe_user($webinar_id, $email, $name);
			break;
		case 4:
			// webinar express
			return wpautoc_webignition_subscribe_user($webinar_id, $email, $name);
			break;
		case 5:
			// webinarjam
			return wpautoc_webinarjam_subscribe_user($webinar_id, $email, $name);
			break;
		case 6:
			// webinarjeo
			return wpautoc_webinarjeo_subscribe_user($webinar_id, $email, $name);
			break;
		default:
			break;
	}		
}


/* Aweber */

// define('WPAUTOC_AWEBER_KEY', 'AkMODCBcZhKVzVOrqa4YX9ez');
// define('WPAUTOC_AWEBER_SECRET', 'ChRSiBi8j5BnYfh19tvO4cReVvNWAojsuUyCgCYu');

define('WPAUTOC_AWEBER_KEY', 'AkwDJTBZs7hDn16kuG4JaxNU');
define('WPAUTOC_AWEBER_SECRET', 'JRDfCQZAp6RqUQUUdN0XaUyqfor02qbBnlbJAo0x');

function wpautoc_check_authorize_aweber() {
	if (isset($_POST['wpautoc_aweber_authorize'])) {
		wpautoc_check_authorize_aweber_api();
	}
	else if (isset($_GET['wpautoc_aweber_authorize'])) {
		wpautoc_check_authorize_aweber_api();
	}
	else if (isset($_POST['wpautoc_aweber_unauthorize'])) {
		wpautoc_unauthorize_aweber_api();
	}
}
add_action('admin_init', 'wpautoc_check_authorize_aweber');

function wpautoc_check_authorize_ar_api() {
    $plugin_options = wpautoc_get_plugin_settings();
	// $ar_data = $plugin_options['autoresponders'];

    if (isset($_POST['wpautoc_getresponse_authorize'])) {
    	if (isset($_POST['wpautoc_getresponse_authorize']) && !empty($_POST['wpautoc_getresponse_api'])) {
    		$plugin_options['autoresponders']['getresponse']['apikey'] = trim($_POST['wpautoc_getresponse_api']);
    		wpautoc_save_plugin_settings( $plugin_options );
    		wpautoc_get_getresponse_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_icontact_authorize'])) {
		$plugin_options['autoresponders']['icontact']['app_id'] = trim($_POST['wpautoc_icontact_app_id']);
		$plugin_options['autoresponders']['icontact']['user'] = trim($_POST['wpautoc_icontact_user']);
		$plugin_options['autoresponders']['icontact']['pass'] = trim($_POST['wpautoc_icontact_pass']);
		wpautoc_save_plugin_settings( $plugin_options );

    	if (isset($_POST['wpautoc_icontact_authorize']) && !empty($_POST['wpautoc_icontact_app_id']) && !empty($_POST['wpautoc_icontact_user']) && !empty($_POST['wpautoc_icontact_pass'])) {
    		wpautoc_get_icontact_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_mailchimp_authorize'])) {
    	if (isset($_POST['wpautoc_mailchimp_authorize']) && !empty($_POST['wpautoc_mailchimp_api'])) {
    		$plugin_options['autoresponders']['mailchimp']['apikey'] = trim($_POST['wpautoc_mailchimp_api']);
    		wpautoc_save_plugin_settings( $plugin_options );

    		wpautoc_get_mailchimp_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_ccontact_authorize'])) {
		$plugin_options['autoresponders']['ccontact']['user'] = trim($_POST['wpautoc_ccontact_user']);
		$plugin_options['autoresponders']['ccontact']['pass'] = trim($_POST['wpautoc_ccontact_pass']);
		wpautoc_save_plugin_settings( $plugin_options );

    	if (isset($_POST['wpautoc_ccontact_authorize']) && !empty($_POST['wpautoc_ccontact_user']) && !empty($_POST['wpautoc_ccontact_pass'])) {
    		// $ar_data['constantcontact']['apikey'] = $_POST['wpautoc_getresponse_api'];
    		wpautoc_get_constantcontact_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_sendreach_authorize'])) {
		$plugin_options['autoresponders']['sendreach']['user'] = trim($_POST['wpautoc_sendreach_user_id']);
		$plugin_options['autoresponders']['sendreach']['apikey'] = trim($_POST['wpautoc_sendreach_apikey']);
		$plugin_options['autoresponders']['sendreach']['apisecret'] = trim($_POST['wpautoc_sendreach_secret']);
		wpautoc_save_plugin_settings( $plugin_options );

    	if (isset($_POST['wpautoc_sendreach_authorize']) && !empty($_POST['wpautoc_sendreach_user_id']) &&
    		!empty($_POST['wpautoc_sendreach_apikey']) &&
    		!empty($_POST['wpautoc_sendreach_secret'] )) {
    		wpautoc_get_sendreach_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_sendy_authorize'])) {
		$plugin_options['autoresponders']['sendy']['url'] = trim($_POST['wpautoc_sendy_url']);
		$plugin_options['autoresponders']['sendy']['email'] = trim($_POST['wpautoc_sendy_email']);
		$plugin_options['autoresponders']['sendy']['apikey'] = trim($_POST['wpautoc_sendy_apikey']);
		wpautoc_save_plugin_settings( $plugin_options );

    	if (isset($_POST['wpautoc_sendy_authorize']) && !empty($_POST['wpautoc_sendy_url']) &&
    		!empty($_POST['wpautoc_sendy_apikey']) ) {
    		wpautoc_get_sendy_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_activecampaign_authorize'])) {
		$plugin_options['autoresponders']['activecampaign']['url'] = trim($_POST['wpautoc_activecampaign_url']);
		$plugin_options['autoresponders']['activecampaign']['apikey'] = trim($_POST['wpautoc_activecampaign_apikey']);
		wpautoc_save_plugin_settings( $plugin_options );    	

    	if (isset($_POST['wpautoc_activecampaign_authorize']) && !empty($_POST['wpautoc_activecampaign_url']) && 
    		!empty($_POST['wpautoc_activecampaign_apikey']) ) {
    		wpautoc_get_activecampaign_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_mailit_authorize'])) {
		$plugin_options['autoresponders']['mailit']['install_type'] = trim($_POST['wpautoc_mailit_type']);
		$plugin_options['autoresponders']['mailit']['list_url'] = trim($_POST['wpautoc_mailit_url']);
		wpautoc_save_plugin_settings( $plugin_options );

    	if ( isset( $_POST['wpautoc_mailit_authorize'] ) ) {
    		if( $plugin_options['autoresponders']['mailit']['install_type'] == 1 )
    			wpautoc_get_mailit_mailing_lists(1);
    	}
    }

    else if (isset($_POST['wpautoc_mymailit_authorize'])) {
		$plugin_options['autoresponders']['mymailit']['list_url'] = trim($_POST['wpautoc_mymailit_url']);
		wpautoc_save_plugin_settings( $plugin_options );
    }

    else if (isset($_POST['wpautoc_sendlane_authorize'])) {
		$plugin_options['autoresponders']['sendlane']['url'] = trim($_POST['wpautoc_sendlane_url']);
		$plugin_options['autoresponders']['sendlane']['apikey'] = trim($_POST['wpautoc_sendlane_apikey']);
		$plugin_options['autoresponders']['sendlane']['hashkey'] = trim($_POST['wpautoc_sendlane_hashkey']);
		wpautoc_save_plugin_settings( $plugin_options );    	

    	if (isset($_POST['wpautoc_sendlane_authorize']) && !empty($_POST['wpautoc_sendlane_url']) && 
    		!empty($_POST['wpautoc_sendlane_apikey']) && 
    		!empty($_POST['wpautoc_sendlane_hashkey']) ) {
    		wpautoc_get_sendlane_mailing_lists(1);
    	}
    }

    /* Webinars */
        else if (isset($_POST['wpautoc_webinarjam_connect'])) {
    		$plugin_options['webinars']['webinarjam']['apikey'] = trim($_POST['wpautoc_webinarjam_api']);
    		wpautoc_save_plugin_settings( $plugin_options );    	

        	if ( !empty($_POST['wpautoc_webinarjam_api']) ) {
        		wpautoc_get_webinarjam_webinars(1);
        	}
        }

        else if (isset($_POST['wpautoc_webinarjeo_connect'])) {
    		$plugin_options['webinars']['webinarjeo']['user'] = trim($_POST['wpautoc_webinarjeo_user']);
    		$plugin_options['webinars']['webinarjeo']['pass'] = trim($_POST['wpautoc_webinarjeo_pass']);
    		wpautoc_save_plugin_settings( $plugin_options );

        	if ( !empty($_POST['wpautoc_webinarjeo_user']) && !empty($_POST['wpautoc_webinarjeo_pass'])) {
        		wpautoc_get_webinarjeo_webinars(1);
        	}
        }

}



function wpautoc_check_authorize_aweber_api() {
	try {
		$aweber =wpautoc_ar_aweber_authenticate();

		$aweber = $aweber[0];
		if( !$aweber )
			return;
		 // var_dump($aweber);
		 // die();
	// $aweber = new AWeberAPI(wpautoc_AWEBER_KEY, wpautoc_AWEBER_SECRET);

    $plugin_options = wpautoc_get_plugin_settings();
    // $plugin_options['autoresponders']['aweber']['organizer_key'] = $objOAuthEn->getOrganizerKey();
    // $plugin_options['autoresponders']['aweber']['access_token'] = $objOAuthEn->getAccessToken();
// var_dump($plugin_options['autoresponders']['aweber']['access_token']);
	if ( isset($aweber) && ($aweber) && (!isset($plugin_options['autoresponders']['aweber']['access_token']) || (!$plugin_options['autoresponders']['aweber']['access_token'])/*!get_site_option('wpautoc_aweber_accessToken', 0)*/  )) {
	    if (empty($_GET['oauth_token'])) {
// die('Adios1!');
// var_dump($aweber);
	        //$callbackUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	        $callbackUrl = admin_url('admin.php?page=wp-auto-content-ar&wpautoc_aweber_authorize=1');
	        list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
	        // update_site_option('wpautoc_aweber_requestTokenSecret', $requestTokenSecret);
	        // update_site_option('wpautoc_aweber_callbackUrl', $callbackUrl);

			$plugin_options['autoresponders']['aweber']['request_token_secret'] = $requestTokenSecret;
			$plugin_options['autoresponders']['aweber']['callback_url'] = $callbackUrl;
		    wpautoc_save_plugin_settings( $plugin_options );

	        header("Location: {$aweber->getAuthorizeUrl()}");
	        exit();
	    }

	    $aweber->user->tokenSecret = $plugin_options['autoresponders']['aweber']['request_token_secret'];
	    $aweber->user->requestToken = $_GET['oauth_token'];
	    $aweber->user->verifier = $_GET['oauth_verifier'];
	    // var_dump($aweber->user);
	    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
	    // var_dump($accessToken);
	    // var_dump($accessTokenSecret);

	    // update_site_option('wpautoc_aweber_accessToken', $accessToken);
	    // update_site_option('wpautoc_aweber_accessTokenSecret', $accessTokenSecret);
			$plugin_options['autoresponders']['aweber']['access_token'] = $accessToken;
			$plugin_options['autoresponders']['aweber']['access_token_secret'] = $accessTokenSecret;
		    wpautoc_save_plugin_settings( $plugin_options );

	   // header('Location: '.get_site_option('wpautoc_aweber_callbackUrl'));
	    //exit();
	    wp_redirect(admin_url('admin.php?page=wp-auto-content-ar'));
	}
// die('Adios3!');

	// $aweber = new AWeberAPI(wpautoc_AWEBER_KEY, wpautoc_AWEBER_SECRET);
		$aweber =wpautoc_ar_aweber_authenticate();
		$account = $aweber[1];

	# set this to true to view the actual api request and response
	$aweber->adapter->debug = false;

	// $account = $aweber->getAccount(/*get_site_option('wpautoc_aweber_accessToken'), get_site_option('wpautoc_aweber_accessTokenSecret')*/
	// 	$plugin_options['autoresponders']['aweber']['access_token'], $plugin_options['autoresponders']['aweber']['access_token_secret']
	// 	);
	if(isset($account)){
		$plugin_options['autoresponders']['aweber']['token'] = $plugin_options['autoresponders']['aweber']['access_token'];
		$plugin_options['autoresponders']['aweber']['token_secret'] = $plugin_options['autoresponders']['aweber']['access_token'];
	    wpautoc_save_plugin_settings( $plugin_options );
	    wpautoc_get_aweber_mailing_lists(1);
	}

	} catch (Exception $oException) {
		return 0;
	}

}

function wpautoc_unauthorize_aweber_api() {
	$key = 'wpautoc_aweber_lists';
	delete_transient( $key );

    $plugin_options = wpautoc_get_plugin_settings();

	$plugin_options['autoresponders']['aweber']['token'] = 0;
	$plugin_options['autoresponders']['aweber']['token_secret'] = 0;
	$plugin_options['autoresponders']['aweber']['access_token'] = 0;
	$plugin_options['autoresponders']['aweber']['access_token_secret'] = 0;

    wpautoc_save_plugin_settings( $plugin_options );
}


function wpautoc_force_refresh_autoresponder($ar_type) {
	switch ($ar_type) {
		case 1:
			$lists = wpautoc_get_aweber_mailing_lists(1);
			break;
		case 2:
			$lists = wpautoc_get_getresponse_mailing_lists(1);
			break;
		case 3:
			$lists = wpautoc_get_mailchimp_mailing_lists(1);
			break;
		case 4:
			$lists = wpautoc_get_icontact_mailing_lists(1);
			break;
		case 5:
			$lists = wpautoc_get_constantcontact_mailing_lists(1);
			break;
		case 6:
			$lists = wpautoc_get_sendreach_mailing_lists(1);
			break;
		case 7:
			$lists = wpautoc_get_sendy_mailing_lists(1);
			break;
		case 8:
			$lists = wpautoc_get_activecampaign_mailing_lists(1);
			break;
		case 9:
			$lists = wpautoc_get_mailit_mailing_lists(1);
			break;
		case 11:
			$lists = wpautoc_get_sendlane_mailing_lists(1);
			break;
		case 10:
		case 20:
		case 30:
			$lists = 0;
			break;
			// return;
		case 101:
			$webinars = wpautoc_get_gotowebinar_webinars(1);
			break;
		default:
			break;
		}
}

function wpautoc_get_autoresponder_lists( $ar_type ) {
	// $ar_type = intval( $ar_type );
	switch ($ar_type) {
		case 1:
			$lists = wpautoc_get_aweber_mailing_lists(0);
			break;
		case 2:
			$lists = wpautoc_get_getresponse_mailing_lists();
			break;
		case 3:
			$lists = wpautoc_get_mailchimp_mailing_lists();
			break;
		case 4:
			$lists = wpautoc_get_icontact_mailing_lists();
			break;	
		case 5:
			$lists = wpautoc_get_constantcontact_mailing_lists();
			break;
		case 6:
			$lists = wpautoc_get_sendreach_mailing_lists();
			break;
		case 7:
			$lists = wpautoc_get_sendy_mailing_lists();
			break;
		case 8:
			$lists = wpautoc_get_activecampaign_mailing_lists();
			break;
		case 9:
			$lists = wpautoc_get_mailit_mailing_lists();
			break;
		case 11:
			$lists = wpautoc_get_sendlane_mailing_lists();
			break;
			// HTML
		default:
			$lists = 0;
			// return;
		}
	return $lists;
}
/*
	$ar_type:
		1: aweber
		2: getresponse
		3: html
*/

function wpautoc_mailing_lists_select($ar_type, $id = '', $name = '', $val=0, $class='ar_list') {
	$output = '';
	switch ($ar_type) {
		case 1:
			$lists = wpautoc_get_aweber_mailing_lists(0);
			break;
		case 2:
			$lists = wpautoc_get_getresponse_mailing_lists();
			break;
		case 3:
			$lists = wpautoc_get_mailchimp_mailing_lists();
			break;
		case 4:
			$lists = wpautoc_get_icontact_mailing_lists();
			break;
		case 5:
			$lists = wpautoc_get_constantcontact_mailing_lists();
			break;
		case 6:
			$lists = wpautoc_get_sendreach_mailing_lists();
			break;
		case 7:
			$lists = wpautoc_get_sendy_mailing_lists();
			break;
			// HTML
		default:
			$lists = 0;
			break;
			// return;
		}

	// if ($ar_type == 1)
	// 	$lists = wpautoc_get_aweber_mailing_lists(0);
	// else if ($ar_type == 2)
	// 	$lists = wpautoc_get_aweber_mailing_lists(0);
	// else
	// 	$lists = 0;
	if (!empty($name) && !empty($id))
		$output .= '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">';
	else
		$output .= '<select class="'.$class.'">';

	$output .= '<option value="0">-</option>';
	if ($lists) {
		foreach($lists as $offset => $list) {
			// var_dump($list);
				// $output.= '<option '.selected(key($list), $val, false).' name="mc_'.key($list).'" value="'.key($list).'">'.key($list).'</option>';
				$output.= '<option '.selected($list['id'], $val, false).' name="mc_'.$list['id'].'" value="'.$list['id'].'">'.$list['name'].'</option>';
		}
	}
	$output.= '</select>';
	echo $output;
	return 0;
}



function wpautoc_mailing_tags_select($ar_type, $id = '', $name = '', $val=0, $list_id='') {
	$output = '';
	// if ($ar_type == 1)
		$tags = wpautoc_get_custom_fields($list_id);
	if (!empty($name) && !empty($id))
		$output .= '<select name="'.$name.'" id="'.$id.'" class="ar_tag">';
	else
		$output .= '<select class="ar_tag">';

	$output .= '<option value="0">-</option>';
	if ($tags) {
		foreach($tags as $offset => $tag) {
			 // var_dump($tag);
				 $output.= '<option '.selected($tag, $val, false).' name="mc_'.$tag.'" value="'.$tag.'">'.$tag.'</option>';
		}
	}
	$output.= '</select>';
	echo $output;
}


function wpautoc_get_aweber_mailing_lists($force_read = 0, $custom_fields_also=0) {
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['aweber']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['aweber']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['aweber']['lists']);
	}

	$autoresponders = wpautoc_query_aweber_lists($custom_fields_also);
	$plugin_options['autoresponders']['aweber']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['aweber']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['aweber']['lists'] = $autoresponders;
	wpautoc_save_plugin_settings( $plugin_options );
	return $autoresponders;
}

/*
function wpautoc_query_aweber_lists($custom_fields_also=0) {
	$aweber = new AWeberAPI(wpautoc_AWEBER_KEY, wpautoc_AWEBER_SECRET);
	$ar_list_array = array();

    $plugin_options = wpautoc_get_plugin_settings();

	# set this to true to view the actual api request and response
	$aweber->adapter->debug = false;
	if(($token = $plugin_options['autoresponders']['aweber']['access_token']) && ($tokensec = $plugin_options['autoresponders']['aweber']['access_token_secret'])){
		$account = $aweber->getAccount($token, $tokensec);
		foreach($account->lists as $offset => $list) {
			if ($custom_fields_also) {
				$ar_list_array[] = array($list->data['name'] => wpautoc_get_custom_fields_from_data($list->custom_fields->data));
			}
			else
				$ar_list_array[] = array($list->data['name']);
		}
		return $ar_list_array;
	}
	return 0;
}
*/

function wpautoc_get_custom_fields($current_list) {
	$lists = wpautoc_get_aweber_mailing_lists();
	$tags = array();
	if ($lists) {
		foreach ($lists as $list) {
			if (key($list) == $current_list) {
				// var_dump($list);
				if (is_array($list[key($list)])) {
						  // var_dump($list[key($list)]);

					foreach ($list[key($list)] as $tag)
						// var_dump($tag);
						$tags[] = $tag;
				}
				return $tags;
			}
		}
	}
	return 0;
}

function wpautoc_get_custom_fields_from_data($custom_fields_data) {
	$tags = array();
	// var_dump($list);
	if (is_array($custom_fields_data)) {
		foreach ($custom_fields_data['entries'] as $tag)
			// var_dump($tag);
			$tags[] = $tag['name'];
	}
	return $tags;
}


function wpautoc_ar_aweber_authenticate() {
	include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/aweber_api/aweber_api.php');

	// Give the API your information
	try {
		$api = new AWeberAPI( WPAUTOC_AWEBER_KEY, WPAUTOC_AWEBER_SECRET );
		// var_dump(wpautoc_AWEBER_SECRET);
		// die();
		$plugin_options = wpautoc_get_plugin_settings();
	/*	if (!isset($plugin_options['autoresponders']['aweber']['access_token']))
			return array(0,0);*/
		$api->adapter->debug = false;
		if(isset($plugin_options['autoresponders']['aweber']['access_token']) && ($token = $plugin_options['autoresponders']['aweber']['access_token']) && ($tokensec = $plugin_options['autoresponders']['aweber']['access_token_secret']))
			$account = $api->getAccount($token, $tokensec);
		else
			$account = 0;
	} catch (Exception $oException) {
		return array( false, false );
	}
	return array($api, $account);
}

function wpautoc_query_aweber_lists($custom_fields_also=0) {
	$account = wpautoc_ar_aweber_authenticate();
	$account = $account[1];

	if (!$account) return;

	$ar_list_array = array();
    // $plugin_options = wpautoc_get_plugin_settings();

	try {
		foreach($account->lists as $offset => $list) {
			// var_dump($list->data);
			if ($custom_fields_also) {
				$ar_list_array[] = array($list->data['id'] => wpautoc_get_custom_fields_from_data($list->custom_fields->data)); // rmi353, was data['name']
			}
			else
				$ar_list_array[] = array('id' => $list->data['id'], 'name'=>$list->data['name']);
		}
		return $ar_list_array;
	} catch (Exception $oException) {
		return 0;
	}
}


function wpautoc_ar_aweber_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$account = wpautoc_ar_aweber_authenticate();
	$account = $account[1];
	if (!$account) return;
	$account_id = $account->data['id'];
	if (!empty($user_lastname))
		$user_firstname = $user_firstname.' '.$user_lastname;

	try {
	    // $account = $api->getAccount($accessKey, $accessSecret);
	    $listURL = "/accounts/{$account_id}/lists/{$list_id}";
	    $list = $account->loadFromUrl($listURL);
	    # create a subscriber
	    $params = array(
	        'email' => $user_email,
	        'ip_address' => wpautoc_get_visitor_ip() /*'127.0.0.1'*/ /*$_SERVER['REMOTE_ADDR']*/,
	        'ad_tracking' => 'wpautoc',
	        // 'misc_notes' => 'my cool app',
	        'name' => $user_firstname/*,
	        'custom_fields' => array(
	            'Car' => 'Ferrari 599 GTB Fiorano',
	            'Color' => 'Red',
	        ),*/
	    );
	    if (!empty($tag_name) && !empty($tag_value)) {
	    	$custom_fields = array($tag_name => $tag_value);
	    	$params['custom_fields'] = $custom_fields;
	    }

	    $subscribers = $list->subscribers;
	    $new_subscriber = $subscribers->create($params);
	} catch (Exception $e) { }
}

function wpautoc_ar_aweber_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	// $user_email = 'raulmellado@gmail.com';
	$account = wpautoc_ar_aweber_authenticate();
	$account = $account[1];

	if (!$account) return;
	try {

		if (!$delete) {
			// just subscribe to the new list
			wpautoc_ar_aweber_subscribe_user($list_to_id, $user_email, '');
			return;
		}
		$account_id = $account->data['id'];
		$subscribers_collection = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_from_id}/subscribers");
		$found_subscribers_collection = $subscribers_collection->find(array('email' => trim($user_email)));

	    // $found_lists = $account->lists->find(array('id' => $list_from_id));
	    // $destination_list = $found_lists[0];

		foreach ($found_subscribers_collection as $subscriber) {
		    if($subscriber->status == 'subscribed') {
		    	 // var_dump($subscriber->data);
		    	$user_email = $subscriber->data['email'];
		    	$firstname = $subscriber->data['name'];
		    	// rmi353, extract 1st/2nd name
		    	$lastname = '';
		    	if ($delete)
		    		$subscriber->delete();
		    	wpautoc_ar_aweber_subscribe_user($list_to_id, $user_email, $firstname);
			}
		}

		return;
	} catch(AWeberAPIException $exc) {
	    // print "<h3>AWeberAPIException:</h3>";
	    // print " <li> Type: $exc->type              <br>";
	    // print " <li> Msg : $exc->message           <br>";
	    // print " <li> Docs: $exc->documentation_url <br>";
	    // print "<hr>";
	}
}


/* iContact */


function wpautoc_ar_icontact_authenticate() {
	if (!class_exists('iContactApi'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/icontact/iContactApi.php');
	// Give the API your information
    $plugin_options = wpautoc_get_plugin_settings();

	if (!isset($plugin_options['autoresponders']['icontact']['app_id']) || empty($plugin_options['autoresponders']['icontact']['app_id']) ||
	!isset($plugin_options['autoresponders']['icontact']['pass']) || empty($plugin_options['autoresponders']['icontact']['pass']) ||
	!isset($plugin_options['autoresponders']['icontact']['user']) || empty($plugin_options['autoresponders']['icontact']['user'])
		)
		return 0;

	$app_id = $plugin_options['autoresponders']['icontact']['app_id'];
	$pass = $plugin_options['autoresponders']['icontact']['pass'];
	$user = $plugin_options['autoresponders']['icontact']['user'];

	iContactApi::getInstance()->setConfig(array(
		'appId'       => $app_id,
		'apiPassword' => $pass,
		'apiUsername' => $user
	));

	try {
		// return new cc(/*'raulmellado@gmail.com', 'ScA3GwrP8'*/ $user, $pass);
		return iContactApi::getInstance();
	} catch (Exception $oException) { return 0; }

	return 0;
}

function wpautoc_get_icontact_mailing_lists($force_read = 0) {
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['icontact']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['icontact']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['icontact']['lists']);
	}

	$lists = wpautoc_query_icontact_lists();
	$plugin_options['autoresponders']['icontact']['cache_expires'] = time() + (24 * 3600 * 3);
	$plugin_options['autoresponders']['icontact']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_icontact_lists() {
	$oiContact = wpautoc_ar_icontact_authenticate();
	if (!$oiContact) return;
	$ar_list_array = array();

	try {
		$lists = $oiContact->getLists();
		foreach ($lists as $list)
			$ar_list_array[] = array('id' => $list->listId, 'name'=> $list->name);
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }
}

function wpautoc_ar_icontact_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$oiContact = wpautoc_ar_icontact_authenticate();
	if (!$oiContact) return;
	try {
		$contact = $oiContact->addContact($user_email, null, null, $user_firstname, $user_lastname);
		$oiContact->subscribeContactToList($contact->contactId, $list_id, 'normal');
	} catch (Exception $oException) { }
}


function wpautoc_ar_icontact_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	$oiContact = wpautoc_ar_icontact_authenticate();
	if (!$oiContact) return;
	try {
		return;
		$contact = $oiContact->getContactByEmail($user_email);
		var_dump($contact);
		return;

		$contact = $oiContact->addContact($user_email, null, null, $user_firstname, $user_lastname);
		$oiContact->subscribeContactToList($contact->contactId, $list_id, 'normal');
		if ($delete)
			$oiContact->updateContact($iContactId, $sEmail = null, null, null, null, null, null, null, null, null, null, null, null, null, 'deleted');
	} catch (Exception $oException) { var_dump($oException); }
}


/* Getresponse */


function wpautoc_ar_getresponse_authenticate() {
	if (!class_exists('GetResponse'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/getresponse/GetResponseAPI.class.php');

    $plugin_options = wpautoc_get_plugin_settings();
	if (!isset($plugin_options['autoresponders']['getresponse']['apikey']) || empty($plugin_options['autoresponders']['getresponse']['apikey']))
		return 0;

	$api_key = $plugin_options['autoresponders']['getresponse']['apikey'];

	try {
		return new GetResponse(trim($api_key));
	} catch (Exception $oException) { return 0; }
}

function wpautoc_get_getresponse_mailing_lists($force_read = 0) {
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['getresponse']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['getresponse']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['getresponse']['lists']);
	}

	$lists = wpautoc_query_getresponse_lists();
	$plugin_options['autoresponders']['getresponse']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['getresponse']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['getresponse']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_getresponse_lists() {
	$api = wpautoc_ar_getresponse_authenticate();

	// Campaigns
	if (!$api) return;
	$ar_list_array = array();

	try {
	$campaigns 	 = (array)$api->getCampaigns();
	 $campaignIDs = array_keys($campaigns);
		foreach ($campaigns as $key=> $list) {
			$ar_list_array[] = array('id' => $key, 'name'=> $list->name);
		}
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }

}

function wpautoc_ar_getresponse_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	if (empty($user_firstname)) $user_firstname = 'Empty';
	if (!empty($user_lastname))
		$user_firstname = $user_firstname.' '.$user_lastname;
	$api = wpautoc_ar_getresponse_authenticate();
	if (!$api) return;
	try {
		$custom_fields = array();
	    if (!empty($tag_name) && !empty($tag_value)) {
	    	$custom_fields = array($tag_name => $tag_value);
	    	$params['custom_fields'] = $custom_fields;
	    }

		return $api->addContact($list_id, $user_firstname, $user_email, 'standard', 0, $custom_fields);
	} catch (Exception $oException) { return 0; }
}

function wpautoc_ar_getresponse_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	if (empty($user_firstname)) $user_firstname = 'Empty';
	$contact_id = 0;
	$api = wpautoc_ar_getresponse_authenticate();
	if (!$api) return;
	try {
		// $list_from_id = 0;
		// $user_email = 'raulgurubucket@gmail.com';
		try {
			if ($list_from_id) {
				$list_from_id = array($list_from_id);
				$contact = $api->getContactsByEmail($user_email, $list_from_id);
				if ($contact && !empty($contact) && (count( (array)$contact) ) != 0) {
					  var_dump($contact);
					$user_firstname = $contact->name;
					$contact_id = key($contact);
				}
			}
		} catch (Exception $oException) {}

		try {
			if ($delete && $contact_id) {
				$api->deleteContact($contact_id);
			}

			$api->addContact($list_to_id, $user_firstname, $user_email);
		} catch (Exception $oException) {}




	} catch (Exception $oException) { var_dump($oException); return 0; }
}

/* Constant Contact */


function wpautoc_ar_constcontact_authenticate() {
	if (!class_exists('cc'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/constantcontact/class.cc.php');

	// Give the API your information
    $plugin_options = wpautoc_get_plugin_settings();
	if (!isset($plugin_options['autoresponders']['ccontact']['user']) || empty($plugin_options['autoresponders']['ccontact']['user']) ||
	!isset($plugin_options['autoresponders']['ccontact']['pass']) || empty($plugin_options['autoresponders']['ccontact']['pass'])
		)
		return 0;

	$user = $plugin_options['autoresponders']['ccontact']['user'];
	$pass = $plugin_options['autoresponders']['ccontact']['pass'];
	try {
		return new cc( $user, $pass);

	} catch (Exception $oException) { return 0; }


}

function wpautoc_get_constantcontact_mailing_lists($force_read = 0) {
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['constantcontact']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['constantcontact']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['constantcontact']['lists']);
	}

	$lists = wpautoc_query_constantcontact_lists();
	$plugin_options['autoresponders']['constantcontact']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['constantcontact']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['constantcontact']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_constantcontact_lists() {
	$page = 'lists';
	$api = wpautoc_ar_constcontact_authenticate();
	if (!$api) return;

	$ar_list_array = array();

	try {
		$lists = $api->get_lists($page);
		// var_dump($lists);
		 // return;
		foreach ($lists as $list)
			$ar_list_array[] = array('id' => $list['id'], 'name'=> $list['Name']);
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }
}

function wpautoc_ar_constcontact_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_constcontact_authenticate();
	if (!$api) return;
	$extra_fields = array(
		'FirstName' => $user_firstname,
		'LastName' => $user_lastname,
	);

	$custom_fields = array();
	if (!empty($tag_name) && !empty($tag_value)) {
	  	$extra_fields[] = array($tag_name => $tag_value);
	}

	$contact = $api->query_contacts($user_email);
	if ($contact) {
		$contact_details = $api->get_contact($contact['id']);

		$current_lists = $contact_details['lists'];
		if (!in_array($list_id, $current_lists))
			array_push($current_lists, $list_id);
		else
			return;
		$status = $api->update_contact($contact['id'], $user_email, $current_lists);
	}
	else {
		$new_id = $api->create_contact($user_email, $list_id, $extra_fields);
	}
	return;
}

function wpautoc_ar_constcontact_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	$api = wpautoc_ar_constcontact_authenticate();

	$contact = $api->query_contacts($user_email);
	if ($contact) {
		$contact_details = $api->get_contact($contact['id']);
		$current_lists = $contact_details['lists'];
		if (!in_array($list_to_id, $current_lists))
			array_push($current_lists, $list_id);
		if ($delete)
			$current_lists = array_diff($current_lists, array($list_from_id));

		$status = $api->update_contact($contact['id'], $user_email, $current_lists);
	}
	else
		return;
}


/* Mailchimp */


function wpautoc_ar_mailchimp_authenticate() {
	if (!class_exists('MCAPI'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/mailchimp/MCAPI.class.php');

    $plugin_options = wpautoc_get_plugin_settings();
	if (!isset($plugin_options['autoresponders']['mailchimp']['apikey']) || empty($plugin_options['autoresponders']['mailchimp']['apikey']))
		return 0;

	$api_key = $plugin_options['autoresponders']['mailchimp']['apikey'];

	try {
		return $api = new MCAPI($api_key);
	} catch (Exception $oException) { return 0; }

}


function wpautoc_get_mailchimp_mailing_lists($force_read = 0) {
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['mailchimp']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['mailchimp']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['mailchimp']['lists']);
	}

	$lists = wpautoc_query_mailchimp_lists();
	$plugin_options['autoresponders']['mailchimp']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['mailchimp']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['mailchimp']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_mailchimp_lists() {

	$api = wpautoc_ar_mailchimp_authenticate();
	if (!$api) return;


	$ar_list_array = array();

	try {
		$retval = $api->lists();
		if ($api->errorCode)
			return 0;

		foreach ($retval['data'] as $list)
			$ar_list_array[] = array('id' => $list['id'], 'name'=> $list['name']);
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }

}

function wpautoc_ar_mailchimp_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_mailchimp_authenticate();

	$merge_vars = array('FNAME'=>$user_firstname, 'LNAME'=>$user_lastname/*,
	                  'GROUPINGS'=>array(
	                        array('name'=>'Your Interests:', 'groups'=>'Bananas,Apples'),
	                        array('id'=>22, 'groups'=>'Trains'),
	                        )*/
	                    );

	// By default this sends a confirmation email - you will not see new members
	// until the link contained in it is clicked!
	$retval = $api->listSubscribe( $list_id, $user_email, $merge_vars );
}


/* Sendreach */


function wpautoc_ar_sendreach_authenticate() {

	if (!class_exists('SRapi'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/sendreach/classes.php');

    $plugin_options = wpautoc_get_plugin_settings();

	if (!isset($plugin_options['autoresponders']['sendreach']['user']) || empty($plugin_options['autoresponders']['sendreach']['user']) ||
	!isset($plugin_options['autoresponders']['sendreach']['apikey']) || empty($plugin_options['autoresponders']['sendreach']['apikey']) ||
	!isset($plugin_options['autoresponders']['sendreach']['apisecret']) || empty($plugin_options['autoresponders']['sendreach']['apisecret'])
		)
		return 0;

	$user_id = $plugin_options['autoresponders']['sendreach']['user'];
	$api_key = $plugin_options['autoresponders']['sendreach']['apikey'];
	$api_secret = $plugin_options['autoresponders']['sendreach']['apisecret'];

	try {
		return $api = new SRapi($api_key, $api_secret, $user_id);
	} catch (Exception $oException) { /*var_dump($oException);*/ return 0; }

}


function wpautoc_get_sendreach_mailing_lists($force_read = 0) {
	// $force_read = 1;
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['sendreach']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['sendreach']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['sendreach']['lists']);
	}

	$lists = wpautoc_query_sendreach_lists();
	$plugin_options['autoresponders']['sendreach']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['sendreach']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['sendreach']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_sendreach_lists() {

	$api = wpautoc_ar_sendreach_authenticate();
	if (!$api) return;


	$ar_list_array = array();

	try {
		$lists_view = $api->lists_view(); // the data is returned in json format
		$lists_view = json_decode($lists_view); // here we convert the json data into a PHP array
		// if ($api->errorCode)
			// return 0;

		foreach ($lists_view as $list) {
			// var_dump($list);
			$ar_list_array[] = array('id' => $list->id, 'name'=> $list->list_name);
		}
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }

}

function wpautoc_ar_sendreach_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_sendreach_authenticate();

	//  set new subscriber vars
	$client_ip = wpautoc_get_visitor_ip();

	$subscriber_add = $api->subscriber_add($list_id, $user_firstname, $user_lastname, $user_email, $client_ip); // the data is returned in json format
	$subscriber_add = json_decode($subscriber_add); // here we convert the json data into a PHP array
	// var_dump($subscriber_add);
}

function wpautoc_ar_sendreach_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	return;
}


/* Sendy */


function wpautoc_ar_sendy_authenticate() {

	if (!class_exists('SendyPHP'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/sendy/SendyPHP.php');

    $plugin_options = wpautoc_get_plugin_settings();

	if (!isset($plugin_options['autoresponders']['sendy']['url']) || empty($plugin_options['autoresponders']['sendy']['url']) ||
	!isset($plugin_options['autoresponders']['sendy']['apikey']) || empty($plugin_options['autoresponders']['sendy']['apikey'])
	|| empty($plugin_options['autoresponders']['sendy']['email']))
		return 0;

	$sendy_url = $plugin_options['autoresponders']['sendy']['url'];
	$api_key = $plugin_options['autoresponders']['sendy']['apikey'];
	$email_from = $plugin_options['autoresponders']['sendy']['email'];

	try {
		$config = array(
		    'api_key' => $api_key, //your API key is available in Settings
		    'installation_url' => $sendy_url,  //Your Sendy installation
		    'email' => $email_from,  //Your Sendy email
		    'list_id' => 'your_list_id_goes_here'
		);

		return $api = new SendyPHP($config);
	} catch (Exception $oException) { /*var_dump($oException);*/ return 0; }

}


function wpautoc_get_sendy_mailing_lists($force_read = 0) {
	// $force_read = 1;
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['sendy']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['sendy']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['sendy']['lists']);
	}

	$lists = wpautoc_query_sendy_lists();
	$plugin_options['autoresponders']['sendy']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['sendy']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['sendy']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_sendy_lists() {

	$api = wpautoc_ar_sendy_authenticate();
	if (!$api) return;


	$ar_list_array = array();

	try {
		$lists_view = $api->getLists(); // the data is returned in json format
		  // var_dump($lists_view);
		$lists_arr = explode(',', $lists_view['message']);
		foreach ($lists_arr as $list) {
			if (empty($list)) continue;
			 // var_dump($list);
			$list_details = explode(':|:', $list);
			   // var_dump($list_details);

			$ar_list_array[] = array('id' => $list_details[0], 'name'=> $list_details[1]);
		}
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }

}

function wpautoc_ar_sendy_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_sendy_authenticate();

	//  set new subscriber vars
	$client_ip = wpautoc_get_visitor_ip();
	 // var_dump($list_id);
	$api->setListId($list_id);

	if (!empty($user_lastname))
		$user_firstname = $user_firstname.' '.$user_lastname;
	$subscriber_details = array(
	                       'name'=>$user_firstname,
	                       'email' => $user_email //this is the only field required by sendy
	                       );
	if ($tag_name && $tag_value) {
		$subscriber_details[$tag_name] = $tag_value;
	}

	$results = $api->subscribe($subscriber_details);
	// var_dump($results);
}

function wpautoc_ar_sendy_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	$api = wpautoc_ar_sendy_authenticate();
	$subscriber_details = array(
	                       'email' => $user_email //this is the only field required by sendy
	                       );
	$api->setListId($list_to_id);
	$results = $api->subscribe($subscriber_details);
	// var_dump($results);
	if ($delete) {
		$api->setListId($list_from_id);
		$results = $api->unsubscribe($user_email);
	}


	return;
}



/* Active Campaign */


function wpautoc_ar_activecampaign_authenticate() {

	if (!class_exists('ActiveCampaign'))
		include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/activecampaign/ActiveCampaign.class.php');

    $plugin_options = wpautoc_get_plugin_settings();

	if (!isset($plugin_options['autoresponders']['activecampaign']['url']) || empty($plugin_options['autoresponders']['activecampaign']['url']) ||
	!isset($plugin_options['autoresponders']['activecampaign']['apikey']) || empty($plugin_options['autoresponders']['activecampaign']['apikey'])
	) 
		return 0;

	$activecampaign_url = $plugin_options['autoresponders']['activecampaign']['url'];
	$api_key = $plugin_options['autoresponders']['activecampaign']['apikey'];

	try {
		$ac = new ActiveCampaign($activecampaign_url, $api_key);

		if (!(int)$ac->credentials_test()) {
			return 0;
		}

		return $ac;
	} catch (Exception $oException) { /*var_dump($oException);*/ return 0; }

}


function wpautoc_get_activecampaign_mailing_lists($force_read = 0) {
	// $force_read = 1;
	$plugin_options = wpautoc_get_plugin_settings();
// $force_read = 1;
	if (isset($plugin_options['autoresponders']['activecampaign']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['activecampaign']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['activecampaign']['lists']);
	}

	$lists = wpautoc_query_activecampaign_lists();
	// var_dump($lists);
	$plugin_options['autoresponders']['activecampaign']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['activecampaign']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['activecampaign']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_activecampaign_lists() {

	$api = wpautoc_ar_activecampaign_authenticate();
	if (!$api) return;

	/*$account = $api->api("account/view");

	echo "<pre>";
	print_r($account);
	echo "</pre>";*/
	$ar_list_array = array();

	try {
		// $lists_view = $api->getLists(); // the data is returned in json format
		// $lists_arr = explode(',', $lists_view['message']);
		$params = array(
			'ids' => 'all');
		$lists_arr = $api->api("list/list_", $params);
		// var_dump($lists_arr);
		$lists_arr =  (array) $lists_arr;
		// var_dump($lists_arr);

		foreach ($lists_arr as $key => $list) {
			if (empty($list)) continue;
			if (!is_numeric($key))
				continue;
			 // var_dump($list);
			// $list_details = explode(':|:', $list);
			   // var_dump($list_details);

			$ar_list_array[] = array('id' => $list->id, 'name'=> $list->name);
		}
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }	

}

function wpautoc_ar_activecampaign_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_activecampaign_authenticate();

	//  set new subscriber vars
	$client_ip = wpautoc_get_visitor_ip();
	 // var_dump($list_id);
	// $api->setListId($list_id);

	if (!empty($user_lastname))
		$user_firstname = $user_firstname.' '.$user_lastname;

	$contact = array(
		"email"              => $user_email,
		"first_name"         => $user_firstname,
		"last_name"          => $user_lastname,
		"p[{$list_id}]"      => $list_id,
		"status[{$list_id}]" => 1, // "Active" status
	);

	$contact_sync = $api->api("contact/sync", $contact);
	if ($tag_value && !empty($tag_value)) {
		$tag = array(
			"tags"              => $tag_value
		);
		$contact_sync = $api->api("contact/tag_add", $tag);
	}
	// var_dump($results);
}

function wpautoc_ar_activecampaign_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	if (!$user_email || empty($user_email)) return;
	$api = wpautoc_ar_activecampaign_authenticate();

	// $results = $api->api("contact/view?email=".$user_email);
	// $user_id = $results->subscriberid;

	$params = array(
		'email' => $user_email,
		"p[$list_to_id]" => $list_to_id
		);
	$results = $api->api("contact/sync", $params);

	if ($delete && $list_from_id) {
		// also remove from old list
		$results = $api->api("contact/view?email=".$user_email);
		$user_id = $results->subscriberid;

		$params = array(
			'id' => $user_id,
			// "listids[$list_from_id]" => $list_from_id
			"listids[$list_from_id]" => $list_from_id
			);
		$results = $api->api("contact/delete", $params);
		
	}
	/*if ($delete) {
		$api->setListId($list_from_id);
		$results = $api->unsubscribe($user_email);
	}*/


	return;
}



/* Sendlane */


function wpautoc_ar_sendlane_authenticate() {

	include_once(WPAUTOC_DIR.'/lib/libs/autoresponders/sendlane/sendlane_api.php');

    $plugin_options = wpautoc_get_plugin_settings();

	if (!isset($plugin_options['autoresponders']['sendlane']['url']) || empty($plugin_options['autoresponders']['sendlane']['url']) ||
	!isset($plugin_options['autoresponders']['sendlane']['apikey']) || empty($plugin_options['autoresponders']['sendlane']['apikey']) ||
	!isset($plugin_options['autoresponders']['sendlane']['hashkey']) || empty($plugin_options['autoresponders']['sendlane']['hashkey'])
	) 
		return 0;

	$sendlane_url = $plugin_options['autoresponders']['sendlane']['url'];
	$api_key = $plugin_options['autoresponders']['sendlane']['apikey'];
	$hash_key = $plugin_options['autoresponders']['sendlane']['hashkey'];

	return array( $sendlane_url, $api_key, $hash_key );
	// try {
	// 	$ac = new sendlane($sendlane_url, $api_key);

	// 	if (!(int)$ac->credentials_test()) {
	// 		return 0;
	// 	}

	// 	return $ac;
	// } catch (Exception $oException) { /*var_dump($oException);*/ return 0; }

}


function wpautoc_get_sendlane_mailing_lists($force_read = 0) {
	// return;
	// $force_read = 1;
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['sendlane']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['sendlane']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['sendlane']['lists']);
	}

	$lists = wpautoc_query_sendlane_lists();
	// var_dump($lists);
	$plugin_options['autoresponders']['sendlane']['cache_expires'] = time() + (24 * 3600 * 3);
	// $plugin_options['autoresponders']['sendlane']['cache_expires'] = time() + (1);
	$plugin_options['autoresponders']['sendlane']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_sendlane_lists() {

	$api = wpautoc_ar_sendlane_authenticate();
	if ( !$api ) return;

	$ar_list_array = array();

	try {
		// var_dump($api);
		$lists_arr = vprofits_sendlane_get_lists( $api ) ;
		// var_dump($lists_arr);
		// return;
		// $lists_arr =  (array) $lists_arr;
		// var_dump($lists_arr);

		foreach ($lists_arr as $key => $list) {
			// if (empty($list)) continue;
			// if (!is_numeric($key))
			// 	continue;
			 // var_dump($list);
			// $list_details = explode(':|:', $list);
			   // var_dump($list_details);

			$ar_list_array[] = array('id' => $list->list_id, 'name'=> $list->list_name);
		}
		return $ar_list_array;
	} catch (Exception $oException) { return 0; }	

}

function wpautoc_ar_sendlane_subscribe_user($list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='') {
	$api = wpautoc_ar_sendlane_authenticate();

	//  set new subscriber vars
	$client_ip = wpautoc_get_visitor_ip();

	return vprofits_sendlane_api_subscribe( $api, $list_id, $user_email, $user_firstname, $user_lastname = '' );
	 // var_dump($list_id);
	// $api->setListId($list_id);

	// if (!empty($user_lastname))
	// 	$user_firstname = $user_firstname.' '.$user_lastname;

	/*$contact = array(
		"email"              => $user_email,
		"first_name"         => $user_firstname,
		"last_name"          => $user_lastname,
		"p[{$list_id}]"      => $list_id,
		"status[{$list_id}]" => 1, // "Active" status
	);

	$contact_sync = $api->api("contact/sync", $contact);
	if ($tag_value && !empty($tag_value)) {
		$tag = array(
			"tags"              => $tag_value
		);
		$contact_sync = $api->api("contact/tag_add", $tag);
	}*/
	// var_dump($results);
}

function wpautoc_ar_sendlane_move_subscriber($list_from_id=0, $list_to_id=0, $user_email=0, $delete=0) {
	if (!$user_email || empty($user_email)) return;
	$api = wpautoc_ar_activecampaign_authenticate();

	// $results = $api->api("contact/view?email=".$user_email);
	// $user_id = $results->subscriberid;

	$params = array(
		'email' => $user_email,
		"p[$list_to_id]" => $list_to_id
		);
	$results = $api->api("contact/sync", $params);

	if ($delete && $list_from_id) {
		// also remove from old list
		$results = $api->api("contact/view?email=".$user_email);
		$user_id = $results->subscriberid;

		$params = array(
			'id' => $user_id,
			// "listids[$list_from_id]" => $list_from_id
			"listids[$list_from_id]" => $list_from_id
			);
		$results = $api->api("contact/delete", $params);
		
	}
	/*if ($delete) {
		$api->setListId($list_from_id);
		$results = $api->unsubscribe($user_email);
	}*/


	return;
}
/* Mailit Plugin */

function wpautoc_is_mailit_plugin_active() {
	return is_plugin_active( 'mailit/mailit.php' );
}

/* 1 es local, 2 es remoto */
function wpautoc_ar_mailit_type() {
	$plugin_options = wpautoc_get_plugin_settings();

	if ( isset( $plugin_options['autoresponders']['mailit']['install_type'] ) )
		return $plugin_options['autoresponders']['mailit']['install_type'];
	return 1;
}

function wpautoc_ar_mailit_subscribe_user( $list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='' ) {
	$type = wpautoc_ar_mailit_type();

	if( $type == 2 ) {
		// remote
		$plugin_options = wpautoc_get_plugin_settings();
		if ( isset( $plugin_options['autoresponders']['mailit']['list_url'] ) )
			$MailItURL = $plugin_options['autoresponders']['mailit']['list_url'];
		if( !$MailItURL || empty( $MailItURL) )
			return;
		$MailItURL=trim($MailItURL);
		if( empty( $user_firstname) )
			$user_firstname = '';
		$data = array('email' => $user_email, 'name' => $user_firstname);
		$handle = curl_init($MailItURL);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		curl_exec($handle);
		return;
	}
	else if( $type == 1 ) {
		global $wpdb;
		$Table = $wpdb->prefix . "emailit_subscribers";
 		$DateX=date('m/d/Y');
 		$Results2 = $wpdb->get_results( "SELECT * FROM $Table WHERE EmailAddress='$user_email' AND List='$list_id' ");
 		$n=$wpdb->num_rows;
 		if ($n<1){
		$wpdb->insert( $Table, array( 'FirstName' => $user_firstname, 'EmailAddress' => $user_email, 'List' => $list_id, 'Status' => '1', 'DateX' => $DateX ));
		}
	}
	return;
}

function wpautoc_get_mailit_mailing_lists($force_read = 0) {
	// $force_read = 1;
	$plugin_options = wpautoc_get_plugin_settings();

	if (isset($plugin_options['autoresponders']['mailit']['cache_expires']) && ($expiration = $plugin_options['autoresponders']['mailit']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['autoresponders']['mailit']['lists']);
	}

	$lists = wpautoc_query_mailit_lists();
	$plugin_options['autoresponders']['mailit']['cache_expires'] = time() + (24 * 3600 * 3);
	$plugin_options['autoresponders']['mailit']['lists'] = $lists;
	wpautoc_save_plugin_settings( $plugin_options );
	return $lists;
}

function wpautoc_query_mailit_lists() {
	$type = wpautoc_ar_mailit_type();
	$ar_list_array = array();
	if( $type == 2 )
		return false;
	else {
		global $wpdb;
		if (! wpautoc_is_plugin_there( '/mailit' ) )
			return false;
		$Table=$wpdb->prefix . "emailit_lists";
		$Results1 = $wpdb->get_results( "SELECT * FROM $Table");
		if( !$Results1 || empty( $Results1) )
			return false;
		foreach ( $Results1 as $ro ){
				$ar_list_array[] = array('id' => $ro->L_ID, 'name'=> trim($ro->ListName) );
		}
		return $ar_list_array;
	}
}

/** My mailit */

function wpautoc_ar_mymailit_subscribe_user( $list_id, $user_email, $user_firstname = '', $user_lastname = '', $tag_name='', $tag_value='' ) {
		// remote
		$plugin_options = wpautoc_get_plugin_settings();
		// var_dump($plugin_options);
		if ( isset( $plugin_options['autoresponders']['mymailit']['list_url'] ) )
			$MailItURL = $plugin_options['autoresponders']['mymailit']['list_url'];
		// var_dump($MailItURL);
		if( !$MailItURL || empty( $MailItURL) )
			return;

		if( empty( $user_firstname ) )
			$user_firstname = '';
		$MailItURL=trim($MailItURL);
		$data = array('email' => $user_email, 'name' => $user_firstname );
		$handle = curl_init($MailItURL);
		curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		$Response=curl_exec($handle);
		// var_dump($Response);
		curl_close($handle);
	return;
}

function wpautoc_get_visitor_ip() {
	$hostname = getenv('HTTP_HOST');
	if ($hostname == 'localhost')
		return '80.28.15.4';
	return $_SERVER['REMOTE_ADDR'];
}

function wpautoc_refresh_autoresponder() {
	$ar_type = $_POST['ar_type'];
	wpautoc_force_refresh_autoresponder( $ar_type );
}
?>