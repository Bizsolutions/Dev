<?php
/*********************************************************************************************************************************************/
/*                                                                 DISABLE PAGE                                                              */           
/*********************************************************************************************************************************************/

/*================================================================ 1. Functions =============================================================*/




/*================================================================== 2. Views ===============================================================*/

// Scripts
function wpr5_create_campaign_page_print_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	
	wp_enqueue_style('wpr5-admin-styles', plugins_url('/includes/admin-styles.css', __FILE__) );	
}

// Header
function wpr5_create_campaign_page_head() {
?>
	<style>
	 .wpr5ac {
		padding: 5px 7px;
		background: #F9F9F9;
	 }
	 
	.wpr5_template_box {
		display: inline-block;
		vertical-align: top;
		width: 330px;
		margin: 0 15px 15px 0;
		background: #fff;
		padding: 7px;
	}
	
	.wpr5_add_source_form {
		border: 1px solid #ccc;
		margin: 10px 0;
		padding: 10px;	
	}


	.subform {
		display: none;
		background: #fff;
		border-left: 1px solid #ccc;
		border-right: 1px solid #ccc;
		border-bottom: 1px solid #ccc;
		padding: 10px;
		position:absolute;
		width: 318px;
		margin-left: -11px;

	}	
	
	.wpr5_add_source_form:hover > .subform {
		display: block;	

	}
	
	.wpr5_template_title {
		text-align: center;	
		border-bottom: 1px dotted #ccc;
		padding: 10px 0;
	}
	
	.wpr5_template_name {
		text-align: center;
		border-bottom: 1px dotted #ccc;
		text-transform: uppercase;
		letter-spacing: 0.5px;		
	}

	.wpr5_modulelist {
		border: 1px solid #ccc;
		margin: 10px 0;
		padding: 10px;
		font-size: 110%;
	}
	
	.delete_template_link {
		float:right;
		display: block;
		color: #cc0000;
		font-size: 125%;
		text-decoration: none;
	}
	
	#finish_box {
		position: fixed;
		padding: 25px;
		background: #fff;
		border: 1px dotted #ccc;
	}
	
	#create_button {
		border-top: 1px dotted #ccc;
		margin-top: 30px;
		padding: 30px 0 0 0;
		text-align: center;
	}
	
	#step_list ol {
		margin-top: 0;
	}
	
	.stepactive {
		color: #1ACE20;
	}
	
	.main_module {
		background-color: #F2FDFF;
	}
	
	.add_module_source {
		width: 110px;
	}
	
	</style>
	
    <script type="text/javascript">
	jQuery(document).ready(function($) {
	
		jQuery( "#tabs" ).tabs({
		});		
		
		<?php
			if(!empty($_POST)) {
				$c_template_count = (int) sanitize_text_field($_POST['wpr5_template_counter']);			
				for ($i = 1; $i <= $c_template_count; $i++) {
					$tmodules = explode("}{", $_POST["template_content_".$i]);
					foreach($tmodules as $tm) {
						$tm = str_replace(array("{", "}"), "", $tm);
						$tm = explode("|", $tm);
						?>
							jQuery( "#cmsc-tabbar li.tabby-<?php echo $tm[0]; ?>" ).show();
						<?php
					}	
				}
			}
		?>
	
		<?php if((!empty($_POST["keywords"]) && !empty($_POST["feeds"])) || !empty($_GET["edit"])) { ?>jQuery( "li#step1" ).addClass("stepactive");<?php } ?>
		<?php if(!empty($_POST["categories"]) || !empty($_GET["edit"])) { ?>jQuery( "li#step2" ).addClass("stepactive");<?php } ?>
		<?php if(!empty($_POST["interval"]) || !empty($_GET["edit"])) { ?>jQuery( "li#step3" ).addClass("stepactive");<?php } ?>
		<?php if((!empty($_POST["wpr5_template_counter"]) && $_POST["wpr5_template_counter"] > 1) || !empty($_GET["edit"])) { ?>jQuery( "li#step4" ).addClass("stepactive");<?php } ?>
	
		jQuery( ".the_overrider" ).change(function() {
			var theval = jQuery(this).is(":checked");
			var themod = jQuery(this).attr("id").replace('_overrider','');

			if(theval == true) {
				jQuery( ".settingtab-" + themod + " p.thesettings" ).css({"opacity":"1"});
			} else {
				jQuery( ".settingtab-" + themod + " p.thesettings" ).css({"opacity":"0.2"});
			}
		});	
	
		jQuery( "#wpr5_keywords, #wpr5_feeds" ).change(function() {
			var theval = jQuery(this).val();
			if(theval != "" && theval != undefined) {
				jQuery( "li#step1" ).addClass("stepactive");
			} else {
				jQuery( "li#step1" ).removeClass("stepactive");
			}
		});	
		jQuery( "#wpr5_categories" ).change(function() {
			var theval = jQuery(this).val();
			if(theval != "" && theval != undefined) {
				jQuery( "li#step2" ).addClass("stepactive");
			} else {
				jQuery( "li#step2" ).removeClass("stepactive");
			}
		});	
		
		jQuery( "#wpr5_interval" ).change(function() {
			var theval = jQuery(this).val();
			if(theval != "" && theval != undefined) {
				jQuery( "li#step3" ).addClass("stepactive");
			} else {
				jQuery( "li#step3" ).removeClass("stepactive");
			}
		});			
		
		jQuery('#catquickadd').change(function() {

			//var selectedtext = jQuery(this).find('option:selected').text();
			var selectedtext = "id:" + jQuery(this).find('option:selected').val();
	
			if(selectedtext != "" && selectedtext != undefined) {

				var cats = jQuery("#wpr5_categories").val();
				
				if(cats != "") {
					jQuery("#wpr5_categories").val(cats + "\n" + selectedtext);
				} else {
					jQuery("#wpr5_categories").val(cats + selectedtext);
				}	
				jQuery( "li#step2" ).addClass("stepactive");
			}
		});			

		jQuery('#wpr5_source').change(function() {

			var selected = jQuery(this).find('option:selected').val();
			var selectedtext = jQuery(this).find('option:selected').text();
			
			var cur_number = parseInt(jQuery("#wpr5_template_counter").val());
			
			if(selected != "" && selected != undefined) {
			
				jQuery( "#cmsc-tabbar li.tabby-" + selected ).show();

				jQuery( "li#step4" ).addClass("stepactive");
			
				var clone = jQuery("#sample_box").clone();
				clone.attr("id", "template_main_" + cur_number);
				clone.find(".main_module").text(selectedtext + " (main source)");
				clone.find(".wpr5_template_content_field").val("{" + selected + "}");
				
				clone.find(".wpr5_template_content_field").attr("name","template_content_" + cur_number);
				clone.find(".wpr5_template_title_field").attr("name","template_title_" + cur_number);
				
				clone.find(".wpr5_template_name").text("Template " + cur_number);
				clone.insertAfter("#sample_box");	

				jQuery("#template_main_" + cur_number).slideDown();
				
				cur_number = cur_number + 1;
				jQuery("#wpr5_template_counter").val(cur_number);
			
			}
		});	
		
		jQuery('#whichtrans').change(function() {
			var selected = jQuery(this).val();

			if(selected == "deepl") {
				jQuery( "#deepl_box" ).show();
				jQuery( "#yandex_box" ).hide();
			} else if(selected == "yandex") {
				jQuery( "#yandex_box" ).show();
				jQuery( "#deepl_box" ).hide();
			} else {
				jQuery( "#yandex_box" ).hide();
				jQuery( "#deepl_box" ).hide();			
			}
		});		
		
		jQuery('#add-all-sources').click(function(e) {
			e.preventDefault();	
			jQuery("#wpr5_source option").each(function(i) {
			
				var selected = jQuery(this).val();
				var selectedtext = jQuery(this).text();
				var cur_number = parseInt(jQuery("#wpr5_template_counter").val());
				
				if(selected != "" && selected != undefined) {
				
					jQuery( "#cmsc-tabbar li.tabby-" + selected ).show();

					jQuery( "li#step4" ).addClass("stepactive");
				
					var clone = jQuery("#sample_box").clone();
					clone.attr("id", "template_main_" + cur_number);
					clone.find(".main_module").text(selectedtext + " (main source)");
					clone.find(".wpr5_template_content_field").val("{" + selected + "}");
					
					clone.find(".wpr5_template_content_field").attr("name","template_content_" + cur_number);
					clone.find(".wpr5_template_title_field").attr("name","template_title_" + cur_number);
					
					clone.find(".wpr5_template_name").text("Template " + cur_number);
					clone.insertAfter("#sample_box");	

					jQuery("#template_main_" + cur_number).slideDown();
					
					cur_number = cur_number + 1;
					jQuery("#wpr5_template_counter").val(cur_number);
				
				}
			});		
			jQuery('#add-all-sources-box').remove();
		});	
		
		jQuery('.add_module_source_button').live("click", function(e) {
			e.preventDefault();	

			var par = jQuery(this).parent().parent();
			var par2 = jQuery(this).parent().parent().parent();
			
			var mod = par.find('.add_module_source option:selected').val();
			var modname = par.find('.add_module_source option:selected').text();
			
			var setting_add_module_location = par.find('.add_module_location option:selected').val();
			var setting_add_module_keyword = par.find('.add_module_keyword option:selected').val();
			var setting_add_module_num = par.find('.add_module_num option:selected').val();

			var addtotemplate = "{" + mod + "|" + setting_add_module_num + "|" + setting_add_module_keyword + "}";
			var template_content = par2.find('.wpr5_template_content_field').val();			
			
			if(setting_add_module_keyword == "title") {
				var ttkwtxt = "uses title as keyword";
			} else {
			
			}
			
			if(setting_add_module_num < 100 && setting_add_module_keyword == "title") {
				var addmtxt = " (uses title as keyword, " + setting_add_module_num + "%)";
			} else if(setting_add_module_num < 100) {	
				var addmtxt = " (" + setting_add_module_num + "%)";	
			} else if(setting_add_module_keyword == "title") {	
				var addmtxt = " (uses title as keyword)";					
			} else {
				var addmtxt = "";
			}			
			
			if(setting_add_module_location == "before") {
				par2.find("div.wpr5_templates_added").prepend('<div class="wpr5_modulelist">' + modname + addmtxt + '</div>');
				addtotemplate = addtotemplate + "" + template_content;				
			} else {
				par2.find("div.wpr5_templates_added").append('<div class="wpr5_modulelist">' + modname + addmtxt + '</div>');	
				addtotemplate = template_content + "" + addtotemplate;			
			}
			
			jQuery( "#cmsc-tabbar li.tabby-" + mod ).show();

			par2.find('.wpr5_template_content_field').val(addtotemplate);

		});	
		jQuery('.delete_template_link').live("click", function(e) {		
			e.preventDefault();
			
			jQuery(this).parent().remove();

			if(jQuery('.wpr5_template_box:visible').length < 1) {
				jQuery( "li#step4" ).removeClass("stepactive");
			}			
		});	

		jQuery('#wpr_cf_add').live("click", function(e) {
			e.preventDefault();
			var cfnum = parseInt(jQuery("#cf_num").val()) + 1;
			jQuery("#cf_num").val(cfnum);
			jQuery("div.cfcontainers").append('<div class="cfcontainer"><?php _e('Name', 'wprobot'); ?>: <input type="text" value="" name="cf_name' + cfnum + '"> <?php _e('Value', 'wprobot'); ?>: <input type="text" value="" name="cf_value' + cfnum + '"></div>');		
		});

		jQuery('#wpr_cf_woocommerce_setup').live("click", function(e) {
			e.preventDefault();

			var cfnum = parseInt(jQuery("#cf_num").val()) + 1;
			jQuery("div.cfcontainers").append('<div class="cfcontainer"><?php _e('Name', 'wprobot'); ?>: <input type="text" value="_product_url" name="cf_name' + cfnum + '"> <?php _e('Value', 'wprobot'); ?>: <input type="text" value="{cf_url}" name="cf_value' + cfnum + '"></div>');		
			
			// price: also _sale_price _regular_price
			cfnum = cfnum + 1;			
			jQuery("div.cfcontainers").append('<div class="cfcontainer"><?php _e('Name', 'wprobot'); ?>: <input type="text" value="_price" name="cf_name' + cfnum + '"> <?php _e('Value', 'wprobot'); ?>: <input type="text" value="{cf_price}" name="cf_value' + cfnum + '"></div>');		
		
			cfnum = cfnum + 1;			
			jQuery("div.cfcontainers").append('<div class="cfcontainer"><?php _e('Name', 'wprobot'); ?>: <input type="text" value="_button_text" name="cf_name' + cfnum + '"> <?php _e('Value', 'wprobot'); ?>: <input type="text" value="<?php _e('Order Now', 'wprobot'); ?>" name="cf_value' + cfnum + '"></div>');		
						
			jQuery("#cf_num").val(cfnum);		
		});		
		
		
	});
	</script>

