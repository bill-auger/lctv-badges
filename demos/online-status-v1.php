<html><head><title>LCTV Online Status Badge - customizable SVG pill</title></head>
<body><link rel="stylesheet" type="text/css" href="lctv-badges-demo.css" />

  <p>LCTV Online Status Badge - customizable SVG pill</p>

  Type an LCTV channel name here --> <input id="channel-name-input" type="text" />
  then press "Gimme"             --> <input id="submit-input" type="button" value="Gimme" />
                                 --> <a id="lctv-badge-a"></a>

  <?php $SERVER_ROOT      = "http://" . $_SERVER['HTTP_HOST'] ;              ?>
  <?php $STATUS_PHP       = '/badges/online-status.php' ;                    ?>
  <?php $STATUS_V1_PARAMS = "?style=online-status-v1&channel=" ;             ?>
  <?php $STATUS_URL       = $SERVER_ROOT . $STATUS_PHP . $STATUS_V1_PARAMS ; ?>

  <script type="text/javascript">

var BADGE_A_ID       = 'lctv-badge-a' ;
var CHANNEL_INPUT_ID = 'channel-name-input' ;
var SUBMIT_INPUT_ID  = 'submit-input' ;
var LCTV_URL         = "https://www.livecoding.tv/" ;
var STATUS_URL       = "<?php echo $STATUS_URL ; ?>" ;

var BadgeA       = document.getElementById(BADGE_A_ID      ) ;
var ChannelInput = document.getElementById(CHANNEL_INPUT_ID) ;
var SubmitInput  = document.getElementById(SUBMIT_INPUT_ID ) ;
var BadgeImg     = document.createElement('img') ;

BadgeImg.style.width      = '100px' ;
BadgeImg.style.height     = '24px' ;
BadgeImg.style.visibility = 'hidden' ;
SubmitInput.onclick       = function()
{
  var channel_name          = ChannelInput.value
  BadgeA  .href             = LCTV_URL   + channel_name ;
  BadgeImg.src              = STATUS_URL + channel_name ;
  BadgeImg.style.visibility = 'visible' ;
} ;

BadgeA.appendChild(BadgeImg) ;

  </script>

  <p>to use this badge on your website:
    <ol>
      <li>add this A and IMG tag to your HTML
        <pre>&lt;a href="https://www.livecoding.tv/YOUR_LCTV_CHANNEL_NAME"&gt;
  &lt;img width="100" height="24" src="<?php echo $STATUS_URL ?>YOUR_LCTV_CHANNEL_NAME" /&gt;
&lt;/a&gt;</pre>
      </li>
    </ol>
  </p>

</body></html>
