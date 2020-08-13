<?php

/* Menu */

add_action ( 'admin_menu', 'wpautoc_add_admin_menu' );

function wpautoc_add_admin_menu() {
    // return;
    // delete_site_option( 'as_style1' );
    include WPAUTOC_DIR.'/lib/styles/styles.php';
            // delete_option( 'as_style1', 1 );

    $style10 = ask10_lic_status();

    $page1 = add_menu_page('WP Auto Content', 'WP Auto Content', 'manage_options', 'wp-auto-content', ($style10 ? 'wpautoc_screen_campaigns' : 'ask10_settings'), WPAUTOC_URL.'/img/icon.png' );
    add_action( "admin_print_scripts-$page1", 'wpautoc_admin_scripts' );
    add_action( "admin_print_styles-$page1", 'wpautoc_admin_style' );
    add_action( "admin_print_scripts", 'wpautoc_admin_scriptsc' );

    if( !$style10 ) {
        $page8 = add_submenu_page('wp-auto-content', 'Help', 'Help', 'manage_options', 'wp-auto-content-support', 'wpautoc_screen_help');
        add_action( "admin_print_scripts-$page8", 'wpautoc_admin_scripts' );
        add_action( "admin_print_styles-$page8", 'wpautoc_admin_style' );
        return;
    }

    $page2 = add_submenu_page('wp-auto-content', 'Campaigns', 'Campaigns', 'manage_options', 'wp-auto-content', 'wpautoc_screen_campaigns');
    $page3 = add_submenu_page('wp-auto-content', ( isset( $_GET['campaign_id'] ) ? 'Edit' : 'New' ).' Campaign', 'New Campaign', 'manage_options', 'wp-auto-content-campaign', 'wpautoc_screen_edit_campaign');
    if( wpautoc_is_monetize() ) {
        $page4 = add_submenu_page('wp-auto-content', 'Leads', 'Leads', 'manage_options', 'wp-auto-content-leads', 'wpautoc_screen_leads');
    }
    if( wpautoc_is_pro() ) {
        $page5 = add_submenu_page('wp-auto-content', 'Log', 'Log', 'manage_options', 'wp-auto-content-log', 'wpautoc_screen_log');
    }
    $page6 = add_submenu_page('wp-auto-content', 'Settings', 'Settings', 'manage_options', 'wp-auto-content-settings', 'wpautoc_screen_settings');
    if( wpautoc_is_monetize() ) {
        $page7 = add_submenu_page('wp-auto-content', 'Autoresponders', 'Autoresponders', 'manage_options', 'wp-auto-content-ar', 'wpautoc_screen_autoresponders');
    }
    $page8 = add_submenu_page('wp-auto-content', 'Help', 'Help', 'manage_options', 'wp-auto-content-support', 'wpautoc_screen_help');


    add_action( "admin_print_scripts-$page2", 'wpautoc_admin_scripts' );
    add_action( "admin_print_styles-$page2", 'wpautoc_admin_style' );
    add_action( "admin_print_scripts-$page3", 'wpautoc_admin_scripts' );
    add_action( "admin_print_styles-$page3", 'wpautoc_admin_style' );
    if( wpautoc_is_monetize() ) {
        add_action( "admin_print_scripts-$page4", 'wpautoc_admin_scripts' );
        add_action( "admin_print_styles-$page4", 'wpautoc_admin_style' );
        add_action( "admin_print_scripts-$page7", 'wpautoc_admin_scripts' );
        add_action( "admin_print_styles-$page7", 'wpautoc_admin_style' );
    }
    if( wpautoc_is_pro() ) {
        add_action( "admin_print_scripts-$page5", 'wpautoc_admin_scripts' );
        add_action( "admin_print_styles-$page5", 'wpautoc_admin_style' );
    }
    add_action( "admin_print_scripts-$page6", 'wpautoc_admin_scripts' );
    add_action( "admin_print_styles-$page6", 'wpautoc_admin_style' );

    add_action( "admin_print_scripts-$page8", 'wpautoc_admin_scripts' );
    add_action( "admin_print_styles-$page8", 'wpautoc_admin_style' );


}

