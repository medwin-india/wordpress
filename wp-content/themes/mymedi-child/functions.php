<?php 
function mymedi_child_register_scripts(){
    $parent_style = 'mymedi-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array('mymedi-reset'), mymedi_get_theme_version() );
    wp_enqueue_style( 'mymedi-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'mymedi_child_register_scripts' );