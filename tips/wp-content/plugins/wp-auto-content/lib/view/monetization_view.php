<?php
function wpautoc_print_monetize_elements( $campaign_id ) {
?>
	<div id="post-body" class="metabox-holder columns-1">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable" id="wpautoc-monetize-els">
				<?php
					if( $campaign_id ) {
						$monetize = wpautoc_get_monetize_elements( $campaign_id );
						if( $monetize ) {
							$i = 1;
							foreach( $monetize as $el ) {
								// var_dump($el);
								wpautoc_print_monetize_el( $el, $i++ );
							}
						}
					}
				?>
			</div>
		</div>
	</div>
<?php
}

function wpautoc_print_monetize_el( $monetize, $num ) {
	// var_dump($monetize);
	switch( $monetize->type ) {
		case WPAUTOC_MONETIZE_BANNER:
			return wpautoc_monetization_type_banner( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_ADSENSE:
			return wpautoc_monetization_type_adsense( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_ADCODE:
			return wpautoc_monetization_type_adcode( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_AMAZON:
			return wpautoc_monetization_type_amazon( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_ALIEXPRESS:
			return wpautoc_monetization_type_aliexpress( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_GEARBEST:
			return wpautoc_monetization_type_gearbest( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_LINKS:
			return wpautoc_monetization_type_inlinelinks( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_INTEXT:
			return wpautoc_monetization_type_intext( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_OPTIN:
			return wpautoc_monetization_type_optin( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_SOCIALLOCK:
			return wpautoc_monetization_type_sociallock( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_VIRALADS:
			return wpautoc_monetization_type_viralads( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_TEXTADS:
			return wpautoc_monetization_type_textads( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_CLICKBANK:
			return wpautoc_monetization_type_clickbank( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_EBAY:
			return wpautoc_monetization_type_ebay( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_WALMART:
			return wpautoc_monetization_type_walmart( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_BESTBUY:
			return wpautoc_monetization_type_bestbuy( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_ENVATO:
			return wpautoc_monetization_type_envato( $monetize, $num );
			break;
		case WPAUTOC_MONETIZE_POPUP:
			return wpautoc_monetization_type_popup( $monetize, $num );
			break;
		default:
		// var_dump($monetize);
			echo 'Unknown monetize type';
			break;
	}
}

function wpautoc_monetize_print_box_header( $content, $num ) {
?>
	<div class="postbox autoc-monetization-box">
		<button type="button" class="handlediv wpautoc-handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Content</span><span class="toggle-indicator" aria-hidden="true"></span></button>
		<h2 class="hndle"><span><?php wpautoc_print_monetize_title( $content->type, $num );?></span></h2>
		<div class="inside">
			<p><?php wpautoc_print_monetize_description( $content->type, $num );?></p>
			<?php
				wpautoc_field_hidden( 'wpautoc_monetize['.$num.'][action]', ( $content->id ? 1 : 0 ), '', 'wpautoc_monetize_action');
				wpautoc_field_hidden( 'wpautoc_monetize['.$num.'][type]', $content->type );
				wpautoc_field_hidden( 'wpautoc_monetize['.$num.'][id]', $content->id );
				wpautoc_field_hidden( 'wpautoc_monetize['.$num.'][num]', $num, false, 'wpautoc_num' );
			?>
<?php
}

function wpautoc_print_monetize_title( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_MONETIZE_BANNER:
			$str_ret =  '<i class="fa fa-image"></i> Banner Ads';
			break;
		case WPAUTOC_MONETIZE_ADSENSE:
			$str_ret =  '<i class="fa fa-google"></i> Google Adsense';
			break;
		case WPAUTOC_MONETIZE_ADCODE:
			$str_ret =  '<i class="fa fa-html5"></i> HTML Ad Code';
			break;
		case WPAUTOC_MONETIZE_AMAZON:
			$str_ret =  '<i class="fa fa-amazon"></i> Amazon Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_ALIEXPRESS:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Aliexpress Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_GEARBEST:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Gearbest Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_LINKS:
			$str_ret =  '<i class="fa fa-link"></i> Inline Links';
			break;
		case WPAUTOC_MONETIZE_INTEXT:
			$str_ret =  '<i class="fa fa-comment"></i> In-text ads';
			break;
		case WPAUTOC_MONETIZE_OPTIN:
			$str_ret =  '<i class="fa fa-envelope"></i> Optin Form';
			break;
		case WPAUTOC_MONETIZE_SOCIALLOCK:
			$str_ret =  '<i class="fa fa-lock"></i> Social Lock';
			break;
		case WPAUTOC_MONETIZE_VIRALADS:
			$str_ret =  '<i class="fa fa-file-image-o"></i> Viral Ads';
			break;
		case WPAUTOC_MONETIZE_TEXTADS:
			$str_ret =  '<i class="fa fa-list-alt"></i> Text Ads';
			break;
		case WPAUTOC_MONETIZE_CLICKBANK:
			$str_ret =  '<i class="fa fa-bookmark"></i> Clickbank Ads';
			break;
		case WPAUTOC_MONETIZE_EBAY:
			$str_ret =  '<i class="fa fa-ebaY"></i> eBay Ads';
			break;
		case WPAUTOC_MONETIZE_WALMART:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Walmart Products';
			break;
		case WPAUTOC_MONETIZE_BESTBUY:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Best Buy Products';
			break;
		case WPAUTOC_MONETIZE_ENVATO:
			$str_ret =  '<i class="fa fa-leaf"></i> Envato Products';
			break;
		case WPAUTOC_MONETIZE_POPUP:
			$str_ret =  '<i class="fa fa-commenting-o"></i> Popup Content';
			break;
		default:
			echo 'Unknown monetization type - title';
			break;
	}
	echo $num.'. '.$str_ret;
}

function wpautoc_print_monetize_description( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_MONETIZE_BANNER:
			$str_ret =  'Add Banner Ads';
			break;
		case WPAUTOC_MONETIZE_ADSENSE:
			$str_ret =  'Add Google Adsense Ads';
			break;
		case WPAUTOC_MONETIZE_ADCODE:
			$str_ret =  'Any HTML code (banner ads, text, autoresponder optin, etc.)';
			break;
		case WPAUTOC_MONETIZE_AMAZON:
			$str_ret =  'Amazon Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_ALIEXPRESS:
			$str_ret =  'Aliexpress Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_GEARBEST:
			$str_ret =  'Gearbest Affiliate Ads';
			break;
		case WPAUTOC_MONETIZE_LINKS:
			$str_ret =  'Add links to specific keywords. Click on "Add Keyword", and every time that particular keyword appears in the content for this campaign, it will be linked to the url of your choice. You can link to your own offers, affiliate offers, etc.';
			break;
		case WPAUTOC_MONETIZE_INTEXT:
			$str_ret =  'Add links to specific keywords and display content inside a popup/popover';
			break;
		case WPAUTOC_MONETIZE_OPTIN:
			$str_ret =  'Capture leads with an optin form';
			break;
		case WPAUTOC_MONETIZE_SOCIALLOCK:
			$str_ret =  'Hide content from visitors until they share it on social media or like your page';
			break;
		case WPAUTOC_MONETIZE_VIRALADS:
			$str_ret =  'Display eye catching attention ads with image/text/content';
			break;
		case WPAUTOC_MONETIZE_TEXTADS:
			$str_ret =  'Display PPC-like text ads';
			break;
		case WPAUTOC_MONETIZE_CLICKBANK:
			$str_ret =  'Automated Clickbank Ads (text or image)';
			break;
		case WPAUTOC_MONETIZE_EBAY:
			$str_ret =  'Display ebay products to get an affiliate commission';
			break;
		case WPAUTOC_MONETIZE_WALMART:
			$str_ret =  'Display Walmart products to get an affiliate commission';
			break;
		case WPAUTOC_MONETIZE_BESTBUY:
			$str_ret =  'Display Best Buy products to get an affiliate commission';
			break;
		case WPAUTOC_MONETIZE_ENVATO:
			$str_ret =  'Display Envato products to get an affiliate commission';
			break;
		case WPAUTOC_MONETIZE_POPUP:
			$str_ret =  'Display anything inside of a popup';
			break;
		default:
			echo 'Unknown monetization type - description';
			break;
	}
	echo $str_ret;
}

function wpautoc_monetize_print_box_footer( $content, $num = 0 ) {
?>
	</div>
	<div class="autoc-footer">
		<div class="autoc-footer-actions">
			<button class="button button-secondary btn-delete-monetization" type="button"><i class="fa fa-remove"></i> Delete</button>
			<!-- <button class="button button-secondary btn-disable-content" type="button">Disable</button> -->
		</div>
		<div class="clearboth"></div>
	</div>
		</div>
<?php
}



function wpautoc_monetize_popup() {
?>
<div id="autoc-monetize-modal" style="display:none">
	<h3 style="text-align:center">Add Monetization Method</h3>
	<span>Monetization Method:</span> <?php wpautoc_monetize_types_select(); ?>
	<br/>
	<br/>
	<button type="button" id="autoc-do-add-monetization" class="button button-primary">Add Monetization Method</button>
	<a class="button button-secondary" href="#close" rel="acmodal:close">Cancel</a>
</div>
<?php
}

function wpautoc_add_monetize_block_ajax() {
	$monetize = new stdclass();
	$monetize->type = isset( $_POST[ 'type' ] ) ? intval( $_POST[ 'type' ] ) : 0;
	$num = isset( $_POST[ 'num' ] ) ? intval( $_POST[ 'num' ] ) : 1;
	wpautoc_print_monetize_el( $monetize, $num );
	exit();
}

function wpautoc_monetize_types_select( $val = 0 ) {
	$types = wpautoc_get_monetize_types();
	$str = '<select id="wpautoc-monetize-types"><option value="0">Select...</option>';
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

function wpautoc_monetization_type_banner( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_image( $settings, 'image', 'Image', 'wpautoc_monetize['.$num.'][settings][image]', false, 'Banner Image', 'Enter one or more keywords to use inside Backlink Machine (one per line)' );

	wpautoc_ifield_text( $settings, 'link', 'Link URL', 'wpautoc_monetize['.$num.'][settings][link]', false, 'Link URL', 'The url where the visitor will be redirected after click' );

	wpautoc_ifield_checkbox( $settings, 'new_win', 'Open in New Window', 'wpautoc_monetize['.$num.'][settings][new_win]', false, 'If checked, the link will load in a new tab/window' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_adsense( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';


	wpautoc_ifield_textarea( $settings, 'code', 'Adsense Code', 'wpautoc_monetize['.$num.'][settings][code]', false, 'Adsense Code', 'Paste your Adsense Ad Code here', '', 'wpautoc-tall-textarea' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Ad Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_adcode( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';


	wpautoc_ifield_html( $settings, 'html', 'Ad Code', 'wpautoc_monetize['.$num.'][settings][html]', false, 'HTML Code', 'HTML Code' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Ad Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_amazon( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'amazon' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to ad Amazon Affiliate Ads, you need a valid Amazon Key/Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Amazon details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );

	$cats = wpautoc_get_amazon_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Amazon category', '', '' );

	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Amazon Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_aliexpress( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'aliexpress' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to display Aliexpress Affiliate ads, you need a valid EPN API Key and Deeplink Hash.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your EPN Affiliate details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );

	// $cats = wpautoc_get_aliexpress_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Amazon category', '', '' );

	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Aliexpress Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}


function wpautoc_monetization_type_gearbest( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'gearbest' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to display Gearbest Affiliate ads, you need a valid Deeplink, App ID and Secret.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Gearbest Affiliate details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );

	// $cats = wpautoc_get_aliexpress_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Amazon category', '', '' );

	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Aliexpress Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_inlinelinks( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	// echo '<table class="form-table">';

	// $numbers = wpautoc_get_numbers( 1, 10, 'Any' );
	// wpautoc_ifield_select( $settings, 'num_links', 'Maximum links per post', 'wpautoc_monetize['.$num.'][settings][num_links]', $numbers, false, 'Maximum number of links per post', '', '' );
	echo wpautoc_inline_links_html( $settings, $num );
	// echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_inline_links_html( $content, $num ) {
	$str = '<div class="wpautoc-inline-links">
		<table class="wpautoc-inline-links-table form-table"><thead><tr><th scope="col" style="width:34%">Keyword '.wpautoc_pop_admin('The keyword to be linked inside the article').'</th><th scope="col" style="width:60%">URL '.wpautoc_pop_admin('The URL to link that keyword').'</th><th scope="col" style="width:6%">&nbsp;</th></tr></thead><tbody>';
	$links = isset( $content->links ) ? $content->links : false;
	// var_dump($links);
	if( $links ) {
		$i = 0;
		foreach( $links as $link ) {
			$str .= wpautoc_inline_links_row( $link, $num, $i++ );
		}
	}
	$str .= '</tbody></table><br/>';
	$str .= '<button type="button" class="button button-secondary wpautoc-add-inline-link"><i class="fa fa-plus"></i> Add Link</button></div>';
	echo $str;
}

function wpautoc_inline_links_row( $link, $num, $i ) {
	$field1_name = 'wpautoc_monetize['.$num.'][settings][links]['.$i.'][keyword]';
	$field2_name = 'wpautoc_monetize['.$num.'][settings][links]['.$i.'][url]';
	$keyword = $link->keyword;
	$url = $link->url;
	return '<tr class="wpautoc_links_row"><td><input type="text" style="width:100%" name="'.$field1_name.'" value="'.$keyword.'" /></td><td><input type="text" style="width:100%" name="'.$field2_name.'" value="'.$url.'" /></td>
        <td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
}

function wpautoc_monetization_type_intext( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The add will be appended inside this keyword' );

	wpautoc_ifield_html( $settings, 'html', 'Ad Code', 'wpautoc_monetize['.$num.'][settings][html]', false, 'HTML Code', 'HTML Code' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_optin( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<h3>Autoresponder:</h3>';
	echo '<table class="form-table">';

	$autoresponders = wpautoc_get_autoresponder_types() ;

	wpautoc_ifield_select( $settings, 'ar_type', 'Autoresponder type', 'wpautoc_monetize['.$num.'][settings][ar_type]', $autoresponders, false, 'The autoresponder company to store leads', '', 'wpautoc_autoresponder' );
	$current_ar = isset( $settings->ar_type ) ? $settings->ar_type : 0;

	$lists_sel = array();
	if ( $current_ar ) {
		$lists = wpautoc_get_autoresponder_lists( $current_ar );

		if ( $lists ) {
			foreach ($lists as $list) {
				$lists_sel[] = array( 'value' => $list['id'], 'label' => $list['name']);
			}
		}
	}

	wpautoc_ifield_select( $settings, 'list', 'List', 'wpautoc_monetize['.$num.'][settings][list]', $lists_sel, false, 'The list to store the leads', '', 'wpautoc_list', 'wpautoc_list_row', (!$current_ar ||  ($current_ar ==12 )) );

	echo '</table>';

	echo '<h3>Optin Settings:</h3>';
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'email_txt', 'Email Field Text', 'wpautoc_monetize['.$num.'][settings][email_txt]', false, 'Email Field Text', 'Ex: Your Email' );

	wpautoc_ifield_checkbox( $settings, 'show_name', 'Ask for First Name', 'wpautoc_monetize['.$num.'][settings][show_name]', false, 'If checked, the optin form will display a field to ask for the visitor\'s name', '', 'wpautoc_optin_show_name' );

	$display_name_field = ( isset( $settings->show_name ) && ( $settings->show_name ) );
	wpautoc_ifield_text( $settings, 'name_txt', 'Name Field Text', 'wpautoc_monetize['.$num.'][settings][name_txt]', false, 'Name Field Text', 'Ex: Your Name', '', '', 'wpautoc_optin_name_field_row', !$display_name_field );

	wpautoc_ifield_text( $settings, 'submit_txt', 'Submit Button Text', 'wpautoc_monetize['.$num.'][settings][submit_txt]', false, 'Submit Button Text', 'Ex: Submit' );


	wpautoc_ifield_textarea( $settings, 'intro_txt', 'Intro text', 'wpautoc_monetize['.$num.'][settings][intro_txt]', false, 'Intro text', 'This text will be displayed on top of the optin form' );

	wpautoc_ifield_textarea( $settings, 'thankyou_txt', 'Thank you text', 'wpautoc_monetize['.$num.'][settings][thankyou_txt]', false, 'Thank you text', 'This text will be displayed after optin' );

	wpautoc_ifield_text( $settings, 'redirect_url', 'Redirect URL', 'wpautoc_monetize['.$num.'][settings][redirect_url]', false, 'Ex: http://www.google.com', 'Optional, the user will be redirected here after optin (leave blank for no redirect)' );


	echo '</table>';

	echo '<h3>Display:</h3>';
	echo '<table class="form-table">';

	$styles = wpautoc_get_optin_styles();
	wpautoc_ifield_select( $settings, 'style', 'Style', 'wpautoc_monetize['.$num.'][settings][style]', $styles, false, 'Optin Style', '', '' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Optin Form Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_sociallock( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'header_txt', 'Header Text', 'wpautoc_monetize['.$num.'][settings][header_txt]', false, 'Header Text', 'The header text for the box' );

	wpautoc_ifield_textarea( $settings, 'intro_txt', 'Intro Text', 'wpautoc_monetize['.$num.'][settings][intro_txt]', false, 'Intro Text', 'The intro text for the box' );

	$numbers = wpautoc_get_numbers( 1, 20 );
	wpautoc_ifield_select( $settings, 'num_pars', 'Visible Paragraphs', 'wpautoc_monetize['.$num.'][settings][num_pars]', $numbers, false, 'Number of Paragraphs Initially Visible', '', '' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}


function wpautoc_monetization_type_viralads( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	echo wpautoc_viralads_html( $settings, $num );
	echo '<table class="form-table">';

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}


function wpautoc_viralads_html( $content, $num ) {
	$str = '<div class="wpautoc-viral-ads">
		<table class="wpautoc-viral-ads-table form-table"><thead><tr><th scope="col" style="width:28%">Title</th><th scope="col" style="width:28%">Description</th><th scope="col" style="width:20%">URL</th><th scope="col">Image</th><th scope="col" style="width:6%">&nbsp;</th></tr></thead><tbody>';
	$ads = isset( $content->ads ) ? $content->ads : false;
	// var_dump($ads);
	if( $ads ) {
		$i = 0;
		foreach( $ads as $ad ) {
			$str .= wpautoc_viralads_row( $ad, $num, $i++ );
		}
	}
	$str .= '</tbody></table><br/>';
	$str .= '<button type="button" class="button button-secondary wpautoc-add-viral-ad"><i class="fa fa-add"></i> Add New Ad</button></div>';
	return $str;
}

function wpautoc_viralads_row( $ad, $num, $i ) {
	$field1_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][title]';
	$field2_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][description]';
	$field3_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][url]';
	$field4_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][image]';
	$title = $ad->title;
	$description = $ad->description;
	$url = $ad->url;
	$image = $ad->image;
	return '<tr class="wpautoc_viralads_row">
		<td><textarea style="width:100%" name="'.$field1_name.'">'.$title.'</textarea></td>
		<td><textarea style="width:100%" name="'.$field2_name.'">'.$description.'</textarea></td>
		<td><textarea style="width:100%" name="'.$field3_name.'">'.$url.'</textarea></td>
		<td class="file-upload-parent">
		<input style="display:none" class="regular-text file-upload-url" type="text" size="36" name="'.$field4_name.'" value="'.$image.'" placeholder="Select image..." />
	    <img src="'.$image.'" alt="" class="file-upload-img wpautoc-viral-img">
		<button class="button button-secondary wpautoc_img_upload" type="button"><span class="fa fa-upload"></span> Select Image</button>
		</td>
        <td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
}


function wpautoc_monetization_type_textads( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	echo wpautoc_textads_html( $settings, $num );
	echo '<table class="form-table">';

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}


function wpautoc_textads_html( $content, $num ) {
	$str = '<div class="wpautoc-text-ads">
		<table class="wpautoc-text-ads-table form-table"><thead><tr><th scope="col" style="width:28%">Title</th><th scope="col">Description</th><th scope="col" style="width:20%">URL</th><th scope="col" style="width:6%">&nbsp;</th></tr></thead><tbody>';
	$ads = isset( $content->ads ) ? $content->ads : false;
	// var_dump($ads);
	if( $ads ) {
		$i = 0;
		foreach( $ads as $ad ) {
			$str .= wpautoc_textads_row( $ad, $num, $i++ );
		}
	}
	$str .= '</tbody></table><br/>';
	$str .= '<button type="button" class="button button-secondary wpautoc-add-text-ad"><i class="fa fa-add"></i> Add New Ad</button></div>';
	return $str;
}

function wpautoc_textads_row( $ad, $num, $i ) {
	$field1_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][title]';
	$field2_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][description]';
	$field3_name = 'wpautoc_monetize['.$num.'][settings][ads]['.$i.'][url]';
	$title = $ad->title;
	$description = $ad->description;
	$url = $ad->url;
	return '<tr class="wpautoc_textads_row">
		<td><textarea style="width:100%" name="'.$field1_name.'">'.$title.'</textarea></td>
		<td><textarea style="width:100%" name="'.$field2_name.'">'.$description.'</textarea></td>
		<td><textarea style="width:100%" name="'.$field3_name.'">'.$url.'</textarea></td>
        <td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
}

function wpautoc_monetization_type_clickbank( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	echo '<table class="form-table">';
	$numbers = wpautoc_get_numbers( 1, 5 );

	$adtypes = wpautoc_get_adtypes( );
	wpautoc_ifield_select( $settings, 'ad_type', 'Ad Type', 'wpautoc_monetize['.$num.'][settings][ad_type]', $adtypes, false, 'Text or Image Ads', '', '' );

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );
	/*$cats = wpautoc_get_cb_categories();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Only show products from this category (optional)', '', '' );*/

	wpautoc_ifield_checkbox( $settings, 'popular', 'Show popular products', 'wpautoc_monetize['.$num.'][settings][popular]', false, 'If checked, and there are no results for the specific keyword, it will display the top popular products in Clickbank accross multiple niches' );

	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	// wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	// $cats = wpautoc_get_amazon_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Amazon category', '', '' );
	$numbers = wpautoc_get_numbers( 1, 12 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Clickbank Ads to display', '', '' );

	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );

	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_popup( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );

	echo '<table class="form-table">';

	wpautoc_ifield_html( $settings, 'html', 'Popup Content', 'wpautoc_monetize['.$num.'][settings][html]', false, 'Popup Content', 'Popup Content' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_ebay( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );

	$cats = wpautoc_get_ebay_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Search products only in this category', '', '' );

	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Ebay Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_walmart( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'walmart' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to display Walmart affiliate ads, you need a valid Walmart API Key and Affiliate ID.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Walmart API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );
	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	// $cats = wpautoc_get_ebay_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Search products only in this category', '', '' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_bestbuy( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'bestbuy' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to display Bestbuy affiliate ads, you need a valid Bestbuy API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Bestbuy API details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );

	// $cats = wpautoc_get_ebay_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Search products only in this category', '', '' );
	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}

function wpautoc_monetization_type_envato( $content, $num ) {
	wpautoc_monetize_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'envato' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to display Envato Products, you need a valid Envato API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-ecommerce-tab').'">Click here</a> to go to the plugin settings and enter your Envato API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';

	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_monetize['.$num.'][settings][keyword]', false, 'Keyword', 'The keyword to search' );
	wpautoc_ifield_checkbox( $settings, 'show_price', 'Display Price', 'wpautoc_monetize['.$num.'][settings][show_price]', false, 'If checked, it will show the product price in the ad' );

	wpautoc_ifield_text( $settings, 'buy_button_txt', 'Buy Button Text', 'wpautoc_monetize['.$num.'][settings][buy_button_txt]', false, 'Short Text for the Buy Button', 'Ex: Buy Now' );

	// $cats = wpautoc_get_ebay_cats();
	// wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_monetize['.$num.'][settings][category]', $cats, false, 'Search products only in this category', '', '' );

	$numbers = wpautoc_get_numbers( 1, 10 );
	wpautoc_ifield_select( $settings, 'num_ads', 'Number of Ads', 'wpautoc_monetize['.$num.'][settings][num_ads]', $numbers, false, 'Number of Ads to display', '', '' );

	$numbers = wpautoc_get_numbers( 1, 5 );
	wpautoc_ifield_select( $settings, 'ads_per_row', 'Ads per Row', 'wpautoc_monetize['.$num.'][settings][ads_per_row]', $numbers, false, 'Number of Ads per Row', '', '' );

	wpautoc_ifield_text( $settings, 'header', 'Header text', 'wpautoc_monetize['.$num.'][settings][header]', false, 'Header Text', 'A header text for the block (optional)' );


	wpautoc_ifield_bannerpos( $settings, 'position', 'Position', array( 'wpautoc_monetize['.$num.'][settings][position]', 'wpautoc_monetize['.$num.'][settings][paragraph]', 'wpautoc_monetize['.$num.'][settings][float]', 'wpautoc_monetize['.$num.'][settings][margin]' ) , array(), false, 'Banner Position' );

	echo '</table>';
	// echo 'test;';
	wpautoc_monetize_print_box_footer( $content, $num );
}
?>