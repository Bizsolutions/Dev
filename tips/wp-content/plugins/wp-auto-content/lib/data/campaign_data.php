<?php

/* Campaigns */

function wpautoc_get_campaign( $campaign_id = 0 ) {
    if ( !$campaign_id ) return false;
    global $wpdb;
    return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}autoc_campaigns WHERE id = ". intval( $campaign_id ) );
}

function wpautoc_get_default_campaign_values() {
	$campaign = new stdclass();
	$campaign->id = 0;
	$campaign->per_day = 3;
	$campaign->name = '';
	$campaign->enddate = '';
	return $campaign;
}

function wpautoc_add_campaign( $name, $start_date = false, $end_date = NULL, $per_day = 0, $settings = '' ) {
	global $wpdb;
	if( !$start_date )
		$start_date = date( 'Y-m-d' );

	$res = $wpdb->insert(
	    $wpdb->prefix.'autoc_campaigns',
	    array(
	        'name' => $name,
	        'start_date' => $start_date,
	        'end_date' => $end_date,
	        'settings' => $settings,
	        'per_day' => $per_day
	    ),
	    array(
	        '%s',
	        '%s',
	        '%s',
	        '%s',
	        '%d'
	    )
	);
	if ( $res )
	    return $wpdb->insert_id;
	else
		return 0;
}

function wpautoc_update_campaign( $campaign_id, $name, $end_date = NULL, $per_day = 0, $settings = '' ) {
	global $wpdb;

	$res = $wpdb->update(
	    $wpdb->prefix.'autoc_campaigns',
	    array(
	    	'name' => $name,
	    	'end_date' => $end_date,
	    	'settings' => $settings,
	        'per_day' => $per_day
	    ),
	    array( 'id' => $campaign_id ),
	    array(
	    	'%s',
	    	'%s',
	    	'%s',
	        '%d'
	    ),
	    array( '%d' )
	);
	return $res;
}

function wpautoc_get_campaigns( $page = 0, $per_page = 20  ) {
	global $wpdb;
	if( !$page )
		$page = 1;
	$offset = ( $page - 1 ) * $per_page;
	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}autoc_campaigns LIMIT $offset,$per_page" );
}

function wpautoc_total_campaigns( ) {
	global $wpdb;
	return $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}autoc_campaigns" );
}

function wpautoc_delete_campaign( $campaign_id ) {
	global $wpdb;
	if( $campaign_id ) {
		// $wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_actions WHERE campaign_id = ".intval( $campaign_id ) ); // TODO: delete subs
		$wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_campaign_content WHERE campaign_id = ".intval( $campaign_id ) );
		$wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_campaign_monetization WHERE campaign_id = ".intval( $campaign_id ) );
		$wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_campaign_traffic WHERE campaign_id = ".intval( $campaign_id ) );
		return $wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_campaigns WHERE id = ".intval( $campaign_id ) );
	}
	return false;
}

function wpautoc_activate_campaign( $campaign_id, $status ) {
	global $wpdb;
	$status = $status ? 1 : 0;
	if( $campaign_id ) {
		// echo "UPDATE ".$wpdb->prefix."autoc_campaigns set status = ".$status." WHERE id = ".intval( $campaign_id );
		$wpdb->query("UPDATE ".$wpdb->prefix."autoc_campaigns set status = ".$status." WHERE id = ".intval( $campaign_id ) );
	}
}

function wpautoc_get_imported_campaign( $campaign_id ) {
	$rd_args = array(
		'post_type' => 'post',
		'meta_query' => array(
			array(
				'key' => '_wpac_cid',
				'value' => $campaign_id
			),
		)
	);
	$rd_query = new WP_Query( $rd_args );
	return $rd_query->found_posts;
}

function wpautoc_campaign_name( $campaign_id = 0 ) {
	if ( !$campaign_id ) return false;
	global $wpdb;
	return $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}autoc_campaigns WHERE id = ". intval( $campaign_id ) );
}
/*function wpautoc_duplicate_campaign( $campaign_id, $campaign_name = 'New campaign' ) {
	global $wpdb;
	$campaign = wpautoc_get_campaign( $campaign_id );
	$campaign_actions = wpautoc_get_campaign_actions( $campaign_id );
	$new_campaign = wpautoc_add_campaign( $campaign_name, $campaign->url, $campaign->width, $campaign->height, $campaign->preview_img, $campaign->autoplay );
	wpautoc_update_campaign_settings( $new_campaign, $campaign->type, $campaign->type_settings, $campaign->filter_settings, $campaign->options_settings );
	wpautoc_do_activate_campaign( $new_campaign, 0 );
	if( $campaign_actions ) {
		foreach( $campaign_actions as $action ) {
			if ($action) {
				$action_details = wpautoc_get_action( $action->id );
				if ( $action_details && ! empty( $action_details ) )
					$new_action = wpautoc_add_action( $new_campaign, $action_details->type, $action_details->settings );
			}
		}
	}
}*/


