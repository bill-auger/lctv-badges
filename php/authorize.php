<?php
/**
 * Authorize Livecoding.tv accounts.
 * 
 * @package LCTVBadges\Authorize
 * @since 0.0.3
 */

/** Initialize. */
require_once( 'lctv_badges_init.php' );

/** Load the API with no user. */
$lctv_api = new LCTVAPI( array(
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
			<p><a href="https://themetuxedo-andtrev.c9.io/lctv-api/authorize.php?user=<?php echo urlencode( $lctv_api->user ); ?>&delete=<?php echo urlencode( $lctv_api->token->delete_id ); ?>">Disconnect your account</a></p>
		<?php endif; ?>
			<br><br>
			<p><a href="./">Return Home</a></p>
		</div>
	</body>
</html>
<?php
