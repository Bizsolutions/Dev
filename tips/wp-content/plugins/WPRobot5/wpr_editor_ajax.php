<?php

function wpr5_post_editor_save_ajax() {
	global $wpr5_plugin_dir;

	$user_id = get_current_user_id();
	check_ajax_referer('post-editor-data-'.$user_id, 'wpr5-security');	
	
	$data = $_POST;

	if(isset($data['insert_module'])) {
	
		unset($data['security'], $data['action']);

		$keyword = $data['insert_topic'];	
		$module = $data['insert_module'];
		$start = $data['insert_start'];	
		if(empty($start)) {$start = 1;}		

		$limit = 6;
			
		@require_once($wpr5_plugin_dir."/info_sources_options.php");
		@require_once($wpr5_plugin_dir."/info_sources_details.php");
		@require_once($wpr5_plugin_dir."/api.class.php");		

		$api = new API_request;
		$contents = $api->api_content_bulk($keyword, array($module => array("count" => $limit, "start" => $start)), $data); 
	
		$return_arr = array();	
		if(empty($contents)) {
			$return_arr["error"] = "No content found.";
		} elseif(!empty($contents[$module]["error"])) {
			$return_arr["error"] = $contents[$module]["error"];   // -- ERROR ELEMENT if API request fails		
		} else {
			foreach($contents[$module] as $num => $content) {
				$return_arr["c".$num] = $content['content'];
			}		
		}

		echo json_encode($return_arr);
		exit;
	} else {
		echo json_encode(array("error" => "Module not found."));
		exit;		
	}
}