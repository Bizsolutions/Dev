<?php
/* Youtube */

define( 'WPAUTOC_YOUTUBE_PER_PAGE', 50 );
define( 'WPAUTOC_YOUTUBE_MAX_LOOPS', 25 );

/*
	Type: WPAUTOC_CONTENT_YOUTUBE (1)
	Unique id: video id
*/



function wpautoc_process_content_import_youtube( $campaign_id, $settings, $num_posts = 0 ) {
	include_once WPAUTOC_DIR.'/lib/libs/youtube/Youtube.php';

	$num_imported = 0;

	$yt_api_key = wpautoc_get_youtube_key();
	// var_dump($yt_api_key);
	if( empty( $yt_api_key ) )
		return 0;

	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	// $keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;

	// if( empty( $keyword ) )
	// 	return 0;

    $youtube = new YTYoutube( array('key' => $yt_api_key ) );

	// $vimeo = new Vimeo( $vimeo_settings[ 'appid' ], $vimeo_settings[ 'appsecret' ], $vimeo_settings[ 'token' ] );

	if( empty( $youtube ) )
		return 0;
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	$search_type = isset( $settings->video_type ) ?  intval( $settings->video_type) : 1 ;
	$next_page = false;
	$video_ids = false;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_YOUTUBE_MAX_LOOPS )  ) {
		// echo '<p>Pasada'.$i.'</p>';
		$ret = wpautoc_content_youtube_search( $youtube, $search_type, $next_page, WPAUTOC_YOUTUBE_PER_PAGE/*, $keyword*/, $campaign_id, $settings, $num_posts );
		// echo '<p>Next page: '.$next_page.'</p>';
		$video_ids = $ret[0];
		// var_dump($video_ids);
		$next_page = $ret[1];
		if( $video_ids == -1 )
			$end_reached = true;
		$imported = count( $video_ids );
		// echo "en esta pasada saco $imported videos<br/>";
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	if( $video_ids ) {
		$num_imported = wpautoc_content_youtube_import_videos( $video_ids, $youtube, $campaign_id, $settings, $num_posts );
	}
// var_dump($video_ids);
	return $num_imported;
}

function wpautoc_content_youtube_import_videos( $video_ids, $youtube, $campaign_id, $settings, $num_posts ) {
	$imported = 0;
	$response = $youtube->getVideosInfo( $video_ids );
	if( !$response ) {
		return 0;
	}
	foreach( $response as $video ) {
		if( !$video )
			continue;
		// var_dump($video);
		$video_id = wpautoc_do_import_youtube_video( $campaign_id, $video, $video->id, $settings );
		if( $video_id )
			$imported++;
	}
	return $imported;
}

function wpautoc_do_import_youtube_video( $campaign_id, $video, $video_id = 0, $settings = false ) {
		$content = '';
	// var_dump($settings);
		$vid_content = '<p>https://youtube.com/watch?v='.$video->id.'</p>';
		$vid_desc = '';
		if( $settings ) {
			// var_dump($settings);
			if( isset( $settings->fetch_desc ) && $settings->fetch_desc ) {
				$vid_desc = $video->snippet->description;

				if( isset( $settings->remove_links ) && $settings->remove_links ) {
					$vid_desc = wpautoc_remove_links( $vid_desc );
				}

				if( isset( $settings->spin ) && $settings->spin ) {
					$vid_desc = wpautoc_spin_text( $vid_desc );
				}

				// var_dump($vid_content);
				// var_dump($content);
			}
		}
		$content = $vid_content.$vid_desc;

		$thumbnail = false;
		if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
			// download video thumbnail
			if( isset( $video->snippet->thumbnails ) && !empty( $video->snippet->thumbnails ) ) {
				$thumbnail = wpautoc_youtube_pick_thumbnail( $video->snippet->thumbnails );
			}
		}

		$video_tags = false;
		if( isset( $settings->video_tags ) && $settings->video_tags ) {
			if( isset( $video->snippet->tags ) && !empty( $video->snippet->tags ) )
			$video_tags = $video->snippet->tags;
		}
	wpauto_create_post( $campaign_id, $settings, $video->snippet->title, $content, $video_id, WPAUTOC_CONTENT_YOUTUBE, $thumbnail, $video_tags );
