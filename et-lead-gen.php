<?php
/**
 * Lead Generator plugin for Elegant Themes Test Project
 *
 * Plugin Name: Lead Generator
 * Author:      Martín Di Felice
 * Version:     1.0
 *
 * @package Elegant_Themes_Lead_Generator
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . 'admin.php';
require_once plugin_dir_path( __FILE__ ) . 'ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'functions.php';
require_once plugin_dir_path( __FILE__ ) . 'shortcode.php';

add_action( 'wp_enqueue_scripts', function() {
	wp_register_script(
		'et-lead-gen',
		plugins_url( 'public/et-lead-gen.js', __FILE__ ),
		array( 'jquery' ),
		false,
		true
	);

	wp_localize_script(
		'et-lead-gen',
		'etLeadGen',
		array(
			'i18n' => array(
				'success' => __( 'Thanks for your submission.', 'et-lead-gen' ),
				'error'   => __( 'Your submission cannot be processed:', 'et-lead-gen' ),
				'unknown' => __( 'Unknown error, please try again later.', 'et-lead-gen' ),
			),
		)
	);

	wp_register_style(
		'et-lead-gen',
		plugins_url( 'public/et-lead-gen.css', __FILE__ )
	);
} );

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'et-lead-gen', false, basename( __DIR__ ) . '/languages' );
} );
