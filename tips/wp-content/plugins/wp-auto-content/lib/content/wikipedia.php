<?php
/* WIKI */

define( 'WPAUTOC_WIKI_PER_PAGE', 15 );
define( 'WPAUTOC_WIKI_MAX_LOOPS', 25 );

function wpautoc_content_type_wikipedia( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	echo '<table class="form-table">';
	wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in Wikipedia' );

	wpautoc_ifield_checkbox( $settings, 'spin_title', 'Spin Title', 'wpautoc_content['.$num.'][settings][spin_title]', false, 'If checked, it will spin the title  <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );
	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	wpautoc_ifield_checkbox( $settings, 'remove_links', 'Remove Links from Content', 'wpautoc_content['.$num.'][settings][remove_links]', false, 'If checked, it will remove all links from the content' );
	wpautoc_ifield_checkbox( $settings, 'add_attribution', 'Add Attribution', 'wpautoc_content['.$num.'][settings][add_attribution]', false, 'If checked, it will add a link to the article at the end of the content' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_wikipedia( $campaign_id, $settings, $num_posts = 0 ) {

	$num_imported = 0;
	// $wikipedia_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	// if ( ! isset( $wikipedia_settings['apikey'] ) || empty( $wikipedia_settings['apikey'] ) )
	// 	return false;
// echo "WIKI";

	// $extra_params = array();

	$keyword = isset( $settings->keyword ) ? $settings->keyword : false ;

	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_WIKI_MAX_LOOPS )  ) {
		$imported = wpautoc_content_wikipedia_search( $page, WPAUTOC_WIKI_PER_PAGE, $keyword, $campaign_id, $settings, $num_posts );
		if( $imported == -1 )
			$end_reached = true;
		$num_imported += $imported;
		$num_posts = $num_posts - $imported;
		$page++;
		$i++;
	}

	return $num_imported;
/*
	$keyword = isset( $settings->keyword ) ? $settings->keyword : false ;
	$category = isset( $settings->category ) ? $settings->category : false ;

	// if( empty( $keyword ) )
	// 	return;

	$search = wpautoc_wikipedia_search( $keyword, $category, $num_posts );
	// var_dump($search);
	$articles = wpautoc_wikipedia_get_contents( $search );
	// var_dump($articles);
// return;
// 	$content = '';
	$article = $articles[0];
	$content = $article['content'];

	$thumbnail = false;
	if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
		// TO-DO: get thumbnail from flickr, pixabay
	}

	$post_id = wpauto_create_post( $campaign_id, $settings, $article['title'], $content, false  );*/
	return $post_id;
}

function wpautoc_content_wikipedia_search( $page = 1, $per_page = 15, $keyword, $campaign_id, $settings, $num_posts ) {
// echo "necesito $num_posts <br/>";

	// $wikipedia_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	// if ( ! isset( $wikipedia_settings['apikey'] ) || empty( $wikipedia_settings['apikey'] ) )
	// 	return array();
	
	// $url = 'https://en.wikipedia.org/w/api.php?action=query&list=search&format=json&srsearch='.urlencode( $keyword ).'&srwhat=text&srprop=title|size|wordcount|timestamp|snippet';

	// https://en.wikipedia.org/w/api.php?action=query&generator=random&grnnamespace=0&grnlimit=2&prop=info|extracts&inprop=url

	$url = 'https://en.wikipedia.org/w/api.php?action=query&&generator=search&&format=json&gsrsearch='.urlencode( $keyword ).'&prop=info|extracts&inprop=url';
// var_dump($url);
	$data = wpautoc_remote_get( $url );
	$data = json_decode( $data );
	// var_dump($data);
	// var_dump($data->query);
	// var_dump($data->query->pages);
	// return;
	$results = $data->query->pages;
	$articles = wpautoc_wikipedia_get_contents( $results );

	if( $articles ) {
		$imported = 0;
		foreach( $articles as $article ) {
			// $art = $article->article;
			// var_dump($article);
			// RETURN;
			$article_id = $article['id'];
			if( !wpautoc_wikipedia_already_imported( $article_id, $campaign_id ) ) {
				wpautoc_do_import_wikipedia_article( $campaign_id, $article, $article_id, $settings );
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

function wpautoc_do_import_wikipedia_article( $campaign_id, $article, $article_id = 0, $settings = false ) {
	$content = wpautoc_wikipedia_scrape_content( $article['url'] );
	$title = $article['title'];
	$url = $article['url'];
	if( $settings ) {
		if( isset( $settings->remove_links ) && $settings->remove_links ) {
			$content = wpautoc_remove_links( $content );
		}

		if( isset( $settings->spin_content ) && $settings->spin_content ) {
			$content = wpautoc_spin_text( $content );
		}

		if( isset( $settings->spin_title ) && $settings->spin_title ) {
			$title = wpautoc_spin_text( $title );
		}

		if( isset( $settings->add_attribution ) && $settings->add_attribution ) {
			$content .= '<p>Source: <a href="'.$url.'" target="_blank">'.$url.'</a></p>';
		}
	}
	// $article_title = $article['title'];

	$thumbnail = false;
	if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );
	// if( isset( $settings->thumbnail ) && $settings->thumbnail ) {
	// 	if( isset( $video['pictures'] ) && !empty( $video['pictures'] ) ) {
	// 		$thumbnail = wpautoc_vimeo_pick_thumbnail( $video['pictures']['sizes'] );
	// 		$thumbnail = substr( $thumbnail, 0, strpos( $thumbnail, '?' ) );
	// 	}
	// }


	wpauto_create_post( $campaign_id, $settings, $title, $content, $article_id, WPAUTOC_CONTENT_WIKIPEDIA, $thumbnail );

	return $article['url'];
}

function wpautoc_wikipedia_already_imported( $article_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $article_id, WPAUTOC_CONTENT_WIKIPEDIA, $campaign_id );
}
/*
function wpautoc_wikipedia_search( $keyword, $category, $num_posts = 10 ) {
	$wikipedia_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	if ( ! isset( $wikipedia_settings['apikey'] ) || empty( $wikipedia_settings['apikey'] ) )
		return array();

	$url = 'http://api.wikipedia.com/api.php?search=articles&limit='.$num_posts.'&data_amount=extended&response_format=json&key='.$wikipedia_settings['apikey'];
	if( $category ) {
		if( $pos = strpos( $category, '|' ) ) {
			$cats = explode( '|', $category );
			$url .= '&category='.$cats[0].'&subcategory='.$cats[1];
		}
		else
			$url .= '&category='.$category;
	}
	// echo $url;
	$data = wpautoc_remote_get( $url );
	$data = json_decode( $data );
	// var_dump($data);
	return $data->articles;
}
*/
function wpautoc_wikipedia_get_contents( $search ) {
	if( empty( $search ) )
		return array();
	$ret = array();
	foreach( $search as $article ) {
		// var_dump($article);return;
		// $article = $article->article;
		// $url = $article->

		$art = array(
			'id' => $article->pageid,
			'title' => $article->title,
			'url' => $article->fullurl
		);
		$ret[] = $art;
	}
	return $ret;
}

function wpautoc_wikipedia_title( $title ) {
	return $title;
}
function wpautoc_wikipedia_scrape_content( $url ) {
	include_once WPAUTOC_DIR.'/lib/libs/webParser.php';
	$wp = new webParser();

	$wp->set_url( $url );

	$html = $wp->scrape_snippet('#mw-content-text');
	$html = preg_replace("/<script.*?\/script>/s", "", $html) ? : $html;
	$html = preg_replace("/<table.*?\/table>/s", "", $html) ? : $html;
	// $html = preg_replace("/[<a.*?\edit</a>]/s", "", $html) ? : $html;

	$allowed_html = array(
	    'a' => array(
	        'href' => array(),
	        'title' => array()
	    ),
	    'h2' => array(),
	    'h3' => array(),
	    'p' => array(),
	    'ul' => array(),
	    'li' => array(),
	    'br' => array(),
	    'em' => array(),
	    'strong' => array(),
	);
	// var_dump($html);
	$html = wp_kses( $html, $allowed_html );
	// var_dump($html);
	// $json = $wp->element_to_json('#article-resource');
	return trim( str_replace( '[edit]', '', $html ) );
}
?>