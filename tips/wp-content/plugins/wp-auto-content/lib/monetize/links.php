<?php
function wpautoc_monetize_links_replace( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;

	$links = isset( $settings->links ) && !empty( $settings->links ) ? $settings->links : false;
	if( empty( $links ) )
		return $content;
	// $urls = array();
	// $keywords = array();
	if( $links ) {
		foreach( $links as $link ) {
			$replacement = '<a href="'.$link->url.'" target="_blank">${0}</a>';
			$regEx = '\'(?!((<.*?)|(<a.*?)))(\b'. $link->keyword . '\b)(?!(([^<>]*?)>)|([^>]*?</a>))\'si';
			$content = preg_replace( $regEx, $replacement, $content);
		}
	}
	return $content;
}

function wpautoc_monetize_intext_replace( $content, $monetize, $i ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
// var_dump($settings);
	$keyword = isset( $settings->keyword ) && !empty( $settings->keyword ) ? $settings->keyword : false;
	if( empty( $keyword ) )
		return $content;

	if( $keyword ) {
		$popup_html = isset( $settings->html ) && !empty( $settings->html ) ? $settings->html : false;
		$popup_html = nl2br( html_entity_decode( stripslashes( $popup_html ) ) );
		// $popover_content = '<span class="webui-popover-content">'.$popup_html.'</span>';
		// $popover_content = str_replace(PHP_EOL, '', $popover_content);
		$replacement = '<a class="autoc-double autoc-popup" href="#" data-content="'.$popup_html.'">${0}</a>'/*.$popover_content*/;

		$regEx = '\'(?!((<.*?)|(<a.*?)))(\b'. $keyword . '\b)(?!(([^<>]*?)>)|([^>]*?</a>))\'si';
		$content = preg_replace( $regEx, $replacement, $content);
		// $content .= $popover_content.$js;
	}
	return $content;


	/*$html = ' <a href="#" class="autoc-double autocpop_'.$i.'">'.$keyword.'</a> ';
	$js = '<script>
	jQuery(document).ready( function($) {
	    $("a.autocpop_'.$i.'").webuiPopover({trigger:"hover",animation:"pop", url:"#wpautoc-popover-'.$i.'"});
	});
	</script>';
	$popup_html = isset( $settings->html ) && !empty( $settings->html ) ? $settings->html : false;
	$popover_content = '<div style="display:none" id="wpautoc-popover-'.$i.'" class="wpautoc-popup">'.$popup_html.'</div>';
	return str_replace( $keyword, $html, $content.$popover_content.$js );*/
}

function wpautoc_monetize_sociallock( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
// var_dump($settings);
	$num_pars = isset( $settings->num_pars ) && !empty( $settings->num_pars ) ? intval( $settings->num_pars ) : 1;
	$header_txt = isset( $settings->header_txt ) && !empty( $settings->header_txt ) ? sanitize_text_field( $settings->header_txt ) : 'Share to view this content';
	$intro_txt = isset( $settings->intro_txt ) && !empty( $settings->intro_txt ) ? sanitize_textarea_field( $settings->intro_txt ) : 'Choose any social network from below to view this content';

	if( !$num_pars )
		return $content;
    $content = explode( '</p>', $content );
    $new_content = '';
    $num_paragraphs = count( $content );
    $wrapper_open = '<div class="wpac-social-lock">';
    for ( $i = 0; $i < $num_paragraphs; $i++ ) {
        if ( $i == $num_pars ) {
        	$new_content .= $wrapper_open;
        }

        $new_content .= $content[$i] . '</p>';
    }

    $js = '<script>jQuery(document).ready( function($) {
                var options = {
                	title : "'.$header_txt.'",
                	text : "'.$intro_txt.'",
                    facebook:{
                        url: window.location.href,
                        pageId: "https://www.facebook.com/MyCodingTricks",
                        appId:676027382527018
                    },
                    twitter:{
                        via: "mycodingtricks",
                        url: window.location.href,
                        text: document.title
                    },
                    googleplus:{
                        apikey: "AIzaSyD_H_TgxVsG0jMy6dMTKjkhHilxIk_bQBk",
                        url: window.location.href
                    },
                    linkedIn:{
                        url: window.location.href
                    },
                    buttons:["facebook_share","facebook_like","twitter_tweet","twitter_follow","googleplus","linkedin"],
                    id: 2,
                    cookie: false,
                    cookieExpiry: 1
                };
                //alert("a");
            var shareIt = jQuery(".wpac-social-lock").shareIt(options);
        });
        </script>
    ';
    return $new_content.'</div>'.$js;
}
?>