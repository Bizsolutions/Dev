<?php
/**
Plugin Name: WP Robot 5 - Comparison Page Creator
Plugin URI: http://wprobot.net/comparison-page-creator/
Version: 0.92
Description: Addon for WP Robot 5: Easily build any number of product comparison pages.
Author: WP Robot
Author URI: http://www.wprobot.net/
License: Commercial. For personal use only. Not to give away or resell
Text Domain: wprobot
*/

define('wpr5_comparison_DIRPATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );

function wpr5_add_comparison_pages_toadmin() {

	$affehook = add_submenu_page('wpr5-automation', __('Comparisons', 'wprobot'), __('Comparisons', 'wprobot'), "manage_options", 'wpr-affbuilder', 'wpr5_affbuilder_page');	
	if(isset($_GET['page']) && $_GET['page'] == 'wpr-affbuilder' ) {
		add_action('admin_head', 'wpr5_affbuilder_page_head');	
		add_action( "admin_print_scripts-$affehook", 'wpr5_affbuilder_page_print_scripts' );	
	}		
	
}
add_action('admin_menu', 'wpr5_add_comparison_pages_toadmin');

/*********************************************************************************************************************************************/
/*                                                                 COMPARISON PAGE                                                              */           
/*********************************************************************************************************************************************/

function simplify_feat($feat) {

	$feat = strtolower($feat);
	$feat = str_replace(' or ', '', $feat);
	$feat = trim(preg_replace('/\s*\([^)]*\)/', '', $feat));
	$feat = preg_replace('/[^A-Za-z0-9\-]/', '', $feat);
	$feat = str_replace(' ', '', $feat);
	return $feat;
}

function wpr5_update_affpage_options($category, $products) {
	return update_option("wpr5_affpage_products", $products);	

	if(empty($category) || empty($products)) {return false;}
	$catname = "wpr5_affpage_products_".$category;
	return update_option($catname, $products);	
}

function wpr5_get_affpage_options($category) {
	return get_option("wpr5_affpage_products");
	if(empty($category)) {return false;}
	$catname = "wpr5_affpage_products_".$category;
	return get_option($catname);	
}

add_action( 'wp_head', 'wpr5_add_ajax_library' );
function wpr5_add_ajax_library() {
 
    $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html .= '</script>';
 
    echo $html;
} 

function wpr5_affpages_load_scripts() {	
    //if( !is_front_page()) {
		wp_register_script( 'wpr5_aff_compare', plugins_url( 'affpagecss/affcompare.js' , __FILE__ ), array('jquery'));
		wp_enqueue_script('wpr5_aff_compare');
	//}	
}
add_action('wp_enqueue_scripts', 'wpr5_affpages_load_scripts');

add_action('wp_ajax_nopriv_affpagesajax', 'wpr5_affpages_ajax');
add_action('wp_ajax_affpagesajax', 'wpr5_affpages_ajax');
function wpr5_affpages_ajax() {

	$asin1 = trim($_POST["asin1"]);	
	$asin2 = trim($_POST["asin2"]);	

	$structure = get_option("wpr5_affpage_structure");
	
	if(!empty($structure["vsids"][$asin2.$asin1])) {
		$vsid = $structure["vsids"][$asin1.$asin2];
		$vslink = get_permalink($vsid);
		echo json_encode(array("link" => $vslink));
		exit;	
	} else {
		echo json_encode(array("error" => "No comparison found."));
		exit;		
	}
}

/*================================================================ 1. Functions =============================================================*/

function bc_delete_pages() {
	$structure = get_option("wpr5_affpage_structure");
	
	//echo "<pre>";print_r($structure);echo "</pre>";
	
	foreach($structure["vsids"] as $asin => $pageid) {
		wp_delete_post( $pageid, 1 );	
		unset($structure["vsids"][$asin]);
	}
	
	foreach($structure["baseids"] as $cat => $theids) {
		$reviewid = $theids["reviews"];
		$compareid = $theids["compare"];

		$args = array( 
			'parent' => $reviewid,
			//'post_type' => 'page'
		);

		$posts = get_pages( $args );

		if (is_array($posts) && count($posts) > 0) {
			foreach($posts as $post){
				wp_delete_post($post->ID, true);
			}
		}	
		wp_delete_post( $reviewid, 1 );	
		wp_delete_post( $compareid, 1 );	
	}
	
	update_option("wpr5_affpage_structure", $structure);
}

function wpr5_affpage_keyword_research($keyword) {
	if(empty($keyword)) {return false;}
	echo $keyword . "<br>";
	$results = array();
	$url = 'http://api.semrush.com/?type=phrase_all&key=183152c6595baf53966f25d64cbfbb83&export_columns=Db,Ph,Nq,Cp,Co&phrase='.urlencode($keyword).'&database=us';
	$cUrl = curl_init();
	curl_setopt( $cUrl, CURLOPT_URL, $url );
	curl_setopt( $cUrl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $cUrl, CURLOPT_TIMEOUT, 30 );
	curl_setopt( $cUrl, CURLOPT_HTTPHEADER, array ( 'X-Real-IP', $params[ 'uip' ] ) );
	$result = curl_exec( $cUrl );

	if ( curl_getinfo ( $cUrl, CURLINFO_HTTP_CODE ) == 200 ) {
		if(strpos($result, "NOTHING FOUND") !== false) {	
			$results = array(
				"vol" => 0,
				"cpc" => 0,
				"comp" => 0,
			);	
			return $results;			
		} elseif(strpos($result, "ERROR") !== false) {
			return false;
		} else {
			// Database;Phrase;Search Volume;CPC;Competition
		
			$result = explode(PHP_EOL, $result);
			$result = explode(";", $result[1]);

			$results = array(
				"vol" => $result[2],
				"cpc" => $result[3],
				"comp" => $result[4],
			);
			
			return $results;
		}
	}
}

function get_keyword_details($products, $primary_kw, $primary_kw_s) {
	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		if(empty($item["kw"])) {
			$kw = $item["Brand"] . " " . $item["Model"];

			$results = wpr5_affpage_keyword_research($kw);
	
			if(is_array($results) && !empty($results)) {
				$products[$asin]["kw"] = $results;	
			}	
		}
	}
	wpr5_update_affpage_options($category, $products);

	// related keywords
	$structure = get_option("wpr5_affpage_structure");
	$structure["kws"]["main"] = $primary_kw;
	$structure["kws"]["mainsing"] = $primary_kw_s;

	update_option("wpr5_affpage_structure", $structure);	
}

function wpr5_filter_features($features) {
	if(empty($features) || !is_array($features)) {return;}

	unset($features["manufacturer.part.number"]);
	unset($features["Amps"]);
	unset($features["Item Package Quantity"]);
	unset($features["Amps"]);
	unset($features["Power Tool Product Type"]);

	if(!empty($features["Cordless/ Corded"])) {$features["Power Type"] = $features["Cordless/ Corded"];unset($features["Cordless/ Corded"]);}
	if(!empty($features["Manufacturer Warranty"])) {$features["Warranty"] = $features["Manufacturer Warranty"];unset($features["Manufacturer Warranty"]);}
	if(!empty($features["Battery Cell Type"])) {$features["Battery Power Type"] = $features["Battery Cell Type"];unset($features["Battery Cell Type"]);}
	if(!empty($features["Tools Included"])) {$features["Package Contents"] = $features["Tools Included"];unset($features["Tools Included"]);}
	
	return $features;
}

function wpr5_load_affpages_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'affpages_style', $plugin_url . 'affpagecss/affpages.css' );
}
add_action( 'wp_enqueue_scripts', 'wpr5_load_affpages_css' );

//delete_option("wpr5_affpage_structure");

function wpr5_save_all_images($products) {

	$structure = get_option("wpr5_affpage_structure");

	foreach($products as $asin => $item) {	
		// 1. create attachment images	
		if(empty($item["aid"]) && !empty($item["LargeImage"])) {
			$img = $item["LargeImage"];
			
			$ext = pathinfo($img, PATHINFO_EXTENSION);
			$tmp = download_url( $img );
			$tmppath = pathinfo( $tmp );  
						
			$newfn = urlencode(str_replace(" ", "-", $item["Brand"] . "-" . $item["Model"] . "-" . $item["Category"]));
			$newpth = $tmppath['dirname'] . "/". $newfn . "." . $tmppath['extension'];   

			rename($tmp, $newpth); 
			$tmp = $newpth;    				

			$file_array['name'] = $newfn . "." . $ext;   
			$file_array['tmp_name'] = $tmp;

			if ( is_wp_error( $tmp ) ) {				
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
				continue;
			}

			$id = media_handle_sideload( $file_array, $insert );

			if ( !is_wp_error( $id ) ) {
				//$url  = wp_get_attachment_url( $id );
				//$thumb = wp_get_attachment_thumb_url( $id );			
				$products[$asin]["aid"] = $id;
			}		
		}
		wpr5_update_affpage_options($category, $products);	
	}
	echo '<div class="updated below-h2"><p>'.__('Images have been saved. You can create your pages now.', 'wprobot').'</p></div>';
}

