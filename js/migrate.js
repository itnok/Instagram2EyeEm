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

//
//	Load data about Instagram User's pictures
//
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
				
				if( percent == 100 ) {
					//	Hide the Bootstrap progress bar if the process reaches 100%
					//	Hide the status text, substitute it and make it visible again
					//	Reset and Show back the progress bar 
					$( '#progress' )
						.delay( 500 )
						//	Hide Progressbar
						.find( '.progress' )
						.fadeOut( 500, function() {
							$( this )
								//	Modify Status Text
								.parent()
								.find( '.text' )
								.fadeOut( 500, function() {
									$( this )
										.find( 'h2' )
										.html( 'Migrating your ' + d[ 'media' ].length + ' photos to EyeEm...' )
										.parent()
										.fadeIn( 500 )
								} )
								//	Show Progressbar
								.parent()
								.find( '.progress .bar' )
								.css( 'width', '0%' )
								.parent()
								.fadeIn( 500 )
							;
						} );
					
					//	Let's start the import process in EyeEm...
					setTimeout( migrateToEyeEm, 500 );
				} else {
					//	We have not finished yet! Let's set timeout for the next AJAX request
					setTimeout( loadInstagramData, 5 );
				}

			}
		} );
	}
	
}

//
//	Load data about Instagram User's pictures
//
var migrateToEyeEm = function() {
	var d = $.data( document.body, 'instagram' );
	var e = $.data( document.body, 'eyeem' );

	if( d[ 'bucket_size' ] > 0
	 && d[ 'bucket_size' ] > e[ 'media' ].length
//	 && e[ 'media' ].length < 5
	 ) {

		var nextPic = d[ 'bucket_size' ] - e[ 'media' ].length - 1;

		$.ajax( {
			url     : 'upload-eyeem-pic',
			type    : 'POST',
			data    : { 
				photo   : d[ 'media' ][ nextPic ][ 'image' ][ 'std' ][ 'url' ],
				title   : d[ 'media' ][ nextPic ][ 'caption' ],
				topic   : d[ 'media' ][ nextPic ][ 'tags' ].join( ',' ),
				loc_id  : d[ 'media' ][ nextPic ][ 'location' ][ 'id' ],
				loc_lat : d[ 'media' ][ nextPic ][ 'location' ][ 'lat' ],
				loc_lon : d[ 'media' ][ nextPic ][ 'location' ][ 'lon' ],
				loc_name: d[ 'media' ][ nextPic ][ 'location' ][ 'name' ]
			},
			cache   : false,
			dataType: 'json',
			success : function( data ) {
				d[ 'error' ]  = data[ 'error' ];
				d[ 'max_id' ] = data[ 'max_id' ];
				
				for( var pic in data[ 'media' ] ) {
					e[ 'media' ].push( data[ 'media' ][ pic ] );
				}
				
				//	Update data
				$.data( document.body, 'eyeem', e );

				var percent = 100 * e[ 'media' ].length / e[ 'bucket_size' ];
				$( '#progress .bar' ).css( 'width', percent + '%' );
				
				if( percent == 100 /* || e[ 'media' ].length == 5 */ ) {
					//	Stop the big spinner
					$( '#progress .spin' ).data( 'spinner' ).stop();
					$( '#progress .spin' ).remove();

					//	Hide the Bootstrap progress bar if the process reaches 100%
					//	Hide the status text, substitute it and make it visible again
					//	Reset and Keep hidden the progress bar 
					$( '#progress' )
						//	Hide Progressbar
						.find( '.progress' )
						.fadeOut( 500, function() {
							$( this )
								//	Modify Status Text
								.parent()
								.find( '.text' )
								.fadeOut( 500, function() {
									$( this )
										.find( 'h2' )
										.html( 'Migration to EyeEm of your ' + d[ 'media' ].length + ' photos completed successfully!' )
										//	Show the checkmark
										.closest( '#progress' )
										    .prepend(
										    	$( '<div class="checkmark" />' )
										    		.append(
										    			$( '<img src="img/iconmonstr-check-mark-11-icon.png" />' )
										        			.load( function() {
										        				//	The image is fully loaded!
										        				$( this )
										        					.animate(
										        						{
										        							opacity: 1.0
										        						}, 1250
										        					)
										        					//	Now let's show back the status text message
										        					.closest( '#progress' )
										        					.find( '.text' )
										        					.fadeIn( 750 )
										        				;
										        			} )
										    		)
										    );
								} )
						} );
					

				} else {
					//	We have not finished yet! Let's set timeout for the next AJAX request
					setTimeout( migrateToEyeEm, 5 );
				}

			},
			error: function() {
				//	Hide the Bootstrap progress bar if the process reaches 100%
				//	Hide the status text, substitute it and make it visible again
				//	Reset and Keep hidden the progress bar 
				$( '#progress' )
					//	Hide Progressbar
					.find( '.progress' )
					.fadeOut( 500, function() {
						$( this )
							//	Modify Status Text
							.parent()
							.find( '.text' )
							.fadeOut( 500, function() {
								$( this )
									.find( 'h2' )
									.html(
										'An error occurred during the migration of the photo ' +
										d[ 'media' ].length +
										' of ' +
										e[ 'bucket_size' ] + '!'
									)
									//	Show the checkmark
									.closest( '#progress' )
									    .prepend(
									    	$( '<div class="checkmark" />' )
									    		.append(
									    			$( '<img src="img/iconmonstr-x-mark-4-icon.png" />' )
									        			.load( function() {
									        				//	The image is fully loaded!
									        				$( this )
									        					.animate(
									        						{
									        							opacity: 1.0
									        						}, 1250
									        					)
									        					//	Now let's show back the status text message
									        					.closest( '#progress' )
									        					.find( '.text' )
									        					.fadeIn( 750 )
									        				;
									        			} )
									    		)
									    );
							} )
					} );
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

	//	Initialize EyeEm data array
	$.data( document.body, 'eyeem', { 'media':[], 'error':null, 'max_id':null, 'bucket_size':$( '#progress' ).data( 'bucket_size' )  } );

	//	Wait 500ms then execute "loadInstagramData"
	setTimeout( loadInstagramData, 500 );

} );