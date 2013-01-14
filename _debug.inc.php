<?php

define( 'LOGFILENAME', __DIR__ . '/_application.log' );

//
//	Write a status message in the _application.log
//
function logWrite( $logMsg, $shouldReallyWrite = true ) {

	// Let's make sure the file exists and is writable first.
	if( is_writable( LOGFILENAME ) && $shouldReallyWrite ) {

	    // We're opening LOGFILENAME in append mode.
	    // The file pointer is at the bottom of the file hence
	    // that's where $logMsg will go when we fwrite() it.
	    if( $handle = fopen( LOGFILENAME, 'a' ) ) {

		    // Write $somecontent to our opened file.
		    if( fwrite( $handle, date( 'Y-m-d H:i:s.u ==> ' ) . $logMsg . "\n" ) ) {
			    fclose( $handle );
			    return true;
		    }

	        fclose($handle);

	    }

	}

	return false;

}

?>