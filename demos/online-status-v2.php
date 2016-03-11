<html><head><title>LCTV Online Status Badge - LCTV branded pill</title></head>
<body><link rel="stylesheet" type="text/css" href="lctv-badges-demo.css" />

  <p>LCTV Online Status Badge - LCTV branded pill</p>

  <img id="lctv-status-img" data-channel="nobody" width="100" height="24" />

  <?php $SERVER_ROOT   = "http://" . $_SERVER['HTTP_HOST'] ;  ?>
  <?php $STATUS_V2_JS  = 'js/online-status-v2.js' ;           ?>
  <?php $STATUS_V2_URL = $SERVER_ROOT . '/' . $STATUS_V2_JS ; ?>

  <script type="text/javascript" src="../<?php echo $STATUS_V2_JS ; ?>"></script>

  <p>to use this badge on your website:
    <ol>
      <li>add this IMG tag to your HTML
        <pre>&lt;img id="lctv-status-img" data-channel="MY_LCTV_CHANNEL_NAME" width="100" height="24" /&gt;</pre>
      </li>
      <li>include this SCRIPT tag
        <pre>&lt;script type="text/javascript" src="<?php echo $STATUS_V2_URL ; ?>"&gt;&lt;/script&gt;</pre>
      </li>
    </ol>
  </p>

</body></html>