<?php		
}

// Page Body
function wpr5_create_campaign_page() {
	global $generalarray, $wpr5_source_infos, $optionsexpl;
	
	wpr5_check_license_key();
	
	$options = wpr5_get_options();
	
	$campaigns = get_option("wpr5_campaigns");
	
	if(is_array($campaigns)) {
		$last = array_slice($campaigns, -1, 1, true);
	} else {
		$last = 0;
	}
	
	if(empty($last)) {
		$name = "Campaign 1";
	} else {
		$key = key($last) + 2;
		$name = "Campaign $key";
	}

	$wpposttypes = get_post_types('','names');
	$wpusers = get_users('number=500');

	$sources_empty = 1;
	$sourceselect = '<select class="wpr5_optionselect" name="wpr5_source" id="wpr5_source"><option value="">' . __('(choose one)', 'wprobot') . '</option>';		
	$modulearray = $options["options"]; 
	foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 && $moduledata["display"] != "no") {
	
		if((!empty($moduledata["options"]["wai_rewrite_pw"]["name"]) && empty($moduledata["options"]["wai_rewrite_pw"]["value"])) || (!empty($moduledata["options"]["tbs_pw"]["name"]) && empty($moduledata["options"]["tbs_pw"]["value"])) || (!empty($moduledata["options"]["sc_pw"]["name"]) && empty($moduledata["options"]["sc_pw"]["value"])) || (!empty($moduledata["options"]["api_key"]["name"]) && empty($moduledata["options"]["api_key"]["value"])) || (!empty($moduledata["options"]["pw"]["name"]) && empty($moduledata["options"]["pw"]["value"])) || (!empty($moduledata["options"]["appid"]["name"]) && empty($moduledata["options"]["appid"]["value"])) || !empty($moduledata["options"]["public_key"]["name"]) && empty($moduledata["options"]["public_key"]["value"])) {
		} else {
			$sources_empty = 0;
			$sourceselect .= '<option value="' . $module . '">' . $moduledata["name"] . '</option>';
			$sourceselect2 .= '<option value="' . $module . '">' . $moduledata["name"] . '</option>';		
		}
	} }
	
	if($sources_empty == 1) {
		echo '<div class="updated error"><p>'.__('<strong>Important: </strong> You have not activated any content sources yet. Before you can create a campaign you need to go to the <a href="admin.php?page=wpr5-options">Options page</a> and configure the content sources you intend to use.', "wprobot").'</p></div>';		
	}

	if(empty($_GET["edit"])) {
		if(empty($_POST["cf_num"])) {$_POST["cf_num"] = 1;}
		if(($_POST['type2'] || $_POST['ctype'] == "rss") && empty($_POST['wpr_add'])) {
			$_POST['type'] = "rss";
			$_POST["ctype"] = "rss";
			$_POST["template_title_1"] = "{title}";
			$_POST["template_content_1"] = "{rss}";
			$_POST["wpr5_template_counter"] = "2";
		} else {
			$_POST['type'] = "keyword";	
			$_POST["ctype"] = "keyword";
		}
		
		if($_POST['type1']) {
			$_POST['type'] = "keyword";	
			$_POST["ctype"] = "keyword";
			$_POST["template_title_1"] = "";
			$_POST["template_content_1"] = "";
			$_POST["wpr5_template_counter"] = "1";			
		}
	}
	
	if(!$_POST && !empty($_GET["edit"]) && isset($_GET["cid"])) {	
		if (isset($_GET['wpr5_edit_nonce']) && wp_verify_nonce($_GET['wpr5_edit_nonce'], 'wpr5_edit')) {
			$cids = $_GET["cid"];

			$campaign = $campaigns[$cids];
			
			if($_GET["copy"] != 1) {
				$editing = 1;
			}
			
			$_POST['name'] = $campaign["main"]["name"];
			if($_GET["copy"] == 1) {$_POST['name'] .= " Copy";}
			$_POST['postnum'] = $campaign["main"]["num"];
			
			$_POST['interval'] = $campaign["main"]["interval"];
			$_POST['period'] = $campaign["main"]["period"];
			$_POST['perc'] = $campaign["main"]["perc"];
			
			$_POST['wpr_poststatus'] = $campaign["main"]["post_status"];
			$_POST['wp_posttype'] = $campaign["main"]["post_type"];
			$_POST['bc_user'] = $campaign["main"]["author"];
			
			$_POST['exclude_keywords'] = implode("\n", $campaign["settings"]["exclude"]); 		
			$_POST['require_keywords'] = implode("\n", $campaign["settings"]["require"]); 		
			$_POST['replace_keywords'] = implode("\n", $campaign["settings"]["replace"]); 		
			
			$_POST['wpr5_template_counter'] = count($campaign["templates"]) + 1;
			
			$_POST['trans1'] = $campaign["settings"]["translate"]["from"];
			$_POST['trans2'] = $campaign["settings"]["translate"]["to1"];
			$_POST['trans3'] = $campaign["settings"]["translate"]["to2"];
			$_POST['trans4'] = $campaign["settings"]["translate"]["to3"];
			$_POST['trans1_dl'] = $campaign["settings"]["translate"]["from"];
			$_POST['trans2_dl'] = $campaign["settings"]["translate"]["to1"];
			$_POST['trans3_dl'] = $campaign["settings"]["translate"]["to2"];
			$_POST['trans4_dl'] = $campaign["settings"]["translate"]["to3"];	
			
			$_POST['tr_yandex_key'] = $campaign["settings"]["translate"]["key"];
			$_POST['tr_deepl_key'] = $campaign["settings"]["translate"]["deepl_key"];
			
			$_POST['whichtrans'] = $campaign["settings"]["translate"]["which"];
			
			$_POST['rewriter'] = $campaign["settings"]["rewrite"];
		
			$_POST['strip_links'] = $campaign["settings"]["strip_links"];
		
			$cfcount = 1;
			if(!empty($campaign["settings"]["cfs"])) {
				$cfs = str_replace(array("[customfields:", "]"), "", $campaign["settings"]["cfs"]);
				$cfs = explode(";", $cfs);
		
				if(is_array($cfs)) {
					foreach($cfs as $cf) {
						$cf = explode("|", $cf);					
						if(!empty($cf[0]) && !empty($cf[1])) {
							$_POST["cf_name$cfcount"] = $cf[0];
							$_POST["cf_value$cfcount"] = $cf[1];
							$cfcount++;
						}
					}
				}
			}
			$_POST["cf_num"] = $cfcount;

			$i = 1;
			foreach($campaign["templates"] as $tmpl) {
				$_POST["template_title_".$i] = $tmpl["title"];
				$_POST["template_content_".$i] = $tmpl["content"];
				$i++;
			}

			if(!empty($campaign["feeds"]) && is_array($campaign["feeds"])) {
				$_POST["ctype"] = "rss";
				foreach($campaign["feeds"] as $fdd) {
					$catl = "";
					if(is_array($fdd["category"])) {foreach($fdd["category"] as $cat) {$catl .= "id:".$cat["id"] . ",";}}
					if(!empty($catl)) {$_POST['categories'] .= rtrim($catl, ",") . "\n";}
					$_POST['keywords'] .= $fdd["name"] . "\n";
					$_POST['feeds'] .= $fdd["feed"] . "\n";
				}			
			} else {
				$_POST["ctype"] = "keyword";
				foreach($campaign["keywords"] as $kws) {
					$catl = "";
					if(is_array($kws["category"])) {foreach($kws["category"] as $cat) {$catl .= "id:".$cat["id"] . ",";}}
					if(!empty($catl)) {$_POST['categories'] .= rtrim($catl, ",") . "\n";}
					$_POST['keywords'] .= $kws["name"] . "\n";
				}			
			}
			
			if(!empty($campaign["override"])) {
				foreach($campaign["override"] as $mod => $set) {
					$_POST[$mod."_overrider"] = 1;
					$_POST[$mod . "_template"] = $set["template"];
					foreach($set as $sname => $sval) {
						$_POST[$mod . "_" . $sname] = $sval;
					}
				}
			}
		}
	}	
	
	
	if(isset($_POST['type2']) || $_POST['ctype'] == "rss") {
		$sourceselect .= '<option value="rss">RSS Feeds</option>';
		$sourceselect2 .= '<option value="rss">RSS Feeds</option>';
	}
	$sourceselect .= "</select>";		

	if(empty($_POST['tr_yandex_key'])) {
		$_POST['tr_yandex_key'] = get_option("wpr5_yandex_key");
	}
	
	if(empty($_POST['name'])) {$_POST['name'] = $name;}

	if($_POST['wpr_add']) {

		$stop = 0;
		
		if((empty($_POST['keywords']) && empty($_POST['feeds'])) || empty($_POST['categories']) || empty($_POST['interval']) || empty($_POST['name']) || empty($_POST['wpr5_template_counter']) || $_POST['wpr5_template_counter'] < 2) {
			echo '<div class="updated error"><p>'.__("Missing data: Please enter a keyword, category, name and posting inverval for your new campaign.", "wprobot").'</p></div>';		
			$stop = 1;
		}
		
		if($stop === 0) {
		
			$edit = sanitize_text_field($_POST['wpr5_editing_campaign']);

			if(isset($edit)) {
				$editing = 1;
				$old_counts = $campaigns[$edit]["keywords"];
			}
		
			$c_name = sanitize_text_field($_POST['name']);
			
			$c_postnum = sanitize_text_field($_POST['postnum']);
			$c_interval = sanitize_text_field($_POST['interval']);
			$c_period = sanitize_text_field($_POST['period']);
			$c_perc = sanitize_text_field($_POST['perc']);
			
			$c_post_status = sanitize_text_field($_POST['wpr_poststatus']);
			$c_post_type = sanitize_text_field($_POST['wp_posttype']);
			$c_post_author = sanitize_text_field($_POST['bc_user']);

			$c_feeds = esc_textarea($_POST['feeds']);
			$c_feeds = stripslashes($c_feeds);
			$c_feeds = str_replace("\r", "", $c_feeds);
			$c_feeds = explode("\n", $c_feeds); 				
		
			$c_categories = esc_textarea($_POST['categories']);			
			$c_categories = stripslashes($c_categories);
			$c_categories = str_replace("\r", "", $c_categories);
			$c_categories = explode("\n", $c_categories);

			// MISSING: BETTER GET TAXONOMY FOR CUSTOM POST TYPES
			// possible to do: display TAX in form via AJAX REQUEST... $taxonomy_objects = get_object_taxonomies( $c_post_type, 'names' );			
			if($c_post_type == "product") {
				$cat_tax = "product_cat";
			} elseif($c_post_type == "download") {
				$cat_tax = "download_category";
			} else {
				$cat_tax = "category";
			}

			$i = 0;$cats = array();
			foreach($c_categories as $clid => $categories) {
				if(empty($categories)) {unset($c_categories[$clid]);continue;}
			
				$categories = explode(",",$categories); // multiple category support

				$catx = array();
				foreach($categories as $category) {
				
					if(stripos($category, "id:") !== false) {
						$category_ID = trim(str_replace("id:", "", $category));
						$cid = get_term_by( "id", $category_ID, $cat_tax, ARRAY_A );	
						$category = $cid["name"];						
						//echo " DDDD $category ffFF $category_ID";				
					
					} else {
						$category = str_replace("&", "&amp;", $category);
						$category = ucwords($category);						
					}
					
					if(empty($category_ID)) {
						$cid = get_term_by( "name", $category, $cat_tax, ARRAY_A );		
						if(is_array($cid)) {
							$category_ID = $cid["term_id"];
						} else {
							$category_ID = wp_insert_category( array(
							  'cat_name' => $category,
							  'category_description' => "",
							  'category_nicename' => "",
							  'category_parent' => $parent,
							  'taxonomy' => $cat_tax ) );							  
						}					
					}
					$catx[] = array("id" => $category_ID, "name" => $category);
				}
				$cats[$i] = $catx;
				$i++;
			}

			if(count($c_categories) == 1) {
				$maincat = $c_categories[0];
			} else {
				$maincat = "multi";
			}			
			
			$c_keywords = esc_textarea($_POST['keywords']);
			$c_keywords = stripslashes($c_keywords);
			$c_keywords = str_replace("\r", "", $c_keywords);
			$c_keywords = explode("\n", $c_keywords); 	

			if(!empty($c_feeds) && is_array($c_feeds) && !empty($_POST['feeds']) && !empty($c_feeds[0])) {	
				$i = 0;$feeds = array();
				foreach($c_feeds as $feed) {
					if(!empty($feed)) {
						$kwcats = $cats[$i];					
						if(!empty($old_counts[$i]["count"]) && is_array($old_counts[$i]["count"])) {
							$feeds[$i] = array("feed" => $feed, "name" => $c_keywords[$i], "count" => $old_counts[$i]["count"], "category" => $kwcats);
						} else {
							$feeds[$i] = array("feed" => $feed, "name" => $c_keywords[$i], "count" => array(), "category" => $kwcats);
						}						
					}
					$i++;
				}
			} else {	
				$i = 0;$kws = array();
				foreach($c_keywords as $keyword) {
					if(!empty($keyword)) {
						$kwcats = $cats[$i];				
						if(!empty($old_counts[$i]["count"]) && is_array($old_counts[$i]["count"])) {
							$kws[$i] = array("name" => $keyword, "count" => $old_counts[$i]["count"], "category" => $kwcats);			
						} else {
							$kws[$i] = array("name" => $keyword, "count" => array(), "category" => $kwcats);						
						}
					}
					$i++;
				}			
			}

			$c_exclude = esc_textarea($_POST['exclude_keywords']);
			$c_exclude = stripslashes($c_exclude);
			$c_exclude = str_replace("\r", "", $c_exclude);
			$c_exclude = explode("\n", $c_exclude); 			

			$c_require = esc_textarea($_POST['require_keywords']);
			$c_require = stripslashes($c_require);
			$c_require = str_replace("\r", "", $c_require);
			$c_require = explode("\n", $c_require); 			
			
			$c_replace = esc_textarea($_POST['replace_keywords']);
			$c_replace = stripslashes($c_replace);
			$c_replace = str_replace("\r", "", $c_replace);
			$c_replace = explode("\n", $c_replace); 	

			$whichtrans = sanitize_text_field($_POST['whichtrans']);			
			
			$key = sanitize_text_field($_POST['tr_yandex_key']);
			$deepl_key = sanitize_text_field($_POST['tr_deepl_key']);			
			
			if($whichtrans == "deepl") {
				$trans1 = sanitize_text_field($_POST['trans1_dl']);
				$trans2 = sanitize_text_field($_POST['trans2_dl']);
				$trans3 = sanitize_text_field($_POST['trans3_dl']);
				$trans4 = sanitize_text_field($_POST['trans4_dl']);						
			} else {
				$trans1 = sanitize_text_field($_POST['trans1']);
				$trans2 = sanitize_text_field($_POST['trans2']);
				$trans3 = sanitize_text_field($_POST['trans3']);
				$trans4 = sanitize_text_field($_POST['trans4']);
			}

			$strip_links = sanitize_text_field($_POST['strip_links']);
			
			if(!empty($key)) {
				update_option("wpr5_yandex_key", $key);
			}
			
			if(!empty($trans1) && $trans1 != "no" && !empty($trans2) && $trans2 != "no") {
				$trans_settings = array("which" => $whichtrans, "from" => $trans1, "to1" => $trans2, "to2" => $trans3, "to3" => $trans4, "key" => $key, "deepl_key" => $deepl_key);
			} else {
				$trans_settings = array();
			}
			
			$rw = sanitize_text_field($_POST['rewriter']);
			
			$c_template_count = (int) sanitize_text_field($_POST['wpr5_template_counter']);
			$templates = array();
			
			for ($i = 1; $i <= $c_template_count; $i++) {
				if(!empty($_POST["template_content_".$i])) {
					$tt = sanitize_text_field($_POST["template_title_".$i]);
					$tc = sanitize_text_field($_POST["template_content_".$i]);
				
					$templates[] = array("title" => $tt, "content" => $tc);
				}
			}
		
			// OPTIONS OVERRIDES
			$mod_options = array();
			$modulearray = $options["options"];		
			$num = 1; if(is_array($modulearray)) { foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1) { //  && $moduledata["display"] != "no"
				if($_POST[$module.'_overrider'] == 1) {
					$mod_options[$module]["template"] = $_POST[$module."_template"];
					foreach($moduledata["options"] as $option => $data) {
						$mod_options[$module][$option] = $_POST[$module."_".$option];
					}
				}
			} } }

			// CUSTOM FIELDS [customfields:name|{tag};name2|{tag2}]
			$cfnum = $_POST["cf_num"];$cfs = "";
			for ($i = 1; $i <= $cfnum; $i++) {
				if(!empty($_POST["cf_name$i"]) && !empty($_POST["cf_value$i"])) {
					$cfs .= $_POST["cf_name$i"] . "|" . $_POST["cf_value$i"] . ";";
				}
			}
			if(!empty($cfs)) {$cfsave = '[customfields:'.$cfs.']';} else {$cfsave = '';}

			$campaign = array();
			$campaign["count"] = 0;
			$campaign["main"] = array("name" => $c_name, "num" => $c_postnum, "interval" => $c_interval, "perc" => $c_perc, "period" => $c_period, "post_status" => $c_post_status, "post_type" => $c_post_type, "author" => $c_post_author, "category" => $maincat);
			$campaign["templates"] = $templates;	
			if(!empty($feeds) && is_array($feeds)) {	
				$campaign["feeds"] = $feeds;			
			} else {
				$campaign["keywords"] = $kws;			
			}	
			
			if(!empty($c_replace) || !empty($c_exclude) ||!empty($c_require)) {$campaign["settings"] = array("strip_links" => $strip_links, "replace" => $c_replace, "exclude" => $c_exclude, "require" => $c_require);}
			if(!empty($mod_options)) {$campaign["override"] = $mod_options;}
			if(!empty($trans_settings)) {$campaign["settings"]["translate"] = $trans_settings;}
			if(!empty($cfsave)) {$campaign["settings"]["cfs"] = $cfsave;}
			if(!empty($rw)) {$campaign["settings"]["rewrite"] = $rw;}
			
			if(isset($edit) && is_numeric($edit)) {
				$campaigns[$edit] = $campaign;			
			} else {
				$campaigns[] = $campaign;			
			}

			update_option("wpr5_campaigns", $campaigns);
			
			echo '<div class="updated"><p>'.__('<strong>Your campaign has been created!</strong> You can go to the <a href="admin.php?page=wpr5-automation">Campaigns page</a> to view details and create manual posts for it or create another campaign below.', "wprobot").'</p></div>';		
			
			//echo "<pre>";print_r($campaign);echo "</pre>";
		}
	}	
	
