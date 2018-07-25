<?php
/**
 * Shortcode definition.
 *
 * @package Elegant_Themes_Lead_Generator
 */

add_shortcode(
	'et_lead_gen_form', function( $shortcode_attributes ) {
		$fields = et_lead_gen_get_fields();
		$html   = '';

		foreach ( $fields as $id => $attributes ) {
			$type                    = isset( $attributes['type'] ) ? $attributes['type'] : 'text';
			$overwritable_attributes = array();

			$overwritable_attributes[] = 'label';
			$overwritable_attributes[] = 'maxlength';

			if ( 'textarea' === $type ) {
				$overwritable_attributes[] = 'cols';
				$overwritable_attributes[] = 'rows';
			}

			foreach ( $overwritable_attributes as $overwritable_attribute ) {
				$name = $id . '_' . $overwritable_attribute;

				if ( isset( $shortcode_attributes[ $name ] ) ) {
					$fields[ $id ][ $overwritable_attribute ] = $shortcode_attributes[ $name ];
				}
			}
		}

		$html = '<form action="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '" method="post" class="et-lead-gen-form">';

		foreach ( $fields as $id => $attributes ) {
			$type         = $attributes['type'];
			$self_closing = false;

			$html_attributes = array(
				'maxlength',
				'required',
				'name',
			);

			switch ( $type ) {
				case 'textarea':
					$tag = 'textarea';

					$html_attributes[] = 'cols';
					$html_attributes[] = 'rows';
					break;
				case 'text':
				case 'number':
				case 'email':
					$tag = 'input';

					$html_attributes[] = 'type';

					$self_closing = true;
					break;
				default:
					$tag = null;
					break;
			}

			if ( $tag ) {
				$label = $attributes['label'];

				$html .= '<label>' . esc_html( $label );

				$input = '<' . $tag;

				foreach ( $html_attributes as $html_attribute ) {
					if ( isset( $attributes[ $html_attribute ] ) ) {
						$input .= ' ' . $html_attribute . '="' . esc_attr( $attributes[ $html_attribute ] ) . '"';
					}
				}

				$name = et_lead_gen_get_field_form_name( $id );

				$input .= ' name="' . esc_attr( $name ) . '"';
				$input .= $self_closing ? ' />' : '></' . $tag . '>';

				$input = wp_kses(
					$input,
					array(
						$tag => array_fill_keys(
							$html_attributes,
							true
						),
					)
				);

				$html .= $input;
				$html .= '</label>';
			}
		}

		$timestamp = et_lead_gen_get_timestamp();

		$html .= '<input type="hidden" name="' . esc_attr( et_lead_gen_get_field_form_name( 'timestamp' ) ) . '" value="' . esc_attr( $timestamp ) . '" />';
		$html .= '<input type="hidden" name="action" value="et_lead_gen_save" />';
		$html .= '<input type="hidden" name="_ajax_nonce" value="' . esc_attr( wp_create_nonce( 'et_lead_gen_save' ) ) . '" />';
		$html .= '<input type="submit" value="' . esc_attr__( 'Submit', 'et-lead-gen' ) . '" />';
		$html .= '</form>';

		wp_enqueue_script( 'et-lead-gen' );

		wp_enqueue_style( 'et-lead-gen' );

		return $html;
	}
);
