<?php
$db = mysqli_connect("192.64.116.132", "topmovingreviews_dotgov", "jT5n8B#*PS&c") or die(mysqli_error());
mysqli_select_db($db, "topmovin_mymovingreviews");

// $db = mysqli_connect("localhost", "root", "") or die(mysqli_error($db));
// mysqli_select_db($db, "dotgov");

// https://topmovingreviews.com:2083/
// user: topmovingreviews
// pass: CJKHEU^YTYX&#%URFDKEUT(

function scurl($url, $header, $cookiefile, $data = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_ENCODING , "");
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
	curl_setopt($ch, CURLOPT_VERBOSE, 0); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if($data != null){
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data);	
	}
	$html = curl_exec($ch);
	$result['HTML'] = $html;
	$result['INF'] = curl_getinfo($ch);
	$result['ERR'] = curl_error($ch);
	curl_close($ch);
	return  $result;
}

function get_dom($html){
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom -> loadHTML($html);
	libxml_clear_errors();
	$dom = new DOMXPath($dom);
	return $dom;
}