<?php

function wpr5_rewrite($content, $service, $options) {

	$keyword = "";
	if($service == "tbs") {
		$content = wpr5_tbs_rewrite($content,$options["options"]["thebestspinner"]["options"]["tbs_email"]["value"],$options["options"]["thebestspinner"]["options"]["tbs_pw"]["value"],"No",$options["options"]["thebestspinner"]["options"]["tbs_quality"]["value"], $keyword, $options["options"]["thebestspinner"]["options"]["protected_words"]["value"]);
	} elseif($service == "sc") {
		$content = wpr5_sc_rewrite($content, $options["options"]["spinnerchief"]["options"]["sc_email"]["value"], $options["options"]["spinnerchief"]["options"]["sc_pw"]["value"], $options["options"]["spinnerchief"]["options"]["sc_quality"]["value"], $keyword, $options["options"]["spinnerchief"]["options"]["protected_words"]["value"], $options["options"]["spinnerchief"]["options"]["sc_port"]["value"], $options["options"]["spinnerchief"]["options"]["sc_thesaurus"]["value"]);
	} elseif($service == "spinchimp") {
		$content = wpr5_spinchimp_spin($options["options"]["spinchimp"]["options"]["schimp_email"]["value"], $options["options"]["spinchimp"]["options"]["appid"]["value"], $content, $options["options"]["spinchimp"]["options"]["schimp_quality"]["value"], $options["options"]["spinchimp"]["options"]["protected_words"]["value"],$options["options"]["spinchimp"]["options"]["schimp_posmatch"]["value"], 1);		
	} elseif($service == "sr") {
		$content = wpr5_sr_rewrite($content, $options["options"]["spinrewriter"]["options"]["sr_email"]["value"], $options["options"]["spinrewriter"]["options"]["appid"]["value"], $options["options"]["spinrewriter"]["options"]["sr_quality"]["value"], $keyword, $options["options"]["spinrewriter"]["options"]["protected_words"]["value"]);		
	} elseif($service == "wai") {
		$content = wpr5_wai_rewrite($content, $keyword, $options["options"]["wordai"]);		
	}
	
	if(is_array($content) && !empty($content["error"])) {
		return array("error" => $content["error"]);	
	}
	
	if(empty($content)) {
		return array("error" => "Rewriting failed.");
	}

	return $content;
}

// WORDAI

function wpr5_wai_rewrite($article, $keyword, $options) {

	$protected_words = "";//$options["general"]["options"]["protected_words"]["value"];
	//$options = $options["options"]["wordai"]["options"];

	$user = $options["options"]["wai_rewrite_email"]["value"];
	$pw = $options["options"]["wai_rewrite_pw"]["value"];
	$quality = $options["options"]["wai_quality"]["value"];		
	$sentence = $options["options"]["wai_sentence"]["value"];		
	$paragraph = $options["options"]["wai_paragraph"]["value"];		
	$nooriginal = $options["options"]["wai_nooriginal"]["value"];		
	
	if($sentence == 1) {$sentence = "on";}
	if($paragraph == 1) {$paragraph = "on";}
	if($nooriginal == 1) {$nooriginal = "on";}
	$api_ver = $options["options"]["wai_api_ver"]["value"];
	
   if(isset($article) && isset($quality) && isset($user) && isset($pw)) {

      $article = urlencode($article);

	  if($api_ver == "regular") {
		$ch = curl_init('http://wordai.com/users/regular-api.php');	
		if($quality == "Regular") {
			$quality = 0;
		} elseif($quality == "Readable") {
			$quality = 50;
		} else {
			$quality = 100;
		}		
	  } else {
		$ch = curl_init('http://wordai.com/users/turing-api.php');	  
	  }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_POST, 1);
      curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$article&quality=$quality&email=$user&pass=$pw&returnspin=true&sentence=$sentence&paragraph=$paragraph&nooriginal=$nooriginal&protected=$keyword,".urlencode($protected)."");
      $result = curl_exec($ch);
      curl_close ($ch);

		$result2 = json_decode($result, true);
		
		if(is_array($result2) && !empty($result2["error"])) {
			$return["error"] = "Rewrite Error: ".strip_tags($result2["error"]);	
			return $return;			
		}

		if (strpos($result, "Error ") !== false) {
			$return["error"] = "Rewrite Error: ".strip_tags($result);	
			return $return;	
		}	  
		
		if(is_array($result2) && !empty($result2["text"])) {
			return $result2["text"];			
		}		
	  
      return $result;

   } else {
		$return["error"] = "API Information missing.";	
		return $return;	
   }

}

