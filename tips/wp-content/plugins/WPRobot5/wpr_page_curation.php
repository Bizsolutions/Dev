<?php

/*********************************************************************************************************************************************/
/*                                                               BULK CONTENT PAGE                                                           */           
/*********************************************************************************************************************************************/

/*================================================================ 1. Functions =============================================================*/

function wpr5_bulk_content_process($content_array, $contents, $source, $cid, $poststuff, $user_id) {

	$_POST = $poststuff;
	$i = 0;
	$itemcount = count($content_array["c"]);
	foreach($contents[$source] as $num => $item) {
		$itc = strval($item["content"]);
		if(strpos($itc, "<div") === 0) { // If DIV already there (i.e. for floating) only add ID
			$itc = substr($itc, 4);
			$itc = '<div id="'.$source . $cid.'"'. $itc;
			//$itc = '<!-- '.$source . $cid.' START -->'. $itc . '<!-- '.$source . $cid.' END -->';
		} else { // Add identifying DIV for removing		
			$itc = '<div id="'.$source . $cid.'">'. $itc . '</div>';
		}

		if($_POST["bc_where"] == "new" || empty($_POST["bc_where"])) { // add as new items
				$content_array["c"][] = array("title" => strval($item["title"]),"content" => $itc, "comments" => $item["comments"], "customfields" => $item["customfields"], "unique" => array($source => strval($item["unique"])));			
		} elseif($_POST["bc_where"] == "top") { // add at top
			$content_array["c"][$i]["content"] = $itc . $content_array["c"][$i]["content"];
			$content_array["c"][$i]["unique"][$source] = strval($item["unique"]);
			if(empty($content_array["c"][$i]["comments"])) {$content_array["c"][$i]["comments"] = $item["comments"];}
		} elseif($_POST["bc_where"] == "bottom") { // add at bottom
			$content_array["c"][$i]["content"] .= $itc;
			$content_array["c"][$i]["unique"][$source] = strval($item["unique"]);	
			if(empty($content_array["c"][$i]["comments"])) {$content_array["c"][$i]["comments"] = $item["comments"];}
		}
		$i++;
		if($i >= $itemcount) {$i = 0;}
	}
	
	$content_array["commands"][] = array("cid" => $cid, "type" => $_POST["bc_action"], "keyword" => $_POST["bc_topic"], "num" => $_POST["bc_num"], "source" => $source, "where" => $_POST["bc_where"]);
				
	set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );	
	
	return $content_array;
}

/*================================================================== 2. Views ===============================================================*/

// Scripts
function wpr5_curation_page_print_scripts() {
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-cookie', plugins_url('/includes/jquery.cookie.js', __FILE__),array('jquery') );

	wp_register_style('jquery-uix', plugins_url('/includes/jquery-ui-1.10.3.custom.min.css', __FILE__));
	wp_enqueue_style( 'jquery-uix' ); 		
	wp_enqueue_script( 'jquery-ui-datepicker' );	
	
	wp_enqueue_style('wpr5-admin-styles', plugins_url('/includes/admin-styles.css', __FILE__) );		
}

// Header
function wpr5_curation_page_head() {

?>
    <script type="text/javascript">	
	jQuery(document).ready(function($) {
	
		jQuery( "#tabs" ).tabs({
			activate: function (e, ui) { 
				jQuery.cookie('selected-tab', ui.newTab.index(), { path: '/' }); 
			}, 
			active: jQuery.cookie('selected-tab')
		});		

		jQuery('#bc_date').datepicker({
			constrainInput: false,
			dateFormat: 'yy-mm-dd',
			onSelect: function(datetext){
				var d = new Date(); // Y-m-d H:i:s
				datetext=datetext+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
				jQuery('#bc_date').val(datetext);
			}
		});
		jQuery("#ui-datepicker-div").wrap('<div class="dpickr" />');		
		
		var down;
		var down2;
		
		jQuery(".bc_actionselect").change(function(){ // NOT OPTIMAL -- options for module stay visible if action is changed later !!!
			if (down != "") {
				jQuery("#" + down).hide();
			}					
			down = jQuery(this).val();
			jQuery("#" + down).slideDown("fast");
			if (down == "api" || down == "rss" || down == "csv" || down == "txt") {jQuery("#go").slideDown("fast");} else {jQuery("#go").hide();}
			
			if(down == "csv") {jQuery("#datafeed").slideDown("fast");} else {jQuery("#datafeed").hide();}
		});
		jQuery(".bc_optionselect").change(function(){
			if (down2 != "") {
				jQuery("#" + down2).hide();
			}					
			down2 = jQuery(this).val();
			jQuery("#" + down2).slideDown("fast");
		});	
		
		
		jQuery("#presets-list").change(function(){
			var vall = jQuery(this).val();

			if(vall == "posts") {				
				jQuery('#wp_posttype').val("post");
			}	
			
			if(vall == "pages") {				
				jQuery('#wp_posttype').val("page");
			}				
			
			if(vall == "woocommerce") {				
				jQuery('#wp_posttype').val("product");
			}	
			
			if(vall == "edd") {				
				jQuery('#wp_posttype').val("download");
			}	

			if(vall == "bbpress") {				
				jQuery('#wp_posttype').val("topic");
				jQuery("#bc_comments_repl").attr('checked', 'checked');
			} else {
				jQuery("#bc_comments_no").attr('checked', 'checked');
			}
		});			
			
		jQuery('a.tools-scheduling, a.tools-authors, a.tools-presets, a.rw-inject, a.rw-all').click(function(e) {
			e.preventDefault();	
			var theclass = jQuery(this).attr('class');
			jQuery('#' + theclass).toggle();
		});  		
		
		jQuery('a.delete').click(function(e) {	
			e.preventDefault();		
			var parent = jQuery(this).closest("ul");			
			var id = parent.attr('id').replace('record-','');
			jQuery("#bcc2-"+id).animate({'backgroundColor':'#FCB8B8'},300);			
			jQuery.get(ajaxurl, 'ajax=1&action=bc-delete&id=' + id, function(response) {
					jQuery("#bcc-"+id).slideUp(300,function() {
						jQuery("#bcc-"+id).remove();
					});
			});
			return false;
		});  
		
		jQuery('a.rewritetbs,a.rewritesc,a.rewritespinchimp,a.rewritesr,a.rewritewai').click(function(e) {	
			e.preventDefault();
			var parent = jQuery(this).closest("ul");			
			var id = parent.attr('id').replace('record-','');
			var service = jQuery(this).attr('class').replace('rewrite','');	
			jQuery("#bcc2-"+id).animate({'backgroundColor':'#E5EFFA'},1000);			
			jQuery.get(ajaxurl, 'ajax=1&action=bc-rewrite&id=' + id + '&service=' + service, function(response) {	
				if(response.error != undefined && response.error != "") {
					alert(response.error);
				} else if(response.content != undefined && response.content != "") {				
					jQuery("#bc-"+id).html(response.content);
				} else {
					alert("Unknown error");				
				}	
			}, "json");
			jQuery("#bcc2-"+id).animate({'backgroundColor':'##FFFFFF'},600);				
			return false;
		}); 		
		
	});
	</script>			
	<?php 
}

