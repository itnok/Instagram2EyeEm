$( function() {

	//	Let's hide the donation DIV
	$( '.donation' ).slideUp( 1 );

	$( 'form .btn' ).click( function() {
		var msg = $( this ).prev( 'input' ).val();

		if( msg.length ) {
			//	Send the feedback email
			$.post(
				'send-feedback',
				{
					message: msg
				},
				function( data ) {
					$( ".form-inline" )
						.slideUp( 'slow', function() {
							$( ".form-inline" ).before( '<h3>Success</h3><p>Your feedback was sent.</p>');
						} );
				}
			);
		}
	} );

} );