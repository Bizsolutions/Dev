<?php
/*********************************************************************************************************************************************/
/*                                                                 DISABLE PAGE                                                              */           
/*********************************************************************************************************************************************/

/*================================================================ 1. Functions =============================================================*/




/*================================================================== 2. Views ===============================================================*/

// Scripts
function wpr5_automation_page_print_scripts() {
	wp_enqueue_script('jquery');
}

// Header
function wpr5_automation_page_head() {
?>
	<style>
	.campaign-bottom {
		padding-top: 10px;	
	}
	
	.campaign-box {
		background: #fff;
		padding: 10px;
		margin-bottom: 10px;
	}	
	
	.campaign-top h3 {
		margin-top: 0;
		margin-bottom: 10px;
	}
	
	.campaign-top h3 span {	
		color: #666;
	}
	
	.campaign-top span.cname {
		display: inline-block;
		width: 35%;
	}	
	.campaign-top span.nxt {
		width: 13%;	
	}
	.campaign-top span.kws, .campaign-top span.posts, .campaign-top span.nxt {
		vertical-align: top;	
		display: inline-block;
		margin-right: 25px;
		font-size: 110%;
		padding-top: 3px;
	}	

	.campaign-top span.cname span.dlinkcont {
		opacity: 0.3;
		display: inline-block;	
		font-size: 110%;		
	}

	.campaign-top:hover span.cname span.dlinkcont {
		opacity: 1;
	}		
	
	.campaign-top span.cname a.dlinks {
		text-decoration: none;
	}
	
	.boxlink {
		display: inline-block;
		padding: 10px;
		color: #000;
		text-decoration: none;
	}
	.boxlink:hover {
		color: #000;
	}
	
	.boxlink-kws {
		background: #B2F3FF;
	}
	.boxlink-count {
		background: #B5FFB2;		
	}
	.boxlink-err {
		background: #FFB3B2;
	}	
	
	.ccheck {
		margin-top: 2px !important;
	}
	
	.mod_error {
		background: #FFB3B2;
		display:inline-block;
		padding: 0 5px;
		margin-top: 3px;
	}
	
	.postmodlink {
		text-decoration: none;
	}
	.postmodlink span.dashicons {
		font-size: 19px;
	}	
	
	.qm_link {
		cursor: help;
	}
	
	.open_details_link:hover {
		text-decoration: underline;
	}
	</style>
    <script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery('.open_details_link').live("click", function(e) {		
			e.preventDefault();
			
			var cid = jQuery(this).parent().parent().parent().attr("id").replace("ctop-", "");

			if(jQuery('#cdet-' + cid + ':visible').length < 1) {
				jQuery('#cdet-' + cid).show();
				jQuery('#det-' + cid).text("Hide Details");
			} else {
				jQuery('#cdet-' + cid).hide();
				jQuery('#det-' + cid).text("View Details");
			}
		});	
		
		jQuery("#checkall").click(function() {
			if (this.checked) {
				jQuery(".ccheck").prop('checked',true);
			} else {
				jQuery(".ccheck").prop('checked',false);
			}
		});		

		jQuery('a.load_full_log').click(function(e) {	
			e.preventDefault();
			var tit = jQuery(this);
			var cid = jQuery(this).attr('id').replace('polog-','');
			
			jQuery.get(ajaxurl, 'ajax=1&action=load_full_log&cid=' + cid , function(response) { console.log ( response );
				if(response.error != undefined && response.error != "") {
					tit.replaceWith( "<span>" + response.error + "</span>" );
				} else if(response.success != undefined && response.success != "") {				

					var lognum = response.success.length;
					if(lognum > 0) {

						for (x in response.success) {
							thislog = response.success[x]; 
					
							if(thislog.module_errors != undefined && thislog.module_errors != "") {
								var moduleerrors = '<ul style="margin-left: 35px;">';
								for (y in thislog.module_errors) {
									thiserr = thislog.module_errors[y];
									moduleerrors += '<li><span class="mod_error">' + thiserr.mod + ' Error:</span> ' + thiserr.mes + "</li>";
								}
								moduleerrors += '</ul>';
							} else {
								var moduleerrors = '';
							}
							
							if(thislog.link != undefined && thislog.link != "") {
								jQuery("#logsfor-" + cid).append('<li>' + thislog.time + ' - <a href="' + thislog.link + '">' + thislog.title + '</a> (Keyword: ' + thislog.keyword + ')' + moduleerrors +' </li>');
							} else {
								jQuery("#logsfor-" + cid).append('<li>' + thislog.time + ' - ' + thislog.title + ' (Keyword: ' + thislog.keyword + ')' + moduleerrors +' </li>');
							}
						}
						tit.replaceWith( "" );
					} else {
						tit.replaceWith( "<span>No more log entries found.</span>" );
					}				
				
				} else {
					alert("Unknown error");				
				}	
			}, "json");
		
			return false;
		}); 		
	});
	</script>

