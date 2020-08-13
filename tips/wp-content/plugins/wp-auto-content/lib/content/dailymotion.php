<?php
/* Dailymotion */

define( 'WPAUTOC_DAILYMOTION_PER_PAGE', 100 );
define( 'WPAUTOC_DAILYMOTION_MAX_LOOPS', 25 );

/*
	Type: WPAUTOC_CONTENT_DAILYMOTION (4)
	Unique id: video id
*/


function wpautoc_process_content_import_dailymotion( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;
	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;

	if( empty( $keyword ) )
		return 0;
// var_dump($keyword);
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_DAILYMOTION_MAX_LOOPS )  ) {
		$imported = wpautoc_content_dailymotion_search( $page, WPAUTOC_DAILYMOTION_PER_PAGE, $keyword, $campaign_id, $settings, $num_posts );
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	return $num_imported;
}

function wpautoc_content_dailymotion_search( $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
// echo "necesito $num_posts <br/>";
	$url = 'https://api.dailymotion.com/videos?search='.urlencode( $keyword ).'&fields=id,owner,title,description,url,thumbnail_720_url,thumbnail_url,embed_html&language=en&page='.$page.'&limit='.$per_page;
	$search_results = wpautoc_url_get ( $url );
// echo '<pre>';
// print_r( $search_results);
// echo '</pre>';
	$results = json_decode( $search_results, true );
	// var_dump($results);
	if( $results ) {
		$results = $results['list'];
		$imported = 0;
		foreach( $results as $video ) {
			$video_id = $video['id'];
			if( !wpautoc_dailymotion_already_imported( $video_id, $campaign_id ) ) {
				wpautoc_do_import_dailymotion_video( $campaign_id, $video, $video_id, $settings );
				$imported++;
				if( $imported >= $num_posts )
					return $imported;
			}
		}
		return $imported;
	}
	else
		return -1;

}

function wpautoc_do_import_dailymotion_video( $campaign_id, $video, $video_id = 0, $settings = false ) {
	$content = '';
	$vid_content = '<p>'.$video['embed_html'].'</p>';
	if( $settings ) {
		if( isset( $settings->fetch_desc ) && $settings->fetch_desc ) {
			$vid_content .= $video['description'];

			if( isset( $settings->remove_links ) && $settings->remove_links ) {
				$vid_content = wpautoc_remove_links( $vid_content );
			}

			/*if( isset( $settings->spin ) && $settings->spin ) {
				$vid_content = wpautoc_spin_text( $vid_content );
			}*/
		}
	}

// var_dump($video);die();
	$content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
		if( isset( $video['thumbnail_720_url'] ) && !empty( $video['thumbnail_720_url'] ) ) {
			$thumbnail = $video['thumbnail_720_url'];
		}
		else if( isset( $video['thumbnail_url'] ) && !empty( $video['thumbnail_url'] ) ) {
			$thumbnail = $video['thumbnail_url'];
		}
	}

	// $video_tags = false;
	// if( isset( $settings->video_tags ) && $settings->video_tags ) {
	// 	if( isset( $video['tags'] ) && !empty( $video['tags'] ) )
	// 	$video_tags = wpautoc_dailymotion_get_tags( $video['tags'] );
	// }

	wpauto_create_post( $campaign_id, $settings, $video['title'], $content, $video_id, WPAUTOC_CONTENT_DAILYMOTION, $thumbnail );

	return $video_id;
}

function wpautoc_dailymotion_already_imported( $video_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $video_id, WPAUTOC_CONTENT_DAILYMOTION, $campaign_id );
}

?>