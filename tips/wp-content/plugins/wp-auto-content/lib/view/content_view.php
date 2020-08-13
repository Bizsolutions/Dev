<?php
function wpautoc_print_content_elements( $campaign_id ) {
?>
	<div id="post-body" class="metabox-holder columns-1">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable" id="wpautoc-content-els">
				<?php
					if( $campaign_id ) {
						$content = wpautoc_get_content_elements( $campaign_id );
						if( $content ) {
							$i = 1;
							foreach( $content as $el ) {
								wpautoc_print_content_el( $el, $i++ );
							}
						}
					}
				?>
			</div>
		</div>
	</div>
<?php
}

function wpautoc_print_content_el( $content, $num ) {
	// var_dump($action);
	switch( $content->type ) {
		case WPAUTOC_CONTENT_YOUTUBE:
			return wpautoc_content_type_youtube( $content, $num );
			break;
		case WPAUTOC_CONTENT_VIMEO:
			return wpautoc_content_type_vimeo( $content, $num );
			break;
		case WPAUTOC_CONTENT_DAILYMOTION:
			return wpautoc_content_type_dailymotion( $content, $num );
			break;
		case WPAUTOC_CONTENT_EZA:
			return wpautoc_content_type_ezinearticles( $content, $num );
			break;
		case WPAUTOC_CONTENT_BIGCS:
			return wpautoc_content_type_bigcs( $content, $num );
			break;
		case WPAUTOC_CONTENT_BIGAS:
			return wpautoc_content_type_bigas( $content, $num );
			break;
		case WPAUTOC_CONTENT_AFORGE:
			return wpautoc_content_type_aforge( $content, $num );
			break;
		case WPAUTOC_CONTENT_ABUILDER:
			return wpautoc_content_type_abuilder( $content, $num );
			break;
		case WPAUTOC_CONTENT_WIKIPEDIA:
			return wpautoc_content_type_wikipedia( $content, $num );
			break;
		case WPAUTOC_CONTENT_RSS:
			return wpautoc_content_type_rss( $content, $num );
			break;
		case WPAUTOC_CONTENT_AMAZON:
			return wpautoc_content_type_amazon( $content, $num );
			break;
		case WPAUTOC_CONTENT_EBAY:
			return wpautoc_content_type_ebay( $content, $num );
			break;
		case WPAUTOC_CONTENT_CLICKBANK:
			return wpautoc_content_type_clickbank( $content, $num );
			break;
		case WPAUTOC_CONTENT_NEWS:
			return wpautoc_content_type_news( $content, $num );
			break;
		case WPAUTOC_CONTENT_WHIO:
			return wpautoc_content_type_whio( $content, $num );
			break;
		case WPAUTOC_CONTENT_NYT:
			return wpautoc_content_type_nyt( $content, $num );
			break;
		case WPAUTOC_CONTENT_ETSY:
			return wpautoc_content_type_etsy( $content, $num );
			break;
		case WPAUTOC_CONTENT_ALIEXPRESS:
			return wpautoc_content_type_aliexpress( $content, $num );
			break;
		case WPAUTOC_CONTENT_GEARBEST:
			return wpautoc_content_type_gearbest( $content, $num );
			break;
		case WPAUTOC_CONTENT_WALMART:
			return wpautoc_content_type_walmart( $content, $num );
			break;
		case WPAUTOC_CONTENT_BESTBUY:
			return wpautoc_content_type_bestbuy( $content, $num );
			break;
		case WPAUTOC_CONTENT_ENVATO:
			return wpautoc_content_type_envato( $content, $num );
			break;
		case WPAUTOC_CONTENT_UDEMY:
			return wpautoc_content_type_udemy( $content, $num );
			break;
		case WPAUTOC_CONTENT_TWITTER:
			return wpautoc_content_type_twitter( $content, $num );
			break;
		case WPAUTOC_CONTENT_PINTEREST:
			return wpautoc_content_type_pinterest( $content, $num );
			break;
		case WPAUTOC_CONTENT_TUMBLR:
			return wpautoc_content_type_tumblr( $content, $num );
			break;
		case WPAUTOC_CONTENT_GOOGLEPLUS:
			return wpautoc_content_type_googleplus( $content, $num );
			break;
		case WPAUTOC_CONTENT_MEDIUM:
			return wpautoc_content_type_medium( $content, $num );
			break;
		case WPAUTOC_CONTENT_FACEBOOK:
			return wpautoc_content_type_facebook( $content, $num );
			break;
		case WPAUTOC_CONTENT_GOOGLEBOOKS:
			return wpautoc_content_type_googlebooks( $content, $num );
			break;
		case WPAUTOC_CONTENT_CAREERJET:
			return wpautoc_content_type_careerjet( $content, $num );
			break;
		case WPAUTOC_CONTENT_EVENTBRITE:
			return wpautoc_content_type_eventbrite( $content, $num );
			break;
		case WPAUTOC_CONTENT_CRAIGSLIST:
			return wpautoc_content_type_craigslist( $content, $num );
			break;
		case WPAUTOC_CONTENT_YELP:
			return wpautoc_content_type_yelp( $content, $num );
			break;
		case WPAUTOC_CONTENT_NASA:
			return wpautoc_content_type_nasa( $content, $num );
			break;
		default:
			echo 'Unknown content type';
			break;
	}
}