<?php		
}

add_action('wp_ajax_load_full_log', 'wpr5_load_full_log');
function wpr5_load_full_log() {
	
	$cid = $_GET['cid'];
	
	if(!isset($cid)) {
		echo json_encode(array("error" => "No campaign specified."));
		exit;		
	}
	
	global $wpdb, $wpr5_table_posted;

	$logs = $wpdb->get_results("SELECT * FROM $wpr5_table_posted WHERE cid = '".$cid."' ORDER BY time DESC LIMIT 100, 10", ARRAY_A);

	if(is_array($logs) && !empty($logs)) {
		$return_logs = array();
		foreach($logs as $log) {
			$ltitle = $log["title"];
			$ltime = date("d.n.y H:i", $log["time"]);
			if(!empty($log["pid"])) {
				$llink = get_permalink($log["pid"]);
			}
			
			if(!empty($log["keyword"])) {$lkw = $log["keyword"];}
			
			if(!empty($log["module_errors"])) {
				$merr = unserialize($log["module_errors"]);
				if(is_array($merr)) {
					$larr = array();
					foreach($merr as $mod => $err) {
						$larr[] = array("mod" => ucwords($mod), "mes" => $err);	
					}
				}
			}
			$return_logs[] = array("title" => $ltitle, "time" => $ltime, "link" => $llink, "keyword" => $lkw, "module_errors" => $larr, );
		}	
	
		echo json_encode(array("success" => $return_logs));
		exit;			
	} else {
		echo json_encode(array("error" => "No further log entries found."));
		exit;		
	}
}

