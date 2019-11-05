<?php

/*
Plugin Name: 	Salcodes
Version: 		1.0
Description: 	Output the current year in your WordPress site.
Author: 		Salman Ravoof
Author URI: 	https://www.salmanravoof.com/
License:		GPLv2 or later
License URI:	https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 	salcodes
*/

/** Registering the Shortcodes only after WordPress has finished loading completely ('init' hook) */

function salcodes_init(){
	add_shortcode( 'current_year', 'salcodes_year' );
	add_shortcode( 'cta_button', 'salcodes_cta' );
	add_shortcode( 'boxed', 'salcodes_boxed' );
}

add_action('init', 'salcodes_init');

/**
 * [current_year] returns the Current Year as a 4-digit string.
 * @return string Current Year
*/

function salcodes_year() {
		return getdate()['year'];
}

/**
 * [cta_button] returns HTML code for a CTA Button.
 * @return string Button HTML Code
*/

function salcodes_cta( $atts ) {
	$url = home_url();
	$a = shortcode_atts( array(
		'link' 		=> $url,
		'id' 		=> 'salcodes',
		'color' 	=> 'blue',
		'size' 		=> '',
		'label' 	=> 'Button',
		'target' 	=> '_self'
	), $atts );
	$output = '<p><a href="' . esc_url( $a['link'] ) . '" id="' . esc_attr( $a['id'] ) . '" class="button ' . esc_attr( $a['color'] ) . ' ' . esc_attr( $a['size'] ) . '" target="' . esc_attr($a['target']) . '">' . esc_attr( $a['label'] ) . '</a></p>';
	return $output;
}

/**
 * [boxed] returns HTML code for a content box with colored titles.
 * @return string HTML code for boxed content. 
*/

function salcodes_boxed( $atts, $content = null, $tag = '' ) {
	$a = shortcode_atts( array(
		'title' 		=> 'Title',
		'title_color'	=> 'white',
		'color'			=> 'blue',
	), $atts );
	
	$output = '<div class="salcodes-boxed" style="border:2px solid ' . esc_attr( $a['color'] ) . ';">'.'<div class="salcodes-boxed-title" style="background-color:' . esc_attr( $a['color'] ) . ';"><h3 style="color:' . esc_attr( $a['title_color'] ) . ';">' . esc_attr( $a['title'] ) . '</h3></div>'.'<div class="salcodes-boxed-content"><p>' . esc_attr( $content ) . '</p></div>'.'</div>';
	
	return $output;
}

/** Enqueuing the Stylesheet for Salcodes */

function salcodes_enqueue_scripts() {
	global $post;
	$has_shortcode = has_shortcode( $post->post_content, 'cta_button' ) || has_shortcode( $post->post_content, 'boxed' );
	if( is_a( $post, 'WP_Post' ) && $has_shortcode ) {
		wp_register_style( 'salcodes-stylesheet',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
    		wp_enqueue_style( 'salcodes-stylesheet' );
	}
}
add_action( 'wp_enqueue_scripts', 'salcodes_enqueue_scripts');

?>

