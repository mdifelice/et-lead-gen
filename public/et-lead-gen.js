/**
 * JS Handler
 *
 * @package Elegant_Themes
 */

jQuery( document ).ready( function() {
	jQuery( '.et-lead-gen-form' ).submit( function( e ) {
		e.preventDefault();

		var form   = this;
		var submit = jQuery( 'input[type=submit]', form );

		if ( ! submit.attr( 'disabled' ) ) {
			submit.attr( 'disabled', 'disabled' );

			jQuery.ajax(
				{
					url: jQuery( form ).attr( 'action' ),
					complete: function() {
						submit.removeAttr( 'disabled', 'disabled' );
					},
					data: jQuery( form ).serialize(),
					success: function( response ) {
						var message;

						if ( 'number' === typeof response ) {
							message = etLeadGen.i18n.success;

							form.reset();
						} else {
							message = etLeadGen.i18n.error;

							for ( var i in response ) {
								message += '\n' + response[ i ];
							}
						}

						alert( message );
					},
					error: function( response ) {
						alert( etLeadGen.i18n.error + ' ' + etLeadGen.i18n.unknown );
					},
					type: form.method
				}
			);
		}
	} );
} );
