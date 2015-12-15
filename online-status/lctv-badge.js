/* to use this badge on your website
  * include this script
      <script type="text/javascript" src="https://raw.githubusercontent.com/bill-auger/lctv-badges/master/online-status/lctv-badge.js">
  * add an A and IMG tag to your HTML
      <a id="MY_LCTV_CHANNEL_NAME">
        <img id="lctv-online-status" width="100" height="24" />
      </a>
*/
console.log("lctv-badge.js:IN") ;


var BADGE_IMG_ID     = 'lctv-online-status' ;
var LCTV_API_URL     = "https://www.livecoding.tv/livestreams/" ;
var SHIELDSIO_URL    = "https://img.shields.io/badge" ;
var XHR_STATUS_READY = 4 ;

var BadgeImg    = document.getElementById(BADGE_IMG_ID) ;
var ChannelName = BadgeImg.parentNode.id ;


function getStatus()
{
console.log("lctv-badge.js:getStatus() ChannelName=" + ChannelName) ;
// var stats_url = LCTV_API_URL + ChannelName + "/stats.json" ;
// e.g. {"views_live": 4, "item_class": "livestream", "views_overall": 6958}

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

console.log("lctv-badge.js:parseJSON() is_online=" + is_online) ;

  createBadge(is_online) ;
}

function createBadge(is_online)
{
  var status   = (is_online) ? "Online-green" : "Offline-red" ;
  BadgeImg.src = SHIELDSIO_URL + "/LCTV-" + status + ".svg" ;

console.log("lctv-badge.js:OUT") ;
}


window.onload = function() { getStatus() ; } ;
