<?php
/**
 * Authorize Livecoding.tv accounts.
 *
 * @package LCTVBadges\Authorize
 * @since 0.0.3
 */

/** Initialize. */
require_once( 'lctv_badges_init.php' );
/*
define( 'LCTV_CLIENT_ID', getenv( 'LCTV_CLIENT_ID' ) );
define( 'LCTV_CLIENT_SECRET', getenv( 'LCTV_CLIENT_SECRET' ) );
define( 'LCTV_REDIRECT_URL', getenv( 'LCTV_REDIRECT_URL' ) );
define( 'LCTV_MASTER_USER', 'bill-auger' );
define( 'LCTVAPI_DATA_STORE_CLASS', 'LCTVAPIDataStoreFlatFiles' );
define( 'LCTVAPI_DATA_PATH','/var/www/lctv/data/' );
require_once( 'LCTVAPI.php' );
require_once( 'lctv_badges_svg.php' );
*/

/** Load the API with no user. */
$lctv_api = new LCTVAPI( array(
	'data_store'    => LCTVAPI_DATA_STORE_CLASS,
	'client_id'     => LCTV_CLIENT_ID,
	'client_secret' => LCTV_CLIENT_SECRET,
	'redirect_url'  => LCTV_REDIRECT_URL,
	'user'          => '',
) );

/** Authorization html output. */
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>LCTV Badges Authorization</title>
	</head>
	<body style="font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:400;color:#111;">


<ul>constants
<li><?php echo "client_id=" . LCTV_CLIENT_ID ?></li>
<li><?php echo "client_secret=" . LCTV_CLIENT_SECRET ?></li>
<li><?php echo "redirect_url=" . LCTV_REDIRECT_URL ?></li>
<li><?php echo "data_store=" . LCTVAPI_DATA_STORE_CLASS ?></li>
<li><?php echo "user=" . LCTV_MASTER_USER ?></li>
</ul>
<ul>library
<?php $client_id = (isset($lctv_api->client_id)) ? $lctv_api->client_id : "unset" ; ?>
<?php $client_secret = (isset($lctv_api->client_secret)) ? $lctv_api->client_secret : "unset" ; ?>
<?php $data_store = (isset($lctv_api->data_store)) ? $lctv_api->data_store : "unset" ; ?>
<?php $redirect_url = (isset($lctv_api->redirect_url)) ? $lctv_api->redirect_url : "unset" ; ?>
<?php $user = (isset($lctv_api->user)) ? $lctv_api->user : "unset" ; ?>
<li><?php echo "client_id=$client_id" ?></li>
<li><?php echo "client_secret=$client_secret" ?></li>
<li><?php echo "redirect_url=$redirect_url" ?></li>
<li><?php echo "data_store=" . LCTVAPI_DATA_STORE_CLASS ?></li>
<li><?php echo "user=$user" ?></li>
</ul>
<?php
$is_set= isset($lctv_api->token) ;
$is_empty = empty($lctv_api->token) ;
$token = ( ($is_set) ?
                   (($is_empty) ? "empty" :
                                 var_dump($lctv_api->token)) :
                   "unset") ;
$access_token = ((isset( $lctv_api->token) && isset( $lctv_api->token->access_token ) ) ? $lctv_api->token->access_token : "unset") ;
?>
<ul>tokens
<li><?php echo "token=$token"; ?></li>
<li><?php echo "access_token=$access_token"; ?></li>
<li><?php echo "LCTVAPI_DATA_PATH=" . LCTVAPI_DATA_PATH; ?></li>
</ul>


		<div style="padding:20px;width:600px;margin:0 auto;">
		<?php if ( ! $lctv_api->is_authorized() ) : ?>
			<h1 style="font-size:24px;font-weight:400;">Authorize</h1>
			<p>Some badges require access to your Livecoding.tv account information. Authorize account access with the link below.</p>
			<p><a href="<?php echo $lctv_api->get_authorization_url(); ?>">Connect your account</a></p>
			<br><br>
			<h1 style="font-size:24px;font-weight:400;">Remove Authorization</h1>
			<?php if ( isset( $_GET['user'] ) && isset( $_GET['delete'] ) ) : ?>
				<p>The account &ldquo;<?php echo htmlspecialchars( $_GET['user'] ); ?>&rdquo; has been disconnected.</p>
			<?php else : ?>
				<p>This site saves an authorization token to access your Livecoding.tv account. You may delete this token so as to stop this site from further accessing your account information.</p>
				<p>To disconnect your account, first connect your account with the link above, even if you have previously done so. After authorization a disconnect link will appear.</p>
			<?php endif; ?>
		<?php else : ?>
			<h1 style="font-size:24px;font-weight:400;">Authorize</h1>
			<p>The account &ldquo;<?php echo htmlspecialchars( $lctv_api->user ); ?>&rdquo; is now connected.</p>
			<br><br>
			<h1 style="font-size:24px;font-weight:400;">Remove Authorization</h1>
			<p>This site saves an authorization token to access your Livecoding.tv account. You may delete this token so as to stop this site from further accessing your account information with the link below.</p>
			<p><a href="<?php echo $lctv_api->redirect_url; ?>?user=<?php echo urlencode( $lctv_api->user ); ?>&delete=<?php echo urlencode( $lctv_api->token->delete_id ); ?>">Disconnect your account</a></p>
		<?php endif; ?>
			<br><br>
			<p><a href="./">Return Home</a></p>
		</div>
	</body>
</html>
<?php
