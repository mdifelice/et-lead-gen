<?php
/**
 * Generic functions.
 *
 * @package Elegant_Themes
 */

/**
 * Returns current timestamp from web service.
 *
 * @return int Current timestamp or NULL on error.
 */
function et_lead_gen_get_timestamp() {
	$timestamp = null;
	$response  = wp_remote_get( 'http://www.convert-unix-time.com/api?timestamp=now' );

	if ( ! is_wp_error( $response ) ) {
		$body = wp_remote_retrieve_body( $response );

		$decoded_body = json_decode( $body );

		if ( $decoded_body ) {
			$timestamp = $decoded_body->timestamp;
		}
	}

	return $timestamp;
}

/**
 * Returns a field form name based on its id.
 *
 * @param string $id Field ID.
 *
 * @return string Field form name.
 */
function et_lead_gen_get_field_form_name( $id ) {
	return 'et_lead_gen_field_' . $id;
}

/**
 * Returns the list of available fields with its default attributes.
 *
 * @return array List of fields.
 */
function et_lead_gen_get_fields() {
	$fields = array(
		'name'    => array(
			'label'    => __( 'Name', 'et-lead-gen' ),
			'type'     => 'text',
			'required' => 'required',
		),
		'phone'   => array(
			'label'    => __( 'Phone Number', 'et-lead-gen' ),
			'type'     => 'text',
			'required' => 'required',
		),
		'email'   => array(
			'label'    => __( 'Email Address', 'et-lead-gen' ),
			'type'     => 'email',
			'required' => 'required',
		),
		'budget'  => array(
			'label'    => __( 'Desired Budget', 'et-lead-gen' ),
			'type'     => 'number',
			'required' => 'required',
		),
		'message' => array(
			'label' => __( 'Message', 'et-lead-gen' ),
			'type'  => 'textarea',
		),
	);

	return $fields;
}