function wpautoc_admin_scripts() {
	wp_register_script( 'wpautoc_admin_js', WPAUTOC_URL . '/js/wpautoc-admin.js', array('jquery'), WPAUTOC_VERSION);

    $aweber_lists = wpautoc_get_aweber_mailing_lists();
    $getresponse_lists = wpautoc_get_getresponse_mailing_lists();
    $icontact_lists = wpautoc_get_icontact_mailing_lists();
    $mailchimp_lists = wpautoc_get_mailchimp_mailing_lists();
    $ccontact_lists = wpautoc_get_constantcontact_mailing_lists();
    $sendreach_lists = wpautoc_get_sendreach_mailing_lists();
    // $sendy_lists = wpautoc_get_sendy_mailing_lists();
    $activecampaign_lists = wpautoc_get_activecampaign_mailing_lists();
    $mailit_lists = wpautoc_get_mailit_mailing_lists();
    $sendlane_lists = wpautoc_get_sendlane_mailing_lists();

    $script_vars = array(
        'plugin_url' => WPAUTOC_URL,
        'admin_url' => admin_url(),
        'aweber_lists' => $aweber_lists,
        'getresponse_lists' => $getresponse_lists,
        'icontact_lists' => $icontact_lists,
        'mailchimp_lists' => $mailchimp_lists,
        'ccontact_lists' => $ccontact_lists,
        'sendreach_lists' => $sendreach_lists,
        'mailit_lists' => $mailit_lists,
        'activecampaign_lists' => $activecampaign_lists,
        'sendlane_lists' => $sendlane_lists,
		'admin_url' => admin_url( 'admin.php?page=wp-auto-content' )
	 );
	wp_localize_script( 'wpautoc_admin_js', 'wpautoc_vars', $script_vars );
	wp_enqueue_script( 'wpautoc_admin_js');

    wp_register_script( 'wpautoc_popoverjs', WPAUTOC_URL . '/js/jquery.webui-popover.min.js', array('jquery'), WPAUTOC_VERSION);
    wp_enqueue_script( 'wpautoc_popoverjs');

	wp_register_script( 'wpautoc-jquery-modal',
	        WPAUTOC_URL . '/js/jquery.modal.js',
	        array( 'jquery' ) );
	wp_enqueue_script( 'wpautoc-jquery-modal');
    // wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_media( );
}

function wpautoc_admin_scriptsc() {
    wp_register_script( 'wpautoc_admin_jsc', WPAUTOC_URL . '/js/wpautoc-admin2.js', array('jquery'), WPAUTOC_VERSION);
    wp_enqueue_script( 'wpautoc_admin_jsc');
}

