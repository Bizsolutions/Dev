<?php
function wpautoc_screen_log() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
        <img src="<?php echo WPAUTOC_URL;?>/img/logo2.png" alt="">
        <h1>Log</h1>
        <br/>
        <?php wpautoc_print_log(); ?>
    </div>
        <?php
}

function wpautoc_print_log() {
	$campaign_id = isset( $_GET['cid'] ) ? intval( $_GET['cid'] ) : 0;
	$source_id = isset( $_GET['sid'] ) ? intval( $_GET['sid'] ) : 0;
	$page = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 1;
	$per_page = isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 50;

	$args = array(
		'post_type' => 'post',
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key' => '_wpac_cid'
			),
		),
		'posts_per_page' => -1
	);

	if( $campaign_id && $source_id ) {
		$args['meta_query'] = 	array( 'relation' => 'AND',
		array(
			'key' => '_wpac_cnttype',
			'value' => $source_id
		),
		array(
			'key' => '_wpac_cid',
			'value' => $campaign_id
		));
	}
	else if( $campaign_id )
		$args['meta_query'][0]['value'] = $campaign_id;

	else if( $source_id ) {
		$args['meta_query'] = 	array( 'relation' => 'AND',
		array(
			'key' => '_wpac_cnttype',
			'value' => $source_id
		),
		array(
			'key' => '_wpac_cid',
		));
	}
// var_dump($args);
	$total_query = new WP_Query( $args );
	$total = $total_query->found_posts;
	$num_of_pages = ceil( $total / $per_page );

	$args['posts_per_page'] = $per_page;
	if( !$page )
		$args['offset'] = 0;
	else
		$args['offset'] = ( $page-1 ) * $per_page;
	$args['orderby'] = 'ID';

	$str = '<div class=""><form method="GET" action="'.admin_url('admin.php?page=wp-auto-content-log').'"><input type="hidden" name="page" value="wp-auto-content-log" /><label><b>Filter by:</b></label> &nbsp;&nbsp; <label for="cid"> Campaign:</label>'.wpautoc_campaign_select( 'cid', $campaign_id ).' &nbsp; &nbsp; &nbsp; <label for="sid"> Content Source:</label>'.wpautoc_source_select( 'sid', $source_id ).' <button type="submit" class="button button-primary"><i class="fa fa-filter"></i>  Filter</button> <a href="'.admin_url('admin.php?page=wp-auto-content-log').'" class="button button-secondary"><i class="fa fa-remove"></i> Clear Filters</a></form><span style="float:right;margin-right:10px;margin-bottom:8px">Total: <b>'.$total.'</b></span><div style="clear:both"></div></div>';
	$str .= '<table class="widefat"><thead>
		<tr><th></th><th scope="col">Post</th><th scope="col">Campaign</th><th scope="col">Source</th><th scope="col">Date</th><th scope="col">Monetization</th><th scope="col">Traffic</th></tr>
	</thead><tbody>';
	$posts = get_posts( $args );
	$i = $args['offset'] + 1;
	if( $posts ) {
		foreach( $posts as $post ) {
			$campaign = get_post_meta( $post->ID, '_wpac_cid', true );
			$campaign_name = wpautoc_campaign_name( $campaign );
			$source = get_post_meta( $post->ID, '_wpac_cnttype', true );
			$source_name = wpautoc_get_content_name( $source );
			$str .= '<tr><th>'.$i.'</th><th scope="col"><a href="'.get_permalink( $post->ID) .'" target="_blank">'.get_the_title( $post->ID) .'</a></th><td>'.$campaign_name.'</td><td>'.$source_name.'</td><td>'.get_the_date( 'd-m-Y H:m', $post->ID) .'</td><td>'.wpautoc_monetize_log( $campaign, $i ).'</td><td>'.wpautoc_traffic_log( $campaign, $post->ID, $i ).'</td></tr>';
			$i++;
		}
	}
	$str .= '</tbody></table>';

	$page_links = paginate_links( array(
		'base' => add_query_arg( array(
			    'pagenum' => '%#%',
			    'cid' => $campaign_id,
			    'sid' => $source_id
			)
		),
		'format' => '',
		'end_size' => 4,
		'mid_size' => 3,
		'prev_text' => __( '«', 'text-domain' ),
		'next_text' => __( '»', 'text-domain' ),
		'total' => $num_of_pages,
		'current' => $page
	) );
	if ( $page_links ) {
		$str .= '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0;">' . $page_links . '</div></div>';
	}

	echo $str;
}

function wpautoc_monetize_log( $campaign_id, $pos ) {
/*	if( ( $pos % 50 == 1 ) && !wpautoc_is_monetize() )
		return '<a href="https://wpautocontent.com/support/monetizemodule" target="_blank">Monetize Module</a><br/>is not installed';*/
	// return '0 '.$pos;
	$monetization = wpautoc_get_monetize_elements( $campaign_id, false );
	$str = '';
	foreach( $monetization as $monetize ) {
		$str .= wpautoc_log_mon_el( $monetize->type );
	}
	return $str;
}