// var_dump($video);s
	return $video->id;
}

function wpautoc_content_youtube_search( $youtube, $search_type, $page_token = false, $per_page = 50, $campaign_id, $settings, $num_posts ) {
	$array_ids = array();
// var_dump($num_posts);
	$extra_params = array();

	if( isset( $settings->beforef ) && (!empty( $settings->beforef ) ) )
		$extra_params['publishedBefore'] = wpautoc_yt_datef( $settings->beforef ).'T00:00:00Z';

	if( isset( $settings->afterf ) && (!empty( $settings->afterf ) ) )
		$extra_params['publishedAfter'] = wpautoc_yt_datef( $settings->afterf ).'T00:00:00Z';

	if( isset( $settings->quality ) && (!empty( $settings->quality ) ) )
		$extra_params['videoDefinition'] = wpautoc_yt_definition_tostring( $settings->quality );

	if( isset( $settings->duration ) && (!empty( $settings->duration ) ) )
		$extra_params['videoDuration'] = wpautoc_yt_duration_tostring( $settings->duration );

	$extra_params['videoEmbeddable'] = 'true';
	$extra_params['videoSyndicated'] = 'true';

	if( $page_token )
		$extra_params['pageToken'] = $page_token;
	// echo "pido page token".$page_token."<br/>";

	$negative = isset( $settings->negative ) ? $settings->negative : false;
	if( $search_type == 1 ) {
		// Keyword
		$keyword = isset( $settings->keyword ) ? $settings->keyword : false;
		if( !$keyword )
			return false;
		$keywords= wpautoc_yt_keywords_tostring( $keyword, $negative );
		$response = $youtube->searchVideos( $keywords, $per_page, null, $extra_params );
	}
	else if( $search_type == 2 ) {
		// Channel
		$channel = isset( $settings->channel ) ? $settings->channel : false;
		if( empty( $channel ) )
			return false;
		$keywords = '';
		$response = $youtube->searchChannelVideos( $keywords, $channel, $per_page, null, $extra_params, true );
	}
	else if( $search_type == 3 ) {
		// Trends
		$country = isset( $settings->country ) ? $settings->country : false;
		if( empty( $country ) )
			$country = false;
		$keywords = '';
		$response = $youtube->searchTrends( $country, $per_page, null, $extra_params, true );
	}
// var_dump($response);
	$info = $response['info'];
	$total_returned = $info['totalResults'];
    /*if ( defined( 'WPAUTOC_CRON_DEBUG' ) )
		echo '<p>Total Returned = '.$total_returned.'</p>';*/
	$video_ids = wpautoc_yt_video_ids( $response['results'] );

	$next_page = isset( $info['nextPageToken'] ) ? $info['nextPageToken'] : false;

	if( $video_ids ) {
		$imported = 0;
		foreach( $video_ids as $video_id ) {
			if( !wpautoc_youtube_already_imported( $video_id, $campaign_id ) ) {
				$array_ids[] = $video_id;
				//wpautoc_do_import_youtube_video( $campaign_id, $video, $video_id, $settings );
				$imported++;
				if( $imported >= $num_posts )
					return array( array_slice( $array_ids, 0, $num_posts ), $next_page );
			}
		}
		return array( array_slice( $array_ids, 0, $num_posts ), $next_page );
	}
	else
		return array( -1, $next_page );
	// $search_results = $youtube->request( '/videos', array('page' => $page, 'per_page' => $per_page, 'query' => urlencode( $keyword ), 'sort' => 'relevant', 'direction' => 'desc' ) );

	// $results = isset( $search_results['body']['data'] ) ? $search_results['body']['data'] : false;

	/*if( $results ) {
		$imported = 0;
		foreach( $results as $video ) {
			$video_id = wpautoc_youtube_extract_id( $video['uri'] );
			if( !wpautoc_youtube_already_imported( $video_id, $campaign_id ) ) {
				$array_ids[] = $video_ids;
				//wpautoc_do_import_youtube_video( $campaign_id, $video, $video_id, $settings );
				$imported++;
				if( $imported >= $num_posts )
					return $array_ids;
			}
		}
		return $array_ids;
	}
	else
		return -1;*/

}