function wpr5_all_page_creator($products, $status = 'publish', $user = 1, $categorych = "", $create_comparison = 1, $create_reviews = 1, $create_frontpage = 1) {
	
	$structure = get_option("wpr5_affpage_structure");
	$wmreviews = get_option("wpr_wm_reviews");
	
	$structure["kws"]["main"] = ucwords($categorych);
	$structure["kws"]["mainsing"] = ucwords($categorych);
	
	//echo "<pre>";print_r($structure);echo "</pre>";
	
	// 0. create nav menu
	/*$menu_name = 'Default Comparison Menu';
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if( !$menu_exists){
		$menu_id = wp_create_nav_menu($menu_name);
		$structure["menu_id"] = $menu_id;
		update_option("wpr5_affpage_structure", $structure);
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Home'),
			'menu-item-classes' => 'home',
			'menu-item-url' => home_url( '/' ), 
			'menu-item-status' => 'publish'));
		set_theme_mod('nav_menu_locations', array("primary" => $menu_id));	
	} else {
		$menu_id = $structure["menu_id"];
	}*/
	
	// sort by brands
	uasort($products, function($a, $b){ return strcmp($a["Brand"], $b["Brand"]); });
	//echo "<pre>";print_r($products);echo "</pre>";die();
	
	$remembarray = array();$homepagelinks = array();
	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		
		if(!empty($categorych) && $item["Category"] != $categorych) {continue;}		
		
		$cat = $item["Category"];

		// 0. create parent pages if not existing
		if(empty($structure["baseids"][$cat]["reviews"]) || get_post($structure["baseids"][$cat]["reviews"]) == null) {
			$my_post = array(
			  'post_title'    => "$cat Reviews",
			 // 'post_name'    => "reviews",
			  'post_content'  => "",
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type' => "page"
			);
			$rid = wp_insert_post( $my_post );	
			update_post_meta( $rid, '_wp_page_template', 'page-templates/full-width.php' );				
			$structure["baseids"][$cat]["reviews"] = $rid;			
		}
		if(empty($structure["baseids"][$cat]["compare"]) || get_post($structure["baseids"][$cat]["compare"]) == null) {
			$my_post = array(
			  'post_title'    => "Compare The Best $cat Of ".date("Y"),
			  'post_name'    => sanitize_title_with_dashes("Compare $cat"),
			  'post_content'  => "",
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type' => "page"
			);
			$rid = wp_insert_post( $my_post );
			update_post_meta( $rid, '_wp_page_template', 'page-templates/full-width.php' );				
			$structure["baseids"][$cat]["compare"] = $rid;
			/*wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-title' =>  "$cat",
				//'menu-item-classes' => 'home',
				'menu-item-url' => get_permalink($rid), 
				'menu-item-status' => 'publish'));		*/			
		}		
		update_option("wpr5_affpage_structure", $structure);
	
		// 1. create attachment images	
		if(empty($item["aid"]) && !empty($item["LargeImage"])) {
			$img = $item["LargeImage"];
			
			$ext = pathinfo($img, PATHINFO_EXTENSION);
			$tmp = download_url( $img );
			$tmppath = pathinfo( $tmp );  
						
			$newfn = urlencode(str_replace(" ", "-", $item["Brand"] . "-" . $item["Model"] . "-" . $item["Category"]));
			$newpth = $tmppath['dirname'] . "/". $newfn . "." . $tmppath['extension'];   

			rename($tmp, $newpth); 
			$tmp = $newpth;    				

			$file_array['name'] = $newfn . "." . $ext;   
			$file_array['tmp_name'] = $tmp;

			if ( is_wp_error( $tmp ) ) {				
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
				continue;
			}

			$id = media_handle_sideload( $file_array, $insert );

			if ( !is_wp_error( $id ) ) {
				//$url  = wp_get_attachment_url( $id );
				//$thumb = wp_get_attachment_thumb_url( $id );			
				$products[$asin]["aid"] = $id;
			}		
		}

		// 2. create comparison pages 
		if($create_comparison == 1) {
			$vspagelinks = "";		

			$featurelist = "";
			if(empty($item["Features"]) && !empty($item["bb"]["feat"])) {$item["Features"] = $item["bb"]["feat"];}
			
			if(!empty($item["Features"])) {
				$featurelist .= '<ul>';
				foreach($item["Features"] as $feat) {
					$featurelist .= '<li>'.$feat.'</li>';
				}
				$featurelist .= '</ul>';
			}		
			
			$thumb  = wp_get_attachment_thumb_url( $products[$asin]["aid"] );
			$thecat = $item["Category"];
			$revlink = get_permalink($item["rid"]);

			//$homepagelinks[$thecat][] = '<div class="hp-prod"><a href="'.$revlink.'"><img src="'.$thumb.'" /><span class="hp-brand">'.$item["Brand"] . '</span><strong class="hp-model">' . $item["Model"].'</strong></a></div>';		
			
			$homepagelinks[$thecat][] = '
				<div class="hp-prod2"><input name="comp" value="'.$asin . '" id="'.$asin . '" type="checkbox"><label class="'.$thecat.'" for="'.$asin . '"><img alt="'.$item["Brand"] . ' '.$item["Model"].'" src="'.$thumb.'" /><span class="hp-brand">'.$item["Brand"] . '</span><strong class="hp-model">' . $item["Model"].'</strong></label></div>';		

				
			if(!empty($item["kw"]) && $item["kw"]["vol"] > 0) {	
				$popularlinks[$thecat][] = '
					<div class="hp-prod2"><a href="'.$revlink.'"><label class="'.$thecat.'" for="'.$asin . '"><img alt="'.$item["Brand"] . ' '.$item["Model"].'" src="'.$thumb.'" /><span class="hp-brand">'.$item["Brand"] . '</span><strong class="hp-model">' . $item["Model"].'</strong></label></a></div>';		
			}
			
			$reviewslinks[$thecat][] = '
				<div class="hp-prod2"><a href="'.$revlink.'"><label class="'.$thecat.'" for="'.$asin . '"><img alt="'.$item["Brand"] . ' '.$item["Model"].'" src="'.$thumb.'" /><span class="hp-brand">'.$item["Brand"] . '</span><strong class="hp-model">' . $item["Model"].'</strong></label></a></div>';			

			$vsvspagelinks = "";$vsvsmax = 0;$vvv = 0;
			/*foreach($products as $asin2 => $item2) {
				if($item2 == "deleted" || !is_array($item2)) {continue;}
				if($item2["Category"] != $item["Category"]) {continue;}			
				if($asin2 == $asin) {continue;}	
				$thumb2 = wp_get_attachment_thumb_url( $products[$asin2]["aid"] );	
				if(!empty($structure["vsids"][$asin2.$asin])) {
					$vsid = $structure["vsids"][$asin.$asin2];
					$pmlink = get_permalink($vsid);
				}	

				//if(($asin2 == "190021305953" || $asin2 == "190021295483") && ($asin == "190021305953" || $asin == "190021295483")) {echo "<br>DATATATA $vsid $pmlink";}

				$vsvsmax++;if($vsvsmax <= 12) {
					$vsvspagelinks .= '<a class="vslinks" href="'.$pmlink.'"><img src="'.$thumb2.'" /><span>'.$item["Brand"] . " " . $item["Model"].' vs <br/><strong>'.$item2["Brand"] . " " . $item2["Model"].'</strong></span></a>';		
				}
				
			}*/
			
			foreach($products as $asin2 => $item2) {
				if($item2 == "deleted" || !is_array($item2)) {continue;}	
		
				if($asin2 == $asin) {continue;}		
				if($item2["Category"] != $item["Category"]) {continue;}
				
				// check if other page title already exists, if so skip			
				if(!empty($remembarray["vsids"][$asin2.$asin]) || !empty($remembarray["vsids"][$asin.$asin2])) {
					continue;
				}	

				// create review page links
				$thumb2 = wp_get_attachment_thumb_url( $products[$asin2]["aid"] );			
				if(!empty($structure["vsids"][$asin2.$asin])) {
					$vsid = $structure["vsids"][$asin.$asin2];
					$pmlink = get_permalink($vsid);
				}
				
	if(($asin2 == "190021305953" || $asin2 == "190021295483") && ($asin == "190021305953" || $asin == "190021295483")) {echo "<br>DATATATA $vsid $pmlink";}
			
				
				$vspagelinks .= '<a class="vslinks" href="'.$pmlink.'"><img src="'.$thumb2.'" /><span>Compare to <br/><strong>'.$item2["Brand"] . " " . $item2["Model"].'</strong></span></a>';		
				
				$vsvsmax++;if($vsvsmax <= 12) {
					$vsvspagelinks .= '<a class="vslinks" href="'.$pmlink.'"><img src="'.$thumb2.'" /><span>'.$item["Brand"] . " " . $item["Model"].' vs <br/><strong>'.$item2["Brand"] . " " . $item2["Model"].'</strong></span></a>';		
				}				
				
				$vvv++;
				if($vvv < 3 ) { //&& 'publish' === get_post_status( $vsid )
					$forcat = $item["Category"];
					$maincomppagevslinks[$forcat] .= '<a href="'.$pmlink.'">'.$item["Brand"] . " " . $item["Model"].' vs '.$item2["Brand"] . " " . $item2["Model"].'</a><br/>';	
				}					
				
				// create page title
				$vstitle = $item["Brand"] . " " . $item["Model"] . " vs " . $item2["Brand"] . " " . $item2["Model"];
							
				$vslink = sanitize_title(str_replace(" ", "-", $item["Brand"] . "-" . $item["Model"] . "-vs-" . $item2["Brand"] . "-" . $item2["Model"]));	
				$vslink_opposite = sanitize_title(str_replace(" ", "-", $item2["Brand"] . "-" . $item2["Model"] . "-vs-" . $item["Brand"] . "-" . $item["Model"]));	

				if(!empty($structure["vsids"][$asin2.$asin])) {
					$vsid = $structure["vsids"][$asin2.$asin];
				} else {
					$vsid = "";
				}

				$thefeatures = array();
				if(is_array($item["bb"]["list"])) {
					foreach($item["bb"]["list"] as $feat => $val) {
						$simplefeat = simplify_feat($feat);
						$thefeatures[$simplefeat][$asin] = array("val" => $val, "name" => $feat);
					}
				}			
				if(is_array($item["s3"]["features"]) && count($item["bb"]["list"]) < 25) {
					foreach($item["s3"]["features"] as $feat => $val) {
						$simplefeat = simplify_feat($feat);
						$thefeatures[$simplefeat][$asin] = array("val" => $val, "name" => $feat);
					}
				}

				if(is_array($item2["bb"]["list"])) {
					foreach($item2["bb"]["list"] as $feat => $val) {
						$simplefeat = simplify_feat($feat);
						$thefeatures[$simplefeat][$asin2] = array("val" => $val, "name" => $feat);
					}
				}			
				if(is_array($item2["s3"]["features"]) && count($item2["bb"]["list"]) < 25) {
					foreach($item2["s3"]["features"] as $feat => $val) {
						$simplefeat = simplify_feat($feat);
						$thefeatures[$simplefeat][$asin2] = array("val" => $val, "name" => $feat);
					}
				}

				$featurelist2 = "";
				if(empty($item2["Features"]) && !empty($item2["bb"]["feat"])) {$item2["Features"] = $item2["bb"]["feat"];}
				if(!empty($item2["Features"])) {
					$featurelist2 .= '<ul>';
					foreach($item2["Features"] as $feat) {
						$featurelist2 .= '<li>'.$feat.'</li>';
					}
					$featurelist2 .= '</ul>';
				}			

				$vsfeats = "";$counter = 0;
				foreach($thefeatures as $feat => $itms) {
					if(++$counter % 2 === 0) { $class = ' hihligt';} else {$class = '';}

					if(!empty($itms[$asin]["name"])) {$fname = $itms[$asin]["name"];} else {$fname = $itms[$asin2]["name"];}
					$vsfeats .= '<tr class="'.$class.'">
							<td>'.$fname.'</td>
							<td>'.$itms[$asin]["val"].'</td>
							<td class="emptyrborder">'.$itms[$asin2]["val"].'</td>
						</tr>';
				}

				$cplnk = get_permalink($structure["baseids"][$cat]["compare"]);
				
				$reviewsection = "";
				if((!empty($item["bb"]["rating"]) || !empty($item["wm"]["rating"])) && (!empty($item2["bb"]["rating"]) || !empty($item2["wm"]["rating"]))) {
					$reviewsection = '
						<tr>
							<td class="emptyrborder" colspan="3"><h3>Reviews</h3></td>					
						</tr>					
					';
					if(!empty($item["bb"]["rating"]) || !empty($item2["bb"]["rating"])) {
						$reviewsection .= '<tr>
							<td>BestBuy Rating</td>
							<td>'.$item["bb"]["rating"].'</td>
							<td class="emptyrborder">'.$item2["bb"]["rating"].'</td>
						</tr>';				
					}
					if(!empty($item["wm"]["rating"]) || !empty($item2["wm"]["rating"])) {
						$reviewsection .= '<tr>
							<td>Walmart Rating</td>
							<td>'.number_format($item["wm"]["rating"], 1).'</td>
							<td class="emptyrborder">'.number_format($item2["wm"]["rating"], 1).'</td>
						</tr>';				
					}				
				}
				
				$vscontent = '
				<div class="wpr-site-content">
					<table class="comptable">
						<tr class="comp-thumb">
							<td class="comp-firstrow"></td>
							<td class="comp-secrow"><img alt="'.$item["Brand"]." ".$item["Model"].'" src="'.$thumb.'" /></td>
							<td class="comp-secrow"><img alt="'.$item2["Brand"]." ".$item2["Model"].'" src="'.$thumb2.'" /></td>
						</tr>
						<tr class="comp-name">
							<td>Model</td>
							<td>'.$item["Brand"].'<br/><strong>'.$item["Model"].'</strong></td>
							<td class="emptyrborder">'.$item2["Brand"].'<br/><strong>'.$item2["Model"].'</strong></td>
						</tr>
						
						<tr>
							<td class="emptyrborder" colspan="3"><h3>Basic Info</h3></td>					
						</tr>		
						
						<tr>
							<td>Suggested Price</td>
							<td>'.$item["ListPrice"].'</td>
							<td class="emptyrborder">'.$item2["ListPrice"].'</td>
						</tr>
						
						<tr>
							<td>Price</td>
							<td><a class="" target="_blank" href="'.$item["DetailPageURL"].'">Check Price</a>*</td>
							<td class="emptyrborder"><a class="" target="_blank" href="'.$item2["DetailPageURL"].'">Check Price</a>*</td>
						</tr>					

						<tr>
							<td>Release</td>
							<td>'.$item["s3"]["release"].'</td>
							<td class="emptyrborder">'.$item2["s3"]["release"].'</td>
						</tr>	

						'.$reviewsection.'					
						
						<tr>
							<td class="emptyrborder" colspan="3"><h3>Overview</h3></td>					
						</tr>	

						<tr>
							<td>Top Features</td>
							<td>'.$featurelist.'</td>
							<td class="emptyrborder">'.$featurelist2.'</td>
						</tr>					
						
						<tr>
							<td class="emptyrborder" colspan="3"><h3>All Features</h3></td>					
						</tr>	
						'.$vsfeats.'

						<tr>
							<td class="emptyrborder" colspan="3"><h3>More Information</h3></td>					
						</tr>	

						<tr class="comp-last">
							<td></td>
							<td><a class="amalink" target="_blank" href="'.$item["DetailPageURL"].'">View Details & Price*</a></td>
							<td class="emptyrborder"><a class="amalink" target="_blank" href="'.$item2["DetailPageURL"].'">View Details & Price*</a></td>
						</tr>					
					</table>
					<p>Not interested in the '.$structure["kws"]["main"].' compared on this page? Simply go back to our main article to <a href="'.$cplnk.'">start a new comparison between other '.strtolower($cat).' now</a>!</p>
					<p>* affiliate links</p>
				</div><div class="wpr-widget-area">
					<h3>Related Comparisons</h3>'.$vsvspagelinks.'
				</div>
				';
				
				

				if(!empty($categorych)) {

					$my_post = array(
					  'ID'   => $vsid,
					  'post_title'    => $vstitle,
					  'post_name'    => $vslink,
					  'post_content'  => $vscontent,
					  'post_status'   => $status,
					  'post_author'   => $user,
					  'post_type' => "page",
					  'post_parent' => $structure["baseids"][$cat]["compare"],
					);
					$vsid = wp_insert_post( $my_post );	
					update_post_meta( $vsid, '_wp_page_template', 'page-templates/full-width.php' );	
					
					$remembarray["vsids"][$asin2.$asin] = 1;
					$remembarray["vsids"][$asin.$asin2] = 1;	
					if(!empty($vsid)) {
						$structure["vsids"][$asin2.$asin] = $vsid;
						$structure["vsids"][$asin.$asin2] = $vsid;
					}
				}
			}	
		}
		
		// 3. create review page
		if($create_reviews == 1) {
			if(empty($item["rid"])) {$revid = 0;} else {$revid = $item["rid"];}
			$title = $item["Brand"] . " " . $item["Model"] . " Reviews";
			$fullprod = $item["Brand"] . " " . $item["Model"];
			$slug = sanitize_title($item["Brand"] . " " . $item["Model"]);
			
			$imgurl  = wp_get_attachment_url( $products[$asin]["aid"] );
			$content = '<div class="wpr-site-content"><p><img style="float:left;margin:5px;width:300px;" src="'.$imgurl.'" /><strong>'.$item["Title"].'</strong><br/>'.$item["Desc"].'</p>';
			
			$content .= '<p><a class="amalink" target="_blank" href="'.$item["DetailPageURL"].'">View Details & Price</a></p>';	
			
			$content .= $featurelist;

			if(!empty($item["yt"])) {
				$content .= "<h3>".$item["Brand"] . " " . $item["Model"] . ' Video Reviews</h3>';
				foreach($item["yt"] as $ytv) {
					$content .= '<div><strong>'.$ytv["title"].'</strong><iframe width="480" height="320" src="https://www.youtube.com/embed/'.$ytv["id"].'?rel=0" frameborder="0" allowfullscreen></iframe><br/>'.$ytv["desc"].'</div>';
				}
			}

			if(!empty($item["wm"]["numReviews"])) {
				$content .= '<h3>Walmart Reviews</h3><p>This product has an <strong>average rating of '.number_format($item["wm"]["rating"], 1).'</strong> based on '.$item["wm"]["numReviews"].' reviews by Walmart customers. Read some of the latest feedback below or go to <a href="'.$item["wm"]["url"].'">Walmart.com</a> to find all reviews.</p>';
			
				if(!empty($wmreviews[$asin])) {
					foreach($wmreviews[$asin] as $wmr) {
						$rdet = ""; if(!empty($wmr["productAttributes"])) {foreach($wmr["productAttributes"] as $pratr) {$rdet .= $pratr["label"] . ": " . $pratr["rating"].", "; }$rdet = " (".$rdet.")";}
						$content .= '<p><strong>'.$wmr["title"].'</strong><br/><em>Review for '.$item["Brand"] . " " . $item["Model"].' by '.$wmr["reviewer"].' on '.$wmr["submissionTime"].'</em><br/><strong>Rating: '.$wmr["overallRating"]["rating"].'</strong>'.$rdet.'<br/>'.$wmr["reviewText"].'</p>';
					}
				}
			}		
			
			if(!empty($item["bb"]["rating"])) {
				$content .= '<h3>BestBuy Reviews</h3><p>This product has an average rating of '.$item["bb"]["rating"].' on BestBuy based on '.$item["bb"]["reviews"].' reviews. <a href="'.$item["bb"]["url"].'">Go to bestbuy.com now to view all reviews</a>.</p>';
			}
		
			if(!empty($vspagelinks)) {
				$content .= '</div><div class="wpr-widget-area"><h3>Comparisons</h3><p>See our articles below to compare '.$fullprod.' to other popular '.$item["Category"].':</p>'.$vspagelinks.'</div>';
			}
		
			if(!empty($categorych)) {
				$my_post = array(
				  'ID'   => $revid,
				  'post_title'    => $title,
				  'post_name'    => $slug,
				  'post_content'  => $content,
				  'post_status'   => $status,
				  'post_author'   => $user,
				  'post_type' => "page",
				  'post_parent' => $structure["baseids"][$cat]["reviews"],
				);
				$rid = wp_insert_post( $my_post );			
				update_post_meta( $rid, '_wp_page_template', 'page-templates/full-width.php' );	
				
				$products[$asin]["rid"] = $rid;	
			}
		}
	}
	
	// 4. update compare and reviews pages
	foreach($structure["baseids"] as $caty => $catd) {
		$compid = $catd["compare"];
		$compcontent = "How to find the best ".strtolower($caty)." for your needs? Simple! Just use our detailed comparisons below. All you need to do is select two of the ".strtolower($caty)." shown below to see a comparison of all their features and their pros and cons.<div id=\"comparison_page\">";
		foreach($homepagelinks[$caty] as $item) {	
			$compcontent .= $item;
		}
		
		$compcontent .= "</div><h2>Direct Links To All Comparisons</h2>".$maincomppagevslinks[$caty];
		
		if(!empty($compid) && !empty($compcontent)) {
			$my_post = array(
			  'ID'   => $compid,
			  'post_content'  => $compcontent,
			);
			wp_update_post( $my_post );			
		}
		
		if($create_frontpage == 1) {
			update_option( 'page_on_front', $compid );
			update_option( 'show_on_front', 'page' );	
		}
		
		$revid = $catd["reviews"];
		$revcontent = "On our site you can find reviews for all the most popular ".strtolower($caty)." on sale in ".date("Y").". Simply click one of the product images below to go to view all its details, features and video reviews.";
		foreach($reviewslinks[$caty] as $item) {	
			$revcontent .= $item;
		}
		
		if(!empty($revid) && !empty($revcontent)) {
			$my_post = array(
			  'ID'   => $revid,
			  'post_content'  => $revcontent,
			);
			wp_update_post( $my_post );			
		}	
	}
	
	// 5. create frontpage
	/*if($create_frontpage == 1) {
		if(!empty($structure["fpid"])) {
			$fpid = $structure["fpid"];
		}	
		
		$hpcontent = 'This site offers you an overview over the top '.$structure["kws"]["main"].' on the market. Click on one of the '.$structure["kws"]["mainsing"].' below to show its details and reviews or select two products for a detailed comparison.';
		
		foreach($reviewslinks as $cat => $items) {

			$cplnk = get_permalink($structure["baseids"][$cat]["compare"]);
			$rvlnk = get_permalink($structure["baseids"][$cat]["reviews"]);
		
			$hpcontent .= '<h2><a href="'.$cplnk.'">Compare '.$cat.'</a></h2><ul><li><a href="'.$cplnk.'">View all our comparisons for '.strtolower($cat).'</a></li><li><a href="'.$rvlnk.'">View reviews for all '.strtolower($cat).'</a></li></ul>';
			$i = 0;
			foreach($items as $item) {	
				$i++;
				
				if(!empty($popularlinks[$cat][$i])) {
					$theit = $popularlinks[$cat][$i];
				} else {
					$theit = $item;
				}
				
				$hpcontent .= $theit;
				if($i > 5) {break;}
			}
		}
		
		$my_post = array(
		  'ID'   => $fpid,
		  'post_title'    => "The Best " . ucwords($structure["kws"]["main"]) . " " . date("Y"),
		  //'post_name'    => $slug,
		  'post_content'  => $hpcontent,
		  'post_status'   => $status,
		  'post_author'   => $user,
		  'post_type' => "page",
		  'post_parent' => $structure["baseids"]["reviews"],
		);
		$fpid = wp_insert_post( $my_post );
		$structure["fpid"] = $fpid;
		update_post_meta( $fpid, '_wp_page_template', 'page-templates/full-width.php' );	
		update_option( 'page_on_front', $fpid );
		update_option( 'show_on_front', 'page' );	
	}*/
	
	//echo "<pre>";print_r($structure);echo "</pre>";
	
	// update settings
	update_option("wpr5_affpage_structure", $structure);
	wpr5_update_affpage_options($category, $products);	

	echo '<div class="updated below-h2"><p>'.__('All pages have been created successfully.', 'wprobot').'</p></div>';	
}