function wpautoc_content_print_box_header( $content, $num ) {
	if( !isset( $content->id ) )
		$content->id = 0;
?>
	<div class="postbox autoc-content-box">
		<button type="button" class="handlediv wpautoc-handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Content</span><span class="toggle-indicator" aria-hidden="true"></span></button>
		<h2 class="hndle"><span><?php wpautoc_print_content_title( $content->type, $num );?></span></h2>
		<div class="inside">
			<p><?php wpautoc_print_content_description( $content->type, $num );?></p>
			<?php
				wpautoc_field_hidden( 'wpautoc_content['.$num.'][action]', ( $content->id ? 1 : 0 ), '', 'wpautoc_content_action');
				wpautoc_field_hidden( 'wpautoc_content['.$num.'][type]', $content->type );
				wpautoc_field_hidden( 'wpautoc_content['.$num.'][id]', $content->id );
			?>
<?php
}

function wpautoc_print_content_title( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_CONTENT_YOUTUBE:
			$str_ret =  '<i class="fa fa-youtube"></i> Youtube Videos';
			break;
		case WPAUTOC_CONTENT_VIMEO:
			$str_ret =  '<i class="fa fa-vimeo"></i> Vimeo Videos';
			break;
		case WPAUTOC_CONTENT_DAILYMOTION:
			$str_ret =  '<i class="fa fa-dailymotion"></i> Dailymotion Videos';
			break;
		case WPAUTOC_CONTENT_EZA:
			$str_ret =  '<i class="fa fa-file-text-o"></i> Ezine Articles';
			break;
		case WPAUTOC_CONTENT_BIGCS:
			$str_ret =  '<i class="fa fa-file-qrcode"></i> Big Content Search Articles';
			break;
		case WPAUTOC_CONTENT_BIGAS:
			$str_ret =  '<i class="fa fa-file-qrcode"></i> Big Article Scraper Articles';
			break;
		case WPAUTOC_CONTENT_AFORGE:
			$str_ret =  '<i class="fa fa-file-qrcode"></i> ArticleForge Articles';
			break;
		case WPAUTOC_CONTENT_ABUILDER:
			$str_ret =  '<i class="fa fa-file-qrcode"></i> ArticleBuilder Articles';
			break;
		case WPAUTOC_CONTENT_WIKIPEDIA:
			$str_ret =  '<i class="fa fa-wikipedia-w"></i> Wikipedia Articles';
			break;
		case WPAUTOC_CONTENT_RSS:
			$str_ret =  '<i class="fa fa-rss"></i> RSS';
			break;
		case WPAUTOC_CONTENT_AMAZON:
			$str_ret =  '<i class="fa fa-amazon"></i> Amazon products';
			break;
		case WPAUTOC_CONTENT_EBAY:
			$str_ret =  '<i class="fa fa-ebay"></i> eBay products';
			break;
		case WPAUTOC_CONTENT_CLICKBANK:
			$str_ret =  '<i class="fa fa-bell-o"></i> Clickbank Products';
			break;
		case WPAUTOC_CONTENT_NEWS:
			$str_ret =  '<i class="fa fa-newspaper-o"></i> News Headlines from NewsApi.org';
			break;
		case WPAUTOC_CONTENT_WHIO:
			$str_ret =  '<i class="fa fa-newspaper-o"></i> News Items from Webhose.io';
			break;
		case WPAUTOC_CONTENT_NYT:
			$str_ret =  '<i class="fa fa-newspaper-o"></i> News Headlines from NYT';
			break;
		case WPAUTOC_CONTENT_ETSY:
			$str_ret =  '<i class="fa fa-etsy"></i> Etsy Products';
			break;
		case WPAUTOC_CONTENT_ALIEXPRESS:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Aliexpress Products';
			break;
		case WPAUTOC_CONTENT_GEARBEST:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Gearbest Products';
			break;
		case WPAUTOC_CONTENT_WALMART:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Walmart Products';
			break;
		case WPAUTOC_CONTENT_BESTBUY:
			$str_ret =  '<i class="fa fa-shopping-cart"></i> Bestbuy Products';
			break;
		case WPAUTOC_CONTENT_ENVATO:
			$str_ret =  '<i class="fa fa-leaf"></i> Envato Products';
			break;
		case WPAUTOC_CONTENT_UDEMY:
			$str_ret =  '<i class="fa fa-book"></i> Udemy Courses';
			break;
		case WPAUTOC_CONTENT_TWITTER:
			$str_ret =  '<i class="fa fa-twitter"></i> Twitter Tweets';
			break;
		case WPAUTOC_CONTENT_PINTEREST:
			$str_ret =  '<i class="fa fa-pinterest"></i> Pinterest Pins';
			break;
		case WPAUTOC_CONTENT_TUMBLR:
			$str_ret =  '<i class="fa fa-tumblr"></i> Tumblr Posts';
			break;
		case WPAUTOC_CONTENT_GOOGLEPLUS:
			$str_ret =  '<i class="fa fa-google-plus"></i> Google+ Posts';
			break;
		case WPAUTOC_CONTENT_MEDIUM:
			$str_ret =  '<i class="fa fa-medium"></i> Medium Posts';
			break;
		case WPAUTOC_CONTENT_FACEBOOK:
			$str_ret =  '<i class="fa fa-facebook"></i> Facebook Posts';
			break;
		case WPAUTOC_CONTENT_GOOGLEBOOKS:
			$str_ret =  '<i class="fa fa-book"></i> Google Books';
			break;
		case WPAUTOC_CONTENT_CAREERJET:
			$str_ret =  '<i class="fa fa-location-arrow"></i> Careerjet Jobs';
			break;
		case WPAUTOC_CONTENT_EVENTBRITE:
			$str_ret =  '<i class="fa fa-calendar-check-o"></i> Eventbrite Events';
			break;
		case WPAUTOC_CONTENT_CRAIGSLIST:
			$str_ret =  '<i class="fa fa-globe"></i> Craigslist Posts';
			break;
		case WPAUTOC_CONTENT_YELP:
			$str_ret =  '<i class="fa fa-yelp"></i> Yelp Reviews';
			break;
		case WPAUTOC_CONTENT_NASA:
			$str_ret =  '<i class="fa fa-rocket"></i> NASA Articles';
			break;
		default:
			echo 'Unknown content type';
			break;
	}
	echo $num.'. '.$str_ret;
}

