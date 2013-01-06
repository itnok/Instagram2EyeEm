<?php

define( 'APP_DIR',         		__DIR__ );
define( 'WEB_DIR',  			dirname( $_SERVER[ 'PHP_SELF' ] ) );
define( 'REDIRECT',  			'http://dev.itnok.com/igers2eye/' );
define( 'REDIRECT_AFTER_AUTH',  REDIRECT . 'auth_' );

// Turn on error reporting
error_reporting( E_ALL & ~E_NOTICE );
ini_set( 'display_errors', 'On' );

// Start the session
session_start();

//	LESS compiler library
require_once "lib/php/lessphp/lessc.inc.php";
$less = new lessc;

// Authorization configuration
$auth_config = array(
    'instagram' => array(
	    'client_id'         => 'e341350c776a4848b8ab92f37009f4fc',
	    'client_secret'     => '78afef998efe47a5930fe93b96b32def',
	    'redirect_uri'      => REDIRECT_AFTER_AUTH . 'igers',
	    'scope'             => array( 'likes', 'comments', 'relationships' ),
	),
	'eyeem' => array(
		'client_id' 		=> 'qOIvHA9pLUumq6zhYvCEkNr9nhtgf3g2',
		'client_secret'		=> 'uTE9CJX3rngiGC5buh996SWPc2F8TpZ9',
		'redirect_uri'		=> REDIRECT_AFTER_AUTH . 'eyeem',
	),
	'foursquare' => array(
		'client_id' 		=> '3GPBXRNBQNKGYXWXRKAVPFBA5WQNP3FHLYYAUVPS0XOFKRDO',
		'client_secret'		=> 'JAQPICH5MMN5A0D2INGT0S2AKGL0ULMY1NY0QMPCHIQJHENQ',
		'redirect_uri'		=> REDIRECT_AFTER_AUTH . '4square',
	)
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

        //	Registers Instagram API Classes
        $loaderIgers = new SplClassLoader( 'Instagram', APP_DIR . '/api/instagram' );
        $loaderIgers->register();

        $instagram = new Instagram\Instagram;

        //	Registers EyeEm API Classes
        require_once( APP_DIR . '/api/eyeem/lib/Eyeem.php' );

        $eyeem = new Eyeem();
        $eyeem->setClientId( $auth_config[ 'eyeem' ][ 'client_id' ] );
        $eyeem->setClientSecret( $auth_config[ 'eyeem' ][ 'client_secret' ]  );
        $eyeem->autoload();

        //	Append some Js specific for this view if exists
        if( file_exists( APP_DIR . '/js/' . $page . '.js' ) ) {
	        array_push(
	        	$js_append,
	            'js/' . $page . '.js'
	        );
        }
        
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
        header( 'Location: ' . $auth_config[ 'instagram' ][ 'redirect_uri' ] );
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
