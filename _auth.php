<?php

$auth = new Instagram\Auth( $auth_config );

// If a code is present try and get the access token
// otherwise redirect to the Instagram auth page to get the code
if ( isset( $_GET['code'] ) ) {

    try {
        $_SESSION[ 'instagram_access_token' ] = $auth->getAccessToken( $_REQUEST[ 'code' ] );
        header( 'Location: ' . REDIRECT_AFTER_AUTH );
        exit;
    } catch ( \Instagram\Core\ApiException $e ) {
        $error = ucwords( $e->getMessage() );
        require( APP_DIR . '/views/_header.php' );
        require( APP_DIR . '/views/_error.php' );
        require( APP_DIR . '/views/_footer.php' );
        exit;
    }

} else {
    $auth->authorize();
}