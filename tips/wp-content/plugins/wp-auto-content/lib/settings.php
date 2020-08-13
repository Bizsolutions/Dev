<?php

    function wpautoc_save_settings() {
        $settings = wpautoc_get_plugin_settings();

        // 2. Content
        $vimeo_app = isset( $_POST['wpautoc_vimeo_appid'] ) ? trim( sanitize_text_field( $_POST['wpautoc_vimeo_appid'] ) ) : '';
        $vimeo_secret = isset( $_POST['wpautoc_vimeo_appsecret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_vimeo_appsecret'] ) ) : '';
        $vimeo_token = isset( $_POST['wpautoc_vimeo_token'] ) ? trim( sanitize_text_field( $_POST['wpautoc_vimeo_token'] ) ) : '';

        $youtube_apikey = isset( $_POST['wpautoc_youtube_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_youtube_apikey'] ) ) : '';
        $eza_apikey = isset( $_POST['wpautoc_eza_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_eza_apikey'] ) ) : '';
        $newsapi_apikey = isset( $_POST['wpautoc_newsapi_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_newsapi_apikey'] ) ) : '';
        $nyt_apikey = isset( $_POST['wpautoc_nyt_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_nyt_apikey'] ) ) : '';
        $whio_apikey = isset( $_POST['wpautoc_whio_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_whio_apikey'] ) ) : '';
        $etsy_apikey = isset( $_POST['wpautoc_etsy_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_etsy_apikey'] ) ) : '';
        $udemy_client_id = isset( $_POST['wpautoc_udemy_client_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_udemy_client_id'] ) ) : '';
        $udemy_client_secret = isset( $_POST['wpautoc_udemy_client_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_udemy_client_secret'] ) ) : '';

        $bigcs_username = isset( $_POST['wpautoc_bigcs_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bigcs_username'] ) ) : '';
        $bigcs_apikey = isset( $_POST['wpautoc_bigcs_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bigcs_apikey'] ) ) : '';

        $bigas_username = isset( $_POST['wpautoc_bigas_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bigas_username'] ) ) : '';
        $bigas_apikey = isset( $_POST['wpautoc_bigas_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bigas_apikey'] ) ) : '';

        $aforge_username = isset( $_POST['wpautoc_aforge_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_aforge_username'] ) ) : '';
        $aforge_apikey = isset( $_POST['wpautoc_aforge_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_aforge_apikey'] ) ) : '';

        $abuilder_username = isset( $_POST['wpautoc_abuilder_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_abuilder_username'] ) ) : '';
        $abuilder_pass = isset( $_POST['wpautoc_abuilder_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_abuilder_pass'] ) ) : '';

        $googlebooks_apikey = isset( $_POST['wpautoc_googlebooks_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_googlebooks_apikey'] ) ) : '';
        $eventbrite_token = isset( $_POST['wpautoc_eventbrite_token'] ) ? trim( sanitize_text_field( $_POST['wpautoc_eventbrite_token'] ) ) : '';
        $yelp_client_id = isset( $_POST['wpautoc_yelp_client_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_yelp_client_id'] ) ) : '';
        $yelp_client_secret = isset( $_POST['wpautoc_yelp_client_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_yelp_client_secret'] ) ) : '';
        $yelp_token = isset( $settings['content']['yelp']['token'] ) ? $settings['content']['yelp']['token'] : false;
        $yelp_expiration = isset( $settings['content']['yelp']['expiration'] ) ? $settings['content']['yelp']['expiration'] : false;

        $settings['content'] = array(
            'vimeo' => array(
                'appid' => $vimeo_app,
                'appsecret' => $vimeo_secret,
                'token' => $vimeo_token
            ),
            'youtube' => array(
                'apikey' => $youtube_apikey
            ),
            'eza' => array(
                'apikey' => $eza_apikey
            ),
            'newsapi' => array(
                'apikey' => $newsapi_apikey
            ),
            'nyt' => array(
                'apikey' => $nyt_apikey
            ),
            'whio' => array(
                'apikey' => $whio_apikey
            ),
            'bigcs' => array(
                'username' => $bigcs_username,
                'apikey' => $bigcs_apikey
            ),
            'bigas' => array(
                'username' => $bigas_username,
                'apikey' => $bigas_apikey
            ),
            'aforge' => array(
                'username' => $aforge_username,
                'apikey' => $aforge_apikey
            ),
            'abuilder' => array(
                'username' => $abuilder_username,
                'pass' => $abuilder_pass
            ),
            'etsy' => array(
                'apikey' => $etsy_apikey
            ),
            'googlebooks' => array(
                'apikey' => $googlebooks_apikey
            ),
            'udemy' => array(
                'client_id' => $udemy_client_id,
                'client_secret' => $udemy_client_secret
            ),
            'eventbrite' => array(
                'token' => $eventbrite_token
            ),
            'yelp' => array(
                'client_id' => $yelp_client_id,
                'client_secret' => $yelp_client_secret,
                'token' => $yelp_token,
                'expiration' => $yelp_expiration
            ),
        );

        // 3. Images
        $flickr_apikey = isset( $_POST['wpautoc_flickr_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_flickr_apikey'] ) ) : '';
        // $pixabay_username = isset( $_POST['wpautoc_pixabay_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pixabay_username'] ) ) : '';
        $pixabay_apikey = isset( $_POST['wpautoc_pixabay_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pixabay_apikey'] ) ) : '';

        $settings['images'] = array(
            'flickr' => array(
                'apikey' => $flickr_apikey,
            ),
            'pixabay' => array(
                // 'username' => $pixabay_username,
                'apikey' => $pixabay_apikey,
            )
        );

        // 4. Traffic
        $bm_email = isset( $_POST['wpautoc_bm_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bm_email'] ) ) : '';
        $bm_apikey = isset( $_POST['wpautoc_bm_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bm_apikey'] ) ) : '';
        $ili_apikey = isset( $_POST['wpautoc_ili_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_ili_apikey'] ) ) : '';
        $bli_apikey = isset( $_POST['wpautoc_bli_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bli_apikey'] ) ) : '';

        $settings['traffic'] = array(
            'bmachine' => array(
                'email' => $bm_email,
                'apikey' => $bm_apikey,
            ),
            'ili' => array(
                'apikey' => $ili_apikey,
            ),
            'bli' => array(
                'apikey' => $bli_apikey,
            )
        );

        // 5. Social
        $twitter_key = isset( $_POST['wpautoc_twitter_key'] ) ? trim( sanitize_text_field( $_POST['wpautoc_twitter_key'] ) ) : '';
        $twitter_secret = isset( $_POST['wpautoc_twitter_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_twitter_secret'] ) ) : '';
        $oauth_token = isset( $_POST['wpautoc_twitter_token'] ) ? trim( sanitize_text_field( $_POST['wpautoc_twitter_token'] ) ) : '';
        $oauth_secret = isset( $_POST['wpautoc_twitter_oauth_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_twitter_oauth_secret'] ) ) : '';

        $facebook_app_id = isset( $_POST['wpautoc_facebook_app_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_facebook_app_id'] ) ) : '';
        $facebook_secret = isset( $_POST['wpautoc_facebook_app_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_facebook_app_secret'] ) ) : '';
        $facebook_token = isset( $settings['social']['facebook']['token'] ) ? $settings['social']['facebook']['token'] : false;
        $pinterest_email = isset( $_POST['wpautoc_pinterest_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pinterest_email'] ) ) : '';
        $pinterest_pass = isset( $_POST['wpautoc_pinterest_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pinterest_pass'] ) ) : '';
        $pinterest_app_id = isset( $_POST['wpautoc_pinterest_app_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pinterest_app_id'] ) ) : '';
        $pinterest_secret = isset( $_POST['wpautoc_pinterest_app_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_pinterest_app_secret'] ) ) : '';

        $stumbleupon_email = isset( $_POST['wpautoc_stumbleupon_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_stumbleupon_email'] ) ) : '';
        $stumbleupon_pass = isset( $_POST['wpautoc_stumbleupon_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_stumbleupon_pass'] ) ) : '';

        $googleplus_email = isset( $_POST['wpautoc_googleplus_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_googleplus_email'] ) ) : '';
        $googleplus_pass = isset( $_POST['wpautoc_googleplus_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_googleplus_pass'] ) ) : '';
        $googleplus_apikey = isset( $_POST['wpautoc_googleplus_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_googleplus_apikey'] ) ) : '';

        $instagram_email = isset( $_POST['wpautoc_instagram_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_instagram_email'] ) ) : '';
        $instagram_pass = isset( $_POST['wpautoc_instagram_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_instagram_pass'] ) ) : '';

        $reddit_email = isset( $_POST['wpautoc_reddit_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_reddit_email'] ) ) : '';
        $reddit_pass = isset( $_POST['wpautoc_reddit_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_reddit_pass'] ) ) : '';

        $medium_token = isset( $_POST['wpautoc_medium_token'] ) ? trim( sanitize_text_field( $_POST['wpautoc_medium_token'] ) ) : '';

        $tumblr_key = isset( $_POST['wpautoc_tumblr_key'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tumblr_key'] ) ) : '';
        $tumblr_secret = isset( $_POST['wpautoc_tumblr_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tumblr_secret'] ) ) : '';
        $tumblr_oauth_token = isset( $_POST['wpautoc_tumblr_token'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tumblr_token'] ) ) : '';
        $tumblr_oauth_secret = isset( $_POST['wpautoc_tumblr_oauth_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tumblr_oauth_secret'] ) ) : '';

        $linkedin_app_id = isset( $_POST['wpautoc_linkedin_app_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_linkedin_app_id'] ) ) : '';
        $linkedin_secret = isset( $_POST['wpautoc_linkedin_app_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_linkedin_app_secret'] ) ) : '';

        $buffer_client_id = isset( $_POST['wpautoc_buffer_client_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_buffer_client_id'] ) ) : '';
        $buffer_client_secret = isset( $_POST['wpautoc_buffer_client_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_buffer_client_secret'] ) ) : '';

        $settings['social'] = array(
            'twitter' => array(
                'key' => $twitter_key,
                'secret' => $twitter_secret,
                'oauth_token' => $oauth_token,
                'oauth_secret' => $oauth_secret
            ),
            'facebook' => array(
                'app_id' => $facebook_app_id,
                'app_secret' => $facebook_secret,
                'token' => $facebook_token
            ),
            'pinterest' => array(
                'email' => $pinterest_email,
                'pass' => $pinterest_pass,
                'app_id' => $pinterest_app_id,
                'app_secret' => $pinterest_secret
            ),
            'stumbleupon' => array(
                'email' => $stumbleupon_email,
                'pass' => $stumbleupon_pass
            ),
            'reddit' => array(
                'email' => $reddit_email,
                'pass' => $reddit_pass
            ),
            'instagram' => array(
                'email' => $instagram_email,
                'pass' => $instagram_pass
            ),
            'googleplus' => array(
                'email' => $googleplus_email,
                'pass' => $googleplus_pass,
                'key' => $googleplus_apikey
            ),
            'medium' => array(
                'token' => $medium_token
            ),
            'tumblr' => array(
                'key' => $tumblr_key,
                'secret' => $tumblr_secret,
                'oauth_token' => $tumblr_oauth_token,
                'oauth_secret' => $tumblr_oauth_secret
            ),
            'linkedin' => array(
                'app_id' => $linkedin_app_id,
                'app_secret' => $linkedin_secret
            ),
            'buffer' => array(
                'client_id' => $buffer_client_id,
                'client_secret' => $buffer_client_secret
            ),
        );

        // 6. Affiliate
        $amazon_key = isset( $_POST['wpautoc_amazon_key'] ) ? trim( sanitize_text_field( $_POST['wpautoc_amazon_key'] ) ) : '';
        $amazon_secret = isset( $_POST['wpautoc_amazon_secret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_amazon_secret'] ) ) : '';
        $amazon_tag = isset( $_POST['wpautoc_amazon_tag'] ) ? trim( sanitize_text_field( $_POST['wpautoc_amazon_tag'] ) ) : '';
        $amazon_country = isset( $_POST['wpautoc_amazon_country'] ) ? trim( sanitize_text_field( $_POST['wpautoc_amazon_country'] ) ) : '';

        $ebay_appid = isset( $_POST['wpautoc_ebay_appid'] ) ? trim( sanitize_text_field( $_POST['wpautoc_ebay_appid'] ) ) : '';
        $ebay_cid = isset( $_POST['wpautoc_ebay_cid'] ) ? trim( sanitize_text_field( $_POST['wpautoc_ebay_cid'] ) ) : '';
        $ebay_country = isset( $_POST['wpautoc_ebay_country'] ) ? trim( sanitize_text_field( $_POST['wpautoc_ebay_country'] ) ) : '';

        $clickbank_id = isset( $_POST['wpautoc_clickbank_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_clickbank_id'] ) ) : '';
        $bestbuy_api = isset( $_POST['wpautoc_bestbuy_api'] ) ? trim( sanitize_text_field( $_POST['wpautoc_bestbuy_api'] ) ) : '';
        $walmart_api = isset( $_POST['wpautoc_walmart_api'] ) ? trim( sanitize_text_field( $_POST['wpautoc_walmart_api'] ) ) : '';
        $walmart_aff_id = isset( $_POST['wpautoc_walmart_aff_id'] ) ? trim( sanitize_text_field( $_POST['wpautoc_walmart_aff_id'] ) ) : '';

        $aliexpress_api = isset( $_POST['wpautoc_aliexpress_api'] ) ? trim( sanitize_text_field( $_POST['wpautoc_aliexpress_api'] ) ) : '';
        $aliexpress_hash = isset( $_POST['wpautoc_aliexpress_hash'] ) ? trim( sanitize_text_field( $_POST['wpautoc_aliexpress_hash'] ) ) : '';
        $envato_api = isset( $_POST['wpautoc_envato_api'] ) ? trim( sanitize_text_field( $_POST['wpautoc_envato_api'] ) ) : '';
        $envato_username = isset( $_POST['wpautoc_envato_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_envato_username'] ) ) : '';

        $careerjet_affid = isset( $_POST['wpautoc_careerjet_affid'] ) ? trim( sanitize_text_field( $_POST['wpautoc_careerjet_affid'] ) ) : '';

        $gearbest_appid = isset( $_POST['wpautoc_gearbest_appid'] ) ? trim( sanitize_text_field( $_POST['wpautoc_gearbest_appid'] ) ) : '';
        $gearbest_appsecret = isset( $_POST['wpautoc_gearbest_appsecret'] ) ? trim( sanitize_text_field( $_POST['wpautoc_gearbest_appsecret'] ) ) : '';
        $gearbest_deeplink = isset( $_POST['wpautoc_gearbest_deeplink'] ) ? trim( sanitize_text_field( $_POST['wpautoc_gearbest_deeplink'] ) ) : '';


        $settings['affiliate'] = array(
            'amazon' => array(
                'key' => $amazon_key,
                'secret' => $amazon_secret,
                'tag' => $amazon_tag,
                'country' => $amazon_country
            ),
            'ebay' => array(
                'appid' => $ebay_appid,
                'campaignid' => $ebay_cid,
                'country' => $ebay_country
            ),
            'clickbank'  => array(
                'id' => $clickbank_id,
            ),
            'bestbuy'  => array(
                'apikey' => $bestbuy_api,
            ),
            'aliexpress'  => array(
                'apikey' => $aliexpress_api,
                'hash' => $aliexpress_hash,
            ),
            'walmart'  => array(
                'apikey' => $walmart_api,
                'aff_id' => $walmart_aff_id,
            ),
            'envato'  => array(
                'apikey' => $envato_api,
                'username' => $envato_username
            ),
            'careerjet'  => array(
                'affid' => $careerjet_affid
            ),
            'gearbest' => array(
                'deeplink' => $gearbest_deeplink,
                'appid' => $gearbest_appid,
                'appsecret' => $gearbest_appsecret,
            )
        );

        // 7. Text Spinners
        $srewriter_email = isset( $_POST['wpautoc_srewriter_email'] ) ? trim( sanitize_text_field( $_POST['wpautoc_srewriter_email'] ) ) : '';
        $srewriter_apikey = isset( $_POST['wpautoc_srewriter_apikey'] ) ? trim( sanitize_text_field( $_POST['wpautoc_srewriter_apikey'] ) ) : '';

        $schief_username = isset( $_POST['wpautoc_schief_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_schief_username'] ) ) : '';
        $schief_password = isset( $_POST['wpautoc_schief_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_schief_pass'] ) ) : '';

        $tbs_username = isset( $_POST['wpautoc_tbspinner_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tbspinner_username'] ) ) : '';
        $tbs_password = isset( $_POST['wpautoc_tbspinner_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_tbspinner_pass'] ) ) : '';

        $wordai_username = isset( $_POST['wpautoc_wordai_username'] ) ? trim( sanitize_text_field( $_POST['wpautoc_wordai_username'] ) ) : '';
        $wordai_password = isset( $_POST['wpautoc_wordai_pass'] ) ? trim( sanitize_text_field( $_POST['wpautoc_wordai_pass'] ) ) : '';

        $spinner = isset( $_POST['wpautoc_spinner'] ) ? trim( sanitize_text_field( $_POST['wpautoc_spinner'] ) ) : '';

        $settings['spinners'] = array(
            'spinrewriter' => array(
                'email' => $srewriter_email,
                'apikey' => $srewriter_apikey,
            ),
            'schief' => array(
                'username' => $schief_username,
                'password' => $schief_password,
            ),
            'tbs' => array(
                'username' => $tbs_username,
                'password' => $tbs_password,
            ),
            'wordai' => array(
                'username' => $wordai_username,
                'password' => $wordai_password,
            ),
            'spinner' => $spinner
        );

        wpautoc_save_plugin_settings( $settings );
    }

