<?php

		use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
		use Amazon\ProductAdvertisingAPI\v1\ApiException;
		use Amazon\ProductAdvertisingAPI\v1\Configuration;
		use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsRequest;
		use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResource;
		use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
		use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\ProductAdvertisingAPIClientException;

		require_once('paapi5-php-sdk/vendor/autoload.php'); // change path as needed

class API_request {

	var $title = "";
	var $unique = "";
	var $start = "";	
	var $num = "";	
	var $modulearray = "";
	var $sourceinfos = "";
	public $debug = 0;
	var $process_on_user_site = 0;	
	var $special_tags = array("IF","WHILE","ALL","SPEC");

	var $source_vals = array();
	
	function API_request() {
		global $wpr5_source_infos;
			
		$options = wpr5_get_options();
		$this->sourceinfos = $wpr5_source_infos;
		$this->modulearray = $options;		
	}
	
	function request_and_return_amazon_only($keyword,$counts = 55, $options_override = array()) {
	
		$module = "amazon";
		$options[$module] = $this->modulearray["options"][$module]["options"];
		$cat = ucwords($keyword);
		
		if(!empty($options_override)) {
			foreach($options[$module] as $name => $option) {					
				if(!empty($options_override[$module . "_" . $name]) || $options_override[$module . "_" . $name] === 0) {
					$options[$module][$name]["value"] = $options_override[$module . "_" . $name];
				}
			}
		}
		
		$sourcedetails = $this->get_request_url_remote_multiple(array("amazon" => $counts), $keyword,  $options);		
		$sourcedetails["amazon"]["request"] = str_replace("{start}", $start, $sourcedetails["amazon"]["request"]);	
		$sourcedetails["amazon"]["request"] = str_replace("{num}", $num, $sourcedetails["amazon"]["request"]);		
		
		//echo "RRRR<pre>";print_r($options[$module]);echo "</pre>";
		
		$stop = 1;
		$start = 1;
		$num = 10;	
		$itemarray = array();		
		while(count($itemarray) < $counts) {
			$stop++;
			$start = $start + 10;
			
			$xml = $this->api_content_request($sourcedetails["amazon"],$keyword,$num,$start,"amazon",$options["amazon"]);
			//echo "<pre>";print_r($xml);echo "</pre>";return;

			if(isset($xml["error"])) {
				echo '<div class="updated error"><p>'.__('Error: ', 'wprobot').esc_html($xml["error"]).'</p></div>';			
				break;
			} elseif(empty($xml["items"])) {
				echo '<div class="updated error"><p>'.__('Error: No content found.', 'wprobot').'</p></div>';			
				break;				
			} else {

				foreach($xml["items"] as $amaitem) {	

					$asin = $amaitem["asin"];
					$DetailPageURL = $amaitem["url"];
					$SalesRank = "";
					
					$LargeImage = $amaitem["images"][0]["large"]["url"];
					
					$EAN = $amaitem["eans"][0];		
					$UPC = $amaitem["upcs"][0];			
					$Color = $amaitem["color"];			
					$Brand = $amaitem["brand"];
					$Model = $amaitem["model"];
					$Title = $amaitem["title"];
					$ListPrice = $amaitem["offers"]["New"]["low"];

					$dimensions = "";

					$edreview = "";
	
					$feats = $amaitem["features"];$featarr = array();
					foreach($feats as $ft) {$featarr[] = (string) $ft;}
								
					$itemarray[$asin] = array(
						"DetailPageURL" => $DetailPageURL,
						"SalesRank" => $SalesRank,
						"Category" => $cat,
						"LargeImage" => $LargeImage,	
						"Color" => $Color,
						"EAN" => $EAN,
						"Desc" => $edreview,
						"UPC" => $UPC,
						"Brand" => $Brand,
						"Model" => $Model,
						"Title" => $Title,
						"ListPrice" => $ListPrice,
						"Dimensions" => $dimensions,				
						"Features" => $featarr,				
					);
				}				
			}			

			if($stop > 4) {break;}
		}	

		/*
			Missing: EditorialReviews, ImageSets	
		*/	
		//echo "TTT<pre>";print_r($itemarray);echo "</pre>";
		
		return $itemarray;
	}	
	
	/*
		Mass Content Request
		- To schedule much content from a specific (or mixed) sources, no page templates, standard module templates
	*/		
	// $counts = array("amazon" => num, "yahooanswers" => 15, "youtube" => 10) OR $counts = array("amazon" => array("count" => 15, "start" => 10))
	function api_content_bulk($keyword,$counts = array(), $options_override = array(), $templates = array(), $feed = "", $customfields = "", $duplicate_check = 1, $sdet = "") {

		$save_tags = 0; // SET TO SAVE TAGS -- better alternative: new templates with structured data / div IDS
		$items = array();	
	
			// HOW TO GET OPTIONS HERE? 2nd foreach and replace all options here?
		foreach($counts as $module => $data) {
			$options[$module] = $this->modulearray["options"][$module]["options"];
			
			if(!is_array($options[$module]) || $this->modulearray["options"][$module]["disabled"] == 1) {
				unset($counts[$module]);
				$items[$module]["error"] = __('No settings found for this module. Is it active on the Options page?', 'wprobot');
				continue;
			}
			
			if(!empty($options_override)) {
				foreach($options[$module] as $name => $option) {					
					if(!empty($options_override[$module . "_" . $name]) || $options_override[$module . "_" . $name] === 0) {
						$options[$module][$name]["value"] = $options_override[$module . "_" . $name];
					}
				}
				if($module != "youtube" && empty($options_override[$module . "_comments"])) {$options[$module]["comments"]["value"] = 0;}
				if($module == "youtube" && !empty($options_override[$module . "_next"])) {$options[$module]["next"]["value"] = $options_override[$module . "_next"];} else {$options[$module]["next"]["value"] = "";}
			}
		}

		if($duplicate_check == "no" && !empty($sdet)) {
			$sourcedetails = $sdet;
		} else {
			$sourcedetails = $this->get_request_url_remote_multiple($counts, $keyword,  $options);		
		}
		// RETREIVE NECESSARY CONTENT
		
		foreach($counts as $module => $data) {
		
			$start = 1;
			if(is_array($data)) {
				$count = $data["count"];
				$start = $data["start"];
				if(!empty($data["feed"])) {$rssfeed = $data["feed"];} else {$rssfeed = "";}
			} else {
				$count = $data;
				$start = 1;
			}

			$stop = 0;
			$left = $count;

			$limits = $this->sourceinfos["sources"][$module]["limits"];
			if(!empty($limits["total"]) && $left > $limits["total"]) {$left = $limits["total"];}
			
			if(!empty($templates[$module])) {$template = $templates[$module];} else {
				if(!empty($options[$module]["template_default"])) {
					$template = $options[$module]["template_default"];
				} else {
					$template = "default";
				}	
			}

			if(empty($template) || empty($this->modulearray["options"][$module]["templates"][$template]["content"])) {
				foreach($this->modulearray["options"][$module]["templates"] as $tnm => $tpl) {
					if(!empty($tpl["content"])) {
						$template = $tnm;
					}
				}
			}
			
			while(count($items[$module]) < $count && $count > 0) {
				$stop++;			

				/*if(!empty($options_override)) { // example: $options_override = array("amazon_public_key" => 666);
					$options = $this->modulearray["options"][$module]["options"];
					foreach($options as $name => $option) {					
						if(!empty($options_override[$module . "_" . $name]) || $options_override[$module . "_" . $name] === 0) {
							$options[$name]["value"] = $options_override[$module . "_" . $name];
						}
					}
					if($module != "youtube" && empty($options_override[$module . "_comments"])) {$options["comments"]["value"] = 0;}
					if($module == "youtube" && !empty($options_override[$module . "_next"])) {$options["next"]["value"] = $options_override[$module . "_next"];} else {$options["next"]["value"] = "";}
				}*/

				if(!empty($limits["request"]) && $left > $limits["request"]) {$num = $limits["request"];} else {$num = $left;}			
				if(empty($start)) {$start = 1;}
				
				if($module == "articles") {
					require_once("wpr_articles.php");
					$thetemplate = $this->modulearray["options"][$module]["templates"][$template]["content"];
					$newitems = wpr5_article_post($keyword,$num,$start,$thetemplate);
				} else {					
					if($module == "rss" && ($options[$module]["fulltext"]["value"] == 1 || ($this->modulearray["options"]["rss"]["options"]["fulltext"]["value"] == 1 && $options[$module]["fulltext"]["value"] !== 0))) {
						$rssfeed = 'http://ftr.fivefilters.org/makefulltextfeed.php?url='.urlencode($rssfeed).'&max=5';
					}
					$newitems = $this->api_content_process($keyword,$num,$start,$module,$template,$options[$module],$limits,$rssfeed, $save_tags, $customfields, $sourcedetails[$module]);				
				}

				if(isset($newitems["error"])) {
					$items[$module]["error"] = $newitems["error"];
					break;
				} else {
					foreach($newitems as $nid => $newitem) {
					
						// duplicate check here
						if($duplicate_check == "no") {
							$dcheck = false;					
						} else {
							$dcheck = wpr5_check_unique($newitem["unique"]);								
						}
					
						if($dcheck == false && $module == "rss" && $options[$module]["filter"]["value"] == 1 && !empty($keyword)) {
							
							$c1 = stripos($newitem["content"], $keyword);
							$c2 = stripos($newitem["title"], $keyword);
							if($c1 != false || $c2 != false) {
								if($this->debug == 1) {echo "keyword $keyword was found<br>";}
							} else {
								if($this->debug == 1) {echo "keyword $keyword was not found<br>";	}								
								continue;
							}
							
						}	

						if($dcheck == false) {
							
							if($module == "rss" && ($options[$module]["fulltext"]["value"] == 1 || ($this->modulearray["options"]["rss"]["options"]["fulltext"]["value"] == 1 && $options[$module]["fulltext"]["value"] !== 0))) {							
								$newitem["content"] = str_replace("Let's block ads!", '', $newitem["content"]);
								$newitem["content"] = str_replace('<strong><a href="https://blockads.fivefilters.org"></a></strong>', '', $newitem["content"]);
								$newitem["content"] = str_replace('<a href="https://blockads.fivefilters.org/acceptable.html">(Why?)</a>', '', $newitem["content"]);
								$newitem["content"] = str_replace('This article passed through the Full-Text RSS service – if this is your content and you’re reading it on someone else’s sitio, please read the FAQ at fivefilters.org/content-only/faq.php#publishers.', '', $newitem["content"]);
							}								
							
							$items[$module][] = $newitem;
							$left = $left - 1;
							$start = $start + 1;
						} else {
						
							$start = $start + 1;

							if($stop > 4) {
								// module error
								if($module == "youtube" && !empty($newitem["ytnext"])) {$items[$module]["ytnext"] = $newitem["ytnext"];}
								$items[$module]["duplicates"] = "Duplicate content found for $module module. Will retry next time.";					
								break;							
							}
						}					

						// set youtube next value
						if(!empty($newitem["ytnext"])) {
							$sourcedetails[$module]["request"] = str_replace('pageToken='.$options[$module]["next"]["value"], 'pageToken='.$newitem["ytnext"], $sourcedetails[$module]["request"]);
							$options[$module]["next"]["value"] = $newitem["ytnext"];
						}
						
					
					}
				}

				//if(current_user_can('administrator')) {echo "<br>COUNT: $num <br><br>LEFT: $left <br><br>START: $start <br>";print_r($newitems);	}
								
				if($stop > 4 && $module != "articlebuilder") {break;}
			}
		}

		return $this->api_apply_content_actions($items);
	}	
	
