<?php
/* RSS */

define( 'WPAUTOC_RSS_PER_PAGE', 100 );
define( 'WPAUTOC_RSS_MAX_LOOPS', 3 );

/*
	Type: WPAUTOC_CONTENT_RSS (6)
	Unique id: video id
*/

	function wpautoc_process_content_import_rss( $campaign_id, $settings, $num_posts = 0 ) {

		$num_imported = 0;

		$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

		$url = isset( $settings->url ) ? $settings->url : false ;
		if( empty( $url ) )
			return 0;

		$end_reached = false;
		$page = 1;
		$i = 0;
		$total_posts = $num_posts;
		while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_RSS_MAX_LOOPS )  ) {
			$imported = wpautoc_content_rss_search( $url, $page, WPAUTOC_RSS_PER_PAGE, $campaign_id, $settings, $num_posts );
			if( $imported == -1 )
				$end_reached = true;
			$num_imported += $imported;
			$num_posts = $num_posts - $imported;
			$page++;
			$i++;
		}

		return $num_imported;
	}

	function wpautoc_content_rss_search( $url, $page = 1, $per_page = 50, $campaign_id, $settings, $num_posts ) {


		$response = wp_remote_get( $url );
		$body = wp_remote_retrieve_body( $response );

		$x = new SimpleXmlElement( $body );
		// $cnt = 1;
		$ret = array();
		$imported = 0;
		if( $x->channel->item ) {
			foreach( $x->channel->item as $key=>$entry ) {
				// var_dump($entry);
				// $id = $entry->title;
				$title = (string)$entry->title;
				$id = isset( $entry->guid ) ? (string)$entry->guid : str_replace( ' ', '-',strtolower ( $title ) );
				$url = (string)$entry->link;
				$content = (string)$entry->children( 'content', true );
				$description = (string)$entry->description;

				$article = array(
					'id' => $id,
					'title' => $title,
					'url' => $url,
					'content' => ( isset( $content ) && !empty( $content ) ) ? $content : $description
				);
// var_dump($article);
				if( !wpautoc_rss_already_imported( $id, $campaign_id ) ) {
					wpautoc_do_import_rss_article( $campaign_id, $article, $id, $settings );
					$imported++;
					if( $imported >= $num_posts )
						return $imported;
				}

			}
			if( !$imported )
				return -1;
			return $imported;
		}
		else
			return -1;

	}

	function wpautoc_do_import_rss_article( $campaign_id, $article, $article_id = 0, $settings = false ) {
		$content = $article['content'];
		if( $settings ) {
			if( isset( $settings->remove_links ) && $settings->remove_links ) {
				$content = wpautoc_remove_links( $content );
			}

			if( isset( $settings->spin ) && $settings->spin ) {
				$content = wpautoc_spin_text( $content );
			}

			if( isset( $settings->add_url ) && $settings->add_url ) {
				$content .= '<p>Original source: <a href="'.$article['url'].'" target="_blank">'.$article['url'].'</a></p>';
			}

		}

		$thumbnail = false;
		if( $keywords = wpautoc_campaign_images( $campaign_id ) )
			$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );
		// if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
		// 	if( isset( $article['pictures'] ) && !empty( $article['pictures'] ) ) {
		// 		$thumbnail = wpautoc_rss_pick_thumbnail( $article['pictures']['sizes'] );
		// 		$thumbnail = substr( $thumbnail, 0, strpos( $thumbnail, '?' ) );
		// 	}
		// }

// var_dump($article);
		wpauto_create_post( $campaign_id, $settings, $article['title'], $content, $article_id, WPAUTOC_CONTENT_RSS, $thumbnail/*, $article_tags*/ );

		return $article_id;
	}

	function wpautoc_rss_already_imported( $article_id, $campaign_id ) {
		return !wpautoc_is_content_unique( $article_id, WPAUTOC_CONTENT_RSS, $campaign_id );
	}


////////////////////// old //////////////
function wpautoc_process_content_import_rss_old( $campaign_id, $settings, $num_posts = 0 ) {

	$num_imported = 0;

	$extra_params = array();
// var_dump($settings);
	$url = isset( $settings->url ) ? $settings->url : false ;

	if( empty( $url ) )
		return;

	$search = wpautoc_rss_search( $url, $num_posts );
	$articles = wpautoc_rss_import( $search, $num_posts, $campaign_id, $settings );
	return 1;

	$article = $articles[0];
	$content = $article['content'];
	$title = $article['title'];
	// var_dump($article);
	/*if( $settings ) {
		if( isset( $settings->remove_links ) && $settings->remove_links ) {
			$content = wpautoc_remove_links( $content );
		}

		if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}

		// $content .= '<br/>'.$content;
		// var_dump($vid_content);
		// var_dump($content);
	}*/

	$thumbnail = false;
	if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
		// TO-DO: get thumbnail from flickr, pixabay
	}

	$post_id = wpauto_create_post( $campaign_id, $settings, $title, $content, false /*TO-DO, thumbnail */ );
	return $post_id;
}

function wpautoc_rss_search( $url, $num_posts = 10 ) {
	$response = wp_remote_get( $url );
	$body = wp_remote_retrieve_body( $response );

	$x = new SimpleXmlElement( $body );
	$cnt = 1;
	$ret = array();
	foreach( $x->channel->item as $key=>$entry ) {
		$title = (string)$entry->title;
		$url = (string)$entry->link;
		$content = (string)$entry->children( 'content', true );
		$description = (string)$entry->description;

		$art = array(
			'id' => $entry->id,
			'title' => $title,
			'url' => $url,
			'content' => ( isset( $content ) && !empty( $content ) ) ? $content : $description
		);
		$ret[] = $art;
	}
	return $ret;
}

function wpautoc_rss_import( $feed, $num_posts = 1, $campaign_id, $settings ) {
	$imported = 0;
	if( $feed ) {
		foreach( $feed as $apage ) {
			$rss_content = $apage['content'];
			if( isset( $settings->remove_links ) && $settings->remove_links ) {
				$rss_content = wpautoc_remove_links( $rss_content );
			}

			if( isset( $settings->add_url ) && $settings->add_url ) {
				$rss_content .= '<p>Original source: <a href="'.$apage['url'].'" target="_blank">'.$apage['url'].'</a></p>';
			}

			$thumbnail = false;
			if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
				// TO-DO: get thumbnail from flickr, pixabay
			}
			// echo "aa";
			// var_dump($apage);
			if( !empty( $apage ) /*&& !wpautoc_exist_page_by_title( $apage['title'] )*/ ) {
				$post_id = wpauto_create_post( $campaign_id, $settings, $apage['title'], $rss_content, false /*TO-DO, thumbnail */ );
				echo "post creado";
				$imported++;
			}
			if( $imported >= $num_posts )
				return 1;
			// return $post_id;
		}
	}
}


?>