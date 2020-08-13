<?php

include WPAUTOC_DIR.'/lib/data/campaign_data.php';
include WPAUTOC_DIR.'/lib/data/monetize_data.php';

/* 1. Campaign List */
function wpautoc_list_campaigns() {
	$page = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 0;
	$per_page = 30;
	$total_campaigns = wpautoc_total_campaigns( );
	$campaigns = wpautoc_get_campaigns( $page, $per_page );
	if( $campaigns ) {
		$i = 1;
		$str = '<table class="widefat wpauto-campaign-table"><thead>';
		$str .= '<tr><th scope="col">&nbsp;</th><th scope="col">Name</th><th scope="col">Imported <a href="#" class="autocpop"><i class="fa fa-question"></i></a>
<div class="webui-popover-content">
   <p>Number of posts imported by the campaign so far</p>
</div></th><th scope="col">Status <a href="#" class="autocpop"><i class="fa fa-question"></i></a>
<div class="webui-popover-content">
   <p>If the campaign is paused (grey button), no posts will be imported.<br/>Activate it (make it green) so it starts importing again.</p>
</div></th><th scope="col">&nbsp;</th></tr><tbody>';
		foreach( $campaigns as $campaign ) {
			$str .= wpautoc_campaign_row( $campaign, $i++ );
		}
		$str .= '</tbody></table>';
		echo $str;

		// $offset = ( $page - 1 ) * $per_page;
		$num_of_pages = ceil( $total_campaigns / $per_page );

		$page_links = paginate_links( array(
		    'base' => add_query_arg( 'pagenum', '%#%' ),
		    'format' => '',
		    'show_all' => true,
		    'prev_text' => __( '&laquo;', 'wpauto' ),
		    'next_text' => __( '&raquo;', 'wpauto' ),
		    'total' => $num_of_pages,
		    'current' => $page
		) );

		if ( $page_links ) {
		    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
	}
	else
		echo '<p>You have not created any campaigns yet</p>';
}

function wpautoc_campaign_row( $campaign, $i ) {
	$imported = wpautoc_get_imported_campaign( $campaign->id );
	$str = '<tr><th scope="row">'.$i.'</th>';
	$str .= '<td>'.stripslashes( $campaign->name ).'</td>';
	$str .= '<td>'.$imported.'</td>';
	$str .= '<td>'.wpautoc_status_toggle( $campaign->status, $campaign->id ).'</td>';
	$str .= '<td><a class="button button-primary" href="'.admin_url( '/admin.php?page=wp-auto-content-campaign&campaign_id='.$campaign->id ) .'"><i class="fa fa-edit"></i> Edit</a>
	<button class="button button-secondary btn-del-camp" data-campaign-id="'.$campaign->id.'" href="#"><i class="fa fa-remove"></i> Delete</button>

	<a class="button button-primary" target="_blank" href="'.site_url( 'wp-content/plugins/wp-auto-content/cron.php?force_import=1&do_debug=1&cid='.$campaign->id ) .'"><i class="fa fa-gear"></i> Run!</a>
	</td>';
	$str .= '</tr>';
	return $str;
}

function wpautoc_status_txt( $status ) {
	return ( $status ? 'Active' : 'Inactive' );
}

function wpautoc_status_toggle( $status, $campaign_id ) {

	$str = '<input type="checkbox" class="autoc-toggle wpautoc-en-camp" data-campaign-id="'.$campaign_id.'" id="autoc_t_'.$campaign_id.'" '.checked( $status, 1, false ).'>
	<label for="autoc_t_'.$campaign_id.'">
		<span></span>
	</label>';
	return $str;
}

function wpautoc_campaign_select( $name = 'fcampaign', $campaign_id = 0 ) {
	$campaigns = wpautoc_get_campaigns( 0, 1000 );
	$str = '<select name="'.$name.'" id="'.$name.'"><option value="0">- All - </option>';
	if( $campaigns ) {
		foreach( $campaigns as $campaign ) {
			// if( $campaign_id )
				$sel = selected( $campaign_id, $campaign->id, false );
			$str .= '<option value="'.$campaign->id.'" '.$sel.'>'.$campaign->name.'</option>';
		}
	}
	$str .= '</select>';
	return $str;
}

function wpautoc_source_select( $name = 'scampaign', $content_id = 0 ) {
	$contents = wpautoc_get_content_name( );
	$str = '<select name="'.$name.'" id="'.$name.'"><option value="0">- All - </option>';
	if( $contents ) {
		foreach( $contents as $id => $name ) {
			// if( $content_id )
			$sel = selected( $content_id, $id, false );
			$str .= '<option value="'.$id.'" '.$sel.'>'.$name.'</option>';
		}
	}
	$str .= '</select>';
	return $str;
}
/* 2. Individual Campaign */

function wpautoc_add_create_campaign( $campaign_id ) {
	if( $campaign_id )
		$campaign = wpautoc_get_campaign( $campaign_id );
	else
		$campaign = wpautoc_get_default_campaign_values();
?>
	<div id="autoc-tabs" >
		<h2 class="nav-tab-wrapper">
			<a href="#autoc-general-tab" class="nav-tab nav-tab-active"><i class="fa fa-cog"></i> 1. General Settings </a>
			<a href="#autoc-content-tab" class="nav-tab" ><i class="fa fa-list"></i> 2. Get Content </a>
				<a href="#autoc-monetize-tab" class="nav-tab"><i class="fa fa-dollar"></i> 3. Monetize </a>
			<?php if( wpautoc_is_traffic() ) { ?>
				<a href="#autoc-traffic-tab" class="nav-tab"><i class="fa fa-link"></i> 4. Get Traffic</a>
			<?php } ?>
		</h2>
		<div style="clear:both"></div>
		<div id="autoc-general-tab" class="tab-inner" style="display:block"><?php wpautoc_camp_tab_general( $campaign_id, $campaign );?></div>
		<div id="autoc-content-tab" class="tab-inner"><?php wpautoc_camp_tab_content( $campaign_id );?></div>
		<div id="autoc-monetize-tab" class="tab-inner"><?php wpautoc_camp_tab_monetize( $campaign_id );?></div>
		<div id="autoc-traffic-tab" class="tab-inner"><?php wpautoc_camp_tab_traffic( $campaign_id );?></div>
	</div>
	<br/>
	<hr/>
	<?php wpautoc_field_hidden( 'wpautoc_save_campaign', 1 ); ?>
	<?php wpautoc_field_hidden( 'wpautoc_campaign_id', $campaign_id ); ?>
	<div style="display: none">
		<?php wp_editor('aaab2', 'aaab2'); ?>
	</div>
	<!-- <input type="submit" class="button button-primary" value="Save All Changes" /> -->
	<button type="submit" class="button button-primary"><i class="fa fa-save"></i> Save All Changes</button>
	<a class="button button-secondary" href="<?php echo admin_url( '/admin.php?page=wp-auto-content' );?>" ><i class="fa fa-undo"></i> Cancel</a>
<?php
}

function wpautoc_camp_tab_general( $campaign_id, $campaign ) {
	$end_date = ( empty( $campaign->end_date ) || is_null( $campaign->end_date ) || ( $campaign->end_date == '0000-00-00' ) ) ? '' : $campaign->end_date;
	$settings = isset( $campaign->settings ) ? json_decode( $campaign->settings ) : false;
	$category = isset( $settings->category ) ? $settings->category : 0;
	$author = isset( $settings->author ) ? $settings->author : 1;
	$tags = isset( $settings->tags ) ? trim($settings->tags) : '';
	$imgs = isset( $settings->imgs ) ? intval($settings->imgs) : 0;
	$post_status = isset( $settings->post_status ) ? intval($settings->post_status) : 0;
	$img_keywords = isset( $settings->img_keywords ) ? trim($settings->img_keywords) : '';
	$yesno = wpautoc_get_yesno();

?>
	<br/>
	<a href="https://wpautocontent.com/support/knowledgebase/how-to-create-a-campaign/" target="_blank">Need help setting up a campaign?</a><br/>
	<table class="form-table">
		<?php wpautoc_field_text( 'Name', 'wpautoc_campaign_name', $campaign->name, false, '', 'Give your campaign a name (not visible for users, just for you)' ); ?>
		<?php /*wpautoc_field_datepicker( 'End Date (optional)', 'wpautoc_campaign_enddate', $end_date, false, '', 'The campaign will stop running on this date' );*/ ?>
		<?php wpautoc_field_numeric( 'Posts per day', 'wpautoc_campaign_perday', $campaign->per_day, false, '', 'Number of posts to be created per day (we recommend having no more than 10 posts per campaign/day for optimal performance)' ); ?>
		<?php wpautoc_field_category( 'Category', 'wpautoc_campaign_category', $category, false, '', 'Category for new posts' ); ?>
		<?php wpautoc_field_author( 'Author', 'wpautoc_campaign_author', $author, false, '', 'Author for new posts' ); ?>
		<?php
			wpautoc_field_select( 'Post Status', 'wpautoc_campaign_post_status', $post_status, wpautoc_get_poststatus(), false, 'If you select "Draft", the new posts for this campaign will not be published directly, and you will have to publish them manually' );
		?>

		<?php wpautoc_field_textarea( 'Tags', 'wpautoc_campaign_tags', $tags, false, '', 'Tags to be applied to the new posts (one per line)', 'One per line' ); ?>
		<?php
			wpautoc_field_select( 'Get Extra Images', 'wpautoc_campaign_imgs', $imgs, $yesno, false, $help = 'If you select "Yes", the plugin will try to download Copyright Free featured images for the content with no attached image', '', 'campaign_featured_imgs' );
			wpautoc_field_text( '&nbsp;&nbsp;&nbsp;Image Keywords', 'wpautoc_campaign_img_keywords', $img_keywords, false, '', 'Keywords to search images (ex: dogs,dog training,german shepherd), related to your campaign. If you want to enter more than one, separate them by comma', 'keyword1,keyword2,keyword3', 'campaign_imgs', $imgs );
		?>

	</table>
<!-- 	<p>TODO: Replace keywords</p>
	<p>TODO: Negative keywords</p> -->
<?php
}

function wpautoc_camp_tab_content( $campaign_id ) {
	echo '<h2>Content Sources: <a href="https://wpautocontent.com/support/knowledgebase/how-to-set-up-content-sources/" target="_blank">Help</a></h2>';
	wpautoc_print_content_elements( $campaign_id );
	echo '<br/><button id="autoc-add-content" type="button" class="button button-primary"><i class="fa fa-plus"></i> Add Content Source</button><br/>';
}

function wpautoc_camp_tab_monetize( $campaign_id ) {
	echo '<h2>Monetization Methods: <a href="https://wpautocontent.com/support/knowledgebase/how-to-set-up-monetization-methods/" target="_blank">Help</a></h2>';
	wpautoc_print_monetize_elements( $campaign_id );
	echo '<br/><button id="autoc-add-monetization" type="button" class="button button-primary"><i class="fa fa-plus"></i> Add Monetization Method</button><br/>';
}

function wpautoc_camp_tab_traffic( $campaign_id ) {
	echo '<h2>Traffic Systems: <a href="https://wpautocontent.com/support/knowledgebase/how-to-set-up-traffic-sources/" target="_blank">Help</a></h2>';
	wpautoc_print_traffic_elements( $campaign_id );
	echo '<br/><button id="autoc-add-traffic" type="button" class="button button-primary"><i class="fa fa-plus"></i> Add Traffic System</button><br/>';
}


/* Save Campaign */

function wpautoc_save_campaign( $campaign_id ) {
    $name = isset( $_POST['wpautoc_campaign_name'] ) ? sanitize_text_field( $_POST['wpautoc_campaign_name'] ) : '';
    $end_date = isset( $_POST['wpautoc_campaign_enddate'] ) ? sanitize_text_field( $_POST['wpautoc_campaign_enddate'] ) : NULL;
    $per_day = isset( $_POST['wpautoc_campaign_perday'] ) ? intval( $_POST['wpautoc_campaign_perday'] ) : 0;

    $settings = array(
    	'category' => isset( $_POST['wpautoc_campaign_category'] ) ? intval( $_POST['wpautoc_campaign_category'] ) : 0,
    	'author' => isset( $_POST['wpautoc_campaign_author'] ) ? intval( $_POST['wpautoc_campaign_author'] ) : 1,
    	'tags' => isset( $_POST['wpautoc_campaign_tags'] ) ? sanitize_textarea_field( $_POST['wpautoc_campaign_tags'] ) : '',
    	'post_status' => isset( $_POST['wpautoc_campaign_post_status'] ) ? sanitize_textarea_field( $_POST['wpautoc_campaign_post_status'] ) : '',
    	'imgs' => isset( $_POST['wpautoc_campaign_imgs'] ) ? intval( $_POST['wpautoc_campaign_imgs'] ) : 0,
    	'img_keywords' => isset( $_POST['wpautoc_campaign_img_keywords'] ) ? sanitize_textarea_field( $_POST['wpautoc_campaign_img_keywords'] ) : '',
    );
   	$settings = wpautoc_raw_json_encode( $settings );

 /*   if( isset( $content['settings'] ) )
    	$settings = wpautoc_raw_json_encode( $content['settings'] );
    else
    	$settings = '';
*/
    if( !$campaign_id ) {
    	$campaign_id = wpautoc_add_campaign( $name, date( 'Y-m-d' ), $end_date, $per_day, $settings );
    }
    else
    	wpautoc_update_campaign( $campaign_id, $name, $end_date, $per_day, $settings );

    $content = isset( $_POST['wpautoc_content'] ) ? $_POST['wpautoc_content'] : false;
    wpautoc_save_campaign_content( $campaign_id, $content );
    $monetize = isset( $_POST['wpautoc_monetize'] ) ? $_POST['wpautoc_monetize'] : false;
    wpautoc_save_campaign_monetization( $campaign_id, $monetize );
    $traffic = isset( $_POST['wpautoc_traffic'] ) ? $_POST['wpautoc_traffic'] : false;
    wpautoc_save_campaign_traffic( $campaign_id, $traffic );
    return $campaign_id;
}

function wpautoc_save_campaign_content( $campaign_id, $content_els ) {
	if( $content_els ) {
		foreach( $content_els as $content ) {
			$action = $content['action'];
			$content = wpautoc_array_map_recursive( 'htmlspecialchars', $content );
			if  (isset ( $content['settings']['html'] ) ) {
				$content['settings']['html'] = html_entity_decode(htmlentities($content['settings']['html'], ENT_QUOTES, 'UTF-8'));
			}

			if( isset( $content['settings'] ) )
				$settings = wpautoc_raw_json_encode( $content['settings'] );
			else
				$settings = '';

			if( $action == 0 )
				wpautoc_add_content( $campaign_id, $content['type'], $settings );
			else if( $action == 1 )
				wpautoc_update_content( $content['id'], $settings );
			else
				wpautoc_delete_content( $content['id'] );
		}
	}
}

function wpautoc_save_campaign_monetization( $campaign_id, $monetize_els ) {
	if( $monetize_els ) {
		foreach( $monetize_els as $content ) {
			$action = $content['action'];
			$content = wpautoc_array_map_recursive( 'htmlspecialchars', $content );
			if  (isset ( $content['settings']['html'] ) ) {
				$content['settings']['html'] = html_entity_decode( htmlentities( $content['settings']['html'], ENT_QUOTES, 'UTF-8' ) );
			}

			if( $content['type'] == WPAUTOC_MONETIZE_AMAZON ) {
				delete_transient( 'amazon_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_EBAY ) {
				delete_transient( 'ebay_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_CLICKBANK ) {
				delete_transient( 'cb_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_ENVATO ) {
				delete_transient( 'envato_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_WALMART ) {
				delete_transient( 'walmart_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_BESTBUY ) {
				delete_transient( 'bestbuy_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_ALIEXPRESS ) {
				delete_transient( 'aliexpress_products'.$content['id'] );
			}
			else if( $content['type'] == WPAUTOC_MONETIZE_GEARBEST ) {
				delete_transient( 'gearbest_products'.$content['id'] );
			}
/*			else if( $content['type'] == WPAUTOC_MONETIZE_CLICKBANK ) {
				include_once WPAUTOC_DIR.'/lib/monetize/clickbank.php';
				$keyword = isset( $content['settings']['keyword'] ) ? $content['settings']['keyword'] : false;
				// $category = isset( $content['settings']['category'] ) ? $content['settings']['category'] : 'All';
				$content['settings']['products'] = wpautoc_update_clickbank_data( $keyword );
			}

			else if( $content['type'] == WPAUTOC_MONETIZE_ALIEXPRESS ) {
				include_once WPAUTOC_DIR.'/lib/monetize/aliexpress.php';
				$keyword = isset( $content['settings']['keyword'] ) ? $content['settings']['keyword'] : false;
				$category = isset( $content['settings']['category'] ) ? $content['settings']['category'] : 'All';
				$content['settings']['products'] = wpautoc_update_aliexpress_data( $keyword, $category );
			}*/

			if( isset( $content['settings'] ) )
				$settings = wpautoc_raw_json_encode( $content['settings'] );
			else
				$settings = '';


			if( $action == 0 )
				wpautoc_add_monetization( $campaign_id, $content['type'], $settings );
			else if( $action == 1 )
				wpautoc_update_monetization( $content['id'], $settings );
			else
				wpautoc_delete_monetization( $content['id'] );
		}
	}
}

function wpautoc_save_campaign_traffic( $campaign_id, $traffic_els ) {
	if( $traffic_els ) {
		foreach( $traffic_els as $content ) {
			$action = $content['action'];
			$content = wpautoc_array_map_recursive( 'htmlspecialchars', $content );
			if  (isset ( $content['settings']['html'] ) ) {
				$content['settings']['html'] = html_entity_decode(htmlentities($content['settings']['html'], ENT_QUOTES, 'UTF-8'));
			}
			if( isset( $content['settings'] ) )
				$settings = wpautoc_raw_json_encode( $content['settings'] );
			else
				$settings = '';

			if( $action == 0 )
				wpautoc_add_traffic( $campaign_id, $content['type'], $settings );
			else if( $action == 1 )
				wpautoc_update_traffic( $content['id'], $settings );
			else
				wpautoc_delete_traffic( $content['id'] );
		}
	}
}
?>