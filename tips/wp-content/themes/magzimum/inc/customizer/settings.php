<?php
add_filter( 'magzimum_filter_default_theme_options', 'magzimum_theme_settings_default_options' );
/**
 * Theme Settings defaults.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_theme_settings_default_options' ) ):

  function magzimum_theme_settings_default_options( $input ){

    // Header
    $input['site_logo']        = '';
    $input['show_tagline']     = true;
    $input['social_in_header'] = false;

    // Footer
    $input['copyright_text']  = esc_html__( 'Copyright. All rights reserved.', 'magzimum' );

    //Scroll Up
    $input['go_to_top']       = true;

    // Home Page
    $input['show_blog_listing_in_front'] = true;

    // Blog
    $input['excerpt_length']       = 40;
    $input['read_more_text']       = esc_html__( 'Read More ...', 'magzimum' );
    $input['exclude_categories']   = '';
    $input['author_bio_in_single'] = false;
    $input['post_meta_on_blog'] = true; 
    $input['image_on_blog'] = true;

    // Breadcrumb
    $input['breadcrumb_type']      = 'disabled';
    $input['breadcrumb_separator'] = '&gt;';

    // Pagination
    $input['pagination_type']       = 'default';

    // Layout
    $input['site_layout']            = 'fluid';
    $input['global_layout']          = 'right-sidebar';
    $input['archive_layout']         = 'excerpt-thumb';
    $input['single_image']           = 'large';

    return $input;
  }

endif;

add_filter( 'magzimum_theme_options_args', 'magzimum_settings_theme_options_args' );
/**
 * Theme settings.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_settings_theme_options_args' ) ):

  function magzimum_settings_theme_options_args( $args ){
    if ( function_exists( 'has_custom_logo' ) ) {
    $args['panels']['theme_option_panel']['sections']['section_header'] = array(
      'title'    => esc_html__( 'Header', 'magzimum' ),
      'priority' => 40,
      'fields'   => array(
        'site_logo' => array(
          'title'             => esc_html__( 'Logo', 'magzimum' ),
          'type'              => 'image',
          'sanitize_callback' => 'esc_url_raw',
        )
        )
        );
      }
    // Header Section
    $args['panels']['theme_option_panel']['sections']['section_header'] = array(
      'title'    => esc_html__( 'Header', 'magzimum' ),
      'priority' => 40,
      'fields'   => array(
        'show_tagline' => array(
          'title'   => esc_html__( 'Show Tagline', 'magzimum' ),
          'type'    => 'checkbox',
        ),
        'social_in_header' => array(
          'title'   => esc_html__( 'Show Social Icons', 'magzimum' ),
          'type'    => 'checkbox',
        ),
      )
    );

    // Footer Section
    $args['panels']['theme_option_panel']['sections']['section_footer'] = array(
      'title'    => esc_html__( 'Footer', 'magzimum' ),
      'priority' => 80,
      'fields'   => array(
        'copyright_text' => array(
          'title' => esc_html__( 'Copyright Text', 'magzimum' ),
          'type'  => 'text',
        ),
      )
    );

    //Scroll Up Section
    $args['panels']['theme_option_panel']['sections']['scroll_up'] = array(
      'title'    => esc_html__( 'Scroll Up', 'magzimum' ),
      'priority' => 80,
      'fields'   => array(
        'go_to_top' => array(
          'title' => esc_html__( 'Check to Show Go To Top', 'magzimum' ),
          'type'  => 'checkbox',
        ),
      )
    );

    // Blog Section
    $args['panels']['theme_option_panel']['sections']['section_blog'] = array(
      'title'    => esc_html__( 'Blog', 'magzimum' ),
      'priority' => 80,
      'fields'   => array(
        'excerpt_length' => array(
          'title'             => esc_html__( 'Excerpt Length (words)', 'magzimum' ),
          'description'       => esc_html__( 'Default is 40 words', 'magzimum' ),
          'type'              => 'number',
          'sanitize_callback' => 'magzimum_sanitize_excerpt_length',
          'input_attrs'       => array(
                                  'min'   => 1,
                                  'max'   => 200,
                                  'style' => 'width: 55px;'
                                ),
        ),
        'read_more_text' => array(
          'title'             => esc_html__( 'Read More Text', 'magzimum' ),
          'type'              => 'text',
          'sanitize_callback' => 'sanitize_text_field',
        ),
        'exclude_categories' => array(
          'title'             => esc_html__( 'Exclude Categories in Blog', 'magzimum' ),
          'description'       => esc_html__( 'Enter category ID to exclude in Blog Page. Separate with comma if more than one', 'magzimum' ),
          'type'              => 'text',
          'sanitize_callback' => 'sanitize_text_field',
        ),
        'author_bio_in_single' => array(
          'title'             => esc_html__( 'Show Author Bio', 'magzimum' ),
          'type'              => 'checkbox',
        ),
         'post_meta_on_blog' => array(
          'title'             => esc_html__( 'Show Post Meta', 'magzimum' ),
          'description'       => esc_html__('This will remove the post meta on post listings ', 'magzimum'),
          'type'              => 'checkbox',
        ),
           'image_on_blog' => array(
          'title'             => esc_html__( 'Show Post Images', 'magzimum' ),
          'description'       => esc_html__('This will remove the post featured image on post listings ', 'magzimum'),
          'type'              => 'checkbox',
        ),
      )
    );

    // Homepage Section
    $args['panels']['theme_option_panel']['sections']['section_home_page'] = array(
      'title'    => esc_html__( 'Home Page', 'magzimum' ),
      'priority' => 65,
      'fields'   => array(
        'show_blog_listing_in_front' => array(
          'title'             => esc_html__( 'Show Blog Listing', 'magzimum' ),
          'description'       => esc_html__( 'Check to show blog listing in home page.', 'magzimum' ),
          'type'              => 'checkbox',
          'sanitize_callback' => 'esc_attr',
        ),
      )
    );

    // Breadcrumb Section
    $args['panels']['theme_option_panel']['sections']['section_breadcrumb'] = array(
      'title'    => esc_html__( 'Breadcrumb', 'magzimum' ),
      'priority' => 80,
      'fields'   => array(
        'breadcrumb_type' => array(
          'title'             => esc_html__( 'Breadcrumb Type', 'magzimum' ),
          'description'       => sprintf( esc_html__( 'Advanced: Requires %1$sBreadcrumb NavXT%2$s plugin', 'magzimum' ), '<a href="'. esc_url ( 'https://wordpress.org/plugins/breadcrumb-navxt/' ) . '" target="_blank">','</a>' ),
          'type'              => 'select',
          'choices'           => magzimum_get_breadcrumb_type_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'breadcrumb_separator' => array(
          'title'       => esc_html__( 'Separator', 'magzimum' ),
          'type'        => 'text',
          'input_attrs' => array('style' => 'width: 55px;'),
        ),
      )
    );

    // Pagination Section
    $args['panels']['theme_option_panel']['sections']['section_pagination'] = array(
      'title'    => esc_html__( 'Pagination', 'magzimum' ),
      'priority' => 70,
      'fields'   => array(
        'pagination_type' => array(
          'title'             => esc_html__( 'Pagination Type', 'magzimum' ),
          'description'       => sprintf( esc_html__( 'Numeric: Requires %1$sWP-PageNavi%2$s plugin', 'magzimum' ), '<a href="' . esc_url ( 'https://wordpress.org/plugins/wp-pagenavi/' ) . '" target="_blank">','</a>' ),
          'type'              => 'select',
          'sanitize_callback' => 'sanitize_key',
          'choices'           => magzimum_get_pagination_type_options(),
        ),
      )
    );

    // Layout Section
    $args['panels']['theme_option_panel']['sections']['section_layout'] = array(
      'title'    => esc_html__( 'Layout', 'magzimum' ),
      'priority' => 70,
      'fields'   => array(
        'site_layout' => array(
          'title'             => esc_html__( 'Site Layout', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_site_layout_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'global_layout' => array(
          'title'             => esc_html__( 'Global Layout', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_global_layout_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'archive_layout' => array(
          'title'             => esc_html__( 'Archive Layout', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_archive_layout_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'single_image' => array(
          'title'             => esc_html__( 'Image in Single Post/Page', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_image_sizes_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
      )
    );





    return $args;
  }

endif;


/**
 * Sanitize excerpt length
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_sanitize_excerpt_length' ) ) :

  function magzimum_sanitize_excerpt_length( $input ) {

    $input = absint( $input );

    if ( $input < 1 ) {
      $input = 40;
    }
    return $input;

  }

endif;