function wpr5_spinchimp_GlobalSpin($email,$apiKey, $text, $quality, $protectedTerms, $posmatch, $rewrite) {

	//Check Inputs
	if (!isset($email) || trim($email)=== '') return 'No email specified';
	if (!isset($apiKey) || trim($apiKey)=== '') return 'No APIKey specified';
	if (!isset($text) || trim($text)=== '') return "";

	//Add paramaters
	$paramaters = array();
	$paramaters['email'] = $email;
	$paramaters['apiKey'] = $apiKey;
	$paramaters['aid'] = "WPRobot"; 
	if (isset($quality) && trim($quality)=== '') 
		$paramaters['quality'] = $quality;
	if (isset($posmatch)) 
		$paramaters['posmatch'] = $posmatch;
	if (isset($rewrite)) 
		$paramaters['rewrite'] = $rewrite;
	if (isset($protectedTerms) && trim($protectedTerms)=== '') 
		$paramaters['protectedterms'] = $protectedTerms;		

	$qs = wpr5_spinchimp_buildQueryString($paramaters);
	$result = wpr5_spinchimp_makeApiRequest('http://api.spinchimp.com/','GlobalSpin',$qs,$text);
	return $result;
}

function wpr5_spinchimp_buildQueryString($paramaters) {
	$data = '';
	$firstparam = true;
	foreach ($paramaters as $key => $value) {
		if ($firstparam) $firstparam = false;
		else $data .= '&';
		$data .= $key . '=' . urlencode($value);
	}
	return $data;
}

function wpr5_spinchimp_makeApiRequest($url, $command, $querystring, $text) {
	$req = curl_init();
	curl_setopt($req, CURLOPT_URL, 'http://api.spinchimp.com/' . $command . '?' . $querystring);
	curl_setopt($req,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($req, CURLOPT_POST, true);
	curl_setopt($req, CURLOPT_POSTFIELDS, $text);
	$result = trim(curl_exec($req));
	curl_close($req);
	return $result;
}

function wpr5_spinchimp_spin($email,$apiKey, $text, $quality, $protectedTerms, $posmatch, $rewrite) {

	$result = wpr5_spinchimp_GlobalSpin($email,$apiKey, $text, $quality, $protectedTerms, $posmatch, $rewrite);

	if (strpos($result, "Failed:") !== false) {
		$return["error"] = "Rewrite Error: ".strip_tags($result);	
		return $return;		
	}
	
	return $result;
}

// SPIN REWRITER
function wpr5_sr_rewrite($article, $user, $pw, $quality, $keyword, $protected) {

	$data = array();
	$data['email_address'] = $user;			// your Spin Rewriter email address goes here
	$data['api_key'] = $pw;	// your unique Spin Rewriter API key goes here
	$data['action'] = "unique_variation";						// possible values: 'api_quota', 'text_with_spintax', 'unique_variation', 'unique_variation_from_spintax'
	$data['text'] = $article;
	$protected = explode(",", $protected);
	$prot = "";
	foreach($protected as $pt) {$prot .= trim($pt)."\n";}
	$prot .= $keyword;
	$data['protected_terms'] = $prot;		// protected terms: John, Douglas Adams, then
	$data['confidence_level'] = $quality;							// possible values: 'low', 'medium' (default value), 'high'
	$data['nested_spintax'] = "true";							// possible values: 'false' (default value), 'true'
	
	$data_raw = "";
	foreach ($data as $key => $value){
		$data_raw = $data_raw . $key . "=" . urlencode($value) . "&";
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.spinrewriter.com/action/api");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_raw);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$api_response = trim(curl_exec($ch));
	curl_close($ch);	
	$resp = json_decode($api_response, true);
	
	if($resp["status"] == "OK") {
		return $resp["response"];	
	} elseif($resp["status"] == "ERROR") {
		$return["error"] = $resp["response"];	
		return $return;			
	} else {
		$return["error"] = "No response received";	
		return $return;				
	}
}

// SpinnerChief
function wpr5_sc_rewrite($article, $user, $pw, $quality, $keyword, $protected, $port, $thesaurus){
	
	if(empty($port)) {$port = 9001;}	
	if(empty($thesaurus)) {$thesaurus = "English";}	
	$url = "http://api.spinnerchief.com:$port/apikey=ca01285820b24905b&username=$user&password=$pw&spintype=1&protecthtml=0&spinhtml=0&original=0&spinfreq=1&wordquality=$quality&thesaurus=$thesaurus&tagprotect=[]&protectwords=$keyword,".urlencode($protected)."";

	$article = base64_encode($article);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_PORT , $port);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $article);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 80);	
	$html = trim(curl_exec($ch));
	curl_close($ch);

	if (strpos($html, "error=") !== false) {
		$return["error"] = "Rewrite Error: ".strip_tags($html);	
		return $return;		
	}
	
	$html = base64_decode($html);
	
	return $html;	
}