function wpautoc_print_content_description( $type, $num ) {
	$str_ret = '';
	switch( $type ) {
		case WPAUTOC_CONTENT_YOUTUBE:
			$str_ret =  'Import Youtube Videos automatically (from keywords, channels or trends)';
			break;
		case WPAUTOC_CONTENT_VIMEO:
			$str_ret =  'Import Vimeo Videos automatically';
			break;
		case WPAUTOC_CONTENT_DAILYMOTION:
			$str_ret =  'Import Dailymotion Videos automatically';
			break;
		case WPAUTOC_CONTENT_EZA:
			$str_ret =  'Import Articles from EzineArticles';
			break;
		case WPAUTOC_CONTENT_BIGCS:
			$str_ret =  'Import Articles from <a href="https://wpautocontent.com/support/bigcontentsearch" target="_blank">BigContentSearch.com</a>';
			break;
		case WPAUTOC_CONTENT_BIGAS:
			$str_ret =  'Import Articles from <a href="https://wpautocontent.com/support/bigarticlescraper" target="_blank">BigArticleScraper.com</a>';
			break;
		case WPAUTOC_CONTENT_AFORGE:
			$str_ret =  'Import Articles from <a href="https://wpautocontent.com/support/articleforge" target="_blank">ArticleForge.com</a>';
			break;
		case WPAUTOC_CONTENT_ABUILDER:
			$str_ret =  'Import Articles from <a href="https://wpautocontent.com/support/articlebuilder" target="_blank">ArticleBuilder.net</a>';
			break;
		case WPAUTOC_CONTENT_WIKIPEDIA:
			$str_ret =  'Import Articles from Wikipedia';
			break;
		case WPAUTOC_CONTENT_RSS:
			$str_ret =  'Import Articles from an RSS feed';
			break;
		case WPAUTOC_CONTENT_AMAZON:
			$str_ret =  'Import Products from Amazon.com';
			break;
		case WPAUTOC_CONTENT_EBAY:
			$str_ret =  'Import eBay Products';
			break;
		case WPAUTOC_CONTENT_CLICKBANK:
			$str_ret =  'Import Clickbank Products';
			break;
		case WPAUTOC_CONTENT_NEWS:
			$str_ret =  'Import Recent News from your desired topics';
			break;
		case WPAUTOC_CONTENT_WHIO:
			$str_ret =  'Import Recent News from your desired topics using the webhose.io API';
			break;
		case WPAUTOC_CONTENT_NYT:
			$str_ret =  'Import Recent News from your desired topics from The New York Times';
			break;
		case WPAUTOC_CONTENT_ETSY:
			$str_ret =  'Import Etsy Products';
			break;
		case WPAUTOC_CONTENT_ALIEXPRESS:
			$str_ret =  'Import Aliexpress Products';
			break;
		case WPAUTOC_CONTENT_GEARBEST:
			$str_ret =  'Import Gearbest Products';
			break;
		case WPAUTOC_CONTENT_WALMART:
			$str_ret =  'Import Walmart Products';
			break;
		case WPAUTOC_CONTENT_BESTBUY:
			$str_ret =  'Import Bestbuy Products';
			break;
		case WPAUTOC_CONTENT_ENVATO:
			$str_ret =  'Import Envato Products';
			break;
		case WPAUTOC_CONTENT_UDEMY:
			$str_ret =  'Import Udemy Courses';
			break;
		case WPAUTOC_CONTENT_TWITTER:
			$str_ret =  'Import Twitter Tweets';
			break;
		case WPAUTOC_CONTENT_PINTEREST:
			$str_ret =  'Import Pinterest Pins';
			break;
		case WPAUTOC_CONTENT_TUMBLR:
			$str_ret =  'Import Tumblr Blog Posts';
			break;
		case WPAUTOC_CONTENT_GOOGLEPLUS:
			$str_ret =  'Import Google+ Posts';
			break;
		case WPAUTOC_CONTENT_MEDIUM:
			$str_ret =  'Import Medium Posts';
			break;
		case WPAUTOC_CONTENT_FACEBOOK:
			$str_ret =  'Import Facebook Posts from a Public Group or Page';
			break;
		case WPAUTOC_CONTENT_GOOGLEBOOKS:
			$str_ret =  'Import Books From Google Books';
			break;
		case WPAUTOC_CONTENT_CAREERJET:
			$str_ret =  'Import Jobs from Careerjet';
			break;
		case WPAUTOC_CONTENT_EVENTBRITE:
			$str_ret =  'Import Events from Eventbrite';
			break;
		case WPAUTOC_CONTENT_CRAIGSLIST:
			$str_ret =  'Import Posts from Craigslist';
			break;
		case WPAUTOC_CONTENT_YELP:
			$str_ret =  'Import Reviews from Yelp';
			break;
		case WPAUTOC_CONTENT_NASA:
			$str_ret =  'Import content from nasa.gov';
			break;
		default:
			echo 'Unknown content type';
			break;
	}
	echo $str_ret;
}