/* Content */

define( 'WPAUTOC_CONTENT_YOUTUBE', 1 );
define( 'WPAUTOC_CONTENT_VIMEO', 4 );
define( 'WPAUTOC_CONTENT_EZA', 3 );
define( 'WPAUTOC_CONTENT_AMAZINES', 4 ); // falta
define( 'WPAUTOC_CONTENT_A1ARTICLES', 5 ); // falta
define( 'WPAUTOC_CONTENT_RSS', 6 );
define( 'WPAUTOC_CONTENT_AMAZON', 7 );
define( 'WPAUTOC_CONTENT_ALIEXPRESS', 8 );
define( 'WPAUTOC_CONTENT_EBAY', 9 );
define( 'WPAUTOC_CONTENT_FACEBOOK', 10 );
define( 'WPAUTOC_CONTENT_TWITTER', 11 );
define( 'WPAUTOC_CONTENT_PINTEREST', 12 );
define( 'WPAUTOC_CONTENT_INSTAGRAM', 13 );
define( 'WPAUTOC_CONTENT_GOOGLEPLUS', 27 );
define( 'WPAUTOC_CONTENT_TUMBLR', 14 );
define( 'WPAUTOC_CONTENT_CRAIGSLIST', 15 ); 
define( 'WPAUTOC_CONTENT_NEWS', 16 );
define( 'WPAUTOC_CONTENT_WHIO', 39 );
define( 'WPAUTOC_CONTENT_NYT', 40 );
define( 'WPAUTOC_CONTENT_IMDB', 17 ); // falta
define( 'WPAUTOC_CONTENT_GOOGLETRENDS', 18 ); // falta
define( 'WPAUTOC_CONTENT_GOOGLEBOOKS', 19 );
define( 'WPAUTOC_CONTENT_RECIPES', 20 ); // falta
define( 'WPAUTOC_CONTENT_WIKIPEDIA', 21 );
define( 'WPAUTOC_CONTENT_NASA', 22 );
define( 'WPAUTOC_CONTENT_EVENTBRITE', 23 );
define( 'WPAUTOC_CONTENT_ITUNES', 24 ); // falta
define( 'WPAUTOC_CONTENT_ETSY', 25 );
define( 'WPAUTOC_CONTENT_TWITCH', 26 ); // falta
define( 'WPAUTOC_CONTENT_DAILYMOTION', 28 );
define( 'WPAUTOC_CONTENT_YELP', 29 );
define( 'WPAUTOC_CONTENT_BLOGSPOT', 30 ); // falta
define( 'WPAUTOC_CONTENT_CLICKBANK', 31 );
define( 'WPAUTOC_CONTENT_WALMART', 32 );
define( 'WPAUTOC_CONTENT_BESTBUY', 33 );
define( 'WPAUTOC_CONTENT_ENVATO', 34 );
define( 'WPAUTOC_CONTENT_UDEMY', 35 );
define( 'WPAUTOC_CONTENT_MEDIUM', 36 );
define( 'WPAUTOC_CONTENT_CAREERJET', 37 );
define( 'WPAUTOC_CONTENT_BIGCS', 38 );
define( 'WPAUTOC_CONTENT_BIGAS', 44 );
define( 'WPAUTOC_CONTENT_AFORGE', 43 );
define( 'WPAUTOC_CONTENT_ABUILDER', 42 );
define( 'WPAUTOC_CONTENT_GEARBEST', 41 );
// define( 'WPAUTOC_CONTENT_RECIPES', 20 );

function wpautoc_get_content_elements( $campaign_id = 0  ) {
	global $wpdb;
	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}autoc_campaign_content WHERE campaign_id = ".intval( $campaign_id ) );
}

