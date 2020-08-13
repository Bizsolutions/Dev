<?php
/* Vimeo */

define( 'WPAUTOC_VIMEO_PER_PAGE', 100 );
define( 'WPAUTOC_VIMEO_MAX_LOOPS', 25 );

/*
	Type: WPAUTOC_CONTENT_VIMEO (4)
	Unique id: video id
*/


function wpautoc_process_content_import_vimeo( $campaign_id, $settings, $num_posts = 0 ) {
	include_once WPAUTOC_DIR.'/lib/libs/vimeo/vimeo.php';

	$num_imported = 0;

	$vimeo_settings = wpautoc_get_settings( array( 'content', 'vimeo' ) );
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;
	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;

	if( empty( $keyword ) )
		return 0;
	if ( empty( $vimeo_settings[ 'appid' ] ) || empty( $vimeo_settings[ 'appsecret' ] ) || empty( $vimeo_settings[ 'token' ] ) )
		return 0;
	$vimeo = new Vimeo( $vimeo_settings[ 'appid' ], $vimeo_settings[ 'appsecret' ], $vimeo_settings[ 'token' ] );

	if( empty( $vimeo ) )
		return 0;

	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_VIMEO_MAX_LOOPS )  ) {
		$imported = wpautoc_content_vimeo_search( $vimeo, $page, WPAUTOC_VIMEO_PER_PAGE, $keyword, $campaign_id, $settings, $num_posts );
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	return $num_imported;
}

function wpautoc_content_vimeo_search( $vimeo, $page = 1, $per_page = 50, $keyword, $campaign_id, $settings, $num_posts ) {
// echo "necesito $num_posts <br/>";
	$search_results = $vimeo->request( '/videos', array('page' => $page, 'per_page' => $per_page, 'query' => urlencode( $keyword ), 'sort' => 'relevant', 'direction' => 'desc' ) );

	$results = isset( $search_results['body']['data'] ) ? $search_results['body']['data'] : false;

	if( $results ) {
		$imported = 0;
		foreach( $results as $video ) {
			$video_id = wpautoc_vimeo_extract_id( $video['uri'] );
			if( !wpautoc_vimeo_already_imported( $video_id, $campaign_id ) ) {
				wpautoc_do_import_vimeo_video( $campaign_id, $video, $video_id, $settings );
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

function wpautoc_do_import_vimeo_video( $campaign_id, $video, $video_id = 0, $settings = false ) {
	$content = '';
	$vid_content = '<p>'.$video['link'].'</p>';
	if( $settings ) {
		if( isset( $settings->fetch_desc ) && $settings->fetch_desc ) {
			$vid_content .= $video['description'];

			if( isset( $settings->remove_links ) && $settings->remove_links ) {
				$vid_content = wpautoc_remove_links( $vid_content );
			}

			if( isset( $settings->spin ) && $settings->spin ) {
				$vid_content = wpautoc_spin_text( $vid_content );
			}
		}
	}

	$content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
		if( isset( $video['pictures'] ) && !empty( $video['pictures'] ) ) {
			$thumbnail = wpautoc_vimeo_pick_thumbnail( $video['pictures']['sizes'] );
			$thumbnail = substr( $thumbnail, 0, strpos( $thumbnail, '?' ) );
		}
	}

	$video_tags = false;
	if( isset( $settings->video_tags ) && $settings->video_tags ) {
		if( isset( $video['tags'] ) && !empty( $video['tags'] ) )
		$video_tags = wpautoc_vimeo_get_tags( $video['tags'] );
	}

	wpauto_create_post( $campaign_id, $settings, $video['name'], $content, $video_id, WPAUTOC_CONTENT_VIMEO, $thumbnail, $video_tags );

	return $video['uri'];
}

function wpautoc_vimeo_already_imported( $video_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $video_id, WPAUTOC_CONTENT_VIMEO, $campaign_id );
}

function wpautoc_vimeo_extract_id( $link ) {
	$parts = explode( '/', $link );
	if( $parts )
		return end( $parts );
	return 0;
}

/* Vimeo Specific Functions */
function wpautoc_vimeo_pick_thumbnail( $thumbnails ) {
	$order = array( 5, 4, 3, 2, 1, 0 );
	foreach( $order as $size ) {
		if( isset( $thumbnails[$size] ) )
			return $thumbnails[$size]['link'];
	}
	return false;
}

function wpautoc_vimeo_get_tags( $video_tags ) {
	$arr = array();
	if( $video_tags ) {
		foreach( $video_tags as $tag )
			$arr[] = $tag['name'];
	}
	return $arr;
}
?>