// Page Body
function wpr5_curation_page() {		
	wpr5_check_license_key();

?>	
<div class="wrap">
	<div id="saved"></div>
	<h1 style="margin-bottom:10px;"><?php _e('Create Bulk Content', "wprobot"); ?></h1>
<?php
	$user_id = get_current_user_id();	

	$sitedata["wpusers"] = get_users('number=50');
	$sitedata["wpcats"] = get_categories(array("hide_empty" => 0));
	$sitedata["wpposttypes"] = get_post_types('','names');		
	
	if($_POST["bc_clear"]) {
		delete_transient( 'wpr5_bc_'.$user_id );	
	}	
	$content_array = get_transient( 'wpr5_bc_'.$user_id );
	if(!is_array($content_array) && is_string($content_array)) {delete_transient( 'wpr5_bc_'.$user_id);$content_array = array();}
	
	$options = wpr5_get_options();	

	if($_GET['action'] == "bc-delete" && !$_POST) {
		$did = $_GET['id'];
		unset($content_array["c"][$did]);
		set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );
	} elseif($_GET['action'] == "bc-rewrite" && !$_POST) {
		$id = $_GET["id"];		
		@require_once("wpr_rewriter.php");
		$content = wpr5_rewrite($content_array["c"][$id]["content"], $_GET["service"], $options);
		
		if(is_array($content) && !empty($content["error"])) {
			echo '<div class="updated error"><p>'.esc_html($content["error"]).'</p></div>';	
		} else {			
			$content_array["c"][$id]["content"] = $content;
			set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );						
		}		
	}
	
	if($_POST["bc_rand_users"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		if(empty($sitedata)) {
			echo '<div class="updated error"><p>'.__('Please load site data first.', 'wprobot').'</p></div>';
		} else {
			if(isset($sitedata["wpusers"])) {
				$users = $sitedata["wpusers"];
			}
			
			if(empty($users)) {
				echo '<div class="updated error"><p>'.__('No generated users found.', 'wprobot').'</p></div>';
			} else {
			
				if(!is_array($content_array["c"])) {
					echo '<div class="updated error"><p>'.__('No content. Please generate it on the first tab.', 'wprobot').'</p></div>';
				} else {
				
					$count = count($content_array["c"]);				
					foreach ($content_array["c"]  as $i => $item) {$count = count($item["comments"]) + $count;}

					if($count > count($users)) {
						echo '<div class="updated error"><p>'.__('Not enough auto-generated users on site - at least one is necessary for each content item and comment. Create more on the "Users" page.', 'wprobot').'</p></div>';				
					} else {	
						$rand = array_rand($users, $count);	// choose as many rand users as necessary to prevent duplicates		
						$x = 0;
						foreach ($content_array["c"]  as $i => $item) {	
							$content_array["c"][$i]["user"] = $users[$rand[$x]]->user_login;	
							
							$x++;					
							
							if(is_array($item["comments"])) {
								foreach($item["comments"] as $c => $comment) {
									if($type == 1) {
										$content_array["c"][$i]["comments"][$c]["author"] = $users[$rand[$x]]["username"];
									} elseif($type == 2) {
										$content_array["c"][$i]["comments"][$c]["author"] = $users[$rand[$x]]->name;	
									} elseif($type == 3) {	
										$content_array["c"][$i]["comments"][$c]["author"] = $users[$rand[$x]]->user_login;	
									} elseif($type == 4) {	
										$content_array["c"][$i]["comments"][$c]["author"] = $users[$rand[$x]][0];	
										$content_array["c"][$i]["comments"][$c]["password"] = $users[$rand[$x]][1];	
									}
									$x++;
								}
							}
						}
						set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );	
					}
				}
			}
		}
	}
	
	if($_POST["bc_dates"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		$sp1 = $_POST['timespace'];
		$sp2 = $_POST['timespace2'];
		$time = explode("-", $_POST['time']);	
		$time[1] = (int) $time[1];
		$time[2] = (int) $time[2];
		$time[0] = (int) $time[0];
		foreach ($content_array["c"]  as $i => $item) {	
			if(is_numeric($time[1]) && is_numeric($time[2]) && is_numeric($time[0])) {
				$comment_date = mktime(rand(0,23), rand(0, 59), rand(0, 59), $time[1], $time[2] + $i* rand($sp1,$sp2), $time[0]);
				$ctime=date("Y-m-d H:i:s", $comment_date);													
				$content_array["c"][$i]["date"] = $ctime;
			}
		}
		set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );			
	}		

	if($_POST["bc_create"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) {
		
		// include spin content class
		if($_POST['bc_spin'] == 1) {
			include_once("includes/spintax.class.php");
			$spintax = new Spintax;			
		}
		
		$args = array();
		// build content
		if(is_array($content_array["c"])) {
			foreach ($content_array["c"]  as $num => $item) {
			
				// spin content
				if($_POST['bc_spin'] == 1) {
					$item["contentfinal"] = $spintax->process($item["content"]);
					$item["titlefinal"] = $spintax->process($item["title"]);
				} else {
					$item["contentfinal"] = $item["content"];
					$item["titlefinal"] = $item["title"];						
				}
			
				$content = array();
				$content['title'] = $item["titlefinal"];
				$content['description'] = $item["contentfinal"];

				$content['post_title'] = $item["titlefinal"];
				$content['post_content'] = $item["contentfinal"];

				if(!empty($item["user"])) {$content["post_author"] = $item["user"];} elseif(!empty($_POST["bc_user_new"])) {$content["post_author"] = $_POST["bc_user_new"];} elseif(!empty($_POST["bc_user"])) {$content["post_author"] = $_POST["bc_user"];} else {$content["post_author"] = 1;}																		
				if(empty($_POST["bc_status"])) {$content["post_status"] = "publish";} else {$content["post_status"] = $_POST["bc_status"];}
				if(!empty($_POST["bc_parent"])) {
					$content["post_parent"] = (int) $_POST["bc_parent"];	
				}
				if(!empty($item["date"])) {
					$content["post_date"] = $item["date"];
					$content["post_date_gmt"] = $item["date"]; // how to correct for gmt?
					$content["post_status"] = "future"; // SET SCHEDULED POST  -- !CHECK MISSING! only if date in future !!!							
				} elseif(!empty($_POST["bc_date"])) {
					$content["post_date"] = $_POST["bc_date"];
					$content["post_date_gmt"] = $_POST["bc_date"]; // how to correct for gmt?
					$content["post_status"] = "future"; // SET SCHEDULED POST  -- !CHECK MISSING! only if date in future !!!							
				} else {$content["post_date"] = "";}	
				if(!empty($_POST["wp_posttype"])) {$content["post_type"] = $_POST["wp_posttype"];} else {$content["post_type"] = "post";}
				
				if(!empty($_POST["bc_cat_new"])) {
					$args['params']["post"][$num]['post_data']['post_extras']['post_categories'] = $_POST["bc_cat_new"];
				} else {
					$args['params']["post"][$num]['post_data']['post_extras']['post_categories'] = $_POST["bc_cat"];
				}

				if(!empty($item["customfields"]) && is_array($item["customfields"])) {
					foreach($item["customfields"] as $cf) {
						$cfname = $cf["name"];
						$args['params']["post"][$num]['post_data']['post_extras']['post_meta'][$cfname] = array($cf["value"]);
					}
				}

				//print_r($content);
				$args['params']["post"][$num]['post_data']['post_data'] = $content;	
				$args['params']["post"][$num]['content'] = $content;
				if($_POST['bc_comments'] == 1) {
					$args['params']["post"][$num]['comments'] = $item["comments"];		
				}
				if($_POST['bc_comments'] == 2) {
					$args['params']["post"][$num]['replies'] = $item["comments"];		
				}
			}
			
			$result = wpr5_create_posts($args['params']);
			
			if (!empty($result["success"])) {
				echo '<div class="updated"><p>'.count($result["success"]).' '.__(' articles have been created successfuly.', 'wprobot').'</p></div>';
			} else {
				echo '<div class="updated error"><p>'.__('Error: ', 'wprobot').esc_html($result["error"]).'</p></div>';
			}						
		} else {
			echo '<div class="updated error"><p>'.__('Error: No content was built or found in the preview section below. Please do that on the "1. Build Content" tab.', 'wprobot').'</p></div>';				
		}
	
	}

	if($_POST["bc_go"] && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) { // CONTENT PARSING 
		$cid = rand(0, 9999);	
		//if(!is_array($content_array)) {$content_array = array();}

		if(!empty($_FILES["bc_plr_files"]["name"][0])) {	// PLR Parsing
		
		
			if(count($_FILES["bc_plr_files"]["name"]) > 999) {
				echo '<div class="error updated"><p>'.__('Error: For performance reasons the maximum number of PLR articles per import is limited to 999 files.', 'wprobot').'</p></div>';				
			} else {
				if ($_FILES['bc_plr_files']['error'][0] == 0) {
					$source = "plr";
					$plritems = array();	

					@ini_set('max_file_uploads', 100);
					
					//echo ini_get('max_file_uploads');echo "PLIMIT $postlimit <pre>";print_r($_FILES);echo "</pre>";
					
					foreach($_FILES["bc_plr_files"]["name"] as $i => $filename) {
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext == "txt") && ($_FILES["bc_plr_files"]["type"][$i] == "text/plain" || $_FILES["bc_plr_files"]["type"][$i] == "text/csv" || $_FILES["bc_plr_files"]["type"][$i] == "application/x-txt") && ($_FILES["bc_plr_files"]["size"][$i] < 500000)) {			
							if (($file = fopen($_FILES['bc_plr_files']['tmp_name'][$i], "r")) !== FALSE) {
								$output = ""; $title = "";
								while(!feof($file)) {	
									if(empty($title)) {$title = fgets($file, 4096);}
									$output = $output . fgets($file, 4096);	 
								}
								if(!empty($title) && !empty($output)) {
									$output = trim($output);
									$output = nl2br($output);
									$output = utf8_encode($output);
									$title = utf8_encode($title);
									//$output = iconv('UTF-8','ISO-8859-1//IGNORE', $output);
									//$title = iconv('UTF-8','ISO-8859-1//IGNORE', $title);
									$plritems[$source][] = array("title" => $title, "content" => $output, "unique" => "");
								}
							}
							fclose($file);					
						} else {
							echo '<div class="updated error"><p>'.__('Error: Only .txt files under 500Kb are accepted for upload.', 'wprobot').'</p></div>';	
							break;
						}
					}
					if(!empty($plritems)) {
						$content_array = wpr5_bulk_content_process($content_array, $plritems, $source, $cid, $_POST, $user_id);
						
					}
				} else {
					echo '<div class="updated error"><p>'.__('Error', 'wprobot').': '.esc_html($_FILES['bc_plr_files']['error'][0]).' - '.__('Upload failed', 'wprobot').'</p></div>';		
				}	
			}
		} elseif(!empty($_FILES["bc_csv_file"]["name"])) {	// CSV Parsing
			
			if($_POST["bc_csv_num"] > 999) {
				echo '<div class="error error"><p>'.__('Error: For performance reasons the maximum number of CSV items per import is limited to 999.', 'wprobot').'</p></div>';					
			} else {		
				if ($_FILES['bc_csv_file']['error'] == 0) {
					$filename = basename($_FILES['bc_csv_file']['name']);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					
					//print_r($_FILES);echo $ext;

					if (($ext == "csv") && ($_FILES["bc_csv_file"]["type"] == "application/octet-stream" || $_FILES["bc_csv_file"]["type"] == "text/plain" || $_FILES["bc_csv_file"]["type"] == "text/csv" || $_FILES["bc_csv_file"]["type"] == "application/x-csv")) {			

						$delim = $options["options"]["datafeed"]["options"]["delimiter"]["value"];
						$dunique = $options["options"]["datafeed"]["options"]["unique"]["value"];
						$dtitle = $options["options"]["datafeed"]["options"]["title"]["value"];					
					
						$source = "datafeed";
						$count = 0;	$csvitems = array();
						$csvtemplate = $options["options"]["datafeed"]["templates"]["default"]["content"];
						if (($handle = fopen($_FILES['bc_csv_file']['tmp_name'], "rb")) !== FALSE) {
							while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) { //, $_POST["datafeed_enclosure"]
								$itemcontent = $csvtemplate;
								foreach($options["options"]["datafeed"]["options"] as $oname => $ocont) {
									if($oname != "delimiter" && $oname != "enclosure") {
										$oval = $ocont["value"];
										$itemcontent = str_replace("{".$oname."}", $data[$oval], $itemcontent);	
									}
								}
								$csvitems[$source][] = array("title" => $data[$dtitle], "content" => $itemcontent, "unique" => $data[$dunique]);
								$count++;
								if($count == $_POST["bc_csv_num"]) {break;}
							}
							fclose($handle);
						}

						$content_array = wpr5_bulk_content_process($content_array, $csvitems, $source, $cid, $_POST, $user_id);
					
					} else {
						echo '<div class="updated error"><p>'.__('Error: Only .csv files under 500Kb are accepted for upload.', 'wprobot').'</p></div>';		
					}
				} else {
					echo '<div class="updated error"><p>'.__('Error', 'wprobot').': '.esc_html($_FILES['bc_csv_file']['error']).' - '.__('Upload failed', 'wprobot').'</p></div>';		
				}
			}
		} elseif(!empty($_POST["bc_source"])) {	// API Request and Parsing
		
			if($_POST["bc_num"] > 300) {
				echo '<div class="error error"><p>'.__('Error: For performance reasons the maximum number of API items per import is limited to 300.', 'wprobot').'</p></div>';								
			} else {

				$source = esc_html($_POST["bc_source"]);
				
				require_once("api.class.php");
				
				$api = new API_request;
				$contents = $api->api_content_bulk($_POST["bc_topic"], array($source => $_POST["bc_num"]), $_POST, array($source => $_POST[$source."_template"])); 

				if(!empty($contents[$source]["error"])) { // API error
					echo '<div class="updated error"><p>'.ucwords($source)." API Error: ".esc_html($contents[$source]["error"]).'</p></div>';		
				} elseif(empty($contents)) {	
					echo '<div class="updated error"><p>'.__('No content found. Please try again.', 'wprobot').'</p></div>';		
				} else {
					$content_array = wpr5_bulk_content_process($content_array, $contents, $source, $cid, $_POST, $user_id);
				}
			}
		} elseif(!empty($_POST["bc_rss_feeds"])) {	// RSS Parsing
		
			require_once("api.class.php");
			
			$feeds = str_replace("\r", "", $_POST['bc_rss_feeds']);
			$feeds = explode("\n", $feeds);
	
			$contents = array();
			$api = new API_request;
			foreach( $feeds as $feed) {
	
				if($feed != "") {
					$contents = $api->api_content_bulk("", array("rss" => array("count" => 10, "feed" => $feed)), $_POST, array("rss" => "default"), $feed); 

					if(!empty($contents["rss"]["error"])) { // API error
						echo '<div class="updated error"><p>'.__('RSS Error', 'wprobot').': '.esc_html($contents["rss"]["error"]).'</p></div>';		
					} elseif(empty($contents)) {	
						echo '<div class="updated error"><p>'.__('No content found. Please try again.', 'wprobot').'</p></div>';		
					} else {
						$content_array = wpr5_bulk_content_process($content_array, $contents, "rss", $cid, $_POST, $user_id);
					}		
				}
			}			
		}
	}	
	
	if($_POST["bc_inject"] && !empty($content_array["c"]) && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) { // INJECT CONTENT
		$aboptions = $options["options"]["articlebuilder"]["options"]; // ["email"]["value"]
		if(empty($aboptions["email"]["value"]) || empty($aboptions["email"]["value"])) {
			echo '<div class="updated below-h2"><p>'.__('Please enter your Articlebuilder.net user details on the Options page first.', 'wprobot').'</p></div>';			
		} else {
			$url = 'http://articlebuilder.net/api.php';

			$data = array();
			$data['action'] = 'authenticate';
			$data['format'] = 'php';
			$data['username'] = $aboptions["email"]["value"];
			$data['password'] = $aboptions["pw"]["value"];
			$output = unserialize(api_ab_curl_post($url, $data, $info));

			if($output['success']=='true') {
				$session = $output['session'];			
				
				$data = array();
				$data['session'] = $session;
				$data['format'] = 'php';
				$data['action'] = 'injectContent';  
				
				$data['category'] = $_POST["inject_category"];
				if($_POST["inject_volume"] == 1 || $_POST["inject_volume"] == 2 || $_POST["inject_volume"] == 3) {
					$data['volume'] = $_POST["inject_volume"];
				}
				if($_POST["inject_style"] == 1 || $_POST["inject_style"] == 2 || $_POST["inject_style"] == 3 || $_POST["inject_style"] == 4 || $_POST["inject_style"] == 5) {
					$data['style'] = $_POST["inject_style"];
				}
				if($_POST["inject_superspun"] == 1 || empty($_POST["inject_superspun"])) {
					$data['superspun'] = $_POST["inject_superspun"];
				}

				$posts = array(); $save = 0;
				foreach ($content_array["c"]  as $num => $item) {		

					$data['article'] = $item["content"];				
				
					$output = api_ab_curl_post($url, $data, $info);
					$output = unserialize($output);
					if($output['success']=='true'){
					
						$article = str_replace("\r", "<br>", str_replace("\n\n", "<p>", $output['output']));					
						$content_array["c"][$num]["content"] = $article;
						$save = 1;
					} else {
						echo '<div class="updated error"><p>ArticleBuilder Error: '.$output["error"].'</p></div>';						
					}
				}	
				if($save == 1) {
					set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );
					echo '<div class="updated"><p>ArticleBuilder Content has been injected. If some items are the same Article Builder did not find any content to insert.</p></div>';					
				}
			} else {
				echo '<div class="updated error"><p>ArticleBuilder Error: '.$output["error"].'</p></div>';				
			}			
		
		}
	}
	
	if($_POST["bc_rewrite"] && !empty($content_array["c"]) && check_admin_referer( 'cmsc-bulkcontent-form-'.$user_id )) { // BASIC REWRITING REQUEST
		$save = 0;
		@require_once("wpr_rewriter.php");
		foreach ($content_array["c"]  as $num => $item) {
			$content = wpr5_rewrite($item["content"], $_POST["bc_rewrite_service"], $options);
			if(is_array($content) && !empty($content["error"])) {
				echo '<div class="updated below-h2"><p>'.esc_html($content["error"]).'</p></div>';				
			} else {
				$content_array["c"][$num]["content"] = $content;
				$save = 1;					
			}
		}
		
		if($save == 1) {set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );}
	}
	
	if($_GET["undo"] && !$_POST) {
		$cont = 0;
		$cid = $_GET["undo"];	
		
		if(is_array($content_array["commands"])) {
			foreach ($content_array["commands"]  as $num => $comm) {
				if($comm["source"] . $comm["cid"] == $cid) {unset($content_array["commands"][$num]);$cont = 1;}
			}	
		}
		if($cont == 1) {	
			foreach ($content_array["c"]  as $num => $item) {
					
				//$content_array["c"][$num]["content"] = preg_replace('#\<!-- '.$cid.' START -->(.*)<!-- '.$cid.' END -->#smiU', "", $content_array["c"][$num]["content"]); 				
				
				$dom = new DOMDocument;
				@$dom->loadHTML($content_array["c"][$num]["content"]); // returns warning because of BAD HTML ? 
				$xPath = new DOMXPath($dom);
				$nodes = $xPath->query('//*[@id="'.$cid.'"]');
				
				for ($i = 0;  $i < $nodes->length; $i++ ) {
					if($nodes->item($i)) {
						$nodes->item($i)->parentNode->removeChild($nodes->item($i));
					}				
				}			

				$content_array["c"][$num]["content"] = $dom->saveHTML();
				if(empty($content_array["c"][$num]["content"]) || $content_array["c"][$num]["content"] == "  ") {unset($content_array["c"][$num]);} // NOT WORKING
			}

			set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );
		}
	}
	
