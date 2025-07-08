<?php
// Funciones del child theme
function shoptimizer_child_enqueue_styles() {
    wp_enqueue_style( 'shoptimizer-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'shoptimizer-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('shoptimizer-style'),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'shoptimizer_child_enqueue_styles' );
?>