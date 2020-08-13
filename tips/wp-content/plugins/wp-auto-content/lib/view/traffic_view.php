<?php
function wpautoc_print_traffic_elements( $campaign_id ) {
?>
	<div id="post-body" class="metabox-holder columns-1">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable" id="wpautoc-traffic-els">
				<?php
					if( $campaign_id ) {
						$traffic = wpautoc_get_traffic_elements( $campaign_id );
						if( $traffic ) {
							$i = 1;
							foreach( $traffic as $el ) {
								wpautoc_print_traffic_el( $el, $i++ );
							}
						}
					}
				?>
			</div>
		</div>
	</div>
<?php
}

function wpautoc_print_traffic_el( $traffic, $num ) {
	// var_dump($action);
	switch( $traffic->type ) {
		case WPAUTOC_TRAFFIC_BACKLINKMACHINE:
			return wpautoc_traffic_type_backlinkmachine( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_ILI:
			return wpautoc_traffic_type_ili( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_BLI:
			return wpautoc_traffic_type_bli( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_FACEBOOK:
			return wpautoc_traffic_type_facebook( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_TWITTER:
			return wpautoc_traffic_type_twitter( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_PINTEREST:
			return wpautoc_traffic_type_pinterest( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_STUMBLEUPON:
			return wpautoc_traffic_type_stumbleupon( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_MEDIUM:
			return wpautoc_traffic_type_medium( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_TUMBLR:
			return wpautoc_traffic_type_tumblr( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_LINKEDIN:
			return wpautoc_traffic_type_linkedin( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_BUFFER:
			return wpautoc_traffic_type_buffer( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_REDDIT:
			$done = wpautoc_traffic_type_reddit( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_INSTAGRAM:
			$done = wpautoc_traffic_type_instagram( $traffic, $num );
			break;
		case WPAUTOC_TRAFFIC_GOOGLEPLUS:
			$done = wpautoc_traffic_type_googleplus( $traffic, $num );
			break;
		default:
			echo 'Unknown traffic type';
			break;
	}
}

function wpautoc_traffic_print_box_header( $content, $num ) {
?>
	<div class="postbox autoc-traffic-box">
		<button type="button" class="handlediv wpautoc-handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Content</span><span class="toggle-indicator" aria-hidden="true"></span></button>
		<h2 class="hndle"><span><?php wpautoc_print_traffic_title( $content->type, $num );?></span></h2>
		<div class="inside">
			<p><?php wpautoc_print_traffic_description( $content->type, $num );?></p>
			<?php
				wpautoc_field_hidden( 'wpautoc_traffic['.$num.'][action]', ( $content->id ? 1 : 0 ), '', 'wpautoc_traffic_action');
				wpautoc_field_hidden( 'wpautoc_traffic['.$num.'][type]', $content->type );
				wpautoc_field_hidden( 'wpautoc_traffic['.$num.'][id]', $content->id );
			?>
<?php
}

function wpautoc_print_traffic_title( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_TRAFFIC_FACEBOOK:
			$str_ret =  '<i class="fa fa-facebook"></i> Facebook';
			break;
		case WPAUTOC_TRAFFIC_BACKLINKMACHINE:
			$str_ret =  '<i class="fa fa-link"></i> Backlink Machine';
			break;
		case WPAUTOC_TRAFFIC_ILI:
			$str_ret =  '<i class="fa fa-link"></i> Instant Link Indexer';
			break;
		case WPAUTOC_TRAFFIC_BLI:
			$str_ret =  '<i class="fa fa-link"></i> Backlinks Indexer';
			break;
		case WPAUTOC_TRAFFIC_TWITTER:
			$str_ret =  '<i class="fa fa-twitter"></i> Twitter';
			break;
		case WPAUTOC_TRAFFIC_PINTEREST:
			$str_ret =  '<i class="fa fa-pinterest"></i> Pinterest';
			break;
		case WPAUTOC_TRAFFIC_STUMBLEUPON:
			$str_ret =  '<i class="fa fa-stumbleupon"></i> StumbleUpon';
			break;
		case WPAUTOC_TRAFFIC_MEDIUM:
			$str_ret =  '<i class="fa fa-medium"></i> Medium';
			break;
		case WPAUTOC_TRAFFIC_TUMBLR:
			$str_ret =  '<i class="fa fa-tumblr"></i> Tumblr';
			break;
		case WPAUTOC_TRAFFIC_LINKEDIN:
			$str_ret =  '<i class="fa fa-linkedin"></i> Linkedin';
			break;
		case WPAUTOC_TRAFFIC_BUFFER:
			$str_ret =  '<i class="fa fa-play-circle-o"></i> Buffer App';
			break;
		case WPAUTOC_TRAFFIC_REDDIT:
			$str_ret =  '<i class="fa fa-reddit"></i> Reddit';
			break;
		case WPAUTOC_TRAFFIC_INSTAGRAM:
			$str_ret =  '<i class="fa fa-instagram"></i> Instagram';
			break;
		case WPAUTOC_TRAFFIC_GOOGLEPLUS:
			$str_ret =  '<i class="fa fa-google-plus"></i> Google Plus';
			break;
		default:
			echo 'Unknown content type';
			break;
	}
	echo $num.'. '.$str_ret;
}

function wpautoc_print_traffic_description( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_TRAFFIC_FACEBOOK:
			$str_ret =  'Post to Facebook Pages';
			break;
		case WPAUTOC_TRAFFIC_TWITTER:
			$str_ret =  'Post to you Twitter Timeline';
			break;
		case WPAUTOC_TRAFFIC_BACKLINKMACHINE:
			$str_ret =  'Get backlinks on autopilot using <a target="_blank" href="https://backlinkmachine.com">Backlink Machine</a>';
			break;
		case WPAUTOC_TRAFFIC_ILI:
			$str_ret =  'Get your new urls instantly indexed by Google using <a target="_blank" href="https://wpautocontent.com/support/instantlinksindexer">Instant Link Indexer</a>';
			break;
		case WPAUTOC_TRAFFIC_BLI:
			$str_ret =  'Get your new urls instantly indexed by Google using <a target="_blank" href="https://wpautocontent.com/support/backlinksindexer">Backlinks Indexer</a>';
			break;
		case WPAUTOC_TRAFFIC_PINTEREST:
			$str_ret =  'Publish to Pinterest';
			break;
		case WPAUTOC_TRAFFIC_STUMBLEUPON:
			$str_ret =  'Share to your StumbleUpon account';
			break;
		case WPAUTOC_TRAFFIC_MEDIUM:
			$str_ret =  'Publish to Medium';
			break;
		case WPAUTOC_TRAFFIC_TUMBLR:
			$str_ret =  'Publish to Tumblr';
			break;
		case WPAUTOC_TRAFFIC_LINKEDIN:
			$str_ret =  'Share on your Linkedin account';
			break;
		case WPAUTOC_TRAFFIC_BUFFER:
			$str_ret =  'Publish to Buffer.com';
			break;
		case WPAUTOC_TRAFFIC_REDDIT:
			$str_ret =  'Publish your posts to Reddit';
			break;
		case WPAUTOC_TRAFFIC_INSTAGRAM:
			$str_ret =  'Publish your new posts to Instagram';
			break;
		case WPAUTOC_TRAFFIC_GOOGLEPLUS:
			$str_ret =  'Publish to your Google Plus account or page';
			break;
		default:
			echo 'Unknown content type';
			break;
	}
	echo $str_ret;
}

function wpautoc_traffic_print_box_footer( $content, $num = 0 ) {
?>
	</div>
	<div class="autoc-footer">
		<div class="autoc-footer-actions">
			<button class="button button-secondary btn-delete-traffic" type="button"><i class="fa fa-remove"></i> Delete</button>
			<!-- <button class="button button-secondary btn-disable-content" type="button">Disable</button> -->
		</div>
		<div class="clearboth"></div>
	</div>
		</div>
<?php
}



function wpautoc_traffic_popup() {
?>
<div id="autoc-traffic-modal" style="display:none">
	<h3 style="text-align:center">Add Traffic System</h3>
	<span>Traffic System:</span> <?php wpautoc_traffic_types_select(); ?>
	<br/>
	<br/>
	<button type="button" id="autoc-do-add-traffic" class="button button-primary">Add Traffic System</button>
	<a class="button button-secondary" href="#close" rel="acmodal:close">Cancel</a>

</div>
<?php
}

function wpautoc_add_traffic_block_ajax() {
	$traffic = new stdclass();
	$traffic->type = isset( $_POST[ 'type' ] ) ? intval( $_POST[ 'type' ] ) : 0;
	$num = isset( $_POST[ 'num' ] ) ? intval( $_POST[ 'num' ] ) : 1;
	wpautoc_print_traffic_el( $traffic, $num );
	exit();
}

function wpautoc_traffic_types_select( $val = 0 ) {
	$types = wpautoc_get_traffic_types();
	$str = '<select id="wpautoc-traffic-types"><option value="0">Select...</option>';
	foreach( $types as $type ) {
		if ($type['name'] === 'optgroup') {
			$str .= '<optgroup label="'.$type['label'].'">';
		}   else if (/*$value['value'] &&*/ $type['name'] === 'optgroupclose') {
			$str .= '</optgroup>';
	    } else {
			$str .= '<option value="'.$type['id'].'">'.$type['name'].'</option>';
		}
	}
	$str .= '</select>';
	echo $str;
}


/* Monetize Types */

function wpautoc_traffic_type_facebook( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'facebook' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Facebook, you need a valid App ID and Secret and Authenticate your app.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings, enter your Facebook details and authenticate.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	$fb_pages = wpautoc_get_facebook_pages( true );
	wpautoc_ifield_select( $settings, 'fb_page', 'Facebook Page', 'wpautoc_traffic['.$num.'][settings][fb_page]', $fb_pages, false, 'The Facebook Page where new posts will be published', '', '', '', false, '- Select Page... -' );

	//wpautoc_field_text( 'Channel ID', 'wpautoc_traffic['.$num.'][settings][channel]', $settings->channel, false, '', 'Enter the channel ID' );
	echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_backlinkmachine( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = wpautoc_json_decode_nice( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'bmachine' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to create backlinks on autopilot using Backlink Machine, you need an API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-traffic-tab').'">Click here</a> to go to the plugin settings and enter your Backlink Machine API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_textarea( $settings, 'keywords', 'Keywords', 'wpautoc_traffic['.$num.'][settings][keywords]', false, 'Keywords for backlinking', 'Enter one or more keywords to use inside Backlink Machine (one per line)' );

	$backlinks = wpautoc_blmachine_linknum();
	wpautoc_ifield_select( $settings, 'num_links', 'Number of Backlinks', 'wpautoc_traffic['.$num.'][settings][num_links]', $backlinks, '', 'Number of backlinks to create for each new post' );

	echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_ili( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = wpautoc_json_decode_nice( $content->settings );
	echo '<p>Every new post created will be submitted to <a href="https://wpautocontent.com/support/instantlinksindexer" target="_blank">Instant Link Indexer</a> so it gets picked up by Google and other Search Engines much faster</p>';

	$is_api_valid = wpautoc_is_api_valid( 'ili' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to submit your new URLs to Instant Link Indexer, you need a valid API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-traffic-tab').'">Click here</a> to go to the plugin settings and enter your Instant Link Indexer API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo '<table class="form-table">';
	// wpautoc_ifield_textarea( $settings, 'keywords', 'Keywords', 'wpautoc_traffic['.$num.'][settings][keywords]', false, 'Keywords for backlinking', 'Enter one or more keywords to use inside Backlink Machine (one per line)' );

	// $backlinks = wpautoc_blmachine_linknum();
	// wpautoc_ifield_select( $settings, 'num_links', 'Number of Backlinks', 'wpautoc_traffic['.$num.'][settings][num_links]', $backlinks );

	// echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_bli( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = wpautoc_json_decode_nice( $content->settings );
	echo '<p>Every new post created will be submitted to <a href="https://wpautocontent.com/support/backlinksindexer" target="_blank">Backlinks Indexer</a> so it gets picked up by Google and other Search Engines much faster</p>';

	$is_api_valid = wpautoc_is_api_valid( 'bli' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to submit your new URLs to Backlinks Indexer, you need a valid API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-traffic-tab').'">Click here</a> to go to the plugin settings and enter your Backlinks Indexer API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo '<table class="form-table">';
	// wpautoc_ifield_textarea( $settings, 'keywords', 'Keywords', 'wpautoc_traffic['.$num.'][settings][keywords]', false, 'Keywords for backlinking', 'Enter one or more keywords to use inside Backlink Machine (one per line)' );

	// $backlinks = wpautoc_blmachine_linknum();
	// wpautoc_ifield_select( $settings, 'num_links', 'Number of Backlinks', 'wpautoc_traffic['.$num.'][settings][num_links]', $backlinks );

	// echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_twitter( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'twitter' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share posts to Twitter, you need a valid Consumer Key and Oauth Token.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Twitter details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_pinterest( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'pinterest' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Pinterest, you need to enter your details first.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Pinterest details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_include_nextgen();
	$boards = wpautoc_get_pinterest_boards( true );
	// var_dump($boards);
	wpautoc_ifield_select( $settings, 'board', 'Pinterest Board', 'wpautoc_traffic['.$num.'][settings][board]', $boards, false, 'The Pinterest Board where new posts will be published', '', '', '', false, '- Select Board... -' );

	//wpautoc_field_text( 'Channel ID', 'wpautoc_traffic['.$num.'][settings][channel]', $settings->channel, false, '', 'Enter the channel ID' );
	echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_medium( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	$is_api_valid = wpautoc_is_api_valid( 'medium' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Medium, you need to enter your Medium Token.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Medium Token.</p>';
		wpautoc_noapi_msg( $msg );
	}

	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_tumblr( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'tumblr' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Tumblr, you need to enter your Tumblr API Details.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Tumblr Details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_linkedin( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'linkedin' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Linkedin, you need to enter your Linkedin App ID and Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Linkedin details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_buffer( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'buffer' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Buffer, you need to enter your Buffer Client ID and Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Buffer Details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_stumbleupon( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'stumbleupon' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Stumbleupon, you need to enter your email and password.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Stumbleupon details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	$categories = wpautoc_get_stumbleupon_cats( true );
	// var_dump($categories);
	wpautoc_ifield_select( $settings, 'category', 'StumbleUpon category', 'wpautoc_traffic['.$num.'][settings][category]', $categories, false, 'The StumbleUpon category where new posts will be published', '', '', '', false, '- Select Category... -' );

	echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_reddit( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'reddit' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Reddit, you need to enter your username and password.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Reddit details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_field_text( 'Subreddit', 'wpautoc_traffic['.$num.'][settings][subreddit]', $settings->subreddit, false, '', 'Enter the Subreddit to publish (ej: r/space).' );


	echo '</table>';
	echo '<p><b>Warning!</b> Make sure to only post to <b>your own subreddits</b> or Reddit will ban your account</p>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_instagram( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	$is_api_valid = wpautoc_is_api_valid( 'instagram' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Instagram, you need to enter your username and password.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Instagram details.</p>';
		wpautoc_noapi_msg( $msg );
	}

	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}

function wpautoc_traffic_type_googleplus( $content, $num ) {
	wpautoc_traffic_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'googleplust' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to share to Google+, you need to enter your Google+ API details first.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Google+ email and password.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_field_text( 'Page ID', 'wpautoc_traffic['.$num.'][settings][page_id]', $settings->page_id, false, '', 'Enter the Page ID to publish. Leave Empty if instead of publishing to a page, you want to publish to your public profile.' );
	echo '</table>';
	// echo 'test;';
	wpautoc_traffic_print_box_footer( $content, $num );
}
?>