	function parse_children_recursive($xml,$template,$options,$depth=0,$maxdepth=5,$source="",$tag_escape = 1, $reg_array = array(), $save_tags) {

		/* RECORDER */ //$recorder = get_option("cmsc_recorder"); if(!is_array($recorder[$source])) {$recorder[$source] = array();}

		if($tag_escape == 1) {$fr = "{";$ba = "}";} elseif($tag_escape == 2) {$fr = "|";$ba = "|";}
	
		// DIRECT TITLE + ID Fill
		if(!empty($options["unique_direct"]) && empty($this->unique)) {		
			$path=$options["unique_direct"];
			$this->unique = $xml->$path;	
		}
		if(!empty($options["title_direct"]) && empty($this->title)) {		
			$path=$options["title_direct"];
			$this->title = $xml->$path;
		}
		
		foreach($xml->children() as $subselector => $value) {
			
			if($depth >= $maxdepth) {break;}	
			if($this->debug == 1) {
				if($depth == 0) {$mark = "---";} elseif($depth == 1) {$mark = "--- ---";} elseif($depth == 2) {$mark = "--- --- ---";} else {$mark = "--- --- --- ---";}			
				echo " ". $mark . " L".$depth.": " . $subselector . " --- " . $value . "<br>";	
			}
			
			$template = $this->parse_special_tags($template, $value, $options, $depth, $subselector);	

			$template = str_replace($fr.$subselector.$ba, $value, $template);

			if(!empty($this->source_vals[$source]["cf_url"]) && $this->source_vals[$source]["cf_url"] == $subselector) {
				$template = str_replace('{cf_url}', $value, $template);		
			}
			if(!empty($this->source_vals[$source]["cf_price"]) && $this->source_vals[$source]["cf_price"] == $subselector) {
				$pval = str_replace("$", "", $value);
				$pval = str_replace("EUR ", "", $pval);
				$pval = str_replace("USD ", "", $pval);
				$template = str_replace('{cf_price}', $pval, $template);				
			}
			
			$reg_array = $this->register_array_tags($reg_array, $subselector, $value, $source, $save_tags);			
			
			/* RECORDER */ if(is_array($this->recorder[$source]) && !in_array($subselector, $this->recorder[$source])) {$this->recorder[$source][] = $subselector;}
			
			$this->parse_options_unique_values($source, $subselector, $value);

			// ATTRIBUTES
			foreach($value->attributes() as $attribute => $avalue) {
				if($this->debug == 1) {echo  " ". $mark . "> A".$depth.": " . $attribute . " --- " . $avalue . "<br>";}	
				preg_match('#\[IF:'.$attribute.'\](.*)\[/IF:'.$attribute.'\]#smiU', $template, $matches); // IF Tags
				if ($matches[0] != false && empty($avalue)) {
					$template = str_replace($matches[0], "", $template);
				} else {
					$template = str_replace(array('[IF:'.$attribute.']','[/IF:'.$attribute.']'), "", $template);		
				}					
				$template = str_replace($fr.$attribute.$ba, $avalue, $template);

				$reg_array = $this->register_array_tags($reg_array, $attribute, $avalue, $source, $save_tags);
				
				/* RECORDER */ if(is_array($this->recorder[$source]) && !in_array($subselector, $this->recorder[$source])) {$this->recorder[$source][] = $subselector;}
				$this->parse_options_unique_values($source, $attribute, $avalue);
			}
			
			if(count($value->children()) > 0) {
		
				/* RECORDER *///update_option("cmsc_recorder", $recorder);
				$template = $this->parse_children_recursive($value,$template,$options,$depth+1,5,$source,$tag_escape, $reg_array, $save_tags);
			}

		}
		/* RECORDER *///update_option("cmsc_recorder", $recorder);
		return $template;
	}
	
	function register_array_tags($reg_array, $key, $value, $source, $save_tags) {
	
		if($save_tags != 1) {return;}
		
		if (is_array($this->sourceinfos["sources"][$source]["tags"]) && in_array($key, $this->sourceinfos["sources"][$source]["tags"])) {
			$skey = array_search($key, $this->sourceinfos["sources"][$source]["tags"]);	
			$reg_array[$skey] = (string) $value;	
		}
		return $reg_array;		
	}
	