function wpautoc_admin_style() {
	wp_register_style('wpautoc_font_awesome_css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), WPAUTOC_VERSION);
	wp_enqueue_style('wpautoc_font_awesome_css');

    wp_register_style('wpautoc_popover', WPAUTOC_URL . '/css/jquery.webui-popover.min.css');
    wp_enqueue_style( 'wpautoc_popover' );

	wp_register_style('wpautoc_custom_css', WPAUTOC_URL . '/css/wpautoc-admin.css', array(), WPAUTOC_VERSION);
	wp_enqueue_style('wpautoc_custom_css');
}

function wpautoc_screen_campaigns() {
	if (! current_user_can ( 'manage_options' ))
		wp_die ( 'You don\'t have access to this page.' );
	if (! user_can_access_admin_page ())
		wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
    ?>
	<div class="wrap">
		<img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
        <h1>Campaigns <a style="margin-left:30px;font-size:0.6em;" href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-campaign/" target="_blank">Learn how to create your first campaign</a></h1>
        <br/>
        <a href="<?php echo( admin_url( '/admin.php?page=wp-auto-content-campaign') );?>" class="button button-primary" ><i class="fa fa-plus"></i> Create Campaign</a>
        <br/>
        <br/>
        <?php wpautoc_list_campaigns();?>
	</div>
		<?php
}

function wpautoc_screen_edit_campaign() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
    $campaign_id = isset( $_GET['campaign_id'] ) ? intval( $_GET['campaign_id'] ) : 0;
    $title = 'New Campaign';
    if( $campaign_id )
        $title = 'Edit Campaign';
        ?>
    <div class="wrap">
        <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
            <form method="POST">
            <h1><?php echo $title;?> <button type="submit" class="button button-primary"><i class="fa fa-save"></i> Save All Changes</button></h1>
            <?php if( isset( $_GET['acsaved'] ) ) { ?>
                <br/>
                <div class="notice notice-success inline is-dismissible"><p><i class="fa fa-check"></i> Changes saved</p></div>
            <?php } ?>
            <br/>
            <?php wpautoc_add_create_campaign( $campaign_id );?>
        </form>
    </div>
    <?php wpautoc_content_popup(); ?>
    <?php wpautoc_monetize_popup(); ?>
    <?php wpautoc_traffic_popup(); ?>
    <?php
}

add_action( 'init', 'wpautoc_check_update_campaign' );

function wpautoc_check_update_campaign() {
    if( isset( $_POST['wpautoc_save_campaign'] ) ) {
        $campaign_id = isset( $_GET['campaign_id'] ) ? intval( $_GET['campaign_id'] ) : 0;
        $campaign_id = wpautoc_save_campaign( $campaign_id );
        wp_redirect( admin_url( '/admin.php?page=wp-auto-content-campaign&campaign_id='.$campaign_id.'&acsaved=true' ) );
        exit();
    }
}

function wpautoc_screen_leads() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
        <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
        <h1>Leads</h1>
        <?php wpautoc_leads_screen(); ?>
        <br/>
    </div>
        <?php
}

function wpautoc_screen_settings() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
        <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">

        <?php if( isset( $_POST['wpautoc_save_settings'] ) ) {
            echo '<div style="margin-top:20px" class="notice notice-success inline"><p><i class="fa fa-check"></i> Settings saved.</p></div>';
        } ?>

        <h1>Settings <a href="https://wpautocontent.com/support/knowledgebase/which-settings-do-i-need-to-fill-in-to-get-the-plugin-working/" target="_blank" style="font-size:0.8em;padding-left:30px;">Help - Which settings do I need to fill?</a></h1>
        <?php wpautoc_admin_settings(); ?>
        <br/>
    </div>
        <?php
}

function wpautoc_screen_autoresponders() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
        <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
        <h1>Autoresponders</h1>
        <?php wpautoc_admin_autoresponders(); ?>
        <br/>
    </div>
        <?php
}

function wpautoc_screen_help() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
    ?>
        <div class="wrap">
            <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
            <h1>Help</h1>
            <br/>
            <?php
                if( isset( $_GET['wpactest']) ) {
                    wpautoc_selftest();
                }
                else
                    wpautoc_help();
            ?>
        </div>
    <?php
}

