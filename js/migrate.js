$.fn.spin = function( opts ) {
	this.each( function() {
    	var $this = $( this ),
	        data  = $this.data();

	    if( data.spinner ) {
	    	data.spinner.stop();
	    	delete data.spinner;
	    }
    
	    if( opts !== false ) {
		    data.spinner = new Spinner( $.extend( { color: $this.css( 'color' ) }, opts ) ).spin( this );
        }
    } );
  return this;
};

var loadInstagramData = function() {
	var d = $.data( document.body, 'instagram' );

	if( d[ 'bucket_size' ] > 0
	 && d[ 'bucket_size' ] > d[ 'media' ].length
	 && ( d[ 'max_id' ] != null || d[ 'media' ].length == 0 ) ) {
		$.ajax( {
			url     : 'get-instagram-pics',
			type    : 'POST',
			data    : { max_id: d[ 'max_id' ] },
			cache   : false,
			dataType: 'json',
			success : function( data ) {
				d[ 'error' ]  = data[ 'error' ];
				d[ 'max_id' ] = data[ 'max_id' ];
				
				for( var pic in data[ 'media' ] ) {
					d[ 'media' ].push( data[ 'media' ][ pic ] );
				}
				
				//	Update data
				$.data( document.body, 'instagram', d );

				var percent = 100 * d[ 'media' ].length / d[ 'bucket_size' ];
				$( '#progress .bar' ).css( 'width', percent + '%' );
				
				//	Hide the Bootstrap progress bar if the process reaches 100%
				if( percent == 100 ) {
					$( '#progress .progress' ).delay( 500 ).fadeOut( 500 );
				}

				//	Set timeout for the next AJAX request
				setTimeout( loadInstagramData, 5 );
			}
		} );
	}
	
}

$( function() {

	var spinOptions = {
		lines    : 13,			// The number of lines to draw
		length   : 21,			// The length of each line
		width    : 12,			// The line thickness
		radius   : 32,			// The radius of the inner circle
		corners  : 1,			// Corner roundness (0..1)
		rotate   : 0,			// The rotation offset
		color    : '#000',		// #rgb or #rrggbb
		speed    : 1,			// Rounds per second
		trail    : 60,			// Afterglow percentage
		shadow   : true,		// Whether to render a shadow
		hwaccel  : true,		// Whether to use hardware acceleration
		className: 'spinner',	// The CSS class to assign to the spinner
		zIndex   : 2e9,			// The z-index (defaults to 2000000000)
		top      : 'auto',		// Top position relative to parent in px
		left     : 'auto'		// Left position relative to parent in px
	};

	$( '#progress .spin' ).spin( spinOptions );

	//	Initialize Instagram data array
	$.data( document.body, 'instagram', { 'media':[], 'error':null, 'max_id':null, 'bucket_size':$( '#progress' ).data( 'bucket_size' )  } );

	//	Wait 500ms then execute "loadInstagramData
	setTimeout( loadInstagramData, 500 );

} );