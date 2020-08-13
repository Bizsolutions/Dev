<?php

define( 'WPAUTOC_PINTEREST_PER_PAGE', 20 );
define( 'WPAUTOC_PINTEREST_MAX_LOOPS', 3 );

/*
	Type: WPAUTOC_CONTENT_PINTEREST (7)
	Unique id: video id
*/

function wpautoc_content_type_pinterest( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'pinterest' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import Pinterest pins, you need to enter your details first.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-social-tab').'">Click here</a> to go to the plugin settings and enter your Pinterest details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Pinterest' );

	// wpautoc_ifield_text( $settings, 'users', 'Users', 'wpautoc_content['.$num.'][settings][users]', false, 'User names (optional)', 'Optional; if you enter a list of usernames, only posts from these users will be imported. Usernames must be separated by comma. Ex: username1,username2,username3' );

	wpautoc_ifield_checkbox( $settings, 'text_only', 'Show Text/Image Only', 'wpautoc_content['.$num.'][settings][text_only]', false, 'If checked, it will only display the pin contents (Image + Text), not the Pinterest embed' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many pinterest items will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the pinterest item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_pinterest( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$users = isset( $settings->users ) && !empty( $settings->users ) ? $settings->users : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_PINTEREST_MAX_LOOPS )  ) {
		$imported = wpautoc_content_pinterest_search( $page, WPAUTOC_PINTEREST_PER_PAGE, $keyword, $users, $campaign_id, $settings, $num_posts, $num_items );
		// var_dump($imported);
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	return $num_imported;
}

function wpautoc_content_pinterest_search( $page = 1, $per_page = 50, $keyword, $users = false, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_pinterest_search( $keyword, $users, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	$items = array();
	$imported = 0;
	if( $results ) {
		if( $results ) {
		foreach( $results as $result ) {
			$item_id = $result['id'];
			// var_dump($item_id);
			if( !wpautoc_pinterest_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_pinterest_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

				// wpautoc_do_import_pinterest_item( $campaign_id, $result, $item_id, $settings );
				// $imported++;
				// if( $imported >= $num_posts )
					// return $imported;
			}
		}
	}
		return $imported;
	}
	else
		return -1;

}


function wpautoc_content_do_pinterest_search( $keyword,  $users = false, $page = 1, $per_page = 60 ) {
		$pinterest_settings = wpautoc_get_settings( array( 'social', 'pinterest' ) );

		// var_dump($pinterest_settings);
		/*if ( ! isset( $pinterest_settings['app_id'] ) || empty( $pinterest_settings['app_id'] )
			|| ! isset( $pinterest_settings['app_secret'] ) || empty( $pinterest_settings['app_secret'] )
	)
			return false;*/

	$feed_uri = "https://www.pinterest.com/resource/BaseSearchResource/get/?source_url=%2Fsearch%2Fpins%2F%3Fq%3D" . urlencode(trim($keyword)) . "&data=%7B%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22" . urlencode(trim($keyword)). "%22%7D%2C%22context%22%3A%7B%7D%2C%22module%22%3A%7B%22name%22%3A%22SearchPage%22%2C%22options%22%3A%7B%22restrict%22%3Anull%2C%22scope%22%3A%22pins%22%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22" . urlencode(trim($keyword)) . "%22%7D%7D%2C%22render_type%22%3A1%2C%22error_strategy%22%3A0%7D&module_path=App()%3EHeader()%3ESearchForm()%3ETypeaheadField(enable_recent_queries%3Dtrue%2C+support_guided_search%3Dtrue%2C+resource_name%3DAdvancedTypeaheadResource%2C+name%3Dq%2C+tags%3Dautocomplete%2C+class_name%3DbuttonOnRight%2C+type%3Dtokenized%2C+prefetch_on_focus%3Dtrue%2C+value%3D%22%22%2C+input_log_element_type%3D227%2C+hide_tokens_on_focus%3Dundefined%2C+support_advanced_typeahead%3Dfalse%2C+view_type%3Dguided%2C+populate_on_result_highlight%3Dtrue%2C+search_delay%3D0%2C+search_on_focus%3Dtrue%2C+placeholder%3DDiscover%2C+show_remove_all%3Dtrue)&_=1430685210358";
            $ch               = curl_init();

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.pinterest.com/','X-NEW-APP: 1','X-Requested-With: XMLHttpRequest'));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_ENCODING , "");
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_URL, $feed_uri);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$exec = curl_exec($ch);
	curl_close($ch);
// var_dump($exec);
	if (stristr($exec, 'request_identifier') === FALSE) {
	    /*pinterestomatic_log_to_file('Unrecognized API response: ' . $exec . ' URI: ' . $feed_uri);
	    if($auto == 1)
	    {
	        pinterestomatic_clearFromList($param);
	    }*/
	    return 'fail';
	}
	$items = json_decode($exec);
	// print_r($items);
    $items = $items->resource_data_cache[0]->data->results;
// var_dump($items);return;
    $ret = array();
    if( $items ) {
    	foreach( $items as $item ) {
    		// var_dump($item);
    		$res = array();
    		$res['id'] = $item->id;
    		$res['title'] = wpautoc_short( $item->description, 140, true );
    		$content = $item->description_html;
    		    $img_found = false;

    		if ($content != '') {
    		    $content = htmlspecialchars_decode($content, ENT_QUOTES);
    		    $content = preg_replace('/#(\w+)/u', ' <a href="https://www.pinterest.com/explore/tags/$1">#$1</a>', $content);
    		}
    		$res['content'] = $content;
    		$res['url'] = 'https://pinterest.com/pin/'.$item->id;
    		$all_images = (Array)$item->images;
    		$pinned_img = $all_images['orig'];
    		if(isset($pinned_img->url))
    		{
    		    $get_img = $pinned_img->url;
    		    $img_found = true;
    		}
    		if( !$img_found ) {
    			$res['image_url'] = false;
    		}
    		$res['image_url'] = $get_img;
    		$ret[] = $res;
    	}
    }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_pinterest_items( $campaign_id, $items, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $items[0]['content']['title'] );
	$content = '';
	$item_ids = array();
	foreach( $items as $item ) {

		if( isset( $settings->text_only ) && $settings->text_only ) {
			// embed the Image + Text
			$content .= '<p style="text-align:center"><img src="'.$item['content']['image_url'].'" /></p>';
			$content .= '<p>'.$item['content']['content'].'</p>';
		}
		else
			$content .= '<p><a data-pin-do="embedPin" data-pin-lang="en" data-pin-width="large" href="https://www.pinterest.com/pin/'.$item['content']['id'].'/"></a></p>';
		$item_ids[] = $item['id'];
	}
	// $content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}
	}

	// if( isset( $settings->add_link ) && $settings->add_link ) {
	// 	// var_dump($product);
	// 	$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
	// 	if( $url )
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-pinterest-btn">Read More...</a></p>';
	// }

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( /*isset( $settings->thumbnail ) && $settings->thumbnail*/ 1 ) {
		foreach( $items as $item ) {
			if( isset( $item['content']['image_url'] ) && !empty( $item['content']['image_url'] ) ) {
				$thumbnail = $item['content']['image_url'];
				break;
			}
		}
	}

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_PINTEREST, $thumbnail );

	return $item_ids;
}

function wpautoc_pinterest_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_PINTEREST, $campaign_id );
}

// function wpautoc_pinterest_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>