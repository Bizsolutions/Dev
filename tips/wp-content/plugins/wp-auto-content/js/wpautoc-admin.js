var autoc_global_lead = 0;
jQuery(document).ready( function($) {
    // $('#autoc-tabs').tabs();

    $('a.autocpop').webuiPopover({trigger:'hover'});

    $('#autoc-tabs a.nav-tab').click(function(e){
        e.preventDefault();
        $(this).addClass("nav-tab-active");
        $(this).siblings().removeClass("nav-tab-active");
        var tab = $(this).attr("href");
        $(".tab-inner").not(tab).css("display", "none");
        $(tab).fadeIn();
    });

    if( window.location.hash ) {
          var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
          /*console.log(hash);*/
          var current_tab = jQuery("a[href='#"+hash+"']");
          current_tab.addClass("nav-tab-active");
          current_tab.siblings().removeClass("nav-tab-active");
          // var tab = $(this).attr("href");
          $(".tab-inner").not(hash).css("display", "none");
          $('#'+hash).fadeIn();
          $("html, body").animate({ scrollTop: 0 }, "slow");
    }
    /* 1.2. WP Image Selector */
    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;

    jQuery(document).on('click', '.wpautoc_img_upload', function(e) {
        e.preventDefault();
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        _custom_media = true;

        wp.media.editor.send.attachment = function(props, attachment){
            // console.log('a')
            if ( _custom_media ) {
            // console.log('b')

                // console.log(attachment)
                 parent = button.closest('.file-upload-parent');
                // parent.find('.file-upload-url').val('aaa')
                parent.find('.file-upload-url').val(attachment.url)
                jQuery('.file-upload-url').trigger('change');
                parent.find('.file-upload-img').attr('src', attachment.url)
                parent.find('.file-upload-img').show();
            } else {
                return _orig_send_attachment.apply( this, [props, attachment] );
            };
        }

        wp.media.editor.open(button);
        return false;
    });

    /* Campaign list */

    jQuery('.wpautoc-en-camp').change(function(e){
        e.preventDefault();
        var $this = jQuery(this);
        status = $this.is(':checked') ? 1 : 0;
        campaign_id = $this.attr('data-campaign-id')
// console.log(status);
        var data = {
            action: 'wpautoc_activate_campaign',
            campaign_id: campaign_id,
            status : status
        };

        jQuery.post(ajaxurl, data, function(response) {
        });

        // jQuery( '#autoc-content-modal' ).acmodal();
        return false;
    });

    jQuery('.btn-del-camp').click(function(e){
        var $this = jQuery(this);
        campaign_id = $this.attr('data-campaign-id');
        if( !campaign_id )
            return;
        if( !confirm('Are you sure?\nAll details for the campaigns will be lost\n(the created posts will not be deleted)') ) {
            return false;
        }
        var data = {
            action: 'wpautoc_delete_campaign',
            campaign_id: campaign_id
        };

        jQuery.post(ajaxurl, data, function(response) {
            document.location = document.location;
        });
        return true;
    });

    /* Campaigns */
    jQuery('#autoc-add-content').click(function(e){
        e.preventDefault();
        jQuery( '#autoc-content-modal' ).acmodal();
        return false;
    });

    jQuery('#autoc-do-add-content').click(function(e){
        e.preventDefault();
        wpatoc_add_content();
    });

    jQuery(document).on('click', '.btn-delete-content', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-content-box' )
        parent.find( '.wpautoc_content_action' ).val(2);
        parent.fadeOut();
    });

    jQuery(document).on('click', '.wpautoc-handlediv', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-content-box' )
        var inside = parent.find( '.inside' )
        inside.toggle();
    });

    /* 0. General Settings */

    jQuery(document).on('change', 'select.campaign_featured_imgs', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( 'table' )
        // var inside = parent.find( '.inside' )
        val = $this.val();
        if( val == 1 ) {
            parent.find( '.campaign_imgs' ).show();
        }
        else {
            parent.find( '.campaign_imgs' ).hide();
        }
        // console.log(val)
    });
    /* Content */

    jQuery(document).on('change', 'input.autoc_youtube_video_type', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-content-box' )
        // var inside = parent.find( '.inside' )
        val = $this.val();
        if( val == 2 ) {
            parent.find( '.autoc_youtube_keyword' ).hide();
            parent.find( '.autoc_youtube_channel' ).show();
            parent.find( '.autoc_youtube_country' ).hide();
        }
        else if( val == 3 ) {
            parent.find( '.autoc_youtube_channel' ).hide();
            parent.find( '.autoc_youtube_keyword' ).hide();
            parent.find( '.autoc_youtube_country' ).show();
        }
        else {
            parent.find( '.autoc_youtube_channel' ).hide();
            parent.find( '.autoc_youtube_keyword' ).show();
            parent.find( '.autoc_youtube_country' ).hide();
        }
        // console.log(val)
    });

    jQuery(document).on('change', 'input.bbtnon', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-content-box' )
        // var inside = parent.find( '.inside' )
        if( $this.is(':checked') )
            parent.find( '.bbtnon_row' ).show();
        else
            parent.find( '.bbtnon_row' ).hide();
    });

    jQuery(document).on('change', 'input.autoc_youtube_description', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-content-box' )
        // var inside = parent.find( '.inside' )
        var checked = $this.is( ':checked' );
        if( checked )
            parent.find( '.autoc_youtube_descsub' ).show();
        else
            parent.find( '.autoc_youtube_descsub' ).hide();
    });

    /* Monetization */

    jQuery('#autoc-add-monetization').click(function(e){
        e.preventDefault();
        jQuery( '#autoc-monetize-modal' ).acmodal();
        return false;
    });

    jQuery('#autoc-do-add-monetization').click(function(e){
        e.preventDefault();
        wpatoc_add_monetization();
    });

    jQuery(document).on('click', '.btn-delete-monetization', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        parent.find( '.wpautoc_monetize_action' ).val(2);
        parent.fadeOut();
    });

    jQuery(document).on('change', '.wpautoc_banner_pos', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        if( $this.val() == 4 )
            parent.find( '.wpautoc_paragraph_row' ).show();
        else
            parent.find( '.wpautoc_paragraph_row' ).hide();
    });

    jQuery(document).on('click', '.wpautoc-add-inline-link', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        var num = parent.find('.wpautoc_num').val();
        num_rows = parent.find( '.wpautoc_links_row' ).length + 1;
        field1_name = 'wpautoc_monetize['+num+'][settings][links]['+num_rows+'][keyword]';
        field2_name = 'wpautoc_monetize['+num+'][settings][links]['+num_rows+'][url]';
        var row = '<tr class="wpautoc_links_row"><td><input type="text" style="width:100%" name="'+field1_name+'" /></td><td><input type="text" style="width:100%" name="'+field2_name+'" /></td>'+
        '<td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
        parent.find( '.wpautoc-inline-links-table' ).append(row);
    });

    jQuery(document).on('click', '.wpautoc-add-viral-ad', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        var num = parent.find('.wpautoc_num').val();
        num_rows = parent.find( '.wpautoc_viralads_row' ).length + 1;
        field1_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][title]';
        field2_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][description]';
        field3_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][url]';
        field4_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][image]';
        var row = '<tr class="wpautoc_viralads_row"><td><textarea name="'+field1_name+'" style="width:100%"></textarea></td>'+
        '<td><textarea name="'+field2_name+'" style="width:100%"></textarea></td>'+
        '<td><textarea name="'+field3_name+'" style="width:100%"></textarea></td>'+
            '<td class="file-upload-parent">'+
            '<input style="display:none" class="regular-text file-upload-url" type="text" size="36" name="'+field4_name+'" />'+
            '<img src="" alt="" class="file-upload-img wpautoc-viral-img">'+
            '<button class="button button-secondary wpautoc_img_upload" type="button"><span class="fa fa-upload"></span> Select Image</button>'+
            '</td>'+
        '<td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
        parent.find( '.wpautoc-viral-ads-table' ).append(row);
    });

    jQuery(document).on('click', '.wpautoc-add-text-ad', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        var num = parent.find('.wpautoc_num').val();
        num_rows = parent.find( '.wpautoc_textads_row' ).length + 1;
        field1_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][title]';
        field2_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][description]';
        field3_name = 'wpautoc_monetize['+num+'][settings][ads]['+num_rows+'][url]';
        var row = '<tr class="wpautoc_viralads_row"><td><textarea name="'+field1_name+'" style="width:100%"></textarea></td>'+
        '<td><textarea name="'+field2_name+'" style="width:100%"></textarea></td>'+
        '<td><textarea name="'+field3_name+'" style="width:100%"></textarea></td>'+
        '<td><button type="button" class="button button-secondary wpautoc_remove_link_row"><i class="fa fa-remove"></i></button></td></tr>';
        parent.find( '.wpautoc-text-ads-table' ).append(row);
    });

    jQuery(document).on('click', '.wpautoc_remove_link_row', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( 'tr' )
        parent.remove();
    });

    jQuery(document).on('change', '.wpautoc_optin_show_name', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-monetization-box' )
        // var inside = parent.find( '.inside' )
        var checked = $this.is( ':checked' );
        if( checked )
            parent.find( '.wpautoc_optin_name_field_row' ).show();
        else
            parent.find( '.wpautoc_optin_name_field_row' ).hide();
    });

    /* Traffic */

    jQuery('#autoc-add-traffic').click(function(e){
        e.preventDefault();
        jQuery( '#autoc-traffic-modal' ).acmodal();
        return false;
    });

    jQuery('#autoc-do-add-traffic').click(function(e){
        e.preventDefault();
        wpatoc_add_traffic();
    });

    jQuery(document).on('click', '.btn-delete-traffic', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.autoc-traffic-box' )
        parent.find( '.wpautoc_traffic_action' ).val(2);
        parent.fadeOut();
    });

    // jQuery('#wpautoc-content-cancel').click(function(e){
    //     e.preventDefault();
    //     jQuery.acmodal('close');
    // });

    /* Autoresponders */

        jQuery('#wpautoc_mailit_type').change(function(e) {
            $this = jQuery(this);
            if( $this.val() == 2 ) {
                jQuery('#mailit-local-settings').hide();
                jQuery('#mailit-remote-settings').show();
            }
            else {
                jQuery('#mailit-remote-settings').hide();
                jQuery('#mailit-local-settings').show();
            }
        });

        jQuery('#do-activate-mailit').click(function(e) {
            e.preventDefault();
            jQuery('#activate-mailit-plg').val(1);
            jQuery('#mailit-vp-form').submit();
        });

        jQuery('.aweber_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(1, jQuery(this));
        });

        jQuery('.getresponse_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(2, jQuery(this));
        });

        jQuery('.mailchimp_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(3, jQuery(this));
        });

        jQuery('.icontact_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(4, jQuery(this));
        });

        jQuery('.constantcontact_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(5, jQuery(this));
        });

        jQuery('.sendreach_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(6, jQuery(this));
        });

        jQuery('.sendy_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(7, jQuery(this));
        });

        jQuery('.activecampaign_refresh_lists').click(function(e) {
            e.preventDefault();
            // alert('x');
            wpautoc_autoresponder_refresh(8, jQuery(this));
        });

        jQuery('.mailit_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(9, jQuery(this));
        });

        jQuery('.sendlane_refresh_lists').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(11, jQuery(this));
        });

        jQuery('.gtw_refresh_webinars').click(function(e) {
            e.preventDefault();
            wpautoc_autoresponder_refresh(101, jQuery(this));
        });

        jQuery(document).on('change', '.wpautoc_autoresponder', function(e) {
            e.preventDefault();
            /* Act on the event */
            $this = jQuery(this);
            var parent = $this.closest( '.autoc-monetization-box' )
// console.log($this.val())
            // $parent = $this.closest('.wpautoc-action')
            if ( $this.val() == 0 || $this.val() == 12 ) {
                parent.find( '.wpautoc_list_row' ).hide()
                // $parent.find('.wpautoc-ar-html-code').show()
            }
            else {
                type = $this.val();
                wpautoc_update_ar_list_by_type( type, true, parent.find( '.wpautoc_list' ) );
                // $parent.find('.wpautoc-ar-html-code').hide()
                parent.find( '.wpautoc_list_row' ).show()
            }
        });

        jQuery ('.wpautoc-remove-lead').click(function( e ){
            e.preventDefault();
            autoc_global_lead = jQuery(this).attr('data-lead-id')
            jQuery( '#autoc-leads-modal' ).acmodal();
        });

        jQuery ('#wpautoc-do-remove-lead').click(function( e ){
            e.preventDefault();
            wpautoc_delete_lead( autoc_global_lead );
        });

        jQuery ('#do-search-leads').click(function( e ){
            e.preventDefault();
            // wpautoc_delete_lead( global_lead );
            document.location = document.location + '&stm='+jQuery('#wpautoc_stm').val();
        });

        jQuery ('#do-clear-leads').click(function( e ){
            e.preventDefault();
            // wpautoc_delete_lead( global_lead );
            document.location = wpautoc_vars.admin_url+'admin.php?page=wp-auto-content-leads';
        });
});