function wpautoc_content_print_box_footer( $content, $num = 0 ) {
?>
	</div>
	<div class="autoc-footer">
		<div class="autoc-footer-actions">
			<button class="button button-secondary btn-delete-content" type="button"><i class="fa fa-remove"></i> Delete</button>
			<!-- <button class="button button-secondary btn-disable-content" type="button">Disable</button> -->
		</div>
		<div class="clearboth"></div>
	</div>
		</div>
<?php
}



function wpautoc_content_popup() {
?>
<div id="autoc-content-modal" style="display:none">
	<h3 style="text-align:center">Add Content Source</h3>
	<span>Content type:</span> <?php wpautoc_content_types_select(); ?>
	<br/>
	<br/>
	<button type="button" id="autoc-do-add-content" class="button button-primary">Add Content Source</button>
	<a class="button button-secondary" href="#close" rel="acmodal:close">Cancel</a>
</div>
<?php
}

function wpautoc_add_content_block_ajax() {
	$content = new stdclass();
	$content->type = isset( $_POST[ 'type' ] ) ? intval( $_POST[ 'type' ] ) : 0;
	$num = isset( $_POST[ 'num' ] ) ? intval( $_POST[ 'num' ] ) : 1;
	wpautoc_print_content_el( $content, $num );
	exit();
}

