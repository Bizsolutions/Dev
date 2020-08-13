<?php

// possible add. http://www.sooperarticles.com/search/?t=contents&s=fun&p=2

function wpr5_article_post($keyword,$num,$start,$template) {
	global $wpdb,$wpr_table_templates;

	if(strlen($keyword) < 4) {
		$return["error"] = __("Keywords have to be 4 characters or more for article searches. ","wprobot");	
		return $return;			
	}

	$page = $start / 15;
	$page = (string) $page; 
	$page = explode(".", $page);	
	$page=(int)$page[0];	
	$page++;	

	if($page == 0) {$page = 1;}
	$prep = floor($start / 15);
	$numb = $start - $prep * 15;
	
	$keyword2 = urlencode($keyword);	
	//$keyword2 = urlencode(str_replace( "+","-",$keyword ));	
	$search_url = "http://www.articlesfactory.com/search/$keyword2/page$page.html";
	
	if($_GET["debug"] == 1) {echo "<br>SEARCH URL: $search_url";}

	if($_GET["debug"] == 1) {echo "<br>start $start numb $numb prep $prep";}	
	
	$blist[] = "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)";
    $blist[] = "Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)";
    $blist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; .NET CLR 1.1.4322; Windows-Media-Player/10.00.00.3990; InfoPath.2";
    $blist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; Dealio Deskball 3.0)";
    $blist[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; NeosBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
    $ua = $blist[array_rand($blist)];		

	// make the cURL request to $search_url
	if ( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
			if($proxy != "") {
				//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1); 
				curl_setopt($ch, CURLOPT_PROXY, $proxy);
				if($proxyuser) {curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyuser);}
				if($proxytype == "socks") {curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);}
			}			
		curl_setopt($ch, CURLOPT_URL,$search_url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 45);
		$html = curl_exec($ch);
		if (!$html) {
			$return["error"] = __("cURL Error Number ","wprobot").curl_errno($ch).": ".curl_error($ch);	
			return $return;
		}		
		curl_close($ch);
	} else { 				
		$html = @file_get_contents($search_url);
		if (!$html) {
			$return["error"] = __("cURL is not installed on this server!","wprobot");	
			return $return;		
		}
	}	

	// parse the html into a DOMDocument  

	$dom = new DOMDocument();
	@$dom->loadHTML($html);

	// Grab Product Links  

	$xpath = new DOMXPath($dom);
	$paras = $xpath->query("//div[@class='h2']/a[@class='h2']");
	
	$x = 0;
	$end = $numb + $num;
	
		if($paras->length == 0) {
			$posts["error"] = __("No (more) articles found.","wprobot");	
			return $posts;		
		}	
	
	if($end > $paras->length) { $end = $paras->length;}
	for ($i = $numb;  $i < $end; $i++ ) {
		if($_GET["debug"] == 1) {echo "<br>REPEAT - i $i numb $numb end $end";}	
		$para = $paras->item($i);
	
		if(empty($para)) {
			$posts["error"] = __("No (more) articles found.","wprobot");	
			//print_r($posts);
			return $posts;		
		} else {
		
			$target_url = $para->getAttribute('href');
			if($_GET["debug"] == 1) {echo "<br>target_url URL: $target_url";}
			// make the cURL request to $search_url
			if ( function_exists('curl_init') ) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_USERAGENT, $ua);
				if($proxy != "") {
					//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1); 
					curl_setopt($ch, CURLOPT_PROXY, $proxy);
					if($proxyuser) {curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyuser);}
					if($proxytype == "socks") {curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);}
				}					
				curl_setopt($ch, CURLOPT_URL,$target_url);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 45);
				$html = curl_exec($ch);
				if (!$html) {
					$return["error"] = __("cURL Error Number ","wprobot").curl_errno($ch).": ".curl_error($ch);	
					return $return;
				}		
				curl_close($ch);
			} else { 				
				$html = @file_get_contents($target_url);
				if (!$html) {
					$return["error"] = __("cURL is not installed on this server!","wprobot");	
					return $return;		
				}
			}
				//if($_GET["debug"] == 1) {echo "<br>target_url URL: $html";}
			// parse the html into a DOMDocument  

			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			
			// Grab Article Title 			
			$xpath1 = new DOMXPath($dom);
			$paras1 = $xpath1->query("//h1[@class='h2']");
			$para1 = $paras1->item(0);
			$title = $para1->textContent;
			
			if($_GET["debug"] == 1) {echo "<br>title: $title";}
			
				if (empty($title)) {
					$return["error"] = __("Skipping article. No title found. ","wprobot");	
					return $return;
				}				
			
			// Grab Article	
			$xpath2 = new DOMXPath($dom);
			$paras2 = $xpath2->query("//div[@class='txt-main']/p"); 
	
			$string = "";
			for ($r = 1;  $r < $paras2->length; $r++ ) {
				$para2 = $paras2->item($r);		
				$string .= $dom->saveXml($para2);	
			}	
		
			$string = explode('<p class="txt-small-regular', $string);
			$string = $string[0];
		
			if (empty($string)) {
			
				// Grab Article	
				$xpath2 = new DOMXPath($dom);
				$paras2 = $xpath2->query("//div[@class='txt-main']"); 			
				$para2 = $paras2->item(1);	
//print_r($para2);				
				//$string = $dom->saveXml($para2);	

				$string = $para2->textContent;
				
				/*$string = str_replace("var dc_adprod=’ADL’;", "", $string);
				$string = str_replace("var dc_open_new_win = ‘yes’;", "", $string);
				$string = str_replace("var dc_underlineType = ‘solid’;", "", $string);
				$string = str_replace("var dc_AdLinkColor = ‘#990000’;", "", $string);
				$string = str_replace("medianet_width=\’336\′; medianet_height= \‘280\’; medianet_crid=\’124211550\′;", "", $string);
				$string = str_replace("medianet_width=", "", $string);				
				$string = str_replace("medianet_height=", "", $string);				
				$string = str_replace("medianet_crid=", "", $string);		*/

				$string = explode('medianet_crid=', $string);
				$string = $string[1];				
				
				$string = explode('var dc_AdLinkColor', $string);
				$string = $string[0];
				
				$string = str_replace("124211550", "", $string);						
				$string = str_replace("var dc_PublisherID = 3523;", "", $string);
				$string = str_replace("var dc_UnitID = 14;", "", $string);				
				
				$string = trim($string);
/*
				if($_GET["debug"] == 1) {echo "<br>ffff: $string";}	

//if($_GET["debug"] == 1) {echo "<br>string: $string";}		
				$string = explode('<table width="336" height="280" border="0">', $string);
				$string = $string[1];
if($_GET["debug"] == 1) {echo "<br>string1: $string";}					
				$string = explode('<p class="txt-small-regular', $string);
				$string = $string[0];
if($_GET["debug"] == 1) {echo "<br>string2: $string";}					
				$string = explode('</table>', $string);
				$string = $string[1];				
				
if($_GET["debug"] == 1) {echo "<br>string3: $string";}	
die();
*/
				if (empty($string)) {
					$return["error"] = __("Video content skipped. ","wprobot");	
					return $return;
				}
			}	
	
			$articlebody = $string . ' ';	

			// Grab Ressource Box	

			$xpath3 = new DOMXPath($dom);
			$paras3 = $xpath3->query("//div[@class='txt-main']/div/p']");		//$para = $paras->item(0);		
			
			$ressourcetext = "";
			for ($y = 0;  $y < $paras3->length; $y++ ) {  //$paras->length
				$para3 = $paras3->item($y);
				$ressourcetext .= $dom->saveXml($para3);	
			}	

			$title = utf8_decode($title);
			
			// Split into Pages
	
			$post = $template;
			$post = str_replace("{article}", $articlebody, $post);			
			$post = str_replace("{authortext}", $ressourcetext, $post);	
			$noqkeyword = str_replace('"', '', $keyword);
			$post = str_replace("{keyword}", $noqkeyword, $post);
			$post = str_replace("{Keyword}", ucwords($noqkeyword), $post);				
			$post = str_replace("{title}", $title, $post);	
			$post = str_replace("{url}", $target_url, $post);
					
			$posts[$x]["source"] = "articles";				
			$posts[$x]["unique"] = $target_url;
			$posts[$x]["title"] = $title;
			$posts[$x]["content"] = $post;				
			$x++;
			
		}	
		if($_GET["debug"] == 1) {echo "<br>next repeat ... ";}
	}	
	if($_GET["debug"] == 1) {echo "<br>end of article module. returning...";}
	return $posts;
}

?>