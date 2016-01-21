<?php
/**
 * Livecoding.tv Badges main page.
 *
 * Intructions and other info about LCTV Badges.
 *
 * @author  andtrev
 * @license GPLv3
 * @package LCTVBadges
 * @version 0.0.5
 */

/** Initialize. */
require_once( 'lctv_badges_init.php' );

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
				<img id="streamingstatus" src="streamingstatus.php?channel=<?php echo LCTV_MASTER_USER; ?>">
			</p>
			<p>
				Enter a channel name and try it out:
				<br>
				<input id="streamingstatuschannel" type="text"> <input type="submit" value="GO" onclick="document.getElementById('streamingstatus').src='streamingstatus.php?channel='+document.getElementById('streamingstatuschannel').value;">
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
				<img id="liveviewers" src="liveviewers.php?channel=<?php echo LCTV_MASTER_USER; ?>">
			</p>
			</p>
			<p>
				Enter a channel name and try it out:
				<br>
				<input id="liveviewerschannel" type="text"> <input type="submit" value="GO" onclick="document.getElementById('liveviewers').src='liveviewers.php?channel='+document.getElementById('liveviewerschannel').value;">
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
				<img id="numberfollowers" src="numberfollowers.php?channel=<?php echo LCTV_MASTER_USER; ?>">
			</p>
			<p>
				Enter a channel name and try it out:
				<br>
				<input id="numberfollowerschannel" type="text"> <input type="submit" value="GO" onclick="document.getElementById('numberfollowers').src='numberfollowers.php?channel='+document.getElementById('numberfollowerschannel').value;">
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
				<img id="laststreamed" src="laststreamed.php?channel=<?php echo LCTV_MASTER_USER; ?>">
			</p>
			<p>
				Enter a channel name and try it out:
				<br>
				<input id="laststreamedchannel" type="text"> <input type="submit" value="GO" onclick="document.getElementById('laststreamed').src='laststreamed.php?channel='+document.getElementById('laststreamedchannel').value;">
			</p>
			<br><br>
		</div>
	</body>
</html>
<?php