function wpautoc_get_content_types() {
	if( wpautoc_is_pro() ) {
		$arr = array(
			array( 'id' => 100, 'name' => 'optgroup', 'label' => 'Article Directories' ),
			array( 'id' => WPAUTOC_CONTENT_EZA, 'name' => 'Ezine Articles' ),
			array( 'id' => WPAUTOC_CONTENT_BIGCS, 'name' => 'Big Content Search Articles' ),
			array( 'id' => WPAUTOC_CONTENT_BIGAS, 'name' => 'Big Article Scraper Articles', 'pro' => 1 ),
			array( 'id' => WPAUTOC_CONTENT_AFORGE, 'name' => 'ArticleForge Articles', 'pro' => 1 ),
			array( 'id' => WPAUTOC_CONTENT_ABUILDER, 'name' => 'ArticleBuilder Articles', 'pro' => 1 ),
			// array( 'id' => WPAUTOC_CONTENT_AMAZINES, 'name' => 'Amazines' ),
			// array( 'id' => WPAUTOC_CONTENT_A1ARTICLES, 'name' => 'A1 Articles' ),
			array( 'id' => 101, 'name' => 'optgroupclose' ),
			array( 'id' => 200, 'name' => 'optgroup', 'label' => 'Videos' ),
			array( 'id' => WPAUTOC_CONTENT_YOUTUBE, 'name' => 'Youtube Videos' ),
			array( 'id' => WPAUTOC_CONTENT_VIMEO, 'name' => 'Vimeo Videos' ),
			// array( 'id' => WPAUTOC_CONTENT_TWITCH, 'name' => 'Twitch Videos' ),
			array( 'id' => WPAUTOC_CONTENT_DAILYMOTION, 'name' => 'Dailymotion Videos', 'pro' => 1 ),
			array( 'id' => 201, 'name' => 'optgroupclose' ),
			array( 'id' => 300, 'name' => 'optgroup', 'label' => 'Ecommerce' ),
			array( 'id' => WPAUTOC_CONTENT_AMAZON, 'name' => 'Amazon Products' ),
			array( 'id' => WPAUTOC_CONTENT_EBAY, 'name' => 'Ebay Products' ),
			array( 'id' => WPAUTOC_CONTENT_ALIEXPRESS, 'name' => 'Aliexpress Products' ),
			array( 'id' => WPAUTOC_CONTENT_WALMART, 'name' => 'Walmart Products' ),
			array( 'id' => WPAUTOC_CONTENT_BESTBUY, 'name' => 'Bestbuy Products' ),
			array( 'id' => WPAUTOC_CONTENT_ETSY, 'name' => 'Etsy Products', 'pro' => 1 ),
			array( 'id' => WPAUTOC_CONTENT_GEARBEST, 'name' => 'Gearbest Products', 'pro' => 1 ),
			array( 'id' => 301, 'name' => 'optgroupclose' ),
			array( 'id' => 400, 'name' => 'optgroup', 'label' => 'Other Content Sources' ),
			array( 'id' => WPAUTOC_CONTENT_RSS, 'name' => 'RSS' ),
			array( 'id' => WPAUTOC_CONTENT_WIKIPEDIA, 'name' => 'Wikipedia Pages' ),
			array( 'id' => WPAUTOC_CONTENT_CLICKBANK, 'name' => 'Clickbank Products' ),
			array( 'id' => WPAUTOC_CONTENT_TUMBLR, 'name' => 'Tumblr Posts' ),
			array( 'id' => WPAUTOC_CONTENT_MEDIUM, 'name' => 'Medium Posts' ),
			array( 'id' => WPAUTOC_CONTENT_ENVATO, 'name' => 'Envato Products' ),
			array( 'id' => WPAUTOC_CONTENT_UDEMY, 'name' => 'Udemy Courses' ),
			// array( 'id' => WPAUTOC_CONTENT_BLOGSPOT, 'name' => 'Blogspot' ),

			array( 'id' => WPAUTOC_CONTENT_CAREERJET, 'name' => 'Careerjet Jobs' ),
			// array( 'id' => WPAUTOC_CONTENT_GOOGLETRENDS, 'name' => 'Google Trends' ),
			// array( 'id' => WPAUTOC_CONTENT_IMDB, 'name' => 'IMDB' ),
			array( 'id' => WPAUTOC_CONTENT_GOOGLEBOOKS, 'name' => 'Google Books' ),
			// array( 'id' => WPAUTOC_CONTENT_RECIPES, 'name' => 'Recipes' ),
			array( 'id' => WPAUTOC_CONTENT_NASA, 'name' => 'NASA Articles' ),
			// array( 'id' => WPAUTOC_CONTENT_ITUNES, 'name' => 'Itunes Content' ),
			array( 'id' => WPAUTOC_CONTENT_YELP, 'name' => 'Yelp Reviews' ),
			array( 'id' => WPAUTOC_CONTENT_EVENTBRITE, 'name' => 'Eventbrite Events', 'pro' => 1 ),
			array( 'id' => WPAUTOC_CONTENT_CRAIGSLIST, 'name' => 'Craigslist Posts', 'pro' => 1 ),
			array( 'id' => 401, 'name' => 'optgroupclose' ),
			array( 'id' => 600, 'name' => 'optgroup', 'label' => 'News' ),
			array( 'id' => WPAUTOC_CONTENT_NEWS, 'name' => 'Newsapi.org Items' ),
			array( 'id' => WPAUTOC_CONTENT_NYT, 'name' => 'New York Times News Items' ),
			array( 'id' => WPAUTOC_CONTENT_WHIO, 'name' => 'Webhose.io News Items', 'pro' => 1 ),
			array( 'id' => 101, 'name' => 'optgroupclose' ),
			array( 'id' => 500, 'name' => 'optgroup', 'label' => 'Social' ),
			array( 'id' => WPAUTOC_CONTENT_FACEBOOK, 'name' => 'Facebook Posts' ),
			array( 'id' => WPAUTOC_CONTENT_TWITTER, 'name' => 'Twitter Tweets' ),
			array( 'id' => WPAUTOC_CONTENT_PINTEREST, 'name' => 'Pinterest Pins' ),
			// array( 'id' => WPAUTOC_CONTENT_INSTAGRAM, 'name' => 'Instagram Posts' ),
			array( 'id' => WPAUTOC_CONTENT_GOOGLEPLUS, 'name' => 'Google+ Posts', 'pro' => 1 ),
			array( 'id' => 501, 'name' => 'optgroupclose' ),
		);
	}
	else {
		$arr = array(
			array( 'id' => 100, 'name' => 'optgroup', 'label' => 'Article Directories' ),
			array( 'id' => WPAUTOC_CONTENT_EZA, 'name' => 'Ezine Articles' ),
			array( 'id' => WPAUTOC_CONTENT_BIGCS, 'name' => 'Big Content Search Articles' ),
			// array( 'id' => WPAUTOC_CONTENT_AMAZINES, 'name' => 'Amazines' ),
			// array( 'id' => WPAUTOC_CONTENT_A1ARTICLES, 'name' => 'A1 Articles' ),
			array( 'id' => 101, 'name' => 'optgroupclose' ),
			array( 'id' => 200, 'name' => 'optgroup', 'label' => 'Videos' ),
			array( 'id' => WPAUTOC_CONTENT_YOUTUBE, 'name' => 'Youtube Videos' ),
			array( 'id' => WPAUTOC_CONTENT_VIMEO, 'name' => 'Vimeo Videos' ),
			// array( 'id' => WPAUTOC_CONTENT_TWITCH, 'name' => 'Twitch Videos' ),
			array( 'id' => 201, 'name' => 'optgroupclose' ),
			array( 'id' => 300, 'name' => 'optgroup', 'label' => 'Ecommerce' ),
			array( 'id' => WPAUTOC_CONTENT_AMAZON, 'name' => 'Amazon Products' ),
			array( 'id' => 301, 'name' => 'optgroupclose' ),
			array( 'id' => 400, 'name' => 'optgroup', 'label' => 'Other Content Sources' ),
			array( 'id' => WPAUTOC_CONTENT_RSS, 'name' => 'RSS' ),
			array( 'id' => WPAUTOC_CONTENT_WIKIPEDIA, 'name' => 'Wikipedia Pages' ),
			array( 'id' => WPAUTOC_CONTENT_TUMBLR, 'name' => 'Tumblr Posts' ),
			// array( 'id' => WPAUTOC_CONTENT_BLOGSPOT, 'name' => 'Blogspot' ),

			array( 'id' => WPAUTOC_CONTENT_CAREERJET, 'name' => 'Careerjet Jobs' ),
			// array( 'id' => WPAUTOC_CONTENT_GOOGLETRENDS, 'name' => 'Google Trends' ),
			// array( 'id' => WPAUTOC_CONTENT_IMDB, 'name' => 'IMDB' ),
			array( 'id' => WPAUTOC_CONTENT_GOOGLEBOOKS, 'name' => 'Google Books' ),
			// array( 'id' => WPAUTOC_CONTENT_RECIPES, 'name' => 'Recipes' ),
			array( 'id' => WPAUTOC_CONTENT_NASA, 'name' => 'NASA Articles' ),
			// array( 'id' => WPAUTOC_CONTENT_ITUNES, 'name' => 'Itunes Content' ),
			array( 'id' => 401, 'name' => 'optgroupclose' ),
			array( 'id' => 600, 'name' => 'optgroup', 'label' => 'News' ),
			array( 'id' => WPAUTOC_CONTENT_NEWS, 'name' => 'Newsapi.org Items' ),
			array( 'id' => WPAUTOC_CONTENT_NYT, 'name' => 'New York Times News Items' ),
			array( 'id' => 101, 'name' => 'optgroupclose' ),
			array( 'id' => 500, 'name' => 'optgroup', 'label' => 'Social' ),
			array( 'id' => WPAUTOC_CONTENT_FACEBOOK, 'name' => 'Facebook Posts' ),
			array( 'id' => WPAUTOC_CONTENT_TWITTER, 'name' => 'Twitter Tweets' ),
			array( 'id' => WPAUTOC_CONTENT_PINTEREST, 'name' => 'Pinterest Pins' ),
			// array( 'id' => WPAUTOC_CONTENT_INSTAGRAM, 'name' => 'Instagram Posts' ),
			array( 'id' => 501, 'name' => 'optgroupclose' ),
		);
	}
	return $arr;
}

