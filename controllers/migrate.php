<?php

// Start authorization if an access token session isnt present
if ( ! isset( $_SESSION[ 'instagram_access_token' ] ) ) {
    require( APP_DIR . '/_auth.php' );
    exit;
}

$instagram = new Instagram\Instagram;
$instagram->setAccessToken( $_SESSION[ 'instagram_access_token' ] );
$current_user = $instagram->getCurrentUser();

$mediaList = array();

try {

	$username  = ! empty( $_REQUEST[ 'user' ] ) ? $_REQUEST[ 'user' ] : $current_user->getUserName();
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
    'lib/spin.js/dist/spin.min.js'
);

require( 'views/_header.php' );
require( 'views/migrate.php' );
require( 'views/_footer.php' );