function get_youtube_details($products, $category = "") {

	$options = wpr5_get_options();

	if(empty($options["options"]["youtube"]["options"]["appid"]["value"])) {
		echo '<div class="updated below-h2"><p>'.__('Please activate Youtube on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';			
		return false;
	} else {
		$apikey = $options["options"]["youtube"]["options"]["appid"]["value"]; 
	}

	require_once(wpr5_DIRPATH."api.class.php");
	$foundprods = 0;
	$api = new API_request;
	$templates["youtube"] = "affpagetempl";

	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		if(!empty($category) && $item["Category"] != $category) {continue;}
		$ytstuff = array();		
		if(!empty($item["Model"]) && !empty($item["Brand"])) {
			$stitle = $item["Brand"] . " " . $item["Model"] . " review";
			//$skw = $item["Brand"] . " " . $item["Model"];
			$skw = $item["Model"];
		} else {continue;}

		$contents = $api->api_content_bulk($stitle, array("youtube" => 5), $_POST, $templates); 
		if($_GET["debug"] == 1) {echo "$stitle SEARCHING $skw<br><pre>";print_r($contents);echo "</pre>";		}	
		$toadd = "";
		if(empty($contents["youtube"]["error"]) && !empty($contents["youtube"])) {

			foreach($contents["youtube"] as $ct) {
				if (stripos($ct["content"], $skw) !== false || stripos($ct["title"], $skw) !== false) {
					if($_GET["debug"] == 1) {echo " yo found something... <br>";}
					$vitdesc = $ct["content"];
					$vitdesc = (strlen($vitdesc) > 300) ? substr($vitdesc,0,300).'...' : $vitdesc;
					
					$ytstuff[] = array("id" => $ct["unique"], "title" => $ct["title"], "desc" => $vitdesc);
					
				}
				
			}
			$products[$asin]["yt"] = $ytstuff;	
			if(!empty($ytstuff)) {$foundprods++;}
		}
	}	
	
	wpr5_update_affpage_options($category, $products);	
	
	if($foundprods > 0) {
		echo '<div class="updated below-h2"><p>Youtube videos have been found and saved for '.$foundprods.' products.</p></div>';			
	} else {
		echo '<div class="updated below-h2"><p>'.__('No videos could be found on Youtube for your products. Last error: ', 'wprobot').$err.'</p></div>';			
		return false;	
	}		
}