function wpautoc_add_content( $campaign_id, $type, $settings ) {
	global $wpdb;
	$sql = "INSERT INTO {$wpdb->prefix}autoc_campaign_content (campaign_id, type, settings ) VALUES (".intval( $campaign_id ).", ".intval( $type ).", '$settings')";
	// die($sql);
	return $wpdb->query( $sql );
}

function wpautoc_update_content( $content_id, $settings ) {
	global $wpdb;
	$sql = "UPDATE {$wpdb->prefix}autoc_campaign_content SET settings = '$settings' WHERE id = ".intval( $content_id );
	return $wpdb->query( $sql );
}

function wpautoc_delete_content( $content_id ) {
	global $wpdb;
	$sql = "DELETE FROM {$wpdb->prefix}autoc_campaign_content WHERE id = ".intval( $content_id );
	return $wpdb->query( $sql );
}


function wpautoc_get_content_name( $id = 0 ) {
	$arr = array(
		WPAUTOC_CONTENT_EZA => 'EzineArticles',
		WPAUTOC_CONTENT_YOUTUBE => 'Youtube',
		WPAUTOC_CONTENT_VIMEO => 'Vimeo',
		WPAUTOC_CONTENT_DAILYMOTION => 'Dailymotion',
		WPAUTOC_CONTENT_AMAZON => 'Amazon',
		WPAUTOC_CONTENT_EBAY => 'Ebay',
		WPAUTOC_CONTENT_ALIEXPRESS => 'Aliexpress',
		WPAUTOC_CONTENT_WALMART => 'Walmart',
		WPAUTOC_CONTENT_BESTBUY => 'Bestbuy',
		WPAUTOC_CONTENT_ETSY => 'Etsy',
		WPAUTOC_CONTENT_GEARBEST => 'Gearbest',
		WPAUTOC_CONTENT_RSS => 'RSS',
		WPAUTOC_CONTENT_WIKIPEDIA => 'Wikipedia',
		WPAUTOC_CONTENT_CLICKBANK => 'Clickbank',
		WPAUTOC_CONTENT_TUMBLR => 'Tumblr',
		WPAUTOC_CONTENT_MEDIUM => 'Medium',
		WPAUTOC_CONTENT_ENVATO => 'Envato',
		WPAUTOC_CONTENT_UDEMY => 'Udemy',
		WPAUTOC_CONTENT_CRAIGSLIST => 'Craigslist',
		WPAUTOC_CONTENT_CAREERJET => 'Careerjet',
		WPAUTOC_CONTENT_GOOGLEBOOKS => 'Google Books',
		WPAUTOC_CONTENT_NASA => 'NASA',
		WPAUTOC_CONTENT_EVENTBRITE => 'Eventbrite',
		WPAUTOC_CONTENT_YELP => 'Yelp',
		WPAUTOC_CONTENT_NEWS => 'Newsapi.org',
		WPAUTOC_CONTENT_WHIO => 'Webhose.io',
		WPAUTOC_CONTENT_NYT => 'New York Times',
		WPAUTOC_CONTENT_FACEBOOK => 'Facebook',
		WPAUTOC_CONTENT_TWITTER => 'Twitter',
		WPAUTOC_CONTENT_PINTEREST => 'Pinterest',
		WPAUTOC_CONTENT_GOOGLEPLUS => 'Google+',
		WPAUTOC_CONTENT_BIGCS => 'Big Content Search',
		WPAUTOC_CONTENT_BIGAS => 'Big Article Scraper',
		WPAUTOC_CONTENT_AFORGE => 'ArticleForge',
		WPAUTOC_CONTENT_ABUILDER => 'ArticleBuilder',
	);
	if( $id )
		return $arr[$id];
	return $arr;
}



