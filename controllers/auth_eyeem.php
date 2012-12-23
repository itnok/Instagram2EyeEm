<?php

// If a code is present try and get the access token
// otherwise redirect to the Instagram auth page to get the code
if ( isset( $_REQUEST[ 'code' ] ) ) {

    try {
        $token = $eyeem->getToken( $_REQUEST[ 'code' ] );
        $_SESSION[ 'eyeem_access_token' ] = $token;
        $eyeem->setAccessToken( $token[ 'access_token' ] );
        header( 'Location: ' . REDIRECT . 'migrate' );
        exit;
    } catch ( Exception $e ) {
        $error = 'EyeEm - ' . ucwords( $e->getMessage() );
        require( APP_DIR . '/views/_header.php' );
        require( APP_DIR . '/views/_error.php' );
        require( APP_DIR . '/views/_footer.php' );
        exit;
    }

} else {
	header( 'Location: ' . $eyeem->getLoginUrl() );
    exit;
}