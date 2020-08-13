<?php
/* Cron Job */
include_once WPAUTOC_DIR.'/lib/content/youtube.php';
include WPAUTOC_DIR.'/lib/content/vimeo.php';
include WPAUTOC_DIR.'/lib/content/dailymotion.php';
include WPAUTOC_DIR.'/lib/content/ezinearticles.php';
include WPAUTOC_DIR.'/lib/content/wikipedia.php';
include WPAUTOC_DIR.'/lib/content/rss.php';
include WPAUTOC_DIR.'/lib/content/amazon.php';
include WPAUTOC_DIR.'/lib/content/ebay.php';
include WPAUTOC_DIR.'/lib/content/clickbank.php';
include WPAUTOC_DIR.'/lib/content/news.php';
include WPAUTOC_DIR.'/lib/content/webhose.php';
include WPAUTOC_DIR.'/lib/content/nyt.php';
include WPAUTOC_DIR.'/lib/content/aliexpress.php';
include WPAUTOC_DIR.'/lib/content/gearbest.php';
include WPAUTOC_DIR.'/lib/content/walmart.php';
include WPAUTOC_DIR.'/lib/content/bestbuy.php';
include WPAUTOC_DIR.'/lib/content/etsy.php';
include WPAUTOC_DIR.'/lib/content/envato.php';
include WPAUTOC_DIR.'/lib/content/udemy.php';
include WPAUTOC_DIR.'/lib/content/twitter.php';
include WPAUTOC_DIR.'/lib/content/pinterest.php';
include WPAUTOC_DIR.'/lib/content/tumblr.php';
include WPAUTOC_DIR.'/lib/content/googleplus.php';
include WPAUTOC_DIR.'/lib/content/medium.php';
include WPAUTOC_DIR.'/lib/content/facebook.php';
include WPAUTOC_DIR.'/lib/content/googlebooks.php';
include WPAUTOC_DIR.'/lib/content/careerjet.php';
include WPAUTOC_DIR.'/lib/content/eventbrite.php';
include WPAUTOC_DIR.'/lib/content/craigslist.php';
include WPAUTOC_DIR.'/lib/content/yelp.php';
include WPAUTOC_DIR.'/lib/content/nasa.php';
include WPAUTOC_DIR.'/lib/content/bigcs.php';
include WPAUTOC_DIR.'/lib/content/bigas.php';
include WPAUTOC_DIR.'/lib/content/articleforge.php';
include WPAUTOC_DIR.'/lib/content/articlebuilder.php';
include WPAUTOC_DIR.'/lib/content/pixabay.php';
include WPAUTOC_DIR.'/lib/content/flickr.php';

add_filter( 'cron_schedules', 'wpautoc_add_cron_interval' );

function wpautoc_add_cron_interval( $schedules ) {
    $schedules['four_hourzz'] = array(
        'interval' => 60 * 60 * 4,
        'display'  => esc_html__( 'Every 4 hours' ),
    );

    return $schedules;
}

add_action( 'init', 'wpautoc_cron_activation');
add_action( 'wpautoc_campaign_importing', 'wpautoc_maybe_import' );

function wpautoc_cron_activation() {
    if (! wp_next_scheduled ( 'wpautoc_campaign_importing' )) {
		wp_schedule_event( time(), 'four_hourzz', 'wpautoc_campaign_importing' );
    }
}

// add_action('adminx_footer', 'wpautoc_maybe_import');

