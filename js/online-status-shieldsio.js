/* LCTV online status badge - shields.io pill

  to use this badge on your website:

  1. add this IMG tag to your HTML
       <img id="lctv-status-img" id="MY_LCTV_CHANNEL_NAME" width="100" height="24" />
  2. include this SCRIPT tag
       <script type="text/javascript" src="https://bill-auger.github.io/lctv-badges/js/online-status-shieldsio.js"></script>
*/


var STATUS_IMG_ID    = 'lctv-status-img' ;
var STATUS_A_ID      = 'lctv-status-a' ;
var LCTV_URL         = "https://www.livecoding.tv/" ;
var LCTV_API_URL     = "https://www.livecoding.tv/livestreams/" ;
var SHIELDSIO_URL    = "https://img.shields.io/badge" ;
var XHR_STATUS_READY = 4 ;

var StatusImg   = document.getElementById(STATUS_IMG_ID) ;
var StatusA     = document.createElement('a') ;
var ChannelName = StatusImg.dataset.channel ;


function getStatus()
{
  var xhr        = new XMLHttpRequest() ;
  var status_url = LCTV_API_URL ;

  xhr.open("GET" , status_url , true) ;
  xhr.setRequestHeader("Content-type" , "application/json") ;
  xhr.onreadystatechange = function() { parseJSON(xhr) ; } ;
  xhr.send() ;
}

function parseJSON(xhr)
{
  if (xhr.readyState != XHR_STATUS_READY) return ;

  var is_online = !!(~xhr.responseText.indexOf("/video/livestream/" + ChannelName + "/thumbnail")) ;

  createBadge(is_online) ;
}

function createBadge(is_online)
{
  StatusA.id   = STATUS_A_ID ;
  StatusA.href = LCTV_URL + ChannelName ;

  StatusImg.parentNode.replaceChild(StatusA , StatusImg) ;
  StatusA             .appendChild(StatusImg) ;

  var status   = (is_online) ? "Online-green" : "Offline-red" ;
  StatusImg.src = SHIELDSIO_URL + "/LCTV-" + status + ".svg" ;
}


window.onload = function() { getStatus() ; } ;