function wpatoc_add_content() {
    type = jQuery('#wpautoc-content-types').val();;
    num = jQuery('.autoc-content-box').length + 1;
    var data = {
        action: 'wpautoc_add_content',
        type: type,
        num : num
    };

    jQuery.post(ajaxurl, data, function(response) {
        if ( response ) {
            jQuery('#wpautoc-content-els').append( response );
        }
        jQuery.acmodal.close()
    });
}

function wpatoc_add_monetization() {
    type = jQuery('#wpautoc-monetize-types').val();;
    num = jQuery('.autoc-monetization-box').length + 1;
    var data = {
        action: 'wpautoc_add_monetization',
        type: type,
        num : num
    };

    jQuery.post(ajaxurl, data, function(response) {
        if ( response ) {
            jQuery('#wpautoc-monetize-els').append( response );

            if( type == 3 || type == 6 || type == 14 ) {
                /*tinymce.execCommand( 'mceAddEditor', true, 'wpautoc_wpautoc_monetize_'+num+'__settings__html_' );
                tinyMCE.execCommand( 'mceAddControl', false, 'wpautoc_wpautoc_monetize_'+num+'__settings__html_');*/
                tinymce.execCommand('mceAddEditor', false, 'wpautoc_wpautoc_monetize_'+num+'__settings__html_');
                quicktags({id : 'wpautoc_wpautoc_monetize_'+num+'__settings__html_'});
            }
        }
        jQuery.acmodal.close()
    });
}