?>

	<div id="tabs">	
		<form method="post" name="test_form" id="fluency-options" enctype="multipart/form-data" >	
		<?php wp_nonce_field( 'cmsc-bulkcontent-form-'.$user_id ); ?>
		
			<div id="cmsc-tabbar">			
				<div id="cmsc-tabbar-siteshead"><?php _e('Your Sites', 'wprobot'); ?></div>
				<ul class="tabs">
					<li><a href="#tabs-1" title="1. Build Content"><?php _e('1. Build Content', 'wprobot'); ?></a></li>	
					<li><a href="#tabs-2" title="2. Tools and Rewriting"><?php _e('2. Tools and Rewriting', 'wprobot'); ?></a></li>
					<li><a href="#tabs-3" title="3. Publish Articles"><?php _e('3. Publish Articles', 'wprobot'); ?></a></li>
				</ul>		
				<div style="clear:both;"></div>
			</div>

		<div id="cmsc-main">
			<div id="cmsc-main-content">					
				
				<div class="bc-tabs bulk-content-box" id="tabs-1">	
		
					<ul>
					<?php if(is_array($content_array["commands"])) { foreach($content_array["commands"] as $n => $command) {?>
						<li>
						<?php if($command["type"] == "api") { ?><?php _e('Fetched', 'wprobot'); ?> <?php echo esc_html($command["num"]) . " " . esc_html($command["source"]); ?> <?php _e('items related to the topic', 'wprobot'); ?> <?php echo esc_html($command["keyword"]); ?> <?php _e('and', 'wprobot'); ?> <?php } ?>
						<?php if($command["type"] == "csv") { ?><?php _e('Imported datafeed items and', 'wprobot'); ?> <?php } ?>
						<?php if($command["type"] == "txt") { ?><?php _e('Imported PLR articles and', 'wprobot'); ?> <?php } ?>
						<?php if($command["type"] == "rss") { ?><?php _e('Imported RSS feeds and', 'wprobot'); ?> <?php } ?>
						<?php if($command["where"] == "new" || empty($command["where"])) { ?> <?php _e('added them as new content.', 'wprobot'); ?><?php } elseif($command["where"] == "top") { ?> <?php _e('added them to the beginning of the current content.', 'wprobot'); ?><?php } elseif($command["where"] == "bottom") { ?> <?php _e('added them to the end of the current content.', 'wprobot'); ?><?php } ?>
						<?php if(!empty($command["cid"])) { ?>(<a href="?page=bulk-content&undo=<?php echo esc_html($command["source"].$command["cid"]); ?>">Undo</a>)<?php } ?>		
						</li>
					<?php } } ?>						
						<li>
							<span>
								<select name="bc_action" class="bc_actionselect">
									<option value=""><?php _e('What do you want to do?', 'wprobot'); ?></option>
									<option value="api"><?php _e('Create API Content', 'wprobot'); ?></option>
									<?php if($options["options"]["datafeed"]["disabled"] === 0) { ?><option value="csv"><?php _e('Upload a Datafeed / .csv files', 'wprobot'); ?></option><?php } ?>
									<?php if($options["options"]["plr"]["disabled"] !== 0) { ?><option value="txt"><?php _e('Upload PLR content / .txt files', 'wprobot'); ?></option><?php } ?>
									<?php if($options["options"]["rss"]["disabled"] === 0) { ?><option value="rss"><?php _e('Parse RSS Feeds', 'wprobot'); ?></option><?php } ?>
								</select> 
							</span>
							<!-- API --><span style="display:none;" id="api"><br/><br/>
								<?php _e('Get', 'wprobot'); ?> <input type="text" class="small-text" name="bc_num" value="10">
								<select class="bc_optionselect" name="bc_source" id="bc_source">
									<option value=""><?php _e('(choose one)', 'wprobot'); ?></option>			
									<?php $modulearray = $options["options"]; foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 && $moduledata["display"] != "no") { ?>
									<option value="<?php echo $module; ?>"><?php echo $moduledata["name"]; ?></option>
									<?php } } ?>
								</select> <?php _e('items related to the <strong>topic</strong>:', 'wprobot'); ?> <input type="text" class="text" name="bc_topic" value="<?php echo esc_attr($_POST["bc_topic"]); ?>">					
							</span>
							<!-- CSV --><span style="display:none;" id="csv"><br/><br/>
								<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
								<?php _e('Upload the .csv file', 'wprobot'); ?> <input type="file" name="bc_csv_file" id="bc_csv_file" class="regular-text"> <?php _e('and parse the first', 'wprobot'); ?> <input type="text" class="small-text" name="bc_csv_num" value="50"> <?php _e('items', 'wprobot'); ?>
							</span>
							<!-- PLR --><span style="display:none;" id="txt"><br/><br/>
								<?php _e('Upload and import the following .txt files', 'wprobot'); ?> <input multiple="multiple" type="file" name="bc_plr_files[]" id="bc_plr_files" class="regular-text"> <?php _e('(select several by dragging or holding down "strg". Multiple file selection only works in new browsers!)', 'wprobot'); ?>
							</span>
							<!-- RSS --><span style="display:none;" id="rss"><br/><br/><?php _e('Scan all items in the following RSS Feeds (one URL starting with http:// per line)', 'wprobot'); ?>
								<br/><textarea cols="80" rows="3" name="bc_rss_feeds"><?php echo esc_attr($_POST["bc_rss_feeds"]); ?></textarea><br/>
							</span>
							<span style="display:none;" id="go">
								<?php if(!empty($content_array["commands"])) { ?>
								<?php _e('and', 'wprobot'); ?> <select name="bc_where" class="bc_where">
								<option value="new"><?php _e('add them as new content', 'wprobot'); ?></option>
								<option value="bottom"><?php _e('add them to the end of the current content', 'wprobot'); ?></option>
								<option value="top"><?php _e('add them to the beginning of the current content', 'wprobot'); ?></option>
							</select>
								<?php } ?>
								<input class="button-primary" type="submit" value="<?php _e('Go', 'wprobot'); ?>" name="bc_go" id="bc_go">
							</span>
							
							<?php $modulearray = $options["options"];$num = 0; if(is_array($modulearray)) { foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 && $moduledata["display"] != "no") {$num++; ?>			
								<div style="display:none;" id="<?php echo $module;?>">	
								
									<label for="<?php echo $module;?>_template"><strong>Template</strong>:</label>	
									<select name="<?php echo $module;?>_template" id="<?php echo $module;?>_template">		
										<?php foreach($moduledata["templates"] as $template => $templatedata) { ?>
										<option value="<?php echo $template;?>"><?php echo $templatedata["name"]; ?></option>
										<?php } ?>
									</select>	
								
									<div id="bc-module-settings">
									<i><?php _e('Your settings (edit to override)', 'wprobot'); ?>:</i><br/>
									<?php foreach($moduledata["options"] as $option => $data) {
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
							<?php } } } ?>								
						</li>
					</ul>
				</div>
				
				<div class="bc-tabs bulk-content-box" id="tabs-2">	
			
					<div id="tools-scheduling">		
						<h3 style="margin-top: 0;"><?php _e('Set Random Dates for Scheduling All Posts', 'wprobot'); ?></h3>	
						<?php _e('Start Date', 'wprobot'); ?>: <input type="text" value="<?php echo date('Y-m-d'); ?>" id="time" name="time" style="background:#fff;" size="11"><br>
						<?php _e('Between Posts', 'wprobot'); ?>: <input type="text" value="1" id="time" name="timespace" style="background:#fff;" size="3"> <?php _e('to', 'wprobot'); ?> <input type="text" value="2" id="time" name="timespace2" style="background:#fff;" size="3"> <?php _e('day(s)', 'wprobot'); ?>	
						<br/>
						<span>		
							<input class="button" id="bc_dates" type="submit" name="bc_dates" value="<?php _e('Assign Random Dates', 'wprobot'); ?>" />
						</span>				
					</div>	
					
					<div id="tools-authors">	
						<h3><?php _e('Set Random Authors for each Post', 'wprobot'); ?></h3>			
						<span>		
							<input class="button" id="bc_rand_users" type="submit" name="bc_rand_users" value="<?php _e('Assign Random Users', 'wprobot'); ?>" />
						</span>
					</div>	
					
					<h3><?php _e('Rewriting', 'wprobot'); ?></h3>		
			
					<p><strong><?php _e("Parse spin tags while posting to your sites","wprobot") ?></strong><br/><input id="bc_spin" type="checkbox" name="bc_spin" value="1" checked /> <strong><?php _e("Yes","wprobot") ?></strong> <i>(<?php _e("Supports spin tags in the format {spin|this|randomly}, nested tags are supported and a different spin is returned for each individual site you post to.","wprobot") ?>)</i>
					</p>

					<p>		
						<span style="font-size: 110%;">&rarr; <a href="#" class="rw-inject"><?php _e('Inject content with your Article Builder account', 'wprobot'); ?></a></span><br/>	
						<div id="rw-inject" style="display: none;margin: 10px 0;">						
							<?php _e('<strong>ArticleBuilder.net Content Injection: </strong>',"wprobot") ?>
							<?php if($options["options"]["articlebuilder"]["disabled"] != 1) {_e('Use the articlebuilder.net API to inject related content to the articles in your preview section below.',"wprobot") ?>
							<br/><strong><?php _e('Category', 'cmsc'); ?>: </strong>	
							<select name="inject_category">
								<option value="0">Select A Category</option><option value="affiliate marketing">Business - Affiliate Marketing </option><option value="article marketing">Business - Article Marketing </option><option value="email marketing">Business - Email Marketing </option><option value="forex">Business - Forex </option><option value="home business">Business - Home Business </option><option value="internet marketing">Business - Internet Marketing </option><option value="mobile marketing">Business - Mobile Marketing </option><option value="network marketing">Business - Network Marketing </option><option value="search engine optimization">Business - Search Engine Optimization </option><option value="social media marketing">Business - Social Media Marketing </option><option value="credit cards">Finance - Credit Cards </option><option value="credit repair">Finance - Credit Repair </option><option value="insurance - auto">Finance - Insurance - Auto </option><option value="insurance - general">Finance - Insurance - General </option><option value="insurance - life">Finance - Insurance - Life </option><option value="personal bankruptcy">Finance - Personal Bankruptcy </option><option value="personal finance">Finance - Personal Finance </option><option value="real estate - buying">Finance - Real Estate - Buying </option><option value="real estate - commercial">Finance - Real Estate - Commercial </option><option value="stock market">Finance - Stock Market </option><option value="acne">Health - Acne </option><option value="aging">Health - Aging </option><option value="allergies">Health - Allergies </option><option value="anxiety">Health - Anxiety </option><option value="arthritis">Health - Arthritis </option><option value="asthma">Health - Asthma </option><option value="back pain">Health - Back Pain </option><option value="beauty">Health - Beauty </option><option value="cancer">Health - Cancer </option><option value="cosmetic surgery">Health - Cosmetic Surgery </option><option value="depression">Health - Depression </option><option value="diabetes">Health - Diabetes </option><option value="fitness">Health - Fitness </option><option value="hair care">Health - Hair Care </option><option value="hair loss">Health - Hair Loss </option><option value="hemorrhoids">Health - Hemorrhoids </option><option value="insurance - health">Health - Insurance - Health </option><option value="juicing">Health - Juicing </option><option value="memory">Health - Memory </option><option value="muscle building">Health - Muscle Building </option><option value="nutrition">Health - Nutrition </option><option value="panic attacks">Health - Panic Attacks </option><option value="personal development">Health - Personal Development </option><option value="quit smoking">Health - Quit Smoking </option><option value="skin care">Health - Skin Care </option><option value="snoring">Health - Snoring </option><option value="stress">Health - Stress </option><option value="teeth whitening">Health - Teeth Whitening </option><option value="tinnitus">Health - Tinnitus </option><option value="weight loss">Health - Weight Loss </option><option value="cooking">Home And Family - Cooking </option><option value="dog training">Home And Family - Dog Training </option><option value="gardening">Home And Family - Gardening </option><option value="home improvement">Home And Family - Home Improvement </option><option value="insurance - home owner's">Home And Family - Insurance - Home Owner's *</option><option value="landscaping">Home And Family - Landscaping </option><option value="organic gardening">Home And Family - Organic Gardening </option><option value="parenting">Home And Family - Parenting </option><option value="plumbing">Home And Family - Plumbing </option><option value="pregnancy">Home And Family - Pregnancy </option><option value="fishing">Recreation - Fishing </option><option value="golf">Recreation - Golf </option><option value="photography">Recreation - Photography </option><option value="travel">Recreation - Travel </option><option value="jewelry">Shopping - Jewelry </option><option value="real estate - selling">Society - Real Estate - Selling </option><option value="weddings">Society - Weddings </option><option value="blogging">Technology - Blogging </option><option value="green energy">Technology - Green Energy </option><option value="web design">Technology - Web Design </option><option value="web hosting">Technology - Web Hosting *</option>
							</select>
							<br/>
							<strong><?php _e('How much Content', 'cmsc'); ?>: </strong>							
							<select name="inject_volume">
								<option value="3"><?php _e('A Little', 'cmsc'); ?></option>
								<option value="2"><?php _e('Quite A Bit', 'cmsc'); ?></option>
								<option value="1"><?php _e('A Lot', 'cmsc'); ?></option>
							</select>	
							<br/>
							<strong><?php _e('Where to Add', 'cmsc'); ?>: </strong>	
							<select name="inject_style">
								<option value="1">Inside The Content</option>
								<option value="2">As Sidebar "Tips"</option>
								<option value="3">Both Inside and Sidebar</option>
								<option value="4">As In-Line "Callouts"</option>
								<option value="5">Both Inside and Callouts</option>
							</select>
							<br/>
							<strong>Super Spun: </strong>							
							<input type="checkbox" name="inject_superspun" value="1">
							<br/>	
							<input class="button-primary" id="bc_inject" type="submit" name="bc_inject" value="<?php _e("Inject Content","wprobot") ?>" />
							<?php } else { _e('Please activate ArticleBuilder.net on the "Choose Sources" page first and enter your user details on the "Options" page.',"wprobot");} ?>
						</div>
						
						<span style="font-size: 110%;">&rarr; <a href="#" class="rw-all"><?php _e('Rewrite all articles below', 'wprobot'); ?></a></span><br/>	
						<div id="rw-all" style="display: none;margin: 10px 0;">		
							<?php if($options["options"]["wordai"]["disabled"] != 1 || $options["options"]["spinrewriter"]["disabled"] != 1 || $options["options"]["thebestspinner"]["disabled"] != 1 || $options["options"]["spinchimp"]["disabled"] != 1 || $options["options"]["spinnerchief"]["disabled"] != 1) {?>
							<div><?php _e("Rewrite all content below with ","wprobot") ?> <select name="bc_rewrite_service">
								<?php if($options["options"]["thebestspinner"]["disabled"] != 1) {?><option value="tbs">TheBestSpinner.com</option><?php } ?>
								<?php if($options["options"]["spinnerchief"]["disabled"] != 1) {?><option value="sc">SpinnerChief.com</option><?php } ?>
								<?php if($options["options"]["spinchimp"]["disabled"] != 1) {?><option value="spinchimp">SpinChimp.com</option><?php } ?>
								<?php if($options["options"]["spinrewriter"]["disabled"] != 1) {?><option value="sr">SpinRewriter.com</option><?php } ?>
								<?php if($options["options"]["wordai"]["disabled"] != 1) {?><option value="wai">WordAI</option><?php } ?>	
							</select>
							: <input class="button-primary" id="bc_rewrite" type="submit" name="bc_rewrite" value="<?php _e("Rewrite Now","wprobot") ?>" />
							</div>	
							<?php } else { ?><p><?php _e('Please activate a rewriter on the Options page first.', 'cmsc'); ?></p><?php }  ?>
						</div>						
					</p>					
	
					<!--<p>		
						<?php _e("<strong>Link the keyword</strong>","wprobot") ?> <input type="text" value="" name="bc_link_kw_word"> to the URL <input type="text" value="" name="bc_link_kw_url"> 
						<select name="bc_link_kw_chance">
							<option value="20">20%</option>
							<option value="40">40%</option>
							<option value="60">60%</option>
							<option value="80">80%</option>
							<option value="100">100%</option>
						</select>
						<?php _e("of the times it occurs in the content below.","wprobot") ?> <input class="button-primary" id="bc_link_kw" type="submit" name="bc_link_kw" value="<?php _e("Go","wprobot") ?>" />
					</p>	-->				
				</div>
	
				<div class="bc-tabs bulk-content-box" id="tabs-3">		

					
				
					<div style="clear:both;">
						<h3 style="margin-top: 0;"><?php _e( '<strong>Post Settings</strong>', 'wprobot' ); ?></h3>
						
						<div>
							<span style="font-size: 110%;">&rarr; <a href="#" class="tools-presets"><?php _e('Presets for posting products, forum threads and others', 'wprobot'); ?></a></span><br/>	
							<div id="tools-presets" style="display: none;margin: 10px 0;">	
								<strong><?php _e('What do you want to create?', 'wprobot'); ?></strong><br/>			
								<select name="presets-list" id="presets-list">
									<option value="posts"><?php _e('Default WordPress posts', 'wprobot'); ?></option>
									<option value="pages"><?php _e('WordPress pages', 'wprobot'); ?></option>
									<option value="woocommerce"><?php _e('WooCommerce products', 'wprobot'); ?></option>
									<option value="edd"><?php _e('Easy Digital Downloads downloads', 'wprobot'); ?></option>
									<option value="bbpress"><?php _e('bbPress forum topics', 'wprobot'); ?></option>
								</select>
								<br/>
								<span><?php _e('Make a choice above to automatically configure the post settings below.', 'wprobot'); ?></span>
							</div>						
						</div>						
					
						<table class="form-table">
							<tbody>	
							
								<tr>
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
								</tr>		

								<tr>
									<th scope="row"><label for="wp_posttype"><?php _e("Post Type","wprobot") ?></label></th>
									<td>
										<?php 
										if(isset($sitedata["wpposttypes"])) { ?>
										<select id="wp_posttype" name="wp_posttype">
											<?php foreach($sitedata["wpposttypes"] as $wpposttype) { ?>
												<option <?php if($_POST["wp_posttype"] == $wpposttype) {echo "selected";} ?> value="<?php echo $wpposttype; ?>"><?php echo ucwords($wpposttype); ?></option>
											<?php } ?>
										</select>										
										<?php } else { ?>	
											<input type="text" value="<?php if(!empty($_POST["wp_posttype"])) {echo esc_attr($_POST["wp_posttype"]);} else {echo "post";} ?>" name="wp_posttype" id="wp_posttype">											
											<br/><em><?php _e('Can be "post", "page", a custom post type or "topic" if posting to bbPress.',"wprobot") ?></em>
										<?php } ?>											
									</td>	
								</tr>

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
										<br/><em><?php _e("If the username does not exist on a blog yet it <strong>will get created</strong> automatically.","wprobot") ?></em>								
									</td>	
								</tr>	
								
								<tr>
									<th scope="row"><label for="bc_date"><?php _e("Date (optional)","wprobot") ?></label></th>
									<td>
										<input type="text" value="<?php echo esc_attr($_POST["bc_date"]); ?>" name="bc_date" id="bc_date">
										<br/><em><?php _e("Enter to <strong>schedule future post</strong>. Leave empty to use current date and time","wprobot") ?></em>
									</td>	
								</tr>

								<tr>
									<th scope="row"><label for="bc_parent"><?php _e("Parent (optional)","wprobot") ?></label></th>
									<td>
										<input type="text" value="<?php echo esc_attr($_POST["bc_parent"]); ?>" name="bc_parent" id="bc_parent">
										<br/><em><?php _e("Enter a <strong>page ID</strong> to create subpages. To create bbPress forum topics enter the <strong>forum ID</strong> here.","wprobot") ?></em>
									</td>	
								</tr>									
	
								<tr>
									<th scope="row"><label for="bc_comments"><?php _e("Add Comments","wprobot") ?></label></th>
									<td>
									
										<label for="bc_comments_no"><input <?php if($_POST["bc_comments"] == 0 || empty($_POST["bc_comments"])) {echo "checked";} ?> type="radio" name="bc_comments" value="0" id="bc_comments_no"> <?php _e('No', 'wprobot'); ?></label>
										<label style="margin-left: 12px;" for="bc_comments_yes"><input <?php if($_POST["bc_comments"] == 1) {echo "checked";} ?> type="radio" name="bc_comments" value="1" id="bc_comments_yes"> <?php _e('Yes, as WP comments', 'wprobot'); ?></label>
										<label style="margin-left: 12px;" for="bc_comments_repl"><input <?php if($_POST["bc_comments"] == 2) {echo "checked";} ?> type="radio" name="bc_comments" value="2" id="bc_comments_repl"> <?php _e('Yes, as bbPress replies', 'wprobot'); ?></label>
									</td>	
								</tr>							
							</tbody>
						</table>							
					
						<p class="submit">		
							<input class="button-primary" id="bc_create" type="submit" name="bc_create" value="<?php _e('Create Posts Now', 'wprobot'); ?>" />
						</p>
					</div>
				
				</div>
	
				<div id="content-preview">
		
					<?php if(is_array($content_array["c"])) {?>		
					<div style="float:right;margin: -5px 10px 0 0;">
						<input id="bc_clear" type="submit" class="button" name="bc_clear" value="<?php _e('Clear Content and Restart', 'wprobot'); ?>" />
					</div>			
					<h3><?php _e('Content Preview', 'wprobot'); ?> <span style="font-size: 70%;font-weight:normal;"><?php _e('(not posted to your sites yet - go to "3. Post to Your Sites" to do that)', 'wprobot'); ?></span></h3>
					<?php foreach($content_array["c"] as $n => $item) {?>
						<div class="bc-preview-box" id="bcc-<?php echo esc_attr($n);?>">
							<div class="bc-preview-tools">
								<div class="bc-preview-tools-no">
									<?php _e('Article', 'wprobot'); ?> #<?php echo esc_attr($n + 1);?>
								</div>
								<?php if(!empty($item["date"])) { ?>
									<p style="color:#eee;"><strong><?php _e('Schedule Date', 'wprobot'); ?></strong>:<br/><?php echo esc_html($item["date"]); ?></p>
								<?php } ?>		
								<?php if(!empty($item["user"])) { ?>
									<p style="color:#eee;"><strong><?php _e('Author Username', 'wprobot'); ?></strong>:<br/><?php echo esc_html($item["user"]); ?></p>
								<?php } ?>	
							
								<ul id="record-<?php echo esc_attr($n);?>">
									<span><strong><?php _e('Actions', 'wprobot'); ?></strong>:</span>
									<li><a href="?page=bulk-content&action=bc-delete&id=<?php echo esc_attr($n);?>&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-delete_$n" ) ); ?>" class="delete"><?php _e('Delete This', 'wprobot'); ?></a></li>
									<!--<li><a href="?page=post-editor&action=bc-edit&id=<?php echo esc_attr($n);?>"><?php _e('Go to Editor', 'wprobot'); ?></a></li>									
									-->
									<span><strong><?php _e('Rewrite', 'wprobot'); ?></strong>:</span>
									<?php if($options["options"]["thebestspinner"]["disabled"] != 1) {$yee = 1;?><li><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo esc_attr($n);?>&service=tbs&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritetbs">TheBestSpinner</a></li><?php } ?>	
									<?php if($options["options"]["spinnerchief"]["disabled"] != 1) {$yee = 1;?><li><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo esc_attr($n);?>&service=sc&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritesc">SpinnerChief</a></li><?php } ?>	
									<?php if($options["options"]["spinchimp"]["disabled"] != 1) {$yee = 1;?><li><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo esc_attr($n);?>&service=spinchimp&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritespinchimp">SpinChimp</a></li><?php } ?>	
									<?php if($options["options"]["spinrewriter"]["disabled"] != 1) {$yee = 1;?><li><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo esc_attr($n);?>&service=sr&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritesr">SpinRewriter</a></li><?php } ?>	
									<?php if($options["options"]["wordai"]["disabled"] != 1) {$yee = 1;?><li><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo esc_attr($n);?>&service=wai&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritewai">WordAI</a></li><?php } ?>	
									
									<?php if($yee != 1) {?><li><a href="?page=wpr5-options" class=""><?php _e('Activate in Options', 'cmsc'); ?></a></li><?php } ?>	
								
								</ul>
						
							</div>
							<div class="bc-preview-content" id="bcc2-<?php echo esc_attr($n);?>">
								<div class="bc-preview-content-title">
									<strong><?php echo esc_html($item["title"]); ?></strong>
								</div>	
								<div class="bc-preview-content-main" id="bc-<?php echo esc_attr($n);?>">
									<?php echo $item["content"]; ?>
								</div>	
								<div style="clear:both;"></div>
								<?php if(!empty($item["comments"])) { ?>
								<div class="bc-preview-content-comments">				
									<strong><?php echo count($item["comments"]); ?> <?php _e('Comments', 'wprobot'); ?>:</strong><br/>
									<?php foreach($item["comments"] as $comment) { ?>
										<div><?php echo esc_html($comment["author"]); ?>: <?php echo $comment["content"]; ?></div>
									<?php } ?>											
								</div>	
								<?php } ?>		
							</div>							
						</div>
						<div style="clear:both;"></div>
					<!--
						<div style="clear:both;background:#fff;margin-bottom: 15px;border-radius:10px;" id="bcc-<?php echo $n;?>">
							<div style="border-bottom: 2px dotted #000;">
								<?php if(!empty($item["date"]) || !empty($item["user"])) { ?>
									<div style="padding: 10px;float: right;border-left: 2px dotted #000;">
										Date: <?php echo $item["date"]; ?><br/>
										User: <?php echo $item["user"]; ?>
									</div>
								<?php } ?>	
								<div style="padding: 10px;" id="record-<?php echo $n;?>">								
									<span><strong>#<?php echo $n;?></strong> <?php echo $item["title"]; ?></span>
									<a href="?page=bulk-content&action=bc-delete&id=<?php echo $n;?>&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-delete_$n" ) ); ?>" class="delete">Delete</a>
									<a href="?page=post-editor&action=bc-edit&id=<?php echo $n;?>">Edit</a>									
									Rewrite: 
									<?php if($options["options"]["thebestspinner"]["disabled"] != 1) {?><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo $n;?>&service=tbs&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritetbs">TheBestSpinner</a><?php } ?>	
									<?php if($options["options"]["spinnerchief"]["disabled"] != 1) {?><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo $n;?>&service=sc&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritesc">SpinnerChief</a><?php } ?>	
									<?php if($options["options"]["spinchimp"]["disabled"] != 1) {?><a href="?page=bulk-content&action=bc-rewrite&id=<?php echo $n;?>&service=spinchimp&<?php echo esc_html( '_wpnonce=' . wp_create_nonce( "bc-rewrite_$n" ) ); ?>" class="rewritespinchimp">SpinChimp</a><?php } ?>	
								</div>
							</div>
							<div style="padding: 10px;" id="bc-<?php echo $n;?>">					
							<?php echo $item["content"]; ?>
							</div>
							
							<?php if(!empty($item["comments"])) { ?>
								<div style="margin-top: 10px; border-top: 1px dotted #000;padding: 10px;">					
									<?php echo count($item["comments"]); ?> Comments: <br/>
									<?php foreach($item["comments"] as $comment) { ?>
										<div><?php echo $comment["author"]; ?>: <?php echo $comment["content"]; ?></div>
									<?php } ?>							
								</div>
							<?php } ?>					
						</div>
						-->
					<?php } } ?>	
				</div>
			</div>
		</div>	
		
	</div>		
		
	</form>		

</div>

<?php
}

add_action('wp_ajax_bc-delete', 'wpr5_bc_delete2');
function wpr5_bc_delete2() {
	$did = $_GET['id'];

	//check_ajax_referer("bc-delete_$did", 'security');	
	
	$user_id = get_current_user_id();	
	
	$content_array = get_transient( 'wpr5_bc_'.$user_id );
	unset($content_array["c"][$did]);
	set_transient( 'wpr5_bc_'.$user_id, $content_array, 6000 );
	
	//die(1);break;
}


add_action('wp_ajax_bc-rewrite', 'wpr5_bc_rewrite');
function wpr5_bc_rewrite() {

	$user_id = get_current_user_id();	
	
	$service = $_GET["service"];
	$id = $_GET["id"];
	
	$content_array = get_transient( 'wpr5_bc_'.$user_id );
	$options = wpr5_get_options();

	@require_once("wpr_rewriter.php");
	$content = wpr5_rewrite($content_array["c"][$id]["content"], $service, $options);
	
	if(is_array($content) && !empty($content["error"])) {
		echo json_encode(array("error" => "Error: ".$content["error"]));
		exit;	
	} else {			
		$content_array["c"][$id]["content"] = $content;
		set_transient( 'wpr5_bc_'.$user_id, $content_array, 12000 );
		echo json_encode(array("content" => $content));
		exit;								
	}
}

?>