<?php

$mailTo   = 's.conti@itnok.com';
$mailFrom = 'Instagram 2 EyeEm <igers2eye@dev.itnok.com>';
$subject  = 'NO Donation - Feedback';
$message  = $_REQUEST[ 'message' ];

if( ! empty( $message ) ) {
	mail( $mailTo, $subject, $message, "From: " . $mailFrom );
}
