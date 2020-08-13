jQuery(document).ready( function($) {
    jQuery(document).on( 'click', '.wpautoc-anotice .notice-dismiss', function() {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'wpautoc_dismiss_notice'
            }
        })
    });
});

jQuery(document).ready( function($) {
    jQuery(document).on( 'click', '.wpautoc-anotice2 .notice-dismiss', function() {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'wpautoc_dismiss_notice2'
            }
        })
    });
});