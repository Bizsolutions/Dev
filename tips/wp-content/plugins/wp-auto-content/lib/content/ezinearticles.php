<?php
/* EZA */

define( 'WPAUTOC_EZA_PER_PAGE', 15 );
define( 'WPAUTOC_EZA_MAX_LOOPS', 20 );

global $eza_shown;
$eza_shown = 0;
function wpautoc_content_type_ezinearticles( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'eza' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import articles from EzineArticles, you need a valid API Key.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your API Key.</p>';
		wpautoc_noapi_msg( $msg );
	}
	//AIzaSyBYQ6C7G5Vmf8Rn0kyNRBfRcMqTBTMMsqw
	echo '<table class="form-table" '.$disp.'>';
	// wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in youtube' );

	$cats = wpautoc_eza_get_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_content['.$num.'][settings][category]', $cats, false );
	wpautoc_ifield_checkbox( $settings, 'spin_title', 'Spin Title', 'wpautoc_content['.$num.'][settings][spin_title]', false, 'If checked, it will spin the title  <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );
	wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );
	wpautoc_ifield_checkbox( $settings, 'add_attribution', 'Add Attribution', 'wpautoc_content['.$num.'][settings][add_attribution]', false, 'If checked, it will add a link to the article at the end of the content' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_ezinearticles( $campaign_id, $settings, $num_posts = 0 ) {

	$num_imported = 0;
	$eza_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	if ( ! isset( $eza_settings['apikey'] ) || empty( $eza_settings['apikey'] ) )
		return false;
// echo "EZA";

	// $extra_params = array();

	$category = isset( $settings->category ) ? $settings->category : false ;

	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_EZA_MAX_LOOPS )  ) {
		$imported = wpautoc_content_eza_search( $page, WPAUTOC_EZA_PER_PAGE, $category, $campaign_id, $settings, $num_posts );
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

	$search = wpautoc_eza_search( $keyword, $category, $num_posts );
	// var_dump($search);
	$articles = wpautoc_eza_get_contents( $search );
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

function wpautoc_content_eza_search( $page = 1, $per_page = 15, $category, $campaign_id, $settings, $num_posts ) {
// echo "necesito $num_posts <br/>";

	$eza_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	if ( ! isset( $eza_settings['apikey'] ) || empty( $eza_settings['apikey'] ) )
		return array();

	$url = 'http://api.ezinearticles.com/api.php?search=articles&limit='.$num_posts.'&data_amount=extended&response_format=json&page='.$page.'&key='.$eza_settings['apikey'];
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
	$results = $data->articles;
	$articles = wpautoc_eza_get_contents( $results );

	if( $articles ) {
		$imported = 0;
		foreach( $articles as $article ) {
			// $art = $article->article;
			// var_dump($article);
			// RETURN;
			$article_id = $article['id'];
			if( !wpautoc_eza_already_imported( $article_id, $campaign_id ) ) {
				$res = wpautoc_do_import_eza_article( $campaign_id, $article, $article_id, $settings );
				if( $res == -1 )
					return 0;
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

function wpautoc_do_import_eza_article( $campaign_id, $article, $article_id = 0, $settings = false ) {
	$content = wpautoc_eza_scrape_content( $article['full_url'] );
	if( !$content )
		return -1;
	$title = $article['title'];
	$url = $article['url'];
	if( $settings ) {
		if( isset( $settings->remove_links ) && $settings->remove_links ) {
			$vid_content = wpautoc_remove_links( $content );
		}
// var_dump($settings);
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


	wpauto_create_post( $campaign_id, $settings, $title, $content, $article_id, WPAUTOC_CONTENT_EZA, $thumbnail );
usleep( 1600);
	return $article['url'];
}

function wpautoc_eza_already_imported( $article_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $article_id, WPAUTOC_CONTENT_EZA, $campaign_id );
}
/*
function wpautoc_eza_search( $keyword, $category, $num_posts = 10 ) {
	$eza_settings = wpautoc_get_settings( array( 'content', 'eza' ) );
	if ( ! isset( $eza_settings['apikey'] ) || empty( $eza_settings['apikey'] ) )
		return array();

	$url = 'http://api.ezinearticles.com/api.php?search=articles&limit='.$num_posts.'&data_amount=extended&response_format=json&key='.$eza_settings['apikey'];
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
function wpautoc_eza_get_contents( $search ) {
	if( empty( $search ) )
		return array();
	$ret = array();
	foreach( $search as $article ) {
		$article = $article->article;
		// $url = $article->
		$art_url = 'http://ezinearticles.com/?'.str_replace( ' ', '-', $article->title ).'&id='.$article->id;
		$art = array(
			'id' => $article->id,
			'title' => $article->title,
			'url' => $article->url,
			'full_url' => $art_url/*,
			'content' => wpautoc_eza_scrape_content( $art_url )*/
		);
		$ret[] = $art;
	}
	return $ret;
}

function wpautoc_eza_scrape_content( $url ) {
	global $eza_shown;
	include_once WPAUTOC_DIR.'/lib/libs/webParser.php';
	$wp = new webParser();

	$wp->set_url( $url );

	$html = $wp->scrape_snippet('#article-content');
	if( $html == '<!do' ) {
		if( !$eza_shown && defined( 'WPAUTOC_CRON_DEBUG' ) ) {
			$eza_shown = 1;
			echo '<p>EzineArticles: maximum allowed daily connections from your IP '.$_SERVER['REMOTE_ADDR'].'. Please wait 24 hours until next connection is available.</p>';
		}
		return false;
	}
	// var_dump($html);
	$html = preg_replace("/<script.*?\/script>/s", "", $html) ? : $html;
	// var_dump($url);
	// var_dump($html);
	$allowed_html = array(
	    'a' => array(
	        'href' => array(),
	        'title' => array()
	    ),
	    'p' => array(),
	    'br' => array(),
	    'em' => array(),
	    'strong' => array(),
	);
	// var_dump($html);
	$html = wp_kses( $html, $allowed_html );
	// var_dump($html);
	// $json = $wp->element_to_json('#article-resource');
	return trim( $html );
}


function wpautoc_eza_get_cats( ) {
	return array(
		array( 'value' => 'All', 'label' => 'All' ),

		array( "label" => "Arts and Entertainment", "value" => "Arts and Entertainment"),
		array( "value" => "Arts and Entertainment|Animation", "label" => "  &nbsp; &nbsp; &nbsp;  > Animation"),
		array( "value" => "Arts and Entertainment|Astrology", "label" => "  &nbsp; &nbsp; &nbsp;  > Astrology"),
		array( "value" => "Arts and Entertainment|Body Piercings", "label" => "  &nbsp; &nbsp; &nbsp;  > Body Piercings"),
		array( "value" => "Arts and Entertainment|Casino Gambling", "label" => "  &nbsp; &nbsp; &nbsp;  > Casino Gambling"),
		array( "value" => "Arts and Entertainment|Filmmaking and Film Editing", "label" => "  &nbsp; &nbsp; &nbsp;  > Filmmaking and Film Editing"),
		array( "value" => "Arts and Entertainment|Humanities", "label" => "  &nbsp; &nbsp; &nbsp;  > Humanities"),
		array( "value" => "Arts and Entertainment|Humor", "label" => "  &nbsp; &nbsp; &nbsp;  > Humor"),
		array( "value" => "Arts and Entertainment|Movies TV", "label" => "  &nbsp; &nbsp; &nbsp;  > Movies TV"),
		array( "value" => "Arts and Entertainment|Music", "label" => "  &nbsp; &nbsp; &nbsp;  > Music"),
		array( "value" => "Arts and Entertainment|Music Industry", "label" => "  &nbsp; &nbsp; &nbsp;  > Music Industry"),
		array( "value" => "Arts and Entertainment|Music Instruction", "label" => "  &nbsp; &nbsp; &nbsp;  > Music Instruction"),
		array( "value" => "Arts and Entertainment|Painting", "label" => "  &nbsp; &nbsp; &nbsp;  > Painting"),
		array( "value" => "Arts and Entertainment|Performing Arts", "label" => "  &nbsp; &nbsp; &nbsp;  > Performing Arts"),
		array( "value" => "Arts and Entertainment|Philosophy", "label" => "  &nbsp; &nbsp; &nbsp;  > Philosophy"),
		array( "value" => "Arts and Entertainment|Photography", "label" => "  &nbsp; &nbsp; &nbsp;  > Photography"),
		array( "value" => "Arts and Entertainment|Poetry", "label" => "  &nbsp; &nbsp; &nbsp;  > Poetry"),
		array( "value" => "Arts and Entertainment|Short Fiction", "label" => "  &nbsp; &nbsp; &nbsp;  > Short Fiction"),
		array( "value" => "Arts and Entertainment|Tattoos", "label" => "  &nbsp; &nbsp; &nbsp;  > Tattoos"),
		array( "value" => "Arts and Entertainment|Visual Graphic Arts", "label" => "  &nbsp; &nbsp; &nbsp;  > Visual Graphic Arts"),
		array( "label" => "Automotive", "value" => "Automotive"),
		array( "value" => "Automotive|ATV", "label" => "  &nbsp; &nbsp; &nbsp;  > ATV"),
		array( "value" => "Automotive|Buying Selling Auctions", "label" => "  &nbsp; &nbsp; &nbsp;  > Buying Selling Auctions"),
		array( "value" => "Automotive|Car Detailing Customization", "label" => "  &nbsp; &nbsp; &nbsp;  > Car Detailing Customization"),
		array( "value" => "Automotive|Classic Cars", "label" => "  &nbsp; &nbsp; &nbsp;  > Classic Cars"),
		array( "value" => "Automotive|Hybrid Energy Efficient", "label" => "  &nbsp; &nbsp; &nbsp;  > Hybrid Energy Efficient"),
		array( "value" => "Automotive|Mobile Audio Video", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Audio Video"),
		array( "value" => "Automotive|Motorcycles", "label" => "  &nbsp; &nbsp; &nbsp;  > Motorcycles"),
		array( "value" => "Automotive|Repairs", "label" => "  &nbsp; &nbsp; &nbsp;  > Repairs"),
		array( "value" => "Automotive|RV", "label" => "  &nbsp; &nbsp; &nbsp;  > RV"),
		array( "value" => "Automotive|SUVs", "label" => "  &nbsp; &nbsp; &nbsp;  > SUVs"),
		array( "value" => "Automotive|Trucks", "label" => "  &nbsp; &nbsp; &nbsp;  > Trucks"),
		array( "value" => "Automotive|Vans", "label" => "  &nbsp; &nbsp; &nbsp;  > Vans"),
		array( "label" => "Book Reviews", "value" => "Book Reviews"),
		array( "value" => "Book Reviews|Almanacs", "label" => "  &nbsp; &nbsp; &nbsp;  > Almanacs"),
		array( "value" => "Book Reviews|Arts Photography", "label" => "  &nbsp; &nbsp; &nbsp;  > Arts Photography"),
		array( "value" => "Book Reviews|Biographies Memoirs", "label" => "  &nbsp; &nbsp; &nbsp;  > Biographies Memoirs"),
		array( "value" => "Book Reviews|Biology", "label" => "  &nbsp; &nbsp; &nbsp;  > Biology"),
		array( "value" => "Book Reviews|Business", "label" => "  &nbsp; &nbsp; &nbsp;  > Business"),
		array( "value" => "Book Reviews|Careers", "label" => "  &nbsp; &nbsp; &nbsp;  > Careers"),
		array( "value" => "Book Reviews|Childrens Books", "label" => "  &nbsp; &nbsp; &nbsp;  > Childrens Books"),
		array( "value" => "Book Reviews|Comics Humor", "label" => "  &nbsp; &nbsp; &nbsp;  > Comics Humor"),
		array( "value" => "Book Reviews|Computers", "label" => "  &nbsp; &nbsp; &nbsp;  > Computers"),
		array( "value" => "Book Reviews|Cookery Cookbook", "label" => "  &nbsp; &nbsp; &nbsp;  > Cookery Cookbook"),
		array( "value" => "Book Reviews|Current Affairs", "label" => "  &nbsp; &nbsp; &nbsp;  > Current Affairs"),
		array( "value" => "Book Reviews|Dance", "label" => "  &nbsp; &nbsp; &nbsp;  > Dance"),
		array( "value" => "Book Reviews|Economics", "label" => "  &nbsp; &nbsp; &nbsp;  > Economics"),
		array( "value" => "Book Reviews|Educational Science", "label" => "  &nbsp; &nbsp; &nbsp;  > Educational Science"),
		array( "value" => "Book Reviews|Fiction", "label" => "  &nbsp; &nbsp; &nbsp;  > Fiction"),
		array( "value" => "Book Reviews|Health Mind Body", "label" => "  &nbsp; &nbsp; &nbsp;  > Health Mind Body"),
		array( "value" => "Book Reviews|History", "label" => "  &nbsp; &nbsp; &nbsp;  > History"),
		array( "value" => "Book Reviews|Home Garden", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Garden"),
		array( "value" => "Book Reviews|Home School Curriculum", "label" => "  &nbsp; &nbsp; &nbsp;  > Home School Curriculum"),
		array( "value" => "Book Reviews|Inspirational Fiction", "label" => "  &nbsp; &nbsp; &nbsp;  > Inspirational Fiction"),
		array( "value" => "Book Reviews|Internet", "label" => "  &nbsp; &nbsp; &nbsp;  > Internet"),
		array( "value" => "Book Reviews|Internet Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Internet Marketing"),
		array( "value" => "Book Reviews|Law Legal", "label" => "  &nbsp; &nbsp; &nbsp;  > Law Legal"),
		array( "value" => "Book Reviews|Lifestyle", "label" => "  &nbsp; &nbsp; &nbsp;  > Lifestyle"),
		array( "value" => "Book Reviews|Literary Classics", "label" => "  &nbsp; &nbsp; &nbsp;  > Literary Classics"),
		array( "value" => "Book Reviews|Magazines", "label" => "  &nbsp; &nbsp; &nbsp;  > Magazines"),
		array( "value" => "Book Reviews|Maps Guides", "label" => "  &nbsp; &nbsp; &nbsp;  > Maps Guides"),
		array( "value" => "Book Reviews|Mens", "label" => "  &nbsp; &nbsp; &nbsp;  > Mens"),
		array( "value" => "Book Reviews|Multicultural", "label" => "  &nbsp; &nbsp; &nbsp;  > Multicultural"),
		array( "value" => "Book Reviews|Music", "label" => "  &nbsp; &nbsp; &nbsp;  > Music"),
		array( "value" => "Book Reviews|Mysteries Thrillers", "label" => "  &nbsp; &nbsp; &nbsp;  > Mysteries Thrillers"),
		array( "value" => "Book Reviews|Non Fiction", "label" => "  &nbsp; &nbsp; &nbsp;  > Non Fiction"),
		array( "value" => "Book Reviews|Personal Finance", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Finance"),
		array( "value" => "Book Reviews|Pets", "label" => "  &nbsp; &nbsp; &nbsp;  > Pets"),
		array( "value" => "Book Reviews|Philosophy", "label" => "  &nbsp; &nbsp; &nbsp;  > Philosophy"),
		array( "value" => "Book Reviews|Physics", "label" => "  &nbsp; &nbsp; &nbsp;  > Physics"),
		array( "value" => "Book Reviews|Poetry Playscripts", "label" => "  &nbsp; &nbsp; &nbsp;  > Poetry Playscripts"),
		array( "value" => "Book Reviews|Politics", "label" => "  &nbsp; &nbsp; &nbsp;  > Politics"),
		array( "value" => "Book Reviews|Psychology", "label" => "  &nbsp; &nbsp; &nbsp;  > Psychology"),
		array( "value" => "Book Reviews|Reference Encyclopedia Dictionary", "label" => "  &nbsp; &nbsp; &nbsp;  > Reference Encyclopedia Dictionary"),
		array( "value" => "Book Reviews|Romance", "label" => "  &nbsp; &nbsp; &nbsp;  > Romance"),
		array( "value" => "Book Reviews|SciFi Fantasy Horror", "label" => "  &nbsp; &nbsp; &nbsp;  > SciFi Fantasy Horror"),
		array( "value" => "Book Reviews|Self Help", "label" => "  &nbsp; &nbsp; &nbsp;  > Self Help"),
		array( "value" => "Book Reviews|Short Stories", "label" => "  &nbsp; &nbsp; &nbsp;  > Short Stories"),
		array( "value" => "Book Reviews|Spirituality Religion", "label" => "  &nbsp; &nbsp; &nbsp;  > Spirituality Religion"),
		array( "value" => "Book Reviews|Sports Literature", "label" => "  &nbsp; &nbsp; &nbsp;  > Sports Literature"),
		array( "value" => "Book Reviews|Travel Leisure", "label" => "  &nbsp; &nbsp; &nbsp;  > Travel Leisure"),
		array( "value" => "Book Reviews|True Crime", "label" => "  &nbsp; &nbsp; &nbsp;  > True Crime"),
		array( "value" => "Book Reviews|Wealth Building", "label" => "  &nbsp; &nbsp; &nbsp;  > Wealth Building"),
		array( "value" => "Book Reviews|Weight Loss Diet", "label" => "  &nbsp; &nbsp; &nbsp;  > Weight Loss Diet"),
		array( "value" => "Book Reviews|Westerns", "label" => "  &nbsp; &nbsp; &nbsp;  > Westerns"),
		array( "value" => "Book Reviews|Womens", "label" => "  &nbsp; &nbsp; &nbsp;  > Womens"),
		array( "value" => "Book Reviews|Young Adults", "label" => "  &nbsp; &nbsp; &nbsp;  > Young Adults"),
		array( "label" => "Business", "value" => "Business"),
		array( "value" => "Business|Accounting", "label" => "  &nbsp; &nbsp; &nbsp;  > Accounting"),
		array( "value" => "Business|Accounting Payroll", "label" => "  &nbsp; &nbsp; &nbsp;  > Accounting Payroll"),
		array( "value" => "Business|Advertising", "label" => "  &nbsp; &nbsp; &nbsp;  > Advertising"),
		array( "value" => "Business|Agriculture", "label" => "  &nbsp; &nbsp; &nbsp;  > Agriculture"),
		array( "value" => "Business|Architecture and Interior Design", "label" => "  &nbsp; &nbsp; &nbsp;  > Architecture and Interior Design"),
		array( "value" => "Business|Branding", "label" => "  &nbsp; &nbsp; &nbsp;  > Branding"),
		array( "value" => "Business|Business Travel", "label" => "  &nbsp; &nbsp; &nbsp;  > Business Travel"),
		array( "value" => "Business|Career Advice", "label" => "  &nbsp; &nbsp; &nbsp;  > Career Advice"),
		array( "value" => "Business|Careers Employment", "label" => "  &nbsp; &nbsp; &nbsp;  > Careers Employment"),
		array( "value" => "Business|Change Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Change Management"),
		array( "value" => "Business|Construction Industry", "label" => "  &nbsp; &nbsp; &nbsp;  > Construction Industry"),
		array( "value" => "Business|Consulting", "label" => "  &nbsp; &nbsp; &nbsp;  > Consulting"),
		array( "value" => "Business|Continuity Disaster Recovery", "label" => "  &nbsp; &nbsp; &nbsp;  > Continuity Disaster Recovery"),
		array( "value" => "Business|Customer Service", "label" => "  &nbsp; &nbsp; &nbsp;  > Customer Service"),
		array( "value" => "Business|Entrepreneurialism", "label" => "  &nbsp; &nbsp; &nbsp;  > Entrepreneurialism"),
		array( "value" => "Business|Ethics", "label" => "  &nbsp; &nbsp; &nbsp;  > Ethics"),
		array( "value" => "Business|Franchising", "label" => "  &nbsp; &nbsp; &nbsp;  > Franchising"),
		array( "value" => "Business|Fundraising", "label" => "  &nbsp; &nbsp; &nbsp;  > Fundraising"),
		array( "value" => "Business|Furnishings and Supplies", "label" => "  &nbsp; &nbsp; &nbsp;  > Furnishings and Supplies"),
		array( "value" => "Business|Human Resources", "label" => "  &nbsp; &nbsp; &nbsp;  > Human Resources"),
		array( "value" => "Business|Industrial Mechanical", "label" => "  &nbsp; &nbsp; &nbsp;  > Industrial Mechanical"),
		array( "value" => "Business|Innovation", "label" => "  &nbsp; &nbsp; &nbsp;  > Innovation"),
		array( "value" => "Business|International Business", "label" => "  &nbsp; &nbsp; &nbsp;  > International Business"),
		array( "value" => "Business|Interviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Interviews"),
		array( "value" => "Business|Job Search Techniques", "label" => "  &nbsp; &nbsp; &nbsp;  > Job Search Techniques"),
		array( "value" => "Business|Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Management"),
		array( "value" => "Business|Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Marketing"),
		array( "value" => "Business|Marketing Direct", "label" => "  &nbsp; &nbsp; &nbsp;  > Marketing Direct"),
		array( "value" => "Business|Negotiation", "label" => "  &nbsp; &nbsp; &nbsp;  > Negotiation"),
		array( "value" => "Business|Networking", "label" => "  &nbsp; &nbsp; &nbsp;  > Networking"),
		array( "value" => "Business|Non Profit", "label" => "  &nbsp; &nbsp; &nbsp;  > Non Profit"),
		array( "value" => "Business|Outsourcing", "label" => "  &nbsp; &nbsp; &nbsp;  > Outsourcing"),
		array( "value" => "Business|PR", "label" => "  &nbsp; &nbsp; &nbsp;  > PR"),
		array( "value" => "Business|Presentation", "label" => "  &nbsp; &nbsp; &nbsp;  > Presentation"),
		array( "value" => "Business|Productivity", "label" => "  &nbsp; &nbsp; &nbsp;  > Productivity"),
		array( "value" => "Business|Restaurant Industry", "label" => "  &nbsp; &nbsp; &nbsp;  > Restaurant Industry"),
		array( "value" => "Business|Resumes Cover Letters", "label" => "  &nbsp; &nbsp; &nbsp;  > Resumes Cover Letters"),
		array( "value" => "Business|Retail", "label" => "  &nbsp; &nbsp; &nbsp;  > Retail"),
		array( "value" => "Business|Risk Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Risk Management"),
		array( "value" => "Business|Sales", "label" => "  &nbsp; &nbsp; &nbsp;  > Sales"),
		array( "value" => "Business|Sales Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Sales Management"),
		array( "value" => "Business|Sales Teleselling", "label" => "  &nbsp; &nbsp; &nbsp;  > Sales Teleselling"),
		array( "value" => "Business|Sales Training", "label" => "  &nbsp; &nbsp; &nbsp;  > Sales Training"),
		array( "value" => "Business|Security", "label" => "  &nbsp; &nbsp; &nbsp;  > Security"),
		array( "value" => "Business|Small Business", "label" => "  &nbsp; &nbsp; &nbsp;  > Small Business"),
		array( "value" => "Business|Solo Professionals", "label" => "  &nbsp; &nbsp; &nbsp;  > Solo Professionals"),
		array( "value" => "Business|Strategic Planning", "label" => "  &nbsp; &nbsp; &nbsp;  > Strategic Planning"),
		array( "value" => "Business|Team Building", "label" => "  &nbsp; &nbsp; &nbsp;  > Team Building"),
		array( "value" => "Business|Top7 or 10 Tips", "label" => "  &nbsp; &nbsp; &nbsp;  > Top7 or 10 Tips"),
		array( "value" => "Business|Venture Capital", "label" => "  &nbsp; &nbsp; &nbsp;  > Venture Capital"),
		array( "value" => "Business|Workplace Communication", "label" => "  &nbsp; &nbsp; &nbsp;  > Workplace Communication"),
		array( "value" => "Business|Workplace Safety", "label" => "  &nbsp; &nbsp; &nbsp;  > Workplace Safety"),
		array( "label" => "Cancer", "value" => "Cancer"),
		array( "value" => "Cancer|Brain Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Brain Cancer"),
		array( "value" => "Cancer|Breast Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Breast Cancer"),
		array( "value" => "Cancer|Colon Rectal Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Colon Rectal Cancer"),
		array( "value" => "Cancer|Leukemia Lymphoma Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Leukemia Lymphoma Cancer"),
		array( "value" => "Cancer|Lung Mesothelioma Asbestos", "label" => "  &nbsp; &nbsp; &nbsp;  > Lung Mesothelioma Asbestos"),
		array( "value" => "Cancer|Ovarian Cervical Uterine Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Ovarian Cervical Uterine Cancer"),
		array( "value" => "Cancer|Prostate Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Prostate Cancer"),
		array( "value" => "Cancer|Skin Cancer", "label" => "  &nbsp; &nbsp; &nbsp;  > Skin Cancer"),
		array( "label" => "Communications", "value" => "Communications"),
		array( "value" => "Communications|Broadband Internet", "label" => "  &nbsp; &nbsp; &nbsp;  > Broadband Internet"),
		array( "value" => "Communications|Fax", "label" => "  &nbsp; &nbsp; &nbsp;  > Fax"),
		array( "value" => "Communications|GPS", "label" => "  &nbsp; &nbsp; &nbsp;  > GPS"),
		array( "value" => "Communications|Mobile Cell Phone", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Cell Phone"),
		array( "value" => "Communications|Mobile Cell Phone Accessories", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Cell Phone Accessories"),
		array( "value" => "Communications|Mobile Cell Phone Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Cell Phone Reviews"),
		array( "value" => "Communications|Mobile Cell Phone SMS", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Cell Phone SMS"),
		array( "value" => "Communications|Phone Conferencing", "label" => "  &nbsp; &nbsp; &nbsp;  > Phone Conferencing"),
		array( "value" => "Communications|Radio", "label" => "  &nbsp; &nbsp; &nbsp;  > Radio"),
		array( "value" => "Communications|Satellite Radio", "label" => "  &nbsp; &nbsp; &nbsp;  > Satellite Radio"),
		array( "value" => "Communications|Satellite TV", "label" => "  &nbsp; &nbsp; &nbsp;  > Satellite TV"),
		array( "value" => "Communications|Telephone Systems", "label" => "  &nbsp; &nbsp; &nbsp;  > Telephone Systems"),
		array( "value" => "Communications|Video Conferencing", "label" => "  &nbsp; &nbsp; &nbsp;  > Video Conferencing"),
		array( "value" => "Communications|VOIP", "label" => "  &nbsp; &nbsp; &nbsp;  > VOIP"),
		array( "label" => "Computers and Technology", "value" => "Computers and Technology"),
		array( "value" => "Computers and Technology|Certification Tests", "label" => "  &nbsp; &nbsp; &nbsp;  > Certification Tests"),
		array( "value" => "Computers and Technology|Computer Forensics", "label" => "  &nbsp; &nbsp; &nbsp;  > Computer Forensics"),
		array( "value" => "Computers and Technology|Data Recovery", "label" => "  &nbsp; &nbsp; &nbsp;  > Data Recovery"),
		array( "value" => "Computers and Technology|Hardware", "label" => "  &nbsp; &nbsp; &nbsp;  > Hardware"),
		array( "value" => "Computers and Technology|Mobile Computing", "label" => "  &nbsp; &nbsp; &nbsp;  > Mobile Computing"),
		array( "value" => "Computers and Technology|Personal Tech", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Tech"),
		array( "value" => "Computers and Technology|Programming", "label" => "  &nbsp; &nbsp; &nbsp;  > Programming"),
		array( "value" => "Computers and Technology|Registry Cleaners", "label" => "  &nbsp; &nbsp; &nbsp;  > Registry Cleaners"),
		array( "value" => "Computers and Technology|Software", "label" => "  &nbsp; &nbsp; &nbsp;  > Software"),
		array( "value" => "Computers and Technology|Spyware and Viruses", "label" => "  &nbsp; &nbsp; &nbsp;  > Spyware and Viruses"),
		array( "label" => "Finance", "value" => "Finance"),
		array( "value" => "Finance|Auto Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Auto Loans"),
		array( "value" => "Finance|Bankruptcy", "label" => "  &nbsp; &nbsp; &nbsp;  > Bankruptcy"),
		array( "value" => "Finance|Bankruptcy Lawyers", "label" => "  &nbsp; &nbsp; &nbsp;  > Bankruptcy Lawyers"),
		array( "value" => "Finance|Bankruptcy Medical", "label" => "  &nbsp; &nbsp; &nbsp;  > Bankruptcy Medical"),
		array( "value" => "Finance|Bankruptcy Personal", "label" => "  &nbsp; &nbsp; &nbsp;  > Bankruptcy Personal"),
		array( "value" => "Finance|Bankruptcy Tips Advice", "label" => "  &nbsp; &nbsp; &nbsp;  > Bankruptcy Tips Advice"),
		array( "value" => "Finance|Budgeting", "label" => "  &nbsp; &nbsp; &nbsp;  > Budgeting"),
		array( "value" => "Finance|Commercial Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Commercial Loans"),
		array( "value" => "Finance|Credit", "label" => "  &nbsp; &nbsp; &nbsp;  > Credit"),
		array( "value" => "Finance|Credit Counseling", "label" => "  &nbsp; &nbsp; &nbsp;  > Credit Counseling"),
		array( "value" => "Finance|Credit Tips", "label" => "  &nbsp; &nbsp; &nbsp;  > Credit Tips"),
		array( "value" => "Finance|Currency Trading", "label" => "  &nbsp; &nbsp; &nbsp;  > Currency Trading"),
		array( "value" => "Finance|Debt Consolidation", "label" => "  &nbsp; &nbsp; &nbsp;  > Debt Consolidation"),
		array( "value" => "Finance|Debt Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Debt Management"),
		array( "value" => "Finance|Debt Relief", "label" => "  &nbsp; &nbsp; &nbsp;  > Debt Relief"),
		array( "value" => "Finance|Estate Plan Trusts", "label" => "  &nbsp; &nbsp; &nbsp;  > Estate Plan Trusts"),
		array( "value" => "Finance|Home Equity Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Equity Loans"),
		array( "value" => "Finance|Leases Leasing", "label" => "  &nbsp; &nbsp; &nbsp;  > Leases Leasing"),
		array( "value" => "Finance|Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Loans"),
		array( "value" => "Finance|PayDay Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > PayDay Loans"),
		array( "value" => "Finance|Personal Finance", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Finance"),
		array( "value" => "Finance|Personal Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Loans"),
		array( "value" => "Finance|Philanthropy Charitable Giving", "label" => "  &nbsp; &nbsp; &nbsp;  > Philanthropy Charitable Giving"),
		array( "value" => "Finance|Structured Settlements", "label" => "  &nbsp; &nbsp; &nbsp;  > Structured Settlements"),
		array( "value" => "Finance|Student Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Student Loans"),
		array( "value" => "Finance|Taxes", "label" => "  &nbsp; &nbsp; &nbsp;  > Taxes"),
		array( "value" => "Finance|Taxes Income", "label" => "  &nbsp; &nbsp; &nbsp;  > Taxes Income"),
		array( "value" => "Finance|Taxes Property", "label" => "  &nbsp; &nbsp; &nbsp;  > Taxes Property"),
		array( "value" => "Finance|Taxes Relief", "label" => "  &nbsp; &nbsp; &nbsp;  > Taxes Relief"),
		array( "value" => "Finance|Taxes Tools", "label" => "  &nbsp; &nbsp; &nbsp;  > Taxes Tools"),
		array( "value" => "Finance|Unsecured Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > Unsecured Loans"),
		array( "value" => "Finance|VA Loans", "label" => "  &nbsp; &nbsp; &nbsp;  > VA Loans"),
		array( "value" => "Finance|Wealth Building", "label" => "  &nbsp; &nbsp; &nbsp;  > Wealth Building"),
		array( "label" => "Food and Drink", "value" => "Food and Drink"),
		array( "value" => "Food and Drink|BBQ Grilling", "label" => "  &nbsp; &nbsp; &nbsp;  > BBQ Grilling"),
		array( "value" => "Food and Drink|Chocolate", "label" => "  &nbsp; &nbsp; &nbsp;  > Chocolate"),
		array( "value" => "Food and Drink|Coffee", "label" => "  &nbsp; &nbsp; &nbsp;  > Coffee"),
		array( "value" => "Food and Drink|Cooking Supplies", "label" => "  &nbsp; &nbsp; &nbsp;  > Cooking Supplies"),
		array( "value" => "Food and Drink|Cooking Tips", "label" => "  &nbsp; &nbsp; &nbsp;  > Cooking Tips"),
		array( "value" => "Food and Drink|Crockpot Recipes", "label" => "  &nbsp; &nbsp; &nbsp;  > Crockpot Recipes"),
		array( "value" => "Food and Drink|Desserts", "label" => "  &nbsp; &nbsp; &nbsp;  > Desserts"),
		array( "value" => "Food and Drink|Home Brewing", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Brewing"),
		array( "value" => "Food and Drink|Low Calorie", "label" => "  &nbsp; &nbsp; &nbsp;  > Low Calorie"),
		array( "value" => "Food and Drink|Main Course", "label" => "  &nbsp; &nbsp; &nbsp;  > Main Course"),
		array( "value" => "Food and Drink|Pasta Dishes", "label" => "  &nbsp; &nbsp; &nbsp;  > Pasta Dishes"),
		array( "value" => "Food and Drink|Recipes", "label" => "  &nbsp; &nbsp; &nbsp;  > Recipes"),
		array( "value" => "Food and Drink|Restaurant Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Restaurant Reviews"),
		array( "value" => "Food and Drink|Salads", "label" => "  &nbsp; &nbsp; &nbsp;  > Salads"),
		array( "value" => "Food and Drink|Soups", "label" => "  &nbsp; &nbsp; &nbsp;  > Soups"),
		array( "value" => "Food and Drink|Tea", "label" => "  &nbsp; &nbsp; &nbsp;  > Tea"),
		array( "value" => "Food and Drink|Vegetarian Recipes", "label" => "  &nbsp; &nbsp; &nbsp;  > Vegetarian Recipes"),
		array( "value" => "Food and Drink|Wine Spirits", "label" => "  &nbsp; &nbsp; &nbsp;  > Wine Spirits"),
		array( "label" => "Gaming", "value" => "Gaming"),
		array( "value" => "Gaming|Communities", "label" => "  &nbsp; &nbsp; &nbsp;  > Communities"),
		array( "value" => "Gaming|Computer Games", "label" => "  &nbsp; &nbsp; &nbsp;  > Computer Games"),
		array( "value" => "Gaming|Console Games", "label" => "  &nbsp; &nbsp; &nbsp;  > Console Games"),
		array( "value" => "Gaming|Console Systems", "label" => "  &nbsp; &nbsp; &nbsp;  > Console Systems"),
		array( "value" => "Gaming|Online Gaming", "label" => "  &nbsp; &nbsp; &nbsp;  > Online Gaming"),
		array( "value" => "Gaming|Video Game Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Video Game Reviews"),
		array( "label" => "Health and Fitness", "value" => "Health and Fitness"),
		array( "value" => "Health and Fitness|Acne", "label" => "  &nbsp; &nbsp; &nbsp;  > Acne"),
		array( "value" => "Health and Fitness|Acupuncture", "label" => "  &nbsp; &nbsp; &nbsp;  > Acupuncture"),
		array( "value" => "Health and Fitness|Aerobics Cardio", "label" => "  &nbsp; &nbsp; &nbsp;  > Aerobics Cardio"),
		array( "value" => "Health and Fitness|Allergies", "label" => "  &nbsp; &nbsp; &nbsp;  > Allergies"),
		array( "value" => "Health and Fitness|Alternative", "label" => "  &nbsp; &nbsp; &nbsp;  > Alternative"),
		array( "value" => "Health and Fitness|Anti Aging", "label" => "  &nbsp; &nbsp; &nbsp;  > Anti Aging"),
		array( "value" => "Health and Fitness|Anxiety", "label" => "  &nbsp; &nbsp; &nbsp;  > Anxiety"),
		array( "value" => "Health and Fitness|Aromatherapy", "label" => "  &nbsp; &nbsp; &nbsp;  > Aromatherapy"),
		array( "value" => "Health and Fitness|Arthritis", "label" => "  &nbsp; &nbsp; &nbsp;  > Arthritis"),
		array( "value" => "Health and Fitness|Asthma", "label" => "  &nbsp; &nbsp; &nbsp;  > Asthma"),
		array( "value" => "Health and Fitness|Autism", "label" => "  &nbsp; &nbsp; &nbsp;  > Autism"),
		array( "value" => "Health and Fitness|Back Pain", "label" => "  &nbsp; &nbsp; &nbsp;  > Back Pain"),
		array( "value" => "Health and Fitness|Beauty", "label" => "  &nbsp; &nbsp; &nbsp;  > Beauty"),
		array( "value" => "Health and Fitness|Build Muscle", "label" => "  &nbsp; &nbsp; &nbsp;  > Build Muscle"),
		array( "value" => "Health and Fitness|Childhood Obesity Prevention", "label" => "  &nbsp; &nbsp; &nbsp;  > Childhood Obesity Prevention"),
		array( "value" => "Health and Fitness|Contraceptives Birth Control", "label" => "  &nbsp; &nbsp; &nbsp;  > Contraceptives Birth Control"),
		array( "value" => "Health and Fitness|Cosmetic Surgery", "label" => "  &nbsp; &nbsp; &nbsp;  > Cosmetic Surgery"),
		array( "value" => "Health and Fitness|Critical Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Critical Care"),
		array( "value" => "Health and Fitness|Dental Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Dental Care"),
		array( "value" => "Health and Fitness|Depression", "label" => "  &nbsp; &nbsp; &nbsp;  > Depression"),
		array( "value" => "Health and Fitness|Detoxification", "label" => "  &nbsp; &nbsp; &nbsp;  > Detoxification"),
		array( "value" => "Health and Fitness|Developmental Disabilities", "label" => "  &nbsp; &nbsp; &nbsp;  > Developmental Disabilities"),
		array( "value" => "Health and Fitness|Diabetes", "label" => "  &nbsp; &nbsp; &nbsp;  > Diabetes"),
		array( "value" => "Health and Fitness|Disability", "label" => "  &nbsp; &nbsp; &nbsp;  > Disability"),
		array( "value" => "Health and Fitness|Diseases", "label" => "  &nbsp; &nbsp; &nbsp;  > Diseases"),
		array( "value" => "Health and Fitness|Diseases Multiple Sclerosis", "label" => "  &nbsp; &nbsp; &nbsp;  > Diseases Multiple Sclerosis"),
		array( "value" => "Health and Fitness|Diseases STDs", "label" => "  &nbsp; &nbsp; &nbsp;  > Diseases STDs"),
		array( "value" => "Health and Fitness|Drug Abuse", "label" => "  &nbsp; &nbsp; &nbsp;  > Drug Abuse"),
		array( "value" => "Health and Fitness|Ears Hearing", "label" => "  &nbsp; &nbsp; &nbsp;  > Ears Hearing"),
		array( "value" => "Health and Fitness|Eating Disorders", "label" => "  &nbsp; &nbsp; &nbsp;  > Eating Disorders"),
		array( "value" => "Health and Fitness|Eczema", "label" => "  &nbsp; &nbsp; &nbsp;  > Eczema"),
		array( "value" => "Health and Fitness|Emotional Freedom Technique", "label" => "  &nbsp; &nbsp; &nbsp;  > Emotional Freedom Technique"),
		array( "value" => "Health and Fitness|Environmental Issues", "label" => "  &nbsp; &nbsp; &nbsp;  > Environmental Issues"),
		array( "value" => "Health and Fitness|Ergonomics", "label" => "  &nbsp; &nbsp; &nbsp;  > Ergonomics"),
		array( "value" => "Health and Fitness|Exercise", "label" => "  &nbsp; &nbsp; &nbsp;  > Exercise"),
		array( "value" => "Health and Fitness|Eyes Vision", "label" => "  &nbsp; &nbsp; &nbsp;  > Eyes Vision"),
		array( "value" => "Health and Fitness|Fitness Equipment", "label" => "  &nbsp; &nbsp; &nbsp;  > Fitness Equipment"),
		array( "value" => "Health and Fitness|Foot Health", "label" => "  &nbsp; &nbsp; &nbsp;  > Foot Health"),
		array( "value" => "Health and Fitness|Hair Loss", "label" => "  &nbsp; &nbsp; &nbsp;  > Hair Loss"),
		array( "value" => "Health and Fitness|Hand Wrist Pain", "label" => "  &nbsp; &nbsp; &nbsp;  > Hand Wrist Pain"),
		array( "value" => "Health and Fitness|Headaches Migraines", "label" => "  &nbsp; &nbsp; &nbsp;  > Headaches Migraines"),
		array( "value" => "Health and Fitness|Healing Arts", "label" => "  &nbsp; &nbsp; &nbsp;  > Healing Arts"),
		array( "value" => "Health and Fitness|Healthcare Systems", "label" => "  &nbsp; &nbsp; &nbsp;  > Healthcare Systems"),
		array( "value" => "Health and Fitness|Heart Disease", "label" => "  &nbsp; &nbsp; &nbsp;  > Heart Disease"),
		array( "value" => "Health and Fitness|Heartburn and Acid Reflux", "label" => "  &nbsp; &nbsp; &nbsp;  > Heartburn and Acid Reflux"),
		array( "value" => "Health and Fitness|Hemorrhoids", "label" => "  &nbsp; &nbsp; &nbsp;  > Hemorrhoids"),
		array( "value" => "Health and Fitness|Holistic", "label" => "  &nbsp; &nbsp; &nbsp;  > Holistic"),
		array( "value" => "Health and Fitness|Home Health Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Health Care"),
		array( "value" => "Health and Fitness|Hypertension", "label" => "  &nbsp; &nbsp; &nbsp;  > Hypertension"),
		array( "value" => "Health and Fitness|Massage", "label" => "  &nbsp; &nbsp; &nbsp;  > Massage"),
		array( "value" => "Health and Fitness|Medicine", "label" => "  &nbsp; &nbsp; &nbsp;  > Medicine"),
		array( "value" => "Health and Fitness|Meditation", "label" => "  &nbsp; &nbsp; &nbsp;  > Meditation"),
		array( "value" => "Health and Fitness|Mens Issues", "label" => "  &nbsp; &nbsp; &nbsp;  > Mens Issues"),
		array( "value" => "Health and Fitness|Mental Health", "label" => "  &nbsp; &nbsp; &nbsp;  > Mental Health"),
		array( "value" => "Health and Fitness|Mind Body Spirit", "label" => "  &nbsp; &nbsp; &nbsp;  > Mind Body Spirit"),
		array( "value" => "Health and Fitness|Mood Disorders", "label" => "  &nbsp; &nbsp; &nbsp;  > Mood Disorders"),
		array( "value" => "Health and Fitness|Nursing", "label" => "  &nbsp; &nbsp; &nbsp;  > Nursing"),
		array( "value" => "Health and Fitness|Nutrition", "label" => "  &nbsp; &nbsp; &nbsp;  > Nutrition"),
		array( "value" => "Health and Fitness|Obesity", "label" => "  &nbsp; &nbsp; &nbsp;  > Obesity"),
		array( "value" => "Health and Fitness|Pain Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Pain Management"),
		array( "value" => "Health and Fitness|Personal Training", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Training"),
		array( "value" => "Health and Fitness|Phobias", "label" => "  &nbsp; &nbsp; &nbsp;  > Phobias"),
		array( "value" => "Health and Fitness|Physical Therapy", "label" => "  &nbsp; &nbsp; &nbsp;  > Physical Therapy"),
		array( "value" => "Health and Fitness|Pilates", "label" => "  &nbsp; &nbsp; &nbsp;  > Pilates"),
		array( "value" => "Health and Fitness|Popular Diets", "label" => "  &nbsp; &nbsp; &nbsp;  > Popular Diets"),
		array( "value" => "Health and Fitness|Quit Smoking", "label" => "  &nbsp; &nbsp; &nbsp;  > Quit Smoking"),
		array( "value" => "Health and Fitness|Self Hypnosis", "label" => "  &nbsp; &nbsp; &nbsp;  > Self Hypnosis"),
		array( "value" => "Health and Fitness|Skin Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Skin Care"),
		array( "value" => "Health and Fitness|Sleep Snoring", "label" => "  &nbsp; &nbsp; &nbsp;  > Sleep Snoring"),
		array( "value" => "Health and Fitness|Spa and Wellness", "label" => "  &nbsp; &nbsp; &nbsp;  > Spa and Wellness"),
		array( "value" => "Health and Fitness|Speech Pathology", "label" => "  &nbsp; &nbsp; &nbsp;  > Speech Pathology"),
		array( "value" => "Health and Fitness|Supplements", "label" => "  &nbsp; &nbsp; &nbsp;  > Supplements"),
		array( "value" => "Health and Fitness|Thyroid", "label" => "  &nbsp; &nbsp; &nbsp;  > Thyroid"),
		array( "value" => "Health and Fitness|Weight Loss", "label" => "  &nbsp; &nbsp; &nbsp;  > Weight Loss"),
		array( "value" => "Health and Fitness|Womens Issues", "label" => "  &nbsp; &nbsp; &nbsp;  > Womens Issues"),
		array( "value" => "Health and Fitness|Yoga", "label" => "  &nbsp; &nbsp; &nbsp;  > Yoga"),
		array( "label" => "Home and Family", "value" => "Home and Family"),
		array( "value" => "Home and Family|Adolescent Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Adolescent Care"),
		array( "value" => "Home and Family|Adoption Foster Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Adoption Foster Care"),
		array( "value" => "Home and Family|Antiques", "label" => "  &nbsp; &nbsp; &nbsp;  > Antiques"),
		array( "value" => "Home and Family|Babies Toddler", "label" => "  &nbsp; &nbsp; &nbsp;  > Babies Toddler"),
		array( "value" => "Home and Family|Baby Boomer", "label" => "  &nbsp; &nbsp; &nbsp;  > Baby Boomer"),
		array( "value" => "Home and Family|Baby Showers", "label" => "  &nbsp; &nbsp; &nbsp;  > Baby Showers"),
		array( "value" => "Home and Family|Board Games", "label" => "  &nbsp; &nbsp; &nbsp;  > Board Games"),
		array( "value" => "Home and Family|Card Games", "label" => "  &nbsp; &nbsp; &nbsp;  > Card Games"),
		array( "value" => "Home and Family|Crafts Hobbies", "label" => "  &nbsp; &nbsp; &nbsp;  > Crafts Hobbies"),
		array( "value" => "Home and Family|Crafts Supplies", "label" => "  &nbsp; &nbsp; &nbsp;  > Crafts Supplies"),
		array( "value" => "Home and Family|Death Dying", "label" => "  &nbsp; &nbsp; &nbsp;  > Death Dying"),
		array( "value" => "Home and Family|Early Childhood Education", "label" => "  &nbsp; &nbsp; &nbsp;  > Early Childhood Education"),
		array( "value" => "Home and Family|Elder Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Elder Care"),
		array( "value" => "Home and Family|Entertaining", "label" => "  &nbsp; &nbsp; &nbsp;  > Entertaining"),
		array( "value" => "Home and Family|Fatherhood", "label" => "  &nbsp; &nbsp; &nbsp;  > Fatherhood"),
		array( "value" => "Home and Family|Gardening", "label" => "  &nbsp; &nbsp; &nbsp;  > Gardening"),
		array( "value" => "Home and Family|Genealogy Family Trees", "label" => "  &nbsp; &nbsp; &nbsp;  > Genealogy Family Trees"),
		array( "value" => "Home and Family|Grandparenting", "label" => "  &nbsp; &nbsp; &nbsp;  > Grandparenting"),
		array( "value" => "Home and Family|Holidays", "label" => "  &nbsp; &nbsp; &nbsp;  > Holidays"),
		array( "value" => "Home and Family|Motherhood", "label" => "  &nbsp; &nbsp; &nbsp;  > Motherhood"),
		array( "value" => "Home and Family|Parenting", "label" => "  &nbsp; &nbsp; &nbsp;  > Parenting"),
		array( "value" => "Home and Family|Parties", "label" => "  &nbsp; &nbsp; &nbsp;  > Parties"),
		array( "value" => "Home and Family|Pregnancy", "label" => "  &nbsp; &nbsp; &nbsp;  > Pregnancy"),
		array( "value" => "Home and Family|Retirement", "label" => "  &nbsp; &nbsp; &nbsp;  > Retirement"),
		array( "value" => "Home and Family|Scrapbooking", "label" => "  &nbsp; &nbsp; &nbsp;  > Scrapbooking"),
		array( "value" => "Home and Family|Special Education", "label" => "  &nbsp; &nbsp; &nbsp;  > Special Education"),
		array( "value" => "Home and Family|Step Parenting", "label" => "  &nbsp; &nbsp; &nbsp;  > Step Parenting"),
		array( "value" => "Home and Family|Woodworking", "label" => "  &nbsp; &nbsp; &nbsp;  > Woodworking"),
		array( "label" => "Home Based Business", "value" => "Home Based Business"),
		array( "value" => "Home Based Business|Network Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Network Marketing"),
		array( "label" => "Home Improvement", "value" => "Home Improvement"),
		array( "value" => "Home Improvement|Appliances", "label" => "  &nbsp; &nbsp; &nbsp;  > Appliances"),
		array( "value" => "Home Improvement|Audio Video", "label" => "  &nbsp; &nbsp; &nbsp;  > Audio Video"),
		array( "value" => "Home Improvement|Bath and Shower", "label" => "  &nbsp; &nbsp; &nbsp;  > Bath and Shower"),
		array( "value" => "Home Improvement|Cabinets", "label" => "  &nbsp; &nbsp; &nbsp;  > Cabinets"),
		array( "value" => "Home Improvement|Cleaning Tips and Tools", "label" => "  &nbsp; &nbsp; &nbsp;  > Cleaning Tips and Tools"),
		array( "value" => "Home Improvement|Concrete", "label" => "  &nbsp; &nbsp; &nbsp;  > Concrete"),
		array( "value" => "Home Improvement|DIY", "label" => "  &nbsp; &nbsp; &nbsp;  > DIY"),
		array( "value" => "Home Improvement|Doors", "label" => "  &nbsp; &nbsp; &nbsp;  > Doors"),
		array( "value" => "Home Improvement|Electrical", "label" => "  &nbsp; &nbsp; &nbsp;  > Electrical"),
		array( "value" => "Home Improvement|Energy Efficiency", "label" => "  &nbsp; &nbsp; &nbsp;  > Energy Efficiency"),
		array( "value" => "Home Improvement|Feng Shui", "label" => "  &nbsp; &nbsp; &nbsp;  > Feng Shui"),
		array( "value" => "Home Improvement|Flooring", "label" => "  &nbsp; &nbsp; &nbsp;  > Flooring"),
		array( "value" => "Home Improvement|Foundation", "label" => "  &nbsp; &nbsp; &nbsp;  > Foundation"),
		array( "value" => "Home Improvement|Furniture", "label" => "  &nbsp; &nbsp; &nbsp;  > Furniture"),
		array( "value" => "Home Improvement|Green Living", "label" => "  &nbsp; &nbsp; &nbsp;  > Green Living"),
		array( "value" => "Home Improvement|Heating and Air Conditioning", "label" => "  &nbsp; &nbsp; &nbsp;  > Heating and Air Conditioning"),
		array( "value" => "Home Improvement|Home Inspections", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Inspections"),
		array( "value" => "Home Improvement|House Plans", "label" => "  &nbsp; &nbsp; &nbsp;  > House Plans"),
		array( "value" => "Home Improvement|Interior Design and Decorating", "label" => "  &nbsp; &nbsp; &nbsp;  > Interior Design and Decorating"),
		array( "value" => "Home Improvement|Kitchen Improvements", "label" => "  &nbsp; &nbsp; &nbsp;  > Kitchen Improvements"),
		array( "value" => "Home Improvement|Landscaping Outdoor Decorating", "label" => "  &nbsp; &nbsp; &nbsp;  > Landscaping Outdoor Decorating"),
		array( "value" => "Home Improvement|Lighting", "label" => "  &nbsp; &nbsp; &nbsp;  > Lighting"),
		array( "value" => "Home Improvement|New Construction", "label" => "  &nbsp; &nbsp; &nbsp;  > New Construction"),
		array( "value" => "Home Improvement|Painting", "label" => "  &nbsp; &nbsp; &nbsp;  > Painting"),
		array( "value" => "Home Improvement|Patio Deck", "label" => "  &nbsp; &nbsp; &nbsp;  > Patio Deck"),
		array( "value" => "Home Improvement|Pest Control", "label" => "  &nbsp; &nbsp; &nbsp;  > Pest Control"),
		array( "value" => "Home Improvement|Plumbing", "label" => "  &nbsp; &nbsp; &nbsp;  > Plumbing"),
		array( "value" => "Home Improvement|Remodeling", "label" => "  &nbsp; &nbsp; &nbsp;  > Remodeling"),
		array( "value" => "Home Improvement|Roofing", "label" => "  &nbsp; &nbsp; &nbsp;  > Roofing"),
		array( "value" => "Home Improvement|Security", "label" => "  &nbsp; &nbsp; &nbsp;  > Security"),
		array( "value" => "Home Improvement|Stone Brick", "label" => "  &nbsp; &nbsp; &nbsp;  > Stone Brick"),
		array( "value" => "Home Improvement|Storage Garage", "label" => "  &nbsp; &nbsp; &nbsp;  > Storage Garage"),
		array( "value" => "Home Improvement|Swimming Pools Spas", "label" => "  &nbsp; &nbsp; &nbsp;  > Swimming Pools Spas"),
		array( "value" => "Home Improvement|Tools and Equipment", "label" => "  &nbsp; &nbsp; &nbsp;  > Tools and Equipment"),
		array( "value" => "Home Improvement|Windows", "label" => "  &nbsp; &nbsp; &nbsp;  > Windows"),
		array( "value" => "Home Improvement|Yard Equipment", "label" => "  &nbsp; &nbsp; &nbsp;  > Yard Equipment"),
		array( "label" => "Insurance", "value" => "Insurance"),
		array( "value" => "Insurance|Agents Marketers", "label" => "  &nbsp; &nbsp; &nbsp;  > Agents Marketers"),
		array( "value" => "Insurance|Car Auto", "label" => "  &nbsp; &nbsp; &nbsp;  > Car Auto"),
		array( "value" => "Insurance|Commercial", "label" => "  &nbsp; &nbsp; &nbsp;  > Commercial"),
		array( "value" => "Insurance|Dental", "label" => "  &nbsp; &nbsp; &nbsp;  > Dental"),
		array( "value" => "Insurance|Disability", "label" => "  &nbsp; &nbsp; &nbsp;  > Disability"),
		array( "value" => "Insurance|Flood", "label" => "  &nbsp; &nbsp; &nbsp;  > Flood"),
		array( "value" => "Insurance|Health", "label" => "  &nbsp; &nbsp; &nbsp;  > Health"),
		array( "value" => "Insurance|Home Owners Renters", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Owners Renters"),
		array( "value" => "Insurance|Life Annuities", "label" => "  &nbsp; &nbsp; &nbsp;  > Life Annuities"),
		array( "value" => "Insurance|Long Term Care", "label" => "  &nbsp; &nbsp; &nbsp;  > Long Term Care"),
		array( "value" => "Insurance|Medical Billing", "label" => "  &nbsp; &nbsp; &nbsp;  > Medical Billing"),
		array( "value" => "Insurance|Personal Property", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Property"),
		array( "value" => "Insurance|Pet", "label" => "  &nbsp; &nbsp; &nbsp;  > Pet"),
		array( "value" => "Insurance|RV Motorcycle", "label" => "  &nbsp; &nbsp; &nbsp;  > RV Motorcycle"),
		array( "value" => "Insurance|Supplemental", "label" => "  &nbsp; &nbsp; &nbsp;  > Supplemental"),
		array( "value" => "Insurance|Travel", "label" => "  &nbsp; &nbsp; &nbsp;  > Travel"),
		array( "value" => "Insurance|Umbrella", "label" => "  &nbsp; &nbsp; &nbsp;  > Umbrella"),
		array( "value" => "Insurance|Vision", "label" => "  &nbsp; &nbsp; &nbsp;  > Vision"),
		array( "value" => "Insurance|Watercraft", "label" => "  &nbsp; &nbsp; &nbsp;  > Watercraft"),
		array( "value" => "Insurance|Workers Compensation", "label" => "  &nbsp; &nbsp; &nbsp;  > Workers Compensation"),
		array( "label" => "Internet and Businesses Online", "value" => "Internet and Businesses Online"),
		array( "value" => "Internet and Businesses Online|Affiliate Revenue", "label" => "  &nbsp; &nbsp; &nbsp;  > Affiliate Revenue"),
		array( "value" => "Internet and Businesses Online|Auctions", "label" => "  &nbsp; &nbsp; &nbsp;  > Auctions"),
		array( "value" => "Internet and Businesses Online|Audio Streaming", "label" => "  &nbsp; &nbsp; &nbsp;  > Audio Streaming"),
		array( "value" => "Internet and Businesses Online|Autoresponders", "label" => "  &nbsp; &nbsp; &nbsp;  > Autoresponders"),
		array( "value" => "Internet and Businesses Online|Banner Advertising", "label" => "  &nbsp; &nbsp; &nbsp;  > Banner Advertising"),
		array( "value" => "Internet and Businesses Online|Blogging", "label" => "  &nbsp; &nbsp; &nbsp;  > Blogging"),
		array( "value" => "Internet and Businesses Online|CMS", "label" => "  &nbsp; &nbsp; &nbsp;  > CMS"),
		array( "value" => "Internet and Businesses Online|Domain Names", "label" => "  &nbsp; &nbsp; &nbsp;  > Domain Names"),
		array( "value" => "Internet and Businesses Online|E Books", "label" => "  &nbsp; &nbsp; &nbsp;  > E Books"),
		array( "value" => "Internet and Businesses Online|Ecommerce", "label" => "  &nbsp; &nbsp; &nbsp;  > Ecommerce"),
		array( "value" => "Internet and Businesses Online|Email Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Email Marketing"),
		array( "value" => "Internet and Businesses Online|Ezine Publishing", "label" => "  &nbsp; &nbsp; &nbsp;  > Ezine Publishing"),
		array( "value" => "Internet and Businesses Online|Forums", "label" => "  &nbsp; &nbsp; &nbsp;  > Forums"),
		array( "value" => "Internet and Businesses Online|Internet Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Internet Marketing"),
		array( "value" => "Internet and Businesses Online|Link Popularity", "label" => "  &nbsp; &nbsp; &nbsp;  > Link Popularity"),
		array( "value" => "Internet and Businesses Online|List Building", "label" => "  &nbsp; &nbsp; &nbsp;  > List Building"),
		array( "value" => "Internet and Businesses Online|Paid Surveys", "label" => "  &nbsp; &nbsp; &nbsp;  > Paid Surveys"),
		array( "value" => "Internet and Businesses Online|Podcasting", "label" => "  &nbsp; &nbsp; &nbsp;  > Podcasting"),
		array( "value" => "Internet and Businesses Online|PPC Advertising", "label" => "  &nbsp; &nbsp; &nbsp;  > PPC Advertising"),
		array( "value" => "Internet and Businesses Online|PPC Publishing", "label" => "  &nbsp; &nbsp; &nbsp;  > PPC Publishing"),
		array( "value" => "Internet and Businesses Online|Product Creation", "label" => "  &nbsp; &nbsp; &nbsp;  > Product Creation"),
		array( "value" => "Internet and Businesses Online|Product Launching", "label" => "  &nbsp; &nbsp; &nbsp;  > Product Launching"),
		array( "value" => "Internet and Businesses Online|RSS", "label" => "  &nbsp; &nbsp; &nbsp;  > RSS"),
		array( "value" => "Internet and Businesses Online|Search Engine Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Search Engine Marketing"),
		array( "value" => "Internet and Businesses Online|Security", "label" => "  &nbsp; &nbsp; &nbsp;  > Security"),
		array( "value" => "Internet and Businesses Online|SEO", "label" => "  &nbsp; &nbsp; &nbsp;  > SEO"),
		array( "value" => "Internet and Businesses Online|Site Promotion", "label" => "  &nbsp; &nbsp; &nbsp;  > Site Promotion"),
		array( "value" => "Internet and Businesses Online|Social Bookmarking", "label" => "  &nbsp; &nbsp; &nbsp;  > Social Bookmarking"),
		array( "value" => "Internet and Businesses Online|Social Media", "label" => "  &nbsp; &nbsp; &nbsp;  > Social Media"),
		array( "value" => "Internet and Businesses Online|Social Networking", "label" => "  &nbsp; &nbsp; &nbsp;  > Social Networking"),
		array( "value" => "Internet and Businesses Online|Spam Blocker", "label" => "  &nbsp; &nbsp; &nbsp;  > Spam Blocker"),
		array( "value" => "Internet and Businesses Online|Traffic Building", "label" => "  &nbsp; &nbsp; &nbsp;  > Traffic Building"),
		array( "value" => "Internet and Businesses Online|Video Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Video Marketing"),
		array( "value" => "Internet and Businesses Online|Video Streaming", "label" => "  &nbsp; &nbsp; &nbsp;  > Video Streaming"),
		array( "value" => "Internet and Businesses Online|Web Design", "label" => "  &nbsp; &nbsp; &nbsp;  > Web Design"),
		array( "value" => "Internet and Businesses Online|Web Development", "label" => "  &nbsp; &nbsp; &nbsp;  > Web Development"),
		array( "value" => "Internet and Businesses Online|Web Hosting", "label" => "  &nbsp; &nbsp; &nbsp;  > Web Hosting"),
		array( "label" => "Investing", "value" => "Investing"),
		array( "value" => "Investing|Day Trading", "label" => "  &nbsp; &nbsp; &nbsp;  > Day Trading"),
		array( "value" => "Investing|Futures and Commodities", "label" => "  &nbsp; &nbsp; &nbsp;  > Futures and Commodities"),
		array( "value" => "Investing|Gold Silver", "label" => "  &nbsp; &nbsp; &nbsp;  > Gold Silver"),
		array( "value" => "Investing|IRA 401k", "label" => "  &nbsp; &nbsp; &nbsp;  > IRA 401k"),
		array( "value" => "Investing|Mutual Funds", "label" => "  &nbsp; &nbsp; &nbsp;  > Mutual Funds"),
		array( "value" => "Investing|Retirement Planning", "label" => "  &nbsp; &nbsp; &nbsp;  > Retirement Planning"),
		array( "value" => "Investing|Stocks", "label" => "  &nbsp; &nbsp; &nbsp;  > Stocks"),
		array( "label" => "Kids and Teens", "value" => "Kids and Teens"),
		array( "label" => "Legal", "value" => "Legal"),
		array( "value" => "Legal|Child Custody", "label" => "  &nbsp; &nbsp; &nbsp;  > Child Custody"),
		array( "value" => "Legal|Copyright", "label" => "  &nbsp; &nbsp; &nbsp;  > Copyright"),
		array( "value" => "Legal|Corporations LLC", "label" => "  &nbsp; &nbsp; &nbsp;  > Corporations LLC"),
		array( "value" => "Legal|Criminal Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Criminal Law"),
		array( "value" => "Legal|Cyber Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Cyber Law"),
		array( "value" => "Legal|Elder Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Elder Law"),
		array( "value" => "Legal|Employment Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Employment Law"),
		array( "value" => "Legal|Family Law and Divorce", "label" => "  &nbsp; &nbsp; &nbsp;  > Family Law and Divorce"),
		array( "value" => "Legal|Identity Theft", "label" => "  &nbsp; &nbsp; &nbsp;  > Identity Theft"),
		array( "value" => "Legal|Immigration", "label" => "  &nbsp; &nbsp; &nbsp;  > Immigration"),
		array( "value" => "Legal|Intellectual Property", "label" => "  &nbsp; &nbsp; &nbsp;  > Intellectual Property"),
		array( "value" => "Legal|Labor Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Labor Law"),
		array( "value" => "Legal|Living Will", "label" => "  &nbsp; &nbsp; &nbsp;  > Living Will"),
		array( "value" => "Legal|Medical Malpractice", "label" => "  &nbsp; &nbsp; &nbsp;  > Medical Malpractice"),
		array( "value" => "Legal|National State Local", "label" => "  &nbsp; &nbsp; &nbsp;  > National State Local"),
		array( "value" => "Legal|Patents", "label" => "  &nbsp; &nbsp; &nbsp;  > Patents"),
		array( "value" => "Legal|Personal Injury", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Injury"),
		array( "value" => "Legal|Real Estate Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Real Estate Law"),
		array( "value" => "Legal|Regulatory Compliance", "label" => "  &nbsp; &nbsp; &nbsp;  > Regulatory Compliance"),
		array( "value" => "Legal|Trademarks", "label" => "  &nbsp; &nbsp; &nbsp;  > Trademarks"),
		array( "value" => "Legal|Traffic Law", "label" => "  &nbsp; &nbsp; &nbsp;  > Traffic Law"),
		array( "label" => "News and Society", "value" => "News and Society"),
		array( "value" => "News and Society|Anthropology Sociology", "label" => "  &nbsp; &nbsp; &nbsp;  > Anthropology Sociology"),
		array( "value" => "News and Society|Crime", "label" => "  &nbsp; &nbsp; &nbsp;  > Crime"),
		array( "value" => "News and Society|Eco Innovations", "label" => "  &nbsp; &nbsp; &nbsp;  > Eco Innovations"),
		array( "value" => "News and Society|Economics", "label" => "  &nbsp; &nbsp; &nbsp;  > Economics"),
		array( "value" => "News and Society|Energy", "label" => "  &nbsp; &nbsp; &nbsp;  > Energy"),
		array( "value" => "News and Society|Environmental", "label" => "  &nbsp; &nbsp; &nbsp;  > Environmental"),
		array( "value" => "News and Society|International", "label" => "  &nbsp; &nbsp; &nbsp;  > International"),
		array( "value" => "News and Society|Military", "label" => "  &nbsp; &nbsp; &nbsp;  > Military"),
		array( "value" => "News and Society|Politics", "label" => "  &nbsp; &nbsp; &nbsp;  > Politics"),
		array( "value" => "News and Society|Pure Opinion", "label" => "  &nbsp; &nbsp; &nbsp;  > Pure Opinion"),
		array( "value" => "News and Society|Religion", "label" => "  &nbsp; &nbsp; &nbsp;  > Religion"),
		array( "value" => "News and Society|Weather", "label" => "  &nbsp; &nbsp; &nbsp;  > Weather"),
		array( "label" => "Pets", "value" => "Pets"),
		array( "value" => "Pets|Birds", "label" => "  &nbsp; &nbsp; &nbsp;  > Birds"),
		array( "value" => "Pets|Cats", "label" => "  &nbsp; &nbsp; &nbsp;  > Cats"),
		array( "value" => "Pets|Dogs", "label" => "  &nbsp; &nbsp; &nbsp;  > Dogs"),
		array( "value" => "Pets|Exotic", "label" => "  &nbsp; &nbsp; &nbsp;  > Exotic"),
		array( "value" => "Pets|Farm Ranch", "label" => "  &nbsp; &nbsp; &nbsp;  > Farm Ranch"),
		array( "value" => "Pets|Fish", "label" => "  &nbsp; &nbsp; &nbsp;  > Fish"),
		array( "value" => "Pets|Horses", "label" => "  &nbsp; &nbsp; &nbsp;  > Horses"),
		array( "value" => "Pets|Insects Arthropods Arachnids", "label" => "  &nbsp; &nbsp; &nbsp;  > Insects Arthropods Arachnids"),
		array( "value" => "Pets|Reptiles Amphibians", "label" => "  &nbsp; &nbsp; &nbsp;  > Reptiles Amphibians"),
		array( "label" => "Real Estate", "value" => "Real Estate"),
		array( "value" => "Real Estate|Agents Realtors", "label" => "  &nbsp; &nbsp; &nbsp;  > Agents Realtors"),
		array( "value" => "Real Estate|Building a Home", "label" => "  &nbsp; &nbsp; &nbsp;  > Building a Home"),
		array( "value" => "Real Estate|Buying", "label" => "  &nbsp; &nbsp; &nbsp;  > Buying"),
		array( "value" => "Real Estate|Commercial Construction", "label" => "  &nbsp; &nbsp; &nbsp;  > Commercial Construction"),
		array( "value" => "Real Estate|Commercial Property", "label" => "  &nbsp; &nbsp; &nbsp;  > Commercial Property"),
		array( "value" => "Real Estate|Condominiums", "label" => "  &nbsp; &nbsp; &nbsp;  > Condominiums"),
		array( "value" => "Real Estate|Foreclosures", "label" => "  &nbsp; &nbsp; &nbsp;  > Foreclosures"),
		array( "value" => "Real Estate|FSBO", "label" => "  &nbsp; &nbsp; &nbsp;  > FSBO"),
		array( "value" => "Real Estate|Green Real Estate", "label" => "  &nbsp; &nbsp; &nbsp;  > Green Real Estate"),
		array( "value" => "Real Estate|Home Staging", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Staging"),
		array( "value" => "Real Estate|Homes", "label" => "  &nbsp; &nbsp; &nbsp;  > Homes"),
		array( "value" => "Real Estate|Investing", "label" => "  &nbsp; &nbsp; &nbsp;  > Investing"),
		array( "value" => "Real Estate|Land", "label" => "  &nbsp; &nbsp; &nbsp;  > Land"),
		array( "value" => "Real Estate|Leasing Renting", "label" => "  &nbsp; &nbsp; &nbsp;  > Leasing Renting"),
		array( "value" => "Real Estate|Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Marketing"),
		array( "value" => "Real Estate|Mortgage Refinance", "label" => "  &nbsp; &nbsp; &nbsp;  > Mortgage Refinance"),
		array( "value" => "Real Estate|Moving Relocating", "label" => "  &nbsp; &nbsp; &nbsp;  > Moving Relocating"),
		array( "value" => "Real Estate|Property Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Property Management"),
		array( "value" => "Real Estate|Selling", "label" => "  &nbsp; &nbsp; &nbsp;  > Selling"),
		array( "label" => "Recreation and Sports", "value" => "Recreation and Sports"),
		array( "value" => "Recreation and Sports|Archery", "label" => "  &nbsp; &nbsp; &nbsp;  > Archery"),
		array( "value" => "Recreation and Sports|Auto Racing", "label" => "  &nbsp; &nbsp; &nbsp;  > Auto Racing"),
		array( "value" => "Recreation and Sports|Badminton", "label" => "  &nbsp; &nbsp; &nbsp;  > Badminton"),
		array( "value" => "Recreation and Sports|Baseball", "label" => "  &nbsp; &nbsp; &nbsp;  > Baseball"),
		array( "value" => "Recreation and Sports|Basketball", "label" => "  &nbsp; &nbsp; &nbsp;  > Basketball"),
		array( "value" => "Recreation and Sports|Billiards", "label" => "  &nbsp; &nbsp; &nbsp;  > Billiards"),
		array( "value" => "Recreation and Sports|Boating", "label" => "  &nbsp; &nbsp; &nbsp;  > Boating"),
		array( "value" => "Recreation and Sports|Bodybuilding", "label" => "  &nbsp; &nbsp; &nbsp;  > Bodybuilding"),
		array( "value" => "Recreation and Sports|Bowling", "label" => "  &nbsp; &nbsp; &nbsp;  > Bowling"),
		array( "value" => "Recreation and Sports|Boxing", "label" => "  &nbsp; &nbsp; &nbsp;  > Boxing"),
		array( "value" => "Recreation and Sports|Cheerleading", "label" => "  &nbsp; &nbsp; &nbsp;  > Cheerleading"),
		array( "value" => "Recreation and Sports|Climbing", "label" => "  &nbsp; &nbsp; &nbsp;  > Climbing"),
		array( "value" => "Recreation and Sports|Cricket", "label" => "  &nbsp; &nbsp; &nbsp;  > Cricket"),
		array( "value" => "Recreation and Sports|Cycling", "label" => "  &nbsp; &nbsp; &nbsp;  > Cycling"),
		array( "value" => "Recreation and Sports|Dancing", "label" => "  &nbsp; &nbsp; &nbsp;  > Dancing"),
		array( "value" => "Recreation and Sports|Equestrian", "label" => "  &nbsp; &nbsp; &nbsp;  > Equestrian"),
		array( "value" => "Recreation and Sports|Extreme", "label" => "  &nbsp; &nbsp; &nbsp;  > Extreme"),
		array( "value" => "Recreation and Sports|Fantasy Sports", "label" => "  &nbsp; &nbsp; &nbsp;  > Fantasy Sports"),
		array( "value" => "Recreation and Sports|Fencing", "label" => "  &nbsp; &nbsp; &nbsp;  > Fencing"),
		array( "value" => "Recreation and Sports|Figure Skating", "label" => "  &nbsp; &nbsp; &nbsp;  > Figure Skating"),
		array( "value" => "Recreation and Sports|Fish Ponds", "label" => "  &nbsp; &nbsp; &nbsp;  > Fish Ponds"),
		array( "value" => "Recreation and Sports|Fishing", "label" => "  &nbsp; &nbsp; &nbsp;  > Fishing"),
		array( "value" => "Recreation and Sports|Football", "label" => "  &nbsp; &nbsp; &nbsp;  > Football"),
		array( "value" => "Recreation and Sports|Golf", "label" => "  &nbsp; &nbsp; &nbsp;  > Golf"),
		array( "value" => "Recreation and Sports|Greyhound Racing", "label" => "  &nbsp; &nbsp; &nbsp;  > Greyhound Racing"),
		array( "value" => "Recreation and Sports|Gymnastics", "label" => "  &nbsp; &nbsp; &nbsp;  > Gymnastics"),
		array( "value" => "Recreation and Sports|Hockey", "label" => "  &nbsp; &nbsp; &nbsp;  > Hockey"),
		array( "value" => "Recreation and Sports|Horse Racing", "label" => "  &nbsp; &nbsp; &nbsp;  > Horse Racing"),
		array( "value" => "Recreation and Sports|Hunting", "label" => "  &nbsp; &nbsp; &nbsp;  > Hunting"),
		array( "value" => "Recreation and Sports|Martial Arts", "label" => "  &nbsp; &nbsp; &nbsp;  > Martial Arts"),
		array( "value" => "Recreation and Sports|Mountain Biking", "label" => "  &nbsp; &nbsp; &nbsp;  > Mountain Biking"),
		array( "value" => "Recreation and Sports|Olympics", "label" => "  &nbsp; &nbsp; &nbsp;  > Olympics"),
		array( "value" => "Recreation and Sports|Racquetball", "label" => "  &nbsp; &nbsp; &nbsp;  > Racquetball"),
		array( "value" => "Recreation and Sports|Rodeo", "label" => "  &nbsp; &nbsp; &nbsp;  > Rodeo"),
		array( "value" => "Recreation and Sports|Rugby", "label" => "  &nbsp; &nbsp; &nbsp;  > Rugby"),
		array( "value" => "Recreation and Sports|Running", "label" => "  &nbsp; &nbsp; &nbsp;  > Running"),
		array( "value" => "Recreation and Sports|Scuba Diving", "label" => "  &nbsp; &nbsp; &nbsp;  > Scuba Diving"),
		array( "value" => "Recreation and Sports|Skateboarding", "label" => "  &nbsp; &nbsp; &nbsp;  > Skateboarding"),
		array( "value" => "Recreation and Sports|Skiing", "label" => "  &nbsp; &nbsp; &nbsp;  > Skiing"),
		array( "value" => "Recreation and Sports|Snowboarding", "label" => "  &nbsp; &nbsp; &nbsp;  > Snowboarding"),
		array( "value" => "Recreation and Sports|Soccer", "label" => "  &nbsp; &nbsp; &nbsp;  > Soccer"),
		array( "value" => "Recreation and Sports|Softball", "label" => "  &nbsp; &nbsp; &nbsp;  > Softball"),
		array( "value" => "Recreation and Sports|Sports Apparel", "label" => "  &nbsp; &nbsp; &nbsp;  > Sports Apparel"),
		array( "value" => "Recreation and Sports|Squash", "label" => "  &nbsp; &nbsp; &nbsp;  > Squash"),
		array( "value" => "Recreation and Sports|Surfing", "label" => "  &nbsp; &nbsp; &nbsp;  > Surfing"),
		array( "value" => "Recreation and Sports|Swimming", "label" => "  &nbsp; &nbsp; &nbsp;  > Swimming"),
		array( "value" => "Recreation and Sports|Tennis", "label" => "  &nbsp; &nbsp; &nbsp;  > Tennis"),
		array( "value" => "Recreation and Sports|Track and Field", "label" => "  &nbsp; &nbsp; &nbsp;  > Track and Field"),
		array( "value" => "Recreation and Sports|Triathlon", "label" => "  &nbsp; &nbsp; &nbsp;  > Triathlon"),
		array( "value" => "Recreation and Sports|Volleyball", "label" => "  &nbsp; &nbsp; &nbsp;  > Volleyball"),
		array( "value" => "Recreation and Sports|Wrestling", "label" => "  &nbsp; &nbsp; &nbsp;  > Wrestling"),
		array( "label" => "Reference and Education", "value" => "Reference and Education"),
		array( "value" => "Reference and Education|Astronomy", "label" => "  &nbsp; &nbsp; &nbsp;  > Astronomy"),
		array( "value" => "Reference and Education|Biology", "label" => "  &nbsp; &nbsp; &nbsp;  > Biology"),
		array( "value" => "Reference and Education|College University", "label" => "  &nbsp; &nbsp; &nbsp;  > College University"),
		array( "value" => "Reference and Education|Continuing Education", "label" => "  &nbsp; &nbsp; &nbsp;  > Continuing Education"),
		array( "value" => "Reference and Education|Financial Aid", "label" => "  &nbsp; &nbsp; &nbsp;  > Financial Aid"),
		array( "value" => "Reference and Education|Future Concepts", "label" => "  &nbsp; &nbsp; &nbsp;  > Future Concepts"),
		array( "value" => "Reference and Education|Home Schooling", "label" => "  &nbsp; &nbsp; &nbsp;  > Home Schooling"),
		array( "value" => "Reference and Education|K 12", "label" => "  &nbsp; &nbsp; &nbsp;  > K 12"),
		array( "value" => "Reference and Education|Languages", "label" => "  &nbsp; &nbsp; &nbsp;  > Languages"),
		array( "value" => "Reference and Education|Mathematics", "label" => "  &nbsp; &nbsp; &nbsp;  > Mathematics"),
		array( "value" => "Reference and Education|Nature", "label" => "  &nbsp; &nbsp; &nbsp;  > Nature"),
		array( "value" => "Reference and Education|Online Education", "label" => "  &nbsp; &nbsp; &nbsp;  > Online Education"),
		array( "value" => "Reference and Education|Paranormal", "label" => "  &nbsp; &nbsp; &nbsp;  > Paranormal"),
		array( "value" => "Reference and Education|Psychic", "label" => "  &nbsp; &nbsp; &nbsp;  > Psychic"),
		array( "value" => "Reference and Education|Psychology", "label" => "  &nbsp; &nbsp; &nbsp;  > Psychology"),
		array( "value" => "Reference and Education|Science", "label" => "  &nbsp; &nbsp; &nbsp;  > Science"),
		array( "value" => "Reference and Education|Special Education", "label" => "  &nbsp; &nbsp; &nbsp;  > Special Education"),
		array( "value" => "Reference and Education|Standardized Tests", "label" => "  &nbsp; &nbsp; &nbsp;  > Standardized Tests"),
		array( "value" => "Reference and Education|Survival and Emergency", "label" => "  &nbsp; &nbsp; &nbsp;  > Survival and Emergency"),
		array( "value" => "Reference and Education|Teaching", "label" => "  &nbsp; &nbsp; &nbsp;  > Teaching"),
		array( "value" => "Reference and Education|Vocational Trade Schools", "label" => "  &nbsp; &nbsp; &nbsp;  > Vocational Trade Schools"),
		array( "value" => "Reference and Education|Wildlife", "label" => "  &nbsp; &nbsp; &nbsp;  > Wildlife"),
		array( "label" => "Relationships", "value" => "Relationships"),
		array( "value" => "Relationships|Affairs", "label" => "  &nbsp; &nbsp; &nbsp;  > Affairs"),
		array( "value" => "Relationships|Anniversaries", "label" => "  &nbsp; &nbsp; &nbsp;  > Anniversaries"),
		array( "value" => "Relationships|Commitment", "label" => "  &nbsp; &nbsp; &nbsp;  > Commitment"),
		array( "value" => "Relationships|Communication", "label" => "  &nbsp; &nbsp; &nbsp;  > Communication"),
		array( "value" => "Relationships|Conflict", "label" => "  &nbsp; &nbsp; &nbsp;  > Conflict"),
		array( "value" => "Relationships|Cross Cultural", "label" => "  &nbsp; &nbsp; &nbsp;  > Cross Cultural"),
		array( "value" => "Relationships|Dating", "label" => "  &nbsp; &nbsp; &nbsp;  > Dating"),
		array( "value" => "Relationships|Dating for Boomers", "label" => "  &nbsp; &nbsp; &nbsp;  > Dating for Boomers"),
		array( "value" => "Relationships|Divorce", "label" => "  &nbsp; &nbsp; &nbsp;  > Divorce"),
		array( "value" => "Relationships|Domestic Violence", "label" => "  &nbsp; &nbsp; &nbsp;  > Domestic Violence"),
		array( "value" => "Relationships|Engagements", "label" => "  &nbsp; &nbsp; &nbsp;  > Engagements"),
		array( "value" => "Relationships|Enhancement", "label" => "  &nbsp; &nbsp; &nbsp;  > Enhancement"),
		array( "value" => "Relationships|Friendship", "label" => "  &nbsp; &nbsp; &nbsp;  > Friendship"),
		array( "value" => "Relationships|Gay Lesbian", "label" => "  &nbsp; &nbsp; &nbsp;  > Gay Lesbian"),
		array( "value" => "Relationships|Long Distance", "label" => "  &nbsp; &nbsp; &nbsp;  > Long Distance"),
		array( "value" => "Relationships|Love", "label" => "  &nbsp; &nbsp; &nbsp;  > Love"),
		array( "value" => "Relationships|Marriage", "label" => "  &nbsp; &nbsp; &nbsp;  > Marriage"),
		array( "value" => "Relationships|Online Dating", "label" => "  &nbsp; &nbsp; &nbsp;  > Online Dating"),
		array( "value" => "Relationships|Post Divorce", "label" => "  &nbsp; &nbsp; &nbsp;  > Post Divorce"),
		array( "value" => "Relationships|Readiness", "label" => "  &nbsp; &nbsp; &nbsp;  > Readiness"),
		array( "value" => "Relationships|Reconnecting", "label" => "  &nbsp; &nbsp; &nbsp;  > Reconnecting"),
		array( "value" => "Relationships|Sexuality", "label" => "  &nbsp; &nbsp; &nbsp;  > Sexuality"),
		array( "value" => "Relationships|Singles", "label" => "  &nbsp; &nbsp; &nbsp;  > Singles"),
		array( "value" => "Relationships|Wedding", "label" => "  &nbsp; &nbsp; &nbsp;  > Wedding"),
		array( "label" => "Self Improvement", "value" => "Self Improvement"),
		array( "value" => "Self Improvement|Abundance Prosperity", "label" => "  &nbsp; &nbsp; &nbsp;  > Abundance Prosperity"),
		array( "value" => "Self Improvement|Achievement", "label" => "  &nbsp; &nbsp; &nbsp;  > Achievement"),
		array( "value" => "Self Improvement|Addictions", "label" => "  &nbsp; &nbsp; &nbsp;  > Addictions"),
		array( "value" => "Self Improvement|Affirmations", "label" => "  &nbsp; &nbsp; &nbsp;  > Affirmations"),
		array( "value" => "Self Improvement|Anger Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Anger Management"),
		array( "value" => "Self Improvement|Attraction", "label" => "  &nbsp; &nbsp; &nbsp;  > Attraction"),
		array( "value" => "Self Improvement|Coaching", "label" => "  &nbsp; &nbsp; &nbsp;  > Coaching"),
		array( "value" => "Self Improvement|Creativity", "label" => "  &nbsp; &nbsp; &nbsp;  > Creativity"),
		array( "value" => "Self Improvement|Empowerment", "label" => "  &nbsp; &nbsp; &nbsp;  > Empowerment"),
		array( "value" => "Self Improvement|Goal Setting", "label" => "  &nbsp; &nbsp; &nbsp;  > Goal Setting"),
		array( "value" => "Self Improvement|Grief Loss", "label" => "  &nbsp; &nbsp; &nbsp;  > Grief Loss"),
		array( "value" => "Self Improvement|Happiness", "label" => "  &nbsp; &nbsp; &nbsp;  > Happiness"),
		array( "value" => "Self Improvement|Innovation", "label" => "  &nbsp; &nbsp; &nbsp;  > Innovation"),
		array( "value" => "Self Improvement|Inspirational", "label" => "  &nbsp; &nbsp; &nbsp;  > Inspirational"),
		array( "value" => "Self Improvement|Leadership", "label" => "  &nbsp; &nbsp; &nbsp;  > Leadership"),
		array( "value" => "Self Improvement|Memory Training", "label" => "  &nbsp; &nbsp; &nbsp;  > Memory Training"),
		array( "value" => "Self Improvement|Mind Development", "label" => "  &nbsp; &nbsp; &nbsp;  > Mind Development"),
		array( "value" => "Self Improvement|Motivation", "label" => "  &nbsp; &nbsp; &nbsp;  > Motivation"),
		array( "value" => "Self Improvement|NLP Hypnosis", "label" => "  &nbsp; &nbsp; &nbsp;  > NLP Hypnosis"),
		array( "value" => "Self Improvement|Organizing", "label" => "  &nbsp; &nbsp; &nbsp;  > Organizing"),
		array( "value" => "Self Improvement|Personal Growth", "label" => "  &nbsp; &nbsp; &nbsp;  > Personal Growth"),
		array( "value" => "Self Improvement|Positive Attitude", "label" => "  &nbsp; &nbsp; &nbsp;  > Positive Attitude"),
		array( "value" => "Self Improvement|Self Esteem", "label" => "  &nbsp; &nbsp; &nbsp;  > Self Esteem"),
		array( "value" => "Self Improvement|Speed Reading", "label" => "  &nbsp; &nbsp; &nbsp;  > Speed Reading"),
		array( "value" => "Self Improvement|Spirituality", "label" => "  &nbsp; &nbsp; &nbsp;  > Spirituality"),
		array( "value" => "Self Improvement|Stress Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Stress Management"),
		array( "value" => "Self Improvement|Success", "label" => "  &nbsp; &nbsp; &nbsp;  > Success"),
		array( "value" => "Self Improvement|Techniques", "label" => "  &nbsp; &nbsp; &nbsp;  > Techniques"),
		array( "value" => "Self Improvement|Time Management", "label" => "  &nbsp; &nbsp; &nbsp;  > Time Management"),
		array( "label" => "Shopping and Product Reviews", "value" => "Shopping and Product Reviews"),
		array( "value" => "Shopping and Product Reviews|Collectible Jewelry", "label" => "  &nbsp; &nbsp; &nbsp;  > Collectible Jewelry"),
		array( "value" => "Shopping and Product Reviews|Electronics", "label" => "  &nbsp; &nbsp; &nbsp;  > Electronics"),
		array( "value" => "Shopping and Product Reviews|Fashion Style", "label" => "  &nbsp; &nbsp; &nbsp;  > Fashion Style"),
		array( "value" => "Shopping and Product Reviews|Gifts", "label" => "  &nbsp; &nbsp; &nbsp;  > Gifts"),
		array( "value" => "Shopping and Product Reviews|Internet Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Internet Marketing"),
		array( "value" => "Shopping and Product Reviews|Jewelry Diamonds", "label" => "  &nbsp; &nbsp; &nbsp;  > Jewelry Diamonds"),
		array( "value" => "Shopping and Product Reviews|Lingerie", "label" => "  &nbsp; &nbsp; &nbsp;  > Lingerie"),
		array( "value" => "Shopping and Product Reviews|Movie Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Movie Reviews"),
		array( "value" => "Shopping and Product Reviews|Music Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Music Reviews"),
		array( "value" => "Shopping and Product Reviews|Toys", "label" => "  &nbsp; &nbsp; &nbsp;  > Toys"),
		array( "label" => "Travel and Leisure", "value" => "Travel and Leisure"),
		array( "value" => "Travel and Leisure|Adventure Travel", "label" => "  &nbsp; &nbsp; &nbsp;  > Adventure Travel"),
		array( "value" => "Travel and Leisure|Airline Travel", "label" => "  &nbsp; &nbsp; &nbsp;  > Airline Travel"),
		array( "value" => "Travel and Leisure|Aviation Airplanes", "label" => "  &nbsp; &nbsp; &nbsp;  > Aviation Airplanes"),
		array( "value" => "Travel and Leisure|Bed Breakfast Inns", "label" => "  &nbsp; &nbsp; &nbsp;  > Bed Breakfast Inns"),
		array( "value" => "Travel and Leisure|Budget Travel", "label" => "  &nbsp; &nbsp; &nbsp;  > Budget Travel"),
		array( "value" => "Travel and Leisure|Camping", "label" => "  &nbsp; &nbsp; &nbsp;  > Camping"),
		array( "value" => "Travel and Leisure|Car Rentals", "label" => "  &nbsp; &nbsp; &nbsp;  > Car Rentals"),
		array( "value" => "Travel and Leisure|Charter Jets", "label" => "  &nbsp; &nbsp; &nbsp;  > Charter Jets"),
		array( "value" => "Travel and Leisure|City Guides and Information", "label" => "  &nbsp; &nbsp; &nbsp;  > City Guides and Information"),
		array( "value" => "Travel and Leisure|Cruise Ship Reviews", "label" => "  &nbsp; &nbsp; &nbsp;  > Cruise Ship Reviews"),
		array( "value" => "Travel and Leisure|Cruising", "label" => "  &nbsp; &nbsp; &nbsp;  > Cruising"),
		array( "value" => "Travel and Leisure|Destination Tips", "label" => "  &nbsp; &nbsp; &nbsp;  > Destination Tips"),
		array( "value" => "Travel and Leisure|First Time Cruising", "label" => "  &nbsp; &nbsp; &nbsp;  > First Time Cruising"),
		array( "value" => "Travel and Leisure|Golf Travel and Resorts", "label" => "  &nbsp; &nbsp; &nbsp;  > Golf Travel and Resorts"),
		array( "value" => "Travel and Leisure|Hiking Backpacking", "label" => "  &nbsp; &nbsp; &nbsp;  > Hiking Backpacking"),
		array( "value" => "Travel and Leisure|Hotels Accommodations", "label" => "  &nbsp; &nbsp; &nbsp;  > Hotels Accommodations"),
		array( "value" => "Travel and Leisure|Limo Rentals Limousines", "label" => "  &nbsp; &nbsp; &nbsp;  > Limo Rentals Limousines"),
		array( "value" => "Travel and Leisure|Luxury Cruising", "label" => "  &nbsp; &nbsp; &nbsp;  > Luxury Cruising"),
		array( "value" => "Travel and Leisure|Outdoors", "label" => "  &nbsp; &nbsp; &nbsp;  > Outdoors"),
		array( "value" => "Travel and Leisure|Pet Friendly Rentals", "label" => "  &nbsp; &nbsp; &nbsp;  > Pet Friendly Rentals"),
		array( "value" => "Travel and Leisure|Sailing", "label" => "  &nbsp; &nbsp; &nbsp;  > Sailing"),
		array( "value" => "Travel and Leisure|Ski Resorts", "label" => "  &nbsp; &nbsp; &nbsp;  > Ski Resorts"),
		array( "value" => "Travel and Leisure|Staycations", "label" => "  &nbsp; &nbsp; &nbsp;  > Staycations"),
		array( "value" => "Travel and Leisure|Timeshare", "label" => "  &nbsp; &nbsp; &nbsp;  > Timeshare"),
		array( "value" => "Travel and Leisure|Travel Planning", "label" => "  &nbsp; &nbsp; &nbsp;  > Travel Planning"),
		array( "value" => "Travel and Leisure|Vacation Homes", "label" => "  &nbsp; &nbsp; &nbsp;  > Vacation Homes"),
		array( "value" => "Travel and Leisure|Vacation Rentals", "label" => "  &nbsp; &nbsp; &nbsp;  > Vacation Rentals"),
		array( "label" => "Womens Interests", "value" => "Womens Interests"),
		array( "value" => "Womens Interests|Beauty Products", "label" => "  &nbsp; &nbsp; &nbsp;  > Beauty Products"),
		array( "value" => "Womens Interests|Cosmetic Surgery", "label" => "  &nbsp; &nbsp; &nbsp;  > Cosmetic Surgery"),
		array( "value" => "Womens Interests|Menopause HRT", "label" => "  &nbsp; &nbsp; &nbsp;  > Menopause HRT"),
		array( "value" => "Womens Interests|Plus Size", "label" => "  &nbsp; &nbsp; &nbsp;  > Plus Size"),
		array( "value" => "Womens Interests|Self Defense", "label" => "  &nbsp; &nbsp; &nbsp;  > Self Defense"),
		array( "label" => "Writing and Speaking", "value" => "Writing and Speaking"),
		array( "value" => "Writing and Speaking|Article Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Article Marketing"),
		array( "value" => "Writing and Speaking|Book Marketing", "label" => "  &nbsp; &nbsp; &nbsp;  > Book Marketing"),
		array( "value" => "Writing and Speaking|Copywriting", "label" => "  &nbsp; &nbsp; &nbsp;  > Copywriting"),
		array( "value" => "Writing and Speaking|Creative Writing", "label" => "  &nbsp; &nbsp; &nbsp;  > Creative Writing"),
		array( "value" => "Writing and Speaking|Public Speaking", "label" => "  &nbsp; &nbsp; &nbsp;  > Public Speaking"),
		array( "value" => "Writing and Speaking|Publishing", "label" => "  &nbsp; &nbsp; &nbsp;  > Publishing"),
		array( "value" => "Writing and Speaking|Screenwriting", "label" => "  &nbsp; &nbsp; &nbsp;  > Screenwriting"),
		array( "value" => "Writing and Speaking|Technical Writing", "label" => "  &nbsp; &nbsp; &nbsp;  > Technical Writing"),
		array( "value" => "Writing and Speaking|Teleseminars", "label" => "  &nbsp; &nbsp; &nbsp;  > Teleseminars"),
		array( "value" => "Writing and Speaking|Writing", "label" => "  &nbsp; &nbsp; &nbsp;  > Writing"),
		array( "value" => "Writing and Speaking|Writing Articles", "label" => "  &nbsp; &nbsp; &nbsp;  > Writing Articles")



	);
}

?>