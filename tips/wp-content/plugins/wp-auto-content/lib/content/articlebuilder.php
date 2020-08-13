<?php

define( 'WPAUTOC_ABUILDER_PER_PAGE', 10 );
define( 'WPAUTOC_ABUILDER_MAX_LOOPS', 1 );

/*
	Type: WPAUTOC_CONTENT_ABUILDER (7)
	Unique id: video id

	https://members.bigcontentsearch.com/api/articles_get_by_search_term?username=raulmellado@gmail.com&api_key=46785a03-2936-4941-af8c-2a105dc2a3c7&search_term=trump&count=20
*/

function wpautoc_content_type_abuilder( $content, $num ) {
	wpautoc_content_print_box_header( $content, $num );
	$settings = json_decode( $content->settings );
	$is_api_valid = wpautoc_is_api_valid( 'abuilder' );
	$disp = '';
	if( !$is_api_valid ) {
		$disp = ' style="display:none" ';
		$msg = '<p>To be able to import <a href="https://wpautocontent.com/support/articlebuilder" target="_blank">ArticleBuilder.net</a> articles, you need a valid username and password.</p><p><a href="'.admin_url('admin.php?page=wp-auto-content-settings#autocs-content-tab').'">Click here</a> to go to the plugin settings and enter your Article Builder Details.</p>';
		wpautoc_noapi_msg( $msg );
	}
	echo '<table class="form-table" '.$disp.'>';
	/*wpautoc_ifield_text( $settings, 'keyword', 'Keyword', 'wpautoc_content['.$num.'][settings][keyword]', false, 'Keyword to search', 'Enter one or more keywords to search in BigContentSearch.com' );*/

	$cats = wpautoc_abuilder_cats();
	wpautoc_ifield_select( $settings, 'category', 'Category', 'wpautoc_content['.$num.'][settings][category]', $cats, false, 'Import articles from this category', '', '', '', false, true );

	$wordcount = array( array( 'value' => 100, 'label' => 100 ), array( 'value' => 200, 'label' => 200 ),array( 'value' => 300, 'label' => 300 ),array( 'value' => 500, 'label' => 500 ),array( 'value' => 800, 'label' => 800 ), array( 'value' => 1000, 'label' => 1000 ) );
	wpautoc_ifield_select( $settings, 'wordcount', 'Word Count', 'wpautoc_content['.$num.'][settings][wordcount]', $wordcount, false, 'Number of Words (approx)', '', '', '' );

	// wpautoc_ifield_text( $settings, 'num_items', 'Items per Post', 'wpautoc_content['.$num.'][settings][num_items]', false, 'Number of Items per post', 'How many abuilder items will be shown per post' );

	// wpautoc_ifield_checkbox( $settings, 'spin_content', 'Spin Article Content', 'wpautoc_content['.$num.'][settings][spin_content]', false, 'If checked, it will spin the text description <a href="https://wpautocontent.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank">What is this?</a>'.wpautoc_nospinner_txt() );

	// wpautoc_ifield_checkbox( $settings, 'buy_button', 'Add Buy Button', 'wpautoc_content['.$num.'][settings][buy_button]', false, 'If checked, it will add a buy button at the end of the content (so you can get affiliate commissions)' );

	echo '</table>';
	wpautoc_content_print_box_footer( $content, $num );
}

