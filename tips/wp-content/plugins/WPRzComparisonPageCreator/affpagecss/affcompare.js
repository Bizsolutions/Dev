jQuery(document).ready(function($) {

	jQuery("input[name='comp']").attr('checked', false);

	jQuery('.clearlink').live("click", function(e) {	
		e.preventDefault();
		jQuery("#comparisonholder").remove();
		jQuery("input[name='comp']").attr('checked', false);
	});
	
	//jQuery('.hp-prod2 label').live("click", function(e) {	
	jQuery('#comparison_page .hp-prod2 label').click(function(e) {

		if(jQuery("#comparisonholder").length == 0) {
			jQuery( '<div id="comparisonholder"><div class="ch-clear"><a href="#" class="clearlink">Clear Selection and Remove</a></div><div class="ch-top"><div id="ch-pic1" class="ch-pic"></div><div class="ch-vs">vs</div><div id="ch-pic2" class="ch-pic"></div></div><div class="ch-bottom"><span>Select another product below to compare</span></div></div>' ).insertAfter( "div#page" );
		}
		
		var img = jQuery(this).find("img").attr('src');
		var asin = jQuery(this).attr('for');
		
		var thecat = jQuery(this).attr('class');
		var oldcat = jQuery("#ch-pic img").attr('id');

		if(jQuery("#ch-pic1").find('img').length == 0) {
			jQuery("#ch-pic1").html('<img id="' + asin + '" class="' + asin + '" src="' + img + '" />');
		//} else if(thecat != "" && oldcat != "" && thecat != oldcat) {	// if CATs are different replace image1 instead		
		//	jQuery("#ch-pic1").html('<img id="' + asin + '" class="' + asin + '" src="' + img + '" />');
		} else {
			if(jQuery("#ch-pic2").find('img').length !== 0) {
				var oldasin = jQuery("#ch-pic2 img").attr('class');
				jQuery('#'+oldasin).attr('checked', false);
			}
			
			jQuery("#ch-pic2").html('<img class="' + asin + '" src="' + img + '" />');		
	
			var asin1 = jQuery("#ch-pic1 img").attr('class');
			var asin2 = jQuery("#ch-pic2 img").attr('class');
		
			var data = {
				action: "affpagesajax",
				asin1: asin1,
				asin2: asin2,
			}; 		
			
			jQuery.post(ajaxurl, data, function(response) {

				if(response.error != undefined && response.error != "") {
					jQuery('.ch-bottom').html("ERROR: " + response.error);
				} else {
					var link = response.link;
					
					jQuery('.ch-bottom').html('<a href="' + link + '" target="_blank">View Comparison</a>');
					
				}	
			}, "json");			
		}
	});	
});