/* Amazon */

function wpautoc_get_amazon_cats() {
	return array(
		array( 'label' => 'All', 'value' => 'All'),
		array( 'label' => 'Apparel & Accessories', 'value' => 'Apparel'),
		// array( 'label' => 'Appstore for Android', 'value' => 'Appstore for Android'),
		array( 'label' => 'Arts, Crafts & Sewing', 'value' => 'ArtsAndCrafts'),
		array( 'label' => 'Automotive', 'value' => 'Automotive'),
		array( 'label' => 'Baby', 'value' => 'Baby'),
		array( 'label' => 'Beauty', 'value' => 'Beauty'),
		// array( 'label' => 'Black Friday Sales', 'value' => 'Black Friday Sales'),
		array( 'label' => 'Books', 'value' => 'Books'),
		array( 'label' => 'Camera & Photo', 'value' => 'Photo'),
		// array( 'label' => 'Car Toys', 'value' => 'Car Toys'),
		array( 'label' => 'Cell Phones & Accessories', 'value' => 'Wireless'),
		array( 'label' => 'Computer & Video Games', 'value' => 'VideoGames'),
		// array( 'label' => 'Computers', 'value' => 'Computers'),
		array( 'label' => 'Electronics', 'value' => 'Electronics'),
		array( 'label' => 'Grocery & Gourmet Food', 'value' => 'Grocery'),
		array( 'label' => 'Health & Personal Care', 'value' => 'HealthPersonalCare'),
		array( 'label' => 'Home & Garden', 'value' => 'Garden'),
		array( 'label' => 'Industrial & Scientific', 'value' => 'Industrial'),
		array( 'label' => 'Jewelry', 'value' => 'Jewelry'),
		array( 'label' => 'Kindle Store', 'value' => 'KindleStore'),
		array( 'label' => 'Kitchen & Housewares', 'value' => 'Kitchen'),
		array( 'label' => 'Magazine Subscriptions', 'value' => 'Magazines'),
		array( 'label' => 'Miscellaneous', 'value' => 'Miscellaneous'),
		array( 'label' => 'Movies & TV', 'value' => 'Movies'),
		array( 'label' => 'MP3 Downloads', 'value' => 'MP3Downloads'),
		array( 'label' => 'Music', 'value' => 'Music'),
		array( 'label' => 'Musical Instruments', 'value' => 'MusicalInstruments'),
		array( 'label' => 'Office Products', 'value' => 'OfficeProducts'),
		array( 'label' => 'Outdoor Living', 'value' => 'OutdoorLiving'),
		// array( 'label' => 'Pet Supplies', 'value' => 'PetSupplies'),
		array( 'label' => 'Pet Supplies', 'value' => 'PetSupplies'),
		array( 'label' => 'PC Hardware', 'value' => 'PCHardware'),
		array( 'label' => 'Shoes', 'value' => 'Shoes'),
		array( 'label' => 'Software', 'value' => 'Software'),
		// array( 'label' => 'Specialty Stores', 'value' => 'Specialty Stores'),
		array( 'label' => 'Sports & Outdoors', 'value' => 'SportingGoods'),
		array( 'label' => 'Tools & Hardware', 'value' => 'Tools'),
		array( 'label' => 'Toys and Games', 'value' => 'Toys'),
		array( 'label' => 'Wine', 'value' => 'Wine')
		// array( 'label' => 'Warehouse Deals', 'value' => 'Warehouse Deals')
	);
}

