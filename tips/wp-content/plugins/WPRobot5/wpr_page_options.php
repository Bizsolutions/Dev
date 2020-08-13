<?php
/*********************************************************************************************************************************************/
/*                                                                 DISABLE PAGE                                                              */           
/*********************************************************************************************************************************************/

/*================================================================ 1. Functions =============================================================*/




/*================================================================== 2. Views ===============================================================*/

// Scripts
function wpr5_disable_page_print_scripts() {

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-cookie', plugins_url('/includes/jquery.cookie.js', __FILE__),array('jquery') );	
	wp_enqueue_script('jquery-tablesorter', plugins_url('/includes/jquery.tablesorter.js', __FILE__),array('jquery') );
	
	wp_enqueue_style('wpr5-admin-styles', plugins_url('/includes/admin-styles.css', __FILE__) );	
}

// Header
function wpr5_disable_page_head() {
?>
    <script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery("#disabletable").tablesorter({headers: { 0: {sorter: false }} }); 
		jQuery( "#tabs" ).tabs({
			activate: function (e, ui) { 
				jQuery.cookie('selected-tab', ui.newTab.index(), { path: '/' }); 
			}, 
			active: jQuery.cookie('selected-tab')
		});	

		<?php if($_GET["tab"] == 2) { ?>
		jQuery( "#tabs" ).tabs( "option", "active", 1 );
		<?php } ?>
		
	});
	function addtxt(input,txt) {
		var obj = document.getElementById(input);
		obj.value += txt;
	}	
	</script>

<?php		
}

