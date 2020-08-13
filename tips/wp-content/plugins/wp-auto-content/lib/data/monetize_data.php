<?php

/* Monetization */

define( 'WPAUTOC_MONETIZE_BANNER', 1 );
define( 'WPAUTOC_MONETIZE_ADSENSE', 2 );
define( 'WPAUTOC_MONETIZE_ADCODE', 3 );
define( 'WPAUTOC_MONETIZE_AMAZON', 4 );
define( 'WPAUTOC_MONETIZE_LINKS', 5 );
define( 'WPAUTOC_MONETIZE_INTEXT', 6 );
define( 'WPAUTOC_MONETIZE_OPTIN', 7 );
define( 'WPAUTOC_MONETIZE_ALIEXPRESS', 8 );
define( 'WPAUTOC_MONETIZE_EBAY', 9 );
define( 'WPAUTOC_MONETIZE_SOCIALLOCK', 10 );
define( 'WPAUTOC_MONETIZE_VIRALADS', 11 );
define( 'WPAUTOC_MONETIZE_TEXTADS', 12 );
define( 'WPAUTOC_MONETIZE_CLICKBANK', 13 );
define( 'WPAUTOC_MONETIZE_POPUP', 14 );
define( 'WPAUTOC_MONETIZE_WALMART', 15 );
define( 'WPAUTOC_MONETIZE_BESTBUY', 16 );
define( 'WPAUTOC_MONETIZE_ENVATO', 17 );
define( 'WPAUTOC_MONETIZE_GEARBEST', 18 );

function wpautoc_get_monetize_elements( $campaign_id = 0, $reverse = false  ) {
	global $wpdb;
	$sql = "SELECT * FROM {$wpdb->prefix}autoc_campaign_monetization WHERE campaign_id = ".intval( $campaign_id );
	if( $reverse )
		$sql .= " ORDER BY id DESC";
	return $wpdb->get_results( $sql );
}

function wpautoc_get_monetize_element( $monetize_id  ) {
	global $wpdb;
	$sql = "SELECT * FROM {$wpdb->prefix}autoc_campaign_monetization WHERE id = ".intval( $monetize_id );
	return $wpdb->get_row( $sql );
}