function wpautoc_log_mon_el( $type ) {
	$str = '<span style="margin-right:4px" title="';
	switch ( $type ) {
		case WPAUTOC_MONETIZE_BANNER:
			$str .=  'Banner Ads"><i class="fa fa-image"></i>';
			break;
		case WPAUTOC_MONETIZE_ADSENSE:
			$str .=  'Google Adsense"><i class="fa fa-google"></i>';
			break;
		case WPAUTOC_MONETIZE_ADCODE:
			$str .=  'HTML Ad Code"><i class="fa fa-html5"></i>';
			break;
		case WPAUTOC_MONETIZE_AMAZON:
			$str .=  'Amazon Affiliate Ads"><i class="fa fa-amazon"></i>';
			break;
		case WPAUTOC_MONETIZE_ALIEXPRESS:
			$str .=  'Aliexpress Affiliate Ads"><i class="fa fa-shopping-cart"></i>';
			break;
		case WPAUTOC_MONETIZE_GEARBEST:
			$str .=  'Gearbest Affiliate Ads"><i class="fa fa-shopping-cart"></i>';
			break;
		case WPAUTOC_MONETIZE_LINKS:
			$str .=  'Inline Links"><i class="fa fa-link"></i>';
			break;
		case WPAUTOC_MONETIZE_INTEXT:
			$str .=  'In-text ads"><i class="fa fa-comment"></i>';
			break;
		case WPAUTOC_MONETIZE_OPTIN:
			$str .=  'Optin Form"><i class="fa fa-envelope"></i>';
			break;
		case WPAUTOC_MONETIZE_SOCIALLOCK:
			$str .=  'Social Lock"><i class="fa fa-lock"></i>';
			break;
		case WPAUTOC_MONETIZE_VIRALADS:
			$str .=  'Viral Ads"><i class="fa fa-file-image-o"></i>';
			break;
		case WPAUTOC_MONETIZE_TEXTADS:
			$str .=  'Text Ads"><i class="fa fa-list-alt"></i>';
			break;
		case WPAUTOC_MONETIZE_CLICKBANK:
			$str .=  'Clickbank Ads"><i class="fa fa-bookmark"></i>';
			break;
		case WPAUTOC_MONETIZE_EBAY:
			$str .=  'eBay Ads"><i class="fa fa-ebaY"></i>';
			break;
		case WPAUTOC_MONETIZE_WALMART:
			$str .=  'Walmart Products"><i class="fa fa-shopping-cart"></i>';
			break;
		case WPAUTOC_MONETIZE_BESTBUY:
			$str .=  'Best Buy Products"><i class="fa fa-shopping-cart"></i>';
			break;
		case WPAUTOC_MONETIZE_ENVATO:
			$str .=  'Envato Products"><i class="fa fa-leaf"></i>';
			break;
		case WPAUTOC_MONETIZE_POPUP:
			$str .=  'Popup Content"><i class="fa fa-commenting-o"></i>';
			break;
		default:
			break;
	}
	$str .= '</span>';
	return $str;
}

function wpautoc_traffic_log( $campaign_id, $post_id, $pos ) {
	if( ( $pos % 50 == 1 ) && !wpautoc_is_traffic() )
		return '<a href="https://wpautocontent.com/support/trafficmodule" target="_blank">Traffic Module</a><br/>is not installed';
	$traffics = wpautoc_get_traffic( $post_id, false );
	$str = '';
	// var_dump($traffics);
	if( $traffics ) {
		foreach( $traffics as $traffic ) {
			$str .= wpautoc_log_traffic_el( $traffic );
		}
	}
	return $str;
	// return $pos;

}

function wpautoc_log_traffic_el( $type ) {
	$str = '<span style="margin-right:4px" title="';
	switch ( $type ) {
		case WPAUTOC_TRAFFIC_FACEBOOK:
			$str .=  'Facebook"><i class="fa fa-facebook"></i>';
			break;
		case WPAUTOC_TRAFFIC_BACKLINKMACHINE:
			$str .=  'Backlink Machine"><i class="fa fa-link"></i>';
			break;
		case WPAUTOC_TRAFFIC_ILI:
			$str .=  'Instant Link Indexer"><i class="fa fa-link"></i>';
			break;
		case WPAUTOC_TRAFFIC_BLI:
			$str .=  'Backlinks Indexer"><i class="fa fa-link"></i>';
			break;
		case WPAUTOC_TRAFFIC_TWITTER:
			$str .=  'Twitter"><i class="fa fa-twitter"></i>';
			break;
		case WPAUTOC_TRAFFIC_PINTEREST:
			$str .=  'Pinterest"><i class="fa fa-pinterest"></i>';
			break;
		case WPAUTOC_TRAFFIC_STUMBLEUPON:
			$str .=  'StumbleUpon"><i class="fa fa-stumbleupon"></i>';
			break;
		case WPAUTOC_TRAFFIC_MEDIUM:
			$str .=  'Medium"><i class="fa fa-medium"></i>';
			break;
		case WPAUTOC_TRAFFIC_TUMBLR:
			$str .=  'Tumblr"><i class="fa fa-tumblr"></i>';
			break;
		case WPAUTOC_TRAFFIC_LINKEDIN:
			$str .=  'Linkedin"><i class="fa fa-linkedin"></i>';
			break;
		case WPAUTOC_TRAFFIC_BUFFER:
			$str .=  'Buffer App"><i class="fa fa-play-circle-o"></i>';
			break;
		case WPAUTOC_TRAFFIC_REDDIT:
			$str .=  'Reddit"><i class="fa fa-reddit"></i>';
			break;
		case WPAUTOC_TRAFFIC_INSTAGRAM:
			$str .=  'Instagram"><i class="fa fa-instagram"></i>';
			break;
		case WPAUTOC_TRAFFIC_GOOGLEPLUS:
			$str .=  'Google Plus"><i class="fa fa-google-plus"></i>';
			break;
		default:
			break;
	}
	$str .= '</span>';
	return $str;
}
?>