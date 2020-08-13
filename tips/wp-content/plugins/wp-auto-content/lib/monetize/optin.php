<?php
function wpautoc_monetize_optin( $content, $monetize ) {
	if( !isset( $monetize->settings ) )
		return $content;
	$settings = json_decode( $monetize->settings );
	if( empty( $settings ) )
		return $content;
	$post_id = get_the_id();
	$form_style = isset( $settings->style ) ? intval( $settings->style ) : 1;
	$show_name = isset( $settings->show_name ) ? intval( $settings->show_name ) : 0;
	$name_txt = ( isset( $settings->name_txt ) && !empty( $settings->name_txt  ) ) ? trim( sanitize_text_field( $settings->name_txt ) ) : 'Your Name';
	$email_txt = ( isset( $settings->email_txt ) && !empty( $settings->email_txt  ) ) ? trim( sanitize_text_field( $settings->email_txt ) ) : 'Your Email';
	$submit_txt = ( isset( $settings->submit_txt ) && !empty( $settings->submit_txt  ) ) ? trim( sanitize_text_field( $settings->submit_txt ) ) : 'Submit';

	$intro_txt = ( isset( $settings->intro_txt ) && !empty( $settings->intro_txt  ) ) ? trim( sanitize_textarea_field( $settings->intro_txt ) ) : false;

	$thankyou_txt = ( isset( $settings->thankyou_txt ) && !empty( $settings->thankyou_txt  ) ) ? trim( sanitize_textarea_field( $settings->thankyou_txt ) ) : false;

	$redirect_url = ( isset( $settings->redirect_url ) && !empty( $settings->redirect_url  ) ) ? trim( sanitize_text_field( $settings->redirect_url ) ) : '';

	$optin_el = '<div class="wpac-optin-form2 wpac-optin-form wpac-style'.$form_style.'"><div class="wpac-optin-formp">';
	if( !empty( $intro_txt) )
		$optin_el .= '<p class="wpac_intro_txt">'.$intro_txt.'</p>';

	$optin_el .= '<div class="wpac-optin-fields">';
	if( $show_name ) {
		$optin_el .= '<span class="wpac-optin-label">'.$name_txt.'</span>';
		$optin_el .= '<input type="text" style="margin-top:10px" name="wpac-name-to" value="" class="wpac-optin-field wpac-name">';
	}
	$optin_el .= "<span class='wpac-optin-label'>".$email_txt."</span>";
	$optin_el .= '<input type="email" style="margin-top:10px" name="wpac-email-to" value="" class="wpac-optin-field wpac-email"><input type="hidden" class="wpac-post-id" value="'.$post_id.'">';
	$optin_el .= '<input type="hidden" class="wpac-redirect-url" value="'.$redirect_url.'" />';
	$optin_el .= '<input type="hidden" class="wpac-monetize-id" value="'.$monetize->id.'" />';
	$optin_el .= '<span class="wpac-thankyou" style="display:none">'.$thankyou_txt.'</span>';
	$optin_el .= '</div><input class="wpac_submit_optin wpac-submit-button" type="submit" name="email_submit" value="'.$submit_txt.'">';
	$optin_el .= '</div></div>';
	return wpautoc_add_element_in_content( $optin_el, $content, $settings );

	// TO-DO, cookie con e-mail?
}

function wpautoc_add_optin_ajax() {
	$email = isset( $_POST['email_to'] ) ? trim( sanitize_text_field( $_POST['email_to'] ) ) : false;
	if( !$email ) {
		wp_send_json( array( 'INVALIDEMAIL' => true) );
		exit();
	}

	$name = isset( $_POST['name_to'] ) ? trim( sanitize_text_field( $_POST['name_to'] ) ) : false;
	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

	$valid = wpautoc_insert_optin( $email, $name, $post_id );

	$monetize_id = isset( $_POST['monetize_id'] ) ? intval( $_POST['monetize_id'] ) : 0;
	if( $monetize_id ) {
		$mon = wpautoc_get_monetize_element( $monetize_id );
		$settings = $mon->settings;
		$settings = json_decode( $settings );
		$ar_type = $settings->ar_type;
		$list = isset( $settings->list ) ? $settings->list : 0;
		wpautoc_signup_customer_autoresponder( $ar_type, $list, $email, $name );
		// var_dump( $settings );
		// die();
	}
	/*$settings = wplpdf_get_settings( );
	if( $settings['optin']['autoresponder'] && ( $settings['optin']['autoresponder'] != 1 ) && $email && !empty( $email )  ) {
		// echo "todo ok;";
		wplpdf_signup_customer_autoresponder( $settings['optin']['autoresponder'], $settings['optin']['list'], $email, $name );
	}*/

	if( $valid ) {
		wp_send_json( array( 'SAVED' => true ) );
	}
	exit();
}