?>
<div class="wrap">
	<div id="wprobot" class="icon32"></div>
	<h1 class="nav-tab-wrapper">
	<span style="display:inline-block;float:left;margin-top: 6px;">WP Robot&nbsp;&nbsp;&nbsp;</span>
	<a href="?page=wpr5-automation" class="nav-tab"><?php _e('Campaigns', 'wprobot'); ?></a>
	<a href="?page=wpr5-create-campaign" class="nav-tab nav-tab-active"><?php _e('Create Campaign', 'wprobot'); ?></a>
	</h1>	

	<div style="height: 10px;"></div>

<form id="wpr_new" method="post">
<div style="width:21%;float:right;">

	<div id="finish_box">
	
		<div id="step_list">

			<ol>
				<li id="step1"><?php _e('Enter your keywords', 'cmsc'); ?></li>
				<li id="step2"><?php _e('Enter a category', 'cmsc'); ?></li>
				<li id="step3"><?php _e('Set the posting frequency', 'cmsc'); ?></li>
				<li id="step4"><?php _e('Add post templates', 'cmsc'); ?></li>
			</ol>
		
		</div>

		<div id="create_button">
			<?php if($editing == 1) { ?>
			<input type="submit" value="<?php _e('Edit Campaign', 'wprobot'); ?>" name="wpr_add" class="button-primary">	
			<?php } else { ?>
			<input type="submit" value="<?php _e('Create Campaign', 'wprobot'); ?>" name="wpr_add" class="button-primary">				
			<?php } ?>
		</div>
		
	</div>
	
	<div id="docs_box" style="margin-top: 250px;padding: 15px;color: #999;">
		<p><?php _e('To create your autoposting campaign only the 4 steps listed above are required. All other settings on the page are optional and can be changed to customize your campaign.', 'cmsc'); ?></p>
		<p><?php _e('For more detailed instructions <a href="http://wprobot.net/wp-robot-5-documentation/#create" target="_blank">take a look at this article in our online documentation</a>.', 'cmsc'); ?></p>
	</div>

