<?php

function scurl($url,$headers,$cookie,$data="", $debug=false, $return_headers = false)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	if($debug)
	{
		curl_setopt($ch, CURLOPT_VERBOSE, true);
	}

	if($return_headers)
	{
		curl_setopt($ch, CURLOPT_HEADER, 1);
	}
	
	$server_output = curl_exec ($ch);

	return $server_output;
}
?>