function wpautoc_get_optin_styles() {
	return array(
		array( 'label' => 'Black', 'value' => 1),
		array( 'label' => 'Green board', 'value' => 2),
		array( 'label' => 'White Clean', 'value' => 3),
		array( 'label' => 'Black and Green', 'value' => 4),
		array( 'label' => 'Pink', 'value' => 5),
		array( 'label' => 'Postcard', 'value' => 6),
		array( 'label' => 'By airplane', 'value' => 7),
		array( 'label' => 'White stylish', 'value' => 8),
		array( 'label' => 'Black dotted', 'value' => 9),
		array( 'label' => 'Grey bg', 'value' => 10),
	);
}


function wpautoc_leads_screen() {
	$search = isset( $_GET['stm'] ) ? $_GET['stm'] : '';
	$total_leads = wpautoc_get_total_leads( $search );
?>
	<a href="<?php echo admin_url("admin.php?page=wp-auto-content-leads&dolexport=1");?>" class="button button-secondary" style="float:right"><i class="fa fa-download"></i> Export All</a>
	<div style="clear:both"></div>

	      <input type="text" name="stm" id="wpautoc_stm" class="regular-text" placeholder="Search name or email..." value="<?php echo $search;?>">
	      <span class="input-group-btn">
	        <button class="button" type="button button-secondary" id="do-search-leads"><i class="fa fa-search"></i> Search</button>
	        <a class="button button-secondary" type="button" href="<?php echo admin_url('admin.php?page=wp-auto-content-leads');?>"><i class="fa fa-remove"></i> Show all</a>
	      </span>


	  		<?php if( $total_leads ) { ?>
				<h4>Total Leads: <?php echo $total_leads;?></h4>
	  		<?php } ?>
			<?php wpautoc_leads_list(); ?>

	  	<div id="autoc-leads-modal" style="display:none">
	  		<h3 style="text-align:center">Are you Sure?</h3>
	  		<p>This action cannot be undone.</p>
	  		<br/>
	  		<br/>
	  		<button type="button" id="wpautoc-do-remove-lead" class="button button-primary">Remove Lead</button>
	  		<a class="button button-secondary" href="#close" rel="acmodal:close">Cancel</a>
	  	</div>

<?php
}


function wpautoc_leads_list() {
	$page = isset( $_GET['pgl'] ) ? $_GET['pgl'] : 0;
	$search = isset( $_GET['stm'] ) ? $_GET['stm'] : false;
	$search_str = '';
	$results_per_page = 60;
	$leads = wpautoc_get_leads( $page, $results_per_page, $search );
	$str_ret = '';
	if (!$leads) {
		$str_ret .= '<p>No Leads</p>';
	}
	else {
		$str_ret .= '<table class="widefat"><thead>';
		$str_ret .= '<tr>
			<th>#</th>
			<th scope="col">Email</th>
			<th scope="col">Name</th>
			<th scope="col">Date Added</th>
			<th scope="col">&nbsp;</th></tr></thead><tbody>';
			$i = ($page * $results_per_page) + 1;
		foreach ( $leads as $lead ) {
			$str_ret .= '<tr>
				<th scope="row" >'.$i++.'</th>
				<th scope="row" >'.$lead->email.'</th>
				<td >'.(empty( $lead->name ) ? '-' : $lead->name).'</td>
				<td >'.$lead->date_f.'</td>
				<td >
					<a class="button button-secondary wpautoc-remove-lead" href="#" data-lead-id="'.$lead->id.'"><i class="fa fa-remove"></i> Delete</a>
				</td>

			</tr>';
		}
		$str_ret .= '</tbody></table><br/><br/>';
	}

	if( $search )
		$search_str = '&stm='.$search;

	if( $page > 0 )
		$str_ret .= '<a class="button button-primary" href="'.admin_url('admin.php?page=wp-auto-content-leads'.$search_str.'&pgl='.($page -1)).'"><i class="fa fa-chevron-left"></i> Prev Page</a>  &nbsp; &nbsp;';

	if( count( $leads ) >= $results_per_page ) {
		$str_ret .= '<a class="button button-primary" href="'.admin_url('admin.php?page=wp-auto-content-leads'.$search_str.'&pgl='.++$page).'"><i class="fa fa-chevron-right"></i> Next Page</a>';
	}

	echo $str_ret;
}

function wpautoc_remove_lead_ajax() {
	if( isset( $_POST['lead_id'] ) )
		wpautoc_delete_lead( intval( $_POST['lead_id'] ) );
	echo 1;
	exit();
}

function wpautoc_export_leads ( $filename = "leads.csv", $delimiter=";") {
	$leads = wpautoc_get_all_leads();
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    foreach ($leads as $line) { 
        // generate csv lines from the inner arrays
        // var_dump($line);
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
    exit();
}

add_action('admin_init', 'wpautoc_check_dlleads');

function wpautoc_check_dlleads() {
	if( isset( $_GET['dolexport'] ) && $_GET['dolexport'] ) {
		wpautoc_export_leads( );
	}
}
?>