function wpautoc_get_amazon_countries() {
	// private $LOCATIONS = array ('ca','com','cn', 'co.jp', 'co.uk','fr',
	//                             'it','cn');
	return array(
		array( 'label' => 'USA', 'value' => 'com'),
		array( 'label' => 'Canada', 'value' => 'ca'),
		array( 'label' => 'UK', 'value' => 'co.uk'),
		array( 'label' => 'Germany', 'value' => 'de'),
		array( 'label' => 'France', 'value' => 'fr'),
		array( 'label' => 'Italy', 'value' => 'it'),
		array( 'label' => 'Spain', 'value' => 'es'),
		array( 'label' => 'Japan', 'value' => 'co.jp'),
		array( 'label' => 'India', 'value' => 'in'),
		array( 'label' => 'Brazil', 'value' => 'com.br')
	);
}

/* Ebay */

function wpautoc_get_ebay_cats() {
	return array(
		array( 'label' => 'Any', 'value' => 0),
		array( 'value' => '20081', 'label' => 'Antiques' ),
		array( 'value' => '550', 'label' => 'Art' ),
		array( 'value' => '2984', 'label' => 'Baby' ),
		array( 'value' => '267', 'label' => 'Books, Comics & Magazines' ),
		array( 'value' => '12576', 'label' => 'Business, Office & Industrial' ),
		array( 'value' => '625', 'label' => 'Cameras & Photography' ),
		array( 'value' => '9800', 'label' => 'Cars, Motorcycles & Vehicles' ),
		array( 'value' => '11450', 'label' => 'Clothes, Shoes & Accessories' ),
		array( 'value' => '11116', 'label' => 'Coins' ),
		array( 'value' => '1', 'label' => 'Collectables' ),
		array( 'value' => '58058', 'label' => 'Computers/Tablets & Networking' ),
		array( 'value' => '14339', 'label' => 'Crafts' ),
		array( 'value' => '237', 'label' => 'Dolls & Bears' ),
		array( 'value' => '11232', 'label' => 'DVDs, Films & TV' ),
		array( 'value' => '1305', 'label' => 'Events Tickets' ),
		array( 'value' => '159912', 'label' => 'Garden & Patio' ),
		array( 'value' => '26395', 'label' => 'Health & Beauty' ),
		array( 'value' => '3252', 'label' => 'Holidays & Travel' ),
		array( 'value' => '11700', 'label' => 'Home, Furniture & DIY' ),
		array( 'value' => '281', 'label' => 'Jewellery & Watches' ),
		array( 'value' => '15032', 'label' => 'Mobile Phones & Communication' ),
		array( 'value' => '11233', 'label' => 'Music' ),
		array( 'value' => '619', 'label' => 'Musical Instruments' ),
		array( 'value' => '1281', 'label' => 'Pet Supplies' ),
		array( 'value' => '870', 'label' => 'Pottery, Porcelain & Glass' ),
		array( 'value' => '10542', 'label' => 'Property' ),
		array( 'value' => '293', 'label' => 'Sound & Vision' ),
		array( 'value' => '888', 'label' => 'Sporting Goods' ),
		array( 'value' => '64482', 'label' => 'Sports Memorabilia' ),
		array( 'value' => '260', 'label' => 'Stamps' ),
		array( 'value' => '220', 'label' => 'Toys & Games' ),
		array( 'value' => '131090', 'label' => 'Vehicle Parts & Accessories' ),
		array( 'value' => '1249', 'label' => 'Video Games & Consoles' ),
		array( 'value' => '40005', 'label' => 'Wholesale & Job Lots' )
	);
}