function get_semantics3_details($products, $category = "", $apikey = "", $apisecret = "", $try = "upc") {

	require(wpr5_comparison_DIRPATH.'semantics3/lib/Semantics3.php');

	$key = $apikey; //'SEM3BB1983FD77B598322AB7C40FCC832465';
	$secret = $apisecret; // 'OTU2NjQ0Yzk4N2UxY2FjMWRmMDg5MzIwYWE2MmZlYzI';
	$foundprods = 0;
	
	if(empty($key) || empty($secret)) {
		echo '<div class="updated below-h2"><p>'.__('Please enter your Semantics3 API keys.', 'wprobot').'</p></div>';
		return false;
	}
	
	$s3keys = array("key" => $apikey, "secret" => $apisecret);
	update_option("wpr5_s3_apikey", $s3keys);

	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		if(!empty($category) && $item["Category"] != $category) {continue;}		

		$upc = $item["UPC"];
		$url = $item["DetailPageURL"];
		if(!empty($upc) && empty($item["s3"])) {

			# Build the request
			$requestor = new Semantics3_Products($key,$secret);
			$requestor->products_field( "field", ["features","model"] );	

			if($try == "upc") {
				$requestor->products_field( "upc", $upc );
			} else {
				$requestor->products_field( "url", $url );
			}
			
			# Run the request
			$results = $requestor->get_products();
			$results = json_decode($results, true);		
				
			if($_GET["debug"] == 1) {echo "<pre>";print_r($results);echo "</pre>";}
				
			if(!empty($results["results"][0])) {

				$prd = $results["results"][0];
				
				//$thefeats = wpr5_filter_features($prd["features"]);
				$thefeats = $prd["features"];
				
				$s3stuff = array();
				$s3stuff["release"] = date('M Y', $prd["created_at"]);
				$s3stuff["features"] = $thefeats;
				$s3stuff["model"] = $prd["model"];
				$s3stuff["name"] = $prd["name"];
				
				$products[$asin]["s3"] = $s3stuff;
				$foundprods++;
				//if(empty($products[$asin]["Model"]) && !empty($s3stuff["model"])) {$products[$asin]["Model"] = $s3stuff["model"];}
				
				if(!empty($products[$asin]["Brand"])) {$newname = str_replace($products[$asin]["Brand"], "", $prd["name"]);}
				$newname = trim($newname, "- ");
				$newname = trim($newname);
				$products[$asin]["Model"] = $newname;
			}		
		}
	}
	wpr5_update_affpage_options($category, $products);	
	
	if($foundprods > 0) {
		echo '<div class="updated below-h2"><p>Semantics3 data has been found and saved for '.$foundprods.' products.</p></div>';			
	} else {
		echo '<div class="updated below-h2"><p>'.__('No data could be found on Semantics3 for your products. Last error: ', 'wprobot').$err.'</p></div>';			
		return false;	
	}		
}


function get_all_bestbuy_products($catid, $minprice, $page = 1, $sku = "", $cat = "", $maxprice = 99999) {

	$options = wpr5_get_options();

	if(empty($options["options"]["bestbuy"]["options"]["appid"]["value"])) {
		echo '<div class="updated below-h2"><p>'.__('Please activate Bestbuy on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';			
		return false;
	} else {
		$apikey = $options["options"]["bestbuy"]["options"]["appid"]["value"]; 
	}

	if(!empty($sku)) {$searchpara = "&SKU=".$sku;} else {$searchpara = "&categoryPath.id=".$catid;}

	$mxpr = "&salePrice<".$maxprice;
	
	$req = "https://api.bestbuy.com/v1/products(salePrice>".$minprice.$searchpara.$mxpr.")?apiKey=".$apikey."&pageSize=100&page=".$page."&&sort=bestSellingRank.asc&show=bestSellingRank,color,condition,customerReviewAverage,customerReviewCount,description,details.name,details.value,features.feature,image,longDescription,manufacturer,modelNumber,name,onlineAvailability,onSale,percentSavings,regularPrice,salePrice,shortDescription,sku,thumbnailImage,upc,url&format=json";

	if ( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $req);
		curl_setopt($ch, CURLOPT_TIMEOUT, 25);
		$response = curl_exec($ch);
		curl_close($ch);
	} else { 				
		$response = @file_get_contents($req);
	}

	if ($response !== False) {
		$feed = json_decode($response, true);			
	}
	
	if($_GET["debug"] == 1) {echo "<pre>";print_r($feed);echo "</pre>";	}
	
	if(!empty($feed["products"])) {	
		foreach($feed["products"] as $prod) {

			$LargeImage = $prod["image"];
			$DetailPageURL = $prod["url"];
			$SKU = $prod["sku"];
			$UPC = $prod["upc"];
			$Color = $prod["color"];		
			$Brand = $prod["manufacturer"];
			$Model = $prod["modelNumber"];
			$Title = $prod["name"];
			$ListPrice = $prod["regularPrice"];

			$edreview = $prod["longDescription"];
			$edreview2 = $prod["shortDescription"];
			
			$itemarray[$UPC] = array(
				"DetailPageURL" => $DetailPageURL,
				"SalesRank" => $SalesRank,
				"Category" => $cat,
				"LargeImage" => $LargeImage,	
				"Color" => $Color,
				"SKU" => $SKU,
				"Desc" => $edreview,
				"ShortDesc" => $edreview2,
				"UPC" => $UPC,
				"Brand" => $Brand,
				"Model" => $Model,
				"Title" => $Title,
				"ListPrice" => $ListPrice,
				"Dimensions" => "",				
				"Features" => "",				
			);	

			$bbstuff = array();
			
			$bbfeat = array();
			foreach($prod["details"] as $det) {
				$nm = $det["name"];
				$vl = $det["value"];
				$bbfeat[$nm] = $vl;
			}
			$bbstuff["list"] = $bbfeat;
			
			$bbfeat = array();
			foreach($prod["features"] as $det) {
				$ft = $det["feature"];
				$bbfeat[] = $ft;
			}
			$bbstuff["feat"] = $bbfeat;				
			
			$bbstuff["rating"] = $prod["customerReviewAverage"];
			$bbstuff["reviews"] = $prod["customerReviewCount"];
			$bbstuff["url"] = $prod["url"];
			$bbstuff["price"] = $prod["salePrice"];
			
			$itemarray[$UPC]["bb"] = $bbstuff;
		}	
		return $itemarray;
	}

	return false;
}

