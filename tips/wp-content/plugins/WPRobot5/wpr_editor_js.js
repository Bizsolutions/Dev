	function toggleChecked(cname, status) {
		jQuery("."+cname).each( function() {
			jQuery(this).attr("checked",status);
		})
	}	
	
	jQuery(document).ready(function($) {

		var down;
		jQuery(".optionselector").change(function(){
			if (down != "") {
				jQuery("#" + down).hide();
			}
			down = jQuery(this).val();
			jQuery("#" + down).slideDown("fast");
		});	
	
		jQuery('#wpr-get-content-button').click(function(e) {

			e.preventDefault();

			if(jQuery("input[name='insert_topic']").val() !== "" && jQuery("select[name='insert_module']").val() !== "") {
				var data = "";
				var post = jQuery(this).attr("name") + "=" + jQuery(this).val();  
				//data = jQuery('form#popup_form').serialize() + "&" + post + "&action=wpr5_post_editor_data_save";  
				//data = jQuery('form#post').serialize() + "&" + post + "&action=wpr5_post_editor_data_save";  
				data = jQuery('#insert-form input, #insert-form select, #insert-form option').serialize() + "&" + post + "&action=wpr5_post_editor_data_save";  

				console.log( data );
				
				jQuery('#insert-form').hide(); // Loading Graphic Show
				jQuery('#loading').show();
				
				jQuery.post(ajaxurl, data, function(response) {

					jQuery('#insert-form').show(); // Loading Graphic Hide
					jQuery('#loading').hide();		

					if(response.error != undefined && response.error != "") {
						jQuery("#error_message").html("<p>" + response.error + "</p>");
					} else if (response == 'undefined' || response === null || response === '' || response === 0) {	
						jQuery("#error_message").html("<p>Error: Empty response received. Please try again.</p>");
					} else {		

						for (i=0;i<=5;i++) {
							jQuery('#cb-' + i).attr('checked', false);
							if(i == 0) {
								jQuery('#c' + i).empty();
							} else {
								jQuery('#cont' + i).remove();
							}	
						}					
					
						var x = 0;
						for (i in response) {

							var l = x + 1;
							var clone = jQuery("#cont" + x).clone();
							clone.attr("id", "cont" + l);
							clone.find("#cb-" + x).attr("id","cb-" + l);
							clone.find("#c" + x).attr("id","c" + l);
							clone.find("#cl-" + x).attr("id","cl-" + l).attr("for","cb-" + l);
							clone.insertAfter("#cont" + x);	
							x = x + 1;

							jQuery("#" + i).html(response[i]);
						}
						
						jQuery('#cont' + x).remove();	

						if (jQuery("#buttons_top > button").length > 0) {
						} else {
							jQuery('.ui-dialog-buttonset').find('button:contains("Insert Selected")').clone(true).appendTo('#buttons_top');
							jQuery('.ui-dialog-buttonset').find('button:contains("Close")').clone(true).appendTo('#buttons_top');
						}
						
						jQuery("#error_message").html("");
						jQuery('.module-settings-box').hide();		
						jQuery('#insert-content-form-results').show();		
					}						
					
				}, "json");
				return false;
			} else {
				jQuery("#error_message").html("<p>Please enter a topic and select a module from the list.</p>");
			}			
		});	

		jQuery('#wpr-insert-content-button').click(function(e) {

			e.preventDefault();		
	
			for (i=0;i<=5;i++) {
				if (jQuery('#cb-' + i).attr('checked')) {
				
					if(jQuery("#content").is(":visible")) {
						document.getElementById("content").value += jQuery('#c' + i).html();	
					} else {
						tinyMCE.execCommand('mceInsertContent',false,jQuery('#c' + i).html());	
					}
				}	

				/*jQuery('#cb-' + i).attr('checked', false);
				if(i == 0) {
					jQuery('#c' + i).empty();
				} else {
					jQuery('#cont' + i).remove();
				}*/
			}	
		});	

		jQuery('#wpr-clear-content-button').click(function(e) {
		
			e.preventDefault();	

			for (i=0;i<=5;i++) {
				jQuery('#cb-' + i).attr('checked', false);
				if(i == 0) {
					jQuery('#c' + i).empty();
				} else {
					jQuery('#cont' + i).remove();
				}	
			}
			jQuery('#insert-content-form-results').hide();		

		});			
	});	
