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
//	Display an error message if something go wrong during the process
//
var showErrorMsg = function( errMsg ) {
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
    					.html( errMsg )
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

//
//	Display an error message if something go wrong during the process
//
var updateInstagramProgress = function( statusMsg ) {
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
						.html( statusMsg )
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
}

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
			data    : { max_id: d[ 'max_id' ], user: d[ 'username' ] },
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
					updateInstagramProgress( 'Migrating your ' + d[ 'media' ].length + ' photos to EyeEm...' );
					
					//	Let's start the import process in EyeEm...
					setTimeout( migrateToEyeEm, 500 );
				} else {
					//	We have not finished yet! Let's set timeout for the next AJAX request
					setTimeout( loadInstagramData, 5 );
				}

			//	END of SUCCESS Callback
			},
			error : function( data ) {
				showErrorMsg( 
					'An error occurred during the loading of your Instagram portfolio!<br />' +
					'<br />' +
					'Please reload the page to restart the process...<br />' +
					'<br />' +
					'<a class="btn btn-large btn-success" href="migrate">Restart Migration</a>' );
			//	END of ERROR Callback
			}
		} );
	} else {
		//	Kill the Zebra!
		//	Sometimes there are Instagram profiles that state they are composed by X images
		//	BUT IT'S NOT TRUE! They are composed by X-n images (n = 1 in my own Zebra)
		//
		//	This is unlucky, unpleasant and untoward... We MUST handle it!
		if( d[ 'max_id' ] == null &&
			d[ 'media' ].length > 0 &&
			d[ 'media' ].length < d[ 'bucket_size' ] ) {

			var howManyZebras = d[ 'bucket_size' ] - d[ 'media' ].length;
			
			//	Set the new value for Bucket Size
			d[ 'bucket_size' ] = d[ 'media' ].length;
			//	Also EyeEm Bucket Size MUST BE corrected accordingly
			var e = $.data( document.body, 'eyeem' );
			e[ 'bucket_size' ] = d[ 'media' ].length;

			//	Update data
			$.data( document.body, 'instagram', d );
			$.data( document.body, 'eyeem',     e );

			var percent = 100 * d[ 'media' ].length / d[ 'bucket_size' ];
			$( '#progress .bar' ).css( 'width', percent + '%' );
			
			if( percent == 100 ) {
				//	Hide the Bootstrap progress bar if the process reaches 100%
				//	Hide the status text, substitute it and make it visible again
				//	Reset and Show back the progress bar 
				updateInstagramProgress( 'Migrating only ' + d[ 'media' ].length + ' photos to EyeEm...' +
										'<br />' +
										'<span class="smaller">There '   + ( howManyZebras > 1 ? 'are ' : 'is ' ) +
										howManyZebras + ' missing photo' + ( howManyZebras > 1 ? 's'    : '' ) +
										' (not readable from Instagram)</span>' );
				
				//	Let's start the import process in EyeEm...
				setTimeout( migrateToEyeEm, 500 );
			}
		//	Kill the Zebra!
		//	Sometimes there are odd Instagram profiles that state they are composed by X images
		//	BUT IT'S NOT TRUE! They are composed by X+n images (n = 1 in my own Zebra)
		//
		//	This is unlucky, unpleasant and untoward... We also MUST handle this case!
		} else if( d[ 'max_id' ] == null &&
				   d[ 'media' ].length > 0 &&
				   d[ 'media' ].length > d[ 'bucket_size' ] ) {
			
			var howManyZebras = d[ 'media' ].length - d[ 'bucket_size' ];
			
			//	Set the new value for Bucket Size
			d[ 'bucket_size' ] = d[ 'media' ].length;
			//	Also EyeEm Bucket Size MUST BE corrected accordingly
			var e = $.data( document.body, 'eyeem' );
			e[ 'bucket_size' ] = d[ 'media' ].length;

			//	Update data
			$.data( document.body, 'instagram', d );
			$.data( document.body, 'eyeem',     e );

			var percent = 100 * d[ 'media' ].length / d[ 'bucket_size' ];
			$( '#progress .bar' ).css( 'width', percent + '%' );
			
			if( percent == 100 ) {
				//	Hide the Bootstrap progress bar if the process reaches 100%
				//	Hide the status text, substitute it and make it visible again
				//	Reset and Show back the progress bar 
				updateInstagramProgress( 'Migrating ' + d[ 'media' ].length + ' photos to EyeEm...' +
										 '<br />' +
										 '<span class="smaller">There '   + ( howManyZebras > 1 ? 'are ' : 'is ' ) +
										 howManyZebras + ' more photo' + ( howManyZebras > 1 ? 's'    : '' ) +
										 ' (Instagram made a mistake counting your photos!)</span>' );

				//	Let's start the import process in EyeEm...
				setTimeout( migrateToEyeEm, 500 );
			}
		}
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
	 ) {

		var nextPic = d[ 'bucket_size' ] - e[ 'media' ].length - 1;

		$.ajax( {
			url     : 'upload-eyeem-pic',
			type    : 'POST',
			data    : { 
				photo     : d[ 'media' ][ nextPic ][ 'image' ][ 'std' ][ 'url' ],
				title     : d[ 'media' ][ nextPic ][ 'caption' ],
				topic     : d[ 'media' ][ nextPic ][ 'tags' ].join( ',' ),
				loc_id    : d[ 'media' ][ nextPic ][ 'location' ][ 'id' ],
				loc_lat   : d[ 'media' ][ nextPic ][ 'location' ][ 'lat' ],
				loc_lon   : d[ 'media' ][ nextPic ][ 'location' ][ 'lon' ],
				loc_name  : d[ 'media' ][ nextPic ][ 'location' ][ 'name' ],
				igers_id  : d[ 'media' ][ nextPic ][ 'id' ],
				created   : d[ 'media' ][ nextPic ][ 'created' ],
				igers_user: d[ 'userid' ]
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
				
				if( percent == 100 ) {
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
									//	Calculate how many dupes were skipped
									var howManyDupes = 0;
									
									for( var i in e[ 'media' ] ) {
										howManyDupes += e[ 'media' ][ i ].duplicate;
									}

									//	Let' show the results!
									$( this )
										.find( 'h2' )
										.html(
											'The migration of your ' + d[ 'media' ].length + ' photos to EyeEm completed successfully!' +
											( howManyDupes
												?	'<br />' +
													'<span class="smaller">' + howManyDupes + ' out of ' + d[ 'media' ].length +
													( howManyDupes > 1 ? ' photos were ' : 'photo was ' ) + 'not imported' +
													'<br />' +
													'becuase ' + ( howManyDupes > 1 ? 'they have ' : 'it has ' ) + 
													'already been migrated by a previous attempt</span>'
												:	''
											)
										)
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
									        				
										        				$( '.donation' ).slideDown( 750 );
										        			} )
										    		)
										    );
								} )
						} );
					

				} else {
					//	We have not finished yet! Let's set timeout for the next AJAX request
					setTimeout( migrateToEyeEm, 5 );
				}

			//	END of SUCCESS Callback
			},
			error: function() {
				showErrorMsg(
					'An error occurred during the migration of the photo ' +
					e[ 'media' ].length +
					' of ' +
					e[ 'bucket_size' ] + '!'
				);

			//	END of ERROR Callback
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
	$.data( document.body, 'instagram', { 'media':[], 'error':null, 'max_id':null, 'bucket_size':$( '#progress' ).data( 'bucket_size' ), 'username': $( '#progress' ).data( 'username' ), 'userid': $( '#progress' ).data( 'userid' ) } );

	//	Initialize EyeEm data array
	$.data( document.body, 'eyeem', { 'media':[], 'error':null, 'max_id':null, 'bucket_size':$( '#progress' ).data( 'bucket_size' )  } );

	//	Let's hide the donation DIV just until the end of the process!
	$( '.donation' ).slideUp( 1 );

	//	Wait 500ms then execute "loadInstagramData"
	setTimeout( loadInstagramData, 500 );

} );