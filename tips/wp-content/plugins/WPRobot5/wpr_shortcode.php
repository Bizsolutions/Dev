<?php

function wpr5_ama_handler($atts, $content = null) {

	$asin = $atts["asin"];
	$region	= $atts["region"];
	require_once("api.class.php");	
	
	$api = new API_request;
	$req = array("amazon" => 1);
	//$sdet = array("amazon" => array("request" => "http://ecs.amazonaws.com/onca/xml", "json" => 0, "oauth" => 0, "selector" => "Item", "error" => "Error", "title" => "Title", "unique" => "ASIN", ));
	
	$surl = "http://webservices.amazon.{region}/onca/xml";
	
	// get option and replace region
	if(!empty($region)) {
		$surl = str_replace("{region}", $region, $surl);
	}	
	$surl = str_replace("{region}", "com", $surl);
	
	$sdet = array("amazon" => array("request" => $surl, "json" => 0, "oauth" => 0, "selector" => "Item", "error" => "Error", "title" => "Title", "unique" => "ASIN", ));
	$contents = $api->api_content_bulk($asin, $req, "", "", "", "", "no", $sdet); 	

	if(!empty($contents["amazon"][0]["error"])) {
		return $content;		
	} else {
		return $contents["amazon"][0]["content"];		
	}		
	return $content;	
}
add_shortcode('wpr5-amazon', 'wpr5_ama_handler' );

?>