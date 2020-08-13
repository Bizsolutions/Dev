<?php

add_filter( 'magzimum_filter_default_theme_options', 'magzimum_core_default_options' );

/**
 * Core defaults.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_core_default_options' ) ):

  function magzimum_core_default_options( $input ){

    $input['custom_css']         = '';
    $input['search_placeholder'] = esc_html__( 'Search...', 'magzimum' );
    $input['enable_background']   = 'entire-site';
    return $input;
  }

endif;


add_filter( 'magzimum_theme_options_args', 'magzimum_core_theme_options_args' );


/**
 * Add core options.
 *
 * @since  Magzimum 1.0
 */
if( ! function_exists( 'magzimum_core_theme_options_args' ) ):

  function magzimum_core_theme_options_args( $args ){

    $args['panels']['theme_option_panel']['title'] = esc_html__( 'Themes Options', 'magzimum' );

    if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
    // Create theme option panel

    // Advance Section
    $args['panels']['theme_option_panel']['sections']['section_advanced'] = array(
      'title'    => esc_html__( 'Custom CSS', 'magzimum' ),
      'priority' => 1000,
      'fields'   => array(
        'custom_css' => array(
          'title'                => esc_html__( 'Custom CSS', 'magzimum' ),
          'type'                 => 'textarea',
          'sanitize_callback'    => 'wp_filter_nohtml_kses',
          'sanitize_js_callback' => 'wp_filter_nohtml_kses',
        ),
      )
    );
    }

    // Search Section
    $args['panels']['theme_option_panel']['sections']['section_search'] = array(
      'title'    => esc_html__( 'Search', 'magzimum' ),
      'priority' => 70,
      'fields'   => array(
        'search_placeholder' => array(
          'title'             => esc_html__( 'Search Placeholder', 'magzimum' ),
          'type'              => 'text',
          'sanitize_callback' => 'sanitize_text_field',
        ),
      )
    );

    //Custom Background
    $args['panels']['pre_defined']['sections']['background_image'] = array(
      'fields'   => array(
        'enable_background' => array(
          'title'             => esc_html__( 'Enable Custom Background on', 'magzimum' ),
          'type'              => 'select',
          'choices'           => magzimum_get_custom_background_options(),
          'sanitize_callback' => 'sanitize_key',
        ),
      )
    );
    
    // Social Section
    $args['panels']['theme_option_panel']['sections']['section_social'] = array(
      'title'    => esc_html__( 'Social Icons', 'magzimum' ),
      'priority' => 90,
      'fields'   => array(
        'social_icons' => array(
          'title'       => esc_html__( 'Social Icons', 'magzimum' ),
          'type'        => 'message',
          'description' => esc_html__( 'Please assign social menu.', 'magzimum' ) . ' <a href="' . admin_url( 'customize.php' ).'?autofocus[section]=nav">'.esc_html__( 'Assign now', 'magzimum' ) . '</a>',
        ),
      )
    );

    return $args;
  }

endif;
