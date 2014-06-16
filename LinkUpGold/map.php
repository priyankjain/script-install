<?PHP

#################################################
##                                             ##
##               Link Up Gold                  ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                                             ##
##               version:  8.0                 ##
##            copyright (c) 2012               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./common.php');


if (($_GET[cat]) AND (is_numeric($_GET[cat])))
{ $category_vars = get_category_variables($_GET[cat]);
  if (!$category_vars[n]) exit;
  $latitude = $category_vars[latitude];
  $longitude = $category_vars[longitude];
  $zoom = $category_vars[map_zoom];
  $table = $s[item_types_tables][$category_vars[use_for]];

  $q = dq("select $s[pr]items_maps.*,$table.title,$table.map,$table.rewrite_url from $s[pr]items_maps,$s[pr]cats_items,$table where $s[pr]cats_items.c = '$category_vars[n]' and $s[pr]items_maps.latitude != 0.0000000 and $s[pr]items_maps.longitude != 0.0000000 and $s[pr]items_maps.latitude != -1 and $s[pr]items_maps.longitude != -1 and $s[pr]items_maps.what = '$category_vars[use_for]' and $s[pr]cats_items.what = '$category_vars[use_for]' and $s[pr]items_maps.n = $s[pr]cats_items.n and $table.n = $s[pr]items_maps.n",1);
  while ($x = mysql_fetch_assoc($q))
  { $pocet++;
    $x[title] = htmlspecialchars($x[title]);
    if ($x[image2]) $icon1 = $x[image2]; else $icon1 = "$s[site_url]/images/map_icon1.png";
    //if ($x[image2]) $icon2 = $x[image2]; else $icon2 = "$s[site_url]/images/map_icon2.png";
    $html = '<div style="width:200px;height:150px;overflow:auto;background-color:#E0E9FE;padding:7px;color:#000000;"> <a target="_top" href="'.get_detail_page_url($category_vars[use_for],$x[n],$x[rewrite_url],'',1).'" style="font-size:18px;font-weight:bold;">'.$x[title].'<div style="font-size:13px;font-weight:normal;">'.htmlspecialchars(str_replace('_gmok_','',$x[map])).'</div>';
    $points .= "
    var mymappoint$pocet = new google.maps.LatLng('$x[latitude]','$x[longitude]');
    createMarker(mymappoint$pocet,'$html','$x[title]','$icon1');";
  }
}
elseif (($_GET[what]) AND ($s[item_types_tables][$_GET[what]]))
{ $n = round($_GET[n]);
  $item_vars = get_item_variables($_GET[what],$n);
  if (!$item_vars[n]) exit;
  $items_maps = get_items_maps_variables($_GET[what],$n);
  $longitude = $items_maps[longitude]; $latitude = $items_maps[latitude];
  $zoom = 15;
  $icon1 = "$s[site_url]/images/map_icon1.png";
  $item_vars[title] = str_replace("\n", "", str_replace("\r", "", $item_vars[title]));
  $item_vars[description] = str_replace("\n", "", str_replace("\r", "", $item_vars[description]));
  $html = '<span style="font-size:15px;font-weight:bold;">'.htmlspecialchars($item_vars[title]).'</span><br>'.htmlspecialchars($item_vars[description]);
  $points .= "
  var mymappoint$pocet = new google.maps.LatLng('$latitude','$longitude');
  createMarker(mymappoint$pocet,'<div style=\"width:200px;height:150px;overflow:auto;background-color:#E0E9FE;padding:7px;color:#000000;\">".$html."</div>','$title','$icon1');";
}
else exit;

echo '<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<LINK href="'.$s[site_url].'/styles/Sky/styles.css" rel="StyleSheet">
<style type="text/css">
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}
#map_canvas {
  height: 100%;
}
</style>
<script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
var mymap = new google.maps.LatLng('.($latitude+0.00).','.($longitude-0.00).');
var marker;
var map;




function createMarker(point,html,title,icon) {
var infowindow = new google.maps.InfoWindow({
        content: html
    });

var marker = new google.maps.Marker({
    position: point,
    map: map,
    title: title,
    icon: icon
  });
    google.maps.event.addListener(marker, \'click\', function() {
      infowindow.open(map,marker);
    });

  return marker;
}

function initialize() {
  var mapOptions = {
    zoom: '.$zoom.',
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: mymap
  };
  map = new google.maps.Map(document.getElementById("map_canvas"),
      mapOptions);
'.$points.'
}

</script>
</head>
<body onload="initialize()">
  <div id="map_canvas"></div>
</body>
</html>
';

/*
var mymappoint = new google.maps.LatLng('.($latitude).','.($longitude).');
createMarker(mymappoint, \'kkkkkkkkkkkkkkk\', \'mmmmmmmmmmmmmmmmmmmmm\');
    
var mymappoint1 = new google.maps.LatLng('.($latitude+1).','.($longitude+1).');
createMarker(mymappoint1, \'kkk22222222222kkkkk\', \'mmm11111111mmmmmmm\');
    
*/

?>