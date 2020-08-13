<?php
// include("db.php");
// function date_sort($a, $b) {
//     return strtotime($a) - strtotime($b);
// }
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


include ("functions.php");

// Settings
$host = "pvl.ehawaii.gov";
$search_name = "ahuja";
$url = "https://pvl.ehawaii.gov/pvlsearch/captcha/index.html";
$catpcha_evaluate_url = "https://pvl.ehawaii.gov/pvlsearch/api/evaluateCaptcha/byName/".$search_name."?_licid=&_name=&_a=byName&g-recaptcha-response=";
$ajax_url = "https://pvl.ehawaii.gov/pvlsearch/api/licenses/".$search_name."/licenseNameLocationsByName.json";
$ajax_referer = "https://pvl.ehawaii.gov/pvlsearch/name/".$search_name;

$header = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
"Accept-Encoding:gzip, deflate, br",
"Accept-Language:en-US,en;q=0.9,hi;q=0.8",
"Cache-Control:max-age=0",
"Host:".$host,
"Connection:keep-alive",
"Upgrade-Insecure-Requests:1",
"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");

# Add these values
$api_key = 'e1335bb3fef6ce8616db3ce4ad1d0138';
$site_key = '6LflvxYTAAAAAFrbZAe6u4CqRN8S_K2YOvIfBDbN';

// Captcha Solving Part
$captcha = "https://2captcha.com/in.php?key=" . $api_key . "&method=userrecaptcha&googlekey=" . $site_key . "&pageurl=" .urlencode( $url) . "&json=1";
$json = file_get_contents($captcha);
$encode = json_decode($json, TRUE);

$id = $encode['request'];
if (is_numeric($id)) 
{
	$cookiefile = "D:\Dropbox\www\scripts\dot.gov\Recaptcha\/".time()."_pvl.ehawaii.gov.txt";
	$i = 1;
	$g_recaptcha_response = "";
	do{
		$html = scurl($url, $header, $cookiefile);
		echo "Attempt ". $i."\n";

		sleep(28);
		$req = "http://2captcha.com/res.php?key=" . $api_key . "&action=get&id=" . $id . "&json=1";
		$res_json = json_decode(file_get_contents($req), TRUE);
		$g_recaptcha_response = $res_json['request'];
		$i++;
	}while( $g_recaptcha_response == "CAPCHA_NOT_READY" ); //
		//}while( strlen($g_recaptcha_response) < 111 ); //"CAPCHA_NOT_READY"
	

	// Validate Captcha On the given webpage
	$header_2 = $header;
	$header_2[] = "Referer:https://pvl.ehawaii.gov/pvlsearch/captcha/index.html";

	$validate_captcha = scurl($catpcha_evaluate_url.urlencode($g_recaptcha_response), $header_2,$cookiefile,"",true, true);

	// parsing cookies for the ajax request, in other case it will not work
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $validate_captcha, $matches);
	$cookies = array();
	foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
	}
	
	// Setting the ajax request headers to get the search results from the page
   $header_3 = array("Accept: application/json, text/javascript, */*; q=0.01",
	"Accept-Encoding:gzip, deflate, br",
	"Accept-Language:en-US,en;q=0.9,hi;q=0.8",
	"Cache-Control:max-age=0",
	"Host:".$host,
	"Connection:keep-alive",
	"Upgrade-Insecure-Requests:1",
	"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");
   $header_3[] = "Referer:".$ajax_referer;
   $header_3[] = "X-Requested-With: XMLHttpRequest";

   $cookie_text = "Cookie:";

   // Setting the ajax request cookies
   foreach ($cookies as $key => $value) 
   {
		$cookie_text .= $key."=".$value.";";
   }
   $header_3[] = $cookie_text;

   print_r($header_3);

   // Making the ajax requestt for the results
	$licence_list = scurl($ajax_url, $header_3,$cookiefile,"",true);

	$result_json = json_decode($licence_list);

	print_r($licence_list);

	print_r($result_json->value->items);
  }
		

		
?>