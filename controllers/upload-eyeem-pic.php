<?php

function return_json( $mediaList = array(), $max_id = null, $error = null ) {
	return json_encode( array( 'media' => $mediaList, 'error' => $error, 'max_id' => $max_id ) );
}

$error     = null;
$mediaList = array();


// Return empty JSON object if an access token session isnt present
if ( empty( $_SESSION[ 'eyeem_access_token' ][ 'access_token' ] ) ) {
    exit( return_json( array(), null, 'You are not authenticated in EyeEm!' ) );
} else {
	$eyeem->setAccessToken( $_SESSION[ 'eyeem_access_token' ][ 'access_token' ] );

}

try{

	//	Config parameters for the temporary upload on server
	$url     = $_REQUEST[ 'photo' ];
	$savedir = '/tmp';

	//	Let's find what the Instagram url is made of... ;-)
	$urlInfo    = parse_url( $url );
	$sourcename = basename(	$urlInfo[ 'path' ] );
	$targetname = $savedir . '/' . $sourcename;

	//	Save the Instagram image on my server so that I can upload it to EyeEm
	file_put_contents( $targetname, file_get_contents( $_REQUEST[ 'photo' ] ) );

	//	Upload the image to EyeEm
	$filename = $eyeem->uploadPhoto( $targetname );

	//	Create the EyeEm Photo object
	$photo = $eyeem->postPhoto(
		array(
			'filename' => $filename,
			'title'    => $_REQUEST[ 'title' ],
			'topic'    => $_REQUEST[ 'topic' ],
		)
	);

	//	Create the igers2eye array to pass back to the ajax caller
	$mediaList[] = array(
    	'id'        => $photo->id,
    	'title'     => $photo->title,
    	'caption'   => $photo->caption,
    	'width'     => $photo->width,
    	'height'    => $photo->height,
    	'thumbUrl'  => $photo->thumbUrl,
    	'photoUrl'  => $photo->photoUrl,
    	'webUrl'    => $photo->webUrl,
    	'latitude'  => $photo->latitude,
    	'longitude' => $photo->longitude,
    	'updated'   => $photo->updated,
    );

} catch( ApiException $e ) {
	exit( return_json( array(), null, 'Troubles uploading photo to EyeEm!' ) );
}

exit( return_json( $mediaList, null, null ) );