function get_bestbuy_details($products, $category = "") {

	$options = wpr5_get_options();
	$foundprods = 0;
	
	if(empty($options["options"]["bestbuy"]["options"]["appid"]["value"])) {
		echo '<div class="updated below-h2"><p>'.__('Please activate Bestbuy on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';			
		return false;
	} else {
		$apikey = $options["options"]["bestbuy"]["options"]["appid"]["value"]; 
	}

	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		if(!empty($category) && $item["Category"] != $category) {continue;}		
		$upc = $item["UPC"];
		if(!empty($upc) && empty($item["bb"])) {
		
			$req = "https://api.bestbuy.com/v1/products(upc=".$upc.")?apiKey=".$apikey."&sort=bestSellingRank.asc&show=bestSellingRank,color,condition,customerReviewAverage,customerReviewCount,description,details.name,details.value,features.feature,image,longDescription,manufacturer,modelNumber,name,onlineAvailability,onSale,percentSavings,regularPrice,salePrice,shortDescription,sku,thumbnailImage,upc,url&format=json";

			if ( function_exists('curl_init') ) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, $req);
				curl_setopt($ch, CURLOPT_TIMEOUT, 25);
				$response = curl_exec($ch);
				curl_close($ch);
			} else { 				
				$response = @file_get_contents($req);
			}

			if ($response !== False) {
				$feed = json_decode($response, true);			
			}
		
			echo "<pre>";print_r($feed);echo "</pre>";		
		
			if(!empty($feed["products"][0])) {

				$prd = $feed["products"][0];
				$bbstuff = array();
				
				$bbfeat = array();
				foreach($prd["details"] as $det) {
					$nm = $det["name"];
					$vl = $det["value"];
					$bbfeat[$nm] = $vl;
				}
				$bbstuff["list"] = $bbfeat;
				
				$bbfeat = array();
				foreach($prd["features"] as $det) {
					$ft = $det["feature"];
					$bbfeat[] = $ft;
				}
				$bbstuff["feat"] = $bbfeat;				
				
				$bbstuff["rating"] = $prd["customerReviewAverage"];
				$bbstuff["reviews"] = $prd["customerReviewCount"];
				$bbstuff["url"] = $prd["url"];
				$bbstuff["price"] = $prd["salePrice"];
				
				$products[$asin]["bb"] = $bbstuff;
				$foundprods++;
				
				//echo "<pre>";print_r($bbstuff);echo "</pre>";
			}
			sleep(1);
		}
	}
	wpr5_update_affpage_options($category, $products);	
	
	if($foundprods > 0) {
		echo '<div class="updated below-h2"><p>BestBuy data has been found and saved for '.$foundprods.' products.</p></div>';			
	} else {
		echo '<div class="updated below-h2"><p>'.__('No data could be found on BestBuy for your products. Last error: ', 'wprobot').$err.'</p></div>';			
		return false;	
	}	
}

function get_walmart_details($products, $category = "") {

	$options = wpr5_get_options();

	if(empty($options["options"]["walmart"]["options"]["appid"]["value"])) {
		echo '<div class="updated below-h2"><p>'.__('Please activate Walmart on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';			
		return false;
	} else {
		$apikey = $options["options"]["walmart"]["options"]["appid"]["value"]; 
	}
	
	$wmreviews = get_option("wpr_wm_reviews");
	$foundprods = 0;
	
	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		if(!empty($category) && $item["Category"] != $category) {continue;}		
		$upc = $item["UPC"];
		if(!empty($upc) && empty($item["wm"])) {
		
			$req = "http://api.walmartlabs.com/v1/items?apiKey=".$apikey."&upc=".$upc;
		
			if ( function_exists('curl_init') ) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, $req);
				curl_setopt($ch, CURLOPT_TIMEOUT, 22);
				$response = curl_exec($ch);
				curl_close($ch);
			} else { 				
				$response = @file_get_contents($req);
			}

			if ($response !== False) {
				$feed = json_decode($response, true);			
			}
			
			if(!empty($feed["errors"])) {
				$err = $feed["errors"];
				continue;
			} else {
				$item = $feed["items"][0];
				
				$iid = $item["itemId"];	
				$afflink = $item["productUrl"];
				$rating = $item["customerRating"];
				$numReviews = $item["numReviews"];
				
				if(!empty($numReviews) && (int) $numReviews > 0) {
				
					$req = "http://api.walmartlabs.com/v1/reviews/".$iid."?apiKey=".$apikey."";
				
					if ( function_exists('curl_init') ) {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, $req);
						curl_setopt($ch, CURLOPT_TIMEOUT, 22);
						$response = curl_exec($ch);
						curl_close($ch);
					} else { 				
						$response = @file_get_contents($req);
					}

					if ($response !== False) {
						$feed = json_decode($response, true);			
					}	

					if(!empty($feed["reviews"])) {
						$reviews = $feed["reviews"];
						
						$foundprods++;
						
						$wmreviews[$asin] = $reviews;
						$wmstuff = array("id" => $iid, "url" => $afflink, "rating" => $rating, "numReviews" => $numReviews);	
						$products[$asin]["wm"] = $wmstuff;
						
						//echo "<pre>";print_r($wmstuff);echo "</pre>";
					}			
				}
			}
			sleep(1);
		}
	}
	wpr5_update_affpage_options($category, $products);
	update_option("wpr_wm_reviews", $wmreviews);
	
	if($foundprods > 0) {
		echo '<div class="updated below-h2"><p>Walmart data has been found and saved for '.$foundprods.' products.</p></div>';			
	} else {
		echo '<div class="updated below-h2"><p>'.__('No data could be found on Walmart for your products. Last error: ', 'wprobot').$err.'</p></div>';			
		return false;	
	}
}


/*================================================================== 2. Views ===============================================================*/

// Scripts
function wpr5_affbuilder_page_print_scripts() {
	wp_enqueue_script('jquery-ui-tabs');
	//wp_enqueue_script('jquery-cookie', plugins_url('/includes/jquery.cookie.js', __FILE__),array('jquery') );
	//wp_enqueue_style('wpr5-admin-styles', plugins_url('/includes/admin-styles.css', __FILE__) );

	wp_enqueue_script('jquery-cookie', wpr5_URLPATH.'includes/jquery.cookie.js',array('jquery') );	
	wp_enqueue_style('wpr5-admin-styles', wpr5_URLPATH.'includes/admin-styles.css' );	
}

// Header
function wpr5_affbuilder_page_head() {
?>
    <script type="text/javascript">	
	function checkAll2(status) {
		jQuery("table td.check-column input").each( function() {
			jQuery(this).attr("checked",status);
		})
	}	
	jQuery(document).ready(function($) {
	
		jQuery( "#tabs" ).tabs({
			activate: function (e, ui) { 
				jQuery.cookie('selected-tab', ui.newTab.index(), { path: '/' }); 
			}, 
			active: jQuery.cookie('selected-tab')
		});		

		jQuery('a.tools-scheduling, a.tools-authors, a.tools-presets, a.rw-inject, a.rw-all').click(function(e) {
			e.preventDefault();	
			var theclass = jQuery(this).attr('class');
			jQuery('#' + theclass).toggle();
		});  

		jQuery(".modeleditclass").focus(function(){
		
			var tid = jQuery(this).attr("id");	
			jQuery( "#ed-" + tid ).prop( "checked", true );
		});	
		

		jQuery(".bc_actionselect").change(function(){
			var down2 = jQuery(this).val();
			jQuery(".bbset").hide();
			jQuery("#bbset_" + down2).slideDown("fast");
		});			
	
		jQuery(".bc_bulkactions").change(function(){
			var down2 = jQuery(this).val();
			if(down2 == "edit_cat" || down2 == "edit_brand") {
				jQuery("#newact").show();
			} else {
				jQuery("#newact").hide();
			}
		});		
	
	});
	</script>	
<?php		
}