function wpautoc_yt_video_ids( $response, $prev_results = false, $advanced = false, $negative_kw = false ) {
	$array_ret = array();
	if( $response ) {
		foreach( $response as $video ) {
			if( $advanced)
				$array_ret[] = $video->id;
			else
				$array_ret[] = $video->id->videoId;
		}
	}
	// if( $prev_results )
	// 	return array_merge( $prev_results, $array_ret );
	// else
		return $array_ret;
}

/*function wpautoc_do_import_youtube_video( $campaign_id, $video, $video_id = 0, $settings = false ) {
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
			$thumbnail = wpautoc_youtube_pick_thumbnail( $video['pictures']['sizes'] );
			$thumbnail = substr( $thumbnail, 0, strpos( $thumbnail, '?' ) );
		}
	}

	$video_tags = false;
	if( isset( $settings->video_tags ) && $settings->video_tags ) {
		if( isset( $video['tags'] ) && !empty( $video['tags'] ) )
		$video_tags = wpautoc_youtube_get_tags( $video['tags'] );
	}

	wpauto_create_post( $campaign_id, $settings, $video['name'], $content, $video_id, WPAUTOC_CONTENT_VIMEO, $thumbnail, $video_tags );

	return $video['uri'];
}*/

function wpautoc_youtube_already_imported( $video_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $video_id, WPAUTOC_CONTENT_YOUTUBE, $campaign_id );
}

function wpautoc_youtube_extract_id( $link ) {
	$parts = explode( '/', $link );
	if( $parts )
		return end( $parts );
	return 0;
}

function wpautoc_yt_definition_tostring( $def ) {
	if( $def == 1 )
		return 'high';
	else if( $def == 2 )
		return 'standard';
	return 'any';
}

function wpautoc_yt_duration_tostring( $def ) {
	if( $def == 1 )
		return 'short';
	else if( $def == 2 )
		return 'medium';
	else if( $def == 3 )
		return 'long';
	return 'any';
}

function wpautoc_yt_datef( $date ) {
	$date = explode( '-', $date );
	return $date[2].'-'.$date[1].'-'.$date[0];
}

function wpautoc_yt_keywords_tostring( $keywords, $negative = false ) {
	$q = '';
	$keywords = explode( ',', $keywords );
	if( count( $keywords ) == 1 )
		$q = trim( $keywords[0] );
	else {
		if( $keywords ) {
			$i = 0;
			foreach( $keywords as $keyword ) {
				if( $i++ )
					$q .= '|';
				$q .= '"'.trim($keyword).'"';
			}
		}
	}

	if( $negative && (!empty( $negative ) ) ) {
		$neg = explode( ',', $negative );
		if( $neg ) {
			foreach( $neg as $kw ) {
				$q.= '-"'.trim( $kw ).'"';
			}
		}
	}
	return $q;
}




function wpautoc_youtube_pick_thumbnail( $thumbnails ) {
	$order = array( 'maxres', 'standard', 'high', 'medium', 'default' );
	foreach( $order as $size ) {
		if( isset( $thumbnails->{$size}) )
			return $thumbnails->{$size}->url;
	}
	return false;
}

function wpautoc_get_youtube_key() {
	$youtube_api = wpautoc_get_settings( array( 'content', 'youtube', 'apikey' ) );
	if( $youtube_api )
		return $youtube_api;
	return false;
	// return 'AIzaSyBYQ6C7G5Vmf8Rn0kyNRBfRcMqTBTMMsqw';
}