	function api_content_process($keyword,$num,$start,$source,$template_name = "default",$options = array(),$limits,$feed = "",$save_tags = 0, $customfields = "", $sourcedetails = array()) {

		// UPDATE TEMPLATES FOR AMA5
		$updatingama5 = 0;
		if(get_option("wpr5_amaapi5update") != "done7" && $source == "amazon") {
			$updoptions = wpr5_get_options();			
			if(is_array($updoptions["options"]["amazon"]["templates"])) {
				$updatingama5 = 1;
				foreach($updoptions["options"]["amazon"]["templates"] as $template => $templatedata) {
					
					if($template == "short") {
						$updoptions["options"]["amazon"]["templates"][$template]["content"] = 
'<div style="float:left;margin: 5px;width: 200px;">
<a href="{DetailPageURL}" rel="nofollow"><img style="float:left;margin:0 5px 5px 0;" src="{mediumimage}" /></a><br/>
<strong>{title}</strong><br/>
Price: {LowestNewPrice} [IF:savings_discount] - save {savings_discount}![/IF:savings_discount]<br/>
<a href="{DetailPageURL}"><strong>More Information</strong></a>
</div>';								
					} else {
						$updoptions["options"]["amazon"]["templates"][$template]["content"] = 
'<a href="{DetailPageURL}"><strong>{title}</strong></a><br/>
<img style="float:left;margin:0 5px 5px 0;" src="{mediumimage}" />
Price: {LowestNewPrice} [IF:savings_discount] - save {savings_discount}![/IF:savings_discount]<br/><br/>
[IF:color]Color: {color}<br/>[/IF:color]
[IF:model]Model: {model}<br/>[/IF:model]
[IF:warranty]Warranty: {warranty}<br/>[/IF:warranty]
[IF:brand]Brand: {brand}<br/>[/IF:brand]
<br/>
<div><strong>Features</strong><br/><ul>{featurelist}</ul></div>
{alloffers}<br/>
<div><a href="{DetailPageURL}"><img src="https://images-na.ssl-images-amazon.com/images/G/01/associates/remote-buy-box/buy1.gif" /></a></div>
<div style="clear:both;">{image_gallery_medium}</div>';
					}
				}	
				wpr5_update_options($updoptions);
				
				$recorder = get_option("cmsc_recorder");
				$recorder["amazon"] = array("title", "unique", "keyword", "Keyword", "DetailPageURL", "color", "model", "warranty", "brand", "manufacturer", "asin", "upc", "ean", "savings_merchant", "savings_price", "savings_discount", "mediumimage", "largeimage", "LowestNewPrice", "alloffers", "featurelist", "image_gallery_medium", "image_gallery_large" );;				
				update_option("cmsc_recorder", $recorder);
				
				update_option("wpr5_amaapi5update", "done7");
			}
		}

		$items = array();$reg_array = array();
		$x = 0;
		
		if(empty($options)) {
			$options = $this->modulearray["options"][$source]["options"];
		}
	
		if(empty($sourcedetails)) {
			if($this->debug == 1) {echo "<br> - - - empty source details. sending single request.";}
			$sourcedetails = $this->get_request_url_remote($source, $keyword, $start, $num, $options);
		}

		if(empty($sourcedetails["request"]) && empty($feed)) {
			return array("error" => "Error: source request could not be retreived.");
		}
		
		// replace start and num if not happened remotely already
		if($source == "pixabay" && $num < 3) {$num = 3;}
		$sourcedetails["request"] = str_replace("{start}", $start, $sourcedetails["request"]);	
		$sourcedetails["request"] = str_replace("{num}", $num, $sourcedetails["request"]);				

		$xml = $this->api_content_request($sourcedetails,$keyword,$num,$start,$source,$options,$feed);		

		if($updatingama5 == 1 && is_array($updoptions["options"])) {
			$thetemplate = $updoptions["options"][$source]["templates"][$template_name]["content"];
		} else {
			$thetemplate = $this->modulearray["options"][$source]["templates"][$template_name]["content"];				
		}
	
		if(empty($thetemplate)) {global $modulearray; $thetemplate = $modulearray["options"][$source]["templates"][$template_name]["content"];}
	
		if(!empty($customfields) && strpos($customfields, "[customfields:") !== false) {
			$thetemplate .= $customfields;
		}
		
		if($source == "ebay") {return $this->api_ebay_process($keyword,$num,$start,$source,$thetemplate,$options);}

		if(is_array($xml) && isset($xml["error"])) {
			return $xml;
		} elseif(is_array($xml) && isset($xml["items"])) {
			// AMAZON V5 PARSING
			
		/*
						$amaitems["items"][] = array(
							"asin" => $asin,
							"url" => $url,
							"title" => $title,
							"color" => $color,
							"model" => $model,
							"warranty" => $warranty,
							"brand" => $brand,
							"manufacturer" => $manufacturer,
							"eans" => $eans,
							"upcs" => $upcs,
							"images" => $allimages,
							"offers" => $offersz,
							"savings" => $savs,
							"features" => $featarray
						);
*/		
			if($this->debug == 1) {echo "<br> ==== AMAZON - ".count($xml["items"])." RESULTS ==== <br><br>";}	
			if($this->debug == 1) {echo "<pre>"; print_r($xml["items"]);echo "</pre>";}	
			
			foreach($xml["items"] as $amaitem) {			
				$this->title = $amaitem["title"];
				$this->unique = $amaitem["asin"];	

				$template = $thetemplate;
				
				$template = $this->parse_random_tags($template);	// Random Tags	

				$ifvalues = array("color", "model", "warranty", "brand");
				foreach($ifvalues as $ifvalue) {
					preg_match('#\[IF:'.$ifvalue.'\](.*)\[/IF:'.$ifvalue.'\]#smiU', $template, $matches); // IF Tags
					if ($matches[0] != false && empty($amaitem[$ifvalue])) {
						$template = str_replace($matches[0], "", $template);
					} else {
						$template = str_replace(array('[IF:'.$ifvalue.']','[/IF:'.$ifvalue.']'), "", $template);		
					}			
				}
				$ifvalues = array("savings_discount");
				foreach($ifvalues as $ifvalue) {
					preg_match('#\[IF:'.$ifvalue.'\](.*)\[/IF:'.$ifvalue.'\]#smiU', $template, $matches); // IF Tags
					if ($matches[0] != false && empty($amaitem["savings"][0]["savings"])) {
						$template = str_replace($matches[0], "", $template);
					} else {
						$template = str_replace(array('[IF:'.$ifvalue.']','[/IF:'.$ifvalue.']'), "", $template);		
					}			
				}
				
				$template = str_replace("{title}", $this->title, $template);
				$template = str_replace("{unique}", $this->unique, $template);
				$template = str_replace("{keyword}", $keyword, $template);
				$template = str_replace("{Keyword}", ucwords($keyword), $template);							

				$template = str_replace("{DetailPageURL}", $amaitem["url"], $template);
				
				$template = str_replace("{color}", $amaitem["color"], $template);				
				$template = str_replace("{model}", $amaitem["model"], $template);				
				$template = str_replace("{warranty}", $amaitem["warranty"], $template);				
				$template = str_replace("{brand}", $amaitem["brand"], $template);				
				$template = str_replace("{manufacturer}", $amaitem["manufacturer"], $template);				

				$template = str_replace("{asin}",$amaitem["asin"], $template);	
				$template = str_replace("{upc}", $amaitem["upcs"][0], $template);		
				$template = str_replace("{ean}", $amaitem["eans"][0], $template);						

				$template = str_replace("{savings_merchant}", $amaitem["savings"][0]["merchant"], $template);
				$template = str_replace("{savings_price}", $amaitem["savings"][0]["price"], $template);
				$template = str_replace("{savings_discount}", $amaitem["savings"][0]["savings"], $template);
				
				$template = str_replace("{mediumimage}", $amaitem["images"][0]["medium"]["url"], $template);
				
				if(!empty($amaitem["images"][1]["large"]["url"])) {$largeimg = $amaitem["images"][1]["large"]["url"];} else {$largeimg = $amaitem["images"][0]["large"]["url"];}
				$template = str_replace("{largeimage}", $largeimg, $template);
				
				$template = str_replace("{LowestNewPrice}", $amaitem["offers"]["New"]["low"], $template);
					
				$alloffers = "";
				foreach($amaitem["offers"] as $off => $fer) {
					$alloffers .= $off . ' from ' . $fer["low"] . ' (' . $fer["count"] . ' offers)<br/>';
				}
				$template = str_replace("{alloffers}", $alloffers, $template);

				
				$featlist = "";foreach($amaitem["features"] as $feat) {$featlist .= "<li>$feat</li>";}
				$template = str_replace("{featurelist}", $featlist, $template);
				
				$imgmed = "";$imglrg = "";
				foreach($amaitem["images"] as $img) {
					$imgmed .= '<img style="float:left;margin:0 5px 5px 0;" src="'.$img["medium"]["url"].'"/>';
					$imglrg .= '<img style="float:left;margin:0 5px 5px 0;" src="'.$img["large"]["url"].'"/>';
				}
				$template = str_replace("{image_gallery_medium}", $imgmed, $template);
				$template = str_replace("{image_gallery_large}", $imglrg, $template);
				
				$template = preg_replace('#\{(.*)\}#smiU', "", $template); 		// leftover tag replace MISSING: Could create problems in certain cases?

				$items[$x]["source"] = $source;				
				$items[$x]["unique"] = (string) $this->unique;
				$items[$x]["title"] = (string) $this->title;
				$items[$x]["content"] = $template;	
	
				if(strpos($template, "customfields") !== false) {			
					$customfields = $this->get_custom_fields($template, $keyword);
					$items[$x]["customfields"] = $customfields;					
				}
				
				$x++;					
			}
			
			return $items;
			
		} else {
			$errorpath = $sourcedetails["error"];
			$selector = $sourcedetails["selector"];
	
			if(isset($xml->$errorpath)) {
				$error = $xml->$errorpath;				
				if($error == "") {foreach($xml->$errorpath->children() as $subselector => $value) {
					$error .= $value. " - ";
				}}
				if($error == "") {foreach($xml->$errorpath->attributes() as $subselector => $value) {
					$error .= $value. " - ";
				}}				
				if($this->debug == 1) {echo "<br>ERROR: ".$error;	}		
				return array("error" => $error);
			}
			
			// SPECIAL CASES
			$z = 0;
			if($source=="amazon") {
				$start = $this->start;
				if(empty($start)) {$start = 1;}
				$y = $start + $this->num -1;

				if(!empty($xml->Items->Request->Errors->Error->Message)) {

					$emsg = (string) $xml->Items->Request->Errors->Error->Message;
					return array("error" => $emsg);									
				}
			}

			if($source == "youtube") {
				$nextpagetoken = (string) $xml->nextPageToken;
			}

			$uri = $xml->getDocNamespaces();
			$uri = $uri[""];
			if(!empty($uri)) {
				$xml->registerXPathNamespace('def', $uri);
				$xml = $xml->xpath('//def:'.$selector);				
			} else {
				$xml = $xml->xpath('//'.$selector);			
			}
			
		
			/////////////////////////////////////////////			
			//echo "<pre>";print_r($xml);echo "</pre>";
			/////////////////////////////////////////////

			/*
			OLD WAY -- NEW NEEDS MORE TESTING
			if(!empty($level3)) {$xml = $xml->$level3;}
			if(!empty($level2)) {$xml = $xml->$level2;}
			if(!empty($level1)) {$xml = $xml->$level1;}
			foreach($xml->$selector as $entry) {*/ 
			
			/* RECORDER */ $this->recorder = get_option("cmsc_recorder");$updrec = 0; if(!is_array($this->recorder[$source])) {$this->recorder[$source] = array();}				

			foreach($xml as $entry) {

				$this->title = "";
				$this->unique = "";			
	
				if($source=="amazon") {
					$z++;
					if($z < $start || $z > $y) {continue;}
				}
		
				$template = $thetemplate;

				// Random Tags
				$template = $this->parse_random_tags($template);		

				if(is_array($options)) {
					foreach($options as $oname => $oarray) {
						$template = str_replace("[OPTION:".$oname."]", $oarray["value"], $template);
					}
				}
				
				if($this->debug == 1) {echo "<br> ==== ENTRY ==== <br><br>";}	

					// VIMEO SPECIAL CASES START
					if($source == "vimeo") {
						$attrs = $entry->attributes();					
						$this->unique = $attrs['id'];
					}
					// YOUTUBE SPECIAL CASES START
					if($source == "youtube") {
					
						if($entry->id->kind == "youtube#channel") {
							continue;
						}
						
						$thumbnailurl = $entry->snippet->thumbnails->default->url;	
						$thumbnailurl_med = $entry->snippet->thumbnails->medium->url;	
						$thumbnailurl_lrg = $entry->snippet->thumbnails->high->url;		

						$template = str_replace("{thumbnailurl}", $thumbnailurl, $template);
						$template = str_replace("{thumbnailurl_med}", $thumbnailurl_med, $template);
						$template = str_replace("{thumbnailurl_lrg}", $thumbnailurl_lrg, $template);
						
						$fulldesc = $this->youtube_get_full_desc($entry->id->videoId);	
						if(!empty($fulldesc)) {
							$template = str_replace("{description}", $fulldesc, $template);	
						}
					}
					// YOUTUBE SPECIAL CASES END			
					
				if(count($entry->children()) > 0) {	
					$template = $this->parse_children_recursive($entry,$template,$options,0,4,$source, 1, $reg_array, $save_tags);	
				}
				
				foreach($entry->attributes() as $attribute => $avalue) {
					if($this->debug == 1) {echo  " ---> ATTRIBUTE --- " . $attribute . " --- " . $avalue . "<br>";}		
					$template = str_replace("{".$attribute."}", $avalue, $template);
					
					$reg_array = $this->register_array_tags($reg_array, $attribute, $avalue, $source, $save_tags);
					
					$this->parse_options_unique_values($source, $attribute, $avalue);	

					/* RECORDER */ if(is_array($this->recorder[$source]) && !in_array($attribute, $this->recorder[$source])) {$this->recorder[$source][] = $attribute;}
					
				}
				
				if($source == "rss" && empty($this->unique)) {
					$this->unique = $entry->link;
				}				

				if($this->debug == 1) {echo "<br>TITLE: ".$this->title;}
				if($this->debug == 1) {echo "<br>UNIQUE: ".$this->unique;}

				$template = str_replace("{title}", $this->title, $template);
				$template = str_replace("{unique}", $this->unique, $template);
				$template = str_replace("{keyword}", $keyword, $template);
				$template = str_replace("{Keyword}", ucwords($keyword), $template);
				
				foreach($this->special_tags as $stag) {
					$template = preg_replace('#\['.$stag.':(.*)\[/'.$stag.'(.*)\]#smiU', "", $template); 							
				}										
		
				if($this->debug == 1) {echo  " <br><br> ======== Template ======== <br><br>" . $template . "<br><br> ======================== <br><br>";}
				
				if($source == "youtube") {$comments = $this->unique;} elseif($source == "flickr") {$comments = $this->unique;} elseif($source == "yahooanswers") {$comments = $entry['id'];} elseif($source == "amazon") {$comments = $entry->CustomerReviews->IFrameURL;}

				if($source == "linkshare") {$template = htmlspecialchars_decode($template);	}
		
				if($source=="bingnews") {
					$this->title = $entry->node->name;
				}		
		
				// leftover tag replace MISSING: Could create problems in certain cases?
				$template = preg_replace('#\{(.*)\}#smiU', "", $template); 		
								
				
				$items[$x]["source"] = $source;				
				$items[$x]["unique"] = (string) $this->unique;
				$items[$x]["title"] = (string) $this->title;
				$items[$x]["content"] = $template;	
				if(!empty($nextpagetoken)) {$items[$x]["ytnext"] = $nextpagetoken;}
				
				if(strpos($template, "customfields") !== false) {			
					$customfields = $this->get_custom_fields($template, $keyword);
					$items[$x]["customfields"] = $customfields;					
				}

				if($options["comments"]["value"] == 1 && !empty($comments)) {	
					$items[$x]["comments"] = $this->get_comments($comments, $source);
	
					if(strpos($template, "{comments") !== false) {
						$items[$x]["content"] = $this->parse_comments($items[$x]["content"], $items[$x]["comments"], $source);
					}
				}
				
				if($save_tags == 1) {
					$items[$x]["tags"] = $reg_array;	
				}
				
				/* RECORDER */update_option("cmsc_recorder", $this->recorder);

				$x++;			
			}	

			return $items;
		}
	}

