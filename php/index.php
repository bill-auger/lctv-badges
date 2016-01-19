<?php
/**
 * Livecoding.tv Badges main page.
 *
 * Intructions and other info about LCTV Badges.
 *
 * @author  andtrev
 * @license GPLv3
 * @package LCTVBadges
 * @version 0.0.4
 */

/** Badge svg creator. */
require_once( 'lctv_badges_svg.php' );

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Livecoding.tv Badges</title>
	</head>
	<body style="font-family:Helvetica,Arial,sans-serif;font-size:15px;font-weight:400;color:#111;">
		<div style="padding:20px;width:600px;margin:0 auto;">
			<h1 style="font-size:24px;font-weight:400;">Livecoding.tv Badges</h1>
			<p>
				usage:
				<br>
				<ul>
					<li>Badges are SVG images meant to be used as src for img elements.</li>
				</ul>
				<pre>&lt;img src="streamingstatus.php?channel=channelname"&gt;</pre>
			</p>
			<br><br>
			<h2 style="font-size:20px;font-weight:400;">Streaming Status:</h2>
			<p><pre>streamingstatus.php?channel=channelname</pre></p>
			<p>
				options:
				<br>
				<ul>
					<li><em>channel</em> (required) LCTV channel name.</li>
					<li><em>online</em> (optional) Button message if status is streaming.</li>
					<li><em>offline</em> (optional) Button message if status is not streaming.</li>
					<li><em>title</em> (optional) true/false to show streaming title when live, this will override the online message.</li>
					<li><em>link</em> (optional) true/false to automatically link to channel.</li>
				</ul>
			</p>
			<p>
				<?php echo get_badge_svg( 'livecoding.tv', 'online', '#4c1' ); ?>
				<br>
				<?php echo get_badge_svg( 'livecoding.tv', 'offline', '#e05d44' ); ?>
			</p>
			<br><br>
			<h2 style="font-size:20px;font-weight:400;">Live Viewers:</h2>
			<p><pre>liveviewers.php?channel=channelname</pre></p>
			<p>
				options:
				<br>
				<ul>
					<li><em>channel</em> (required) LCTV channel name.</li>
					<li><em>link</em> (optional) true/false to automatically link to channel.</li>
				</ul>
			</p>
			<p>
				<?php echo get_badge_svg( 'lctv viewers', '30', '#4c1' ); ?>
				<br>
				<?php echo get_badge_svg( 'lctv viewers', '0', '#e05d44' ); ?>
			</p>
			<br><br>
			<h2 style="font-size:20px;font-weight:400;">Number of Followers:</h2>
			<p><pre>numberfollowers.php?channel=channelname</pre></p>
			<p>
				options:
				<br>
				<ul>
					<li><em>channel</em> (required) LCTV channel name.</li>
					<li><em>link</em> (optional) true/false to automatically link to channel.</li>
				</ul>
				Needs an <a href="authorize.php">authorized account</a> to function.
			</p>
			<p>
				<?php echo get_badge_svg( 'lctv followers', '50', '#4c1' ); ?>
			</p>
			<br><br>
			<h2 style="font-size:20px;font-weight:400;">Last Streamed:</h2>
			<p><pre>laststreamed.php?channel=channelname</pre></p>
			<p>
				options:
				<br>
				<ul>
					<li><em>channel</em> (required) LCTV channel name.</li>
					<li><em>link</em> (optional) true/false to automatically link to channel.</li>
				</ul>
				Needs an <a href="authorize.php">authorized account</a> to function.
			</p>
			<p>
				<?php echo get_badge_svg( 'lctv last streamed', 'Jan 1, 2016', '#4c1' ); ?>
				<br>
				<?php echo get_badge_svg( 'lctv last streamed', 'never', '#e05d44' ); ?>
			</p>
			<br><br>
		</div>
	</body>
</html>
<?php