function wpautoc_content_types_select( $val = 0 ) {
	$types = wpautoc_get_content_types();
	$str = '<select id="wpautoc-content-types"><option value="0">Select...</option>';
	foreach( $types as $type ) {
		if ($type['name'] === 'optgroup') {
			$str .= '<optgroup label="'.$type['label'].'">';
		}   else if (/*$value['value'] &&*/ $type['name'] === 'optgroupclose') {
			$str .= '</optgroup>';
	    } else {
/*	    	if( isset( $type['pro'] ) )
				$str .= '<option value="'.$type['id'].'" disabled="disabled">'.$type['name'].' (Pro Only)</option>';
	    	else*/
				$str .= '<option value="'.$type['id'].'">'.$type['name'].'</option>';
		}
	}
	$str .= '</select>';
	echo $str;
}


/* Content Types */

function wpautoc_content_type_youtube( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	if( isset( $content->settings ) )
		$settings = json_decode( $content->settings );
	else
		$settings = new stdclass();
	$is_api_valid = wpautoc_is_api_valid( 'youtube' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Youtube videos, you need a valid Youtube API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	$video_type = isset( $settings->video_type ) ? $settings->video_type : 1;
	wpautoc_youtube_field_typeselector( 'Type', $content, $num, $video_type );
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in youtube', '', '', 'autoc_youtube_keyword', !( !$video_type || $video_type == 1 ) );

	wpautoc_ifield_text( $settings, 'channel', 'Channel ID', 'wpautoc_content['.$num.'][settings][channel]', false, 'Youtube Channel ID', 'Enter the channel ID from Youtube', '', '', 'autoc_youtube_channel', !( $video_type == 2 ) );

	$countries = wpautoc_yt_countries();
	wpautoc_ifield_select( $settings, 'country', 'Country', 'wpautoc_content['.$num.'][settings][country]', $countries, false, '', '', '', 'autoc_youtube_country', !( $video_type == 3 ), false );


	wpautoc_ifield_checkbox( $settings, 'fetch_desc', 'Fetch Video Description', 'wpautoc_content['.$num.'][settings][fetch_desc]', false, 'If checked, it will also download the video description and insert it into the post', '', 'autoc_youtube_description' );

	$hidden = !( isset( $settings->fetch_desc ) && $settings->fetch_desc );
	wpautoc_ifield_checkbox( $settings, 'spin', 'Spin Text Description', 'wpautoc_content['.$num.'][settings][spin]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt(), '', '', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Description', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the video description', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'thumbnail', 'Download Thumbnail as featured image', 'wpautoc_content['.$num.'][settings][thumbnail]', false, 'If checked, the plugin will download the featured image from Youtube and set it as post featured image' );

	wpautoc_ifield_checkbox( $settings, 'video_tags', 'Update Video Tags', 'wpautoc_content['.$num.'][settings][video_tags]', false, 'If checked, the plugin will fetch the video tags and set them as post tags' );

	// TO-DO, download comments, published before/after, quality, duración

/*	wpautoc_field_text( 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', $settings->keyword, false, '', 'Enter one or more keywords to search' );
	wpautoc_field_text( 'Channel ID', 'wpautoc_content['.$num.'][settings][channel]', $settings->channel, false, '', 'Enter the channel ID' );*/
	echo '</table>';
	// echo 'test;';
	wpautoc_content_print_box_footer( $content, $num );
}


function wpautoc_youtube_field_typeselector( $label, $content, $num, $val ) {
	?>
	    <tr valign="top">
	        <th scope="row"><?php echo $label;?></th>
	        <td>
				<label>
					<input type="radio" name="wpautoc_content[<?php echo $num;?>][settings][video_type]" value="1" class="autoc_youtube_video_type" <?php checked( (!$val || $val == '1' ) );?>/>
					<span>Keyword</span>
				</label>
				 &nbsp; 
				<label>
					<input type="radio" name="wpautoc_content[<?php echo $num;?>][settings][video_type]" value="2" class="autoc_youtube_video_type" <?php checked( ( $val == '2' ) );?>/>
					<span>Channel</span>
				</label>
				 &nbsp; 
				<label>
					<input type="radio" name="wpautoc_content[<?php echo $num;?>][settings][video_type]" value="3" class="autoc_youtube_video_type" <?php checked( ( $val == '3' ) );?>/>
					<span>Trends</span>
				</label>
	        </td>
	    </tr>
	<?php
}


function wpautoc_content_type_vimeo( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'vimeo' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Vimeo videos, you need a valid Vimeo Application ID.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in youtube' );

	wpautoc_ifield_checkbox( $settings, 'fetch_desc', 'Fetch Video Description', 'wpautoc_content['.$num.'][settings][fetch_desc]', false, 'If checked, it will also download the video description and insert it into the post', '', 'autoc_youtube_description' );

	$hidden = !( isset( $settings->fetch_desc ) && $settings->fetch_desc );
	wpautoc_ifield_checkbox( $settings, 'spin', 'Spin Text Description', 'wpautoc_content['.$num.'][settings][spin]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt(), '', '', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Description', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the video description', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'thumbnail', 'Download Thumbnail as featured image', 'wpautoc_content['.$num.'][settings][thumbnail]', false, 'If checked, the plugin will download the featured image from Youtube and set it as post featured image' );

	wpautoc_ifield_checkbox( $settings, 'video_tags', 'Update Video Tags', 'wpautoc_content['.$num.'][settings][video_tags]', false, 'If checked, the plugin will fetch the video tags and set them as post tags' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_content_type_dailymotion( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in youtube' );

	wpautoc_ifield_checkbox( $settings, 'fetch_desc', 'Fetch Video Description', 'wpautoc_content['.$num.'][settings][fetch_desc]', false, 'If checked, it will also download the video description and insert it into the post', '', 'autoc_youtube_description' );

	$hidden = !( isset( $settings->fetch_desc ) && $settings->fetch_desc );
	wpautoc_ifield_checkbox( $settings, 'spin', 'Spin Text Description', 'wpautoc_content['.$num.'][settings][spin]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt(), '', '', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Description', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the video description', 'autoc_youtube_descsub', $hidden );

	wpautoc_ifield_checkbox( $settings, 'thumbnail', 'Download Thumbnail as featured image', 'wpautoc_content['.$num.'][settings][thumbnail]', false, 'If checked, the plugin will download the featured image from Youtube and set it as post featured image' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}




function wpautoc_content_type_rss( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'url', 'URL', 'wpautoc_content['.$num.'][settings][url]', false, 'Feed URL', 'Enter the URL of the rss feed (for example, http://blogsite.com/feed' );

	wpautoc_ifield_checkbox( $settings, 'spin_title', 'Spin Title', 'wpautoc_content['.$num.'][settings][spin_title]', false, 'If checked, it will spin the title  <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'spin', 'Spin Content', 'wpautoc_content['.$num.'][settings][spin]', false, 'If checked, it will spin the article content  <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Content', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the original post content' );

	wpautoc_ifield_checkbox( $settings, 'add_url', 'Add Link to original source', 'wpautoc_content['.$num.'][settings][add_url]', false, 'If checked, it will add a link to the original source at the bottom of the article' );


	echo '</table>';
	// echo 'test;';
	wpautoc_content_print_box_footer( $content, $num );
}



?>