	///////////////////////////// REQUESTS /////////////////////////////////	
	
	function fetch_request_url_remote_multiple($request) {
		global $wpr5_source_infos2;
		
		$sarray = array();
		$sources = $request["sources"];
		$keyword = urlencode($request["keyword"]);
		$keyword = filter_var($keyword, FILTER_SANITIZE_URL);
		$optionsarray = $request["options"];			
	
		foreach($sources as $source => $sdet) {
			$start = $sdet["start"];
			$num = $sdet["count"];				
			$sourcedetails = $wpr5_source_infos2["sources"][$source];
			$requrl = $sourcedetails["request"];

			if(is_array($optionsarray[$source])) {
				foreach($optionsarray[$source] as $oname => $oarray) {
					if($oname == "channel" && empty($oarray["value"])) {
						$requrl = str_replace("&channelId={".$oname."}", $oarray["value"], $requrl);	
					} else {
						$requrl = str_replace("{".$oname."}", $oarray["value"], $requrl);					
					}
				}
			}

			$requrl = str_replace("{keyword}", $keyword, $requrl);	
			$requrl = str_replace("{next}", "", $requrl);				

			$selector = $sourcedetails["selector"];
			$error = $sourcedetails["error"];	
			$title = $sourcedetails["title"];	
			$unique = $sourcedetails["unique"];	
			$url = $sourcedetails["url"];	
			$price = $sourcedetails["price"];						
			if($sourcedetails["json"] == 1) {$json = 1;} else {$json = 0;}
			if($sourcedetails["oauth"] == 1) {$oauth = 1;} else {$oauth = 0;}

			$sarray[$source] = array("request" => $requrl, "json" => $json, "oauth" => $oauth, "selector" => $selector, "error" => $error, "title" => $title, "unique" => $unique, "url" => $url, "price" => $price);
		}
		if($this->debug == 1) {echo "--- return source request ---<br>";}
		
		return $sarray;
	}
	
	function fetch_request_url_remote_single($request) {
		global $wpr5_source_infos2;
		
		// return sources
		$sarray = array();
		$sources = explode(";", $request["sources"]);
		
		$keyword = filter_var($request["keyword"], FILTER_SANITIZE_URL);
		$start = filter_var($request["start"], FILTER_SANITIZE_URL);
		$num = filter_var($request["num"], FILTER_SANITIZE_URL);

		$optionsarray = unserialize($request["options"]);
		
		foreach($sources as $source) {
			$sourcedetails = $wpr5_source_infos2["sources"][$source];
			$requrl = $sourcedetails["request"];

			if(is_array($optionsarray)) {
				foreach($optionsarray as $oname => $oarray) {
					if($oname == "channel" && empty($oarray["value"])) {
						$requrl = str_replace("&channelId={".$oname."}", $oarray["value"], $requrl);	
					} else {
						$requrl = str_replace("{".$oname."}", $oarray["value"], $requrl);					
					}
				}
			}

			$keyword = urlencode($keyword);
			$requrl = str_replace("{keyword}", $keyword, $requrl);	
			$requrl = str_replace("{start}", $start, $requrl);	
			$requrl = str_replace("{num}", $num, $requrl);	
			$requrl = str_replace("{next}", "", $requrl);				

			$selector = $sourcedetails["selector"];
			$error = $sourcedetails["error"];	
			$title = $sourcedetails["title"];	
			$unique = $sourcedetails["unique"];	
			$url = $sourcedetails["url"];	
			$price = $sourcedetails["price"];	
			if($sourcedetails["json"] == 1) {$json = 1;} else {$json = 0;}
			if($sourcedetails["oauth"] == 1) {$oauth = 1;} else {$oauth = 0;}

			$sarray[$source] = array("request" => $requrl, "json" => $json, "oauth" => $oauth, "selector" => $selector, "error" => $error, "title" => $title, "unique" => $unique, "url" => $url, "price" => $price);
		}	
		
		if($this->debug == 1) {echo "--- return source request ---<br>";}
		
		return $sarray;
	}	
	
	function get_request_url_remote_multiple($sourcearray, $keyword,  $options) {

		$lkey = get_option("wpr5_license_final");
	
		if(get_option("wpr5_is_trial") == true) {$is_trial = true;} else {$is_trial = false;}

		$api_params = array(
			'action' => "requests_multiple",
			'itemname' => EDD_WPR_ITEM_NAME,
			'sources' => $sourcearray,
			'license' => $lkey,
			'keyword' => $keyword,
			'options' => $options,
			'url' => home_url(),
			'trial' => $is_trial
		);	
		
		$response = $this->fetch_request_url_remote_multiple($api_params);//wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=getreq&k=".urlencode($keyword), array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

		if ( is_wp_error( $response ) ) {
			return array("error" => $response->get_error_message());		
		}
		
		$license_data = $response;
		
		if($this->debug == 1) {echo "--- MULTI LICENSE REQUEST ---<pre>";print_r($license_data);echo "</pre>";}

		if(!empty($license_data["error"])) {
		
			if (strpos($license_data["error"], "Your license key is not valid or has expired") !== false) {
				update_option("wpr5_license_expired", true);
			}		
		
			return array("error" => $license_data["error"]);
		} elseif(is_array($license_data)) {
			foreach($license_data as $source => $sdet) {
				$this->source_vals[$source]["unique"] = $sdet["unique"];
				$this->source_vals[$source]["title"] = $sdet["title"];		
				$this->source_vals[$source]["cf_url"] = $sdet["url"];	
				$this->source_vals[$source]["cf_price"] = $sdet["price"];	
			}
			return $license_data;
		}
	}	
	
	function get_request_url_remote($source, $keyword, $start, $num, $options) {
	
		$lkey = get_option("wpr5_license_final");
	
		$api_params = array(
			'sources' => $source,
			'itemname' => EDD_WPR_ITEM_NAME,
			'license' => $lkey,
			'keyword' => $keyword,
			'start' => $start,
			'options' => serialize($options),
			'num' => $num,
			'url' => home_url()
		);	
		
		$response = $this->fetch_request_url_remote_single($api_params);//wp_remote_post( "http://wpr5.us-west-2.elasticbeanstalk.com/aws-license-check/?r=getreqsingle", array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );	

		if ( is_wp_error( $response ) ) {
			return array("error" => $response->get_error_message());		
		}
		
		$license_data = $response;
		
		if($this->debug == 1) {echo "--- LICENSE REQUEST ---<pre>";print_r($license_data);echo "</pre>";}

		if(!empty($license_data["error"])) {
			return array("error" => $license_data["error"]);
		} else {
			$this->source_vals[$source]["unique"] = $license_data[$source]["unique"];
			$this->source_vals[$source]["title"] = $license_data[$source]["title"];
			return $license_data[$source];
		}
	}
	
	function api_content_request($sourcedetails,$keyword,$num,$start,$source,$optionsarray,$feed="") {

		if($source == "articlebuilder") {
			return $this->api_send_articlebuilder_request($keyword,$num,$start,$optionsarray);		
		}

		$requrl = $sourcedetails["request"];

		if($source == "amazon") {
			return $this->api_send_amazon_request($requrl,$keyword,$num,$start,$optionsarray);		
		}		
	
		//echo "<br>--- Request ---<br>".$requrl."<br>------------------<br>";
	
		if($source == "yelp") {	
			return $this->api_send_yelp_oauth_request($requrl,$source,$optionsarray,1);
		} elseif(!empty($feed)) {
			return $this->api_send_request($feed,$source,$optionsarray,0);		
		} elseif($sourcedetails["json"] == 1 && $sourcedetails["oauth"] != 1) {
			return $this->api_send_request($requrl,$source,$optionsarray,1);			
		} elseif($sourcedetails["oauth"] == 1) {
			return $this->api_send_oauth_request($requrl,$source,$optionsarray);
		} else {
			return $this->api_send_request($requrl,$source,$optionsarray);
		}
	}

