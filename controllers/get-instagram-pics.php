<?php

function return_json( $mediaList = array(), $max_id = null, $error = null ) {
	return json_encode( array( 'media' => $mediaList, 'error' => $error, 'max_id' => $max_id ) );
}

$error     = null;
$max_id    = ! empty( $_REQUEST[ 'max_id' ] ) ? $_REQUEST[ 'max_id' ] : null;
$mediaList = array();


// Return empty JSON object if an access token session isnt present
if ( ! isset( $_SESSION[ 'instagram_access_token' ] ) ) {
    exit( return_json( array(), null, 'You are not authenticated in Instagram!' ) );
}

$instagram = new Instagram\Instagram;
$instagram->setAccessToken( $_SESSION[ 'instagram_access_token' ] );
$current_user = $instagram->getCurrentUser();

try{
	$username  = ! empty( $_REQUEST[ 'user' ] ) ? $_REQUEST[ 'user' ] : $current_user->getUserName();
	$user      = $instagram->getUserByUsername( $username );
	
	try {
		$params = array(
    		'max_id' => $max_id,
    	);
    	$media = $user->getMedia( $params );

    	foreach( $media as $photo ) {
    		$location = array(
    			'lat' => null,
    			'lon' => null,
    		);
    		if( $photo->hasLocation() ) {
	    		$location = array(
	    			'lat' => $photo->getLocation()->getData()->latitude,
	    			'lon' => $photo->getLocation()->getData()->longitude,
	    		);
    		}

    		$caption = $photo->getCaption();

    		$mediaList[] = array(
    			'caption'  => ( ! empty( $caption ) ? $photo->getCaption()->getText() : '' ),
    			'tags'     => $photo->getTags()->toArray(),
    			'location' => $location,
    			'link'     => $photo->getLink(),
    			'image'    => array(
    				'ico' => $photo->getThumbnail(),
    				'low' => $photo->getLowRes(),
    				'std' => $photo->getStandardRes(),
    			),
    			'created'  => $photo->getCreatedTime(),
    		);
    	}
    	
    	//	Get Next pagination Id
    	$max_id = $media->getNextMaxId();
    	
	} catch( \Instagram\Core\ApiException $e ) {
		$error = $_POST['error_message'];
	}
} catch( \Instagram\Core\ApiException $e ) {
	if ( $e->getType() == $e::TYPE_NOT_ALLOWED ) {
		exit( return_json( array(), null, 'This user is PRIVATE and media cannot be accessed!' ) );
	} else {
		throw $e;
	}
}

exit( return_json( $mediaList, $max_id, $error ) );