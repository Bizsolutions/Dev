<?php

if(is_admin()){
	if(is_multisite()) {
		if(preg_match("~/wp-admin/post\.php$~", $_SERVER['SCRIPT_NAME']) || preg_match("~/wp-admin/post-new\.php$~", $_SERVER['SCRIPT_NAME'])){
			add_action('admin_enqueue_scripts', 'wpr5_editor_scripts');
		}		
	
		if(preg_match("~/wp-admin/admin-ajax\.php$~", $_SERVER['SCRIPT_NAME'])) {
			require_once('wpr_editor_ajax.php');
			add_action('wp_ajax_wpr5_post_editor_data_save', 'wpr5_post_editor_save_ajax');
		}		

	} else {
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php'){	
			add_action('admin_enqueue_scripts', 'wpr5_editor_scripts');
		}	
		
		if($pagenow == 'admin-ajax.php'){
			require_once('wpr_editor_ajax.php');
			add_action('wp_ajax_wpr5_post_editor_data_save', 'wpr5_post_editor_save_ajax');
		}		
	}
}

add_action( 'add_meta_boxes', 'wpr5_editor_metabox' );
function wpr5_editor_metabox() {
	$screens = get_post_types();
	foreach($screens as $screen) {
		add_meta_box('wpr5_editor_section',__( 'WP Robot 5', 'wprobot5' ), 'wpr5_editor_metabox_content', $screen);
	}
}

function wpr5_editor_scripts() {
	//wp_register_style( 'wpinject-editor-css', plugins_url( 'wpdf-editor-styles.css', __FILE__ ) );
	//wp_enqueue_style( 'wpinject-editor-css' );	

	wp_register_script( 'wpr-editor-js', plugins_url( 'wpr_editor_js.js', __FILE__ ) );
	wp_enqueue_script( 'wpr-editor-js' );		
}

function wpr5_editor_metabox_content($post) {
	global $wpr5_plugin_url;
	$user_id = get_current_user_id();
	
	$options = wpr5_get_options();

	?>

	<div id="insert-content-form">
		<div id="error_message" class="cmsc-ajax-error"></div>
	
		<div id="loading" style="display:none;text-align: center; padding: 50px 10px;">
			<?php _e('Please wait while the content is being retreived from the source you selected.', 'wprobot'); ?><br/>
			<img src="<?php echo $wpr5_plugin_url; ?>/images/ajax-loader.gif" style="margin-top: 40px;" />
		</div>
		<div id="insert-form">	
			<form id="popup_form">
			<fieldset>
				<input type="hidden" name="wpr5-action" value="wpr5_post_editor_data_save" />
				<input type="hidden" name="wpr5-security" value="<?php echo wp_create_nonce('post-editor-data-'.$user_id); ?>" />			
			
				<label for="insert_topic"><?php _e('Topic', 'wprobot'); ?>:</label>
				<input type="text" name="insert_topic" id="insert_topic" class="text ui-widget-content ui-corner-all" />
				<label for="insert_module"><?php _e('Source', 'wprobot'); ?>:</label>
				<select class="optionselector" name="insert_module" id="insert_module">
					<option value=""><?php _e('(choose one)', 'wprobot'); ?></option>			
					<?php $modulearray = $options["options"]; foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 && $moduledata["display"] != "no") { ?>
					<option value="<?php echo $module; ?>"><?php echo $moduledata["name"]; ?></option>
					<?php } } ?>
				</select>
				<label for="insert_start"><?php _e('Start', 'wprobot'); ?>:</label>
				<input size="3" value="1" type="text" name="insert_start" id="insert_start" class="small-text ui-widget-content ui-corner-all" />				
				<input type="submit" value="Search Content" class="button" id="wpr-get-content-button" name="wpr-get-content-button">
			

			<?php $modulearray = $options["options"];$num = 0; if(is_array($modulearray)) {foreach($modulearray as $module => $moduledata) { if($moduledata["disabled"] != 1 && $moduledata["display"] != "no") {$num++; ?>			
				<div style="display:none;" id="<?php echo $module;?>" class="module-settings-box">	
				
					<label for="insert_template"><?php _e('Template', 'wprobot'); ?>:</label>	
					<select name="insert_template" id="insert_template">		
						<?php foreach($moduledata["templates"] as $template => $templatedata) { ?>
						<option value="<?php echo $template;?>"><?php echo $templatedata["name"]; ?></option>
						<?php } ?>
					</select>	
				
					<div style="border-top: 1px dotted #ccc;margin-top: 15px;padding-top: 15px;">
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
										<option value="<?php echo $val;?>" <?php if($val == $data["value"]) {echo "selected";} ?>><?php echo $name; ?></option>
										<?php } ?>		
									</select><br/>	
							<?php } elseif($data["type"] == "checkbox") { // checkbox Option ?>		
								<label for="<?php echo $module."_".$option;?>"><?php echo $data["name"];?></label>
								<input class="button" type="checkbox" name="<?php echo $module."_".$option; ?>" value="1" <?php if(1 == $data["value"] && $option != "comments") {echo "checked";} ?>/>	<br/>						
							<?php } ?>	
							
						<?php } ?>
					<?php } ?>	
					</div>
					
				</div>
			<?php } } } else { ?>	
				<p><strong><?php _e('Please choose and activate at least one content source on the "Choose Sources" page first.', 'wprobot'); ?></strong></p>
			<?php } ?>		
			
			</fieldset>
			</form>
		</div>		
	</div>
	<div id="insert-content-form-results" style="display:none;margin-top: 6px;padding-top:6px;border-top:1px solid #e5e5e5;">
	
		<div style="padding: 5px 0;" id="buttons_top">
			<div style="float:left;padding: 5px; background: #f1f1f1; border: 1px solid #ccc;margin-right: 5px;"><input onclick="toggleChecked('insert-content-cb', this.checked)" type="checkbox" name="insert-content-check-all" id="insert-content-check-all"><label for="insert-content-check-all"> <?php _e("Select All.","wprobot") ?></label></div>
			<input type="submit" value="Insert Selected" class="button" id="wpr-insert-content-button" name="wpr-insert-content-button">
			<input type="submit" value="Clear" class="button" id="wpr-clear-content-button" name="wpr-clear-content-button">
		</div>	
	
		<div style="clear:both;"></div>	
	
		<div class="insert-content-container" id="cont0">
			<div class="insert-content-label" style="clear: both;margin: 10px 0; padding: 5px; background: #f1f1f1; border: 1px solid #ccc;">  
				<input class="insert-content-cb" type="checkbox" id="cb-0" name="cb-0" value="1">
				<label id="cl-0" for="cb-0"><?php _e('Check to insert content below:', 'wprobot'); ?></label>	
			</div>		
				
			<div id="c0">
			</div>		
		</div>	

	</div>

	<?php 

}