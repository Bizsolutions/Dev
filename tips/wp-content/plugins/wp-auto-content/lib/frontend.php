<?php
function wpautoc_front_styles()
{
    if (is_admin()) return;

    wp_register_script( 'wpautoc_popoverjs', WPAUTOC_URL . '/js/jquery.webui-popover.min.js', array('jquery'), WPAUTOC_VERSION);
    wp_enqueue_script( 'wpautoc_popoverjs');
    wp_register_script( 'wpautoc_shareit', WPAUTOC_URL . '/js/shareIt.js', array('jquery'), WPAUTOC_VERSION);
    wp_enqueue_script( 'wpautoc_shareit');
    // wp_register_script( 'wpautoc_gplus', 'https://apis.google.com/js/platform.js', array('jquery'), WPAUTOC_VERSION);
    // wp_register_script( 'wpautoc_gplus', 'https://apis.google.com/js/plusone.js', array('jquery'), WPAUTOC_VERSION);
    // wp_enqueue_script( 'wpautoc_gplus');
    wp_register_script( 'wpautoc-jquery-modalfr',
            WPAUTOC_URL . '/js/jquery.modal.js',
            array( 'jquery' ), WPAUTOC_VERSION );
    wp_enqueue_script( 'wpautoc-jquery-modalfr');
    wp_register_script( 'wpautoc_frontjs', WPAUTOC_URL . '/js/wpautoc-front.js', array('jquery'), WPAUTOC_VERSION);
    $script_vars = array(
    	'ajax_url' => admin_url ( 'admin-ajax.php' )
    );
	wp_localize_script( 'wpautoc_frontjs', 'wpautocf_vars', $script_vars );
    wp_enqueue_script( 'wpautoc_frontjs');
    wp_register_style('wpautoc_popover', WPAUTOC_URL . '/css/jquery.webui-popover.min.css');
    wp_enqueue_style( 'wpautoc_popover' );
    wp_register_style('wpautoc_front', WPAUTOC_URL . '/css/wpautoc-front.css');
    wp_enqueue_style( 'wpautoc_front' );
}
add_action( 'wp_enqueue_scripts', 'wpautoc_front_styles' );

// Add Pinterest Script for pins
add_action( 'wp_head', 'wpautoc_pinterest_script', 99 );
function wpautoc_pinterest_script() {
    echo '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
    echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
}

?>