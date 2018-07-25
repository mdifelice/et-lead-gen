<?php
/**
 * Admin related functions.
 *
 * @package Elegant_Themes_Lead_Generator
 */

add_action( 'init', function() {
	register_post_type(
		'customer',
		array(
			'label'   => __( 'Customers', 'et-lead-gen' ),
			'public'  => false,
			'show_ui' => true,
		)
	);
} );