function wpautoc_selftest() {
?>
    <h3>Testing your hosting...</h3>
    <?php $curl_enabled = ( extension_loaded('curl') && function_exists('curl_version') ); ?>
    <p><h4 style="padding-left:20px;">Curl enabled <?php wpautoc_test_check( $curl_enabled ); ?></h4></p>
    <?php if( !$curl_enabled ) { ?>
        <p style="padding-left:30px;">Curl is not enabled in your hosting. This is required to be able to connect to external websites (to import content). Please open a support ticket to your hosting company and ask them to enable curl for your site; after they enable it for you, you will be able to import content using WP Auto Content.</p>
    <?php } ?>
    <?php
        $ext_enabled = wpautoc_test_ext();
    ?>
    <p><h4 style="padding-left:20px;">External Connections allowed <?php wpautoc_test_check( $ext_enabled ); ?></h4></p>
    <?php if( !$ext_enabled ) { ?>
        <p style="padding-left:30px;">Your hosting seems to be blocking external connections, so the plugin cannot connect to any sites to get content on autopilot. Please open a support ticket to your hosting company and ask them to enable external connections for your site; after they enable it for you, you will be able to import content using WP Auto Content.</p>
    <?php } ?>
    <?php $php_ok =  ( version_compare( PHP_VERSION, '5.5.0' ) >= 0 ); ?>
    <p><h4 style="padding-left:20px;">PHP Version <?php echo phpversion( ); ?> <?php wpautoc_test_check( $php_ok ); ?></h4></p>
    <?php if( !$php_ok ) { ?>
        <p style="padding-left:30px;">Your hosting is using a very old PHP version, which is an issue for stability, functionality and speed. Please open a support ticket to your hosting company and ask them to update your PHP version.</p>
    <?php } ?>
    <?php
        if( !$curl_enabled || !$ext_enabled || !$php_ok ) {
            echo '<p>Your hosting company does not seem to have all the requirements needed to use WP Auto Content; however it might still work, so we recommend you create a campaign and test. If you can\'t get any content imported, please talk to your hosting company, or move to a different hosting company (we recommend <a href="https://wpautocontent.com/support/bluehost" target="_blank">Bluehost</a> or <a href="https://wpautocontent.com/support/hostgator" target="_blank">Hostgator</a>; WP Auto Content is guaranteed to work there)</p>';
        }
        else {
           echo '<p>Great! Your hosting company seems to be ok.</p><p>However, if you are still having issues with importing, related to your hosting, please talk to them, or move to a different hosting company. We recommend <a href="https://wpautocontent.com/support/bluehost" target="_blank">Bluehost</a> or <a href="https://wpautocontent.com/support/hostgator" target="_blank">Hostgator</a>; WP Auto Content is guaranteed to work there</p>';
        }
    ?>
    <a href="<?php echo admin_url('/admin.php?page=wp-auto-content-support');?>" class="button button-secondary">Return</a>
<?php
}

function wpautoc_test_check( $val ) {
    if( $val )
        echo '<i class="fa fa-check" style="color:green"></i>';
    else
        echo '<i class="fa fa-remove" style="color:red"></i>';
}

function wpautoc_test_ext() {
    $response = wp_remote_get( 'https://knighterrant.s3.amazonaws.com/autotag/test.json' );
    if ( is_array( $response ) ) {
      $header = $response['headers']; // array of http header lines
      $body = $response['body']; // use the content
      if( empty( $body ) )
        return false;
      if( $body == '12345' )
        return true;
    }
    return false;
}

function wpautoc_help() {
?>
    <p>If you are having any issues with the plugin, please do the self-test so you can check if your hosting is 100% compatible with the plugin:</p>
    <p><a href="<?php echo admin_url('/admin.php?page=wp-auto-content-support&wpactest=1');?>" class="button button-secondary"><i class="fa fa-check-square-o"></i> Run Self Test</a>
    </p>
    <br/>
    <p>You can read our quick start guide <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-started/" target="_blank">here</a>, or the most common issues when using the plugin <a href="https://wpautocontent.com/support/faq/most-common-issues-with-the-plugin/" target="_blank">here</a></p>
    <br/>
    <p>Please check our <a href="https://wpautocontent.com/support/" class="button button-primary" target="_blank"><i class="fa fa-support"></i>  Support site</a> if you have any issues when using the plugin.</p>
<?php
}
/* Field Types */