	function api_send_yelp_oauth_request($requrl,$source,$optionsarray) {	
		require_once('lib/OAuth.php');

		$token = new wpr5_OAuthToken($optionsarray["token"]["value"], $optionsarray["token_secret"]["value"]);
		$consumer = new wpr5_OAuthConsumer($optionsarray["appid"]["value"], $optionsarray["secretid"]["value"]);
		$signature_method = new wpr5_OAuthSignatureMethod_HMAC_SHA1();
		
		$oauthrequest = wpr5_OAuthRequest::from_consumer_and_token(
			$consumer, 
			$token, 
			'GET', 
			$requrl
		);	

		$oauthrequest->sign_request($signature_method, $consumer, $token);
		$signed_url = $oauthrequest->to_url();

		try {
			$ch = curl_init($signed_url);
			if (FALSE === $ch)
				throw new Exception('Failed to initialize');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$data = curl_exec($ch);
			if (FALSE === $data)
				throw new Exception(curl_error($ch), curl_errno($ch));
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 != $http_status)
				throw new Exception($data, $http_status);
			curl_close($ch);
		} catch(Exception $e) {
			trigger_error(sprintf(
				'Curl failed with error #%d: %s',
				$e->getCode(), $e->getMessage()),
				E_USER_ERROR);
		}

		$pxml = json_decode($data);	

		$xmlize = new XMLSerializer;
		if(empty($pxml)) {$return["error"] = "No content could be found.";return $return;}
		$response = $xmlize->generateValidXmlFromObj($pxml);
		$pxml = simplexml_load_string($response);	
		
		//echo "effe<pre>";print_r($pxml);echo "</pre>";
		
