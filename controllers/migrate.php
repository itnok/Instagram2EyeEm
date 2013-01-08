<?php

//
//	Instagram
//

// Start Instagram authorization if an access token session isnt present
if( empty( $_SESSION[ 'instagram_access_token' ] ) ) {
	header( 'Location: ' . REDIRECT_AFTER_AUTH . 'igers' );
    exit;
}

$instagram->setAccessToken( $_SESSION[ 'instagram_access_token' ] );
$current_user = $instagram->getCurrentUser();

//
//	EyeEm
//

// Start EyeEm authorization if an access token session isnt present
if( empty( $_SESSION[ 'eyeem_access_token' ][ 'access_token' ] ) ) {
	header( 'Location: ' . REDIRECT_AFTER_AUTH . 'eyeem' );
    exit;
} else {
	$eyeem->setAccessToken( $_SESSION[ 'eyeem_access_token' ][ 'access_token' ] );
}

try {

	$username  = ! empty( $_REQUEST[ 'user' ] ) ? $_REQUEST[ 'user' ] : $current_user->getUserName();
//	$username  = $current_user->getUserName();
	$user      = $instagram->getUserByUsername( $username );
	$media_num = $user->getMediaCount();
	
} catch( \Instagram\Core\ApiException $e ) {
	if ( $e->getType() == $e::TYPE_NOT_ALLOWED ) {
		require( 'views/_header.php' );
		require( 'views/user_private.php' );
		require( 'views/_footer.php' );
		exit;
	} else {
		throw $e;
	}
}

//	Append some Js in the footer view template
array_unshift(
	$js_append,
    'lib/js/spin.js/dist/spin.min.js'
);

require( 'views/_header.php' );
require( 'views/migrate.php' );
require( 'views/_footer.php' );