function wpautoc_get_monetize_types() {
	if( wpautoc_is_monetize() ) {
		$arr = array(
			array( 'id' => 100, 'name' => 'optgroup', 'label' => 'Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_BANNER, 'name' => 'Banner Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_ADSENSE, 'name' => 'Adsense' ),
			array( 'id' => WPAUTOC_MONETIZE_ADCODE, 'name' => 'HTML Ad Code' ),
			array( 'id' => WPAUTOC_MONETIZE_VIRALADS, 'name' => 'Viral Image Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_TEXTADS, 'name' => 'Text Ads' ),
			array( 'id' => 101, 'name' => 'optgroupclose' ),
			array( 'id' => 200, 'name' => 'optgroup', 'label' => 'Affiliate' ),
			array( 'id' => WPAUTOC_MONETIZE_AMAZON, 'name' => 'Amazon Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_EBAY, 'name' => 'Ebay Products' ),
			array( 'id' => WPAUTOC_MONETIZE_ALIEXPRESS, 'name' => 'Aliexpress Products' ),
			array( 'id' => WPAUTOC_MONETIZE_CLICKBANK, 'name' => 'Clickbank Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_WALMART, 'name' => 'Walmart Products' ),
			array( 'id' => WPAUTOC_MONETIZE_BESTBUY, 'name' => 'BestBuy Products' ),
			array( 'id' => WPAUTOC_MONETIZE_ENVATO, 'name' => 'Envato Products' ),
			array( 'id' => WPAUTOC_MONETIZE_GEARBEST, 'name' => 'Gearbest Products' ),
			array( 'id' => 201, 'name' => 'optgroupclose' ),
			array( 'id' => 300, 'name' => 'optgroup', 'label' => 'Links' ),
			array( 'id' => WPAUTOC_MONETIZE_LINKS, 'name' => 'Inline Links' ),
			array( 'id' => WPAUTOC_MONETIZE_INTEXT, 'name' => 'In-Text Ads' ),
			array( 'id' => 301, 'name' => 'optgroupclose' ),
			array( 'id' => 400, 'name' => 'optgroup', 'label' => 'Others' ),
			array( 'id' => WPAUTOC_MONETIZE_OPTIN, 'name' => 'Optin Form' ),
			array( 'id' => WPAUTOC_MONETIZE_POPUP, 'name' => 'Popup Content' ),
			array( 'id' => WPAUTOC_MONETIZE_SOCIALLOCK, 'name' => 'Social Lock' ),
			array( 'id' => 401, 'name' => 'optgroupclose' ),
		);
	}
	else {
		$arr = array(
			array( 'id' => WPAUTOC_MONETIZE_BANNER, 'name' => 'Banner Ads' ),
			array( 'id' => WPAUTOC_MONETIZE_ADSENSE, 'name' => 'Adsense' ),
			array( 'id' => WPAUTOC_MONETIZE_ADCODE, 'name' => 'HTML Ad Code' ),
			array( 'id' => WPAUTOC_MONETIZE_LINKS, 'name' => 'Inline Links' ),
		);
	}
	return $arr;
}

function wpautoc_add_monetization( $campaign_id, $type, $settings ) {
	global $wpdb;
	$sql = "INSERT INTO {$wpdb->prefix}autoc_campaign_monetization (campaign_id, type, settings ) VALUES (".intval( $campaign_id ).", ".intval( $type ).", '$settings')";
	// die($sql);
	return $wpdb->query( $sql );
}

function wpautoc_update_monetization( $monetize_id, $settings ) {
	global $wpdb;
	$sql = "UPDATE {$wpdb->prefix}autoc_campaign_monetization SET settings = '".wpautoc_escape_text( $settings )."' WHERE id = ".intval( $monetize_id );
	return $wpdb->query( $sql );
}

function wpautoc_delete_monetization( $monetize_id ) {
	global $wpdb;
	$sql = "DELETE FROM {$wpdb->prefix}autoc_campaign_monetization WHERE id = ".intval( $monetize_id );
	return $wpdb->query( $sql );
}

function wpautoc_get_banner_positions() {
	return array(
		array( 'label' => 'Beginning of Post', 'value' => '1'),
		array( 'label' => 'End of Post', 'value' => '2'),
		array( 'label' => 'Middle of Post', 'value' => '3'),
		array( 'label' => 'After paragraph x', 'value' => '4')
	);
}

function wpautoc_get_banner_float() {
	return array(
		array( 'label' => 'None', 'value' => '1'),
		array( 'label' => 'Left', 'value' => '2'),
		array( 'label' => 'Right', 'value' => '3')
	);
}

/* Leads / Optins */
function wpautoc_insert_optin( $email, $name, $post_id ) {
	global $wpdb;
	// $created_at = date( 'Y-m-d' );
	if( empty( $name ) )
		$name = '';

	$res = $wpdb->insert(
	    $wpdb->prefix.'autoc_optins',
	    array(
	        'email' => $email,
	        'name' => $name,
	        'post_id' => $post_id,
	        'created_at' => current_time( 'mysql' )
	    ),
	    array(
	        '%s',
	        '%s',
	        '%d',
	        '%s'
	    )
	);
	if ( $res )
	    return $wpdb->insert_id;
	else
		return 0;
}

function wpautoc_get_leads( $page = 0, $results_per_page = 30, $search = false ) {
	global $wpdb;
	if ($page) $page--;
	$start = $page*$results_per_page;
	if( $search ) {
		return $wpdb->get_results( "SELECT *, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}autoc_optins WHERE name like '%$search%' OR email like '%$search%' LIMIT $start,$results_per_page" );
	}

	return $wpdb->get_results( "SELECT *, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}autoc_optins LIMIT $start,$results_per_page" );
}

function wpautoc_get_total_leads( $search = false ) {
	global $wpdb;
	if( $search )
		return $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}autoc_optins WHERE name like '%$search%' OR email like '%$search%'" );
	return $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}autoc_optins" );
}

function wpautoc_get_all_leads( ) {
	global $wpdb;
	return $wpdb->get_results( "SELECT email, name, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}autoc_optins", 'ARRAY_A' );
}

function wpautoc_delete_lead( $lead_id ) {
	global $wpdb;
	if( $lead_id )
		$wpdb->query( "DELETE FROM {$wpdb->prefix}autoc_optins WHERE id = ".intval( $lead_id ) );
}
?>