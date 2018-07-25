<?php
/**
 * AJAX related functionality.
 *
 * @package Elegant_Themes
 */

/**
 * AJAX generic handler.
 */
function et_lead_gen_handle_ajax() {
	check_ajax_referer( 'et_lead_gen_save' );

	$fields = et_lead_gen_get_fields();
	$errors = array();
	$meta   = array();

	foreach ( $fields as $id => $attributes ) {
		$name     = et_lead_gen_get_field_form_name( $id );
		$required = ! empty( $attributes['required'] );
		$type     = isset( $attributes['type'] ) ? $attributes['type'] : null;
		$value    = '';

		if ( isset( $_POST[ $name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $name ] ) );
		}

		if ( $required && empty( $value ) ) {
			$errors[] = sprintf( __( 'Missing field %s.', 'et-lead-gen' ), $id );
		} else {
			if ( 'email' === $type && ! is_email( $value ) ) {
				$errors[] = sprintf( __( 'Invalid email address in field %s.', 'et-lead-gen' ), $id );
			}

			if ( 'number' === $type && ! is_numeric( $value ) ) {
				$errors[] = sprintf( __( 'Field %s must be numeric.', 'et-lead-gen' ), $id );
			}
		}

		$meta[ $id ] = $value;
	}

	$timestamp_field = et_lead_gen_get_field_form_name( 'timestamp' );

	if ( isset( $_POST[ $timestamp_field ] ) ) {
		$timestamp = absint( wp_unslash( $_POST[ $timestamp_field ] ) );

		$meta['timestamp'] = $timestamp;
	}

	if ( ! $timestamp ) {
		$errors[] = __( 'Missing timestamp field.', 'et-lead-gen' );
	}

	if ( empty( $errors ) ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $meta['name'] . ' @ ' . date_i18n( 'Y-m-d H:i:s', $timestamp ),
				'post_content' => $meta['message'],
				'post_type'    => 'customer',
				'post_status'  => 'publish',
				'meta_input'   => $meta,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$errors[] = $post_id->get_error_message();
		}

		$response = $post_id;
	}

	if ( ! empty( $errors ) ) {
		$response = $errors;
	}

	wp_send_json( $response );
}

add_action( 'wp_ajax_et_lead_gen_save', 'et_lead_gen_handle_ajax' );
add_action( 'wp_ajax_nopriv_et_lead_gen_save', 'et_lead_gen_handle_ajax' );
