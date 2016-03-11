<html><head><title>LCTV Online Status Badge - SVG glassy</title></head>
<body><link rel="stylesheet" type="text/css" href="lctv-badges-demo.css" />

  <p>LCTV Online Status Badge - SVG glassy</p>

  Type an LCTV channel name here --> <input id="channel-name-input" type="text" />
  then press "Gimme"             --> <input id="submit-input" type="button" value="Gimme" />
                                 --> <img   id="lctv-status-img" data-channel="nobody"></img>

  <?php $STATUS_IMG_ID = 'lctv-status-img' ;                 ?>
  <?php $SERVER_ROOT   = "http://" . $_SERVER['HTTP_HOST'] ; ?>
  <?php $STATUS_V3_JS  = '/js/online-status-v3.js' ;         ?>
  <?php $STATUS_V3_URL = $SERVER_ROOT . $STATUS_V3_JS ;      ?>

  <script type="text/javascript" src="<?php echo $STATUS_V3_URL ; ?>"></script>
  <script type="text/javascript">

var CHANNEL_INPUT_ID = 'channel-name-input' ;
var SUBMIT_INPUT_ID  = 'submit-input' ;
var STATUS_IMG_ID    = '<?php echo $STATUS_IMG_ID ; ?>' ;

var ChannelInput = document.getElementById(CHANNEL_INPUT_ID) ;
var SubmitInput  = document.getElementById(SUBMIT_INPUT_ID ) ;
var StatusImg    = document.getElementById(STATUS_IMG_ID   ) ;

StatusImg.style.width      = '100px' ;
StatusImg.style.height     = '24px' ;
StatusImg.style.visibility = 'hidden' ;
SubmitInput.onclick        = function()
{
  var channel_name          = ChannelInput.value ;
  StatusImg.dataset.channel = channel_name ;

  loadImages() ;
  StatusImg.style.visibility = 'visible' ;
} ;

  </script>


  <p>to use this badge on your website:
    <ol>
      <li>add this IMG tag to your HTML
        <pre>&lt;img id="<?php echo $STATUS_IMG_ID ; ?>" data-channel="YOUR_LCTV_CHANNEL_NAME" width="100" height="24" /&gt;</pre>
      </li>
      <li>include this SCRIPT tag
        <pre>&lt;script type="text/javascript" src="<?php echo $STATUS_V3_URL ; ?>"&gt;&lt;/script&gt;</pre>
      </li>
    </ol>
  </p>

</body></html>
