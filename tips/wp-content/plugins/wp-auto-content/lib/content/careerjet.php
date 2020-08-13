<?php

define( 'WPAUTOC_CAREERJET_PER_PAGE', 20 );
define( 'WPAUTOC_CAREERJET_MAX_LOOPS', 24 );

/*
	Type: WPAUTOC_CONTENT_CAREERJET (7)
	Unique id: video id
*/

function wpautoc_content_type_careerjet( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Careerjet (ex: php programmer)' );

	wpautoc_ifield_text( $settings, 'location', 'Location (optional)', 'wpautoc_content['.$num.'][settings][location]', false, 'Job location', 'Optional; you can enter a country/city name. Leave blank to search worldwide' );


	// wpautoc_ifield_checkbox( $settings, 'text_only', 'Show Text Only', 'wpautoc_content['.$num.'][settings][text_only]', false, 'If checked, it will only display the tweet contents (not the careerjet card)' );

	if( empty( $settings->num_items ) )
		$settings->num_items = 1;
	wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many careerjet items will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'add_link', 'Add Link to Source', 'wpautoc_content['.$num.'][settings][add_link]', false, 'If checked, it will add a link to the original source of the careerjet item' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_careerjet( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	$location = isset( $settings->location ) && !empty( $settings->location ) ? $settings->location : false;
	$num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $keyword ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts ;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_CAREERJET_MAX_LOOPS )  ) {
		$imported = wpautoc_content_careerjet_search( $page, WPAUTOC_CAREERJET_PER_PAGE, $keyword, $location, $campaign_id, $settings, $num_posts, $num_items );
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

function wpautoc_content_careerjet_search( $page = 1, $per_page = 50, $keyword, $location = false, $campaign_id, $settings, $num_posts, $num_items = 1 ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_careerjet_search( $keyword, $location, $page, $per_page );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	$items = array();
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$item_id = $result['id'];
			// var_dump($item_id);
			if( !wpautoc_careerjet_already_imported( $item_id, $campaign_id ) ) {
				$el = array( 'id' => $item_id, 'content' => $result );
				$items[] = $el;
				if( count( $items ) >= $num_items ) {
					wpautoc_do_import_careerjet_items( $campaign_id, $items, $settings );
					$items = array();
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

				// wpautoc_do_import_careerjet_item( $campaign_id, $result, $item_id, $settings );
				// $imported++;
				// if( $imported >= $num_posts )
					// return $imported;
			}
		}
		return $imported;
	}
	else
		return -1;

}


function wpautoc_content_do_careerjet_search( $keyword,  $location = false, $page = 1, $per_page = 60 ) {
	require_once WPAUTOC_DIR.'/lib/libs/careerjet/Careerjet_API.php';

	$cjapi = new Careerjet_API('en_US') ;
	//$page = 1 ; # Or from parameters.

	// $result = $api->search(array(
	//   'keywords' => 'php developer',
	//   'location' => 'London',
	//   'page' => $page ,
	//   'affid' => '678bdee048',
	// ));
	$cj_settings = wpautoc_get_settings( array( 'affiliate', 'careerjet' ) );
	// var_dump($udemy_settings);
	$affiliate_id = ( isset( $cj_settings['affid'] ) && !empty( $cj_settings['affid'] ) ) ? $cj_settings['affid'] : 'b561df7f920343758d1b5be2eb17b49b';
  $search = array( 'keywords' => $keyword, 'page' => $page, 'pagesize' => $per_page, 'affid'    => $affiliate_id );
  if( $location )
  	$search['location'] = $location;
// var_dump($search);
  // Then call api methods (see methods doc for details)
  $result = $cjapi->search( $search );
// var_dump($result);
  $ret = array();
  if ( $result->type == 'JOBS' ) {
  	// var_dump($result);
      // echo "Got ".$result->hits." jobs: \n\n" ;
      $jobs = $result->jobs ;
// var_dump($jobs);return;
      foreach( $jobs as &$item ){
          // echo " URL: ".$job->url."\n" ;
          // echo " TITLE: ".$job->title."\n" ;
          // echo " LOC:   ".$job->locations."\n";
          // echo " COMPANY: ".$job->company."\n" ;
          // echo " SALARY: ".$job->salary."\n" ;
          // echo " DATE:   ".$job->date."\n" ;
          // echo " DESC:   ".$job->description."\n" ;
          // echo "\n" ;

      	$content = '<p>'.$item->description.'</p>';
      	if( !empty( $item->location ) )
      		$content .= '<p>Location: '.$item->location.'</p>';
      	if( !empty( $item->company ) )
      		$content .= '<p>Company: '.$item->company.'</p>';
      	if( !empty( $item->salary ) )
      		$content .= '<p>Salary: '.$item->salary.'</p>';
  		// var_dump($item);
  		$res = array();
  		$res['id'] = $item->date;
  		$res['title'] = $item->title;
  		$res['content'] = $content;
  		$res['url'] = $item->url;
  		$res['image_url'] = false;
  		$ret[] = $res;
       }

   }
    else
    	return array();
    return $ret;
}


function wpautoc_do_import_careerjet_items( $campaign_id, $items, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $items[0]['content']['title'] );
	// var_dump($name);
	$content = '';
	$item_ids = array();
	foreach( $items as $item ) {
		// $content .= '<h3>'.$item['content']['title'].'</h3>';
		// $content .= $item['content']['content'];
		if( count( $items) > 1 )
			$content .= '<h3>'.$item['content']['title'].'</h3>';
		$content .= '<p>'.$item['content']['content'].'</p>';
		// $content .= '<p>https://careerjet.com/syedbalkhi/status/441336208476868608'.'</p>';
		$item_ids[] = $item['id'];
		if( 1 || isset( $settings->add_link ) && $settings->add_link ) {
			// var_dump($product);
			$url = ( isset( $item['content']['url'] ) && !empty( $item['content']['url'] ) ) ?  $item['content']['url'] : false;
			if( $url )
				$content .= '<p><a href="'.$url.'" target="_blank" class="wpac-careerjet-btn">Read More...</a></p>';
		}
	}

	/*if( $settings ) {
		if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}
	}*/

	// if( isset( $settings->add_link ) && $settings->add_link ) {
	// 	// var_dump($product);
	// 	$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
	// 	if( $url )
	// 		$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-careerjet-btn">Read More...</a></p>';
	// }

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	foreach( $items as $item ) {
		if( isset( $item['content']['image_url'] ) && !empty( $item['content']['image_url'] ) ) {
			$thumbnail = $item['content']['image_url'];
			break;
		}
	}
	if( !$thumbnail && $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $item_ids, WPAUTOC_CONTENT_CAREERJET, $thumbnail );

	return $item_ids;
}

function wpautoc_careerjet_already_imported( $item_id, $campaign_id ) {
	return !wpautoc_is_content_unique_array( $item_id, WPAUTOC_CONTENT_CAREERJET, $campaign_id );
}

// function wpautoc_careerjet_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>