// TheBestSpinner
function wpr5_tbs_request($url, $data, &$info){

	$fdata = "";
	foreach($data as $key => $val){
		$fdata .= "$key=" . urlencode($val) . "&";
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 80);	
	$html = trim(curl_exec($ch));
	curl_close($ch);
	return $html;
}

function wpr5_tbs_rewrite($text,$email,$password,$spinsave = "No",$quality = 1, $keyword, $protected) {

	$data = array();
	$data['action'] = 'authenticate';
	$data['apikey'] = 'wprobot4b8ff4a5ef0d3';	
	$data['format'] = 'php';
	$data['username'] = $email;
	$data['password'] = $password;
	
	$output = unserialize(wpr5_tbs_request('http://thebestspinner.com/api.php', $data, $info));
	if($output['success']=='true'){

		$session = $output['session'];

		$data = array();
		$data['session'] = $session;
		$data['apikey'] = 'wprobot4b8ff4a5ef0d3';
		$data['format'] = 'php';
		$data['text'] = $text;
		$data['action'] = 'replaceEveryonesFavorites';
		$data['maxsyns'] = '3';
		$data['quality'] = $quality;
		$data['protectedterms'] = $keyword.",".urlencode($protected);
		
		$output = wpr5_tbs_request('http://thebestspinner.com/api.php', $data, $info);
		$output = unserialize($output);

		if($output['success']=='true'){
			if($spinsave == "Yes") {		
				return stripslashes(str_replace("\r", "<br>", $output['output']));			
			} else {
				
				$newtext = stripslashes(str_replace("\r", "<br>", $output['output']));

				$data = array();
				$data['session'] = $session;
				$data['apikey'] = 'wprobot4b8ff4a5ef0d3';			
				$data['format'] = 'php';
				$data['text'] = $newtext;
				$data['action'] = 'randomSpin';
				
				$output = wpr5_tbs_request('http://thebestspinner.com/api.php', $data, $info);
				$output = unserialize($output);		

				if($output['success']=='true'){	
					return stripslashes(str_replace("\r", "<br>", $output['output']));
				} else {
					if(empty($output["error"])) {$output["error"] = "TBS request has timed out, no response received.";}
					$return["error"] = __("Rewrite Error: ","wprobot").$output["error"];	
					return $return;				
				}	
			}
		} else {
			if(empty($output["error"])) {$output["error"] = "TBS request has timed out, no response received.";}			
			$return["error"] = __("Rewrite Error: ","wprobot").$output["error"];	
			return $return;				
		}
	} else {
		if(empty($output["error"])) {$output["error"] = "TBS request has timed out, no response received.";}
		$return["error"] = __("Rewrite Error: ","wprobot").$output["error"];	
		return $return;			
	}
}

?>