function wpautoc_maybe_import() {
	/*$file = fopen("d:/archivo.txt", "w");
	fwrite($file, "Esto es una nueva linea de texto" . PHP_EOL);
	fwrite($file, "Otra más" . PHP_EOL);
	fclose($file);
	// do something every day
	// include VIDPROFITS_DIR.'/lib/youtube/Youtube.php';
	wp_mail( 'raulmellado@gmail.com', 'Automatic email', 'Automatic scheduled email from WordPress.');*/
	// die('cron called');
// return;
	@set_time_limit ( 200 );
	@ini_set( 'max_execution_time', 200 );
	if( wpautoc_has_already_imported() && ! (defined( 'WPAUTOC_FORCE_IMPORT' ) ) )
		return false;


	$array_src = array();
	$campaigns = wpautoc_get_campaigns( 0, 50 );
	foreach( $campaigns as $campaign ) {
		if( defined( 'WPAUTOC_ONLY_CAMPAIGN' ) && ( $campaign->id != WPAUTOC_ONLY_CAMPAIGN ) )
			continue;

		if( defined( 'WPAUTOC_CRON_DEBUG' ) ) {
			echo '<h2>WP Auto Content, executing Campaign '.$campaign->name.'</h2>';
		}

		if( $campaign->status /*TO-DO, si hemos superado fecha limite */ ) {
			try {
				$num_imported = wpautoc_do_process_campaign( $campaign );
			} catch( Exception $e ) {
				if( defined( 'WPAUTOC_CRON_DEBUG' ) ) {
					echo $e->getMessage();
				}
				$num_imported = 0;
			}

			/*if( $num_imported  )
				wpautoc_increase_campaign_imported( $campaign->id, $num_imported );*/

			$array_src[] = array(
				'campaign_id' => intval( $campaign->id ),
				'vids_imported' => ( $num_imported ? $num_imported : 0 ),
				'last_imported' => date('Y-m-d H:i:s')
			);
		if( defined( 'WPAUTOC_CRON_DEBUG' ) && !$num_imported ) {
			global $eza_shown;
			if( !$eza_shown )
				echo '<p>0 posts imported for this campaign. If you are having issues importing content with the plugin, please <a href="https://wpautocontent.com/support/faq/most-common-issues-with-the-plugin/wp-auto-content-is-not-importing-any-posts-to-my-website/" target="_blank">check this guide</a> for more help.</p>';
			}
		}
	}
//	if ( ! defined( 'WPAUTOC_CRON_DEBUG' ) /*&& WPAUTOC_DO_IMPORT*/ )
		wpautoc_update_import_settings( $array_src );
	/*else
		var_dump( $array_src );*/
	// $option = get_option( 'wpac_cron_settings');
	// var_dump($option);

}

function wpautoc_do_process_campaign( $campaign ) {
	$num_posts = $campaign->per_day;
	$contents = wpautoc_get_content_elements( $campaign->id );
	if( !$contents || empty( $contents ) )
		return 0;
	$num_els = count( $contents );
	$per_el = ceil( $num_posts / $num_els );
	// TO-DO algoritmo inteligente randomizador, también ojo por si tuviera que dar una segunda pasada!
	shuffle( $contents );
	$imported = 0;
	foreach( $contents as $content ) {
		$remaining = ( $num_posts - $imported );
		$to_import = min( $remaining, $per_el );
		$imported += wpautoc_process_content_import( $campaign->id, $content, $to_import );
	}
	return $imported;
}

