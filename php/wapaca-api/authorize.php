<?php

require( 'livecodingAuth.php' );


define( "CLIENT_ID", getenv( 'LCTV_CLIENT_ID' ));
define( "CLIENT_SECRET", getenv( 'LCTV_CLIENT_SECRET' ));
define( "REDIRECT_URL", getenv( 'LCTV_REDIRECT_URL' ));


/* Instantiate authorizarion helper. */
try {

	$LivecodingAuth = new LivecodingAuth( CLIENT_ID, CLIENT_SECRET, REDIRECT_URL );

}
catch( Exception $ex ) {
	// Error initializing library. Display error message.

	die( $ex->getMessage() );

}

/* Check for previous authorization */
if ( !$LivecodingAuth->getIsAuthorized() ) {

	// Here we have not yet been authorized

	// Display a link for the user to authorize the app with this script as the redirect URL
	$auth_link = $LivecodingAuth->getAuthLink();
	echo "This app is not yet authorized. Use the link or URL below to authorize it.<br/>";
	echo "<a href=\"$auth_link\">Connect my account</a><br/>" ;

	// Here we wait for the user to click the authorization link
	//   which will result in another request for this page
	//   with $LivecodingAuth->getIsAuthorized() then returning true.

} else {

	// Here we are authorized from the previous request

	echo "This app is authorized.";

}

?>
