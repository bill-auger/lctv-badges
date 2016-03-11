/** online-status-v3.js
 * loads SVG v3 online status images
 * and assigns them to image static, mouseover, mousedown states
 * (see: demos/online-status-v3.php)
 */


var STATUS_IMG_ID    = 'lctv-status-img' ;
var STATUS_A_ID      = 'lctv-status-a' ;
var SERVER_ROOT      = window.location.hostname ;
var STATUS_PHP       = '/badges/online-status.php' ;
var STATUS_V3_PARAMS = "?style=online-status-v3&channel=" ;
var STATUS_V3_URL    = "http://" + SERVER_ROOT + STATUS_PHP + STATUS_V3_PARAMS ;
var LCTV_URL         = "https://www.livecoding.tv/" ;


function loadImages()
{
  var status_img   = document.getElementById(STATUS_IMG_ID) ;
  var status_a     = document.createElement('a') ;
  var channel_name = status_img.dataset.channel ;

  var parent = status_img.parentNode ;
  parent.replaceChild(status_a , status_img) ;
  status_a.appendChild(status_img) ;

  status_a   .id  = STATUS_A_ID ;
  status_a  .href = LCTV_URL      + channel_name ;
  status_img.src  = STATUS_V3_URL + channel_name ;
}