function wpautoc_process_content_import( $campaign_id, $content, $num_posts ) {
	$settings = json_decode( $content->settings );
	$imported = 0;
	switch( $content->type ) {
		case WPAUTOC_CONTENT_YOUTUBE:
			$imported = wpautoc_process_content_import_youtube( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_VIMEO:
			$imported = wpautoc_process_content_import_vimeo( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_DAILYMOTION:
			$imported = wpautoc_process_content_import_dailymotion( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_EZA:
			$imported = wpautoc_process_content_import_ezinearticles( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_BIGCS:
			$imported = wpautoc_process_content_import_bigcs( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_AFORGE:
			$imported = wpautoc_process_content_import_aforge( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_ABUILDER:
			$imported = wpautoc_process_content_import_abuilder( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_BIGAS:
			$imported = wpautoc_process_content_import_bigas( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_WIKIPEDIA:
			$imported = wpautoc_process_content_import_wikipedia( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_RSS:
			$imported = wpautoc_process_content_import_rss( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_AMAZON:
			$imported = wpautoc_process_content_import_amazon( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_EBAY:
			$imported = wpautoc_process_content_import_ebay( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_CLICKBANK:
			$imported = wpautoc_process_content_import_clickbank( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_NEWS:
			$imported = wpautoc_process_content_import_news( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_WHIO:
			$imported = wpautoc_process_content_import_whio( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_NYT:
			$imported = wpautoc_process_content_import_nyt( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_ETSY:
			$imported = wpautoc_process_content_import_etsy( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_ALIEXPRESS:
			$imported = wpautoc_process_content_import_aliexpress( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_GEARBEST:
			$imported = wpautoc_process_content_import_gearbest( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_WALMART:
			$imported = wpautoc_process_content_import_walmart( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_BESTBUY:
			$imported = wpautoc_process_content_import_bestbuy( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_ENVATO:
			$imported = wpautoc_process_content_import_envato( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_UDEMY:
			$imported = wpautoc_process_content_import_udemy( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_TWITTER:
			$imported = wpautoc_process_content_import_twitter( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_PINTEREST:
			$imported = wpautoc_process_content_import_pinterest( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_TUMBLR:
			$imported = wpautoc_process_content_import_tumblr( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_GOOGLEPLUS:
			$imported = wpautoc_process_content_import_googleplus( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_MEDIUM:
			$imported = wpautoc_process_content_import_medium( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_FACEBOOK:
			$imported = wpautoc_process_content_import_facebook( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_GOOGLEBOOKS:
			$imported = wpautoc_process_content_import_googlebooks( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_CAREERJET:
			$imported = wpautoc_process_content_import_careerjet( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_EVENTBRITE:
			$imported = wpautoc_process_content_import_eventbrite( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_CRAIGSLIST:
			$imported = wpautoc_process_content_import_craigslist( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_YELP:
			$imported = wpautoc_process_content_import_yelp( $campaign_id, $settings, $num_posts );
			break;
		case WPAUTOC_CONTENT_NASA:
			$imported = wpautoc_process_content_import_nasa( $campaign_id, $settings, $num_posts );
			break;
		default:
			break;
	}

	return $imported;
}

function wpautoc_cron_deactivation() {
	wp_clear_scheduled_hook('wpautoc_campaign_importing');
}

/*
	Cron Data
	Last Exec
	Sources Exec

*/

function wpautoc_has_already_imported() {
	// $today = date('Y-m-d H:i:s');
	$last_import = wpautoc_get_last_imported_date();
	if( !$last_import )
		return false;
	$last_d = strtotime( $last_import );
	$today_dates = array( date( 'd' ), date( 'm' ) );
	$last_dates = array( date( 'd', $last_d ), date( 'm', $last_d ) );
	if( ( $today_dates[0] != $last_dates[0] ) || ( $today_dates[1] != $last_dates[1] ) )
		return false;
	return true;
}

function wpautoc_get_last_imported_date() {
	$settings = wpautoc_get_import_settings();
	if( $settings && isset( $settings['last_update'] ) )
		return $settings['last_update'];
	return false;
}

function wpautoc_get_import_settings() {
	return get_option( 'wpac_cron_settings' );
}

function wpautoc_update_import_settings( $sources ) {
	$date = date('Y-m-d H:i:s');
	// var_dump($date);
	$settings = array(
		'last_update' => $date,
		'sources_exec' => $sources
	);
	update_option( 'wpac_cron_settings', $settings );
}

function wpautoc_nospinner_txt() {
	$sr_settings = wpautoc_get_settings( array( 'spinners', 'spinrewriter' ) );
	$tbs_settings = wpautoc_get_settings( array( 'spinners', 'tbs' ) );
	$tbs_settings = wpautoc_get_settings( array( 'spinners', 'wordai' ) );
	$tbs_settings = wpautoc_get_settings( array( 'spinners', 'schief' ) );

	// var_dump($settings);
	if ( ( ! isset( $tbs_settings['username'] ) || empty( $tbs_settings['username'] ) || ! isset( $tbs_settings['password'] ) || empty( $tbs_settings['password'] ) ) && ( ! isset( $sr_settings['email'] ) || empty( $sr_settings['email'] ) || ! isset( $sr_settings['apikey'] ) || empty( $sr_settings['apikey'] ) ) )
		return '<br/>NOTE: To be able to spin the content you need to enter your <a href="https://wpautocontent.com/support/spinrewriter" target="_blank">Spin Rewriter</a> / <a href="https://wpautocontent.com/support/bestspinner" target="_blank">The Best Spinner </a> / <a href="https://wpautocontent.com/support/spinnerchief" target="_blank">SpinnerChief </a> / <a href="https://wpautocontent.com/support/wordai" target="_blank">WordAI </a> / account settings under Settings > Spinners';
	return '';
}

function wpautoc_spin_text( $text, $spinner = 1 ) {
	$spinners_settings = wpautoc_get_settings( array( 'spinners' ) );
	// $spinners_settings = isset( $settings['spinners'] ) ? $settings['spinners'] : false;
	$spinner = isset( $spinners_settings['spinner'] ) ? trim( $spinners_settings['spinner'] ) : 1;
// die('sss');
	if( $spinner == 2 )
		return wpautoc_spin_text_tbs( $text );
	else if( $spinner == 3 )
		return wpautoc_spin_text_wordai( $text );
	else if( $spinner == 4 )
		return wpautoc_spin_text_schief( $text );
	return wpautoc_spin_text_srewiter( $text );
}

function wpautoc_spin_text_srewiter( $text ) {
	require_once WPAUTOC_DIR.'/lib/libs/SpinRewriterAPI.php';

	$sr_settings = wpautoc_get_settings( array( 'spinners', 'spinrewriter' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['email'] ) || empty( $sr_settings['email'] ) || ! isset( $sr_settings['apikey'] ) || empty( $sr_settings['apikey'] ) )
		return $text;
// die('aa');
/*	$email_address = "raulmellado@gmail.com";			// your Spin Rewriter email address goes here
	$api_key = "e53de7e#d903feb_2d3542b?e5acb71";	// your unique Spin Rewriter API key goes here
*/
	// Include the Spin Rewriter API SDK.

	// Authenticate yourself.
	$spinrewriter_api = new SpinRewriterAPI( $sr_settings['email'], $sr_settings['apikey'] );

	/*
	 * (optional) Set a list of protected terms.
	 * You can use one of the following formats:
	 * - protected terms are separated by the '\n' (newline) character
	 * - protected terms are separated by commas (comma-separated list)
	 * - protected terms are stored in a PHP array
	 */
	// $protected_terms = "John, Douglas Adams, then";
	// $spinrewriter_api->setProtectedTerms($protected_terms);

	// (optional) Set whether the One-Click Rewrite process automatically protects Capitalized Words outside the article's title.
	$spinrewriter_api->setAutoProtectedTerms(true);

	// (optional) Set the confidence level of the One-Click Rewrite process.
	$spinrewriter_api->setConfidenceLevel("medium");

	// (optional) Set whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not.
	$spinrewriter_api->setNestedSpintax(true);

	// (optional) Set whether Spin Rewriter rewrites complete sentences on its own.
	$spinrewriter_api->setAutoSentences(true);

	// (optional) Set whether Spin Rewriter rewrites entire paragraphs on its own.
	$spinrewriter_api->setAutoParagraphs(true);

	// (optional) Set whether Spin Rewriter writes additional paragraphs on its own.
	$spinrewriter_api->setAutoNewParagraphs(false);

	// (optional) Set whether Spin Rewriter changes the entire structure of phrases and sentences.
	$spinrewriter_api->setAutoSentenceTrees(true);

	// (optional) Sets whether Spin Rewriter should only use synonyms (where available) when generating spun text.
	$spinrewriter_api->setUseOnlySynonyms(false);

	// (optional) Sets whether Spin Rewriter should intelligently randomize the order of paragraphs and lists when generating spun text.
	$spinrewriter_api->setReorderParagraphs(false);

	// Make the actual API request and save the response as a native PHP array.
	// $text = "John will book a room. Then he will read a book by Douglas Adams.";
	$api_response = $spinrewriter_api->getUniqueVariation($text);
// var_dump($api_response);

	if( isset( $api_response ['status']) && $api_response ['status'] == 'OK' )
		return $api_response['response'];
	else
		return $text;
}

function wpautoc_spin_text_wordai( $text ) {
	$sr_settings = wpautoc_get_settings( array( 'spinners', 'wordai' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['username'] ) || empty( $sr_settings['username'] ) || ! isset( $sr_settings['password'] ) || empty( $sr_settings['password'] ) )
		return $text;

	//'Regular', 'Unique', 'Very Unique', 'Readable', or 'Very Readable

	$quality = 'Readable';
	$email = $sr_settings['username'];
	$pass = $sr_settings['password'];
	$text = urlencode($text);

     $ch = curl_init('http://wordai.com/users/turing-api.php');

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt ($ch, CURLOPT_POST, 1);
     curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json&returnspin=true&sentence=on&paragraph=on");
     curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
     $result = curl_exec($ch);

     curl_close ($ch);
	// var_dump($result);
	$res = json_decode( $result );
	// var_dump($res);
	if( isset( $res->status ) && $res->status == 'Success' )
		return $res->text;
	return $text;
}

function wpautoc_spin_text_schief( $text ) {
	$sr_settings = wpautoc_get_settings( array( 'spinners', 'schief' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['username'] ) || empty( $sr_settings['username'] ) || ! isset( $sr_settings['password'] ) || empty( $sr_settings['password'] ) )
		return $text;

	$quality = 'Readable';
	$email = $sr_settings['username'];
	$pass = $sr_settings['password'];
	// $text = urlencode($text);

     $ch = curl_init("http://api.spinnerchief.com:443/apikey=72f539e6f62b411a9&username=$email&password=$pass&protecthtml=1&spintype=1");

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt ($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $text);
     curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
     $result = curl_exec($ch);

     curl_close ($ch);
     if( $result && !empty( $result ) )
     	return $result;
	// var_dump($result);
	// $res = base64_decode( $result );
	// var_dump($res);
	/*if( isset( $res->status ) && $res->status == 'Success' )
		return $res->text;*/
	return $text;
}

function wpautoc_tbs_curl_post($url, $data, &$info){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, wpautoc_tbs_curl_postData($data));
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  $html = trim(curl_exec($ch));
  curl_close($ch);

  return $html;
}

function wpautoc_tbs_curl_postData($data){

  $fdata = "";
  foreach($data as $key => $val){
    $fdata .= "$key=" . urlencode($val) . "&";
  }

  return $fdata;

}

function wpautoc_spin_text_tbs( $text ) {

	$tbs_settings = wpautoc_get_settings( array( 'spinners', 'tbs' ) );
// var_dump($tbs_settings);
	// var_dump($settings);
	if ( ! isset( $tbs_settings['username'] ) || empty( $tbs_settings['username'] ) || ! isset( $tbs_settings['password'] ) || empty( $tbs_settings['password'] ) )
		return $text;

	// if( empty( $email_address ) || empty( $api_key ) )
	// 	return $text;

	$url = 'http://thebestspinner.com/api.php';

	#$testmethod = 'identifySynonyms';
	$testmethod = 'replaceEveryonesFavorites';


	# Build the data array for authenticating.

	$data = array();
	$data['action'] = 'authenticate';
	$data['format'] = 'php'; # You can also specify 'xml' as the format.

	# The user credentials should change for each UAW user with a TBS account.

	$data['username'] = $tbs_settings['username'];
	$data['password'] = $tbs_settings['password'];

	# Authenticate and get back the session id.
	# You only need to authenticate once per session.
	# A session is good for 24 hours.
	$output = unserialize(wpautoc_tbs_curl_post($url, $data, $info));

	if($output['success']=='true'){
	  # Success.
	  $session = $output['session'];
	  
	  # Build the data array for the example.
	  $data = array();
	  $data['session'] = $session;
	  $data['format'] = 'php'; # You can also specify 'xml' as the format.
	  $data['text'] = $text;
	  $data['action'] = /*$testmethod*/'rewriteText';
	  $data['maxsyns'] = '3'; # The number of synonyms per term.
	  
	  /*if($testmethod=='replaceEveryonesFavorites'){
	    # Add a quality score for this method.
	    $data['quality'] = '1';
	  }*/

	  # Post to API and get back results.
	  $output = wpautoc_tbs_curl_post($url, $data, $info);
	  $output = unserialize($output);
	  if($output['success']=='true')
	  	return $output['output'];
  		return $text;
	}
	else{
	  # There were errors.
		return $text;
	}
}

function wpautoc_import_media( $file_url, $post_id ) {
    if( !$post_id ) {
        return false;
    }
    $filename = basename( $file_url );
    $pos = strpos( $filename, '?' );
    if( $pos !== false )
    	$filename = substr( $filename, 0, $pos );

    $upload_file = wp_upload_bits( $filename, null, file_get_contents( $file_url ) );
// die();
    if (!$upload_file['error']) {
        $wp_filetype = wp_check_filetype( $filename, null );
        // var_dump($wp_filetype);
        $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
        $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );

        if (!is_wp_error($attachment_id)) {
            require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );
            update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
        } else {
            return false;
        }
    } else {
        return false;
    }
    return true;
}

function wpauto_create_post( $campaign_id, $settings, $title, $body, $content_id = 0, $content_type = 0, $thumbnail = false, $extra_tags = false ) {
	$campaign = wpautoc_get_campaign( $campaign_id );
	$campaign_settings = json_decode( $campaign->settings );
	// $category = isset( $settings->category ) ? $settings->category : 0;
	// $author = isset( $settings->author ) ? $settings->author : 1;
	// $tags = isset( $settings->tags ) ? trim($settings->tags) : '';

	$my_post = array(
	  'post_title'    => wp_strip_all_tags( $title ),
	  'post_content'  => $body,
	  'post_status'   => ( isset( $campaign_settings->post_status ) && !empty ($campaign_settings->post_status ) ) ? 'draft' : 'publish',
	  'post_author'   => ( isset( $campaign_settings->author ) && !empty ($campaign_settings->author ) ) ? $campaign_settings->author : 1/*,
	  'meta_input' => array(
	  	'_wpac_cid' => $campaign_id,
	  	'_wpac_cntid' => $content_id,
	  	'_wpac_cnttype' => $content_type,
	  )*/
	);

	if( isset( $campaign_settings->category ) && !empty( $campaign_settings->category ) ) {
		$my_post['post_category'] = array( $campaign_settings->category );
	}
// var_dump($my_post);die();
	if( isset( $campaign_settings->tags ) && $campaign_settings->tags ) {
		$tags_array = preg_split('/\r\n|[\r\n]/', trim( $campaign_settings->tags ) );

		if ( isset( $extra_tags ) && !empty( $extra_tags ) )
			$tags_array = array_unique( array_merge( $tags_array, $extra_tags ) );
		$my_post['tags_input'] = $tags_array;
	}
	else {
		if ( isset( $extra_tags ) && !empty( $extra_tags ) )
			$my_post['tags_input'] = $extra_tags;
	}
	// var_dump($campaign);
	// var_dump($campaign_settings);
	// die('antes');
	/*if ( defined( 'WPAUTOC_CRON_DEBUG' ) ) {
		echo "<P>IMPORTED: ".$title.'</p>';
		return false;
	}*/

/*	if( !VIDPROFITSAI_DO_IMPORT ) {
		echo "<P>IMPORTO: ".$video->snippet->title.'</p>';
		return 0;
	}*/
// var_dump($my_post);
// var_dump($thumbnail);
	$post_id =  wp_insert_post( $my_post );
	if( $post_id ) {
		add_post_meta( $post_id, '_wpac_cid', $campaign_id, true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_wpac_cntid', $content_id, true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_wpac_cnttype', $content_type, true ); //  rmi353, campaign id!
		if( $thumbnail )
			wpautoc_import_media( $thumbnail, $post_id );

    	if ( defined( 'WPAUTOC_CRON_DEBUG' ) ) {
    		// print stuff
			$source_name = wpautoc_get_content_name( $content_type );
    		echo '<p>Creating post: <b><a href="'.get_permalink( $post_id ).'">'.wp_strip_all_tags( $title ).'</a></b> from <b>'.$source_name.'</b><p>';
    	}

	}

	do_action( 'wpautoc_insert_post', $post_id, $campaign_id );
	return $post_id;
}

function wpautoc_is_content_unique( $content_id, $content_type, $campaign_id ) {
	$rd_args = array(
		'post_type' => 'post',
		'post_status' => 'any',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_wpac_cntid',
				'value' => $content_id
			),
			array(
				'key' => '_wpac_cnttype',
				'value' => $content_type
			),
			array(
				'key' => '_wpac_cid',
				'value' => $campaign_id
			),
		)
	);
	$rd_query = new WP_Query( $rd_args );
	return $rd_query->found_posts ? 0 : 1 ;
}

function wpautoc_is_content_unique_array( $content_id, $content_type, $campaign_id ) {
	$rd_args = array(
		'post_type' => 'post',
		'posts_per_page'   => -1,
		'meta_query' => array(
			'relation' => 'AND',
			// array(
			// 	'key' => '_wpac_cntid',
			// 	'value' => $content_id,
			// 	'compare' => 'IN'
			// ),
			array(
				'key' => '_wpac_cnttype',
				'value' => $content_type
			),
			array(
				'key' => '_wpac_cid',
				'value' => $campaign_id
			),
		)
	);
	$posts = get_posts( $rd_args );
	if( !$posts )
		return 1;
	else {
		foreach( $posts as $post ) {
			$post_ids = get_post_meta( $post->ID, '_wpac_cntid', true );
			// var_dump($post_ids);
			if( is_array( $post_ids ) ) {
				if( in_array( $content_id, $post_ids ) )
					return 0;
			}
		}
	}
	return 1;
	// $rd_query = new WP_Query( $rd_args );
	// return $rd_query->found_posts ? 0 : 1 ;
}

function wpautoc_remove_links( $str ){
	$regex = '/<a (.*)<\/a>/isU';
	preg_match_all($regex,$str,$result);
	foreach($result[0] as $rs)
	{
	    $regex = '/<a (.*)>(.*)<\/a>/isU';
	    $text = preg_replace($regex,'$2',$rs);
	    $str = str_replace($rs,$text,$str);
	}
	return $str;
}

function wpautoc_remote_get( $url, $args = false ) {
	set_time_limit( 600 );

	// $url = add_query_arg( $params, $url );

	$req_args = array(
		'method' 	  => 'GET',
	    'timeout'     => 100,
	    'sslverify'   => false
	);

	$response = wp_remote_get( $url, $req_args );

	if( is_array($response) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  return $body;
	}
	else {
	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}
	return false;
}

function wpautoc_exist_page_by_title( $title_str ) {
	global $wpdb;
	return $wpdb->get_row( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $title_str . "' && post_status = 'publish'", 'ARRAY_N' );
}
?>