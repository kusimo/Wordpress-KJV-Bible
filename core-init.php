<?php
if ( ! defined( 'WPINC' ) ) {die;} // end if
/*
*  Register CSS 
*/
function bibeli_register_css(){
    wp_enqueue_style('bibeli-css', BIBELI_CORE_CSS . 'bibeli.css',null,time('s'),'all');
    
    };

    add_action( 'wp_enqueue_scripts', 'bibeli_register_css' );    
/*
*  Register JS/Jquery Ready
*/
function bibeli_register_js(){
    wp_enqueue_script('bibeli-js', BIBELI_CORE_JS . 'bibeli.js','jquery',time(),true);

    };
    add_action( 'wp_enqueue_scripts', 'bibeli_register_js' );    


// Register the widget
function bibeli_register_custom_widget() {
	register_widget( 'Bibeli_Widget' );
}
add_action( 'widgets_init', 'bibeli_register_custom_widget' );



