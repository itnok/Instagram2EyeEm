<?php

define( 'APP_DIR',         		__DIR__ );
define( 'WEB_DIR',  			dirname( $_SERVER[ 'PHP_SELF' ] ) );
define( 'REDIRECT_AFTER_AUTH',  'http://dev.itnok.com/igers2eye/migrate' );

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

// Requested page
$page = $_REQUEST[ 'p' ];

// Javascript files to append
$js_append = array();

// If an example has been chosen, include it and exit
if( ! empty( $page ) && $page != 'home' && file_exists( APP_DIR . '/controllers/' . $page . '.php' ) ) {
    try {
        date_default_timezone_set( 'Europe/Rome' );
        require( APP_DIR . '/_SplClassLoader.php' );
        $loader = new SplClassLoader( 'Instagram', APP_DIR . '/api/instagram' );
        $loader->register();

        //	Append some Js specific for this view if exists
        if( file_exists( APP_DIR . '/js/' . $page . '.js' ) )
        array_push(
        	$js_append,
            'js/' . $page . '.js'
        );
        
        require( APP_DIR . '/controllers/' . $page . '.php' );
        exit;
    }
    /**
     * Authorization Exception thrown
     * Clear the session and redirect to the auth page
     */
    catch ( \Instagram\Core\ApiAuthException $e ) {
        unset( $_SESSION );
        session_destroy();
        header( 'Location: ' . $auth_config[ 'redirect_uri' ] );
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