</div>

<div style="width:76%;position:relative;">

<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e('Main Settings', 'wprobot'); ?></h3>

<?php if($editing == 1) { ?>
<input type="hidden" id="wpr5_editing_campaign" name="wpr5_editing_campaign" value="<?php echo $_GET["cid"];?>">
<?php } ?>

<p class="wpr5ac" <?php if($options["options"]["rss"]["disabled"] == 1 || empty($options["options"]["rss"])) {echo 'style="display:none;"';} ?>>	
	<b><?php _e("Campaign Type:","wprobot") ?></b> 
	<input type="hidden" id="ctype" name="ctype" value="<?php echo $_POST["ctype"];?>">
	<input class="<?php if($_POST['ctype'] == "keyword") {echo "button-primary";} else {echo "button";} ?>" type="submit" name="type1" value="<?php _e("Keyword Campaign","wprobot") ?>" /> 
	<input class="<?php if($_POST['ctype'] == "rss") {echo "button-primary";} else {echo "button";} ?>" type="submit" name="type2" value="<?php _e("RSS Campaign","wprobot") ?>" />
</p>

<p class="wpr5ac"><b><?php _e('Campaign Name', 'wprobot'); ?></b>: <input type="text" value="<?php echo $_POST["name"]; ?>" name="name"></p>

<p class="wpr5ac"><b><?php _e('Schedule', 'wprobot'); ?>:</b> <?php _e('Create', 'wprobot'); ?> 
<select name="postnum" style="width: 50px;">
	<option <?php if($_POST["postnum"] == "1") {echo "selected";} ?> value="1">1</option>
	<option <?php if($_POST["postnum"] == "2") {echo "selected";} ?> value="2">2</option>
	<option <?php if($_POST["postnum"] == "3") {echo "selected";} ?> value="3">3</option>
	<option <?php if($_POST["postnum"] == "4") {echo "selected";} ?> value="4">4</option>
	<option <?php if($_POST["postnum"] == "5") {echo "selected";} ?> value="5">5</option>
	<option <?php if($_POST["postnum"] == "6") {echo "selected";} ?> value="6">6</option>
	<option <?php if($_POST["postnum"] == "7") {echo "selected";} ?> value="7">7</option>
	<option <?php if($_POST["postnum"] == "8") {echo "selected";} ?> value="8">8</option>
	<option <?php if($_POST["postnum"] == "9") {echo "selected";} ?> value="9">9</option>
	<option <?php if($_POST["postnum"] == "10") {echo "selected";} ?> value="10">10</option>
	<option <?php if($_POST["postnum"] == "20") {echo "selected";} ?> value="20">20</option>
	<option <?php if($_POST["postnum"] == "30") {echo "selected";} ?> value="30">30</option>
	<option <?php if($_POST["postnum"] == "0") {echo "selected";} ?> value="0"><?php _e('0 (no autoposting)', 'wprobot'); ?></option>
</select>	

<?php _e('new posts every', 'wprobot'); ?> <input type="text" value="<?php echo $_POST["interval"]; ?>" id="wpr5_interval" name="interval" size="5">
<select name="period">
	<option <?php if($_POST["period"] == "hours") {echo "selected";} ?> value="hours"><?php _e('Hours', 'wprobot'); ?></option>
	<option <?php if($_POST["period"] == "days") {echo "selected";} ?> value="days"><?php _e('Days', 'wprobot'); ?></option>
	<option <?php if($_POST["period"] == "minutes") {echo "selected";} ?> value="minutes"><?php _e('Minutes', 'wprobot'); ?></option>	
</select>
 <?php _e('in', 'wprobot'); ?> <input type="text" value="<?php if(empty($_POST["perc"])) {$_POST["perc"] = 100;}echo $_POST["perc"]; ?>" id="perc" name="perc" size="4"><?php _e('% of the time.', 'wprobot'); ?>
	
</p>

<p class="wpr5ac">
	<b><?php _e('Post Status', 'wprobot'); ?>:</b>
	<select id="wpr_poststatus" name="wpr_poststatus">			
		<option <?php if($_POST["wpr_poststatus"] == "publish") {echo "selected";} ?> value="publish"><?php _e('Published', 'wprobot'); ?></option>
		<option <?php if($_POST["wpr_poststatus"] == "draft") {echo "selected";} ?> value="draft"><?php _e('Draft', 'wprobot'); ?></option>
	</select>
	
	<span style="display:inline-block;margin-left:50px;">
	<b><?php _e('Post Type', 'wprobot'); ?>:</b>
	
	<?php if(isset($wpposttypes)) { ?>
	<select id="wp_posttype" name="wp_posttype">
		<?php foreach($wpposttypes as $wpposttype) { ?>
			<option <?php if($_POST["wp_posttype"] == $wpposttype) {echo "selected";} ?> value="<?php echo $wpposttype; ?>"><?php echo ucwords($wpposttype); ?></option>
		<?php } ?>
	</select>										
	<?php } else { ?>	
		<input type="text" value="<?php if(!empty($_POST["wp_posttype"])) {echo esc_attr($_POST["wp_posttype"]);} else {echo "post";} ?>" name="wp_posttype" id="wp_posttype">											
	<?php } ?>			
	</span>	
	
	<span style="display:inline-block;margin-left:50px;">
	<b><?php _e('Author', 'wprobot'); ?>:</b>	
	<select id="bc_user" name="bc_user">
		<?php foreach($wpusers as $user) { ?>
			<option <?php if($_POST["bc_user"] == $user->ID) {echo "selected";} ?> value="<?php echo $user->ID; ?>"><?php echo $user->user_login; ?></option>
		<?php } ?>
	</select>			
	</span>	
</p>

