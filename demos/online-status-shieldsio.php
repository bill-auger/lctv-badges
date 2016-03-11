<html><head><title>LCTV Online Status Badge - shields.io pill</title></head>
<body><link rel="stylesheet" type="text/css" href="lctv-badges-demo.css" />

  <p>LCTV Online Status Badge - shields.io pill</p>

  <img id="lctv-status-img" id="MY_LCTV_CHANNEL_NAME" width="100" height="24" />

  <?php $SERVER_ROOT          = $_SERVER['HTTP_HOST'] ;           ?>
  <?php $SHIELDSIO_JS         = 'js/online-status-shieldsio.js' ; ?>
  <?php $STATUS_SHIELDSIO_URL = $SERVER_ROOT . '/' . $SHIELDSIO_JS ; ?>

  <script type="text/javascript" src="../<?php echo $SHIELDSIO_JS ; ?>"></script>

  <p>to use this badge on your website:
    <ol>
      <li>add this IMG tag to your HTML
        <pre>&lt;img id="lctv-status-img" id="MY_LCTV_CHANNEL_NAME" width="100" height="24" /&gt;</pre>
      </li>
      <li>include this SCRIPT tag
        <pre>&lt;script type="text/javascript" src="http://<?php echo $STATUS_SHIELDSIO_URL ; ?>"&gt;&lt;/script&gt;</pre>
      </li>
    </ol>
  </p>

</body></html>