function wpautoc_admin_settings() {
    if( isset( $_POST['wpautoc_save_settings'] ) ) {
        wpautoc_save_settings();
    }

    if( isset( $_GET['fbsaved'] ) ) {
        echo '<div class="notice notice-success"><p>Facebook Account Linked Correctly. Now you can auto-share to Facebook your new content from your campaigns.</p></div>';
    }

    if( isset( $_GET['lin_auth'] ) ) {
        echo '<div class="notice notice-success"><p>Linkedin Account Linked Correctly. Now you can auto-share to Linkedin your new content from your campaigns.</p></div>';
    }
    $settings = wpautoc_get_plugin_settings();
    ?>

        <form action="" method="post" class="form-horizontal" id="wpautoc-settings-form">
        <div id="autoc-tabs" >
            <h2 class="nav-tab-wrapper">
                <a href="#autocs-content-tab" class="nav-tab  nav-tab-active" ><i class="fa fa-list"></i> Content</a>
                <a href="#autocs-monetize-tab" class="nav-tab"><i class="fa fa-image"></i> Images</a>
                <?php if( wpautoc_is_traffic() ) { ?>
                    <a href="#autocs-traffic-tab" class="nav-tab"><i class="fa fa-link"></i> Traffic</a>
                <?php } ?>
                <a href="#autocs-social-tab" class="nav-tab"><i class="fa fa-share-alt"></i> Social</a>
                <a href="#autocs-ecommerce-tab" class="nav-tab"><i class="fa fa-shopping-cart"></i> Affiliate</a>
                <a href="#autocs-spinners-tab" class="nav-tab"><i class="fa fa-random"></i> Spinners</a>
                <a href="#autocs-general-tab" class="nav-tab"><i class="fa fa-cog"></i> Other Settings</a>
            </h2>
            <div style="clear:both"></div>
            <div id="autocs-content-tab" class="tab-inner" style="display:block"><?php wpautoc_settings_tab_content( $settings );?></div>
            <div id="autocs-monetize-tab" class="tab-inner"><?php wpautoc_settings_tab_images( $settings );?></div>
            <div id="autocs-traffic-tab" class="tab-inner"><?php wpautoc_settings_tab_traffic( $settings );?></div>
            <div id="autocs-social-tab" class="tab-inner"><?php wpautoc_settings_tab_social( $settings );?></div>
            <div id="autocs-ecommerce-tab" class="tab-inner"><?php wpautoc_settings_tab_ecommerce( $settings );?></div>
            <div id="autocs-spinners-tab" class="tab-inner"><?php wpautoc_settings_tab_spinners( $settings );?></div>
            <div id="autocs-general-tab" class="tab-inner" ><?php wpautoc_settings_tab_general();?></div>
        </div>
        <br/>
        <hr/>
        <?php wpautoc_field_hidden( 'wpautoc_save_settings', 1 ); ?>
        <!-- <input type="submit" class="button button-primary" value="Save All Changes" /> -->
        <button type="submit" class="button button-primary"><i class="fa fa-save"></i> Save All Changes</button>
        <a class="button button-secondary" href="<?php echo admin_url( '/admin.php?page=wp-auto-content' );?>" >Cancel</a>
    </form>
    <?php
}

function wpautoc_settings_tab_general( $settings = false ) {
    $general_settings = isset( $settings['general'] ) ? $settings['general'] : false;
    // $img = wpautoc_flickr_get_image( 'horse' );
    // echo $img;
?>
<table class="form-table">
    <tr valign="top">
            <th scope="row">Cron URL</th>
            <td>
                <input class="large-text" value="<?php echo site_url( 'wp-content/plugins/wp-auto-content/cron.php');?>" type="text" readonly="readonly">
                <p>(Optional) If you want to make the importing more reliable, please set up a cron job using cPanel, every 1 hour, using the URL above</p>
                <p><a href="https://wpautocontent.com/support/knowledgebase/configuring-a-cron-job-in-cpanel/" target="_blank">Click here for more info</a></p>
            </td>
        </tr>
</table>
<?php
}

