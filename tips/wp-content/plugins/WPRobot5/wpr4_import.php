<?php

function wpr5_import_previous_settings($import_history = 0) {
	
	$wpr5_modulearray = wpr5_load_options_remote(1);
	
	if(!is_array($wpr5_modulearray) || !empty($wpr5_modulearray["error"])) {
		return array("error" => "Error: options could not be loaded from the WP Robot server. Please contact support. " . $wpr5_modulearray["error"]);
	}
		
	$old_options = unserialize(get_option("wpr_options"));	

	$options = wpr5_get_options();

	if(empty($old_options)) {
		return array("error" => "No WP Robot 4 settings found.");
	}
	
	if(!empty($old_options["wpr_aa_affkey"])) {
		$options["options"]["amazon"] = $wpr5_modulearray["amazon"];
		$options["options"]["amazon"]["disabled"] = 0;
		
		$options["options"]["amazon"]["options"]["affid"]["value"] = $old_options["wpr_aa_affkey"];
		$options["options"]["amazon"]["options"]["public_key"]["value"] = $old_options["wpr_aa_apikey"];
		$options["options"]["amazon"]["options"]["private_key"]["value"] = $old_options["wpr_aa_secretkey"];
		if($old_options["wpr_aa_site"] == "us") {$old_options["wpr_aa_site"] = "com";}
		$options["options"]["amazon"]["options"]["region"]["value"] = $old_options["wpr_aa_site"];
	}
	
	if(!empty($old_options["wpr_avantlink_appkey"])) {
		$options["options"]["avantlink"] = $wpr5_modulearray["avantlink"];
		$options["options"]["avantlink"]["disabled"] = 0;
		
		$options["options"]["avantlink"]["options"]["appid"]["value"] = $old_options["wpr_avantlink_appkey"];
		$options["options"]["avantlink"]["options"]["websiteid"]["value"] = $old_options["wpr_avantlink_website"];
		$options["options"]["avantlink"]["options"]["lowprice"]["value"] = $old_options["wpr_avantlink_lowprice"];
		$options["options"]["avantlink"]["options"]["highprice"]["value"] = $old_options["wpr_avantlink_highprice"];
		$options["options"]["avantlink"]["options"]["advertisers"]["value"] = $old_options["wpr_avantlink_advertisers"];
	}
	
	if(!empty($old_options["wpr_commissionjunction_appkey"])) {
		$options["options"]["commissionjunction"] = $wpr5_modulearray["commissionjunction"];
		$options["options"]["commissionjunction"]["disabled"] = 0;
		
		$options["options"]["commissionjunction"]["options"]["appid"]["value"] = $old_options["wpr_commissionjunction_appkey"];
		$options["options"]["commissionjunction"]["options"]["websiteid"]["value"] = $old_options["wpr_commissionjunction_webid"];
		$options["options"]["commissionjunction"]["options"]["lowprice"]["value"] = $old_options["wpr_commissionjunction_lowprice"];
		$options["options"]["commissionjunction"]["options"]["highprice"]["value"] = $old_options["wpr_commissionjunction_highprice"];
		$options["options"]["commissionjunction"]["options"]["advertisers"]["value"] = $old_options["wpr_commissionjunction_advertisers"];
		$options["options"]["commissionjunction"]["options"]["sort"]["value"] = $old_options["wpr_commissionjunction_sortby"];
		$options["options"]["commissionjunction"]["options"]["sortorder"]["value"] = $old_options["wpr_commissionjunction_sortorder"];
	}
	
	if(!empty($old_options["wpr_eb_affkey"])) {
		$options["options"]["ebay"] = $wpr5_modulearray["ebay"];
		$options["options"]["ebay"]["disabled"] = 0;
		
		$options["options"]["ebay"]["options"]["appid"]["value"] = $old_options["wpr_eb_affkey"];
		$options["options"]["ebay"]["options"]["country"]["value"] = $old_options["wpr_eb_country"];
		$options["options"]["ebay"]["options"]["sort"]["value"] = $old_options["wpr_eb_sortby"];
	}
	
	if(!empty($old_options["wpr_eventful_appkey"])) {
		$options["options"]["eventful"] = $wpr5_modulearray["eventful"];
		$options["options"]["eventful"]["disabled"] = 0;
		
		$options["options"]["eventful"]["options"]["appid"]["value"] = $old_options["wpr_eventful_appkey"];
		$options["options"]["eventful"]["options"]["cat"]["value"] = $old_options["wpr_eventful_cat"];
		$options["options"]["eventful"]["options"]["location"]["value"] = $old_options["wpr_eventful_location"];
		$options["options"]["eventful"]["options"]["sort"]["value"] = $old_options["wpr_eventful_sort"];
	}	

	if(!empty($old_options["wpr_fl_apikey"])) {
		$options["options"]["flickr"] = $wpr5_modulearray["flickr"];
		$options["options"]["flickr"]["disabled"] = 0;
		
		$options["options"]["flickr"]["options"]["appid"]["value"] = $old_options["wpr_fl_apikey"];
		$options["options"]["flickr"]["options"]["license"]["value"] = $old_options["wpr_fl_license"];
		$options["options"]["flickr"]["options"]["sort"]["value"] = $old_options["wpr_fl_sort"];
	}	
	
	if(!empty($old_options["wpr_linkshare_appkey"])) {
		$options["options"]["linkshare"] = $wpr5_modulearray["linkshare"];
		$options["options"]["linkshare"]["disabled"] = 0;
		
		$options["options"]["linkshare"]["options"]["appid"]["value"] = $old_options["wpr_linkshare_appkey"];
		$options["options"]["linkshare"]["options"]["merchant"]["value"] = $old_options["wpr_linkshare_merchant"];
		$options["options"]["linkshare"]["options"]["sort"]["value"] = $old_options["wpr_linkshare_sort"];
	}	
	
	if(!empty($old_options["wpr_oodle_appkey"])) {
		$options["options"]["oodle"] = $wpr5_modulearray["oodle"];
		$options["options"]["oodle"]["disabled"] = 0;
		
		$options["options"]["oodle"]["options"]["appid"]["value"] = $old_options["wpr_oodle_appkey"];
		$options["options"]["oodle"]["options"]["lang"]["value"] = $old_options["wpr_oodle_lang"];
		$options["options"]["oodle"]["options"]["location"]["value"] = $old_options["wpr_oodle_loc"];
		$options["options"]["oodle"]["options"]["cat"]["value"] = $old_options["wpr_oodle_cat"];
		$options["options"]["oodle"]["options"]["radius"]["value"] = $old_options["wpr_oodle_radius"];
	}				
		
	if(!empty($old_options["wpr_shopzilla_appkey"])) {
		$options["options"]["shopzilla"] = $wpr5_modulearray["shopzilla"];
		$options["options"]["shopzilla"]["disabled"] = 0;
		
		$options["options"]["shopzilla"]["options"]["appid"]["value"] = $old_options["wpr_shopzilla_appkey"];
		$options["options"]["shopzilla"]["options"]["pubid"]["value"] = $old_options["wpr_shopzilla_pubkey"];
		$options["options"]["shopzilla"]["options"]["offers"]["value"] = $old_options["wpr_shopzilla_offers"];
		$options["options"]["shopzilla"]["options"]["lowprice"]["value"] = $old_options["wpr_shopzilla_minprice"];
		$options["options"]["shopzilla"]["options"]["highprice"]["value"] = $old_options["wpr_shopzilla_maxprice"];
		$options["options"]["shopzilla"]["options"]["sort"]["value"] = $old_options["wpr_shopzilla_sort"];
	}			
		
	if(!empty($old_options["wpr_yt_api"])) {
		$options["options"]["youtube"] = $wpr5_modulearray["youtube"];
		$options["options"]["youtube"]["disabled"] = 0;
		
		$options["options"]["youtube"]["options"]["appid"]["value"] = $old_options["wpr_yt_api"];
		$options["options"]["youtube"]["options"]["lang"]["value"] = $old_options["wpr_yt_lang"];
		$options["options"]["youtube"]["options"]["safesearch"]["value"] = $old_options["wpr_yt_safe"];
		$options["options"]["youtube"]["options"]["sort"]["value"] = $old_options["wpr_yt_sort"];
		$options["options"]["youtube"]["options"]["width"]["value"] = $old_options["wpr_yt_width"];
		$options["options"]["youtube"]["options"]["height"]["value"] = $old_options["wpr_yt_height"];
	}		
		
	if(!empty($old_options["wpr_ab_email"])) {
		$options["options"]["articlebuilder"] = $wpr5_modulearray["articlebuilder"];
		$options["options"]["articlebuilder"]["disabled"] = 0;
		
		$options["options"]["articlebuilder"]["options"]["email"]["value"] = $old_options["wpr_ab_email"];
		$options["options"]["articlebuilder"]["options"]["pw"]["value"] = $old_options["wpr_ab_pw"];
		$options["options"]["articlebuilder"]["options"]["superspun"]["value"] = $old_options["wpr_ab_superspun"];
		$options["options"]["articlebuilder"]["options"]["length"]["value"] = $old_options["wpr_ab_wordcount"];
	}	

	if(!empty($old_options["wpr_bcs_email"])) {
		$options["options"]["bigcontentsearch"] = $wpr5_modulearray["bigcontentsearch"];
		$options["options"]["bigcontentsearch"]["disabled"] = 0;
		
		$options["options"]["bigcontentsearch"]["options"]["api_key"]["value"] = $old_options["wpr_bcs_email"];
		$options["options"]["bigcontentsearch"]["options"]["username"]["value"] = $old_options["wpr_bcs_pw"];
	}	
	
	wpr5_update_options($options);

	global $wpr_table_posts, $wpr5_table_posts;
	
	if($import_history == 1 && !empty($wpr_table_posts)) {

		global $wpdb;
		$wpdb->query(
			"INSERT INTO $wpr5_table_posts SELECT * FROM $wpr_table_posts;"
		);	
		
	}

	return $options;
}


?>