<?php

define( 'GITHUB_URL',           'https://github.com/galen/PHP-Instagram-API/blob/master/Examples/' );
define( 'APP_DIR',         		__DIR__ );
define( 'REDIRECT_AFTER_AUTH',  http://dev.itnok.com/igers2eye/' );

// Turn on error reporting
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

// Start the session
session_start();

// Authorization configuration
$auth_config = array(
    'client_id'         => 'e341350c776a4848b8ab92f37009f4fc',
    'client_secret'     => '78afef998efe47a5930fe93b96b32def',
    'redirect_uri'      => REDIRECT_AFTER_AUTH,
    'scope'             => array( 'likes', 'comments', 'relationships' )
);

// Start authorization if an access token session isnt present
if ( ! isset( $_SESSION[ 'instagram_access_token' ] ) ) {
    require( APP_DIR . '/_auth.php' );
    exit;
}

// If an example has been chosen, include it and exit
if ( isset( $_GET['example'] ) ) {
    try {
        date_default_timezone_set('America/Los_Angeles');
        require( EXAMPLES_DIR . '/_SplClassLoader.php' );
        $loader = new SplClassLoader( 'Instagram', dirname( APP_DIR ) . '/api/instagram/Instagram' );
        $loader->register();
        require( EXAMPLES_DIR . '/' . $_GET['example'] );
        exit;
    }
    /**
     * Authorization Exception thrown
     * Clear the session and redirect to the auth page
     */
    catch ( \Instagram\Core\ApiAuthException $e ) {
        unset( $_SESSION );
        session_destroy();
        header( 'Location: ' . $auth_config['redirect_uri'] );
        exit;
    }
    catch ( \Instagram\Core\ApiException $e ) {
        $error = ucwords( $e->getMessage() );
        require( APP_DIR . '/views/_header.php' );
        require( APP_DIR . '/views/_error.php' );
        require( APP_DIR . '/views/_footer.php' );
        exit;
    }
}

require( APP_DIR . '/views/_header.php' );
require( APP_DIR . '/views/index.php' );
require( APP_DIR . '/views/_footer.php' );