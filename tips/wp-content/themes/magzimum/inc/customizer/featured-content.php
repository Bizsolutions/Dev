<?php

add_filter( 'magzimum_filter_default_theme_options', 'magzimum_featured_content_default_options' );

/**
 * Featured content defaults.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_featured_content_default_options' ) ):

  function magzimum_featured_content_default_options( $input ){

    $input['featured_content_status']           = 'home-page-only';
    $input['featured_content_show_arrow']       = true;
    $input['featured_content_show_thumbnail']   = true;
    $input['featured_content_show_date']        = true;
    $input['featured_content_show_category']    = false;
    $input['featured_content_enable_autoplay']  = false;
    $input['featured_content_transition_delay'] = 3;
    $input['featured_content_type']             = 'featured-category';
    $input['featured_content_number']           = 6;
    $input['featured_content_category']         = '';

    return $input;
  }

endif;



add_filter( 'magzimum_theme_options_args', 'magzimum_featured_content_theme_options_args' );


/**
 * Add featured content options.
 *
 * @since  Magzimum 1.0
 */

if( ! function_exists( 'magzimum_featured_content_theme_options_args' ) ):

  function magzimum_featured_content_theme_options_args( $args ){

    // Create featured content option panel
    $args['panels']['featured_content_panel']['title'] = esc_html__( 'Featured Content', 'magzimum' );

    // Settings Section
    $args['panels']['featured_content_panel']['sections']['section_content_settings'] = array(
      'title'    => esc_html__( 'Content Settings', 'magzimum' ),
      'priority' => 65,
      'fields'   => array(

        'featured_content_show_arrow' => array(
          'title'             => esc_html__( 'Show Arrow', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_content_show_thumbnail' => array(
          'title'             => esc_html__( 'Show Thumbnail', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_content_show_date' => array(
          'title'             => esc_html__( 'Show Date', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_content_show_category' => array(
          'title'             => esc_html__( 'Show Category', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_content_enable_autoplay' => array(
          'title'             => esc_html__( 'Enable Autoplay', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_content_transition_delay' => array(
          'title'             => esc_html__( 'Transition Delay', 'magzimum' ),
          'description'       => esc_html__( 'In second(s)', 'magzimum' ),
          'type'              => 'number',
          'sanitize_callback' => 'absint',
          'input_attrs'       => array(
                                'min'   => 1,
                                'max'   => 10,
                                'step'  => 1,
                                'style' => 'width: 50px;'
                                ),

        ),


      )
    );

    // Icons Section
    $args['panels']['featured_content_panel']['sections']['section_content_type'] = array(
      'title'    => esc_html__( 'Content Type', 'magzimum' ),
      'priority' => 50,
      'fields'   => array(
        'featured_content_status' => array(
          'title'   => esc_html__( 'Enable Featured Content on', 'magzimum' ),
          'type'    => 'select',
          'choices' => magzimum_get_featured_status_options(),
        ),
        'featured_content_type' => array(
          'title'             => esc_html__( 'Select Content Type', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_featured_content_type_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'featured_content_category' => array(
          'title'             => esc_html__( 'Select Category', 'magzimum' ),
          'type'              => 'dropdown-taxonomies',
          'sanitize_callback' => 'absint',
        ),
        'featured_content_number' => array(
          'title'             => esc_html__( 'No of Posts', 'magzimum' ),
          'type'              => 'number',
          'sanitize_callback' => 'absint',
          'input_attrs'       => array(
                                  'min'   => 1,
                                  'max'   => 20,
                                  'style' => 'width: 55px;'
                                ),

        ),
      )
    );

    return $args;
  }

endif;
