<?php

// possible add. http://www.sooperarticles.com/search/?t=contents&s=fun&p=2

function wpr5_article_post($keyword,$num,$start,$template) {
	global $wpdb,$wpr_table_templates;

	$page = $start / 15;
	$page = (string) $page; 
	$page = explode(".", $page);	
	$page=(int)$page[0];	
	$page++;	

	if($page == 0) {$page = 1;}
	$prep = floor($start / 15);
	$numb = $start - $prep * 15;
	
	$keyword2 = urlencode(str_replace( "+","-",$keyword ));	
	$search_url = "http://ezinemark.com/a/$keyword2/p-$page/";
	
	if($_GET["debug"] == 1) {echo "<br>SEARCH URL: $search_url";}

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
	$paras = $xpath->query("//div[@class='l_search']//h3/a");
	
	$x = 0;
	$end = $numb + $num;
	
		if($paras->length == 0) {
			$posts["error"] = __("No (more) articles found.","wprobot");	
			return $posts;		
		}	
	
	if($end > $paras->length) { $end = $paras->length;}
	for ($i = $numb;  $i < $end; $i++ ) {
	
		$para = $paras->item($i);
	
		if(empty($para)) {
			$posts["error"] = __("No (more) articles found.","wprobot");	
			//print_r($posts);
			return $posts;		
		} else {
		
			$target_url = $para->getAttribute('href');

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

			// parse the html into a DOMDocument  

			$dom = new DOMDocument();
			@$dom->loadHTML($html);
				
			// Grab Article Title 			
			$xpath1 = new DOMXPath($dom);
			$paras1 = $xpath1->query("//div[@class='d_ctitle']/h1");
			$para1 = $paras1->item(0);
			$title = $para1->textContent;		
			
				if (empty($title)) {
					$return["error"] = __("Video content skipped. ","wprobot");	
					return $return;
				}				
			
			// Grab Article	
			$xpath2 = new DOMXPath($dom);
			$paras2 = $xpath2->query("//div[@id='art_content']"); 
			$para2 = $paras2->item(0);		
			$string = $dom->saveXml($para2);

			$string = str_replace('<div class="KonaBody">', "", $string);	
			$string = str_replace("]]>", "", $string);
			$string = str_replace("]]&gt;", "", $string);
			$string = str_replace("&nbsp;", "", $string);	
			//$string = preg_replace('#<ul>(.*)</ul>#smiU', '', $string);			
			$string = preg_replace('#<div class="related_links">(.*)</ul></div>#smiU', '', $string);
			$string = strip_tags($string,'<p><strong><b><a><br>');			
			$string = str_replace("</div>", "", $string);	
			$string = str_replace("$", "$ ", $string); 
			$string = str_replace("<div>", "", $string);		

			$articlebody = $string . ' ';	

			// Grab Ressource Box	

			$xpath3 = new DOMXPath($dom);
			$paras3 = $xpath3->query("//div[@class='authorbox']/div[@class='rightbl']//p");		//$para = $paras->item(0);		
			
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
	}	
	return $posts;
}

?>