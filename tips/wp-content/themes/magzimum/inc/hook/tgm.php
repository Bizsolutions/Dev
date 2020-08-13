<?php

add_action( 'tgmpa_register', 'magzimum_recommended_plugins' );

if( ! function_exists( 'magzimum_recommended_plugins' ) ) :

  /**
   * Recommended plugins
   *
   * @since  Magzimum 1.0
   */
  function magzimum_recommended_plugins(){

    $plugins = array(
      array(
        'name'     => esc_html__( 'WP-PageNavi', 'magzimum' ),
        'slug'     => 'wp-pagenavi',
        'required' => false,
      ),
      array(
        'name'     => esc_html__( 'Breadcrumb NavXT', 'magzimum' ),
        'slug'     => 'breadcrumb-navxt',
        'required' => false,
      ),
    );
    $config = array(
      'dismissable' => true,
    );
    tgmpa( $plugins, $config );

  }

endif;