// Page Body
function wpr5_affbuilder_page() {
	global $wpr5_source_infos, $optionsexpl, $wpdb, $wpr5_table_posted;

	wpr5_check_license_key();
		
	if($_POST["bc_clear"]) {
		delete_option( 'wpr5_affpage_products' );	
		delete_option( 'wpr5_affpage_structure' );	
	}	
	
	$options = wpr5_get_options();	
	
	if(!empty($options["options"]["youtube"]["templates"]) && empty($options["options"]["youtube"]["templates"]["affpagetempl"])) {
		$options["options"]["youtube"]["templates"]["affpagetempl"] = array(
			"name" => "AffPage Template", 
			"content" => '{description}'
		);
		wpr5_update_options($options);
	}
	
	$structure = get_option("wpr5_affpage_structure");	

	//echo "<pre>";print_r($structure);echo "</pre>";
	// TODO: get CAT from structure

	$products = wpr5_get_affpage_options($category);
	
	if($_POST["bc_keyword_research"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {	
		$primary_kw_s = $_POST["primary_kw_s"];
		$primary_kw = $_POST["primary_kw"];
		get_keyword_details($products, $primary_kw, $primary_kw_s);
	}	

		//		echo "<pre>";print_r($products);echo "</pre>";	
	
	if($_POST["bc_s3"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		get_semantics3_details($products, $_POST["fetchcat"], $_POST["bc_s3_apikey"], $_POST["bc_s3_apisecret"], "upc"); // $_POST["try"]
	}		
	$s3keys = get_option("wpr5_s3_apikey");
	$_POST["bc_s3_apikey"] = $s3keys["key"];
	$_POST["bc_s3_apisecret"] = $s3keys["secret"];
	
	if($_POST["bc_bestbuy"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		get_bestbuy_details($products, $_POST["fetchcat"]);
	}
	
	if($_POST["bc_walmart"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		get_walmart_details($products, $_POST["fetchcat"]);
	}

	if($_POST["bc_youtube"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {	
		get_youtube_details($products, $_POST["fetchcat"]);
	}	

	$sitedata["wpusers"] = get_users('number=50');
	$sitedata["wpcats"] = get_categories(array("hide_empty" => 0));
	$sitedata["wpposttypes"] = get_post_types('','names');	
	$_POST["wp_posttype"] = "page";	
	
	if($_POST["bc_save_images"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		wpr5_save_all_images($products);
	}	
	
	if($_POST["bc_create"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
	
		$primary_kw_s = $_POST["primary_kw_s"];
		$primary_kw = $_POST["primary_kw"];	
	
		$structure["kws"]["main"] = $primary_kw;
		$structure["kws"]["mainsing"] = $primary_kw_s;
		update_option("wpr5_affpage_structure", $structure);
		
		if(empty($_POST["bc_status"])) {$content["post_status"] = "publish";} else {$content["post_status"] = $_POST["bc_status"];}
		wpr5_all_page_creator($products, $_POST["bc_status"], $_POST["bc_user"], $_POST["fetchcatpublish"], $_POST["create_comparison"], $_POST["create_reviews"], $_POST["create_frontpage"]);
	}	
	
	if(!empty($structure["kws"]["main"])) {$_POST["primary_kw"] = $structure["kws"]["main"];}
	if(!empty($structure["kws"]["mainsing"])) {$_POST["primary_kw_s"] = $structure["kws"]["mainsing"];}	
	
	if($_POST["bc_go_bbonly"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) { // BESTBUY PARSING 

		$minprice = $_POST["bb_minprice"];
		$page = $_POST["bb_page"];
		$catid = $_POST["bb_cat"];
		$sku = $_POST["bb_sku"];
		$bb_cat2 = $_POST["bb_cat2"];
		$maxprice = $_POST["bb_maxprice"];
		$newcontents = get_all_bestbuy_products($catid, $minprice, $page, $sku, $bb_cat2, $maxprice); 
		
		//echo "<pre>";print_r($newcontents);echo "</pre>";
		
		if(!empty($newcontents["error"])) { // API error
			echo '<div class="updated error"><p>'.ucwords($source)." API Error: ".esc_html($newcontents["error"]).'</p></div>';		
		} elseif(empty($newcontents)) {	
			echo '<div class="updated error"><p>'.__('No content found. Please try again.', 'wprobot').'</p></div>';		
		} else {

			foreach($newcontents as $asin => $item) {
				if(!empty($products[$asin])) {
					// TODO: update existing items?
				} else {
					$products[$asin] = $item;
				}
			
			}
			
			wpr5_update_affpage_options($category, $products);
		}		
		
	}
	
	if($_POST["bc_go"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) { // CONTENT PARSING 
		$cid = rand(0, 9999);	
		//if(!is_array($content_array)) {$content_array = array();}

		if($_POST["bc_num"] > 100) {
			echo '<div class="error error"><p>'.__('Error: Amazon does not allow more than 100 products to be loaded per keyword or node.', 'wprobot').'</p></div>';								
		} else {

			$source = "amazon";
			$options["options"]["amazon"]["options"]["browsenode"]["value"] = $_POST["amazon_browsenode"];
			$options["options"]["amazon"]["options"]["searchindex"]["value"] = $_POST["amazon_searchindex"];
			$options["options"]["amazon"]["options"]["minprice"]["value"] = $_POST["amazon_minprice"];
			$options["options"]["amazon"]["options"]["maxprice"]["value"] = $_POST["amazon_maxprice"];
			wpr5_update_options($options);

			require_once(wpr5_DIRPATH."api.class.php");
		
			$api = new API_request;
			$newcontents = $api->request_and_return_amazon_only($_POST["bc_topic"], 55, $_POST); 
			//echo "<pre>";print_r($newcontents);echo "</pre>";
			if(!empty($newcontents["error"])) { // API error
				echo '<div class="updated error"><p>'.ucwords($source)." API Error: ".esc_html($newcontents["error"]).'</p></div>';		
			} elseif(empty($newcontents)) {	
				echo '<div class="updated error"><p>'.__('No content found. Please try again.', 'wprobot').'</p></div>';		
			} else {

				foreach($newcontents as $asin => $item) {
					if(!empty($products[$asin])) {
						// TODO: update existing items?
					} else {
						$products[$asin] = $item;
					}
				
				}
				
				wpr5_update_affpage_options($category, $products);
			}
		}
	}	

	if($_POST["bc_clearnofeat"]) {
		foreach($products as $asin => $item) {
			if(empty($item["s3"]["features"]) && empty($item["bb"]["list"])) {
				$products[$asin] = "deleted";
			}
		}
		wpr5_update_affpage_options($category, $products);			
	}
	
	if($_POST["bc_clearnofeat_bb"]) {
		foreach($products as $asin => $item) {
			if(empty($item["bb"]["list"])) {
				$products[$asin] = "deleted";
			}
		}
		wpr5_update_affpage_options($category, $products);			
	}		
	
	if($_POST["bc_delete_pages"]) {	
		bc_delete_pages();
	}
	
	if($_POST["bc_clearbad"]) {
		foreach($products as $asin => $item) {
			if(empty($item["Brand"]) || empty($item["Model"])) { // || (empty($item["s3"]["features"]) && empty($item["bb"]["list"])
				$products[$asin] = "deleted";
			}
		}
		wpr5_update_affpage_options($category, $products);			
	}	

	if($_POST["modeledit"]) {
		foreach($products as $asin => $item) {
			
			if($_POST["ed-".$asin] == 1 && !empty($_POST["en-".$asin])) { // || (empty($item["s3"]["features"]) && empty($item["bb"]["list"])
				$products[$asin]["Model"] = $_POST["en-".$asin];
				
				echo "editing ".$_POST["en-".$asin]. "<br>";
				
			} elseif($_POST["del-".$asin] == 1) {	
				$products[$asin] = "deleted";
				echo "deleting.....<br> ";
			} else {
				echo "skipping<br> ";
			}
		}
		wpr5_update_affpage_options($category, $products);			
	}	

	if($_POST['doaction']) {	

		if($_POST['action'] == "trash") {
			foreach ($_POST['prods']  as $key => $asin) {
				$products[$asin] = "deleted";			
			}
		}
		
		/*if($_POST['action'] == "change_model") {
			$newval = trim(ucwords($_POST['newact']));

			foreach ($_POST['prods']  as $key => $asin) {
				if(!empty($products[$asin]["s3"]["model"])) {
					$products[$asin]["Model"] = $products[$asin]["s3"]["model"];
				}
			}		
		}*/		
		
		if($_POST['action'] == "edit_cat" || $_POST['action'] == "edit_brand") {
			$newval = trim(ucwords($_POST['newact']));
			if($_POST['action'] == "edit_cat") {$rpl = "Category";}
			if($_POST['action'] == "edit_brand") {$rpl = "Brand";}
		
			foreach ($_POST['prods']  as $key => $asin) {
				$products[$asin][$rpl] = $newval;
			}	
				
	
		}
		wpr5_update_affpage_options($category, $products);			
	}

	$tcaegories = array();
	$featurearray = array();
	$featurearraycount = array();
	foreach($products as $asin => $item) {
		if($item == "deleted" || !is_array($item)) {continue;}
		$cat = $item["Category"];
		if(empty($tcaegories[$cat]) && !empty($cat)) {
			$tcaegories[$cat] = 1;
		}
		foreach($item["s3"]["features"] as $feat => $val) {
		
			$simplefeat = simplify_feat($feat);
		
			$featurearraycount[$simplefeat] = $featurearraycount[$simplefeat] + 1;
			$featurearray[$simplefeat]["fullnames"][$feat] = $featurearray[$simplefeat]["fullnames"][$feat] + 1;
		}
		foreach($item["bb"]["list"] as $feat => $val) {
		
			$simplefeat = simplify_feat($feat);
		
			$featurearraycount[$simplefeat] = $featurearraycount[$simplefeat] + 1;
			$featurearray[$simplefeat]["fullnames"][$feat] = $featurearray[$simplefeat]["fullnames"][$feat] + 1;
		}		
	}
?>
<div class="wrap">
	<div id="saved"></div>
	<h1 style="margin-bottom:10px;"><?php _e('Create Comparison & Affiliate Pages', "wprobot"); ?></h1>
	
	<div id="tabs">	
		<form method="post" name="test_form" id="fluency-options" enctype="multipart/form-data" >	
		<?php wp_nonce_field( 'cmsc-bulkcontent-form-'.$user_id ); ?>
		
			<div id="cmsc-tabbar">			
				<div id="cmsc-tabbar-siteshead"><?php _e('Your Sites', 'wprobot'); ?></div>
				<ul class="tabs">
					<li><a href="#tabs-1" title="1. Build Page"><?php _e('1. Fetch Products', 'wprobot'); ?></a></li>	
					<li><a href="#tabs-2" title="2. Modify Page"><?php _e('2. Add More Data', 'wprobot'); ?></a></li>
					<li><a href="#tabs-3" title="3. Publish Articles"><?php _e('3. Publish Pages', 'wprobot'); ?></a></li>
					<!--<li><a href="#tabs-4" title="4. Features"><?php _e('4. Features', 'wprobot'); ?></a></li>
					<li><a href="#tabs-5" title="5. Models"><?php _e('5. Models', 'wprobot'); ?></a></li>-->
				</ul>		
				<div style="clear:both;"></div>
			</div>

		<div id="cmsc-main">
			<div id="cmsc-main-content">					
				
				<div class="bc-tabs bulk-content-box" id="tabs-1">
				
					<span>
						<select name="bc_action" class="bc_actionselect">
							<option value=""><?php _e('Choose base source for products', 'wprobot'); ?></option>					
							<option value="amazon"><?php _e('Amazon', 'wprobot'); ?></option>
							<option value="bestbuy"><?php _e('BestBuy', 'wprobot'); ?></option>
						</select> 
					</span>		

					<div id="bbset_start" class="bbset">
						<p style="font-size:90%;width:600px;"><?php _e('The base source determines from which vendor product data gets loaded and which affiliate links are used in your comparison pages. The imported products can be enriched with other data from more sources in the second step.<br/><br/>For more instructions on how to use this feature please <a href="http://wprobot.net/comparison-page-creator/#howto" target="_blank">read the documentation here</a>.', 'wprobot'); ?></p>
					</div>
				
					<div id="bbset_bestbuy" class="bbset" <?php if(empty($_POST["bb_cat"])) { ?>style="display:none;"<?php } ?>>
						<?php if(empty($options["options"]["bestbuy"]["options"]["appid"]["value"])) {
							echo '<div style="color:#ff0000;"><p>'.__('Please activate Bestbuy on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';	
						} else { ?>
							<?php _e('Fetch BestBuy products from the <strong>category</strong>:', 'wprobot'); ?> <input type="text" class="text" name="bb_cat" value="<?php echo esc_attr($_POST["bb_cat"]); ?>">
							<input class="button-primary" type="submit" value="<?php _e('Go', 'wprobot'); ?>" name="bc_go_bbonly" id="bc_go_bbonly">
							<p style="font-size:90%;width:600px;"><?php _e('<a target="_blank" href="https://www.bestbuy.com/site/shop/category">Go here to find BestBuy category IDs</a> by browsing to the category of your choice and copying the last part of the URL in your browser into the field above. For example: "pcmcat377500050006".', 'wprobot'); ?></p>

							<br/><label for="bb_cat2"><strong>Your Keyword</strong>:</label> <input class="regular-text" name="bb_cat2" value="<?php echo esc_attr($_POST["bb_cat2"]); ?>" type="text">													
							<p style="font-size:90%;width:600px;"><?php _e('The main keyword you want to use for this product comparison. It will be set as the category for your pages and to optimize them for SEO. For example: "smart watches"', 'wprobot'); ?></p>

							<br/><label for="bb_minprice">Minimum Price:</label> <input class="regular-text" name="bb_minprice" value="50" type="text">
							<br/><label for="bb_maxprice">Maximum Price:</label> <input class="regular-text" name="bb_maxprice" value="99999" type="text">
							<br/><label for="bb_page">Page:</label> <input class="regular-text" name="bb_page" value="1" type="text">
							<br/><label for="bb_sku">SKU:</label> <input class="regular-text" name="bb_sku" value="" type="text">
					
						<?php } ?>
					</div>
				
					<div id="bbset_amazon" class="bbset" <?php if(empty($_POST["bc_topic"])) { ?>style="display:none;"<?php } ?>>
						<?php if(empty($options["options"]["amazon"]["options"]["public_key"]["value"])) {
							echo '<div style="color:#ff0000;"><p>'.__('Please activate Amazon on the Choose Sources page first and enter your API key.', 'wprobot').'</p></div>';	
						} else { ?>			
					
						<?php _e('Fetch', 'wprobot'); ?> <?php _e('products related to the <strong>keyword</strong>:', 'wprobot'); ?> <input type="text" class="text" name="bc_topic" value="<?php echo esc_attr($_POST["bc_topic"]); ?>">					
						<input class="button-primary" type="submit" value="<?php _e('Go', 'wprobot'); ?>" name="bc_go" id="bc_go">
							<p style="font-size:90%;width:600px;"><?php _e('The main keyword you want to use for this product comparison. It will be set as the category for your pages and to optimize them for SEO. For example: "smart watches"', 'wprobot'); ?></p>
						
						<?php $modulearray = $options["options"];$num = 0; if(is_array($modulearray)) { foreach($modulearray as $module => $moduledata) { if($module== "amazon") {$num++; ?>			
							<div style="" id="<?php echo $module;?>">
							
								<div id="bc-module-settings">
								<i><?php _e('Your settings (edit to override)', 'wprobot'); ?>:</i><br/>
								<?php foreach($moduledata["options"] as $option => $data) {
									if($option == "shortcode" || $option == "comments" || $option == "public_key" || $option == "private_key") {continue;} 
									if($option != "title" && $option != "unique" && $option != "error" && $option != "unique_direct" && $option != "title_direct") {
										if($data["type"] == "text") { // Text Option ?> 
												<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?>:</label>
												<input class="regular-text" type="text" name="<?php echo $module."_".$option;?>" value="<?php echo $data["value"]; ?>" /><br/>
										<?php } elseif($data["type"] == "select") { // Select Option ?>
												<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?>:</label>
												<select name="<?php echo $module."_".$option;?>">
													<?php foreach($data["values"] as $val => $name) { ?>
													<option value="<?php echo esc_attr($val);?>" <?php if($val == $data["value"]) {echo "selected";} ?>><?php echo esc_html($name); ?></option>
													<?php } ?>		
												</select><br/>	
										<?php } elseif($data["type"] == "checkbox") { // checkbox Option ?>		
											<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?></label>
											<input class="button" type="checkbox" id="<?php echo $module."_".$option; ?>" name="<?php echo $module."_".$option; ?>" value="1" <?php if(1 == $data["value"]) {echo "checked";} ?>/>	<br/>						
										<?php } ?>	
										
									<?php } ?>
								<?php } ?>	
								</div>
							</div>
							
						</div>
						
						</div>
					<?php } } } ?>					

					<?php } ?>
					
				</div>
				
				<div class="bc-tabs bulk-content-box" id="tabs-2">

					<h3>Fetch Additional Data</h3>
					
					<?php _e('For category: ', 'wprobot'); ?><select id="fetchcat" name="fetchcat">
							<!--<option value="">All</option>-->
							<?php foreach($tcaegories as $cat => $one) {if($_POST["fetchcat"] == $cat) {}echo '<option value="'.$cat.'">'.$cat.'</option>';} ?>
					</select>					

					<?php 	
					if(empty($options["options"]["bestbuy"]["options"]["appid"]["value"])) {
						$bbudis = " disabled";
						$bbumsg =  __('Please activate Bestbuy on the Choose Sources page first and enter your API key.', 'wprobot');			
					}  
					?>	

					<ul><li><strong>BestBuy - Features and Ratings</strong></li></ul>
					
					<p style="font-size:90%;width:600px;"><?php _e('In case you used Amazon as base in the first step you can load additional data from BestBuy for the products shown below with this button.', 'wprobot'); ?></p>

					<p><input class="button-primary" value="Fetch BestBuy.com Data" name="bc_bestbuy" id="bc_bestbuy" type="submit"<?php echo $bbudis; ?>> <?php echo $bbumsg; ?></p>

					<ul><li><strong>Walmart - Product Reviews</strong></li></ul>		
					
					<?php 	
					if(empty($options["options"]["walmart"]["options"]["appid"]["value"])) {
						$wmdis = " disabled";
						$wmmsg =  __('Please activate Walmart on the Choose Sources page first and enter your API key.', 'wprobot');			
					}  
					?>
					
					<p><input class="button-primary" value="Fetch Walmart Data" name="bc_walmart" id="bc_walmart" type="submit"<?php echo $wmdis; ?>> <?php echo $wmmsg; ?></p>
					
					<ul><li><strong>Semantics3 - Product Features</strong></li></ul>							
					
					<p style="font-size:90%;width:600px;"><?php _e('Semantics3 is a service that provides valuable feature data for your product comparisons pages. You need to sign up for a free API key at <a href="https://www.semantics3.com/" target="_blank">semantics3.com</a> to use this service.', 'wprobot'); ?></p>					
					
					<p>
						<!--<select id="try" name="try">
								<option value="upc">UPC</option>
								<option value="url">url</option>
						</select>	<br/>	-->
						API Key: <input class="regular-text" name="bc_s3_apikey" value="<?php echo $_POST["bc_s3_apikey"]; ?>" type="text"><br/>	
						API Secret: <input class="regular-text" name="bc_s3_apisecret" value="<?php echo $_POST["bc_s3_apisecret"]; ?>" type="text">	<br/>		
						<input class="button-primary" value="Fetch Semantics3 Data" name="bc_s3" id="bc_s3" type="submit"><br/>
					</p>
					
					<ul><li><strong>Youtube - Product Review Videos</strong></li></ul>						
					
					<p><input class="button-primary" value="Fetch Youtube Videos" name="bc_youtube" id="bc_youtube" type="submit"></p>
			
					<!--<h3>Keyword Research</h3>

					<p><input class="button-primary" value="Perform Keyword Research" name="bc_keyword_research" id="bc_keyword_research" type="submit"></p>
					-->
				</div>		
				
				<div class="bc-tabs bulk-content-box" id="tabs-3">		

					<div style="clear:both;">
						<h3 style="margin-top: 0;"><?php _e( '<strong>Page Settings</strong>', 'wprobot' ); ?></h3>

					<p><label for="fetchcatpublish">For category:</label> 
					<select id="fetchcatpublish" name="fetchcatpublish">
							<?php foreach($tcaegories as $cat => $one) {if($_POST["fetchcat"] == $cat) {}echo '<option value="'.$cat.'">'.$cat.'</option>';} ?>
					</select>						
					</p>
			
			
					<!--<p><label for="primary_kw">Main Keyword Plural:</label> <input class="regular-text" name="primary_kw" value="<?php echo $_POST["primary_kw"]; ?>" type="text"></p>
					<p><label for="primary_kw_s">Main Keyword Singular:</label> <input class="regular-text" name="primary_kw_s" value="<?php echo $_POST["primary_kw_s"]; ?>" type="text"></p>
					-->				
						
						<table class="form-table">
							<tbody>	
							
								<!--<tr>
									<th scope="row"><label for="bc_cat"><?php _e("Category","wprobot") ?></label></th>
									<td>

										<select id="bc_cat" name="bc_cat">
											<option value=""><?php _e('Select category', 'wprobot'); ?></option>
											<?php foreach($sitedata["wpcats"] as $cat) { ?>
												<option <?php if($_POST["bc_cat"] == $cat->name) {echo "selected";} ?> value="<?php echo $cat->name; ?>"><?php echo $cat->name; ?></option>
											<?php } ?>
										</select>										

										<?php _e("or","wprobot") ?>
										
										<input placeholder="<?php _e("create new categories","wprobot") ?>" type="text" value="<?php echo esc_attr($_POST["bc_cat_new"]); ?>" name="bc_cat_new" id="bc_cat_new">
										<br/><em><?php _e("Separate multiple categories by comma. Categories not existing on your blog <strong>will get created</strong> automatically.","wprobot") ?></em>
									
									</td>	
								</tr>	-->	
									
								<tr>
									<th scope="row"><label for="bc_status"><?php _e("Post Status","wprobot") ?></label></th>
									<td>
										<select id="bc_status" name="bc_status">
											<option <?php if($_POST["bc_status"] == "publish") {echo "selected";} ?> value="publish"><?php _e('Published', 'wprobot'); ?></option>
											<option <?php if($_POST["bc_status"] == "pending") {echo "selected";} ?> value="pending"><?php _e('Pending Review', 'wprobot'); ?></option>
											<option <?php if($_POST["bc_status"] == "draft") {echo "selected";} ?> value="draft"><?php _e('Draft', 'wprobot'); ?></option>
										</select>
									</td>	
								</tr>															
								
								<tr>
									<th scope="row"><label for="bc_user"><?php _e("Author Username","wprobot") ?></label></th>
									<td>
										<select id="bc_user" name="bc_user">
											<?php foreach($sitedata["wpusers"] as $user) { ?>
												<option <?php if($_POST["bc_user"] == $user->ID) {echo "selected";} ?> value="<?php echo $user->ID; ?>"><?php echo $user->user_login; ?></option>
											<?php } ?>
										</select>	

										<?php _e("or","wprobot") ?>

										<input placeholder="<?php _e("create new user","wprobot") ?>" type="text" value="<?php echo esc_attr($_POST["bc_user_new"]); ?>" name="bc_user_new" id="bc_user_new">
									</td>	
								</tr>	
								
								<!--<tr>
									<th scope="row"><label for="bc_date"><?php _e("Date (optional)","wprobot") ?></label></th>
									<td>
										<input type="text" value="<?php echo esc_attr($_POST["bc_date"]); ?>" name="bc_date" id="bc_date">
										<br/><em><?php _e("Enter to <strong>schedule future post</strong>. Leave empty to use current date and time","wprobot") ?></em>
									</td>	
								</tr>-->
								
						
							</tbody>
						</table>	

						<p>
							<strong><?php _e('Create...', 'wprobot'); ?></strong><br/>
							<input checked value="1" name="create_comparison" type="checkbox" id="create_comparison"> <label for="create_comparison"><strong><?php _e('Comparison Pages', 'wprobot'); ?></strong> <?php _e('(one "X vs Y" comparison for each 2 products)', 'wprobot'); ?></label><br/>
							<input checked value="1" name="create_reviews" type="checkbox" id="create_reviews"> <label for="create_reviews"><strong><?php _e('Reviews Pages', 'wprobot'); ?></strong> <?php _e('(one page per product)', 'wprobot'); ?></label><br/>
							<!--<input value="1" name="create_frontpage" type="checkbox" id="create_frontpage"> <label for="create_frontpage"><strong><?php _e('Front Page', 'wprobot'); ?></strong> <?php _e('(replaces your current frontpage if selected)', 'wprobot'); ?></label><br/>
							-->
						</p>
						
						<p>
							<input value="1" name="create_frontpage" type="checkbox" id="create_frontpage"> <label for="create_frontpage"><strong><?php _e('Set Comparison as Frontpage', 'wprobot'); ?></strong> <?php _e('(replaces your current frontpage if selected)', 'wprobot'); ?></label><br/>
						</p>
						
						<h3 style="margin-top: 0;"><?php _e( '<strong>Publish</strong>', 'wprobot' ); ?></h3>
					
					
						<p>	
							<strong><?php _e( '<strong>1. Remove Bad Products</strong>', 'wprobot' ); ?></strong><br/>
							<span style="display:block;font-size:90%;width:600px;"><?php _e('We recommend you filter out bad products with insufficient data before publishing by using the button below.', 'wprobot'); ?></span>						
							<input onclick="return confirm('This will remove all products with empty model, brand or features. Continue?')" id="bc_clearbad" type="submit" class="button" name="bc_clearbad" value="<?php _e('Remove Bad Products Now', 'wprobot'); ?>" />
						</p>			
		
						<p>	
							<strong><?php _e( '<strong>2. Save Images</strong>', 'wprobot' ); ?></strong><br/>
							<span style="display:block;font-size:90%;width:600px;"><?php _e('In the first step all product images are saved to your site locally. This is done separately to prevent memory issues while creating many comparison pages.', 'wprobot'); ?></span>						
							<input class="button" id="bc_save_images" type="submit" name="bc_save_images" value="<?php _e('Save Images Now', 'wprobot'); ?>" />
						</p>		

						<p>	
							<strong><?php _e( '<strong>2. Create Pages</strong>', 'wprobot' ); ?></strong><br/>
							<span style="display:block;font-size:90%;width:600px;"><?php _e('The second step creates the actual product pages according to the settings above.', 'wprobot'); ?></span>						
							<input class="button-primary" id="bc_create" type="submit" name="bc_create" value="<?php _e('Create Pages Now', 'wprobot'); ?>" />
						</p>
					</div>
				
				</div>			

				<!--<div class="bc-tabs bulk-content-box" id="tabs-4">	
					<ul>
					<?php arsort($featurearraycount);
					foreach($featurearraycount as $feat => $count) {
						$fullns = "";
						if(!empty($featurearray[$feat]["fullnames"])) { foreach($featurearray[$feat]["fullnames"] as $fulln => $fullc) {$fullns .= $fulln . " ($fullc), ";}}
						echo "<li>".$feat . " - <strong> $count </strong> - $fullns</li>";
					} // ?>
					</ul>
				</div>	

				<div class="bc-tabs bulk-content-box" id="tabs-5">	

					<input class="button-primary" value="Edit Models" name="modeledit" id="modeledit" type="submit"><br/><br/>
					<?php
					foreach($products as $asin => $item) {
						if($item == "deleted" || !is_array($item)) {continue;}
						$inam = $item["Title"];
						$inam = str_replace($item["Brand"], "", $inam);
						$inam = str_replace("- ", "", $inam);
						$inam = trim($inam);
						$inam = explode(", ", $inam);
						$inam = trim($inam[0]);
	
						echo '<input value="1" name="del-'.$asin.'" id="del-'.$asin.'" type="checkbox"> <label for="del-'.$asin.'">DEL</label> <input value="1" name="ed-'.$asin.'" id="ed-'.$asin.'" type="checkbox"> <input class="regular-text modeleditclass" id="'.$asin.'" name="en-'.$asin.'" value="'.$inam.'" type="text"> <a style="font-size:85%;" href="'.$item["DetailPageURL"].'">'.$item["Title"].'</a><br/>';
					
					} // ?>

				</div>	-->
				
				
				<div id="content-preview">
		
					<?php if(is_array($products)) { ?>		
					<div style="float:right;margin: -5px 10px 0 0;">
						<!--<input onclick="return confirm('This will remove all products with no features. Continue?')" id="bc_clearnofeat" type="submit" class="button" name="bc_clearnofeat" value="<?php _e('Remove No Features', 'wprobot'); ?>" />
						<input onclick="return confirm('This will remove all products with empty model, brand or features. Continue?')" id="bc_clearnofeat_bb" type="submit" class="button" name="bc_clearnofeat_bb" value="<?php _e('Remove No BestBuy', 'wprobot'); ?>" />
						-->
			
					</div>			
					<h3><?php _e('Product Preview', 'wprobot'); ?> <span style="font-size: 70%;font-weight:normal;"><?php _e('(not posted to your sites yet - go to "3. Publish Pages" to do that)', 'wprobot'); ?></span></h3>
					
					<div>
						<div class="alignleft actions">
							<select class="bc_bulkactions" name="action">
							<option value="-1"><?php _e('Bulk Actions', 'cmsc'); ?></option>
							<option <?php if($_POST["action"] == "trash") {echo "selected";} ?> value="trash"><?php _e('Delete Selected Products', 'cmsc'); ?></option>
							<!--<option <?php if($_POST["action"] == "change_model") {echo "selected";} ?> value="change_model"><?php _e('Use Model From Semantics3 For Selected', 'cmsc'); ?></option>-->
							<option <?php if($_POST["action"] == "edit_cat") {echo "selected";} ?> value="edit_cat"><?php _e('Set Category To...', 'cmsc'); ?></option>
							<option <?php if($_POST["action"] == "edit_brand") {echo "selected";} ?> value="edit_brand"><?php _e('Set Brand To...', 'cmsc'); ?></option>
							</select>
						<input style="display:none;" type="text" value="" id="newact" name="newact">						
						<input type="submit" class="button-secondary apply" value="<?php _e('Apply', 'cmsc'); ?>" id="doaction" name="doaction">
						</div>					
					</div>
					
					<div style="clear:both;">
					<table>
						<thead>
							<th style="width: 2%;"><input onclick="checkAll2(this.checked)" type="checkbox"></th>
							<th style="width: 2%;"></th>
							<!--<th style="width: 9%;">Category</th>-->
							<th style="width: 5%;">Brand</th>
							<th style="width: 18%;">Model</th>
							<th style="width: 6%;">Listprice</th>
							<!--<th style="width: 4%;">Keywords</th>	-->						
							<th style="width: 2%;">Bestbuy</th>
							<th style="width: 2%;">Walmart</th>
							<th style="width: 2%;">Semantics3</th>
							<th style="width: 2%;">Youtube</th>
						</thead>
					
					<?php $p=0;
					$catrack = array();
					
					uasort($products, function($a, $b) { // anonymous function
						return strcmp($a['Category'], $b['Category']);
					});
					//echo "<pre>";print_r($products);echo "</pre>";					
					foreach($products as $asin => $item) {
						if(!in_array($item["Category"], $catrack) && !empty($item["Category"])) {
							$catrack[] = $item["Category"];
							?>
								<tr style="background-color:#FEFEFE;">
									<td colspan="10" style="padding: 10px;font-size: 110%;">Category: <strong><?php echo $item["Category"] ;?></strong></td>
								</tr>							
							<?php
						}

						if($item == "deleted" || !is_array($item)) {continue;} $p++;if($p % 2 == 0) {$bgr="#FFF";} else {$bgr="#fafafa";} ?>
						<tr style="background-color:<?php echo $bgr;?>;">
							<td class="check-column" style="text-align:center;"><input type="checkbox" value="<?php echo $asin; ?>" name="prods[]"></td>
							<td><a href="<?php echo $item["DetailPageURL"] ;?>" target="_blank"><img src="<?php echo $item["LargeImage"]; ?>" style="width: 60px;" /></a></td>
							<!--<td><?php echo $item["Category"] ;?></td>-->
							<td><?php echo $item["Brand"] ;?></td>
							<td><?php echo $item["Model"] ;
								//if(!empty($item["s3"]["model"])) {echo '<br><span style="font-size:90%;color: #999;">from Semantics3: '.$item["s3"]["model"].'</span>';}
							?></td>
							<td><?php echo $item["ListPrice"] ;
							if(!empty($item["EAN"])) {echo '<br><span style="font-size:90%;color: #999;">EAN: '.$item["EAN"].'</span>';}
							if(!empty($item["UPC"])) {echo '<br><span style="font-size:90%;color: #999;">UPC: '.$item["UPC"].'</span>';}
							?></td>
							<!--<td><?php if(!empty($item["kw"])) {echo "Volume: ".$item["kw"]["vol"] . "<br/>Cost: ".$item["kw"]["cpc"]. "<br/>Comp: ".$item["kw"]["comp"];} ?></td>	-->						
							<td><?php if(!empty($item["bb"])) {echo count($item["bb"]["list"]);} ?></td>
							<td><?php if(!empty($item["wm"])) {echo $item["wm"]["numReviews"];} ?></td>
							<td><?php if(!empty($item["s3"])) {echo count($item["s3"]["features"]);} ?></td>
							<td><?php if(!empty($item["yt"])) {echo count($item["yt"]);} ?></td>
						</tr>					
					<?php } ?>
					</table>		
					</div>
					
					<div style="margin: 15px 10px 0 0;">
						<h3>Cleanup Options</h3>
						<input onclick="return confirm('This will remove ALL products. Continue?')" id="bc_clear" type="submit" class="button" name="bc_clear" value="<?php _e('Clear Content and Restart', 'wprobot'); ?>" />
						<input onclick="return confirm('This will delete all pages created by the comparison script. Continue?')" id="bc_delete_pages" type="submit" class="button" name="bc_delete_pages" value="<?php _e('Delete All Pages', 'wprobot'); ?>" />		
					</div>					
					
					<?php } ?>		
				</div>
				
			</div>
		</div>	
		
	</div>		
		
	</form>		

</div>				
<?php	
}
?>