function wpautoc_get_ebay_countries() {
	return array(
		array( 'label' => 'USA', 'value' => 'US'),
		array( 'label' => 'Canada', 'value' => 'CA'),
		array( 'label' => 'UK', 'value' => 'UK'),
		array( 'label' => 'Australia', 'value' => 'AU'),
		array( 'label' => 'Germany', 'value' => 'DE'),
		array( 'label' => 'France', 'value' => 'FR'),
		array( 'label' => 'Italy', 'value' => 'IT'),
		array( 'label' => 'Spain', 'value' => 'ES'),
		array( 'label' => 'Netherlands', 'value' => 'NL'),
		array( 'label' => 'Belgium', 'value' => 'BE'),
		array( 'label' => 'Ireland', 'value' => 'IE'),
		array( 'label' => 'Austria', 'value' => 'AT'),
		array( 'label' => 'Switzerland', 'value' => 'CH'),
	);
}
/* Aliexpress */

function wpautoc_get_aliexpress_cats() {
	// private $LOCATIONS = array ('ca','com','cn', 'co.jp', 'co.uk','fr',
	//                             'it','cn');
	return array(
		array( 'label' => 'All', 'value' => '0'),
		array( 'label' => 'Apparel & Accessories', 'value' => '3'),
		array( 'label' => 'Automobiles & Motorcycles', 'value' => '34'),
		array( 'label' => 'Beauty & Health', 'value' => '66'),
		array( 'label' => 'Books for Local Russian', 'value' => '200004360'),
		array( 'label' => 'Computer & Office', 'value' => '7'),
		array( 'label' => 'Home Improvement', 'value' => '13'),
		array( 'label' => 'Consumer Electronics', 'value' => '44'),
		array( 'label' => 'Electrical Equipment & Supplies', 'value' => '44'),
		array( 'label' => 'Furniture', 'value' => '5'),
		array( 'label' => 'Hair & Accessories', 'value' => '200003655'),
		array( 'label' => 'Hardware', 'value' => '42'),
		array( 'label' => 'Home & Garden', 'value' => '15'),
		array( 'label' => 'Home Appliances', 'value' => '6'),
		array( 'label' => 'Industry & Business', 'value' => '200001996'),
		array( 'label' => 'Jewelry & Accessories', 'value' => '36'),
		array( 'label' => 'Lights & Lighting', 'value' => '39'),
		array( 'label' => 'Luggage & Bags', 'value' => '1524'),
		array( 'label' => 'Mother & Kids', 'value' => '1501'),
		array( 'label' => 'Office & School Supplies', 'value' => '21'),
		array( 'label' => 'Phones & Telecommunications', 'value' => '509'),
		array( 'label' => 'Security & Protection', 'value' => '30'),
		array( 'label' => 'Shoes', 'value' => '322'),
		array( 'label' => 'Sports & Entertainment', 'value' => '18'),
		array( 'label' => 'Tools', 'value' => '1420'),
		array( 'label' => 'Toys & Hobbies', 'value' => '26'),
		array( 'label' => 'Watches', 'value' => '1511'),
		array( 'label' => 'Weddings & Events', 'value' => '320')
	);
}

/* Traffic */

define( 'WPAUTOC_TRAFFIC_FACEBOOK', 1 );
define( 'WPAUTOC_TRAFFIC_TWITTER', 2 );
define( 'WPAUTOC_TRAFFIC_BACKLINKMACHINE', 3 );
define( 'WPAUTOC_TRAFFIC_REDDIT', 4 );
define( 'WPAUTOC_TRAFFIC_PINTEREST', 5 );
define( 'WPAUTOC_TRAFFIC_GOOGLEPLUS', 6 );
define( 'WPAUTOC_TRAFFIC_YOUTUBECOMMENTS', 7 );
define( 'WPAUTOC_TRAFFIC_SLIDESHARE', 8 );
define( 'WPAUTOC_TRAFFIC_LINKEDIN', 9 );
define( 'WPAUTOC_TRAFFIC_STUMBLEUPON', 10 );
define( 'WPAUTOC_TRAFFIC_MEDIUM', 11 );
define( 'WPAUTOC_TRAFFIC_TUMBLR', 12 );
define( 'WPAUTOC_TRAFFIC_ILI', 13 );
define( 'WPAUTOC_TRAFFIC_BLI', 14 );
define( 'WPAUTOC_TRAFFIC_BUFFER', 15 );
define( 'WPAUTOC_TRAFFIC_INSTAGRAM', 16 );