<?php if($_POST['ctype'] == "rss") { ?>
<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('RSS Feeds', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="5" id="wpr5_feeds" name="feeds"><?php echo esc_textarea( $_POST["feeds"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;"><?php _e('Enter the full URL of RSS feeds you want to post to your site. If you enter multiple one will be selected randomly each time the campaign is run.<br/>Example:<br/>https://www.engadget.com/rss-full.xml', 'wprobot'); ?></span>
</p>
<?php } ?>

<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('Keywords', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="5" id="wpr5_keywords" name="keywords"><?php echo esc_textarea( $_POST["keywords"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;"><?php _e('Enter search words you want to post content for.<br/>If you enter multiple one will be selected randomly each time the campaign is run.', 'wprobot'); ?></span>
</p>

<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('Categories', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="5" id="wpr5_categories" name="categories"><?php echo esc_textarea( $_POST["categories"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;">
		<?php _e('Enter a single category or one category on each line per keyword.<br/>Categories not existing yet will get created.', 'wprobot'); ?>
		<span style="display:inline-block;margin-top: 15px;"><strong>+</strong> <?php _e('Add existing: ', 'wprobot'); ?><?php wp_dropdown_categories( array("id" => "catquickadd", "hide_empty" => 0) ); ?>
	</span>
</p>

<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e('Templates', 'wprobot'); ?></h3>

<input type="hidden" id="wpr5_template_counter" name="wpr5_template_counter" value="<?php if(empty($_POST["wpr5_template_counter"])) {echo "1";} else {echo $_POST["wpr5_template_counter"];}?>">

<div id="wpr5_template_main_container">

<?php
	// CREATOR OF HTML
	if(!empty($_POST["wpr5_template_counter"]) && is_numeric($_POST["wpr5_template_counter"])) {

		for ($i = 1; $i <= $_POST["wpr5_template_counter"]; $i++) {
		
			$tmodules = explode("}{", $_POST["template_content_".$i]);
			
			if(!empty($_POST["template_content_".$i])) {

			?>
			
				<div class="wpr5_template_box"  id="template_main_<?php echo $i; ?>">
					<input type="hidden" class="wpr5_template_content_field" name="template_content_<?php echo $i; ?>" value="<?php echo $_POST["template_content_".$i]; ?>">
					
					<a class="delete_template_link" href="#">x</a>
					
					<div class="wpr5_template_name">Template <?php echo $i; ?></div>
					
					<div class="wpr5_template_title"><?php _e('Title:', 'wprobot'); ?> <input type="text" class="wpr5_template_title_field" name="template_title_<?php echo $i; ?>" value="<?php echo $_POST["template_title_".$i]; ?>"></div>
							
					<div class="wpr5_templates_added">							
					
						<?php foreach($tmodules as $tm) {
							$tm = str_replace("{", "", $tm);
							$tm = explode("|", $tm);
							$themod = rtrim($tm[0], "}");
							$loaded_modules[] = $themod;
							if(empty($tm[1])) {$titlem = " (main source)";$titlec = " main_module";} else {$titlem = "";$titlec = "";}
							echo '<div class="wpr5_modulelist'.$titlec.'">' . $wpr5_source_infos["sources"][$themod]["name"] . $titlem.'</div>';
						} ?>
						
					</div>

					<div class="wpr5_add_source_form">
						<div>
							<?php _e('Add another <b>source</b>: ', 'wprobot');?> <select class="add_module_source" name="add_module_source"><?php echo $sourceselect2; ?></select>
							<input type="submit" value="Add" name="add_module_source_button" class="button add_module_source_button">
						</div>
						
						<div class="subform">
							<?php _e('Location: ', 'wprobot');?>
							<select class="add_module_location" name="add_module_location">
								<option selected value="after"><?php _e('After main source', 'cmsc'); ?></option>				
								<option value="before"><?php _e('Before main source', 'cmsc'); ?></option>
							</select><br/>			

							<?php _e('Keyword: ', 'wprobot');?>
							<select class="add_module_keyword" name="add_module_keyword" style="width: 180px;">
								<option selected value="campaign"><?php _e('Use campaign keyword.', 'wprobot');?></option>
								<option value="title"><?php _e('Use title of main content source.', 'wprobot');?></option>
							</select><br/>							
							
							<?php _e('Probability: ', 'wprobot');?>
							<select class="add_module_num" name="add_module_num" style="width: 80px;">
								<option selected value="100">100%</option>
								<option value="90">90%</option>
								<option value="80">80%</option>
								<option value="70">70%</option>
								<option value="60">60%</option>
								<option value="50">50%</option>
								<option value="40">40%</option>
								<option value="30">30%</option>
								<option value="20">20%</option>
								<option value="10">10%</option>
							</select>				
						</div>					
					</div>		
					
				</div>			
			
			<?php			
			}	
		}	
	}
?>

	<div class="wpr5_template_box"  id="sample_box" style="display:none;">
		<input type="hidden" class="wpr5_template_content_field" name="template_content_x" value="{amazon}{youtube|50|front|mainkw}">
		
		<a class="delete_template_link" href="#">x</a>
		
		<div class="wpr5_template_name">Template 1</div>
		
		<div class="wpr5_template_title"><?php _e('Title:', 'wprobot'); ?> <input type="text" class="wpr5_template_title_field" name="template_title_x" value="{title}"></div>
				
		<div class="wpr5_templates_added">
			
			<div class="wpr5_modulelist main_module">Commission Junction</div>
			
		</div>

		<div class="wpr5_add_source_form">
			<div>
				<?php _e('Add another <b>source</b>: ', 'wprobot');?> <select class="add_module_source" name="add_module_source"><?php echo $sourceselect2; ?></select>
				<input type="submit" value="Add" name="add_module_source_button" class="button add_module_source_button">
			</div>
			
			<div class="subform">
				<?php _e('Location: ', 'wprobot');?>
				<select class="add_module_location" name="add_module_location">
					<option selected value="after"><?php _e('After main source', 'cmsc'); ?></option>				
					<option value="before"><?php _e('Before main source', 'cmsc'); ?></option>
				</select><br/>			

				<?php _e('Keyword: ', 'wprobot');?>
				<select class="add_module_keyword" name="add_module_keyword" style="width: 180px;">
					<option selected value="campaign"><?php _e('Use campaign keyword.', 'wprobot');?></option>
					<option value="title"><?php _e('Use title of main content source.', 'wprobot');?></option>
				</select><br/>							
				
				<?php _e('Probability: ', 'wprobot');?>
				<select class="add_module_num" name="add_module_num" style="width: 80px;">
					<option selected value="100">100%</option>
					<option value="90">90%</option>
					<option value="80">80%</option>
					<option value="70">70%</option>
					<option value="60">60%</option>
					<option value="50">50%</option>
					<option value="40">40%</option>
					<option value="30">30%</option>
					<option value="20">20%</option>
					<option value="10">10%</option>
				</select>				
			</div>					
		</div>		
		
	</div>

<p class="wpr5ac"><?php _e('<b>Add Template</b> - Choose the main content source for your template: ', 'wprobot'); echo $sourceselect; ?> <span id="add-all-sources-box">(<?php _e('or click to <a id="add-all-sources" href="#">add all</a>', 'wprobot'); ?>)</span></p>
	
</div>

<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e('Optional Settings', 'wprobot'); ?></h3>

<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('Exclude Keywords', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="3" name="exclude_keywords"><?php echo esc_textarea( $_POST["exclude_keywords"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;">
	<?php _e('Posts containing the keywords you enter here will be skipped and not created on your site.', 'wprobot'); ?>
	</span>
</p>

<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('Require Keywords', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="3" name="require_keywords"><?php echo esc_textarea( $_POST["require_keywords"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;">
	<?php _e('If a post does not contain at least one of the keywords you enter here it will be skipped.', 'wprobot'); ?>
	</span>
</p>

<p class="wpr5ac">
	<span style="display:inline-block;">
		<b><?php _e('Replace Keywords', 'wprobot'); ?></b> <?php _e('(one per line)', 'wprobot'); ?>
		<br>
		<textarea cols="33" rows="3" name="replace_keywords"><?php echo esc_textarea( $_POST["replace_keywords"] ); ?></textarea>
	</span>
	
	<span style="width: 300px;display:inline-block;margin:20px 10px;vertical-align:top;">
	<?php _e('Replace keywords or code in posts with other content.<br/><b>Syntax</b>: Keyword|Replace With|Chance<br/>Example: <i>Wordpress|Joomla|50</i>', 'wprobot'); ?>
	</span>
</p>

<div class="wpr5ac">

	<b><?php _e('Custom Fields', 'wprobot'); ?></b>
	<br>
	
	<input type="hidden" name="cf_num" id="cf_num" value="<?php echo $_POST["cf_num"]; ?>" />

	<div class="cfcontainers">
		<?php for ($i = 1; $i <= $_POST["cf_num"]; $i++) { ?>
			<div class="cfcontainer">
				<?php _e('Name', 'wprobot'); ?>: <input type="text" value="<?php echo esc_textarea( $_POST["cf_name$i"] ); ?>" name="cf_name<?php echo $i; ?>"> 
				<?php _e('Value', 'wprobot'); ?>: <input type="text" value="<?php echo esc_textarea( $_POST["cf_value$i"] ); ?>" name="cf_value<?php echo $i; ?>">
			</div>
		<?php  } ?>

	</div>
	
	<input type="submit" value="+ <?php _e('Add Field', 'wprobot'); ?>" name="wpr_cf_add" id="wpr_cf_add" class="button"> <span style="display:inline-block;padding:5px;"> or <a href="#" name="wpr_cf_woocommerce_setup" id="wpr_cf_woocommerce_setup"><?php _e('click to setup custom fields for WooCommerce', 'wprobot'); ?></a></span>
</div>

<p class="wpr5ac">
<b><?php _e("Translation","wprobot") ?></b><br/>
<?php _e("Translation engine: ","wprobot") ?> 
	<select name="whichtrans" id="whichtrans">
		<option value="---">---</option>	
		<option value="yandex" <?php if ($_POST['whichtrans']=='yandex') {echo 'selected';} ?>><?php _e('Yandex (free)',"wprobot") ?></option>
		<option value="deepl" <?php if ($_POST['whichtrans']=='deepl') {echo 'selected';} ?>><?php _e('DeepL',"wprobot") ?></option>
	</select><br/> <br/>

<span id="deepl_box" <?php if($_POST['whichtrans'] != "deepl") {echo 'style="display:none;"';} ?>>
<?php _e("DeepL API key: ","wprobot") ?> <input type="text" name="tr_deepl_key" value="<?php echo esc_textarea( $_POST["tr_deepl_key"] ); ?>"> <?php _e('(<a href="https://www.deepl.com/pro.html#developer">Requires DeepL Pro subscription</a>)',"wprobot") ?><br/>
<?php _e("Translate posts by this campaign from","wprobot") ?> 
	<select name="trans1_dl" >
		<option value="no" <?php if ($_POST['trans1_dl']=='no') {echo 'selected';} ?>>---</option>
		<option value="de" <?php if ($_POST['trans1_dl']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans1_dl']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans1_dl']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans1_dl']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>		
		<option value="es" <?php if ($_POST['trans1_dl']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>	
		<option value="it" <?php if ($_POST['trans1_dl']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>		
		<option value="nl" <?php if ($_POST['trans1_dl']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>		
		<option value="ru" <?php if ($_POST['trans1_dl']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans1_dl']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>		
	</select>			
<?php _e("to","wprobot") ?> 
	<select name="trans2_dl">
		<option value="no" <?php if ($_POST['trans2_dl']=='no') {echo 'selected';} ?>>---</option>
		<option value="de" <?php if ($_POST['trans2_dl']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans2_dl']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans2_dl']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans2_dl']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>		
		<option value="es" <?php if ($_POST['trans2_dl']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>	
		<option value="it" <?php if ($_POST['trans2_dl']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>		
		<option value="nl" <?php if ($_POST['trans2_dl']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>		
		<option value="ru" <?php if ($_POST['trans2_dl']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans2_dl']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>		
	</select>			
<?php _e("to","wprobot") ?> 
	<select name="trans3_dl">
		<option value="no" <?php if ($_POST['trans3_dl']=='no') {echo 'selected';} ?>>---</option>
		<option value="de" <?php if ($_POST['trans3_dl']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans3_dl']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans3_dl']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans3_dl']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>		
		<option value="es" <?php if ($_POST['trans3_dl']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>	
		<option value="it" <?php if ($_POST['trans3_dl']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>		
		<option value="nl" <?php if ($_POST['trans3_dl']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>		
		<option value="ru" <?php if ($_POST['trans3_dl']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans3_dl']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>		
	</select>	
<?php _e("to","wprobot") ?> 
	<select name="trans4_dl">
		<option value="no" <?php if ($_POST['trans4_dl']=='no') {echo 'selected';} ?>>---</option>
		<option value="de" <?php if ($_POST['trans4_dl']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans4_dl']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans4_dl']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans4_dl']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>		
		<option value="es" <?php if ($_POST['trans4_dl']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>	
		<option value="it" <?php if ($_POST['trans4_dl']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>		
		<option value="nl" <?php if ($_POST['trans4_dl']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>		
		<option value="ru" <?php if ($_POST['trans4_dl']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans4_dl']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>		
	</select>		
</span>	
	
<span id="yandex_box" <?php if($_POST['whichtrans'] != "yandex") {echo 'style="display:none;"';} ?>>
<?php _e("Yandex Translation API key: ","wprobot") ?> <input type="text" name="tr_yandex_key" value="<?php echo esc_textarea( $_POST["tr_yandex_key"] ); ?>"> <?php _e('(required, get one for free <a href="http://api.yandex.com/key/form.xml?service=trnsl">here</a>)',"wprobot") ?><br/>
<?php _e("Translate posts by this campaign from","wprobot") ?> 
	<select name="trans1" >
		<option value="no" <?php if ($_POST['trans1']=='no') {echo 'selected';} ?>>---</option>
		<option value="de" <?php if ($_POST['trans1']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans1']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans1']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="separator" disabled="">&mdash;</option>
		<option value="af" <?php if ($_POST['trans1']=='af') {echo 'selected';} ?>><?php _e('Afrikaans',"wprobot") ?></option>
		<option value="sq" <?php if ($_POST['trans1']=='sq') {echo 'selected';} ?>><?php _e('Albanian',"wprobot") ?></option>
		<option value="ar" <?php if ($_POST['trans1']=='ar') {echo 'selected';} ?>><?php _e('Arabic',"wprobot") ?></option>
		<option value="be" <?php if ($_POST['trans1']=='be') {echo 'selected';} ?>><?php _e('Belarusian',"wprobot") ?></option>
		<option value="bg" <?php if ($_POST['trans1']=='bg') {echo 'selected';} ?>><?php _e('Bulgarian',"wprobot") ?></option>
		<option value="ca" <?php if ($_POST['trans1']=='ca') {echo 'selected';} ?>><?php _e('Catalan',"wprobot") ?></option>
		<option value="zh" <?php if ($_POST['trans1']=='zh') {echo 'selected';} ?>><?php _e('Chinese',"wprobot") ?></option>
		<option value="hr" <?php if ($_POST['trans1']=='hr') {echo 'selected';} ?>><?php _e('Croatian',"wprobot") ?></option>
		<option value="cs" <?php if ($_POST['trans1']=='cs') {echo 'selected';} ?>><?php _e('Czech',"wprobot") ?></option>
		<option value="da" <?php if ($_POST['trans1']=='da') {echo 'selected';} ?>><?php _e('Danish',"wprobot") ?></option>
		<option value="nl" <?php if ($_POST['trans1']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans1']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="et" <?php if ($_POST['trans1']=='et') {echo 'selected';} ?>><?php _e('Estonian',"wprobot") ?></option>
		<option value="tl" <?php if ($_POST['trans1']=='tl') {echo 'selected';} ?>><?php _e('Filipino',"wprobot") ?></option>
		<option value="fi" <?php if ($_POST['trans1']=='fi') {echo 'selected';} ?>><?php _e('Finnish',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans1']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="gl" <?php if ($_POST['trans1']=='gl') {echo 'selected';} ?>><?php _e('Galician',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans1']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="el" <?php if ($_POST['trans1']=='el') {echo 'selected';} ?>><?php _e('Greek',"wprobot") ?></option>
		<option value="iw" <?php if ($_POST['trans1']=='iw') {echo 'selected';} ?>><?php _e('Hebrew',"wprobot") ?></option>
		<option value="hi" <?php if ($_POST['trans1']=='hi') {echo 'selected';} ?>><?php _e('Hindi',"wprobot") ?></option>
		<option value="hu" <?php if ($_POST['trans1']=='hu') {echo 'selected';} ?>><?php _e('Hungarian',"wprobot") ?></option>
		<option value="is" <?php if ($_POST['trans1']=='is') {echo 'selected';} ?>><?php _e('Icelandic',"wprobot") ?></option>
		<option value="id" <?php if ($_POST['trans1']=='id') {echo 'selected';} ?>><?php _e('Indonesian',"wprobot") ?></option>
		<option value="ga" <?php if ($_POST['trans1']=='ga') {echo 'selected';} ?>><?php _e('Irish',"wprobot") ?></option>
		<option value="it" <?php if ($_POST['trans1']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>
		<option value="ja" <?php if ($_POST['trans1']=='ja') {echo 'selected';} ?>><?php _e('Japanese',"wprobot") ?></option>
		<option value="ko" <?php if ($_POST['trans1']=='ko') {echo 'selected';} ?>><?php _e('Korean',"wprobot") ?></option>
		<option value="lv" <?php if ($_POST['trans1']=='lv') {echo 'selected';} ?>><?php _e('Latvian',"wprobot") ?></option>
		<option value="lt" <?php if ($_POST['trans1']=='lt') {echo 'selected';} ?>><?php _e('Lithuanian',"wprobot") ?></option>
		<option value="mk" <?php if ($_POST['trans1']=='mk') {echo 'selected';} ?>><?php _e('Macedonian',"wprobot") ?></option>
		<option value="ms" <?php if ($_POST['trans1']=='ms') {echo 'selected';} ?>><?php _e('Malay',"wprobot") ?></option>
		<option value="mt" <?php if ($_POST['trans1']=='mt') {echo 'selected';} ?>><?php _e('Maltese',"wprobot") ?></option>
		<option value="nor" <?php if ($_POST['trans1']=='nor') {echo 'selected';} ?>><?php _e('Norwegian',"wprobot") ?></option>
		<option value="fa" <?php if ($_POST['trans1']=='fa') {echo 'selected';} ?>><?php _e('Persian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans1']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans1']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>
		<option value="ro" <?php if ($_POST['trans1']=='ro') {echo 'selected';} ?>><?php _e('Romanian',"wprobot") ?></option>
		<option value="ru" <?php if ($_POST['trans1']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="sr" <?php if ($_POST['trans1']=='sr') {echo 'selected';} ?>><?php _e('Serbian',"wprobot") ?></option>
		<option value="sk" <?php if ($_POST['trans1']=='sk') {echo 'selected';} ?>><?php _e('Slovak',"wprobot") ?></option>
		<option value="sl" <?php if ($_POST['trans1']=='sl') {echo 'selected';} ?>><?php _e('Slovenian',"wprobot") ?></option>
		<option value="es" <?php if ($_POST['trans1']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>
		<option value="sw" <?php if ($_POST['trans1']=='sw') {echo 'selected';} ?>><?php _e('Swahili',"wprobot") ?></option>
		<option value="sv" <?php if ($_POST['trans1']=='sv') {echo 'selected';} ?>><?php _e('Swedish',"wprobot") ?></option>
		<option value="th" <?php if ($_POST['trans1']=='th') {echo 'selected';} ?>><?php _e('Thai',"wprobot") ?></option>
		<option value="tr" <?php if ($_POST['trans1']=='tr') {echo 'selected';} ?>><?php _e('Turkish',"wprobot") ?></option>
		<option value="uk" <?php if ($_POST['trans1']=='uk') {echo 'selected';} ?>><?php _e('Ukrainian',"wprobot") ?></option>
		<option value="vi" <?php if ($_POST['trans1']=='vi') {echo 'selected';} ?>><?php _e('Vietnamese',"wprobot") ?></option>
		<option value="cy" <?php if ($_POST['trans1']=='cy') {echo 'selected';} ?>><?php _e('Welsh',"wprobot") ?></option>
		<option value="yi" <?php if ($_POST['trans1']=='yi') {echo 'selected';} ?>><?php _e('Yiddish',"wprobot") ?></option>
	</select>			
<?php _e("to","wprobot") ?> 
	<select name="trans2">
		<option value="no" <?php if ($_POST['trans2']=='no') {echo 'selected';} ?>><?php _e('---',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans2']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans2']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans2']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="separator" disabled="">&mdash;</option>
		<option value="af" <?php if ($_POST['trans2']=='af') {echo 'selected';} ?>><?php _e('Afrikaans',"wprobot") ?></option>
		<option value="sq" <?php if ($_POST['trans2']=='sq') {echo 'selected';} ?>><?php _e('Albanian',"wprobot") ?></option>
		<option value="ar" <?php if ($_POST['trans2']=='ar') {echo 'selected';} ?>><?php _e('Arabic',"wprobot") ?></option>
		<option value="be" <?php if ($_POST['trans2']=='be') {echo 'selected';} ?>><?php _e('Belarusian',"wprobot") ?></option>
		<option value="bg" <?php if ($_POST['trans2']=='bg') {echo 'selected';} ?>><?php _e('Bulgarian',"wprobot") ?></option>
		<option value="ca" <?php if ($_POST['trans2']=='ca') {echo 'selected';} ?>><?php _e('Catalan',"wprobot") ?></option>
		<option value="zh" <?php if ($_POST['trans2']=='zh') {echo 'selected';} ?>><?php _e('Chinese',"wprobot") ?></option>
		<option value="hr" <?php if ($_POST['trans2']=='hr') {echo 'selected';} ?>><?php _e('Croatian',"wprobot") ?></option>
		<option value="cs" <?php if ($_POST['trans2']=='cs') {echo 'selected';} ?>><?php _e('Czech',"wprobot") ?></option>
		<option value="da" <?php if ($_POST['trans2']=='da') {echo 'selected';} ?>><?php _e('Danish',"wprobot") ?></option>
		<option value="nl" <?php if ($_POST['trans2']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans2']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="et" <?php if ($_POST['trans2']=='et') {echo 'selected';} ?>><?php _e('Estonian',"wprobot") ?></option>
		<option value="tl" <?php if ($_POST['trans2']=='tl') {echo 'selected';} ?>><?php _e('Filipino',"wprobot") ?></option>
		<option value="fi" <?php if ($_POST['trans2']=='fi') {echo 'selected';} ?>><?php _e('Finnish',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans2']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="gl" <?php if ($_POST['trans2']=='gl') {echo 'selected';} ?>><?php _e('Galician',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans2']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="el" <?php if ($_POST['trans2']=='el') {echo 'selected';} ?>><?php _e('Greek',"wprobot") ?></option>
		<option value="iw" <?php if ($_POST['trans2']=='iw') {echo 'selected';} ?>><?php _e('Hebrew',"wprobot") ?></option>
		<option value="hi" <?php if ($_POST['trans2']=='hi') {echo 'selected';} ?>><?php _e('Hindi',"wprobot") ?></option>
		<option value="hu" <?php if ($_POST['trans2']=='hu') {echo 'selected';} ?>><?php _e('Hungarian',"wprobot") ?></option>
		<option value="is" <?php if ($_POST['trans2']=='is') {echo 'selected';} ?>><?php _e('Icelandic',"wprobot") ?></option>
		<option value="id" <?php if ($_POST['trans2']=='id') {echo 'selected';} ?>><?php _e('Indonesian',"wprobot") ?></option>
		<option value="ga" <?php if ($_POST['trans2']=='ga') {echo 'selected';} ?>><?php _e('Irish',"wprobot") ?></option>
		<option value="it" <?php if ($_POST['trans2']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>
		<option value="ja" <?php if ($_POST['trans2']=='ja') {echo 'selected';} ?>><?php _e('Japanese',"wprobot") ?></option>
		<option value="ko" <?php if ($_POST['trans2']=='ko') {echo 'selected';} ?>><?php _e('Korean',"wprobot") ?></option>
		<option value="lv" <?php if ($_POST['trans2']=='lv') {echo 'selected';} ?>><?php _e('Latvian',"wprobot") ?></option>
		<option value="lt" <?php if ($_POST['trans2']=='lt') {echo 'selected';} ?>><?php _e('Lithuanian',"wprobot") ?></option>
		<option value="mk" <?php if ($_POST['trans2']=='mk') {echo 'selected';} ?>><?php _e('Macedonian',"wprobot") ?></option>
		<option value="ms" <?php if ($_POST['trans2']=='ms') {echo 'selected';} ?>><?php _e('Malay',"wprobot") ?></option>
		<option value="mt" <?php if ($_POST['trans2']=='mt') {echo 'selected';} ?>><?php _e('Maltese',"wprobot") ?></option>
		<option value="nor" <?php if ($_POST['trans2']=='nor') {echo 'selected';} ?>><?php _e('Norwegian',"wprobot") ?></option>
		<option value="fa" <?php if ($_POST['trans2']=='fa') {echo 'selected';} ?>><?php _e('Persian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans2']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans2']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>
		<option value="ro" <?php if ($_POST['trans2']=='ro') {echo 'selected';} ?>><?php _e('Romanian',"wprobot") ?></option>
		<option value="ru" <?php if ($_POST['trans2']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="sr" <?php if ($_POST['trans2']=='sr') {echo 'selected';} ?>><?php _e('Serbian',"wprobot") ?></option>
		<option value="sk" <?php if ($_POST['trans2']=='sk') {echo 'selected';} ?>><?php _e('Slovak',"wprobot") ?></option>
		<option value="sl" <?php if ($_POST['trans2']=='sl') {echo 'selected';} ?>><?php _e('Slovenian',"wprobot") ?></option>
		<option value="es" <?php if ($_POST['trans2']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>
		<option value="sw" <?php if ($_POST['trans2']=='sw') {echo 'selected';} ?>><?php _e('Swahili',"wprobot") ?></option>
		<option value="sv" <?php if ($_POST['trans2']=='sv') {echo 'selected';} ?>><?php _e('Swedish',"wprobot") ?></option>
		<option value="th" <?php if ($_POST['trans2']=='th') {echo 'selected';} ?>><?php _e('Thai',"wprobot") ?></option>
		<option value="tr" <?php if ($_POST['trans2']=='tr') {echo 'selected';} ?>><?php _e('Turkish',"wprobot") ?></option>
		<option value="uk" <?php if ($_POST['trans2']=='uk') {echo 'selected';} ?>><?php _e('Ukrainian',"wprobot") ?></option>
		<option value="vi" <?php if ($_POST['trans2']=='vi') {echo 'selected';} ?>><?php _e('Vietnamese',"wprobot") ?></option>
		<option value="cy" <?php if ($_POST['trans2']=='cy') {echo 'selected';} ?>><?php _e('Welsh',"wprobot") ?></option>
		<option value="yi" <?php if ($_POST['trans2']=='yi') {echo 'selected';} ?>><?php _e('Yiddish',"wprobot") ?></option>
	</select>			
<?php _e("to","wprobot") ?> 
	<select name="trans3">
		<option value="no" <?php if ($_POST['trans3']=='no') {echo 'selected';} ?>><?php _e('---',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans3']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans3']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans3']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="separator" disabled="">&mdash;</option>
		<option value="af" <?php if ($_POST['trans3']=='af') {echo 'selected';} ?>><?php _e('Afrikaans',"wprobot") ?></option>
		<option value="sq" <?php if ($_POST['trans3']=='sq') {echo 'selected';} ?>><?php _e('Albanian',"wprobot") ?></option>
		<option value="ar" <?php if ($_POST['trans3']=='ar') {echo 'selected';} ?>><?php _e('Arabic',"wprobot") ?></option>
		<option value="be" <?php if ($_POST['trans3']=='be') {echo 'selected';} ?>><?php _e('Belarusian',"wprobot") ?></option>
		<option value="bg" <?php if ($_POST['trans3']=='bg') {echo 'selected';} ?>><?php _e('Bulgarian',"wprobot") ?></option>
		<option value="ca" <?php if ($_POST['trans3']=='ca') {echo 'selected';} ?>><?php _e('Catalan',"wprobot") ?></option>
		<option value="zh" <?php if ($_POST['trans3']=='zh') {echo 'selected';} ?>><?php _e('Chinese',"wprobot") ?></option>
		<option value="hr" <?php if ($_POST['trans3']=='hr') {echo 'selected';} ?>><?php _e('Croatian',"wprobot") ?></option>
		<option value="cs" <?php if ($_POST['trans3']=='cs') {echo 'selected';} ?>><?php _e('Czech',"wprobot") ?></option>
		<option value="da" <?php if ($_POST['trans3']=='da') {echo 'selected';} ?>><?php _e('Danish',"wprobot") ?></option>
		<option value="nl" <?php if ($_POST['trans3']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans3']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="et" <?php if ($_POST['trans3']=='et') {echo 'selected';} ?>><?php _e('Estonian',"wprobot") ?></option>
		<option value="tl" <?php if ($_POST['trans3']=='tl') {echo 'selected';} ?>><?php _e('Filipino',"wprobot") ?></option>
		<option value="fi" <?php if ($_POST['trans3']=='fi') {echo 'selected';} ?>><?php _e('Finnish',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans3']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="gl" <?php if ($_POST['trans3']=='gl') {echo 'selected';} ?>><?php _e('Galician',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans3']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="el" <?php if ($_POST['trans3']=='el') {echo 'selected';} ?>><?php _e('Greek',"wprobot") ?></option>
		<option value="iw" <?php if ($_POST['trans3']=='iw') {echo 'selected';} ?>><?php _e('Hebrew',"wprobot") ?></option>
		<option value="hi" <?php if ($_POST['trans3']=='hi') {echo 'selected';} ?>><?php _e('Hindi',"wprobot") ?></option>
		<option value="hu" <?php if ($_POST['trans3']=='hu') {echo 'selected';} ?>><?php _e('Hungarian',"wprobot") ?></option>
		<option value="is" <?php if ($_POST['trans3']=='is') {echo 'selected';} ?>><?php _e('Icelandic',"wprobot") ?></option>
		<option value="id" <?php if ($_POST['trans3']=='id') {echo 'selected';} ?>><?php _e('Indonesian',"wprobot") ?></option>
		<option value="ga" <?php if ($_POST['trans3']=='ga') {echo 'selected';} ?>><?php _e('Irish',"wprobot") ?></option>
		<option value="it" <?php if ($_POST['trans3']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>
		<option value="ja" <?php if ($_POST['trans3']=='ja') {echo 'selected';} ?>><?php _e('Japanese',"wprobot") ?></option>
		<option value="ko" <?php if ($_POST['trans3']=='ko') {echo 'selected';} ?>><?php _e('Korean',"wprobot") ?></option>
		<option value="lv" <?php if ($_POST['trans3']=='lv') {echo 'selected';} ?>><?php _e('Latvian',"wprobot") ?></option>
		<option value="lt" <?php if ($_POST['trans3']=='lt') {echo 'selected';} ?>><?php _e('Lithuanian',"wprobot") ?></option>
		<option value="mk" <?php if ($_POST['trans3']=='mk') {echo 'selected';} ?>><?php _e('Macedonian',"wprobot") ?></option>
		<option value="ms" <?php if ($_POST['trans3']=='ms') {echo 'selected';} ?>><?php _e('Malay',"wprobot") ?></option>
		<option value="mt" <?php if ($_POST['trans3']=='mt') {echo 'selected';} ?>><?php _e('Maltese',"wprobot") ?></option>
		<option value="nor" <?php if ($_POST['trans3']=='nor') {echo 'selected';} ?>><?php _e('Norwegian',"wprobot") ?></option>
		<option value="fa" <?php if ($_POST['trans3']=='fa') {echo 'selected';} ?>><?php _e('Persian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans3']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans3']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>
		<option value="ro" <?php if ($_POST['trans3']=='ro') {echo 'selected';} ?>><?php _e('Romanian',"wprobot") ?></option>
		<option value="ru" <?php if ($_POST['trans3']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="sr" <?php if ($_POST['trans3']=='sr') {echo 'selected';} ?>><?php _e('Serbian',"wprobot") ?></option>
		<option value="sk" <?php if ($_POST['trans3']=='sk') {echo 'selected';} ?>><?php _e('Slovak',"wprobot") ?></option>
		<option value="sl" <?php if ($_POST['trans3']=='sl') {echo 'selected';} ?>><?php _e('Slovenian',"wprobot") ?></option>
		<option value="es" <?php if ($_POST['trans3']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>
		<option value="sw" <?php if ($_POST['trans3']=='sw') {echo 'selected';} ?>><?php _e('Swahili',"wprobot") ?></option>
		<option value="sv" <?php if ($_POST['trans3']=='sv') {echo 'selected';} ?>><?php _e('Swedish',"wprobot") ?></option>
		<option value="th" <?php if ($_POST['trans3']=='th') {echo 'selected';} ?>><?php _e('Thai',"wprobot") ?></option>
		<option value="tr" <?php if ($_POST['trans3']=='tr') {echo 'selected';} ?>><?php _e('Turkish',"wprobot") ?></option>
		<option value="uk" <?php if ($_POST['trans3']=='uk') {echo 'selected';} ?>><?php _e('Ukrainian',"wprobot") ?></option>
		<option value="vi" <?php if ($_POST['trans3']=='vi') {echo 'selected';} ?>><?php _e('Vietnamese',"wprobot") ?></option>
		<option value="cy" <?php if ($_POST['trans3']=='cy') {echo 'selected';} ?>><?php _e('Welsh',"wprobot") ?></option>
		<option value="yi" <?php if ($_POST['trans3']=='yi') {echo 'selected';} ?>><?php _e('Yiddish',"wprobot") ?></option>
	</select>
<?php _e("to","wprobot") ?> 
	<select name="trans4">
		<option value="no" <?php if ($_POST['trans4']=='no') {echo 'selected';} ?>><?php _e('---',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans4']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans4']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans4']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="separator" disabled="">&mdash;</option>
		<option value="af" <?php if ($_POST['trans4']=='af') {echo 'selected';} ?>><?php _e('Afrikaans',"wprobot") ?></option>
		<option value="sq" <?php if ($_POST['trans4']=='sq') {echo 'selected';} ?>><?php _e('Albanian',"wprobot") ?></option>
		<option value="ar" <?php if ($_POST['trans4']=='ar') {echo 'selected';} ?>><?php _e('Arabic',"wprobot") ?></option>
		<option value="be" <?php if ($_POST['trans4']=='be') {echo 'selected';} ?>><?php _e('Belarusian',"wprobot") ?></option>
		<option value="bg" <?php if ($_POST['trans4']=='bg') {echo 'selected';} ?>><?php _e('Bulgarian',"wprobot") ?></option>
		<option value="ca" <?php if ($_POST['trans4']=='ca') {echo 'selected';} ?>><?php _e('Catalan',"wprobot") ?></option>
		<option value="zh-CN" <?php if ($_POST['trans4']=='zh-CN') {echo 'selected';} ?>><?php _e('Chinese',"wprobot") ?></option>
		<option value="hr" <?php if ($_POST['trans4']=='hr') {echo 'selected';} ?>><?php _e('Croatian',"wprobot") ?></option>
		<option value="cs" <?php if ($_POST['trans4']=='cs') {echo 'selected';} ?>><?php _e('Czech',"wprobot") ?></option>
		<option value="da" <?php if ($_POST['trans4']=='da') {echo 'selected';} ?>><?php _e('Danish',"wprobot") ?></option>
		<option value="nl" <?php if ($_POST['trans4']=='nl') {echo 'selected';} ?>><?php _e('Dutch',"wprobot") ?></option>
		<option value="en" <?php if ($_POST['trans4']=='en') {echo 'selected';} ?>><?php _e('English',"wprobot") ?></option>
		<option value="et" <?php if ($_POST['trans4']=='et') {echo 'selected';} ?>><?php _e('Estonian',"wprobot") ?></option>
		<option value="tl" <?php if ($_POST['trans4']=='tl') {echo 'selected';} ?>><?php _e('Filipino',"wprobot") ?></option>
		<option value="fi" <?php if ($_POST['trans4']=='fi') {echo 'selected';} ?>><?php _e('Finnish',"wprobot") ?></option>
		<option value="fr" <?php if ($_POST['trans4']=='fr') {echo 'selected';} ?>><?php _e('French',"wprobot") ?></option>
		<option value="gl" <?php if ($_POST['trans4']=='gl') {echo 'selected';} ?>><?php _e('Galician',"wprobot") ?></option>
		<option value="de" <?php if ($_POST['trans4']=='de') {echo 'selected';} ?>><?php _e('German',"wprobot") ?></option>
		<option value="el" <?php if ($_POST['trans4']=='el') {echo 'selected';} ?>><?php _e('Greek',"wprobot") ?></option>
		<option value="iw" <?php if ($_POST['trans4']=='iw') {echo 'selected';} ?>><?php _e('Hebrew',"wprobot") ?></option>
		<option value="hi" <?php if ($_POST['trans4']=='hi') {echo 'selected';} ?>><?php _e('Hindi',"wprobot") ?></option>
		<option value="hu" <?php if ($_POST['trans4']=='hu') {echo 'selected';} ?>><?php _e('Hungarian',"wprobot") ?></option>
		<option value="is" <?php if ($_POST['trans4']=='is') {echo 'selected';} ?>><?php _e('Icelandic',"wprobot") ?></option>
		<option value="id" <?php if ($_POST['trans4']=='id') {echo 'selected';} ?>><?php _e('Indonesian',"wprobot") ?></option>
		<option value="ga" <?php if ($_POST['trans4']=='ga') {echo 'selected';} ?>><?php _e('Irish',"wprobot") ?></option>
		<option value="it" <?php if ($_POST['trans4']=='it') {echo 'selected';} ?>><?php _e('Italian',"wprobot") ?></option>
		<option value="ja" <?php if ($_POST['trans4']=='ja') {echo 'selected';} ?>><?php _e('Japanese',"wprobot") ?></option>
		<option value="ko" <?php if ($_POST['trans4']=='ko') {echo 'selected';} ?>><?php _e('Korean',"wprobot") ?></option>
		<option value="lv" <?php if ($_POST['trans4']=='lv') {echo 'selected';} ?>><?php _e('Latvian',"wprobot") ?></option>
		<option value="lt" <?php if ($_POST['trans4']=='lt') {echo 'selected';} ?>><?php _e('Lithuanian',"wprobot") ?></option>
		<option value="mk" <?php if ($_POST['trans4']=='mk') {echo 'selected';} ?>><?php _e('Macedonian',"wprobot") ?></option>
		<option value="ms" <?php if ($_POST['trans4']=='ms') {echo 'selected';} ?>><?php _e('Malay',"wprobot") ?></option>
		<option value="mt" <?php if ($_POST['trans4']=='mt') {echo 'selected';} ?>><?php _e('Maltese',"wprobot") ?></option>
		<option value="nor" <?php if ($_POST['trans4']=='nor') {echo 'selected';} ?>><?php _e('Norwegian',"wprobot") ?></option>
		<option value="fa" <?php if ($_POST['trans4']=='fa') {echo 'selected';} ?>><?php _e('Persian',"wprobot") ?></option>
		<option value="pl" <?php if ($_POST['trans4']=='pl') {echo 'selected';} ?>><?php _e('Polish',"wprobot") ?></option>
		<option value="pt" <?php if ($_POST['trans4']=='pt') {echo 'selected';} ?>><?php _e('Portuguese',"wprobot") ?></option>
		<option value="ro" <?php if ($_POST['trans4']=='ro') {echo 'selected';} ?>><?php _e('Romanian',"wprobot") ?></option>
		<option value="ru" <?php if ($_POST['trans4']=='ru') {echo 'selected';} ?>><?php _e('Russian',"wprobot") ?></option>
		<option value="sr" <?php if ($_POST['trans4']=='sr') {echo 'selected';} ?>><?php _e('Serbian',"wprobot") ?></option>
		<option value="sk" <?php if ($_POST['trans4']=='sk') {echo 'selected';} ?>><?php _e('Slovak',"wprobot") ?></option>
		<option value="sl" <?php if ($_POST['trans4']=='sl') {echo 'selected';} ?>><?php _e('Slovenian',"wprobot") ?></option>
		<option value="es" <?php if ($_POST['trans4']=='es') {echo 'selected';} ?>><?php _e('Spanish',"wprobot") ?></option>
		<option value="sw" <?php if ($_POST['trans4']=='sw') {echo 'selected';} ?>><?php _e('Swahili',"wprobot") ?></option>
		<option value="sv" <?php if ($_POST['trans4']=='sv') {echo 'selected';} ?>><?php _e('Swedish',"wprobot") ?></option>
		<option value="th" <?php if ($_POST['trans4']=='th') {echo 'selected';} ?>><?php _e('Thai',"wprobot") ?></option>
		<option value="tr" <?php if ($_POST['trans4']=='tr') {echo 'selected';} ?>><?php _e('Turkish',"wprobot") ?></option>
		<option value="uk" <?php if ($_POST['trans4']=='uk') {echo 'selected';} ?>><?php _e('Ukrainian',"wprobot") ?></option>
		<option value="vi" <?php if ($_POST['trans4']=='vi') {echo 'selected';} ?>><?php _e('Vietnamese',"wprobot") ?></option>
		<option value="cy" <?php if ($_POST['trans4']=='cy') {echo 'selected';} ?>><?php _e('Welsh',"wprobot") ?></option>
		<option value="yi" <?php if ($_POST['trans4']=='yi') {echo 'selected';} ?>><?php _e('Yiddish',"wprobot") ?></option>
	</select>	
</span>		
</p>

<p class="wpr5ac">
<b><?php _e("Rewriting","wprobot") ?></b><br/>

<?php if($options["options"]["wordai"]["disabled"] != 1 || $options["options"]["spinrewriter"]["disabled"] != 1 || $options["options"]["thebestspinner"]["disabled"] != 1 || $options["options"]["spinchimp"]["disabled"] != 1 || $options["options"]["spinnerchief"]["disabled"] != 1) {?>
<?php _e("Rewrite all posts with ","wprobot") ?> 
<select name="rewriter">
	<option <?php if ($_POST['rewriter']=='') {echo 'selected';} ?> value="">---</option>
	<?php if($options["options"]["thebestspinner"]["disabled"] != 1) {?><option value="tbs" <?php if ($_POST['rewriter']=='tbs') {echo 'selected';} ?>>TheBestSpinner.com</option><?php } ?>
	<?php if($options["options"]["spinnerchief"]["disabled"] != 1) {?><option value="sc" <?php if ($_POST['rewriter']=='sc') {echo 'selected';} ?>>SpinnerChief.com</option><?php } ?>
	<?php if($options["options"]["spinchimp"]["disabled"] != 1) {?><option value="spinchimp" <?php if ($_POST['rewriter']=='spinchimp') {echo 'selected';} ?>>SpinChimp.com</option><?php } ?>
	<?php if($options["options"]["spinrewriter"]["disabled"] != 1) {?><option value="sr" <?php if ($_POST['rewriter']=='sr') {echo 'selected';} ?>>SpinRewriter.com</option><?php } ?>	
	<?php if($options["options"]["wordai"]["disabled"] != 1) {?><option value="wai" <?php if ($_POST['rewriter']=='wai') {echo 'selected';} ?>>WordAI.com</option><?php } ?>	
</select>
<?php } else { ?><?php _e('Please activate a rewriter on the Options page first.', 'wprobot'); ?><?php }  ?>
</p>

<p class="wpr5ac">
<b><?php _e("Misc","wprobot") ?></b><br/>

<input type="checkbox" value="1" name="strip_links" id="strip_links" <?php if($_POST['strip_links']==1) {echo "checked";}?>> <label for="strip_links"><?php _e("Strip all links from posts (Important: also strips your affiliate links or required attribution, so use with caution).","wprobot") ?></label> 

</p>

<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e('Module Settings', 'wprobot'); ?></h3>

	<div id="tabs">

		<div id="cmsc-tabbar">			
				<ul class="tabs">
					<li style="display:none;"><a href="#tabs-1" title="">Intro</a></li>				
					<?php $num = 1; foreach($options["options"] as $module => $moduledata) { if($moduledata["disabled"] != 1 /*&& $moduledata["display"] != "no"*/) {$num++; 
					if(is_array($loaded_modules) && in_array($module, $loaded_modules)) {$dspll = "";} else {$dspll = "display:none;";}
					?>
					<li style="<?php echo $dspll;?>" class="tabby-<?php echo $module;?>"><a href="#tabs-<?php echo $num;?>" title="<?php echo esc_html($moduledata["name"]); ?>"><?php echo esc_html($moduledata["name"]);?></a></li>
					<?php } } ?>
				</ul>		
			<div style="clear:both;"></div>
		</div>

		<div id="tabs-1" class="introtext">		
			<p><?php _e('Select a module above to override its general settings from the Options page for this campaign. Only sources which you have added to this campaign as templates are shown above.', 'wprobot'); ?></p>
		</div>		
		<?php $modulearray = $options["options"];$num = 1; if(is_array($modulearray)) { foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 /*&& $moduledata["display"] != "no"*/) {$num++; ?>			

			<div id="tabs-<?php echo $num;?>" class="settingtab-<?php echo $module;?>">	
				
				<?php if(1 == $_POST[$module."_overrider"]) {
					$tstyl = "";
				} else {
					$tstyl = "opacity:0.2;";
				} ?>
			
				<p class="theoverridebutton">
					<label for="<?php echo $module."_overrider";?>"><?php _e('Check to override global settings of this module in your campaign:', 'wprobot'); ?></label>
					<input class="button the_overrider" type="checkbox" id="<?php echo $module."_overrider"; ?>" name="<?php echo $module."_overrider"; ?>" value="1" <?php if(1 == $_POST[$module."_overrider"]) {echo "checked";} ?>/>		
				</p>
			
				<p style="<?php echo $tstyl;?>" class="thesettings">
				<label for="<?php echo $module;?>_template"><strong>Template</strong>:</label>	
				<select name="<?php echo $module;?>_template" id="<?php echo $module;?>_template">		
					<?php foreach($moduledata["templates"] as $template => $templatedata) { ?>
					<option <?php if($_POST[$module . "_template"] == $template) {echo "selected";} ?> value="<?php echo $template;?>"><?php echo $templatedata["name"]; ?></option>
					<?php } ?>
				</select>	
				</p>
				
				<p style="<?php echo $tstyl;?>" class="thesettings">
				<?php foreach($moduledata["options"] as $option => $data) {
					if($option != "title" && $option != "unique" && $option != "error" && $option != "unique_direct" && $option != "title_direct") {
						if($data["type"] == "text") { // Text Option ?> 
								<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?>:</label>
								<input class="regular-text" type="text" name="<?php echo $module."_".$option;?>" value="<?php if(!empty($_POST[$module."_".$option])) {echo $_POST[$module."_".$option];} else {echo $data["value"];} ?>" /><br/>
						<?php } elseif($data["type"] == "select") { // Select Option ?>
								<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?>:</label>
								<select name="<?php echo $module."_".$option;?>">
									<?php 
									if(!empty($_POST[$module."_".$option])) {$valch = $_POST[$module."_".$option];} else {$valch = $data["value"];}
									foreach($data["values"] as $val => $name) { ?>
									<option value="<?php echo esc_attr($val);?>" <?php if($val == $valch) {echo "selected";} ?>><?php echo esc_html($name); ?></option>
									<?php } ?>		
								</select><br/>	
						<?php } elseif($data["type"] == "checkbox") { // checkbox Option 
							if(!empty($_POST[$module."_".$option])) {$valcd = $_POST[$module."_".$option];} else {$valcd = $data["value"];}
						?>		
							<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?></label>
							<input class="button" type="checkbox" id="<?php echo $module."_".$option; ?>" name="<?php echo $module."_".$option; ?>" value="1" <?php if(1 == $valcd) {echo "checked";} ?>/>	<br/>						
						<?php } ?>	
						
					<?php } ?>
				<?php } ?>	
				</p>
				
			</div>
		<?php } } } ?>
	</div>

</div>
</form>
</div>
<?php	
}
?>