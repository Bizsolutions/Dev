<?php

function wpautoc_escape_input_txt( $text ) {
	return htmlentities( $text, ENT_QUOTES, 'UTF-8' );
}

function wpautoc_strip_magic_slashes( $str )
{
	if( is_array( $str ) ) {
		return wpautoc_array_map_recursive( 'wpautoc_stripslashes', $str);
	}
	else
		return get_magic_quotes_gpc() ? wpautoc_stripslashes( $str ) : $str;
}

function wpautoc_stripslashes( $str ) {
	return stripslashes( $str );
}

function wpautoc_strip_magic_slashes_all( $str )
{
	return str_replace( array("\'", '\"'), array("'", '"'), $str );
    return get_magic_quotes_gpc() ? stripslashes($str) : $str;
}

function wpautoc_array_map_recursive(/*callable*/ $func, array $array) {
    return filter_var($array, \FILTER_CALLBACK, array( 'options' => $func ) );
}

function wpautoc_cdata($data)
{
    if (substr($data, 0, 9) === '<![CDATA[' && substr($data, -3) === ']]>') {
        $data = substr($data, 9, -3);
    }
    return $data;
}

function wpautoc_raw_json_encode($input) {
	if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	    return preg_replace_callback(
	        '/\\\\u([0-9a-zA-Z]{4})/',
	        function ($matches) {
	            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
	        },
	        json_encode($input, JSON_HEX_APOS)
	    );
	}
	else {
		return preg_replace_callback(
		    '/\\\\u([0-9a-zA-Z]{4})/',
		    'wpautoc_anon_encode',
		    json_encode($input, JSON_HEX_APOS)
		);
	}
}

function wpautoc_anon_encode( $matches ) {
        return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
}

function wpautoc_json_decode_nice($json, $assoc = false){
    $json = str_replace("\n","\\n",$json);
    $json = str_replace("\r","",$json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
    $json = preg_replace('/(,)\s*}$/','}',$json);
    return json_decode($json,$assoc);
}

function wpautoc_escape_text( $text, $do_utf = false ) {
	global $wpdb;
	return esc_sql( $text );
}

function wpautoc_encode_quotes( $settings ) {
	$array_ret = array();
	foreach( $settings as $key => $setting ) {
		$array_ret[$key] = htmlspecialchars( $setting );
	}
	return $array_ret;
}

function wpautoc_url_get( $url ) {
	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  return $body;
	}
	return false;
}

function wpautoc_random_useragent() {
    $userAgents=array(
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
        "Opera/9.20 (Windows NT 6.0; U; en)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48",
        "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 7.0; InfoPath.3; .NET CLR 3.1.40767; Trident/6.0; en-IN)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/4.0; InfoPath.2; SV1; .NET CLR 2.0.50727; WOW64)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Macintosh; Intel Mac OS X 10_7_3; Trident/6.0)",
        "Mozilla/4.0 (Compatible; MSIE 8.0; Windows NT 5.2; Trident/6.0)",
        "Mozilla/4.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)",
        "Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/1.22 (compatible; MSIE 10.0; Windows 3.1)",
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'
    );
  /* Select random agent */
  $random = rand(0,count($userAgents)-1);
 return $userAgents[$random];
}

function wpautoc_random_referrer() {
    $userAgents=array(
        "https://m.facebook.com/",
        "https://google.com",
        "https://facebook.com/OnePage",
        "https://www.crunchbase.com/organization/ezinearticles",
        "https://www.brandignity.com/2016/07/why-ezine-articles-failed-and-why-it-matters-in-2016/",
        "https://yaro.blog/377/ariticle-marketing-ezinearticles-review/",
        "https://twitter.com/ezinearticles?lang=es",
        "https://www.quora.com/What-Article-marketing-websites-are-better-than-ezinearticles-com",
        "https://www.warriorforum.com/search-engine-optimization/1277997-there-any-benefit-writing-ezine-articles-2017-a.html",
        "http://www.networksolutions.com/support/what-is-ezinearticle-com/",
        "https://www.youtube.com/user/EzineArticles",
        "https://growth.org/discuss/is-it-still-safe-to-publish-article-at-ezinearticles-com",
        "https://www.sitejabber.com/reviews/ezinearticles.com",
        "http://cazort.net/topic/ezinearticles",
        "https://es.wordpress.org/plugins/ezinearticles-plugin/",
        "https://www.linkedin.com/pulse/20140926082520-175449915-ezinearticles-com-links-are-now-100-percent-nofollow/",
        "https://www.trustpilot.com/review/ezinearticles.com",
        "https://stackshare.io/ezinearticles/ezinearticles",
        'https://www.owler.com/company/ezinearticles'
    );
  /* Select random agent */
  $random = rand(0,count($userAgents)-1);
  $ua = $userAgents[$random];
  $should = rand( 0, 1 );
  if( $should )
  	return $ua;
  return false;
}

add_filter( 'plugin_action_links_' . WPAUTOC_PLUGIN_NAME.'/'.WPAUTOC_PLUGIN_NAME.'.php', 'wpautoc_add_plugin_action_links' );

function wpautoc_add_plugin_action_links( $links ) {
	$autocontent_options = array(
		'Support' => '<a target="_blank" href="https://wpautocontent.com/support/">Support</a>'
	);

	if( !wpautoc_is_pro() ) {
		$autocontent_options = array_merge( $autocontent_options,
			array(
				'Upgrade 2 Pro' => '<a target="_blank" href="https://wpautocontent.com/support/proversion" style="font-weight:bold;color:red">Upgrade to Pro</a>'
			)
		);
	}

	else if( !wpautoc_is_monetize() ) {
		$autocontent_options = array_merge( $autocontent_options,
			array(
				'Monetize3' => '<a target="_blank" href="https://wpautocontent.com/support/monetizemodule" style="font-weight:bold;color:red">Get Auto Monetize</a>'
			)
		);
	}

	else if( !wpautoc_is_traffic() ) {
		$autocontent_options = array_merge( $autocontent_options,
			array(
				'Traffic3' => '<a target="_blank" href="https://wpautocontent.com/support/trafficmodule" style="font-weight:bold;color:red">Get Auto Traffic</a>'
			)
		);
	}

	return array_merge(
		$links,
		$autocontent_options
	);
}

// function wpautoc_is_pro() {
// 	return true;
// }
?>