function wpautoc_get_traffic_elements( $campaign_id = 0  ) {
	global $wpdb;
	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}autoc_campaign_traffic WHERE campaign_id = ".intval( $campaign_id ) );
}

function wpautoc_get_traffic_types() {
	$arr = array(
		array( 'id' => 100, 'name' => 'optgroup', 'label' => 'SEO' ),
		array( 'id' => WPAUTOC_TRAFFIC_BACKLINKMACHINE, 'name' => 'Backlink Machine' ),
		array( 'id' => WPAUTOC_TRAFFIC_ILI, 'name' => 'Instant Link Indexer' ),
		array( 'id' => WPAUTOC_TRAFFIC_BLI, 'name' => 'Backlinks Indexer' ),
		array( 'id' => 101, 'name' => 'optgroupclose' ),
		array( 'id' => 200, 'name' => 'optgroup', 'label' => 'Social' ),
		array( 'id' => WPAUTOC_TRAFFIC_FACEBOOK, 'name' => 'Facebook' ),
		array( 'id' => WPAUTOC_TRAFFIC_TWITTER, 'name' => 'Twitter' ),
		array( 'id' => WPAUTOC_TRAFFIC_REDDIT, 'name' => 'Reddit' ),
		array( 'id' => WPAUTOC_TRAFFIC_PINTEREST, 'name' => 'Pinterest' ),
		array( 'id' => WPAUTOC_TRAFFIC_INSTAGRAM, 'name' => 'Instagram' ),
		array( 'id' => WPAUTOC_TRAFFIC_GOOGLEPLUS, 'name' => 'Google+' ),
		// array( 'id' => WPAUTOC_TRAFFIC_YOUTUBECOMMENTS, 'name' => 'Youtube Comments' ),
		array( 'id' => WPAUTOC_TRAFFIC_LINKEDIN, 'name' => 'Linkedin' ),
		array( 'id' => WPAUTOC_TRAFFIC_STUMBLEUPON, 'name' => 'StumbleUpon' ),
		array( 'id' => WPAUTOC_TRAFFIC_MEDIUM, 'name' => 'Medium' ),
		array( 'id' => WPAUTOC_TRAFFIC_TUMBLR, 'name' => 'Tumblr' ),
		array( 'id' => WPAUTOC_TRAFFIC_BUFFER, 'name' => 'Buffer' ),
		array( 'id' => 201, 'name' => 'optgroupclose' )
		//array( 'id' => WPAUTOC_TRAFFIC_REDDIT, 'name' => 'Slideshare' ),
	);
	return $arr;
}

function wpautoc_add_traffic( $campaign_id, $type, $settings ) {
	global $wpdb;
	$sql = "INSERT INTO {$wpdb->prefix}autoc_campaign_traffic (campaign_id, type, settings ) VALUES (".intval( $campaign_id ).", ".intval( $type ).", '$settings')";
	// die($sql);
	return $wpdb->query( $sql );
}

function wpautoc_update_traffic( $traffic_id, $settings ) {
	global $wpdb;
	$sql = "UPDATE {$wpdb->prefix}autoc_campaign_traffic SET settings = '$settings' WHERE id = ".intval( $traffic_id );
	return $wpdb->query( $sql );
}

function wpautoc_delete_traffic( $traffic_id ) {
	global $wpdb;
	$sql = "DELETE FROM {$wpdb->prefix}autoc_campaign_traffic WHERE id = ".intval( $traffic_id );
	return $wpdb->query( $sql );
}

function wpautoc_blmachine_linknum() {
	return array(
		array( 'label' => '1', 'value' => '1'),
		array( 'label' => '2', 'value' => '2'),
		array( 'label' => '3', 'value' => '3'),
		array( 'label' => '5', 'value' => '5'),
		array( 'label' => '10', 'value' => '10'),
		array( 'label' => '20', 'value' => '20'),
		array( 'label' => '30', 'value' => '30'),
		array( 'label' => '40', 'value' => '40'),
		array( 'label' => '50', 'value' => '50')
	);
}

function wpautoc_get_numbers( $start, $end, $extra = false ) {
	$arr = array();
	if( $extra )
		$arr[] = array( 'label' => $extra, 'value' => 0 );

	for( $i = $start; $i<= $end; $i++ )
		$arr[] = array( 'label' =>$i, 'value' => $i);
	return $arr;
}


function wpautoc_get_post_campaign( $post_id = false ) {
	if( !$post_id )
		$post_id = get_the_id();
	return get_post_meta( $post_id, '_wpac_cid', true );
}

function wpautoc_get_adtypes( ) {
	return array( array( 'label' => 'Image', 'value' => 1 ), array( 'label' => 'Text Only', 'value' => 2 ) );
}

// function wpautoc_activate_campaign( $campaign_id, $status ) {
// 	global $wpdb;
// 	$sql = "UPDATE {$wpdb->prefix}autoc_campaignsc SET status = '$status' WHERE id = ".intval( $campaign_id );
// 	return $wpdb->query( $sql );
// }
?>