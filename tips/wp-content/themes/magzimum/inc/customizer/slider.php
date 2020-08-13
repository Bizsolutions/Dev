<?php

add_filter( 'magzimum_filter_default_theme_options', 'magzimum_slider_default_options' );

/**
 * Slider defaults.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_slider_default_options' ) ):

  function magzimum_slider_default_options( $input ){

    $input['featured_slider_status']                 = 'disabled';
    $input['featured_slider_transition_effect']      = 'fadeout';
    $input['featured_slider_transition_delay']       = 3;
    $input['featured_slider_transition_duration']    = 1;
    $input['featured_slider_enable_caption']         = true;
    $input['featured_slider_enable_arrow']           = false;
    $input['featured_slider_enable_pager']           = true;
    $input['featured_slider_enable_autoplay']        = true;
    $input['featured_slider_enable_clickable_image'] = false;
    $input['featured_slider_type']                   = 'featured-category';
    $input['featured_slider_number']                 = 3;
    $input['featured_slider_category']               = '';

    return $input;
  }

endif;



add_filter( 'magzimum_theme_options_args', 'magzimum_slider_theme_options_args' );


/**
 * Add featured slider options.
 *
 * @since  Magzimum 1.0
 */

if( ! function_exists( 'magzimum_slider_theme_options_args' ) ):

  function magzimum_slider_theme_options_args( $args ){

    // Create featured slider option panel
    $args['panels']['featured_slider_panel']['title'] = esc_html__( 'Featured Slider Section', 'magzimum' );

    // Settings Section
    $args['panels']['featured_slider_panel']['sections']['section_slider_settings'] = array(
      'title'    => esc_html__( 'Slider Settings', 'magzimum' ),
      'priority' => 75,
      'fields'   => array(
        'featured_slider_transition_effect' => array(
          'title'   => esc_html__( 'Transition Effect', 'magzimum' ),
          'type'    => 'select',
          'choices' => magzimum_get_featured_slider_transition_effects(),
        ),

        'featured_slider_transition_delay' => array(
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
        'featured_slider_transition_duration' => array(
          'title'             => esc_html__( 'Transition Duration', 'magzimum' ),
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
        'featured_slider_enable_caption' => array(
          'title'             => esc_html__( 'Enable Caption', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_slider_enable_arrow' => array(
          'title'             => esc_html__( 'Enable Arrow', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_slider_enable_pager' => array(
          'title'             => esc_html__( 'Enable Pager', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_slider_enable_autoplay' => array(
          'title'             => esc_html__( 'Enable Autoplay', 'magzimum' ),
          'type'              => 'checkbox',
        ),
        'featured_slider_enable_clickable_image' => array(
          'title'             => esc_html__( 'Enable Link in Image', 'magzimum' ),
          'type'              => 'checkbox',
        ),

      )
    );

    // Icons Section
    $args['panels']['featured_slider_panel']['sections']['section_slider_type'] = array(
      'title'    => esc_html__( 'Slider Type', 'magzimum' ),
      'priority' => 70,
      'fields'   => array(
        'featured_slider_type' => array(
          'title'             => esc_html__( 'Select Slider Type', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_featured_slider_type(),
          'sanitize_callback' => 'sanitize_key',
        ),
        'featured_slider_category' => array(
          'title'             => esc_html__( 'Select Category', 'magzimum' ),
          'type'              => 'dropdown-taxonomies',
          'sanitize_callback' => 'absint',
        ),
        'featured_slider_number' => array(
          'title'             => esc_html__( 'No of Slides', 'magzimum' ),
          'type'              => 'number',
          'default'           => 3,
          'sanitize_callback' => 'absint',
          'input_attrs'       => array(
                                  'min'   => 1,
                                  'max'   => 20,
                                  'style' => 'width: 55px;'
                                ),

        ),
      )
    );

    // Slider Section
    $args['panels']['featured_slider_panel']['sections']['section_slider_common'] = array(
      'title'    => esc_html__( 'Slider Section', 'magzimum' ),
      'priority' => 60,
      'fields'   => array(
        'featured_slider_status' => array(
          'title'   => esc_html__( 'Enable Slider Section on', 'magzimum' ),
          'type'    => 'select',
          'choices' => magzimum_get_featured_status_options(),
        ),
      )
    );

    return $args;
  }

endif;