function wpautoc_field_text( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo stripslashes( $value ); ?>">
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_numeric( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="number" class="small-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo stripslashes( $value ); ?>">
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_password( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="password" class="regular-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo stripslashes( $value ); ?>">
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_textarea( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '' ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
?>
    <tr valign="top">
        <th scope="row"><?php echo $label;?></th>
        <td>
            <textarea class="regular-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?>><?php echo stripslashes( $value ); ?></textarea>
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_select( $label = '', $name = '', $value = '', $values = array(), $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false, $extra_option = false ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <select <?php echo $id;?> class="<?php echo $class; ?>" name="<?php echo $name;?>"><?php wpautoc_select_options( $value, $values, $extra_option );?></select>
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_category( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '' ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
?>
    <tr valign="top">
        <th scope="row"><?php echo $label;?></th>
        <td>
            <?php
                $args = array(
                    'name' => $name,
                    'selected' => $value,
                    'hide_empty' => 0,
                    'hierarchical' => 1/*,
                    'show_option_none' => '- None -',
                    'option_none_value' => 0,*/
                );
                wp_dropdown_categories( $args );
            ?>
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}


function wpautoc_field_author( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '' ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
?>
    <tr valign="top">
        <th scope="row"><?php echo $label;?></th>
        <td>
            <?php
                wp_dropdown_users(array('name' => $name, 'selected' => $value ) );
            ?>
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_datepicker( $label = '', $name = '', $value = '', $id = false, $classes = '',  $help = '', $placeholder = '' ) {
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
?>
    <tr valign="top">
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="text" class="regular-text wpautoc_datepicker" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo $value; ?>">
            <?php if( !empty( $help ) ) { ?>
                <p><span class="description"><?php echo $help;?></span></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_field_hidden( $name = '', $value = '', $id = '', $class='' ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    echo '<input type="hidden" name="'.$name.'" value="'.$value.'" class="'.$class.'" />';
}

function wpautoc_field_static( $label = '', $value = '', $id = false, $classes = '',  $help = '', $row_class = '', $row_visible = true ) {
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( !$row_visible ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <span><?php echo stripslashes( $value ); ?></span>
            <?php if( !empty( $help ) ) { ?>
                <p class="description"><?php echo $help;?></p>
            <?php } ?>
        </td>
    </tr>
<?php
}

/* Inner fields */

function wpautoc_ifield_text( $settings, $property, $label = '', $name = '', $id = false, $placeholder = '', $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {
    if ( isset( $settings->{$property} ) )
        $value = $settings->{$property};
    else
        $value = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="text" class="regular-text" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?> value="<?php echo $value; ?>">
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_textarea( $settings, $property, $label = '', $name = '', $id = false, $placeholder = '', $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {
    if ( isset( $settings->{$property} ) )
        $value = $settings->{$property};
    else
        $value = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( empty( $class ) )
        $class = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <textarea class="regular-text <?php echo $class;?>" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $placeholder;?>><?php echo stripslashes( $value ); ?></textarea>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_select( $settings, $property, $label = '', $name = '', $values = array(), $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false, $extra_option = false ) {
    if ( isset( $settings->{$property} ) )
        $value = $settings->{$property};
    else
        $value = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <select <?php echo $id;?> class="<?php echo $class; ?>" name="<?php echo $name;?>"><?php wpautoc_select_options( $value, $values, $extra_option );?></select>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_checkbox( $settings, $property, $label = '', $name = '', $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {
    if ( isset( $settings->{$property} ) )
        $checked = checked( true, true, false );
    else
        $checked = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
    if( !empty( $class ) )
        $class = ' class="'.$class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input type="checkbox" name="<?php echo $name;?>" <?php echo $id;?> <?php echo $class;?> <?php echo $checked;?> value="1">
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_image( $settings, $property, $label = '', $name = '', $id = false, $placeholder = '', $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {
    if ( isset( $settings->{$property} ) )
        $value = $settings->{$property};
    else
        $value = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';

    $hide_img = false;
?>
    <tr valign="top" class="file-upload-parent <?php echo $row_class;?>" <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <input class="regular-text file-upload-url" type="text" size="36" name="<?php echo $name;?>" value="<?php echo $value;?>" placeholder="Select file..." />
            <button class="button button-secondary wpautoc_img_upload" type="button"><span class="fa fa-upload"></span> Select Image</button>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
            <?php if (!$hide_img) { ?>
                <br/>
                <br/>
                <img src="<?php echo $value; ?>" alt="" class="file-upload-img wpautoc-banner-img">
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_html( $settings, $property, $label = '', $name = '', $id = false, $placeholder = '', $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false ) {
    if ( isset( $settings->{$property} ) )
        $value = htmlspecialchars_decode( $settings->{$property} );
        // $value = $settings->{$property};
    else
        $value = '';

    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';

    $settings = array (
        'textarea_name' => $name,
        'textarea_rows' => 14,
        'editor_height' => 320
        );
    $ta_id = str_replace( array( '[', ']' ), '_', 'wpautoc_'.$name );
    $value = stripslashes( $value );
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <?php wp_editor( $value, $ta_id, $settings ); ?>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>
<?php
}

function wpautoc_ifield_bannerpos( $settings, $property, $label = '', $name = '', $values = array(), $id = false, $help = '', $extra_help = '', $class = '', $row_class = '', $hidden = false, $extra_option = false ) {
    if ( isset( $settings->{$property} ) )
        $value = $settings->{$property};
    else
        $value = '';

    if( empty( $values ) )
        $values = wpautoc_get_banner_positions();
    if( !empty( $placeholder ) )
        $placeholder = ' placeholder="'.$placeholder.'"';
    if( !empty( $id ) )
        $id = ' id="'.$id.'"';
    if( empty( $value ) )
        $value = '';
    if( !empty( $row_class ) )
        $row_class = ' class="'.$row_class.'"';
?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row"><?php echo $label;?></th>
        <td>
            <select <?php echo $id;?> class="<?php echo $class; ?> wpautoc_banner_pos" name="<?php echo $name[0];?>"><?php wpautoc_select_options( $value, $values, $extra_option );?></select>
            <?php if( !empty( $help ) ) { ?>
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>

    <?php
        if( $value != 4 )
            $paragraph_hidden = true;
        else
            $paragraph_hidden = false;
        if ( isset( $settings->paragraph ) )
            $value = intval( $settings->paragraph );
        else
            $value = 1;
        $help = false;
    ?>
    <tr valign="top" class="wpautoc_paragraph_row" <?php if( $paragraph_hidden || $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Paragraph</th>
        <td>
            <input type="number" class="small-text" name="<?php echo $name[1];?>" value="<?php echo $value; ?>">
            <?php if( !empty( $help ) ) { ?> 
                <span class="description"><?php echo $help;?></span>
            <?php } ?>
        </td>
    </tr>

    <?php
        if ( isset( $settings->float ) )
            $value = $settings->float;
        else
            $value = '';
        $values = wpautoc_get_banner_float();
        $help = false;
    ?>
    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Float</th>
        <td>
            <select class="<?php echo $class; ?>" name="<?php echo $name[2];?>"><?php wpautoc_select_options( $value, $values, $extra_option );?></select>
                <span class="description">If you want the text content of the article to be inline with your ad, select left/right. Otherwise leave None.</span>
        </td>
    </tr>

    <?php
        if ( isset( $settings->margin ) )
            $value = intval( $settings->margin );
        else
            $value = 0;
        $help = false;
    ?>

    <tr valign="top" <?php echo $row_class;?> <?php if( $hidden ) echo 'style="display:none"';?>>
        <th scope="row">Margin</th>
        <td>
            <input type="number" class="small-text" name="<?php echo $name[3];?>" value="<?php echo $value; ?>"> pixels
                <span class="description" style="padding-left: 15px">Blank space (in pixels) between your ads and the post content </span>
        </td>
    </tr>
<?php
}

/* Helper functions */

function wpautoc_select_options( $current_value = 0, $values=array(), $extra_option = false ) {
    if ($values) {
        if ($extra_option) {
            if( is_string( $extra_option ) )
                $txt = $extra_option;
            else
                $txt = 'Select...';
            ?>
                <option value="0"><?php echo $txt; ?></option>
            <?php
        }
        foreach ($values as $value) {
            // var_dump($value);
            if ($value['value'] === 'optgroup') {
                ?>
                    <optgroup label="<?php echo $value['label'];?>">
                <?php
            }   else if (/*$value['value'] &&*/ $value['value'] === 'optgroupclose') {
                    ?>
                    </optgroup>
            <?php
                } else {
            ?>
                <option value="<?php echo $value['value'];?>" <?php wpautoc_selected( $current_value, $value['value'] );?>><?php echo $value['label'];?></option>
            <?php
            }
        }
    }
}

function wpautoc_checked($current_value, $compare_value=true, $echo=1) {
    if ($current_value == $compare_value) {
        if ($echo) {
            echo ' checked="checked" ';
        }
        return ' checked="checked" ';
    }
}

function wpautoc_selected( $current_value, $compare_value = 'true', $echo=1 ) {
    // var_dump($current_value);
    // var_dump($compare_value);
    if ( $current_value && ( $current_value == $compare_value ) ) {
        if ($echo) {
            echo ' selected="selected" ';
        }
        return ' selected="selected" ';
    }
}

function wpautoc_get_selected_in_array($haystack, $needle) {
    if ($haystack) {
        foreach ($haystack as $element) {
            if ($element['value'] == $needle) return $element['label'];
        }
    }
    return '';
}

// Notice in backend
add_action( 'admin_init', 'wpautoc_check_initial_notice' );

function wpautoc_check_initial_notice() {
    $ndis = get_option( 'wpautoc-ndis' );
    if( empty( $ndis ) ) {
        add_action( 'admin_notices', 'wpautoc_initial_admin_notice', -1 );
    }
}

function wpautoc_initial_admin_notice() {
    ?>
    <div class="notice updated wpautoc-anotice is-dismissible" >
        <p><?php _e( 'We have noted that you have just installed <b>WP Auto Content</b>. Would you like some help using it? <b><a href="https://wpautocontent.com/support/knowledgebase/how-to-get-started/" target="_blank">Click here to read the quickstart guide</a></b>', 'wp-auto-content' ); ?></p>
    </div>
    <?php
}

// Notice in backend 2
add_action( 'admin_init', 'cbaffmach_check_initial_notice2' );

function cbaffmach_check_initial_notice2() {
    $ndis = get_option( 'wpautoc2-ndis2' );
    if( empty( $ndis ) ) {
        add_action( 'admin_notices', 'wpautoc_initial_admin_notice2', -1 );
    }
}

function wpautoc_initial_admin_notice2() {
    ?>
    <div class="notice updated wpautoc-anotice2 is-dismissible" >
        <p><?php _e( 'Would you like to increase your Clickbank Earnings in 2020? The number #1 Clickbank Affiliate in the world shares his secrets to generate <b>$1000 per day or more</b> in his Free Webinar <a href="https://wpsocialcontact.com/products/commissionhero?tid=wpautocnot" target="_blank"><b>Join here for FREE!</b> (limited seats)</a>', 'wp-auto-content' ); ?></p>
    </div>
    <?php
}

function wpautocblock_open( $if_mon = 0 ) {
    if( !wpautoc_is_pro() ) {
        // if( $if_mon && !wpautoc_is_monetize() )
        if( !$if_mon || ( $if_mon && !wpautoc_is_monetize() ) )
            echo '<div style="display:none">';
    }
}

function wpautocblock_close( $if_mon = 0 ) {
    if( !wpautoc_is_pro() ) {
        if( !$if_mon || ( $if_mon && !wpautoc_is_monetize() ) )
        // if( $if_mon && !wpautoc_is_monetize() )
            echo '</div>';
    }
}

function wpautoc_pop_admin( $text ) {
    return '<a href="#" class="autocpop"><i class="fa fa-question"></i></a>
    <div class="webui-popover-content">
       <p>'.$text.'</p>
    </div>';
}
?>