// Page Body
function wpr5_automation_page() {
	global $wpr5_source_infos, $optionsexpl, $wpdb, $wpr5_table_posted;

	wpr5_check_license_key();
	
	$campaigns = get_option("wpr5_campaigns");
	

	if($_GET["shopt"] == 1) {echo "<pre>";print_r(get_option( 'wpr5_options' ));echo "</pre>";}
	
	$is_trial = get_option("wpr5_is_trial");

	if($is_trial == true) {
		echo '<div class="updated below-h2"><p>'.__('You are using the <strong>trial version</strong> of WP Robot 5 which allows you to test all features for 14 days. Ready to upgrade to the <a href="http://wprobot.net/order" target="_blank">full version</a>? If so simply <a href="?page=wpr5-options&endtrial=1" onclick="return confirm("This will end your trial period and allow you to upgrade to the full version. You should not continue unless you already ordered the full version or intend to do so. Continue?">click here</a> and afterwards you can enter your full version license key.', 'wprobot').'</p></div>';			
	}	
	
	if($_GET["postnow"] == 1) {
		wpr5_autopost_function(1);
	}
	
	if(empty($_POST) && !empty($_GET["del"]) && isset($_GET["cid"])) {	
		if (isset($_GET['wpr5_del_nonce']) && wp_verify_nonce($_GET['wpr5_del_nonce'], 'wpr5_del')) {
			$cids = $_GET["cid"];

			unset($campaigns[$cids]);
			update_option("wpr5_campaigns", $campaigns);
			
			$results = $wpdb->query("DELETE FROM " . $wpr5_table_posted . " WHERE `cid` = '".$cids."'");					

			echo '<div class="updated"><p>'.__('The campaign has been deleted.', 'wprobot').'</p></div>';	
		}
	}	
	
	if(empty($_POST) && !empty($_GET["run"]) && isset($_GET["cid"])) {	
		if (isset($_GET['wpr5_run_nonce']) && wp_verify_nonce($_GET['wpr5_run_nonce'], 'wpr5_run')) {
			$cids = $_GET["cid"];
			
			if($_GET["debug"] == 1) {$dbg = 1;} else {$dbg = 0;}
			
			$result = wpr5_run_campaign($cids, $dbg);
			
			if(is_array($result) && !empty($result["error"])) {
				echo '<div class="updated error"><p>'.$result["error"].'</p></div>';			
			} elseif(is_array($result) && !empty($result["pid"])) {
				echo '<div class="updated"><p>'.__('Article has been created successfully.', 'wprobot').' <a href="'.site_url( "?p=". $result["pid"] ).'">'.__('View article.', 'wprobot').'</a></p></div>';		
			} else {
				echo '<div class="updated error"><p>'.__('Unknown error: Campaign could not be processed.', 'wprobot').'</p></div>';				
			}
		}
	}

	if(empty($_POST) && !empty($_GET["changestatus"]) && isset($_GET["cid"])) {	
		if (isset($_GET['wpr5_status_nonce']) && wp_verify_nonce($_GET['wpr5_status_nonce'], 'wpr5_status')) {
			$cids = $_GET["cid"];
			
			if($campaigns[$cids]["paused"] == 1) {
				$campaigns[$cids]["paused"] = 0;
				update_option("wpr5_campaigns", $campaigns);
				echo '<div class="updated"><p>'.__('The campaign has been unpaused and autoposting will continue.', 'wprobot').'</p></div>';	
			} else {
				$campaigns[$cids]["paused"] = 1;
				update_option("wpr5_campaigns", $campaigns);
				echo '<div class="updated"><p>'.__('The campaign has been paused.', 'wprobot').'</p></div>';				
			}
		}
	}
	
	if($_POST['pause']) {
		if($_POST["delete"] == "" || $_POST["delete"] == 0 || $_POST["delete"] == null) {
			echo '<div class="updated"><p>'.__('Please select at least one campaign!', 'wprobot').'</p></div>';				
		} else {						
			foreach ($_POST['delete']  as $key => $value) {
				$campaigns[$value]["paused"] = 1;
			}	
			update_option("wpr5_campaigns", $campaigns);
			echo '<div class="updated"><p>'.__('Campaigns have been paused.', 'wprobot').'</p></div>';				
		}	
	}

	if($_POST['continue']) {
		if($_POST["delete"] == "" || $_POST["delete"] == 0 || $_POST["delete"] == null) {
			echo '<div class="updated"><p>'.__('Please select at least one campaign!', 'wprobot').'</p></div>';				
		} else {						
			foreach ($_POST['delete']  as $key => $value) {
				$campaigns[$value]["paused"] = 0;
			}
			update_option("wpr5_campaigns", $campaigns);
			echo '<div class="updated"><p>'.__('Campaigns have been continued.', 'wprobot').'</p></div>';				
		}		
	}

	if($_POST['deleteall']) {
		if($_POST["delete"] == "" || $_POST["delete"] == 0 || $_POST["delete"] == null) {
			echo '<div class="updated"><p>Please select at least one campaign!</p></div>';				
		} else {
			foreach ($_POST['delete']  as $key => $value) {
				unset($campaigns[$value]);
				$results = $wpdb->query("DELETE FROM " . $wpr5_table_posted . " WHERE `cid` = '".$value."'");					
			}
			update_option("wpr5_campaigns", $campaigns);
			echo '<div class="updated"><p>'.__('Campaigns have been deleted.', 'wprobot').'</p></div>';					
		}
	}

	if($_POST['wpr_runnow']) {
		if($_POST["delete"] == "" || $_POST["delete"] == 0 || $_POST["delete"] == null) {
			echo '<div class="updated"><p>'.__('Please select at least one campaign!', 'wprobot').'</p></div>';				
		} else {
			$posted = 0;
			$skipped = 0;
			$bulk = $_POST["wpr_bulk"];
			$delete = $_POST["delete"];

			if($bulk == "" || $bulk == 0 || $bulk == null) {$bulk = 1;}

			for($i=0; $i < $bulk; $i++) { 
				foreach ($_POST['delete']  as $key => $value) {
				
					if($_GET["debug"] == 1) {$dbg = 1;} else {$dbg = 0;}
			
					$result = wpr5_run_campaign($value, $dbg);
					if(is_array($result) && !empty($result["pid"])) {$posted++;} else {$skipped++;}
				}	
			}
			if($posted > 0) {

				echo '<div class="updated"><p>';
				printf(__('%1$s posts have been created successfully %2$s', 'wprobot'), $posted, $sktxt);
				if($skipped > 0) {printf(__(' and %1$s posts have been skipped. Please see the <a href="?page=wpr-log">error log</a> for details.', 'wprobot'), $skipped);} else {$sktxt = '';}					
				echo '</p></div>';	
			} else {
				echo '<div class="updated"><p>';
				printf(__('Error: %1$s posts could not be created. Please see the error log for details.', 'wprobot'), $skipped);
				echo '</p></div>';						
			}
		}
	}	
	
?>
<div class="wrap">
	<form id="campaigns" method="post">	
	<div id="wprobot" class="icon32"></div>
	
	<h1 class="nav-tab-wrapper">
	<span style="display:inline-block;float:left;margin-top: 6px;">WP Robot&nbsp;&nbsp;&nbsp;</span>
	<a href="?page=wpr5-automation" class="nav-tab nav-tab-active"><?php _e('Campaigns', 'wprobot'); ?></a>
	<a href="?page=wpr5-create-campaign" class="nav-tab"><?php _e('Create Campaign', 'wprobot'); ?></a>
	</h1>	

	<div style="height: 10px;"></div>
	
	<?php if(is_array($campaigns) && !empty($campaigns)) {
		foreach($campaigns as $cid => $campaign) { ?>
		<div class="campaign-box" id="c-<?php echo $cid; ?>">
			<div class="campaign-top" id="ctop-<?php echo $cid; ?>">
				<span class="cname">
					<h3><input class="ccheck" type="checkbox" name="delete[]" value="<?php echo $cid; ?>" id="check-<?php echo $cid; ?>"><label for="check-<?php echo $cid; ?>"><?php echo $campaign["main"]["name"]; ?></label></h3>
					<span class="dlinkcont">
						<a id="det-<?php echo $cid; ?>" class="open_details_link dlinks forcid-<?php echo $cid; ?>" href="#">View Details</a> | 
						<a class="dlinks" href="<?php print wp_nonce_url("?page=wpr5-automation&run=1&cid=".$cid, "wpr5_run", "wpr5_run_nonce"); ?>">Run Now</a> | 					
						<a class="edit_link dlinks" href="<?php print wp_nonce_url("?page=wpr5-create-campaign&edit=1&cid=".$cid, "wpr5_edit", "wpr5_edit_nonce"); ?>">Edit</a> | 
						<a class="copy_link dlinks" href="<?php print wp_nonce_url("?page=wpr5-create-campaign&edit=1&copy=1&cid=".$cid, "wpr5_edit", "wpr5_edit_nonce"); ?>">Copy</a> | 
						<?php if($campaign["paused"] == 1) { ?>
							<a class="edit_link dlinks" href="<?php print wp_nonce_url("?page=wpr5-automation&changestatus=1&cid=".$cid, "wpr5_status", "wpr5_status_nonce"); ?>">Continue</a> | 
						<?php } else { ?>
							<a class="edit_link dlinks" href="<?php print wp_nonce_url("?page=wpr5-automation&changestatus=1&cid=".$cid, "wpr5_status", "wpr5_status_nonce"); ?>">Pause</a> | 
						<?php } ?>
						<a onclick="return confirm('<?php _e('Are you sure you want to delete this campaign?', 'wprobot'); ?>')" id="del-<?php echo $cid; ?>" class="delete_link dlinks" href="<?php print wp_nonce_url("?page=wpr5-automation&del=1&cid=".$cid, "wpr5_del", "wpr5_del_nonce"); ?>">Delete</a>					
					</span>
				</span>
				
				<span class="nxt"><?php _e('Next Run:', 'wprobot'); ?><br/>
				<?php if($campaign["paused"] == 1) { ?>
				<strong><?php _e('Paused', 'wprobot'); ?></strong>
				<?php } else { 
					// calc next post time
					if($campaign["main"]["period"] == "hours") {
						$next = $campaign["last_run"] + $campaign["main"]["interval"] * 3600;
					} elseif($campaign["main"]["period"] == "minutes") {	
						$next = $campaign["last_run"] + $campaign["main"]["interval"] * 60;					
					} else {
						$next = $campaign["last_run"] + $campaign["main"]["interval"] * 3600 * 24;
					}					
					if(empty($campaign["last_run"]) || $next < time()) {
						$nextrun = "now";
					} else {
						$nextrun = date("d.m.y H:i", $next);
					}
				?>
				<strong><?php echo $nextrun; ?></strong>
				<?php } ?>
				</span>		
				
				<span>
					<span class="kws">
						<?php $feedcount = count($campaign["feeds"]);$kwcount = count($campaign["keywords"]);
						if($feedcount > 0) { ?>
							<a class="boxlink boxlink-kws forcid-<?php echo $cid; ?>" href="#"><?php echo $feedcount; _e(' Feeds', 'wprobot'); ?></a>					
						<?php } else { 
							$kwl = "";for($i=0; $i < 10; $i++) {if(!empty($campaign["keywords"][$i]["name"])) {$kwl .= $campaign["keywords"][$i]["name"] . ", ";}}
						?>
							<a title="<?php echo rtrim($kwl, ", "); ?>" class="boxlink boxlink-kws qm_link forcid-<?php echo $cid; ?>" href="#"><?php echo $kwcount; _e(' Keywords', 'wprobot'); ?></a>					
						<?php } ?>

					</span>
		
					<span class="posts">
					<?php $ccount = $campaign["count"];if(empty($campaign["count"])) {$ccount = 0;} ?>
						<a class="boxlink boxlink-count open_details_link forcid-<?php echo $cid; ?>" href="#"><?php echo $ccount . " " .ucwords($campaign["main"]["post_type"]); _e('s created', 'wprobot'); ?></a>					
					</span>		

					<?php $error_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpr5_table_posted WHERE cid = '".$cid."' AND (module_errors != '' OR title LIKE 'Rewriting failed%' OR title LIKE 'Translation failed%')" );
					if($error_count > 0) { ?>
					<span class="posts">
						<a class="boxlink boxlink-err open_details_link forcid-<?php echo $cid; ?>" href="#"><?php echo $error_count; _e(' Errors', 'wprobot'); ?></a>					
					</span>					
					<?php } ?>
				</span>
				
			</div>
			<div id="cdet-<?php echo $cid; ?>" class="campaign-bottom" style="display:none;">
			
			<?php

			$logs = $wpdb->get_results("SELECT * FROM $wpr5_table_posted WHERE cid = '".$cid."' ORDER BY time DESC LIMIT 10", ARRAY_A);
			echo '<strong>Post Log</strong><br/><ul id="logsfor-'.$cid.'">';
			foreach($logs as $log) {
				echo '<li>' . date("d.n.y H:i", $log["time"]) . " - ";
				if(!empty($log["pid"])) {
					echo '<a href="'.get_permalink($log["pid"]).'">'.$log["title"].'</a> <a class="postmodlink" title="Edit post" href="'.get_edit_post_link( $log["pid"] ).'"><span class="dashicons dashicons-edit"></span></a> <a class="postmodlink" title="Delete post" href="'.get_delete_post_link( $log["pid"] ).'"><span class="dashicons dashicons-trash"></span></a>';
				} else {
					echo $log["title"];
				}
				
				if($log["template"] != "" && is_numeric($log["template"])) {$tttll = $log["template"] + 1;$ttpl = ', Template: '.$tttll;} else {$ttpl = "";}
				if(!empty($log["keyword"])) {echo ' (Keyword: '.$log["keyword"].$ttpl.')';}
				
				if(!empty($log["module_errors"])) {
					$merr = unserialize($log["module_errors"]);
					if(is_array($merr)) {
						echo '<ul style="margin-left: 35px;">';
						foreach($merr as $mod => $err) {
							echo '<li><span class="mod_error">'.ucwords($mod)." Error:</span> " . $err . "</li>";
						}
						echo "</ul>";
					}
				}
				echo '</li>';
			}
			echo "</ul>";
			?>
			<a href="#" id="polog-<?php echo $cid; ?>" class="load_full_log"><?php _e('Load full log', 'cmsc'); ?> &rarr;</a>
			
			</div>
		</div>
	<?php } ?>
	
		<div style="margin-top: 20px;">
			<span style="display: inline-block;padding: 0 30px 0 10px;vertical-align:middle;">
				<input type="checkbox" id="checkall" value="0" name="" class="ccheck"> <label for="checkall"><?php _e('Select all campaigns', 'wprobot'); ?></label>
			</span>
			
			<span style="display: inline-block;padding: 0 30px 0 0;vertical-align:middle;">
				<input class="button-secondary" type="submit" onclick="return confirm('<?php _e("Are you sure you want to delete all selected campaigns?","wprobot") ?>')" name="deleteall" value="<?php _e("Delete Selected","wprobot") ?>"/>
				<input class="button-secondary" type="submit" name="pause" value="<?php _e("Pause Selected","wprobot") ?>"/>
				<input class="button-secondary" type="submit" name="continue" value="<?php _e("Continue Selected","wprobot") ?>"/>
			</span>
			
			<span style="display: inline-block;padding: 0 30px 0 0;vertical-align:middle;">
				<?php _e('Bulk create', 'cmsc'); ?> <input size="2" style="background:#fff;" name="wpr_bulk" type="text" value="1"/> <?php _e('articles', 'cmsc'); ?>. <input style="margin: 2px;" class="button-secondary" type="submit" name="wpr_runnow" value="<?php _e("Post to Selected","wprobot") ?>"/>	
			</span>			
		</div>
	<?php } else { ?>
		<p><?php _e('No campaigns have been created yet.', 'wprobot'); ?></p>
	<?php } ?>
	</form>
</div>
<?php
}
?>