function wpatoc_add_traffic() {
    type = jQuery('#wpautoc-traffic-types').val();;
    num = jQuery('.autoc-traffic-box').length + 1;
    var data = {
        action: 'wpautoc_add_traffic',
        type: type,
        num : num
    };

    jQuery.post(ajaxurl, data, function(response) {
        if ( response ) {
            jQuery('#wpautoc-traffic-els').append( response );
        }
        if( type == 1 )
            console.log('rmi353')
        jQuery.acmodal.close()
    });
}

function wpautoc_autoresponder_refresh(ar_type, this_button) {
    this_button.after('<img style="margin-left:20px" src="'+wpautoc_vars.plugin_url+'/img/ajax-loader-small.gif" >')

    var data = {
        action: 'wpautoc_refresh_autoresponder',
        ar_type : ar_type
    };

    jQuery.post(ajaxurl, data, function(response) {
        // document.location = document.location;
        this_button.next().remove();
    });
}

function wpautoc_update_ar_list_by_type(type, update, element) {
    // console.log(type);
    switch (type) {
        case '1':
            lists = wpautoc_vars.aweber_lists;
            break;
        case '2':
            lists = wpautoc_vars.getresponse_lists;
            break;
        case '3':
            lists = wpautoc_vars.mailchimp_lists;
            break;
        case '4':
            lists = wpautoc_vars.icontact_lists;
            break;
        case '5':
            lists = wpautoc_vars.ccontact_lists;
            break;
        case '6':
            lists = wpautoc_vars.sendreach_lists;
            break;
        case '7':
            lists = wpautoc_vars.sendy_lists;
            break;
        case '8':
            lists = wpautoc_vars.activecampaign_lists;
            break;
        case '9':
            lists = wpautoc_vars.mailit_lists;
            break;
        case '11':
            lists = wpautoc_vars.sendlane_lists;
            break;
        case '10':
        default:
            lists = new Array();
            break;

    }
// console.log(lists);
    str_ret = '<option value="0">Select list...</option>';
    if (lists)
        n_lists = lists.length;
    else
        n_lists = 0;
    if (n_lists) {
        for (i=0;i<n_lists;i++) {
            str_ret += '<option value="'+lists[i].id+'">'+lists[i].name+'</option>';
        }
    }
    if ( update ) {
        // jQuery('.ar_list').html(str_ret);
        // parent.find('.wpautoc-ar-list-el').html(str_ret);
        element.html(str_ret);
        // parent.find('.wpautoc-ar-list-el').html(str_ret);
    }
    else
        return str_ret;
    return;
    // return str_ret;
}

function wpautoc_delete_lead( lead_id ) {

    var data = {
        action: 'wpautoc_remove_lead',
        lead_id: lead_id
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        if ( response ) {
            document.location = document.location;
        }
    });
}