function wpautoc_process_content_import_abuilder( $campaign_id, $settings, $num_posts = 0 ) {
	$num_imported = 0;
	$num_posts = isset( $num_posts ) ? intval( $num_posts ) : 1;

	$category = isset( $settings->category ) && !empty( $settings->category ) ? $settings->category : false;
	$wordcount = isset( $settings->wordcount ) && !empty( $settings->wordcount ) ? $settings->wordcount : 500;
	// $num_items = isset( $settings->num_items ) && !empty( $settings->num_items ) ? $settings->num_items : 1;

	if( empty( $category ) )
		return 0;
// echo "a";
	$end_reached = false;
	$page = 1;
	$i = 0;
	$total_posts = $num_posts;
	while( ( $num_imported < $total_posts ) && ( !$end_reached ) && ( $i < WPAUTOC_ABUILDER_MAX_LOOPS )  ) {
		$imported = wpautoc_content_abuilder_search( $page, WPAUTOC_ABUILDER_PER_PAGE, $category, $wordcount,  $campaign_id, $settings, $num_posts );
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

function wpautoc_content_abuilder_search( $page = 1, $per_page = 50, $category, $wordcount = 500, $campaign_id, $settings, $num_posts ) {
	/* Set our response to array format. */
	try {
		$results = wpautoc_content_do_abuilder_search( $category, $wordcount, $page, min( $per_page, $num_posts) , $campaign_id );
	} catch (Exception $e) {
	    return 0;
	}
// var_dump($results);
	if( $results ) {
		$imported = 0;
		foreach( $results as $result ) {
			$product_id = $result['id'];
			// var_dump($product_id);
			if( !wpautoc_abuilder_already_imported( $product_id, $campaign_id ) ) {
				wpautoc_do_import_abuilder_product( $campaign_id, $result, $product_id, $settings );
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


function wpautoc_content_do_abuilder_search( $category,  $wordcount = 500, $page = 1, $per_page = 60, $campaign_id = 0 ) {
	$abuilder_settings = wpautoc_get_settings( array( 'content', 'abuilder' ) );
		$username = isset( $abuilder_settings['username'] ) && !empty( $abuilder_settings['username'] ) ? $abuilder_settings['username'] : '';
		$pass = isset( $abuilder_settings['pass'] ) && !empty( $abuilder_settings['pass'] ) ? $abuilder_settings['pass'] : '';

		if( empty( $username ) || empty( $pass ) )
			return false;
		$url = 'http://articlebuilder.net/api.php';

		$data = array();
		$data['action'] = 'authenticate';
		$data['format'] = 'php';
		$data['username'] = $username;
		$data['password'] = $pass;
		$output = unserialize( wpautoc_curl_post($url, $data, $info) );

		$articles = array();
		if( $output['success']=='true' ){
		  # Success.
		  $session = $output['session'];
			# Build the data array for the example.
			$data = array();
			$data['session'] = $session;
			$data['format'] = 'php';
			$data['action'] = 'buildArticle';
			$data['category'] = $category;
 		    $data['wordcount'] = $wordcount;
			# Post to API and get back results.

			for( $i=0; $i< $per_page; $i++ ) {
				$output = wpautoc_curl_post( $url, $data, $info );
				$output = unserialize($output);
				if( $output['success']=='true' ) {
					$content = $output['output'];
					$ch1 = chr(10);
					$pos = strpos( $content, $ch1 );
					// $content = html_entity_decode( $content );
					// $content = nl2br( $content );
					// var_dump($pos);
					$title = substr( $content, 0, $pos );
					$article = substr( $content, $pos+2 );
					$article = '<p>'.str_replace( $ch1.$ch1, '</p><p>', $article ).'</p>';
					$articles[] = array( 'title' => $title, 'content' => $article );
				// var_dump($output);
			  // echo "<p><b>Output:</b><br>" . str_replace("\r", "<br>", str_replace("\n\n", "<p>", $output['output'])) . "</p>";

				}
			}
		}
		else
			return false;

		if( empty( $articles ) )
			return false;
		// var_dump($articles);
		// die();
// echo $url.'<br/>';
// var_dump($args);
		  $products = array();
		  	foreach( $articles as $article ) {
		  		// var_dump($item);
		  		$prod = array();
				$prod['id'] = rand();
				$prod['title'] = (string) $article['title'];
				$prod['content'] = (string) $article['content'];
				$prod['url']  = '';
				// rmi353, probablemente es productTrackingUrl
				$prod['image_url']   = false;
				// $prod['price'] = (string) $item->salePrice;
				$products[] = $prod;
		  	}

		return $products;


	// $results = array();
	// if( isset( $res->results->search_1->offers ) && !empty( $res->results->search_1->offers ) ) {
	//     foreach($res->results->search_1->offers as $item) {
	//     	// var_dump($item);
	// 		$res = array();
 //    		$res['id'] = $item->product_id;
	//         $res['title']   = $item->name;
	//         $res['content']   = empty( $item->description ) ? $item->name : $item->description;
	//         $res['image_url']   = $item->picture;
	//         $res['url']  = $item->url;
	// 		// $res['price'] = $item->sale_price;
	// 		$results[] = $res;
	// 	}
	// }
	// else
	// 	return false;
	// return $results;

}


function wpautoc_do_import_abuilder_product( $campaign_id, $product, $product_id = 0, $settings = false ) {
// echo 'llamo a import';
	$name = wpautoc_escape_input_txt( $product['title'] );
	$content = $product['content'];
// var_dump($content);
	if( $settings ) {
		// if( isset( $settings->remove_links ) && $settings->remove_links ) {
		// 	$vid_content = wpautoc_remove_links( $vid_content );
		// }

		/*if( isset( $settings->spin ) && $settings->spin ) {
			$content = wpautoc_spin_text( $content );
		}*/
	}

	/*if( isset( $settings->buy_button ) && $settings->buy_button ) {
		// var_dump($product);
		$url = ( isset( $product['url'] ) && !empty( $product['url'] ) ) ?  $product['url'] : false;
		if( $url )
			$content .= '<p style="text-align:center"><a href="'.$url.'" target="_blank" class="wpac-abuilder-btn">Buy Now from Bestbuy</a></p>';
	}*/

	// $content .= '<br/>'.$vid_content;

	$thumbnail = false;
	if( isset( $product['image_url'] ) && !empty( $product['image_url'] ) )
		$thumbnail = $product['image_url'];
	else if( $keywords = wpautoc_campaign_images( $campaign_id ) )
		$thumbnail = wpautoc_get_campaign_thumbnail( $keywords );

	wpauto_create_post( $campaign_id, $settings, $name, $content, $product_id, WPAUTOC_CONTENT_ABUILDER, $thumbnail );

	return $product_id;
}

function wpautoc_abuilder_already_imported( $product_id, $campaign_id ) {
	return !wpautoc_is_content_unique( $product_id, WPAUTOC_CONTENT_ABUILDER, $campaign_id );
}

// add_action( 'init' , 'test34');
// function test34() {
// 	wpautocs_abuilder_uids( 1 );
// }
function wpautoc_abuilder_cats( ) {
	return array(
		array( 'value' => 'auto repair', 'label' => 'Automotive &raquo; Auto Repair' ),
		array( 'value' => 'car shopping', 'label' => 'Automotive &raquo; Car Shopping' ),
		array( 'value' => 'affiliate marketing', 'label' => 'Business &raquo; Affiliate Marketing' ),
		array( 'value' => 'article marketing', 'label' => 'Business &raquo; Article Marketing' ),
		array( 'value' => 'carpet cleaning', 'label' => 'Business &raquo; Carpet Cleaning' ),
		array( 'value' => 'email marketing', 'label' => 'Business &raquo; Email Marketing' ),
		array( 'value' => 'facebook marketing', 'label' => 'Business &raquo; Facebook Marketing' ),
		array( 'value' => 'forex', 'label' => 'Business &raquo; Forex' ),
		array( 'value' => 'home business', 'label' => 'Business &raquo; Home Business' ),
		array( 'value' => 'internet marketing', 'label' => 'Business &raquo; Internet Marketing' ),
		array( 'value' => 'lead generation', 'label' => 'Business &raquo; Lead Generation' ),
		array( 'value' => 'leadership', 'label' => 'Business &raquo; Leadership' ),
		array( 'value' => 'locksmiths', 'label' => 'Business &raquo; Locksmiths' ),
		array( 'value' => 'make money online', 'label' => 'Business &raquo; Make Money Online' ),
		array( 'value' => 'mobile marketing', 'label' => 'Business &raquo; Mobile Marketing' ),
		array( 'value' => 'multi-level marketing', 'label' => 'Business &raquo; Multi-level Marketing' ),
		array( 'value' => 'network marketing', 'label' => 'Business &raquo; Network Marketing' ),
		array( 'value' => 'real estate investing', 'label' => 'Business &raquo; Real Estate Investing' ),
		array( 'value' => 'reputation management', 'label' => 'Business &raquo; Reputation Management' ),
		array( 'value' => 'search engine optimization', 'label' => 'Business &raquo; Search Engine Optimization' ),
		array( 'value' => 'social media marketing', 'label' => 'Business &raquo; Social Media Marketing' ),
		array( 'value' => 'video marketing', 'label' => 'Business &raquo; Video Marketing' ),
		array( 'value' => 'woodworking', 'label' => 'Business &raquo; Woodworking' ),
		array( 'value' => 'college', 'label' => 'Education &raquo; College' ),
		array( 'value' => 'homeschooling', 'label' => 'Education &raquo; Homeschooling' ),
		array( 'value' => 'student loans', 'label' => 'Education &raquo; Student Loans' ),
		array( 'value' => 'music downloads', 'label' => 'Entertainment &raquo; Music Downloads' ),
		array( 'value' => 'toys', 'label' => 'Entertainment &raquo; Toys' ),
		array( 'value' => 'coupons', 'label' => 'Finance &raquo; Coupons' ),
		array( 'value' => 'credit cards', 'label' => 'Finance &raquo; Credit Cards' ),
		array( 'value' => 'credit repair', 'label' => 'Finance &raquo; Credit Repair' ),
		array( 'value' => 'debt consolidation', 'label' => 'Finance &raquo; Debt Consolidation' ),
		array( 'value' => 'employment', 'label' => 'Finance &raquo; Employment' ),
		array( 'value' => 'gold', 'label' => 'Finance &raquo; Gold' ),
		array( 'value' => 'home mortgages', 'label' => 'Finance &raquo; Home Mortgages' ),
		array( 'value' => 'insurance - auto', 'label' => 'Finance &raquo; Insurance - Auto' ),
		array( 'value' => 'insurance - general', 'label' => 'Finance &raquo; Insurance - General' ),
		array( 'value' => 'insurance - life', 'label' => 'Finance &raquo; Insurance - Life' ),
		array( 'value' => 'investing', 'label' => 'Finance &raquo; Investing' ),
		array( 'value' => 'payday loans', 'label' => 'Finance &raquo; Payday Loans' ),
		array( 'value' => 'personal bankruptcy', 'label' => 'Finance &raquo; Personal Bankruptcy' ),
		array( 'value' => 'personal finance', 'label' => 'Finance &raquo; Personal Finance' ),
		array( 'value' => 'real estate - buying', 'label' => 'Finance &raquo; Real Estate - Buying' ),
		array( 'value' => 'real estate - commercial', 'label' => 'Finance &raquo; Real Estate - Commercial' ),
		array( 'value' => 'real estate - selling', 'label' => 'Finance &raquo; Real Estate - Selling' ),
		array( 'value' => 'retirement', 'label' => 'Finance &raquo; Retirement' ),
		array( 'value' => 'stock market', 'label' => 'Finance &raquo; Stock Market' ),
		array( 'value' => 'coffee', 'label' => 'Food &raquo; Coffee' ),
		array( 'value' => 'wine', 'label' => 'Food &raquo; Wine' ),
		array( 'value' => 'acid reflux', 'label' => 'Health &raquo; Acid Reflux' ),
		array( 'value' => 'acne', 'label' => 'Health &raquo; Acne' ),
		array( 'value' => 'acupuncture', 'label' => 'Health &raquo; Acupuncture' ),
		array( 'value' => 'aging', 'label' => 'Health &raquo; Aging' ),
		array( 'value' => 'allergies', 'label' => 'Health &raquo; Allergies' ),
		array( 'value' => 'anxiety', 'label' => 'Health &raquo; Anxiety' ),
		array( 'value' => 'arthritis', 'label' => 'Health &raquo; Arthritis' ),
		array( 'value' => 'asthma', 'label' => 'Health &raquo; Asthma' ),
		array( 'value' => 'back pain', 'label' => 'Health &raquo; Back Pain' ),
		array( 'value' => 'beauty', 'label' => 'Health &raquo; Beauty' ),
		array( 'value' => 'cancer', 'label' => 'Health &raquo; Cancer' ),
		array( 'value' => 'cellulite', 'label' => 'Health &raquo; Cellulite' ),
		array( 'value' => 'chiropractic care', 'label' => 'Health &raquo; Chiropractic Care' ),
		array( 'value' => 'cosmetic surgery', 'label' => 'Health &raquo; Cosmetic Surgery' ),
		array( 'value' => 'dental care', 'label' => 'Health &raquo; Dental Care' ),
		array( 'value' => 'depression', 'label' => 'Health &raquo; Depression' ),
		array( 'value' => 'diabetes', 'label' => 'Health &raquo; Diabetes' ),
		array( 'value' => 'eczema', 'label' => 'Health &raquo; Eczema' ),
		array( 'value' => 'eye care', 'label' => 'Health &raquo; Eye Care' ),
		array( 'value' => 'fitness', 'label' => 'Health &raquo; Fitness' ),
		array( 'value' => 'hair care', 'label' => 'Health &raquo; Hair Care' ),
		array( 'value' => 'hair loss', 'label' => 'Health &raquo; Hair Loss' ),
		array( 'value' => 'hemorrhoids', 'label' => 'Health &raquo; Hemorrhoids' ),
		array( 'value' => 'insomnia', 'label' => 'Health &raquo; Insomnia' ),
		array( 'value' => 'insurance - health', 'label' => 'Health &raquo; Insurance - Health' ),
		array( 'value' => 'juicing', 'label' => 'Health &raquo; Juicing' ),
		array( 'value' => 'massage', 'label' => 'Health &raquo; Massage' ),
		array( 'value' => 'memory', 'label' => 'Health &raquo; Memory' ),
		array( 'value' => 'muscle building', 'label' => 'Health &raquo; Muscle Building' ),
		array( 'value' => 'nutrition', 'label' => 'Health &raquo; Nutrition' ),
		array( 'value' => 'panic attacks', 'label' => 'Health &raquo; Panic Attacks' ),
		array( 'value' => 'personal development', 'label' => 'Health &raquo; Personal Development' ),
		array( 'value' => 'quit smoking', 'label' => 'Health &raquo; Quit Smoking' ),
		array( 'value' => 'skin care', 'label' => 'Health &raquo; Skin Care' ),
		array( 'value' => 'sleep apnea', 'label' => 'Health &raquo; Sleep Apnea' ),
		array( 'value' => 'snoring', 'label' => 'Health &raquo; Snoring' ),
		array( 'value' => 'stress', 'label' => 'Health &raquo; Stress' ),
		array( 'value' => 'teeth whitening', 'label' => 'Health &raquo; Teeth Whitening' ),
		array( 'value' => 'tinnitus', 'label' => 'Health &raquo; Tinnitus' ),
		array( 'value' => 'vitamins and minerals', 'label' => 'Health &raquo; Vitamins And Minerals' ),
		array( 'value' => 'weight loss', 'label' => 'Health &raquo; Weight Loss' ),
		array( 'value' => 'yeast infection', 'label' => 'Health &raquo; Yeast Infection' ),
		array( 'value' => 'arts and crafts', 'label' => 'Hobbies &raquo; Arts And Crafts' ),
		array( 'value' => 'cooking', 'label' => 'Home And Family &raquo; Cooking' ),
		array( 'value' => 'dog training', 'label' => 'Home And Family &raquo; Dog Training' ),
		array( 'value' => 'furniture', 'label' => 'Home And Family &raquo; Furniture' ),
		array( 'value' => 'gardening', 'label' => 'Home And Family &raquo; Gardening' ),
		array( 'value' => 'hobbies', 'label' => 'Home And Family &raquo; Hobbies' ),
		array( 'value' => 'home improvement', 'label' => 'Home And Family &raquo; Home Improvement' ),
		array( 'value' => 'home security', 'label' => 'Home And Family &raquo; Home Security' ),
		array( 'value' => 'HVAC', 'label' => 'Home And Family &raquo; HVAC' ),
		array( 'value' => 'insurance - home owners', 'label' => 'Home And Family &raquo; Insurance - Home Owners' ),
		array( 'value' => 'interior design', 'label' => 'Home And Family &raquo; Interior Design' ),
		array( 'value' => 'landscaping', 'label' => 'Home And Family &raquo; Landscaping' ),
		array( 'value' => 'organic gardening', 'label' => 'Home And Family &raquo; Organic Gardening' ),
		array( 'value' => 'parenting', 'label' => 'Home And Family &raquo; Parenting' ),
		array( 'value' => 'pest control', 'label' => 'Home And Family &raquo; Pest Control' ),
		array( 'value' => 'plumbing', 'label' => 'Home And Family &raquo; Plumbing' ),
		array( 'value' => 'pregnancy', 'label' => 'Home And Family &raquo; Pregnancy' ),
		array( 'value' => 'roofing', 'label' => 'Home And Family &raquo; Roofing' ),
		array( 'value' => 'lawyers', 'label' => 'Legal &raquo; Lawyers' ),
		array( 'value' => 'personal injury', 'label' => 'Legal &raquo; Personal Injury' ),
		array( 'value' => 'learn guitar', 'label' => 'Music &raquo; Learn Guitar' ),
		array( 'value' => 'cats', 'label' => 'Pets &raquo; Cats' ),
		array( 'value' => 'dogs', 'label' => 'Pets &raquo; Dogs' ),
		array( 'value' => 'camping', 'label' => 'Recreation &raquo; Camping' ),
		array( 'value' => 'fishing', 'label' => 'Recreation &raquo; Fishing' ),
		array( 'value' => 'golf', 'label' => 'Recreation &raquo; Golf' ),
		array( 'value' => 'hotels', 'label' => 'Recreation &raquo; Hotels' ),
		array( 'value' => 'photography', 'label' => 'Recreation &raquo; Photography' ),
		array( 'value' => 'travel', 'label' => 'Recreation &raquo; Travel' ),
		array( 'value' => 'video games', 'label' => 'Recreation &raquo; Video Games' ),
		array( 'value' => 'public speaking', 'label' => 'Self Improvement &raquo; Public Speaking' ),
		array( 'value' => 'jewelry', 'label' => 'Shopping &raquo; Jewelry' ),
		array( 'value' => 'online shopping', 'label' => 'Shopping &raquo; Online Shopping' ),
		array( 'value' => 'shoes', 'label' => 'Shopping &raquo; Shoes' ),
		array( 'value' => 'fashion', 'label' => 'Society &raquo; Fashion' ),
		array( 'value' => 'weddings', 'label' => 'Society &raquo; Weddings' ),
		array( 'value' => 'baseball', 'label' => 'Sports &raquo; Baseball' ),
		array( 'value' => 'basketball', 'label' => 'Sports &raquo; Basketball' ),
		array( 'value' => 'football', 'label' => 'Sports &raquo; Football' ),
		array( 'value' => 'soccer', 'label' => 'Sports &raquo; Soccer' ),
		array( 'value' => 'blogging', 'label' => 'Technology &raquo; Blogging' ),
		array( 'value' => 'cell phones', 'label' => 'Technology &raquo; Cell Phones' ),
		array( 'value' => 'desktop computers', 'label' => 'Technology &raquo; Desktop Computers' ),
		array( 'value' => 'green energy', 'label' => 'Technology &raquo; Green Energy' ),
		array( 'value' => 'ipad', 'label' => 'Technology &raquo; Ipad' ),
		array( 'value' => 'iphone', 'label' => 'Technology &raquo; Iphone' ),
		array( 'value' => 'laptops', 'label' => 'Technology &raquo; Laptops' ),
		array( 'value' => 'solar energy', 'label' => 'Technology &raquo; Solar Energy' ),
		array( 'value' => 'time management', 'label' => 'Technology &raquo; Time Management' ),
		array( 'value' => 'web design', 'label' => 'Technology &raquo; Web Design' ),
		array( 'value' => 'web hosting', 'label' => 'Technology &raquo; Web Hosting' ),
		array( 'value' => 'wordpress', 'label' => 'Technology &raquo; Wordpress' )
	);
}

function wpautoc_curl_post($url, $data, &$info){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, wpautoc_curl_postData($data));
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  $html = trim(curl_exec($ch));
  curl_close($ch);

  return $html;
}

function wpautoc_curl_postData($data){

  $fdata = "";
  foreach($data as $key => $val){
    $fdata .= "$key=" . urlencode($val) . "&";
  }

  return $fdata;

}
// function wpautoc_abuilder_extract_id( $link ) {
// 	$parts = explode( '/', $link );
// 	if( $parts )
// 		return end( $parts );
// 	return 0;
// }

?>