function wpautoc_settings_tab_content( $settings = false ) {
    $content_settings = isset( $settings['content'] ) ? $settings['content'] : false;
    $vimeo_app = isset( $content_settings['vimeo']['appid'] ) ? trim( $content_settings['vimeo']['appid'] ) : '';
    $vimeo_secret = isset( $content_settings['vimeo']['appsecret'] ) ? trim( $content_settings['vimeo']['appsecret'] ) : '';
    $vimeo_token = isset( $content_settings['vimeo']['token'] ) ? trim( $content_settings['vimeo']['token'] ) : '';

    $youtube_apikey = isset( $content_settings['youtube']['apikey'] ) ? trim( $content_settings['youtube']['apikey'] ) : '';
    $eza_apikey = isset( $content_settings['eza']['apikey'] ) ? trim( $content_settings['eza']['apikey'] ) : '';
    $newsapi_apikey = isset( $content_settings['newsapi']['apikey'] ) ? trim( $content_settings['newsapi']['apikey'] ) : '';
    $nyt_apikey = isset( $content_settings['nyt']['apikey'] ) ? trim( $content_settings['nyt']['apikey'] ) : '';
    $whio_apikey = isset( $content_settings['whio']['apikey'] ) ? trim( $content_settings['whio']['apikey'] ) : '';
    $etsy_apikey = isset( $content_settings['etsy']['apikey'] ) ? trim( $content_settings['etsy']['apikey'] ) : '';

    $bigcs_username = isset( $content_settings['bigcs']['username'] ) ? trim( $content_settings['bigcs']['username'] ) : '';
    $bigcs_apikey = isset( $content_settings['bigcs']['apikey'] ) ? trim( $content_settings['bigcs']['apikey'] ) : '';

    $bigas_username = isset( $content_settings['bigas']['username'] ) ? trim( $content_settings['bigas']['username'] ) : '';
    $bigas_apikey = isset( $content_settings['bigas']['apikey'] ) ? trim( $content_settings['bigas']['apikey'] ) : '';

    $aforge_username = isset( $content_settings['aforge']['username'] ) ? trim( $content_settings['aforge']['username'] ) : '';
    $aforge_apikey = isset( $content_settings['aforge']['apikey'] ) ? trim( $content_settings['aforge']['apikey'] ) : '';

    $abuilder_username = isset( $content_settings['abuilder']['username'] ) ? trim( $content_settings['abuilder']['username'] ) : '';
    $abuilder_pass = isset( $content_settings['abuilder']['pass'] ) ? trim( $content_settings['abuilder']['pass'] ) : '';

    $udemy_client_id = isset( $content_settings['udemy']['client_id'] ) ? trim( $content_settings['udemy']['client_id'] ) : '';
    $udemy_client_secret = isset( $content_settings['udemy']['client_secret'] ) ? trim( $content_settings['udemy']['client_secret'] ) : '';

    $googlebooks_apikey = isset( $content_settings['googlebooks']['apikey'] ) ? trim( $content_settings['googlebooks']['apikey'] ) : '';
    $eventbrite_token = isset( $content_settings['eventbrite']['token'] ) ? trim( $content_settings['eventbrite']['token'] ) : '';

    $yelp_client_id = isset( $content_settings['yelp']['client_id'] ) ? trim( $content_settings['yelp']['client_id'] ) : '';
    $yelp_client_secret = isset( $content_settings['yelp']['client_secret'] ) ? trim( $content_settings['yelp']['client_secret'] ) : '';
?>
    <h3><i class="fa fa-youtube"></i> Youtube</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Youtube API Key', 'wpautoc_youtube_apikey', $youtube_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-youtube-api-key/" target="_blank">How to get your API Key from Youtube</a>', 'Your Youtube API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-vimeo"></i> Vimeo</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Vimeo Application ID', 'wpautoc_vimeo_appid', $vimeo_app, false, '', '', 'Your Vimeo App ID' ); ?>
        <?php wpautoc_field_text( 'Vimeo Application Secret', 'wpautoc_vimeo_appsecret', $vimeo_secret, false, '', '', 'Your Vimeo App Secret' ); ?>
        <?php wpautoc_field_text( 'Vimeo Access token', 'wpautoc_vimeo_token', $vimeo_token, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-vimeo-application/" target="_blank">How to create a Vimeo Application</a>', 'Your Vimeo Access Token' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-file-text-o"></i> EzineArticles</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'EzineArticles API Key', 'wpautoc_eza_apikey', $eza_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-ezinearticles-api-key/" target="_blank">How to get your free API Key from EZA</a>', 'Your EzineArticles API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-newspaper-o"></i> Newsapi</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Newsapi.org API Key', 'wpautoc_newsapi_apikey', $newsapi_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-newsapi-org-api-key/" target="_blank">How to get your free API Key from Newsapi.org</a>', 'Your Newsapi.org API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-newspaper-o"></i> New York Times</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'NYT API Key', 'wpautoc_nyt_apikey', $nyt_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-new-york-times-api-key/" target="_blank">How to get your free API Key from NYT</a>', 'Your New York Times API Key' ); ?>
    </table>
    <br/>
    <?php wpautocblock_open(); ?>
        <h3><i class="fa fa-newspaper-o"></i> Webhose.io</h3>
        <table class="form-table">
            <?php wpautoc_field_text( 'Webhose.io API Key', 'wpautoc_whio_apikey', $whio_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-webhose-io-api-key/" target="_blank">How to get your free API Key from Webhose.io</a>', 'Your Webhose.io API Key' ); ?>
        </table>
        <br/>
    <?php wpautocblock_close(); ?>
    <h3><i class="fa fa-qrcode"></i> BigContentSearch.com - <a href="https://wpautocontent.com/support/bigcontentsearch" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'E-mail', 'wpautoc_bigcs_username', $bigcs_username, false, '', '', 'Your Big Content Search email address' ); ?>
        <?php wpautoc_field_text( 'API Key', 'wpautoc_bigcs_apikey', $bigcs_apikey, false, '', '<a href="https://wpautocontent.com/support/bigcontentsearch" target="_blank">Get it here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-bigcontentsearch-com-api-key/" target="_blank">Tutorial</a>', 'Your Big Content Search API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-qrcode"></i> BigArticleScraper.com - <a href="https://wpautocontent.com/support/bigarticlescraper" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'E-mail', 'wpautoc_bigas_username', $bigas_username, false, '', '', 'Your Big Article Scraper email address' ); ?>
        <?php wpautoc_field_text( 'API Key', 'wpautoc_bigas_apikey', $bigas_apikey, false, '', '<a href="https://wpautocontent.com/support/bigarticlescraper" target="_blank">Get it here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-bigarticlescraper-com-api-key/" target="_blank">Tutorial</a>', 'Your Big Article Scraper API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-qrcode"></i> ArticleForge.com - <a href="https://wpautocontent.com/support/articleforge" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'E-mail', 'wpautoc_aforge_username', $aforge_username, false, '', '', 'Your Article Forge email address' ); ?>
        <?php wpautoc_field_text( 'API Key', 'wpautoc_aforge_apikey', $aforge_apikey, false, '', '<a href="https://wpautocontent.com/support/articleforge" target="_blank">Get it here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-articleforge-api-key/" target="_blank">Tutorial</a>', 'Your ArticleForge API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-qrcode"></i> ArticleBuilder - <a href="https://wpautocontent.com/support/articlebuilder" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Username', 'wpautoc_abuilder_username', $abuilder_username, false, '', '', 'Your ArticleBuilder username' ); ?>
        <?php wpautoc_field_password( 'Password', 'wpautoc_abuilder_pass', $abuilder_pass, false, '', '<a href="https://wpautocontent.com/support/articlebuilder" target="_blank">Get it here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-articlebuilder-details/" target="_blank">Tutorial</a>', 'Your ArticleBuilder Password' ); ?>
    </table>
    <br/>
    <?php wpautocblock_open(); ?>
    <h3><i class="fa fa-etsy"></i> Etsy</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Etsy API Key', 'wpautoc_etsy_apikey', $etsy_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-etsy-api-key/" target="_blank">How to get your API Key from Etsy</a>', 'Your Etsy API Key' ); ?>
    </table>
    <br/>


    <h3><i class="fa fa-book"></i> Udemy</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Client ID', 'wpautoc_udemy_client_id', $udemy_client_id, false, '', '', 'Your Udemy Client ID' ); ?>
        <?php wpautoc_field_text( 'Client Secret', 'wpautoc_udemy_client_secret', $udemy_client_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-udemy-client-id-and-secret/" target="_blank">How to get your Client ID and Secret from Udemy</a>', 'Your Udemy Client Secret' ); ?>
    </table>
    <br/>


    <h3><i class="fa fa-calendar-check-o"></i> Eventbrite</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Eventbrite Token', 'wpautoc_eventbrite_token', $eventbrite_token, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-eventbrite-token/" target="_blank">How to get your free Eventbrite token</a>', 'Your Eventbrite Token' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-yelp"></i> Yelp</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Client ID', 'wpautoc_yelp_client_id', $yelp_client_id, false, '', '', 'Your Yelp Client ID' ); ?>
        <?php wpautoc_field_text( 'Client Secret', 'wpautoc_yelp_client_secret', $yelp_client_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-yelp-client-id-and-secret/" target="_blank">How to get your Yelp Client ID and Secret</a>', 'Your Yelp Client Secret' ); ?>
    </table>
    <br/>
    <?php wpautocblock_close(); ?>

    <h3><i class="fa fa-google"></i> Google Books</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Google Books API Key', 'wpautoc_googlebooks_apikey', $googlebooks_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-google-books-api-key/" target="_blank">How to get your free Google Books API Key</a>', 'Your Google Books API Key' ); ?>
    </table>
    <br/>
<?php
}

function wpautoc_settings_tab_images( $settings = false ) {
    $image_settings = isset( $settings['images'] ) ? $settings['images'] : false;
    $flickr_api_key = isset( $image_settings['flickr']['apikey'] ) ? trim( $image_settings['flickr']['apikey'] ) : '';
    // $pixabay_username = isset( $image_settings['pixabay']['username'] ) ? trim( $image_settings['pixabay']['username'] ) : '';
    $pixabay_apikey = isset( $image_settings['pixabay']['apikey'] ) ? trim( $image_settings['pixabay']['apikey'] ) : '';
?>
<table class="form-table">
    <h3><i class="fa fa-flickr"></i> Flickr</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Flickr API Key', 'wpautoc_flickr_apikey', $flickr_api_key, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-flickr-api-key/" target="_blank">How to get your Flickr API Key</a>', 'Your Flickr API Key' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-image"></i> Pixabay</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Pixabay API Key', 'wpautoc_pixabay_apikey', $pixabay_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-pixabay-api-key/" target="_blank">How to get your Pixabay API Key</a>', 'Your Pixabay API Key' ); ?>
    </table>
    <br/>
</table>
<?php
}

function wpautoc_settings_tab_social( $settings = false ) {
    $social_settings = isset( $settings['social'] ) ? $settings['social'] : false;
    // var_dump($social_settings);
    $twitter_key = isset( $social_settings['twitter']['key'] ) ? trim( $social_settings['twitter']['key'] ) : '';
    $twitter_secret = isset( $social_settings['twitter']['secret'] ) ? trim( $social_settings['twitter']['secret'] ) : '';
    $twitter_oauth_token = isset( $social_settings['twitter']['oauth_token'] ) ? trim( $social_settings['twitter']['oauth_token'] ) : '';
    $twitter_oauth_secret = isset( $social_settings['twitter']['oauth_secret'] ) ? trim( $social_settings['twitter']['oauth_secret'] ) : '';

    $facebook_app_id = isset( $social_settings['facebook']['app_id'] ) ? trim( $social_settings['facebook']['app_id'] ) : '';
    $facebook_app_secret = isset( $social_settings['facebook']['app_secret'] ) ? trim( $social_settings['facebook']['app_secret'] ) : '';

    $pinterest_email = isset( $social_settings['pinterest']['email'] ) ? trim( $social_settings['pinterest']['email'] ) : '';
    $pinterest_pass = isset( $social_settings['pinterest']['pass'] ) ? trim( $social_settings['pinterest']['pass'] ) : '';
    $pinterest_app_id = isset( $social_settings['pinterest']['app_id'] ) ? trim( $social_settings['pinterest']['app_id'] ) : '';
    $pinterest_app_secret = isset( $social_settings['pinterest']['app_secret'] ) ? trim( $social_settings['pinterest']['app_secret'] ) : '';

    $stumbleupon_email = isset( $social_settings['stumbleupon']['email'] ) ? trim( $social_settings['stumbleupon']['email'] ) : '';
    $stumbleupon_pass = isset( $social_settings['stumbleupon']['pass'] ) ? trim( $social_settings['stumbleupon']['pass'] ) : '';

    $reddit_email = isset( $social_settings['reddit']['email'] ) ? trim( $social_settings['reddit']['email'] ) : '';
    $reddit_pass = isset( $social_settings['reddit']['pass'] ) ? trim( $social_settings['reddit']['pass'] ) : '';

    $instagram_email = isset( $social_settings['instagram']['email'] ) ? trim( $social_settings['instagram']['email'] ) : '';
    $instagram_pass = isset( $social_settings['instagram']['pass'] ) ? trim( $social_settings['instagram']['pass'] ) : '';

    $googleplus_email = isset( $social_settings['googleplus']['email'] ) ? trim( $social_settings['googleplus']['email'] ) : '';
    $googleplus_pass = isset( $social_settings['googleplus']['pass'] ) ? trim( $social_settings['googleplus']['pass'] ) : '';
    $googleplus_apikey = isset( $social_settings['googleplus']['key'] ) ? trim( $social_settings['googleplus']['key'] ) : '';

    $medium_token = isset( $social_settings['medium']['token'] ) ? trim( $social_settings['medium']['token'] ) : '';

    $tumblr_key = isset( $social_settings['tumblr']['key'] ) ? trim( $social_settings['tumblr']['key'] ) : '';
    $tumblr_secret = isset( $social_settings['tumblr']['secret'] ) ? trim( $social_settings['tumblr']['secret'] ) : '';
    $tumblr_oauth_token = isset( $social_settings['tumblr']['oauth_token'] ) ? trim( $social_settings['tumblr']['oauth_token'] ) : '';
    $tumblr_oauth_secret = isset( $social_settings['tumblr']['oauth_secret'] ) ? trim( $social_settings['tumblr']['oauth_secret'] ) : '';

    $linkedin_app_id = isset( $social_settings['linkedin']['app_id'] ) ? trim( $social_settings['linkedin']['app_id'] ) : '';
    $linkedin_app_secret = isset( $social_settings['linkedin']['app_secret'] ) ? trim( $social_settings['linkedin']['app_secret'] ) : '';

    $buffer_client_id = isset( $social_settings['buffer']['client_id'] ) ? trim( $social_settings['buffer']['client_id'] ) : '';
    $buffer_client_secret = isset( $social_settings['buffer']['client_secret'] ) ? trim( $social_settings['buffer']['client_secret'] ) : '';

    wpautoc_try_linkedin_auth( $linkedin_app_id, $linkedin_app_secret );
    // var_dump($social_settings['buffer']);
?>

    <h3><i class="fa fa-facebook"></i> Facebook</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'App ID', 'wpautoc_facebook_app_id', $facebook_app_id, false, '', '', 'Your Facebook App ID' ); ?>
        <?php wpautoc_field_text( 'App Secret', 'wpautoc_facebook_app_secret', $facebook_app_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-a-facebook-app-id-and-secret/" target="_blank">How to get your free Facebook App ID and Secret</a>', 'Your Facebook App Secret' ); ?>
    </table>
    <?php
        $login_url = false;
        if ( !empty($facebook_app_id ) && !empty( $facebook_app_secret ) ) {
            try {
                require_once WPAUTOC_DIR . 'lib/libs/facebook/Facebook/autoload.php';
                $fb = new Facebook\Facebook([
                    'app_id' => $facebook_app_id,
                    'app_secret' =>$facebook_app_secret,
                ]);

                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['publish_actions,manage_pages,publish_pages']; // optional
                $login_url = $helper->getLoginUrl( WPAUTOC_URL . '/lib/libs/facebook/facebookcallback.php?fbid=' . $facebook_app_id . '&secret=' . $facebook_app_secret . '' , $permissions);
            } catch (Exception $e) { }
        }
        if( $login_url ) {
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Facebook</a>';
        }
    ?>
    <br/>
    <br/>

    <h3><i class="fa fa-twitter"></i> Twitter</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Consumer Key', 'wpautoc_twitter_key', $twitter_key, false, '', '', 'Your Twitter Consumer Key' ); ?>
        <?php wpautoc_field_text( 'Consumer Secret', 'wpautoc_twitter_secret', $twitter_secret, false, '', '', 'Your Twitter Consumer Secret' ); ?>
        <?php wpautoc_field_text( 'Oauth Access Token', 'wpautoc_twitter_token', $twitter_oauth_token, false, '', '', 'Your Twitter Oauth Token' ); ?>
        <?php wpautoc_field_text( 'Oauth Token Secret', 'wpautoc_twitter_oauth_secret', $twitter_oauth_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-twitter-details/" target="_blank">How to get your free Twitter API Details</a>', 'Your Twitter Token Secret' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-pinterest"></i> Pinterest</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Pinterest email', 'wpautoc_pinterest_email', $pinterest_email, false, '', '', 'Your Pinterest email address' ); ?>
        <?php wpautoc_field_password( 'Pinterest password', 'wpautoc_pinterest_pass', $pinterest_pass, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-pinterest-details/" target="_blank">Tutorial</a>', 'Your Pinterest Password' ); ?>
        <!--<?php /*wpautoc_field_text( 'App ID', 'wpautoc_pinterest_app_id', $pinterest_app_id, false, '' );*/ ?>
        <?php /*wpautoc_field_text( 'App Secret', 'wpautoc_pinterest_app_secret', $pinterest_app_secret, false, '' );*/ ?>-->
    </table>
    <br/>
    <h3><i class="fa fa-google-plus"></i> Google Plus</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Google Plus email', 'wpautoc_googleplus_email', $googleplus_email, false, '', '', 'Your Google+ Email', '', wpautoc_is_traffic() ); ?>
        <?php wpautoc_field_password( 'Google Plus password', 'wpautoc_googleplus_pass', $googleplus_pass, false, '', '', 'Your Google+ Password', '', wpautoc_is_traffic() ); ?>
        <?php wpautoc_field_text( 'Google Plus API key', 'wpautoc_googleplus_apikey', $googleplus_apikey, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-google-api-key/" target="_blank">How to get your free Google+ API Key</a>', 'Your Google+ API Key' ); ?>
    </table>

    <br/>
    <?php if( !wpautoc_is_traffic() ) { echo '<div style="display:none">'; } ?>
    <h3><i class="fa fa-reddit"></i> Reddit</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Reddit username', 'wpautoc_reddit_email', $reddit_email, false, '', '', 'Your Reddit Username' ); ?>
        <?php wpautoc_field_password( 'Reddit password', 'wpautoc_reddit_pass', $reddit_pass, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-reddit-details/" target="_blank">Tutorial</a>', 'Your Reddit Password'  ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-instagram"></i> Instagram</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Instagram username', 'wpautoc_instagram_email', $instagram_email, false, '', '', 'Your Instagram username' ); ?>
        <?php wpautoc_field_password( 'Instagram password', 'wpautoc_instagram_pass', $instagram_pass, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-instagram-details/" target="_blank">Tutorial</a>', 'Your Instagram Password' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-stumbleupon"></i> StumbleUpon</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'StumbelUpon email', 'wpautoc_stumbleupon_email', $stumbleupon_email, false, '', '', 'Your Stumbleupon Email' ); ?>
        <?php wpautoc_field_password( 'StumbelUpon password', 'wpautoc_stumbleupon_pass', $stumbleupon_pass, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-stumbleupon-details/" target="_blank">Tutorial</a>', 'Your Stumbleupon Password' ); ?>
    </table>
    <?php if( !wpautoc_is_traffic() ) { echo '</div>'; } ?>
    <br/>

    <h3><i class="fa fa-medium"></i> Medium</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Token', 'wpautoc_medium_token', $medium_token, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-medium-token/" target="_blank">How to get your free Medium Token</a>', 'Your Medium Token' ); ?>
    </table>
    <br/>

    <h3><i class="fa fa-tumblr"></i> Tumblr</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Consumer Key', 'wpautoc_tumblr_key', $tumblr_key, false, '', '', 'Your Tumblr Consumer Key' ); ?>
        <?php wpautoc_field_text( 'Consumer Secret', 'wpautoc_tumblr_secret', $tumblr_secret, false, '', '', 'Your Tumblr Consumer Secret' ); ?>
        <?php wpautoc_field_text( 'Oauth Access Token', 'wpautoc_tumblr_token', $tumblr_oauth_token, false, '', '', 'Your Tumblr Oauth Token' ); ?>
        <?php wpautoc_field_text( 'Oauth Token Secret', 'wpautoc_tumblr_oauth_secret', $tumblr_oauth_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-tumblr-api-details/" target="_blank">How to get your free Tumblr API Details</a>', 'Your Tumblr Token Secret'  ); ?>
    </table>
    <?php if( !wpautoc_is_traffic() ) { echo '<div style="display:none">'; } ?>
    <br/>
    <h3><i class="fa fa-linkedin"></i> Linkedin</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'App ID/Api Key', 'wpautoc_linkedin_app_id', $linkedin_app_id, false, '', '', 'Your Linkedin App ID' ); ?>
        <?php wpautoc_field_text( 'App Secret', 'wpautoc_linkedin_app_secret', $linkedin_app_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-linkedin-app-details/" target="_blank">How to get your Linkedin API Details</a>', 'Your Linkedin App Secret' ); ?>
        <?php wpautoc_field_static( 'Callback URL', admin_url('/admin.php?page=wp-auto-content-settings&lin_auth=true'), false, '', 'Enter this URL to your linkedin app in OAuth 2.0 Redirect URLs field.'  ); ?>
    </table>

    <?php
        $login_url = false;
        if ( !empty($linkedin_app_id ) && !empty( $linkedin_app_secret ) ) {
            require_once WPAUTOC_DIR . 'lib/libs/linkedin/LinkedIn.php';
            $li = new LinkedIn(
              array(
                'api_key' => $linkedin_app_id,
                'api_secret' => $linkedin_app_secret,
                'callback_url' => admin_url('/admin.php?page=wp-auto-content-settings&lin_auth=true')
              )
            );

            $login_url = $li->getLoginUrl(
              array(
                LinkedIn::SCOPE_BASIC_PROFILE,
                LinkedIn::SCOPE_WRITE_SHARE
              )
            );
        }
        if( $login_url ) {
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Linkedin</a>';
        }
    ?>
    <br/>
    <br/>

    <h3><i class="fa fa-play-circle-o"></i> BufferApp</h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Client ID', 'wpautoc_buffer_client_id', $buffer_client_id, false, '', '', 'Your Buffer Client ID' ); ?>
        <?php wpautoc_field_text( 'Client Secret', 'wpautoc_buffer_client_secret', $buffer_client_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-buffer-client-id-and-secret/" target="_blank">How to get your Buffer API Details</a>', 'Your Buffer Client Secret' ); ?>
        <?php wpautoc_field_static( 'Callback URL', admin_url('/admin.php?page=wp-auto-content-settings&buffer_auth=true'), false, '', 'Enter this URL when creating your Buffer APP.'  ); ?>
    </table>

    <?php
        $login_url = false;
        if ( !empty($buffer_client_id ) && !empty( $buffer_client_secret ) ) {
            require_once WPAUTOC_DIR . 'lib/libs/buffer/buffer.php';
            $buffer = new BufferApp( $buffer_client_id, $buffer_client_secret, admin_url('/admin.php?page=wp-auto-content-settings&buffer_auth=true') );
            $login_url = $buffer->get_login_url();
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Buffer</a>';
        }
    ?>
    <?php if( !wpautoc_is_traffic() ) { echo '</div>'; } ?>
    <br/>
    <br/>
<?php
}



function wpautoc_settings_tab_traffic( $settings = false ) {
    if( !wpautoc_is_traffic() ) {
        echo '<p>To be able to get traffic on autopilot, you need to install and activate the <a href="https://wpautocontent.com/support/trafficmodule" target="_blank"><b>WP Auto Content Traffic Module</b></a> first</p>';
        return;
    }
    $traffic_settings = isset( $settings['traffic'] ) ? $settings['traffic'] : false;
    $bm_email = isset( $traffic_settings['bmachine']['email'] ) ? trim( $traffic_settings['bmachine']['email'] ) : '';
    $bm_apikey = isset( $traffic_settings['bmachine']['apikey'] ) ? trim( $traffic_settings['bmachine']['apikey'] ) : '';
    $ili_apikey = isset( $traffic_settings['ili']['apikey'] ) ? trim( $traffic_settings['ili']['apikey'] ) : '';
    $bli_apikey = isset( $traffic_settings['bli']['apikey'] ) ? trim( $traffic_settings['bli']['apikey'] ) : '';
?>
    <h3><i class="fa fa-link"></i> Backlink Machine - <a href="http://backlinkmachine.com" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Backlink Machine Email', 'wpautoc_bm_email', $bm_email, false, '', '', 'Your Backlink Machine Email' ); ?>
        <?php wpautoc_field_text( 'Backlink Machine Api Key', 'wpautoc_bm_apikey', $bm_apikey, false, '', 'Get it <a target="_blank" href="http://backlinkmachine.com">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-backlink-machine-api-key/">Tutorial</a>', 'Your Backlink Machine API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-external-link"></i> Backlinks Indexer - <a href="https://wpautocontent.com/support/backlinksindexer" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Backlinks Indexer Api Key', 'wpautoc_bli_apikey', $bli_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/backlinksindexer">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-backlinks-indexer-api-key/">Tutorial</a>', 'Your Backlinks Indexer API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-external-link"></i> Instant Link Indexer - <a href="https://wpautocontent.com/support/instantlinksindexer" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Instant Link Indexer Api Key', 'wpautoc_ili_apikey', $ili_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/instantlinksindexer">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-instant-link-indexer-api-key/">Tutorial</a>', 'Your Instant Link Indexer API Key' ); ?>
    </table>
    <br/>
<?php
}

function wpautoc_settings_tab_ecommerce( $settings = false ) {
    $aff_settings = isset( $settings['affiliate'] ) ? $settings['affiliate'] : false;
    $amazon_key = isset( $aff_settings['amazon']['key'] ) ? trim( $aff_settings['amazon']['key'] ) : '';
    $amazon_secret = isset( $aff_settings['amazon']['secret'] ) ? trim( $aff_settings['amazon']['secret'] ) : '';
    $amazon_tag = isset( $aff_settings['amazon']['tag'] ) ? trim( $aff_settings['amazon']['tag'] ) : '';
    $amazon_country = isset( $aff_settings['amazon']['country'] ) ? trim( $aff_settings['amazon']['country'] ) : 'com';
    $ebay_appid = isset( $aff_settings['ebay']['appid'] ) ? trim( $aff_settings['ebay']['appid'] ) : '';
    $ebay_campaignid = isset( $aff_settings['ebay']['campaignid'] ) ? trim( $aff_settings['ebay']['campaignid'] ) : '';
    $ebay_country = isset( $aff_settings['ebay']['country'] ) ? trim( $aff_settings['ebay']['country'] ) : 'US';
    $clickbank_id = isset( $aff_settings['clickbank']['id'] ) ? trim( $aff_settings['clickbank']['id'] ) : '';
    $bestbuy_api = isset( $aff_settings['bestbuy']['apikey'] ) ? trim( $aff_settings['bestbuy']['apikey'] ) : '';
    $walmart_api = isset( $aff_settings['walmart']['apikey'] ) ? trim( $aff_settings['walmart']['apikey'] ) : '';
    $walmart_aff_id = isset( $aff_settings['walmart']['aff_id'] ) ? trim( $aff_settings['walmart']['aff_id'] ) : '';
    $envato_api = isset( $aff_settings['envato']['apikey'] ) ? trim( $aff_settings['envato']['apikey'] ) : '';
    $envato_username = isset( $aff_settings['envato']['username'] ) ? trim( $aff_settings['envato']['username'] ) : '';

    $aliexpress_api = isset( $aff_settings['aliexpress']['apikey'] ) ? trim( $aff_settings['aliexpress']['apikey'] ) : '';
    $aliexpress_hash = isset( $aff_settings['aliexpress']['hash'] ) ? trim( $aff_settings['aliexpress']['hash'] ) : '';
    $careerjet_affid = isset( $aff_settings['careerjet']['affid'] ) ? trim( $aff_settings['careerjet']['affid'] ) : '';
    $gearbest_appid = isset( $aff_settings['gearbest']['appid'] ) ? trim( $aff_settings['gearbest']['appid'] ) : '';
    $gearbest_appsecret = isset( $aff_settings['gearbest']['appsecret'] ) ? trim( $aff_settings['gearbest']['appsecret'] ) : '';
    $gearbest_deeplink = isset( $aff_settings['gearbest']['deeplink'] ) ? trim( $aff_settings['gearbest']['deeplink'] ) : '';

?>
<h3><i class="fa fa-amazon"></i> Amazon</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'Amazon Key', 'wpautoc_amazon_key', $amazon_key, false, '', '', 'Your Amazon Key' ); ?>
    <?php wpautoc_field_text( 'Amazon Secret', 'wpautoc_amazon_secret', $amazon_secret, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-an-amazon-key-and-secret/" target="_blank">How to get your free Key and Secret Key from Amazon</a>', 'Your Amazon Secret Key' ); ?>
    <?php wpautoc_field_text( 'Amazon Tag', 'wpautoc_amazon_tag', $amazon_tag, false, '', 'usually ends with -20', 'Your Amazon Affiliate Tag ' ); ?>
    <?php $amazon_countries = wpautoc_get_amazon_countries(); ?>
    <?php wpautoc_field_select( 'Amazon Country', 'wpautoc_amazon_country', $amazon_country, $amazon_countries, false, '' ); ?>
</table>
<br/>
<?php wpautocblock_open( true ); ?>
<h3><i class="fa fa-ebay"></i> eBay</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'App ID', 'wpautoc_ebay_appid', $ebay_appid, false, '', '<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-an-ebay-app/" target="_blank">How to get your free ebay App ID</a>', 'Your ebay App ID' ); ?>
    <?php wpautoc_field_text( 'Default Campaign ID', 'wpautoc_ebay_cid', $ebay_campaignid, false, '', 'Your campaign ID to use. Get it <a target="_blank" href="https://epn.ebay.com/login">here</a>' ); ?>
    <?php $ebay_countries = wpautoc_get_ebay_countries(); ?>
    <?php wpautoc_field_select( 'eBay Country', 'wpautoc_ebay_country', $ebay_country, $ebay_countries, false, '' ); ?>
</table>

<br/>

<h3><i class="fa fa-shopping-cart"></i> Aliexpress</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'EPN API Key', 'wpautoc_aliexpress_api', $aliexpress_api, false, '', 'Get it <a target="_blank" href="https://epn.bz/en/cabinet#/epn-api">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-epn-api-and-hash-for-aliexpress/" target="_blank">Tutorial</a>' ); ?>
    <?php wpautoc_field_text( 'EPN Deeplink Hash', 'wpautoc_aliexpress_hash', $aliexpress_hash, false, '', 'Your EPN Hash. Get it <a target="_blank" href="https://epn.bz/en/cabinet#/creative/create/type/4">here</a>' ); ?>
</table>

<br/>
<h3><i class="fa fa-shopping-cart"></i> Gearbest</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'Gearbest DeepLink ID', 'wpautoc_gearbest_deeplink', $gearbest_deeplink, false, '', 'Get it <a target="_blank" href="https://affiliate.gearbest.com/">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-gearbest-app-id-and-secret/" target="_blank">Tutorial</a>' ); ?>
    <?php wpautoc_field_text( 'Gearbest App ID', 'wpautoc_gearbest_appid', $gearbest_appid, false, '', 'Your Gearbest App ID' ); ?>
    <?php wpautoc_field_text( 'Gearbest App Secret', 'wpautoc_gearbest_appsecret', $gearbest_appsecret, false, '', 'Your Gearbest App Secret' ); ?>
</table>

<br/>
    <?php wpautocblock_close( 1 ); ?>
<h3><i class="fa fa-bell-o"></i> Clickbank</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'CBPro Ads Account Number', 'wpautoc_clickbank_id', $clickbank_id, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/cbproads">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-cbpro-ads-account-number/" target="_blank">Tutorial</a>' ); ?>
</table>
<p><?php _e( '<b>Tip:</b> Would you like to increase your Clickbank Earnings in 2020? The number #1 Clickbank Affiliate in the world shares his secrets to generate <b>$1000 per day or more</b> in his Free Webinar <a href="https://wpsocialcontact.com/products/commissionhero?tid=wpautocnot" target="_blank"><b>Join here for FREE!</b> (limited seats)</a>', 'wp-auto-content' ); ?></p>
<br/>
<?php wpautocblock_open( true ); ?>
<h3><i class="fa fa-shopping-cart"></i> Best Buy</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'BestBuy API Key', 'wpautoc_bestbuy_api', $bestbuy_api, false, '', 'Get it <a target="_blank" href="https://developer.bestbuy.com">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-best-buy-api-key/">Tutorial</a>', 'Your BestBuy API Key' ); ?>
</table>

<br/>
<h3><i class="fa fa-shopping-cart"></i> Walmart</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'Walmart API Key', 'wpautoc_walmart_api', $walmart_api, false, '', 'Get it <a target="_blank" href="https://developer.walmartlabs.com/">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-walmart-api-key-and-get-your-affiliate-id/">Tutorial</a>' ); ?>
    <?php wpautoc_field_text( 'Walmart Affiliate ID', 'wpautoc_walmart_aff_id', $walmart_aff_id, false, '', 'Your Linkshare Id. Get it <a target="_blank" href="http://cli.linksynergy.com/cli/publisher/home.php">here</a>' ); ?>
</table>

<br/>
<h3><i class="fa fa-leaf"></i> Envato</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'Envato API Key', 'wpautoc_envato_api', $envato_api, false, '', 'Get it <a target="_blank" href="https://build.envato.com/my-apps/#tokens">here</a> - <a target="_blank" href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-envato-api-key/">Tutorial</a>', 'Your Envato API Key' ); ?>
    <?php wpautoc_field_text( 'Envato username', 'wpautoc_envato_username', $envato_username, false, '', 'So you can get affiliate commissions from Envato sites' ); ?>
</table>

<br/>
    <?php wpautocblock_close( 1 ); ?>
<h3><i class="fa fa-location-arrow"></i> Careerjet</h3>
<table class="form-table">
    <?php wpautoc_field_text( 'Careerjet Aff id', 'wpautoc_careerjet_affid', $careerjet_affid, false, '', 'Your careerjet affiliate id, so you can get affiliate commissions - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-careerjet-affiliate-id/" target="_blank">Tutorial</a>' ); ?>
</table>
<?php
}

function wpautoc_settings_tab_spinners( $settings = false ) {
    $spinners_settings = isset( $settings['spinners'] ) ? $settings['spinners'] : false;
    $srewriter_email = isset( $spinners_settings['spinrewriter']['email'] ) ? trim( $spinners_settings['spinrewriter']['email'] ) : '';
    $srewriter_apikey = isset( $spinners_settings['spinrewriter']['apikey'] ) ? trim( $spinners_settings['spinrewriter']['apikey'] ) : '';

    $schief_username = isset( $spinners_settings['schief']['username'] ) ? trim( $spinners_settings['schief']['username'] ) : '';
    $schief_password = isset( $spinners_settings['schief']['password'] ) ? trim( $spinners_settings['schief']['password'] ) : '';

    $tbs_username = isset( $spinners_settings['tbs']['username'] ) ? trim( $spinners_settings['tbs']['username'] ) : '';
    $tbs_password = isset( $spinners_settings['tbs']['password'] ) ? trim( $spinners_settings['tbs']['password'] ) : '';

    $wordai_username = isset( $spinners_settings['wordai']['username'] ) ? trim( $spinners_settings['wordai']['username'] ) : '';
    $wordai_password = isset( $spinners_settings['wordai']['password'] ) ? trim( $spinners_settings['wordai']['password'] ) : '';

    $spinner = isset( $spinners_settings['spinner'] ) ? trim( $spinners_settings['spinner'] ) : 1;
    // var_dump($spinners_settings);
?>
    <p><a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank"><i class="fa fa-question"></i> What is an article spinner?</a></p>
    <h3><i class="fa fa-random"></i> Spin Rewriter - <a href="https://wpautocontent.com/support/spinrewriter" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Spin Rewriter Email', 'wpautoc_srewriter_email', $srewriter_email, false, '', '', 'Your Spin Rewriter Email address' ); ?>
        <?php wpautoc_field_text( 'Spin Rewriter Api Key', 'wpautoc_srewriter_apikey', $srewriter_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/spinrewriter">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-spin-rewriter-api-key/" target="_blank">Tutorial</a>', 'Your Spin Rewriter API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-random"></i> Spinner Chief - <a href="https://wpautocontent.com/support/spinnerchief" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Username', 'wpautoc_schief_username', $schief_username, false, '', '', 'Your Email address for Spinner Chief' ); ?>
        <?php wpautoc_field_password( 'Password', 'wpautoc_schief_pass', $schief_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/spinnerchief">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-api-key-for-spinnerchief/" target="_blank">Tutorial</a>', 'Your Spinner Chief Password' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-random"></i> The Best Spinner - <a href="https://wpautocontent.com/support/bestspinner" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Username', 'wpautoc_tbspinner_username', $tbs_username, false, '', '', 'Your Email address for TBS' ); ?>
        <?php wpautoc_field_password( 'Password', 'wpautoc_tbspinner_pass', $tbs_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/bestspinner">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-api-key-for-the-best-spinner/" target="_blank">Tutorial</a>', 'Your TBS Password' ); ?>
    </table>
    <br/>
    <?php 
    // echo wpautoc_spin_text_schief( 'The cat sat on the mat, and this is very good because Spain is a nice country, and I like to go fishing on the weekend to visit my friends, who are very kind' );
    ?>
    <h3><i class="fa fa-random"></i> WordAI - <a href="https://wpautocontent.com/support/wordai" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php wpautoc_field_text( 'Email', 'wpautoc_wordai_username', $wordai_username, false, '', '', 'Your Email address for WordAI' ); ?>
        <?php wpautoc_field_password( 'Password', 'wpautoc_wordai_pass', $wordai_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/wordai">here</a> - <a href="https://wpautocontent.com/support/knowledgebase/how-to-get-your-api-key-for-wordai/" target="_blank">Tutorial</a>', 'Your WordAI Password' ); ?>
    </table>

    <br/>
    <h3>Spinner to Use for your Campaigns</h3>
    <table class="form-table">
    <?php
        $spinners = array( array( 'value' => 1, 'label' => 'Spin Rewriter' ), array( 'value' => 2, 'label' => 'The Best Spinner' ), array( 'value' => 3, 'label' => 'WordAI'  ), array( 'value' => 4, 'label' => 'Spinner Chief',  ) );
    ?>
    <?php wpautoc_field_select( 'Spinner', 'wpautoc_spinner', $spinner, $spinners, false, 'Select the spinner you want to use for your campaigns (you need to enter the details for that spinner above or it won\'t work)' ); ?>
    </table>
    <br/>

<?php
}

function wpautoc_admin_autoresponders( ) {
    wpautoc_check_authorize_ar_api();
    $settings = wpautoc_get_plugin_settings();
    $ar_data = isset( $settings['autoresponders'] ) ? $settings['autoresponders'] : false;
?>

<div id="autoc-tabs" >
    <h2 class="nav-tab-wrapper">
        <a href="#aweber-tab" class="nav-tab nav-tab-active">Aweber</a>
        <a href="#getresponse-tab" class="nav-tab" >Getresponse</a>
        <a href="#icontact-tab" class="nav-tab">Icontact</a>
        <a href="#mailchimp-tab" class="nav-tab">Mailchimp</a>
        <a href="#ccontact-tab" class="nav-tab">Constant Contact</a>
        <a href="#sendreach-tab" class="nav-tab">Sendreach</a>
        <a href="#activecampaign-tab" class="nav-tab">Active Campaign</a>
        <a href="#sendlane-tab" class="nav-tab">Sendlane</a>
        <a href="#mailit-tab" class="nav-tab">Mailit Plugin</a>
        <a href="#mymailit-tab" class="nav-tab">MyMailit</a>
    </h2>

            <div style="clear:both"></div>
            <div id="mailit-tab" class="tab-inner" style="padding: 20px">

                            <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_mailit.png" style="vertical-align: top" /> Mailit Plugin   - <a href="https://wpautocontent.com/support/mailit" target="_blank" style="font-size: 0.9em">Get it here</a></h2>
                            <form action="" method="post" class="form-horizontal" id="mailit-vp-form">
                            <?php
                                $mailit_type = isset( $plugin_options['autoresponders']['mailit']['install_type'] ) ? $plugin_options['autoresponders']['mailit']['install_type'] : 1;
                            ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row">Install Type</th>
                                    <td>
                                        <select name="wpautoc_mailit_type" id="wpautoc_mailit_type"  class="form-control">
                                            <option value="1" <?php selected( $mailit_type, '1' );?>>Local</option>
                                            <option value="2" <?php selected( $mailit_type, '2' );?>>Remote</option>
                                        </select>
                                        <span class="description">If you have Mailit installed on the same WordPress install, select "Local". Otherwise, select "Remote"</span>
                                    </td>
                                </tr>
                            </table>
                            <div class="form-group" id="mailit-local-settings" <?php if($mailit_type == 2) echo 'style="display:none"';?>>
                                <?php
                                $plugin_name = '/mailit';
                                if ( wpautoc_is_plugin_there( $plugin_name ) ) {
                                    if ( wpautoc_is_mailit_plugin_active() ) {
                                        echo '<p class="text-success">Plugin Active</p>';
                                    }
                                    else
                                        echo '<p class="text-warning">Plugin installed, but not active &nbsp;&nbsp;&nbsp; <input type="submit" class="button button-secondary" value="Activate" id="do-activate-mailit"><input type="hidden" id="activate-mailit-plg" name="activate-mailit" value="0"><br/></p>';
                                }
                                else
                                    echo '<p class="text-error">Plugin not installed &nbsp; </p>';
                            ?>
                            </div>

                                    <button type="submit" class="button button-primary" style="margin-right:20px"><i class="fa fa-sign-in"></i> Update changes </button>
                                    <?php if (isset($ar_data['mailit']['cache_expires']) && !empty($ar_data['mailit']['cache_expires'])
                                        && wpautoc_is_plugin_there( $plugin_name ) && wpautoc_is_mailit_plugin_active()
                                    ) { ?>
                                        <button type="button" class="mailit_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                                    <?php } ?>

                            <div class="form-group" id="mailit-remote-settings" <?php if($mailit_type == 1) echo 'style="display:none"';?>>

                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">Mailit URL</th>
                                        <td>
                                         <input type="text" class="regular-text" name="wpautoc_mailit_url" placeholder="Enter your Mailt URL" value="<?php echo empty( $ar_data['mailit']['list_url'] ) ? '' : $ar_data['mailit']['list_url']; ?>">
                                            <span class="description">You can find it under Mailit > Lists > Remote Submit URL</span>
                                        </td>
                                    </tr>
                                </table>

                                    <input type="hidden" name="wpautoc_mailit_authorize" value="1">
                                    <button type="submit" class="button button-secondary" ><i class="fa fa-sign-in"></i> Update changes </button>
                            </div>

                        <!-- </fieldset> -->
                        <p><?php
                           // var_dump( wpautoc_get_mailit_mailing_lists()); //nvrC
                          // wpautoc_ar_mailit_subscribe_user(1, 'rrxxrrr@gmail.com', 'xxxxx');
                        // wpautoc_ar_getresponse_move_subscriber('nvrC', 0, 'raulmellado@gmail.com', 0);
                        ?></p>
                </form>

            </div>

            <div id="mymailit-tab" class="tab-inner" style="padding: 20px">

                            <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_mymailit.jpg" style="vertical-align: top" /> My Mailit   - <a href="https://wpautocontent.com/support/mymailit" target="_blank" style="font-size: 0.9em">Get it here</a></h2>
                            <form action="" method="post" class="form-horizontal" id="mymailit-vp-form">

                            <div class="form-group" id="my-mailit-remote-settings" >

                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">My Mailit List URL</th>
                                        <td>
                                         <input type="text" class="regular-text" name="wpautoc_mymailit_url" placeholder="Enter your Mailt List URL" value="<?php echo empty( $ar_data['mymailit']['list_url'] ) ? '' : $ar_data['mymailit']['list_url']; ?>">
                                            <span class="description">It will be something like https://mymailit.com/members/remotesubmit.php?l=12345</span>
                                        </td>
                                    </tr>
                                </table>

                                    <input type="hidden" name="wpautoc_mymailit_authorize" value="1">
                                    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Update changes </button>
                            </div>

                        <!-- </fieldset> -->
                        <p><?php
                           // var_dump( wpautoc_get_mailit_mailing_lists()); //nvrC
                          // wpautoc_ar_mailit_subscribe_user(1, 'rrxxrrr@gmail.com', 'xxxxx');
                        // wpautoc_ar_getresponse_move_subscriber('nvrC', 0, 'raulmellado@gmail.com', 0);
                        ?></p>
                </form>

            </div>

            <div id="aweber-tab" class="tab-inner" style="padding: 20px;display:block">

    <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_aweber.png" style="vertical-align: top" /> Aweber - <a href="https://wpautocontent.com/support/aweber" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <?php
    if ( isset($aweber_data['token']) && isset( $aweber_data['token'] ) && $aweber_data['token_secret'] && $aweber_data['token_secret'] ) :?>
        <p class="text-success">You have successfully connected your Aweber account!</p>
        <form action="" method="post">
            <input type="hidden" name="wpautoc_aweber_unauthorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa icon-ban-circle"></i> Disconnect</button>
            <button style="margin-left:20px;" type="button" class="aweber_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
        </form>
    <?php  else: ?>
        <h4 >Connect To Aweber</h4>
            <p>You need to connect your Aweber account first</p>
            <form action="" method="post">
                <input type="hidden" name="wpautoc_aweber_authorize" value="1">
                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Connect </button>
            </form>
    <?php endif; ?>

    <br/>
    </div>
            <div id="getresponse-tab" class="tab-inner" style="padding: 20px">
    <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_getresponse.png" style="vertical-align: top" /> Getresponse  - <a href="https://wpautocontent.com/support/getresponse" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <form action="" method="post" class="form-horizontal">

    <table class="form-table">
        <tr valign="top">
            <th scope="row">API Key</th>
            <td>
             <input type="text" class="form-control regular-text" name="wpautoc_getresponse_api" placeholder="Enter your API key" value="<?php echo empty( $ar_data['getresponse']['apikey'] ) ? '' : $ar_data['getresponse']['apikey']; ?>">
                <span class="description">Get your API key <a target="_blank" href="http://support.getresponse.com/faq/where-i-find-api-key">here</a></span>
            </td>
        </tr>
    </table>

    <input type="hidden" name="wpautoc_getresponse_authorize" value="1">
    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
    <?php if (isset($ar_data['getresponse']['cache_expires']) && !empty($ar_data['getresponse']['cache_expires'])) { ?>
        <button style="margin-left:20px;" type="button" class="getresponse_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
    <?php } ?>
    </form>

    <br/>
    </div>
    <div id="icontact-tab" class="tab-inner" style="padding: 20px">

    <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_icontact.png" style="vertical-align: top" /> iContact  - <a href="https://wpautocontent.com/support/icontact" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <form action="" method="post" class="form-horizontal">

        <table class="form-table">
            <tr valign="top">
                <th scope="row">App Id</th>
                <td>
                 <input type="text" class="regular-text form-control" name="wpautoc_icontact_app_id" placeholder="Enter your APP Id" value="<?php echo empty( $ar_data['icontact']['app_id'] ) ? '' : $ar_data['icontact']['app_id']; ?>">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Api Username</th>
                <td>
                 <input type="text" class="regular-text form-control" name="wpautoc_icontact_user" placeholder="Enter your Api Username" value="<?php echo empty( $ar_data['icontact']['user'] ) ? '' : $ar_data['icontact']['user']; ?>">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Api Password</th>
                <td>
                 <input type="password" class="regular-text form-control" name="wpautoc_icontact_pass" placeholder="Enter your Api Password" value="<?php echo empty( $ar_data['icontact']['pass'] ) ? '' : $ar_data['icontact']['pass']; ?>">
                    <span class="description">Get your API details <a target="_blank" href="http://developer.icontact.com/documentation/register-your-app/">here</a></span>
                </td>
            </tr>

        </table>

        <input type="hidden" name="wpautoc_icontact_authorize" value="1">
        <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
        <?php if (isset($ar_data['icontact']['cache_expires']) && !empty($ar_data['icontact']['cache_expires'])) { ?>
            <button style="margin-left:20px;" type="button" class="icontact_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
        <?php } ?>
    </form>

    <br/>
    </div>
    <div id="mailchimp-tab" class="tab-inner" style="padding: 20px">
    <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_mailchimp.png" style="vertical-align: top" /> Mailchimp  - <a href="https://wpautocontent.com/support/mailchimp" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
    <form action="" method="post" class="form-horizontal">

        <table class="form-table">
            <tr valign="top">
                <th scope="row">API Key</th>
                <td>
                 <input type="text" class="form-control regular-text" name="wpautoc_mailchimp_api" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['mailchimp']['apikey'] ) ? '' : $ar_data['mailchimp']['apikey']; ?>">
                 <span class="description">Get your API key <a target="_blank" href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key">here</a></span>
                </td>
            </tr>
       </table>

                <input type="hidden" name="wpautoc_mailchimp_authorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
            <?php if (isset($ar_data['mailchimp']['cache_expires']) && !empty($ar_data['mailchimp']['cache_expires'])) { ?>
                <button style="margin-left:20px;" type="button" class="mailchimp_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
            <?php } ?>

            <p><?php
                 // var_dump( wpautoc_get_mailchimp_mailing_lists());
                  // wpautoc_ar_constcontact_subscribe_user(2, 'raulmellado@gmail.com', 'Raul Mellado');
            // wpautoc_ar_constcontact_move_subscriber(2, 1, 'raulmellado@gmail.com', 0);
            ?></p>
    </form>

    <br/>
    </div>
        <div id="ccontact-tab" class="tab-inner" style="padding: 20px">
        <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_constantcontact.png" style="vertical-align: top" /> Constant Contact  - <a href="https://wpautocontent.com/support/constantcontact" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
        <form action="" method="post" class="form-horizontal">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API Username</th>
                    <td>
                     <input type="text" class="form-control regular-text" name="wpautoc_ccontact_user" placeholder="Enter your Api Username" value="<?php echo empty( $ar_data['ccontact']['user'] ) ? '' : $ar_data['ccontact']['user']; ?>">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">API Password</th>
                    <td>
                        <input type="password" class="form-control regular-text" name="wpautoc_ccontact_pass" placeholder="Enter your Api Password" value="<?php echo empty( $ar_data['ccontact']['pass'] ) ? '' : $ar_data['ccontact']['pass']; ?>">
                        <span class="description">Get your API details <a target="_blank" href="http://developer.constantcontact.com/">here</a></span>
                    </td>
                </tr>

           </table>

            <input type="hidden" name="wpautoc_ccontact_authorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
            <?php if (isset($ar_data['constantcontact']['cache_expires']) && !empty($ar_data['constantcontact']['cache_expires'])) { ?>
                <button style="margin-left:20px;" type="button" class="constantcontact_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
            <?php } ?>

        </form>
                                <p><?php
                                     // var_dump( wpautoc_get_constantcontact_mailing_lists());
                                      // wpautoc_ar_constcontact_subscribe_user(2, 'raulmellado@gmail.com', 'Raul Mellado');
                                // wpautoc_ar_constcontact_move_subscriber(2, 1, 'raulmellado@gmail.com', 0);
                                ?></p>
    <br/>
    </div>
        <div id="sendreach-tab" class="tab-inner" style="padding: 20px">
            <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_sendreach.png" style="vertical-align: top" /> SendReach  - <a href="https://wpautocontent.com/support/sendreach" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

            <form action="" method="post" class="form-horizontal">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">User Id</th>
                        <td>
                         <input type="text" class="form-control regular-text" name="wpautoc_sendreach_user_id" placeholder="Enter your User Id" value="<?php echo empty( $ar_data['sendreach']['user'] ) ? '' : $ar_data['sendreach']['user']; ?>">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Api KEY</th>
                        <td>
                         <input type="text" class="form-control regular-text" name="wpautoc_sendreach_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['sendreach']['apikey'] ) ? '' : $ar_data['sendreach']['apikey']; ?>">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">API Secret</th>
                        <td>
                            <input type="text" class="form-control regular-text" name="wpautoc_sendreach_secret" placeholder="Enter your Api Secret" value="<?php echo empty( $ar_data['sendreach']['apisecret'] ) ? '' : $ar_data['sendreach']['apisecret']; ?>">
                            <span class="description">Get your API details <a target="_blank" href="http://developer.sendreach.com/documentation/register-your-app/">here</a></span>
                        </td>
                    </tr>
                 </table>

                <input type="hidden" name="wpautoc_sendreach_authorize" value="1">
                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                <?php if (isset($ar_data['sendreach']['cache_expires']) && !empty($ar_data['sendreach']['cache_expires'])) { ?>
                    <button style="margin-left:20px;" type="button" class="sendreach_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                <?php } ?>

                <p><?php

                // var_dump( wpautoc_get_sendreach_mailing_lists(1));
                 // wpautoc_ar_sendreach_subscribe_user(14592, 'pepe2@pepito2.com', 'Pepito 2', 'Perez 2');
                // wpautoc_ar_sendreach_move_subscriber();
                ?></p>
            </form>

            <br/>
            </div>
                <div id="activecampaign-tab" class="tab-inner" style="padding: 20px">
                <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_activecampaign.png" style="vertical-align: top" /> Active Campaign  - <a href="https://wpautocontent.com/support/activecampaign" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
                <form action="" method="post" class="form-horizontal">

                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">URL</th>
                            <td>
                             <input type="text" class="form-control regular-text" name="wpautoc_activecampaign_url" placeholder="Enter your Active Campaign URL" value="<?php echo empty( $ar_data['activecampaign']['url'] ) ? '' : $ar_data['activecampaign']['url']; ?>">
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Api KEY</th>
                            <td>
                             <input type="text" class="form-control regular-text" name="wpautoc_activecampaign_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['activecampaign']['apikey'] ) ? '' : $ar_data['activecampaign']['apikey']; ?>">
                            </td>
                        </tr>
                     </table>

                    <input type="hidden" name="wpautoc_activecampaign_authorize" value="1">
                    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                    <?php if (isset($ar_data['activecampaign']['cache_expires']) && !empty($ar_data['activecampaign']['cache_expires'])) { ?>
                        <button style="margin-left:20px;" type="button" class="activecampaign_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                    <?php } ?>

                    <p><?php

                     // var_dump( wpautoc_get_activecampaign_mailing_lists(1));
                     // wpautoc_ar_activecampaign_subscribe_user(2, 'pepe3@pepito3.com', 'Pepito 2', 'Perez 2');
                    // wpautoc_ar_sendreach_move_subscriber();
                    ?></p>
                </form>
                <br/>
                </div>
                    <div id="sendlane-tab" class="tab-inner" style="padding: 20px">
                    <h2><img src="<?php echo WPAUTOC_URL;?>/img/icons/icon_sendlane.png" style="vertical-align: top" /> Sendlane   - <a href="https://wpautocontent.com/support/sendlane" target="_blank" style="font-size: 0.9em">Get an account</a></h2>



                                            <form action="" method="post" class="form-horizontal">

                                                            <table class="form-table">
                                                                <tr valign="top">
                                                                    <th scope="row">Subdomain</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="wpautoc_sendlane_url" placeholder="Enter your User Id" value="<?php echo empty( $ar_data['sendlane']['url'] ) ? '' : $ar_data['sendlane']['url']; ?>">
                                                                     <span class="description">Ex, if your url is http://yoursite.sendlane.com, enter yoursite</span>
                                                                    </td>
                                                                </tr>

                                                                <tr valign="top">
                                                                    <th scope="row">Api KEY</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="wpautoc_sendlane_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['sendlane']['apikey'] ) ? '' : $ar_data['sendlane']['apikey']; ?>">
                                                                    </td>
                                                                </tr>

                                                                <tr valign="top">
                                                                    <th scope="row">Hash KEY</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="wpautoc_sendlane_hashkey" placeholder="Enter your Hash Key" value="<?php echo empty( $ar_data['sendlane']['hashkey'] ) ? '' : $ar_data['sendlane']['hashkey']; ?>">
                                                                    </td>
                                                                </tr>
                                                             </table>
                                                                <input type="hidden" name="wpautoc_sendlane_authorize" value="1">

                                                                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                                                                <?php if (isset($ar_data['sendlane']['cache_expires']) && !empty($ar_data['sendlane']['cache_expires'])) { ?>
                                                                    <button style="margin-left:20px;" type="button" class="sendlane_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                                                                <?php } ?>

                                                <p><?php

                                                 // var_dump( wpautoc_get_sendlane_mailing_lists(1));
                                                 // wpautoc_ar_sendlane_subscribe_user(1, 'pepe3@pepito3.com', 'Pepito 2', 'Perez 2');
                                                // wpautoc_ar_sendreach_move_subscriber();
                                                ?></p>
                                            </form>
                                    </div>
                                    </div>
                                    </div>
<?php
}

function wpautoc_is_api_valid( $type ) {
    switch( $type ) {
        case 'youtube':
            $api = wpautoc_get_settings( array( 'content', 'youtube', 'apikey' ) );
            $api = trim( $api );
            if( !empty(  $api ) )
                return true;
            break;
        case 'eza':
            $api = wpautoc_get_settings( array( 'content', 'eza', 'apikey' ) );
            $api = trim( $api );
            if( !empty( $api ) )
                return true;
            break;
        case 'vimeo':
            $api = wpautoc_get_settings( array( 'content', 'vimeo' ) );
            if ( !empty( $api[ 'appid' ] ) && !empty( $api[ 'appsecret' ] ) && !empty( $api[ 'token' ] ) )
                return true;
            break;
        case 'amazon':
            $api = wpautoc_get_settings( array( 'affiliate', 'amazon' ) );
            if ( !empty( $api[ 'key' ] ) && !empty( $api[ 'secret' ] ) )
                return true;
            break;
        case 'aliexpress':
            $api = wpautoc_get_settings( array( 'affiliate', 'aliexpress' ) );
            if ( !empty( $api[ 'apikey' ] ) && !empty( $api[ 'hash' ] ) )
                return true;
            break;
        case 'gearbest':
            $api = wpautoc_get_settings( array( 'affiliate', 'gearbest' ) );
            if ( !empty( $api[ 'appid' ] ) && !empty( $api[ 'appsecret' ] ) && !empty( $api[ 'deeplink' ] ) )
                return true;
            break;
        case 'walmart':
            $api = wpautoc_get_settings( array( 'affiliate', 'walmart' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'bestbuy':
            $api = wpautoc_get_settings( array( 'affiliate', 'bestbuy' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'etsy':
            $api = wpautoc_get_settings( array( 'content', 'etsy' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'tumblr':
            $api = wpautoc_get_settings( array( 'social', 'tumblr' ) );
            if ( !empty( $api['key'] ) && !empty( $api['secret'] ) && !empty( $api['oauth_token'] ) && !empty( $api['oauth_secret'] )
             )
                return true;
            break;
        case 'medium':
            $api = wpautoc_get_settings( array( 'social', 'medium' ) );
            if ( !empty( $api[ 'token' ] ) )
                return true;
            break;
        case 'envato':
            $api = wpautoc_get_settings( array( 'affiliate', 'envato' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'udemy':
            $api = wpautoc_get_settings( array( 'content', 'udemy' ) );
            if ( !empty( $api['client_id'] ) && !empty( $api['client_secret'] ) )
                return true;
            break;
        case 'googlebooks':
            $api = wpautoc_get_settings( array( 'content', 'googlebooks' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'eventbrite':
            $api = wpautoc_get_settings( array( 'content', 'eventbrite' ) );
            if ( !empty( $api[ 'token' ] ) )
                return true;
            break;
        case 'yelp':
            $api = wpautoc_get_settings( array( 'content', 'yelp' ) );
            if ( !empty( $api['client_id'] ) && !empty( $api['client_secret'] ) )
                return true;
            break;
        case 'newsapi':
            $api = wpautoc_get_settings( array( 'content', 'newsapi' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'whio':
            $api = wpautoc_get_settings( array( 'content', 'whio' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'nyt':
            $api = wpautoc_get_settings( array( 'content', 'nyt' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'facebook':
            $api = wpautoc_get_settings( array( 'social', 'facebook' ) );
            if ( !empty( $api['app_id'] ) && !empty( $api['app_secret'] ) )
                return true;
            break;
        case 'twitter':
            $api = wpautoc_get_settings( array( 'social', 'twitter' ) );
            if ( !empty( $api['key'] ) && !empty( $api['secret'] ) && !empty( $api['oauth_token'] ) && !empty( $api['oauth_secret'] )
             )
                return true;
            break;
        case 'pinterest':
            $api = wpautoc_get_settings( array( 'social', 'pinterest' ) );
            if ( !empty( $api['email'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        case 'googleplusc':
            $api = wpautoc_get_settings( array( 'social', 'googleplus' ) );
            if ( !empty( $api[ 'key' ] ) )
                return true;
            break;
        case 'googleplust':
            $api = wpautoc_get_settings( array( 'social', 'googleplus' ) );
            if ( !empty( $api['email'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        case 'bmachine':
            $api = wpautoc_get_settings( array( 'traffic', 'bmachine' ) );
            if ( !empty( $api['email'] ) && !empty( $api['apikey'] ) )
                return true;
            break;
        case 'ili':
            $api = wpautoc_get_settings( array( 'traffic', 'ili' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'bli':
            $api = wpautoc_get_settings( array( 'traffic', 'bli' ) );
            if ( !empty( $api[ 'apikey' ] ) )
                return true;
            break;
        case 'reddit':
            $api = wpautoc_get_settings( array( 'social', 'reddit' ) );
            if ( !empty( $api['email'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        case 'instagram':
            $api = wpautoc_get_settings( array( 'social', 'instagram' ) );
            if ( !empty( $api['email'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        case 'linkedin':
            $api = wpautoc_get_settings( array( 'social', 'linkedin' ) );
            if ( !empty( $api['app_id'] ) && !empty( $api['app_secret'] ) )
                return true;
            break;
        case 'stumbleupon':
            $api = wpautoc_get_settings( array( 'social', 'stumbleupon' ) );
            if ( !empty( $api['email'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        case 'buffer':
            $api = wpautoc_get_settings( array( 'social', 'buffer' ) );
            if ( !empty( $api['client_id'] ) && !empty( $api['client_secret'] ) )
                return true;
            break;
        case 'bigcs':
            $api = wpautoc_get_settings( array( 'content', 'bigcs' ) );
            if ( !empty( $api['username'] ) && !empty( $api['apikey'] ) )
                return true;
            break;
        case 'bigas':
            $api = wpautoc_get_settings( array( 'content', 'bigas' ) );
            if ( !empty( $api['username'] ) && !empty( $api['apikey'] ) )
                return true;
            break;
        case 'aforge':
            $api = wpautoc_get_settings( array( 'content', 'aforge' ) );
            if ( !empty( $api['apikey'] ) )
                return true;
            break;
        case 'abuilder':
            $api = wpautoc_get_settings( array( 'content', 'abuilder' ) );
            if ( !empty( $api['username'] ) && !empty( $api['pass'] ) )
                return true;
            break;
        default:
            return false;
    }
    return false;
}

function wpautoc_noapi_msg( $msg ) {
    echo '<div class="notice notice-error inline">'.$msg.'</div>';
}
?>