// Page Body
function wpr5_options_page() {

	global $generalarray, $wpr5_source_infos, $optionsexpl;

	wpr5_check_license_key();

?>
<div class="wrap">
<h1><?php _e("WP Robot Options","wprobot") ?></h1>

<?php	

	$options = wpr5_get_options();
	//echo "<pre>";print_r($options);echo "</pre>";
	$recorder = get_option("cmsc_recorder");
	$is_trial = get_option("wpr5_is_trial");
	
	if($is_trial == true) {
		echo '<div class="updated below-h2"><p>'.__('You are using the <strong>trial version</strong> of WP Robot 5 which allows you to test all features for 14 days. Ready to upgrade to the <a href="http://wprobot.net/order" target="_blank">full version</a>? If so simply <a href="?page=wpr5-options&endtrial=1" onclick="return confirm("This will end your trial period and allow you to upgrade to the full version. You should not continue unless you already ordered the full version or intend to do so. Continue?">click here</a> and afterwards you can enter your full version license key.', 'wprobot').'</p></div>';			
	}
	
	if($_GET["endtrial"] == 1) {	
		update_option("wpr5_trial_expired", true);
	}
	
	if(empty($options)) { // if no options yet...
	
		$expired1 = get_option("wpr5_license_expired");
		$expired2 = get_option("wpr5_trial_expired");
		
		if($expired1 != true && $expired2 != true) {
			$options = array();

			foreach($wpr5_source_infos["sources"] as $module => $moduledata) {
				$options["options"][$module]["name"] = $moduledata["name"];
				$options["options"][$module]["disabled"] = 1; // ...disable all modules...
			}

			wpr5_add_options($options);		
		}
	}
	
	if($_POST["import_option_from_site"]) {	
		$site = $_POST["site_to_import"];
		$options = wpr5_load_options_remote(0, $site);
		
		if($options !== false) {
			$result = wpr5_update_options($options);
			echo '<div class="updated"><p>'.__('Options have been loaded. Don\'t forget to save the options form below if you want to keep them on this site.', 'wprobot').'</p></div>';		
		}
	}		
	
	if($_POST["wpr5_import_options"] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {	
		wpr5_import_options();	
	}	
	
	if($_POST["wpr5_reset_options"] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {
	
		$options = array();

		foreach($wpr5_source_infos["sources"] as $module => $moduledata) {
			$options["options"][$module]["name"] = $moduledata["name"];
			$options["options"][$module]["disabled"] = 1; // ...disable all modules...
		}
		
		wpr5_update_options($options);	
	}
	
	if($_POST['wpr5_uninstall'] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {
		global $wpdb,$wpr5_table_posts, $wpr5_table_posted;
		$results = $wpdb->query("DROP TABLE $wpr5_table_posts,$wpr5_table_posted;");

		$license = trim( get_option( 'wpr5_license_final' ) );
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( EDD_WPR_ITEM_NAME ),
			'url'       => home_url()
		);
		$response = wp_remote_post( EDD_WPR_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );		
		
		wpr5_delete_options();
		delete_option("wpr5_campaigns");
		delete_option("wpr5_license_final");	
		delete_option("wpr5_db_ver");
		delete_option("wpr5_is_trial");	
		delete_option("wpr5_license_expired");
		delete_option("wpr5_trial_expired");
		
		$options = "";
		echo '<div class="updated"><p>'.__('WP Robot has been uninstalled. You can now disable and delete the plugin.<br/><br/><strong>If you intend to reinstall WP Robot please first disable and reenable the plugin on your blogs "Plugins" page - otherwise the installation will not work!</strong>', 'wprobot').'</p></div>';		
		die();
	}	

	if($_POST['wpr5_clear_posts']) {
		global $wpdb,$wpr5_table_posts;
		$results = $wpdb->query("TRUNCATE TABLE $wpr5_table_posts;");			
		echo '<div class="updated"><p>'.__('History has been cleared.', 'wprobot').'</p></div>';		
	}	

	if($_POST["save_sources"] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {

		$wpr5_modulearray = wpr5_load_options_remote(1);
		
		if(!is_array($wpr5_modulearray) || !empty($wpr5_modulearray["error"])) {
			echo '<div class="updated error"><p>'.__('Error: Options could not be loaded from the WP Robot server. Please contact support. ', 'wprobot').$wpr5_modulearray["error"].'</p></div>';		
		} else {
			foreach($wpr5_source_infos["sources"] as $module => $moduledata) { // UPDATE MODULES
				if($options["options"][$module]["disabled"] == 1 && $_POST[$module] == 1 || empty($options["options"][$module]) && $_POST[$module] == 1) { // enable disabled module
					$options["options"][$module] = $wpr5_modulearray[$module];
					if(!empty($options["options"][$module])) {$options["options"][$module]["disabled"] = 0;}
				} elseif($options["options"][$module]["disabled"] === 0 && $_POST[$module] == 1) {// enabled module stays enabled
				} elseif($options["options"][$module]["disabled"] == 0 && $_POST[$module] == 0) { // disable enabled module
					$options["options"][$module] = array("disabled" => 1, "name" => $moduledata["name"]);
				}
			}
			
			wpr5_update_options($options);	
			echo '<div class="updated below-h2"><p>'.__('Your selection has been saved. Please <strong>click on the new settings tabs below</strong> to configure your content sources. Modules with a <span class="dashicons dashicons-warning" style="color:#0073AA;"></span> next to their name require that you enter your API keys or credentials before they can be used.', 'wprobot').'</p></div>';		
		}
	}
	
	if(!empty($_POST) && empty($_POST["wpr_license_save"]) && empty($_POST["wpr_license_replace"]) && empty($_POST["wpr_license_renew"]) && empty($_POST["import_option_from_site"]) && empty($_POST["wpr5_import_options"]) && empty($_POST["wpr5_clear_posts"]) && empty($_POST["wpr5_uninstall"]) && empty($_POST["save_sources"]) && empty($_POST["wpr5_reset_options"]) && empty($_POST["wpr4_import"])) {
	
		foreach($options["options"] as $module => $moduledata) {
			if($moduledata["disabled"] != 1) {
				foreach($moduledata["options"] as $option => $data) {
					if($option != "title" && $option != "unique" && $option != "error" && $option != "unique_direct" && $option != "title_direct") {
						if($options["options"][$module]["options"][$option]["value"] != $_POST[$module."_".$option] && $options["options"][$module]["options"][$option]["verified"] == 1) {
							$options["options"][$module]["options"][$option]["verified"] = 0; // reset verified modules
						}

						if($option == "mmode_template") {
							$options["options"][$module]["options"][$option]["value"] = stripslashes($_POST[$module."_".$option]);				
						} else {
							$options["options"][$module]["options"][$option]["value"] = sanitize_text_field($_POST[$module."_".$option]);
						}
					}
				}
				if(is_array($moduledata["templates"])) {
					foreach($moduledata["templates"] as $template => $templatedata) {
						if(!empty($_POST[$module."_".$template])) {
							$options["options"][$module]["templates"][$template]["content"] = stripslashes($_POST[$module."_".$template]);
							$options["options"][$module]["templates"][$template]["name"] = stripslashes(sanitize_text_field($_POST[$module."_".$template."_name"]));		
						}
					}
				}
			}
		}

		$result = wpr5_update_options($options);
		if($_POST["save_options"] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {

			echo '<div class="updated below-h2"><p>'.__('Options have been updated.', 'wprobot').'</p></div>';	
		}		
		
		// VERIFICATION FUNCTION
		foreach($options["options"] as $module => $moduledata) {
			if($_POST[$module."_verify"] && check_admin_referer( 'cmsc-sources-form-'.$user_id )) {
				$api = new API_request;
				$result = $api->api_content_bulk("camera",array($module => 1));
				if(empty($result[$module]["error"]) && isset($result[$module][0]["content"])) {
					if($module == "amazon") {$options["options"][$module]["options"]["public_key"]["verified"] = 1;} else {$options["options"][$module]["options"]["appid"]["verified"] = 1;}
					wpr5_update_options($options);
				} else {
					echo '<div class="updated below-h2"><p>'.$result[$module]["error"].'</p></div>';	
				}
			}
			
			if(!empty($moduledata["templates"])) {
				foreach($moduledata["templates"] as $template => $templatedata) {	
					if($_POST[$module."_".$template."_copybutton"]) { // COPY TEMPLATE		
						$options["options"][$module]["templates"][$template."_copy"]["content"] = $options["options"][$module]["templates"][$template]["content"];
						$options["options"][$module]["templates"][$template."_copy"]["name"] = $options["options"][$module]["templates"][$template]["name"] . " Copy";	
						wpr5_update_options($options);
					}
					if($_POST[$module."_".$template."_deletebutton"]) { // DELETE TEMPLATE		
						unset($options["options"][$module]["templates"][$template]);
						wpr5_update_options($options);
					}
				}			
			}
		}				
	}	
	
	if($_POST["wpr4_import"]) {
		require_once("wpr4_import.php");
		$options = wpr5_import_previous_settings($_POST["import_history"]);
		if(!empty($options["error"])) {
			$options = wpr5_get_options();	
			echo '<div class="updated below-h2"><p>'.__('Error: ', 'wprobot').$result["error"].'</p></div>';				
		} else {
			echo '<div class="updated below-h2"><p>'.__('Your settings have been imported.', 'wprobot').'</p></div>';				
		}
	}	

?>

	<div id="tabs">
		<form method="post" name="wpr5_options" id="fluency-options">	
		<?php wp_nonce_field( 'cmsc-sources-form-'.$user_id ); ?>
		
		<div id="cmsc-tabbar">			
				<ul class="tabs">
					<li><a href="#tabs-1" title="Choose content sources"><?php _e('Choose Sources', 'wprobot'); ?></a></li>
					<?php $num = 1; if(is_array($options["options"])) {foreach($options["options"] as $module => $moduledata) { if($moduledata["disabled"] != 1) {$num++; 
					if($module != "general") {
						$disp_importer = 1;
					}
					
					if((!empty($moduledata["options"]["wai_rewrite_pw"]["name"]) && empty($moduledata["options"]["wai_rewrite_pw"]["value"])) || (!empty($moduledata["options"]["tbs_pw"]["name"]) && empty($moduledata["options"]["tbs_pw"]["value"])) || (!empty($moduledata["options"]["sc_pw"]["name"]) && empty($moduledata["options"]["sc_pw"]["value"])) || (!empty($moduledata["options"]["api_key"]["name"]) && empty($moduledata["options"]["api_key"]["value"])) || (!empty($moduledata["options"]["pw"]["name"]) && empty($moduledata["options"]["pw"]["value"])) || (!empty($moduledata["options"]["appid"]["name"]) && empty($moduledata["options"]["appid"]["value"])) || !empty($moduledata["options"]["public_key"]["name"]) && empty($moduledata["options"]["public_key"]["value"])) {
						$app_warn = ' <span style="margin-top:3px;color:#0073AA;" class="dashicons dashicons-warning"></span>';
					} else {
						$app_warn = '';
					}
					
					?>
					<li><a href="#tabs-<?php echo $num;?>" title="<?php echo esc_html($moduledata["name"]); ?>"><?php echo esc_html($moduledata["name"]).$app_warn;?></a></li>
					<?php } } } ?>
				</ul>		
			<div style="clear:both;"></div>
		</div>

		<div>
			<div>
			
				<div id="tabs-1">
				
					<div style="display:inline-block;float:right;">
						<a href="http://wprobot.net/wp-robot-5-documentation/#options" target="_blank"><strong><?php _e('Need help? Click here.', 'cmsc'); ?></strong></a>
					</div>
				
					<h3><?php _e('Choose Your Content Sources', 'wprobot'); ?></h3>	
					
						<div style="display:inline-block;float:right;">
						<?php if(!empty($_GET["order"])) { ?><div class="disable-sources-cat"><a href="?page=wpr5-options"><?php _e('Order Alphabetically', 'wprobot'); ?></a></div><?php } else {?><div class="disable-sources-cat"><strong><?php _e('Order Alphabetically', 'wprobot'); ?></strong></div><?php } ?>
						<?php if($_GET["order"] != "category") { ?><div class="disable-sources-cat"><a href="?page=wpr5-options&order=category"><?php _e('Order by Categories', 'wprobot'); ?></a></div><?php } else {?><div class="disable-sources-cat"><strong><?php _e('Order by Categories', 'wprobot'); ?></strong></div><?php } ?>
						<?php if($_GET["order"] != "language") { ?><div class="disable-sources-cat"><a href="?page=wpr5-options&order=language"><?php _e('Order by Languages', 'wprobot'); ?></a></div><?php } else {?><div class="disable-sources-cat"><strong><?php _e('Order by Languages', 'wprobot'); ?></strong></div><?php } ?>
						</div>
						
						<p class="submit"><input class="button-primary" type="submit" name="save_sources" value="<?php _e("Save Selections","wprobot") ?>" /></p>
						
						<table id="disabletable" class="form-table">
							<thead>
							<tr>
								<th style="width:2%;" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>						
								<th style="width:20%;" class="manage-column" scope="col"><span style="margin-left: 20px;"><?php _e('Name', 'wprobot'); ?></th>
								<th style="" class="manage-column column-imgss" scope="col"><span style="margin-left: 20px;"></th>
								<th style="width:25%;" class="manage-column" id="cms" scope="col"><span style="margin-left: 20px;"><?php _e('Languages (besides English)', 'wprobot'); ?></span></th>
								<th style="width:25%;" class="manage-column" id="cms" scope="col"><span style="margin-left: 20px;"><?php _e('Sign Up', 'wprobot'); ?></span></th>							
							</tr>
							</thead>		
							<tbody>	
							<?php if($_GET["order"] == "category") { ?>
								<?php foreach($wpr5_source_infos["categories"] as $cat => $catdata) { ?>
									<tr><td colspan="5"><strong style="font-size:115%;"><?php echo $catdata["name"];?></strong></td></tr>
									<?php foreach($catdata["sources"] as $module) { if($cat == "shopping") {
									$cshow = ucwords($cat) . " / Affiliate";} else {$cshow = ucwords($cat);} 
									// GET PIC
									$imgp = wpr5_DIRPATH . 'images/'.$module;
									if(file_exists($imgp . ".jpg")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.jpg" />';				
									} elseif(file_exists($imgp . ".png")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.png" />';			
									} else {
										$img = '';	
									}	
									?>
										<tr>
											<th class="check-column" scope="row"><input type="checkbox" id="<?php echo $module;?>" name="<?php echo $module;?>" value="1" <?php if($options["options"][$module]["disabled"] === 0) {echo "checked";} ?>></th>						
											<td><label for="<?php echo $module;?>"><strong><?php echo $wpr5_source_infos["sources"][$module]["name"];?></strong><br/><span class="source-cat"><?php echo $cshow; ?></span></label></td>	
											<td class="imgtd"><?php echo $img; ?></td>
											<td><?php foreach($wpr5_source_infos["languages"] as $lang => $ldet) {if(in_array($module, $ldet["sources"])) {echo $lang. " ";}} ?></td>
											<td>
											<?php if($wpr5_source_infos["sources"][$module]["signup"] == "no") {
												_e("No account required","wprobot");
											} elseif(!empty($wpr5_source_infos["sources"][$module]["signup"])) {
												echo '<a target="_blank" href="'.$wpr5_source_infos["sources"][$module]["signup"].'">'.__('Sign Up for API account', 'wprobot').'</a>';
												if($wpr5_source_infos["sources"][$module]["paid"] == 1) {echo " (paid)";} else {echo " (free)";}
											} 
											?></td>
										</tr>	
									<?php } ?>					
								<?php } ?>						
							<?php } elseif($_GET["order"] == "language") { ?>
								<?php foreach($wpr5_source_infos["languages"] as $lang => $langdata) { ?>
									<tr><td colspan="5"><strong style="font-size:115%;"><?php echo $langdata["name"];?></strong></td></tr>
									<?php foreach($langdata["sources"] as $module) { 
									$cat = $wpr5_source_infos["sources"][$module]["categories"][0];
									if($cat == "shopping") {$cshow = ucwords($cat) . " / Affiliate";} else {$cshow = ucwords($cat);}
									
									// GET PIC
									$imgp = wpr5_DIRPATH . 'images/'.$module;
									if(file_exists($imgp . ".jpg")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.jpg" />';				
									} elseif(file_exists($imgp . ".png")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.png" />';			
									} else {
										$img = '';	
									}									
									?>
										<tr>
											<th class="check-column" scope="row"><input type="checkbox" id="<?php echo $module;?>" name="<?php echo $module;?>" value="1" <?php if($options["options"][$module]["disabled"] === 0) {echo "checked";} ?>></th>						
											<td><label for="<?php echo $module;?>"><strong><?php echo $wpr5_source_infos["sources"][$module]["name"];?></strong><br/><span class="source-cat"><?php echo $cshow; ?></span></label></td>	
											<td class="imgtd"><?php echo $img; ?></td>
											<td><?php foreach($wpr5_source_infos["languages"] as $lang => $ldet) {if(in_array($module, $ldet["sources"])) {echo $lang. " ";}} ?></td>
											<td>
											<?php if($wpr5_source_infos["sources"][$module]["signup"] == "no") {
												_e("No account required","wprobot");
											} elseif(!empty($wpr5_source_infos["sources"][$module]["signup"])) {
												echo '<a target="_blank" href="'.$wpr5_source_infos["sources"][$module]["signup"].'">'.__('Sign Up for API account', 'wprobot').'</a>';
												if($wpr5_source_infos["sources"][$module]["paid"] == 1) {echo " (paid)";} else {echo " (free)";}
											} 
											?></td>
										</tr>	
									<?php } ?>					
								<?php } ?>							
							<?php } else {?>
								<?php foreach($wpr5_source_infos["sources"] as $module => $sourcedata) { 
									$cat = $sourcedata["categories"][0];
									if($cat == "shopping") {$cshow = ucwords($cat) . " / Affiliate";} else {$cshow = ucwords($cat);}	

									// GET PIC
									$imgp = wpr5_DIRPATH . 'images/'.$module;
									if(file_exists($imgp . ".jpg")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.jpg" />';				
									} elseif(file_exists($imgp . ".png")) {						
										$img = '<img class="disable-module-img" src="'.wpr5_URLPATH.'images/'.$module.'.png" />';			
									} else {
										$img = '';	
									}	
								?>
									<tr>
										<th class="check-column" scope="row"><input type="checkbox" id="<?php echo $module;?>" name="<?php echo $module;?>" value="1" <?php if($options["options"][$module]["disabled"] === 0) {echo "checked";} ?>></th>						
										<td><label for="<?php echo $module;?>"><strong><?php echo $wpr5_source_infos["sources"][$module]["name"];?></strong><br/><span class="source-cat"><?php echo $cshow; ?></span></label></td>	
										<td class="imgtd"><?php echo $img; ?></td>
										<td><?php foreach($wpr5_source_infos["languages"] as $lang => $ldet) {if(in_array($module, $ldet["sources"])) {echo strtoupper($lang). "&nbsp;&nbsp;";}} ?></td>
										<td>
										<?php if($wpr5_source_infos["sources"][$module]["signup"] == "no") {
											echo "No account required";
										} elseif(!empty($wpr5_source_infos["sources"][$module]["signup"])) {
											echo '<a target="_blank" href="'.$wpr5_source_infos["sources"][$module]["signup"].'">Sign Up for API account</a>';
											if($wpr5_source_infos["sources"][$module]["paid"] == 1) {echo " (paid)";} else {echo " (free)";}
										} 
										?></td>
									</tr>				
								<?php } ?>
							<?php } ?>
							</tbody>
						</table>	
						
					<p class="submit"><input class="button-primary" type="submit" name="save_sources" value="<?php _e("Save Selections","wprobot") ?>" /></p>
		
					<h3><?php _e('Reset Sources and Options', 'wprobot'); ?></h3>		
		
					<!--<input class="button" type="submit" name="wpr5_import_options" value="<?php _e("Import Options","wprobot") ?>" />-->
		
					<p class="submit"><input onclick="return confirm('<?php _e("This will reset all options and sources you have selected and return CMS Commander to its default configuration after you signed up. Continue?","wprobot") ?>')" class="button" type="submit" name="wpr5_reset_options" value="<?php _e("Reset All Options","wprobot") ?>" /></p>

					<input onclick="return confirm('<?php _e("This will clear the WP Robot post history and thus all posts in the history could get posted again. Continue?","wprobot") ?>')" class="button" type="submit" name="wpr5_clear_posts" value="<?php _e("Clear Post History","wprobot") ?>" /> 
					
					<h3><?php _e("Uninstall WP Robot","wprobot") ?></h3>
	
					<p><?php _e("The button below will <b>uninstall WP Robot completely</b> including the <b>deletion of all database tables and all your settings and campaigns</b>. All the <b>posts created by WP Robot are not deleted</b>.","wprobot") ?></p>
						
					<input onclick="return confirm('<?php _e("Warning: This will uninstall WP Robot and delete all settings. Continue?","wprobot") ?>')" class="button" type="submit" name="wpr5_uninstall" value="<?php _e("Uninstall WP Robot","wprobot") ?>" />
					
				</div>

				<?php $num = 1; foreach($options["options"] as $module => $moduledata) { if($moduledata["disabled"] != 1) {$num++; 
					if((!empty($moduledata["options"]["wai_rewrite_pw"]["name"]) && empty($moduledata["options"]["wai_rewrite_pw"]["value"])) || (!empty($moduledata["options"]["tbs_pw"]["name"]) && empty($moduledata["options"]["tbs_pw"]["value"])) || (!empty($moduledata["options"]["sc_pw"]["name"]) && empty($moduledata["options"]["sc_pw"]["value"])) || (!empty($moduledata["options"]["api_key"]["name"]) && empty($moduledata["options"]["api_key"]["value"])) || (!empty($moduledata["options"]["pw"]["name"]) && empty($moduledata["options"]["pw"]["value"])) || (!empty($moduledata["options"]["appid"]["name"]) && empty($moduledata["options"]["appid"]["value"])) || !empty($moduledata["options"]["public_key"]["name"]) && empty($moduledata["options"]["public_key"]["value"])) {
						$app_warn_text = '<p style="color:#0073AA;">'.__('To use this module in WP Robot you need to enter your API key or login credentials below.', 'wprobot').'</p>';
					} else {
						$app_warn_text = '';
					}				
				?>
					<div id="tabs-<?php echo $num;?>">

					<?php echo $app_warn_text; ?>
					
					<table class="form-table">
						<tbody>				
					
							<?php foreach($moduledata["options"] as $option => $data) {
								if($option != "title" && $option != "unique" && $option != "error" && $option != "unique_direct" && $option != "title_direct") {
									if($data["type"] == "text") { // Text Option ?> 
										<tr>
											<th scope="row"><label for="<?php echo esc_html($module."_".$option);?>"><?php echo esc_html($data["name"]);?></label></th>
											<td><input class="regular-text" type="text" name="<?php echo esc_html($module."_".$option);?>" value="<?php echo $data["value"]; ?>" />
												<!-- VERIFICATION BUTTON DISPLAY -->
												<?php if($data["verified"] === 0) {?>
													<!--
													<input class="button" type="submit" name="<?php echo $module."_verify";?>" value="<?php _e("Verify","wprobot"); ?>" <?php if(empty($data["value"])) {echo "disabled";} ?> />
													-->
													<?php if(!empty($wpr5_source_infos["sources"][$module]["signup"])) {?><a href="<?php echo $wpr5_source_infos["sources"][$module]["signup"]; ?>" target="_blank"><?php _e('Sign Up', 'wprobot'); ?></a><?php } ?>
												<?php } elseif($data["verified"] === 1) {?>
													<?php echo '<img style="margin-bottom: -3px;" src="/wp-content/plugins/CMSCommander/images/check.png" /> Verified'; ?>
												<?php } ?>

												<!-- EXPLANATION DISPLAY -->	
												<?php if(!empty($optionsexpl[$module]["options"][$option]["explanation"])) { ?>
													<span style="display: block; font-size: 85%;color: #666;"><?php 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '<a target="_blank" href="'.$optionsexpl[$module]["options"][$option]["link"].'">';} 
													echo $optionsexpl[$module]["options"][$option]["explanation"]; 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '</a>';} 
													?></span>
												<?php } ?>
											</td>	
										</tr>
									<?php } elseif($data["type"] == "select") { // Select Option ?>
										<tr>	
											<th scope="row"><label for="<?php echo esc_html($module."_".$option);?>"><?php echo esc_html($data["name"]);?></label></th>
											<td><select name="<?php echo esc_html($module."_".$option);?>">
												<?php foreach($data["values"] as $val => $name) { ?>
												<option value="<?php echo esc_attr($val);?>" <?php if($val == $data["value"]) {echo "selected";} ?>><?php echo esc_html($name); ?></option>
												<?php } ?>		
											</select></td>	
										</tr>
									<?php } elseif($data["type"] == "checkbox") { // checkbox Option ?>		
									
										<?php if($module == "amazon" && $option == "comments") {continue;} ?>
									
										<tr>	
											<th scope="row"><label for="<?php echo esc_attr($module."_".$option);?>"><?php echo esc_html($data["name"]);?></label></th>
											<td><input class="button" type="checkbox" name="<?php echo esc_attr($module."_".$option); ?>" value="1" <?php if(1 == $data["value"]) {echo "checked";} ?>/> <?php echo esc_html($data["expl"]); ?>
											
													<!-- EXPLANATION DISPLAY -->	
												<?php if(!empty($optionsexpl[$module]["options"][$option]["explanation"])) { ?>
													<span style="display: block; font-size: 85%;color: #666;"><?php 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '<a target="_blank" href="'.$optionsexpl[$module]["options"][$option]["link"].'">';} 
													echo $optionsexpl[$module]["options"][$option]["explanation"]; 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '</a>';} 
													?></span>
												<?php } ?>										
											
											</td>	
										</tr>									
									<?php } elseif($data["type"] == "textarea") { // textarea Option ?>		
										<tr>	
											<th scope="row"><label for="<?php echo esc_attr($module."_".$option);?>"><?php echo esc_html($data["name"]);?></label></th>
											
											<td>
													<!-- EXPLANATION DISPLAY -->	
												<?php if(!empty($optionsexpl[$module]["options"][$option]["explanation"])) { ?>
													<span style="display: block; font-size: 85%;color: #666;"><?php 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '<a target="_blank" href="'.$optionsexpl[$module]["options"][$option]["link"].'">';} 
													echo $optionsexpl[$module]["options"][$option]["explanation"]; 
													if(!empty($optionsexpl[$module]["options"][$option]["link"])) {echo '</a>';} 
													?></span><br/>
												<?php } ?>												
											
											<textarea cols="80" rows="3" name="<?php echo esc_attr($module."_".$option); ?>"><?php echo $data["value"]; ?></textarea>
											</td>	
										</tr>									
									<?php } ?>	
									
								<?php } ?>
							<?php } ?>
					
						</tbody>
					</table>					
					
					<?php if(is_array($moduledata["templates"])) { ?>
					<h3 style="margin-top: 20px;"><?php echo esc_html($moduledata["name"]); ?> <?php _e('Templates', 'wprobot'); ?></h3>		
					<table class="form-table">
						<tbody>	
						
						<?php foreach($moduledata["templates"] as $template => $templatedata) { ?>
							<tr>	
								<td>
									<?php _e("Template Name: ","wprobot") ?><input class="regular-text" type="text" name="<?php echo esc_attr($module."_".$template."_name");?>" value="<?php echo esc_attr($templatedata["name"]); ?>" />
									<!-- Copy Button -->
									<input class="button" type="submit" name="<?php echo esc_attr($module."_".$template);?>_copybutton" value="<?php _e("Copy","wprobot") ?>" />
									<!-- Delete Button -->
									<input class="button" type="submit" name="<?php echo esc_attr($module."_".$template);?>_deletebutton" value="<?php _e("Delete","wprobot") ?>" />									
									<br/>
									<textarea id="<?php echo esc_attr($module."_".$template);?>" class="template" name="<?php echo esc_attr($module."_".$template);?>" rows="8" cols="72"><?php echo $templatedata['content'];?></textarea>	
								</td>
								
								<td valign="top">
									<!-- Available Tags List -->
									<?php if(is_array($recorder[$module])) { ?>
									<div id="" style="overflow-y: scroll; height:220px;"><?php _e("Click to insert template tag: ","wprobot") ?><br/>
									<?php foreach($recorder[$module] as $tag) { ?>
									<input type="button" class="button" name="insert" value="{<?php echo $tag;?>}" onClick="addtxt('<?php echo esc_attr($module."_".$template);?>','{<?php echo $tag;?>}')"> 
									<?php } ?>	
									</div>
									<?php } ?>							
								</td>
							</tr>
						<?php } ?>
					
						</tbody>
					</table>	
					<?php } ?>
					
					<p class="submit"><input class="button-primary" type="submit" name="save_options" value="<?php _e("Save All Options","wprobot") ?>" /></p>
					
					</div>	
				<?php } } ?>

			</div>
		</div>
		</form>	
	</div>
	
		<?php
			if($disp_importer != 1 && function_exists("wpr_default_options")) {
				echo '<div class="updated"><h3>'.__('Import WP Robot 4 Settings', 'wprobot').'</h3><p>'.__('We have detected that WP Robot 4 is installed on your site. If you wish you can now import most of your module settings and API keys into WP Robot 5 by clicking the button below. Campaigns can unfortunately not be imported. If you do not wish to import your old settings simply activate one of the sources below instead.', 'wprobot').'</p><p><form method="post" name="wpr5_import" id="fluency-wpr5_import"><input type="submit" value="Import Settings" name="wpr4_import" class="button-primary">&nbsp;&nbsp; <input type="checkbox" value="1" name="import_history" id="import_history"> <label for="import_history">'.__('Import post history as well (to prevent duplicates)', 'wprobot').'</label></form></p></div>';		
			}		
		?>	
	
</div>
<?php	
}

?>