/* Youtube Data */

function wpautoc_yt_orderby() {
	return array(
		array( 'label' => 'Default', 'value' => 0),
		array( 'label' => 'Relevance', 'value' => 1),
		array( 'label' => 'Date', 'value' => 2),
		array( 'label' => 'Views', 'value' => 3),
		array( 'label' => 'Title', 'value' => 4),
		array( 'label' => 'Rating', 'value' => 5)
	);
}

function wpautoc_yt_duration() {
	return array(
		array( 'label' => 'All Videos', 'value' => 0),
		array( 'label' => 'Short (less than 4 min)', 'value' => 1),
		array( 'label' => 'Medium (between 4 and 20 min)', 'value' => 2),
		array( 'label' => 'Long (over 20 min)', 'value' => 3),
	);
}

function wpautoc_yt_vquality() {
	return array(
		array( 'label' => 'All Videos', 'value' => 0),
		array( 'label' => 'Only High Definition Videos', 'value' => 1),
		array( 'label' => 'Only Standard Definition Videos', 'value' => 2),
	);
}

function wpautoc_yt_countries() {
	return array(

		array( 'value' => '' , 'label'  => 'WorldWide' ),
		array( 'value' => 'AF', 'label' => 'Afghanistan' ),
		array( 'value' => 'AX', 'label' => 'Aland Islands' ),
		array( 'value' => 'AL', 'label' => 'Albania' ),
		array( 'value' => 'DZ', 'label' => 'Algeria' ),
		array( 'value' => 'AS', 'label' => 'American Samoa' ),
		array( 'value' => 'AD', 'label' => 'Andorra' ),
		array( 'value' => 'AO', 'label' => 'Angola' ),
		array( 'value' => 'AI', 'label' => 'Anguilla' ),
		array( 'value' => 'AQ', 'label' => 'Antarctica' ),
		array( 'value' => 'AG', 'label' => 'Antigua And Barbuda' ),
		array( 'value' => 'AR', 'label' => 'Argentina' ),
		array( 'value' => 'AM', 'label' => 'Armenia' ),
		array( 'value' => 'AW', 'label' => 'Aruba' ),
		array( 'value' => 'AU', 'label' => 'Australia' ),
		array( 'value' => 'AT', 'label' => 'Austria' ),
		array( 'value' => 'AZ', 'label' => 'Azerbaijan' ),
		array( 'value' => 'BS', 'label' => 'Bahamas' ),
		array( 'value' => 'BH', 'label' => 'Bahrain' ),
		array( 'value' => 'BD', 'label' => 'Bangladesh' ),
		array( 'value' => 'BB', 'label' => 'Barbados' ),
		array( 'value' => 'BY', 'label' => 'Belarus' ),
		array( 'value' => 'BE', 'label' => 'Belgium' ),
		array( 'value' => 'BZ', 'label' => 'Belize' ),
		array( 'value' => 'BJ', 'label' => 'Benin' ),
		array( 'value' => 'BM', 'label' => 'Bermuda' ),
		array( 'value' => 'BT', 'label' => 'Bhutan' ),
		array( 'value' => 'BO', 'label' => 'Bolivia' ),
		array( 'value' => 'BA', 'label' => 'Bosnia And Herzegovina' ),
		array( 'value' => 'BW', 'label' => 'Botswana' ),
		array( 'value' => 'BV', 'label' => 'Bouvet Island' ),
		array( 'value' => 'BR', 'label' => 'Brazil' ),
		array( 'value' => 'IO', 'label' => 'British Indian Ocean Territory' ),
		array( 'value' => 'BN', 'label' => 'Brunei Darussalam' ),
		array( 'value' => 'BG', 'label' => 'Bulgaria' ),
		array( 'value' => 'BF', 'label' => 'Burkina Faso' ),
		array( 'value' => 'BI', 'label' => 'Burundi' ),
		array( 'value' => 'KH', 'label' => 'Cambodia' ),
		array( 'value' => 'CM', 'label' => 'Cameroon' ),
		array( 'value' => 'CA', 'label' => 'Canada' ),
		array( 'value' => 'CV', 'label' => 'Cape Verde' ),
		array( 'value' => 'KY', 'label' => 'Cayman Islands' ),
		array( 'value' => 'CF', 'label' => 'Central African Republic' ),
		array( 'value' => 'TD', 'label' => 'Chad' ),
		array( 'value' => 'CL', 'label' => 'Chile' ),
		array( 'value' => 'CN', 'label' => 'China' ),
		array( 'value' => 'CX', 'label' => 'Christmas Island' ),
		array( 'value' => 'CC', 'label' => 'Cocos (Keeling) Islands' ),
		array( 'value' => 'CO', 'label' => 'Colombia' ),
		array( 'value' => 'KM', 'label' => 'Comoros' ),
		array( 'value' => 'CG', 'label' => 'Congo' ),
		array( 'value' => 'CD', 'label' => 'Congo, Democratic Republic' ),
		array( 'value' => 'CK', 'label' => 'Cook Islands' ),
		array( 'value' => 'CR', 'label' => 'Costa Rica' ),
		array( 'value' => 'CI', 'label' => 'Cote D\'Ivoire' ),
		array( 'value' => 'HR', 'label' => 'Croatia' ),
		array( 'value' => 'CU', 'label' => 'Cuba' ),
		array( 'value' => 'CY', 'label' => 'Cyprus' ),
		array( 'value' => 'CZ', 'label' => 'Czech Republic' ),
		array( 'value' => 'DK', 'label' => 'Denmark' ),
		array( 'value' => 'DJ', 'label' => 'Djibouti' ),
		array( 'value' => 'DM', 'label' => 'Dominica' ),
		array( 'value' => 'DO', 'label' => 'Dominican Republic' ),
		array( 'value' => 'EC', 'label' => 'Ecuador' ),
		array( 'value' => 'EG', 'label' => 'Egypt' ),
		array( 'value' => 'SV', 'label' => 'El Salvador' ),
		array( 'value' => 'GQ', 'label' => 'Equatorial Guinea' ),
		array( 'value' => 'ER', 'label' => 'Eritrea' ),
		array( 'value' => 'EE', 'label' => 'Estonia' ),
		array( 'value' => 'ET', 'label' => 'Ethiopia' ),
		array( 'value' => 'FK', 'label' => 'Falkland Islands (Malvinas)' ),
		array( 'value' => 'FO', 'label' => 'Faroe Islands' ),
		array( 'value' => 'FJ', 'label' => 'Fiji' ),
		array( 'value' => 'FI', 'label' => 'Finland' ),
		array( 'value' => 'FR', 'label' => 'France' ),
		array( 'value' => 'GF', 'label' => 'French Guiana' ),
		array( 'value' => 'PF', 'label' => 'French Polynesia' ),
		array( 'value' => 'TF', 'label' => 'French Southern Territories' ),
		array( 'value' => 'GA', 'label' => 'Gabon' ),
		array( 'value' => 'GM', 'label' => 'Gambia' ),
		array( 'value' => 'GE', 'label' => 'Georgia' ),
		array( 'value' => 'DE', 'label' => 'Germany' ),
		array( 'value' => 'GH', 'label' => 'Ghana' ),
		array( 'value' => 'GI', 'label' => 'Gibraltar' ),
		array( 'value' => 'GR', 'label' => 'Greece' ),
		array( 'value' => 'GL', 'label' => 'Greenland' ),
		array( 'value' => 'GD', 'label' => 'Grenada' ),
		array( 'value' => 'GP', 'label' => 'Guadeloupe' ),
		array( 'value' => 'GU', 'label' => 'Guam' ),
		array( 'value' => 'GT', 'label' => 'Guatemala' ),
		array( 'value' => 'GG', 'label' => 'Guernsey' ),
		array( 'value' => 'GN', 'label' => 'Guinea' ),
		array( 'value' => 'GW', 'label' => 'Guinea-Bissau' ),
		array( 'value' => 'GY', 'label' => 'Guyana' ),
		array( 'value' => 'HT', 'label' => 'Haiti' ),
		array( 'value' => 'HM', 'label' => 'Heard Island & Mcdonald Islands' ),
		array( 'value' => 'VA', 'label' => 'Holy See (Vatican City State)' ),
		array( 'value' => 'HN', 'label' => 'Honduras' ),
		array( 'value' => 'HK', 'label' => 'Hong Kong' ),
		array( 'value' => 'HU', 'label' => 'Hungary' ),
		array( 'value' => 'IS', 'label' => 'Iceland' ),
		array( 'value' => 'IN', 'label' => 'India' ),
		array( 'value' => 'ID', 'label' => 'Indonesia' ),
		array( 'value' => 'IR', 'label' => 'Iran, Islamic Republic Of' ),
		array( 'value' => 'IQ', 'label' => 'Iraq' ),
		array( 'value' => 'IE', 'label' => 'Ireland' ),
		array( 'value' => 'IM', 'label' => 'Isle Of Man' ),
		array( 'value' => 'IL', 'label' => 'Israel' ),
		array( 'value' => 'IT', 'label' => 'Italy' ),
		array( 'value' => 'JM', 'label' => 'Jamaica' ),
		array( 'value' => 'JP', 'label' => 'Japan' ),
		array( 'value' => 'JE', 'label' => 'Jersey' ),
		array( 'value' => 'JO', 'label' => 'Jordan' ),
		array( 'value' => 'KZ', 'label' => 'Kazakhstan' ),
		array( 'value' => 'KE', 'label' => 'Kenya' ),
		array( 'value' => 'KI', 'label' => 'Kiribati' ),
		array( 'value' => 'KR', 'label' => 'Korea' ),
		array( 'value' => 'KW', 'label' => 'Kuwait' ),
		array( 'value' => 'KG', 'label' => 'Kyrgyzstan' ),
		array( 'value' => 'LA', 'label' => 'Lao People\'s Democratic Republic' ),
		array( 'value' => 'LV', 'label' => 'Latvia' ),
		array( 'value' => 'LB', 'label' => 'Lebanon' ),
		array( 'value' => 'LS', 'label' => 'Lesotho' ),
		array( 'value' => 'LR', 'label' => 'Liberia' ),
		array( 'value' => 'LY', 'label' => 'Libyan Arab Jamahiriya' ),
		array( 'value' => 'LI', 'label' => 'Liechtenstein' ),
		array( 'value' => 'LT', 'label' => 'Lithuania' ),
		array( 'value' => 'LU', 'label' => 'Luxembourg' ),
		array( 'value' => 'MO', 'label' => 'Macao' ),
		array( 'value' => 'MK', 'label' => 'Macedonia' ),
		array( 'value' => 'MG', 'label' => 'Madagascar' ),
		array( 'value' => 'MW', 'label' => 'Malawi' ),
		array( 'value' => 'MY', 'label' => 'Malaysia' ),
		array( 'value' => 'MV', 'label' => 'Maldives' ),
		array( 'value' => 'ML', 'label' => 'Mali' ),
		array( 'value' => 'MT', 'label' => 'Malta' ),
		array( 'value' => 'MH', 'label' => 'Marshall Islands' ),
		array( 'value' => 'MQ', 'label' => 'Martinique' ),
		array( 'value' => 'MR', 'label' => 'Mauritania' ),
		array( 'value' => 'MU', 'label' => 'Mauritius' ),
		array( 'value' => 'YT', 'label' => 'Mayotte' ),
		array( 'value' => 'MX', 'label' => 'Mexico' ),
		array( 'value' => 'FM', 'label' => 'Micronesia, Federated States Of' ),
		array( 'value' => 'MD', 'label' => 'Moldova' ),
		array( 'value' => 'MC', 'label' => 'Monaco' ),
		array( 'value' => 'MN', 'label' => 'Mongolia' ),
		array( 'value' => 'ME', 'label' => 'Montenegro' ),
		array( 'value' => 'MS', 'label' => 'Montserrat' ),
		array( 'value' => 'MA', 'label' => 'Morocco' ),
		array( 'value' => 'MZ', 'label' => 'Mozambique' ),
		array( 'value' => 'MM', 'label' => 'Myanmar' ),
		array( 'value' => 'NA', 'label' => 'Namibia' ),
		array( 'value' => 'NR', 'label' => 'Nauru' ),
		array( 'value' => 'NP', 'label' => 'Nepal' ),
		array( 'value' => 'NL', 'label' => 'Netherlands' ),
		array( 'value' => 'AN', 'label' => 'Netherlands Antilles' ),
		array( 'value' => 'NC', 'label' => 'New Caledonia' ),
		array( 'value' => 'NZ', 'label' => 'New Zealand' ),
		array( 'value' => 'NI', 'label' => 'Nicaragua' ),
		array( 'value' => 'NE', 'label' => 'Niger' ),
		array( 'value' => 'NG', 'label' => 'Nigeria' ),
		array( 'value' => 'NU', 'label' => 'Niue' ),
		array( 'value' => 'NF', 'label' => 'Norfolk Island' ),
		array( 'value' => 'MP', 'label' => 'Northern Mariana Islands' ),
		array( 'value' => 'NO', 'label' => 'Norway' ),
		array( 'value' => 'OM', 'label' => 'Oman' ),
		array( 'value' => 'PK', 'label' => 'Pakistan' ),
		array( 'value' => 'PW', 'label' => 'Palau' ),
		array( 'value' => 'PS', 'label' => 'Palestinian Territory, Occupied' ),
		array( 'value' => 'PA', 'label' => 'Panama' ),
		array( 'value' => 'PG', 'label' => 'Papua New Guinea' ),
		array( 'value' => 'PY', 'label' => 'Paraguay' ),
		array( 'value' => 'PE', 'label' => 'Peru' ),
		array( 'value' => 'PH', 'label' => 'Philippines' ),
		array( 'value' => 'PN', 'label' => 'Pitcairn' ),
		array( 'value' => 'PL', 'label' => 'Poland' ),
		array( 'value' => 'PT', 'label' => 'Portugal' ),
		array( 'value' => 'PR', 'label' => 'Puerto Rico' ),
		array( 'value' => 'QA', 'label' => 'Qatar' ),
		array( 'value' => 'RE', 'label' => 'Reunion' ),
		array( 'value' => 'RO', 'label' => 'Romania' ),
		array( 'value' => 'RU', 'label' => 'Russian Federation' ),
		array( 'value' => 'RW', 'label' => 'Rwanda' ),
		array( 'value' => 'BL', 'label' => 'Saint Barthelemy' ),
		array( 'value' => 'SH', 'label' => 'Saint Helena' ),
		array( 'value' => 'KN', 'label' => 'Saint Kitts And Nevis' ),
		array( 'value' => 'LC', 'label' => 'Saint Lucia' ),
		array( 'value' => 'MF', 'label' => 'Saint Martin' ),
		array( 'value' => 'PM', 'label' => 'Saint Pierre And Miquelon' ),
		array( 'value' => 'VC', 'label' => 'Saint Vincent And Grenadines' ),
		array( 'value' => 'WS', 'label' => 'Samoa' ),
		array( 'value' => 'SM', 'label' => 'San Marino' ),
		array( 'value' => 'ST', 'label' => 'Sao Tome And Principe' ),
		array( 'value' => 'SA', 'label' => 'Saudi Arabia' ),
		array( 'value' => 'SN', 'label' => 'Senegal' ),
		array( 'value' => 'RS', 'label' => 'Serbia' ),
		array( 'value' => 'SC', 'label' => 'Seychelles' ),
		array( 'value' => 'SL', 'label' => 'Sierra Leone' ),
		array( 'value' => 'SG', 'label' => 'Singapore' ),
		array( 'value' => 'SK', 'label' => 'Slovakia' ),
		array( 'value' => 'SI', 'label' => 'Slovenia' ),
		array( 'value' => 'SB', 'label' => 'Solomon Islands' ),
		array( 'value' => 'SO', 'label' => 'Somalia' ),
		array( 'value' => 'ZA', 'label' => 'South Africa' ),
		array( 'value' => 'GS', 'label' => 'South Georgia And Sandwich Isl.' ),
		array( 'value' => 'ES', 'label' => 'Spain' ),
		array( 'value' => 'LK', 'label' => 'Sri Lanka' ),
		array( 'value' => 'SD', 'label' => 'Sudan' ),
		array( 'value' => 'SR', 'label' => 'Suriname' ),
		array( 'value' => 'SJ', 'label' => 'Svalbard And Jan Mayen' ),
		array( 'value' => 'SZ', 'label' => 'Swaziland' ),
		array( 'value' => 'SE', 'label' => 'Sweden' ),
		array( 'value' => 'CH', 'label' => 'Switzerland' ),
		array( 'value' => 'SY', 'label' => 'Syrian Arab Republic' ),
		array( 'value' => 'TW', 'label' => 'Taiwan' ),
		array( 'value' => 'TJ', 'label' => 'Tajikistan' ),
		array( 'value' => 'TZ', 'label' => 'Tanzania' ),
		array( 'value' => 'TH', 'label' => 'Thailand' ),
		array( 'value' => 'TL', 'label' => 'Timor-Leste' ),
		array( 'value' => 'TG', 'label' => 'Togo' ),
		array( 'value' => 'TK', 'label' => 'Tokelau' ),
		array( 'value' => 'TO', 'label' => 'Tonga' ),
		array( 'value' => 'TT', 'label' => 'Trinidad And Tobago' ),
		array( 'value' => 'TN', 'label' => 'Tunisia' ),
		array( 'value' => 'TR', 'label' => 'Turkey' ),
		array( 'value' => 'TM', 'label' => 'Turkmenistan' ),
		array( 'value' => 'TC', 'label' => 'Turks And Caicos Islands' ),
		array( 'value' => 'TV', 'label' => 'Tuvalu' ),
		array( 'value' => 'UG', 'label' => 'Uganda' ),
		array( 'value' => 'UA', 'label' => 'Ukraine' ),
		array( 'value' => 'AE', 'label' => 'United Arab Emirates' ),
		array( 'value' => 'GB', 'label' => 'United Kingdom' ),
		array( 'value' => 'US', 'label' => 'United States' ),
		array( 'value' => 'UM', 'label' => 'United States Outlying Islands' ),
		array( 'value' => 'UY', 'label' => 'Uruguay' ),
		array( 'value' => 'UZ', 'label' => 'Uzbekistan' ),
		array( 'value' => 'VU', 'label' => 'Vanuatu' ),
		array( 'value' => 'VE', 'label' => 'Venezuela' ),
		array( 'value' => 'VN', 'label' => 'Vietnam' ),
		array( 'value' => 'VG', 'label' => 'Virgin Islands, British' ),
		array( 'value' => 'VI', 'label' => 'Virgin Islands, U.S.' ),
		array( 'value' => 'WF', 'label' => 'Wallis And Futuna' ),
		array( 'value' => 'EH', 'label' => 'Western Sahara' ),
		array( 'value' => 'YE', 'label' => 'Yemen' ),
		array( 'value' => 'ZM', 'label' => 'Zambia' ),
		array( 'value' => 'ZW', 'label' => 'Zimbabwe' )
	);
}
?>