		return $pxml;		
	}
	
	function api_send_oauth_request($requrl,$source,$optionsarray) {
		include_once "oauth-php/library/OAuthStore.php";
		include_once "oauth-php/library/OAuthRequester.php";

		$key = $optionsarray["appid"]["value"]; // this is your consumer key
		$secret = $optionsarray["secretid"]["value"]; // this is your secret key

		$options = array( 'consumer_key' => $key, 'consumer_secret' => $secret );
		OAuthStore::instance("2Leg", $options );

		$url = $requrl; // this is the URL of the request
		$method = "GET"; // you can also use POST instead
		$params = null;

		try {
			$request = new OAuthRequester($url, $method, $params);

			// Sign the request, perform a curl request and return the results, 
			// throws OAuthException2 exception on an error
			// $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
			$result = $request->doRequest();
			
			$pxml = simplexml_load_string($result['body']);				

			if ($pxml === False) {
				$emessage = __("Failed loading XML, errors returned: ","wprobot");
				foreach(libxml_get_errors() as $error) {$emessage .= $error->message . ", ";}	
				libxml_clear_errors();
				$return["error"] = $emessage;	
				return $return;			
			} else {
				return $pxml;
			}				
		}
		catch(OAuthException2 $e) {
		}
	}
	
	function api_ab_curl_post($url, $data, $info) {
		$fdata = "";
		foreach($data as $key => $val){$fdata .= "$key=" . urlencode($val) . "&";}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		$html = trim(curl_exec($ch));
		curl_close($ch);
		return $html;	
	}
	
	function array_to_xml($orig_array, &$xml_array) {
		foreach($orig_array as $key => $value) {
			if(is_array($value)) {
				if(!is_numeric($key)){
					$subnode = $xml_array->addChild("$key");
					$this->array_to_xml($value, $subnode);
				} else {
				$this->array_to_xml($value, $xml_array);
				}
			} else {
			$xml_array->addChild("$key","$value");
			}
		}
	}	
	
	function api_send_articlebuilder_request($keyword,$num,$start,$optionsarray) {

		$keywords = explode(",", $keyword);
		$url = 'http://articlebuilder.net/api.php';

		$data = array();
		$data['action'] = 'authenticate';
		$data['format'] = 'php';
		$data['username'] = $optionsarray["email"]["value"];
		$data['password'] = $optionsarray["pw"]["value"];

		$output = unserialize($this->api_ab_curl_post($url, $data, $info));

		if($output['success']=='true') {
			$session = $output['session'];

			$data = array();
			$data['session'] = $session;
			$data['format'] = 'php';
			$data['action'] = 'buildArticle';  
			$data['apikey'] = $apikey;
			$data['category'] = $optionsarray["category"]["value"];

			$subs = "";if(is_array($keywords)) {foreach($keywords as $keyword) {$subs .= $keyword . "\n";}}
			$data['subtopics'] = $subs;
			$data['wordcount'] = $optionsarray["length"]["value"];
			$data['superspun'] = $optionsarray["superspun"]["value"];

			$posts = array();
			for ($i = 0; $i < $num; $i++) {

				$output = $this->api_ab_curl_post($url, $data, $info);
				$output = unserialize($output);
				
				if($output['success']=='true'){
				
					$arts = preg_split('/\r\n|\r|\n/', $output['output'], 2);
					$article = str_replace("\r", "<br>", str_replace("\n\n", "<p>", $arts[1]));					
					$title = $arts[0];
					
					$posts["item"][] = array("title" => $title, "article" => $article);
				} else {
					return array("error" => "ArticleBuilder Error: " . $output["error"]);
				}
			}
			if(empty($posts)) {
				return array("error" => "No Article Builder content found.");
			} else {
				$xml_array = new SimpleXMLElement("<?xml version=\"1.0\"?><items></items>");		
				$this->array_to_xml($posts,$xml_array);
				return $xml_array;	
			}			
		} else {
			return array("error" => "ArticleBuilder Error: " . $output["error"]);
		}	
	}

	function api_send_amazon_request_v5($requrl,$keyword,$num,$start,$optionsarray) {

		$start2 = $start / 10;
		$start2 = (string) $start2; 
		$start2 = explode(".", $start2);
		$page=(int)$start2[0];	
		$page++;				
		$this->start=(int)$start2[1]; 	
		$this->num=$num; 

		$params["MinPercentageOff"] = "";$params["MinimumPrice"] = "";$params["MaximumPrice"] = "";
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinPercentageOff"] = $optionsarray["minoff"]["value"];}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinimumPrice"] = $optionsarray["minprice"]["value"] * 100;}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MaximumPrice"] = $optionsarray["maxprice"]["value"] * 100;}		

		$region="us-east-1";
		if($optionsarray["region"]["value"] == "es" || $optionsarray["region"]["value"] == "com.tr" || $optionsarray["region"]["value"] == "co.uk" || $optionsarray["region"]["value"] == "in" || $optionsarray["region"]["value"] == "it" || $optionsarray["region"]["value"] == "de" || $optionsarray["region"]["value"] == "fr") {$region="eu-west-1";}
		if($optionsarray["region"]["value"] == "co.jp" || $optionsarray["region"]["value"] == "sg") {$region="us-west-2";} 

		$config = new Configuration();

		/*
		 * Add your credentials
		 */
		$config->setAccessKey($optionsarray["public_key"]["value"]);
		$config->setSecretKey($optionsarray["private_key"]["value"]);
		$partnerTag = $optionsarray["affid"]["value"];

		/*
		 * PAAPI host and region to which you want to send request
		 * For more details refer: https://webservices.amazon.com/paapi5/documentation/common-request-parameters.html#host-and-region
		 */
		$config->setHost('webservices.amazon.'.$optionsarray["region"]["value"]);
		$config->setRegion($region);

		$apiInstance = new DefaultApi(
		new GuzzleHttp\Client(), $config);

		/*
		 * Specify the category in which search request is to be made
		 * For more details, refer: https://webservices.amazon.com/paapi5/documentation/use-cases/organization-of-items-on-amazon/search-index.html
		 */
		$searchIndex = "All";
		if(!empty($optionsarray["searchindex"]["value"])) {
			$searchIndex = $optionsarray["searchindex"]["value"];
			
			if($searchIndex == "PCHardware") {$searchIndex = "Computers";}
			if($searchIndex == "Garden") {$searchIndex = "GardenAndOutdoor";}
			if($searchIndex == "Kitchen") {$searchIndex = "HomeAndKitchen";}
			if($searchIndex == "HomeImprovement") {$searchIndex = "ToolsAndHomeImprovement";}
			if($searchIndex == "Tools") {$searchIndex = "ToolsAndHomeImprovement";}
			if($searchIndex == "Toys") {$searchIndex = "ToysAndGames";}
			if($searchIndex == "MusicTracks") {$searchIndex = "DigitalMusic";}
			if($searchIndex == "SportingGoods") {$searchIndex = "SportsAndOutdoors";}
			if($searchIndex == "Wireless") {$searchIndex = "MobileAndAccessories";}
			if($searchIndex == "WirelessAccessories") {$searchIndex = "MobileAndAccessories";}
		}
		$keyword = $keyword;	
		$itemCount = 10;

		/*
		 * Choose resources you want from SearchItemsResource enum
		 * For more details, refer: https://webservices.amazon.com/paapi5/documentation/search-items.html#resources-parameter
		 */
		$resources = array(
			SearchItemsResource::ITEM_INFOTITLE,
			SearchItemsResource::ITEM_INFOTECHNICAL_INFO,
			SearchItemsResource::ITEM_INFOPRODUCT_INFO,
			SearchItemsResource::ITEM_INFOMANUFACTURE_INFO,
			SearchItemsResource::ITEM_INFOFEATURES,
			SearchItemsResource::ITEM_INFOEXTERNAL_IDS,
			SearchItemsResource::ITEM_INFOCLASSIFICATIONS,
			SearchItemsResource::ITEM_INFOCONTENT_RATING,
			SearchItemsResource::ITEM_INFOBY_LINE_INFO,
			//SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODES,
			//SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODESSALES_RANK,			
			//SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODESANCESTOR,			
			//SearchItemsResource::BROWSE_NODE_INFOWEBSITE_SALES_RANK,					
			SearchItemsResource::IMAGESPRIMARYSMALL,
			SearchItemsResource::IMAGESPRIMARYMEDIUM,
			SearchItemsResource::IMAGESPRIMARYLARGE,
			SearchItemsResource::IMAGESVARIANTSSMALL,
			SearchItemsResource::IMAGESVARIANTSMEDIUM,
			SearchItemsResource::IMAGESVARIANTSLARGE,		
			SearchItemsResource::OFFERSSUMMARIESHIGHEST_PRICE,
			SearchItemsResource::OFFERSSUMMARIESLOWEST_PRICE,
			SearchItemsResource::OFFERSSUMMARIESOFFER_COUNT,	
			SearchItemsResource::OFFERSLISTINGSPRICE,
			SearchItemsResource::OFFERSLISTINGSPROMOTIONS,
			SearchItemsResource::OFFERSLISTINGSSAVING_BASIS,
			SearchItemsResource::OFFERSLISTINGSMERCHANT_INFO,
			SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_PRIME_ELIGIBLE,
			SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOSHIPPING_CHARGES,
			SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_FREE_SHIPPING_ELIGIBLE,
			SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_AMAZON_FULFILLED
		);

		# Forming the request
		$searchItemsRequest = new SearchItemsRequest();
		$searchItemsRequest->setSearchIndex($searchIndex);
		$searchItemsRequest->setKeywords($keyword);
		$searchItemsRequest->setItemCount($itemCount);
		$searchItemsRequest->setPartnerTag($partnerTag);
		$searchItemsRequest->setPartnerType(PartnerType::ASSOCIATES);
		$searchItemsRequest->setResources($resources);
		
		$searchItemsRequest->setItemPage($page);	
		$searchItemsRequest->setMarketplace("www.amazon.".$optionsarray["region"]["value"]);
		
		if(!empty($params["MinimumPrice"])) {$searchItemsRequest->setMinPrice($params["MinimumPrice"]);}		
		if(!empty($params["MaximumPrice"])) {$searchItemsRequest->setMaxPrice($params["MaximumPrice"]);}		
		if(!empty($params["MinPercentageOff"])) {$searchItemsRequest->setMinSavingPercent($params["MinPercentageOff"]);}	

		if(!empty($optionsarray["browsenode"]["value"])) {$searchItemsRequest->setBrowseNodeId($optionsarray["browsenode"]["value"]);}	

	// $searchItemsRequest->setMerchant($id);
	// $searchItemsRequest->setSortBy($id);		

		# Validating request
		$invalidPropertyList = $searchItemsRequest->listInvalidProperties();
		$length = count($invalidPropertyList);
		if ($length > 0) {
			$emsg = "Error forming the request... ";
			foreach ($invalidPropertyList as $invalidProperty) {
				$emsg .= $invalidProperty;
			}
			$return["error"] = $emsg;
			return $return;
		}

		# Sending the request
		try {
			$searchItemsResponse = $apiInstance->searchItems($searchItemsRequest);

			//echo 'Complete Response: ', $searchItemsResponse, PHP_EOL;

			if ($searchItemsResponse->getSearchResult() != null) {

				/*$pxml = json_decode($searchItemsResponse);	
				$xmlize = new XMLSerializer;
				if(empty($pxml)) {$return["error"] = "No content could be found.";return $return;}
				$response = $xmlize->generateValidXmlFromObj($pxml);
				$pxml = simplexml_load_string($response);	
				echo "<pre>";print_r($pxml);echo "</pre>";return;*/

				$items = $searchItemsResponse->getSearchResult()->getItems();
				$amaitems = array();
				
				foreach($items as $item) {
					if ($item != null) {
						if ($item->getASIN() != null) {$asin = $item->getASIN();}
						if ($item->getDetailPageURL() != null) {$url = $item->getDetailPageURL();}
						if ($item->getImages() != null) {
								$images = $item->getImages();
								$allimages = array();
								$primary = $images->getPrimary();
								
								$mediumh = $primary->getMedium()->getHeight();
								$mediumw = $primary->getMedium()->getWidth();
								$mediumurl = $primary->getMedium()->getURL();
					
								$largeh = $primary->getMedium()->getHeight();
								$largew = $primary->getMedium()->getWidth();
								$largeurl = $primary->getMedium()->getURL();
								
								$allimages[] = array(
									"large" => array("url" => $largeurl, "w" => $largew, "h" => $largeh),
									"medium" => array("url" => $mediumurl, "w" => $mediumw, "h" => $mediumh),									
								);
					
								//echo '<img width="'.$mediumw.'" height="'.$mediumh.'" src="'.$mediumurl.'" />';
								
								$variants = $images->getVariants();
								if(!empty($variants)) {
									
									foreach($variants as $variant) {
										$largeh = $variant->getLarge()->getHeight();
										$largew = $variant->getLarge()->getWidth();
										$largeurl = $variant->getLarge()->getURL();
										
										$mediumh = $variant->getMedium()->getHeight();
										$mediumw = $variant->getMedium()->getWidth();
										$mediumurl = $variant->getMedium()->getURL();
										
										$allimages[] = array(
											"large" => array("url" => $largeurl, "w" => $largew, "h" => $largeh),
											"medium" => array("url" => $mediumurl, "w" => $mediumw, "h" => $mediumh),									
										); 
										//echo '<img width="'.$mediumw.'" height="'.$mediumh.'" src="'.$mediumurl.'" />';
									}										
								}
						}						
						if ($item->getItemInfo() != null) {
							if ($item->getItemInfo()->getManufactureInfo() != null && $item->getItemInfo()->getManufactureInfo()->getModel() != null) {
								$model = $item->getItemInfo()->getManufactureInfo()->getModel()->getDisplayValue();
							}							
							if ($item->getItemInfo()->getManufactureInfo() != null && $item->getItemInfo()->getManufactureInfo()->getWarranty() != null) {
								$warranty = $item->getItemInfo()->getManufactureInfo()->getWarranty()->getDisplayValue();
							}	

							if ($item->getItemInfo()->getExternalIds() != null && $item->getItemInfo()->getExternalIds()->getEANs() != null) {
								$eans = array();
								foreach($item->getItemInfo()->getExternalIds()->getEANs()->getDisplayValues() as $ean) {
									$eans[] = $ean;
								}	
							}	
							if ($item->getItemInfo()->getExternalIds() != null && $item->getItemInfo()->getExternalIds()->getUPCs() != null) {
								$upcs = array();
								foreach($item->getItemInfo()->getExternalIds()->getUPCs()->getDisplayValues() as $upc) {
									$upcs[] = $upc;
								}	
							}					
							
							if ($item->getItemInfo()->getProductInfo() != null && $item->getItemInfo()->getProductInfo()->getColor() != null) {
								$color = $item->getItemInfo()->getProductInfo()->getColor()->getDisplayValue();
							}
							if ($item->getItemInfo()->getProductInfo() != null && $item->getItemInfo()->getProductInfo()->getSize() != null) {
								$size = $item->getItemInfo()->getProductInfo()->getSize()->getDisplayValue();
							}
							if ($item->getItemInfo()->getProductInfo() != null && $item->getItemInfo()->getProductInfo()->getItemDimensions() != null) {
								$dimensions = $item->getItemInfo()->getProductInfo()->getItemDimensions();
							}
							$title = $item->getItemInfo()->getTitle()->getDisplayValue();
							
							if ($item->getItemInfo()->getByLineInfo()->getBrand() != null) {
								$brand = json_decode($item->getItemInfo()->getByLineInfo()->getBrand(), true);
								$brand = $brand["DisplayValue"];
							}
							if ($item->getItemInfo()->getByLineInfo()->getManufacturer() != null) {
								$manufacturer = json_decode($item->getItemInfo()->getByLineInfo()->getManufacturer(), true);
								$manufacturer = $manufacturer["DisplayValue"];
							}
							if ($item->getItemInfo()->getByLineInfo()->getContributors() != null) {
								$contributors = json_decode($item->getItemInfo()->getByLineInfo()->getContributors(), true);
								$contributors = $contributors["DisplayValue"];
							}
							//echo "<b>$title</b> - $color $brand - $manufacturer <br><br>";
							
							if ($item->getItemInfo()->getFeatures() != null) {
								$features = json_decode($item->getItemInfo()->getFeatures(), true);
								$featarray = array();
								foreach($features["DisplayValues"] as $feat) {
									$featarray[] = $feat;
								}
							}
						}						
						if ($item->getOffers() != null) {
							$offersummaries = $item->getOffers()->getSummaries();

							$offersz = array();	
							foreach($offersummaries as $offersummary) {
								$offerCount = $offersummary->getOfferCount();
								$condition = json_decode($offersummary->getCondition(), true);	
								$condition = $condition["Value"];
								$highestPrice = $offersummary->getHighestPrice()->getDisplayAmount();
								$lowestPrice = $offersummary->getLowestPrice()->getDisplayAmount();
								
								$offersz[$condition] = array(
									//"condition" => $condition,
									"low" => $lowestPrice,
									"high" => $highestPrice,
									"count" => $offerCount
								);						
								//echo "<b>$offerCount Offers</b> - Cond: $condition High: $highestPrice Low: $lowestPrice<br><br>";
							}								

						}	
						
						if ($item->getOffers() != null) {
							$listings = $item->getOffers()->getListings();
							$savs = array();
							foreach($listings as $listing) {
								if ($listing->getPrice()->getSavings() != null) {
									$price = $listing->getPrice()->getDisplayAmount();
									$merchant = $listing->getMerchantInfo()->getName();
									$savings = $listing->getPrice()->getSavings()->getDisplayAmount();
									$savs[] = array("merchant" => $merchant, "price" => $price, "savings" => $savings);
								}
							}
						}
					
						$amaitems["items"][] = array(
							"asin" => $asin,
							"url" => $url,
							"title" => $title,
							"color" => $color,
							"model" => $model,
							"warranty" => $warranty,
							"brand" => $brand,
							"manufacturer" => $manufacturer,
							"eans" => $eans,
							"upcs" => $upcs,
							"images" => $allimages,
							"offers" => $offersz,
							"savings" => $savs,
							"features" => $featarray
						);
					}					
				}
				//echo "<pre>";print_r($amaitems);echo "</pre>";
				return $amaitems;	
			}

			if ($searchItemsResponse->getErrors() != null) {
				$return["error"] = "Amazon API Error: " . $searchItemsResponse->getErrors()[0]->getCode() . " " . $searchItemsResponse->getErrors()[0]->getMessage();
				return $return;						
			}
		} catch (ApiException $exception) {
			$emsg = $exception->getCode(); // . " " . $exception->getMessage();
			if ($exception->getResponseObject() instanceof ProductAdvertisingAPIClientException) {
				$errors = $exception->getResponseObject()->getErrors();
				foreach ($errors as $error) {
					$emsg .= " Error: " . $error->getCode() . " " . $error->getMessage();
				}
			} else {
				$emsg .= " Error: " . $error->getResponseBody();				
			}
			
			$return["error"] = "Amazon API Error: " . $emsg;
			return $return;				
		} catch (Exception $exception) {
			$return["error"] = "Amazon API Error: " . $exception->getMessage();
			return $return;			
		}		
		
	}
	
	function api_send_amazon_request($requrl,$keyword,$num,$start,$optionsarray) {
		
		$amares = $this->api_send_amazon_request_v5($requrl,$keyword,$num,$start,$optionsarray);
		
		if(empty($amares)) {
			$return["error"] = "Amazon API request was not accepted.";return $return;					
		} else {
			return $amares;			
		}
	
		$start2 = $start / 10;
		$start2 = (string) $start2; 
		$start2 = explode(".", $start2);
		$page=(int)$start2[0];	
		$page++;				
		$this->start=(int)$start2[1]; 	
		$this->num=$num; 
		
		$params["MinPercentageOff"] = "";$params["MinimumPrice"] = "";$params["MaximumPrice"] = "";
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinPercentageOff"] = $optionsarray["minoff"]["value"];}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinimumPrice"] = $optionsarray["minprice"]["value"] * 100;}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MaximumPrice"] = $optionsarray["maxprice"]["value"] * 100;}		
		
		$params = array(
			"Operation"=>"ItemSearch",
			"BrowseNode"=>$optionsarray["browsenode"]["value"],
			"AssociateTag"=>$optionsarray["affid"]["value"],
			"Keywords"=>$keyword,
			"SearchIndex"=>$optionsarray["searchindex"]["value"],
			"MerchantId"=>"All",
			"ItemPage"=>$page,
			"ReviewSort"=>"-HelpfulVotes",
			"TruncateReviewsAt"=>"5000",
			"IncludeReviewsSummary"=>"False",
			"ResponseGroup"=>"Medium,Reviews"
		);
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinPercentageOff"] = $optionsarray["minoff"]["value"];}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MinimumPrice"] = $optionsarray["minprice"]["value"] * 100;}
		if($optionsarray["searchindex"]["value"] != "All") {$params["MaximumPrice"] = $optionsarray["maxprice"]["value"] * 100;}
		
		$requrl = str_replace("{region}", $optionsarray["region"]["value"], $requrl);	
	
		$method = "GET";
		$host = "webservices.amazon.".$optionsarray["region"]["value"];

		$uri = "/onca/xml";

		$params["Service"] = "AWSECommerceService";
		$params["AWSAccessKeyId"] = $optionsarray["public_key"]["value"];
		
		$t = time() + 800;
		$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z",$t);	
		$params["Version"] = "2011-08-01";
		ksort($params);
		
		$canonicalized_query = array();
		foreach ($params as $param=>$value) {
			$param = str_replace("%7E", "~", rawurlencode($param));
			$value = str_replace("%7E", "~", rawurlencode($value));
			$canonicalized_query[] = $param."=".$value;
		}
		$canonicalized_query = implode("&", $canonicalized_query);
		$string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;   
		$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $optionsarray["private_key"]["value"], True));  
		$signature = str_replace("%7E", "~", rawurlencode($signature));  
		$request = $requrl."?".$canonicalized_query."&Signature=".$signature; 

		return $this->api_send_request($request);		
	}
	
	function api_send_request($request,$source="",$optionsarray="",$json=0) {
		libxml_use_internal_errors(true);
		
		if($this->debug == 1) {echo "<br>--- Request ---<br>".$request."<br>------------------<br>";}

		if($source == "bingnews") {
			$request = str_replace("/v5.0/", "/v7.0/", $request);
			
			$headers = "Ocp-Apim-Subscription-Key: ".$optionsarray["appid"]["value"]."\r\n";
			$options = array ('http' => array (
									  'header' => $headers,
									  'method' => 'GET' ));		
			$context = stream_context_create($options);
			$result = file_get_contents($request, false, $context);
	
			$res2 =  json_encode(json_decode($result), JSON_PRETTY_PRINT);

			$pxml = json_decode($res2);
			$xmlize = new XMLSerializer;
			if(empty($pxml)) {$return["error"] = "No content could be found.";return $return;}
			$response = $xmlize->generateValidXmlFromObj($pxml);
			$pxml = simplexml_load_string($response);	

		} else {

			if ( function_exists('curl_init') ) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
				//if($json == 1 || $source == "amazon") {			//} //removed for EVENTFUL
				if($source != "eventful") {curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);}
				curl_setopt($ch, CURLOPT_URL, $request);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);	
				if($source == "commissionjunction") {curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Host: link-search.api.cj.com',
					'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8',
					'Authorization: '.$optionsarray["appid"]["value"],
					'Content-Type: application/xml'
					));	}			
				$response = curl_exec($ch);
				if (!$response) {
					$return["error"] = __("cURL Error Number ","wprobot").curl_errno($ch).": ".curl_error($ch);	
					return $return;
				}		
				curl_close($ch);
			} else { 				
				$response = @file_get_contents($request);
				if (!$response) {
					$return["error"] = __("cURL is not installed on this server!","wprobot");	
					return $return;		
				}
			}

			if($json == 1) {
				$pxml = json_decode($response);		
		
				$xmlize = new XMLSerializer;
				if(empty($pxml)) {$return["error"] = "No content could be found.";return $return;}
				$response = $xmlize->generateValidXmlFromObj($pxml);
				$pxml = simplexml_load_string($response);	

			} else {
				$pxml = simplexml_load_string($response);		
			}

		}		

		if($this->debug == 1) {echo "<br>--- RESPONSE ---<br><pre>".print_r($pxml)."</pre><br>------------------<br>";}

		if ($pxml === false) {
			$pxml = simplexml_load_file($request); 
			if ($pxml === false) {	
				$emessage = __("Failed loading XML, errors returned: ","wprobot");
				foreach(libxml_get_errors() as $error) {
					$emessage .= $error->message . ", ";
				}	
				libxml_clear_errors();
				$return["error"] = $emessage;	
				return $return;		
			} else {
				return $pxml;
			}			
		} else {
			//print_r($pxml);
			return $pxml;
		}
	}
	
	///////////////////////////// COMMENTS /////////////////////////////////
	
	function youtube_get_full_desc($vid) {

		$key = $this->modulearray["options"]["youtube"]["options"]["appid"]["value"];
		$request = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$vid."&key=".$key;

		if ( function_exists('curl_init') ) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $request);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$response = curl_exec($ch);
			if (!$response) {
				return "";
			}		
			curl_close($ch);
		} else { 				
			$response = @file_get_contents($request);
			if (!$response) {
				return "";
			}
		}
		
		$pxml = json_decode($response);
		//echo "<pre>";print_r($pxml);echo "</pre>";
		
		if ($pxml === False) {
			return "";
		} else {
			if(isset($pxml->items)) {
				foreach ($pxml->items as $tttt) {
					if (isset($tttt->snippet->description)) {
						return $tttt->snippet->description;
					}	
				}
			}	

			return "";
		}
	}	
	
	function api_comment_request($id, $source) {
		
		if($source == "amazon") {
			return $this->parse_amazon_reviews($id);
		} elseif($source == "yahooanswers") {
			$commenturl = 'http://answers.yahooapis.com/AnswersService/V1/getQuestion?appid='.$this->modulearray["options"]["yahooanswers"]["options"]["appid"]["value"].'&question_id='.$id;
		} elseif($source == "flickr") {	
			$commenturl = "https://api.flickr.com/services/rest/?method=flickr.photos.comments.getList&api_key=".$this->modulearray["options"]["flickr"]["options"]["appid"]["value"]."&photo_id=".$id;
		} elseif($source == "youtube") {
			$commenturl = "https://www.googleapis.com/youtube/v3/commentThreads?key=".$this->modulearray["options"]["youtube"]["options"]["appid"]["value"]."&videoId=".$id."&textFormat=plainText&maxResults=50&part=id,snippet";	
		}
		
		//echo "<br><br>".$commenturl."<br><br>";
		
		if ( function_exists('curl_init') ) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $commenturl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$response = curl_exec($ch);
			curl_close($ch);
		} else { 				
			$response = @file_get_contents($commenturl);
		}
	
		if ($response !== False) {
			if($source == "youtube") {
				$commentsFeed = json_decode($response);
			} else {
				$commentsFeed = simplexml_load_string($response);			
			}
		}  

		//echo "<pre>";print_r($commentsFeed);echo "</pre>";
		
		$comments = array();

		if($source == "yahooanswers") {
			if(isset($commentsFeed->Question->Answers->Answer)) {
				foreach ($commentsFeed->Question->Answers->Answer as $answer) {
					$comments[] = array("author" => strval($answer->UserNick), "content" => strval($answer->Content));
				}
			}
		} elseif($source == "flickr") {	
			if($commentsFeed->comments->comment) {
				foreach ($commentsFeed->comments->comment as $comment) {
					$attrs = $comment->attributes();
					$UserNick = (array) $attrs['authorname']; 
					$comment = (array) $comment;
					$comments[] = array("author" => $UserNick[0], "content" => $comment[0]);	
				}
			}
		} elseif($source == "youtube") {	
			if(isset($commentsFeed->items)) {
				foreach ($commentsFeed->items as $comment) {
					$ctxt = htmlspecialchars($comment->snippet->topLevelComment->snippet->textDisplay);
					$cauth = $comment->snippet->topLevelComment->snippet->authorDisplayName;
					$comments[] = array("author" => $cauth, "content" => $ctxt);
				}
			}
		}		
		
		return $comments;
	}
	
	function parse_comments($content, $comments) {
		// Replace Comments tag in content
		preg_match('#\{comments(.*)\}#iU', $content, $rmatches);
		if ($rmatches[0] != false) {
			$cpost = "";
			$cnum = substr($rmatches[1], 1);
			for ($i = 0; $i < count($comments); $i++) {
				if($i == $cnum) {break;} else {	
					$cpost .= "<p><i>".$comments[$i]["author"].":</i> ".$comments[$i]["content"]."</p>";
				}
			}
			return str_replace($rmatches[0], $cpost, $content);				
		} else {
			return false;
		}
	}
	
	function get_comments($curl, $source) {
	
		$commentcontent = $this->api_comment_request($curl, $source);
		if(!empty($commentcontent)) {
			return $commentcontent;	
		} else {
			return false;							
		}						
	}	
	
	function parse_amazon_reviews($id) {
		$revcontent = file_get_contents($id); 
		if (preg_match('~<body[^>]*>(.*?)</body>~si', $revcontent, $body)) { 
			$reviewsnoiframe = str_replace('class="crVotingButtons">', "", $body[1]); 
		} else {
			$reviewsnoiframe = "";
		} 

		$dom = new DOMDocument();
		@$dom->loadHTML($reviewsnoiframe);	

		$xpath = new DOMXPath($dom);
		$paras = $xpath->query("//table[@class='crIFrameReviewList']//tr/td/div");
		
		$reviews = array();
		for ($y = 0;  $y < $paras->length; $y++ ) {  //$paras->length
			$para = $paras->item($y);
		
			$review = $dom->saveXml($para);
			$review = str_replace('Permalink', "", $review);
			$review = str_replace('Report abuse', "", $review);
			$review = str_replace('See all my reviews', "", $review);
			$review = str_replace('<img src="http://g-ecx.images-amazon.com/images/G/01/x-locale/communities/reputation/c7y_badge_rn_1._V192249968_.gif" width="70" align="absmiddle" alt="(REAL NAME)" height="15" border="0" />', "", $review);
			$review = strip_tags($review, '<p><a><img><br>');
			$reviews[$y]["content"] = $review;
			$reviews[$y]["author"] = "user".rand(0,9999);		
		}
		return $reviews;
	}	
	
	///////////////////////////// PARSER and HELPER FUNCTIONS /////////////////////////////////	
	
	function get_custom_fields($template, $keyword) {
		// Parse Custom Fields, format: [customfields:name|{tag};name2|{tag2}]
		preg_match_all('#\[customfields(.*)\]#smiU', $template, $matches, PREG_SET_ORDER);
		if (!empty($matches) && is_array($matches)) {
			$customfields = array();
			foreach($matches as $match) {
			
				$template = str_replace($match[0], "", $template);				
				
				$match[1] = substr($match[1], 1);		
				$cfs = explode(";",$match[1]);
				if(is_array($cfs)) {
					foreach($cfs as $cf) {
						$cfd = explode("|",$cf);
						$cfd[1] = str_replace("{keyword}", $keyword, $cfd[1]);
						$customfields[] = array("name" => $cfd[0], "value" => $cfd[1]);
					}
				}
			}
			return $customfields;
		}
		return false;	
	}
	
	function parse_options_unique_values($source, $selector, $value) {

		if(empty($this->title) && $this->source_vals[$source]["title"] == $selector) {$this->title = $value;}
		if(empty($this->unique) && $this->source_vals[$source]["unique"] == $selector) {$this->unique = $value;}	
	
		//if(empty($this->title) && $this->sourceinfos["sources"][$source]["title"] == $selector) {$this->title = $value;}
		//if(empty($this->unique) && $this->sourceinfos["sources"][$source]["unique"] == $selector) {$this->unique = $value;}
	}		
	
	function parse_special_tags($template, $value, $options, $depth, $subselector) {
	
		foreach($this->special_tags as $stag) {
		
			if(strpos($template, "[".$stag) === false) {continue;}
		
			preg_match('#\['.$stag.':'.$subselector.'\](.*)\[/'.$stag.':'.$subselector.'\]#smiU', $template, $matches);
			if ($matches[0] != false) {
				$template = $this->parse_special_tag($stag, $template, $matches, $value, $options, $depth, $subselector);
			} else {
				$template = str_replace(array('['.$stag.':'.$subselector.']','[/'.$stag.':'.$subselector.']'), "", $template);		
			}				
		}		
		return $template;
	}
	
	function parse_special_tag($stag, $template, $matches, $value, $options, $depth, $subselector) {
		if($stag == "IF") {
			if(empty($value)) {
				$template = str_replace($matches[0], "", $template);
			} else {
				$template = str_replace(array('['.$stag.':'.$subselector.']','[/'.$stag.':'.$subselector.']'), "", $template);			
			}
		} elseif($stag == "ALL") {
			if($this->debug == 1) {echo "-------------------------- INSIDE ALL LOOP --------------------------<br>";	}		
			$subtemplate = $matches[0];	
			$subtemplate = str_replace("|".$subselector."|", $value, $subtemplate);			
			foreach($value->children() as $subs => $val) {
				$subtemplate = str_replace("|".$subs."|", $val, $subtemplate);
			}
			$subtemplate = str_replace(array('[ALL:'.$subselector.']','[/ALL:'.$subselector.']'), "", $subtemplate);					
			$template = str_replace($matches[0], $subtemplate.$matches[0], $template);	
		} elseif($stag == "WHILE") {
			if($this->debug == 1) {echo "-------------------------- INSIDE WHILE LOOP --------------------------<br>";}			
			$rtemplate = "";
			foreach($value->children() as $subs => $val) {
				$subtemplate = $matches[0];				
				$subtemplate = $this->parse_children_recursive($val,$subtemplate,$options,$depth+1,5,$source,2,"","");
				$subtemplate = str_replace(array('[WHILE:'.$subselector.']','[/WHILE:'.$subselector.']'), "", $subtemplate);	
				$rtemplate .= $subtemplate;
			}	
				$template = str_replace($matches[0], $rtemplate, $template);	
			if($this->debug == 1) {echo "-------------------------- END WHILE LOOP --------------------------<br>";}		
		} elseif($stag == "SPEC") {
			$subtemplate = $matches[0];			
			if($this->debug == 1) {echo "-------------------------- INSIDE SPEC LOOP --------------------------<br>";	}		
			foreach($value->children() as $subs => $val) {
				$subtemplate = str_replace("|".$subs."|", $val, $subtemplate);
				$subtemplate = str_replace(array('[SPEC:'.$subselector.']','[/SPEC:'.$subselector.']'), "", $subtemplate);	
			}	
				$template = str_replace($matches[0], $subtemplate, $template);		
		}
		return $template;
	}	
	
	function parse_random_tags($content) {
	
		if(strpos($content, "[select") === false && strpos($content, "[random") === false) {return $content;}

		preg_match_all('#\[select(.*)\]#smiU', $content, $matches, PREG_SET_ORDER);
		if ($matches) {
			foreach($matches as $match) {
				$match[1] = substr($match[1], 1);
				$paras = explode("|",$match[1]);
				$randp = array_rand($paras);
				
				$content = str_replace($match[0], $paras[$randp], $content);			
			}
		}	

		preg_match_all('#\[random(.*)\](.*)\[/random\]#smiU', $content, $matches, PREG_SET_ORDER);
		if ($matches) {
			foreach($matches as $match) {
				$match[1] = substr($match[1], 1);
				if($match[1] >= rand(1,100)) {	
					$content = str_replace($match[0], $match[2], $content);	
				} else {
					$content = str_replace($match[0], "", $content);				
				}
			}
		}
		
		return $content;
	}	
	
	function api_apply_content_actions($items) { // Replace Keywords   --- TO DO: use better CUSTOM EXPRESSION search
		if(!empty($items) && is_array($items)) {
			$replaces = $this->modulearray["general"]["options"]["replace_keywords"]["value"];

			$replaces = stripslashes($replaces);
			$replaces = str_replace("\r", "", $replaces);
			$replaces = explode("\n", $replaces);    

			if(!empty($replaces) && is_array($replaces)) {
				foreach($items as $module => $mitems) {
					foreach($mitems as $num => $item) {

						foreach($replaces as $replace) {
							if($replace != "") {
								$replace = explode("|", $replace);  
								$from = $replace[0];
								$to = str_replace('\"', "", $replace[1]);
								$chance = $replace[2];
								$code = $replace[3];
								if($chance >= rand(1,100)) {
									$from = trim($from);
									$to = trim($to);
									if($code == "1") {
										$items[$module][$num]["content"] = str_replace($from, $to, $items[$module][$num]["content"]);
										$items[$module][$num]["title"] = str_replace($from, $to, $items[$module][$num]["title"]);					
									} else {
										$items[$module][$num]["content"] = str_replace(" ".$from, " ".$to, $items[$module][$num]["content"]);
										$items[$module][$num]["title"] = str_replace(" ".$from, " ".$to, $items[$module][$num]["title"]);				
									}
								}								
							}
						}
					}
				}
			}	
		}
		return $items;
	}	
	
	///////////////////////////// EBAY /////////////////////////////////	
		
	function api_ebay_process($keyword,$num,$start,$source,$thetemplate,$options) {
	
		$x = 0;
		for ($i = 1; $i <= $num; $i++) {
		
			$template = $thetemplate;
			
			foreach($options as $oname => $oarray) {
				$template = str_replace("[OPTION:".$oname."]", $oarray["value"], $template);
			}	
			
			$template = str_replace("{keyword}", $keyword, $template);
			$template = str_replace("{num}", $i, $template);

			$items[$x]["source"] = $source;				
			$items[$x]["unique"] = "";
			$items[$x]["title"] = "";
			$items[$x]["content"] = $template;	
			$x++;
		}

		return $items;
	}	
}

class XMLSerializer {

    // functions adopted from http://www.sean-barton.co.uk/2009/03/turning-an-array-or-object-into-xml-using-php/

    public static function generateValidXmlFromObj(stdClass $obj, $node_block='nodes', $node_name='node') {
        $arr = get_object_vars($obj);
        return self::generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    public static function generateValidXmlFromArray($array, $node_block='nodes', $node_name='node') {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';

        $xml .= '<' . $node_block . '>';
        $xml .= self::generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';

        return $xml;
    }

    private static function generateXmlFromArray($array, $node_name) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }

}

?>