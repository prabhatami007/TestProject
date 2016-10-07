<?php
/*// We define our address
$address = 'Caen, Basse-Normandie';
// We get the JSON results from this request
$geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
// We convert the JSON to an array
$geo = json_decode($geo, true);
// If everything is cool
if ($geo['status'] = 'OK') {
  // We set our values
  echo $latitude = $geo['results'][0]['geometry']['location']['lat'];
  $longitude = $geo['results'][0]['geometry']['location']['lng'];
}
*/


function getLatLng($address) {
// We get the JSON results from this request
$geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
// We convert the JSON to an array
$geo = json_decode($geo, true);
// If everything is cool
$latlng=array();
if ($geo['status'] = 'OK') {
  // We set our values
 $latlng['latitude'] = $geo['results'][0]['geometry']['location']['lat'];
  $latlng['longitude'] = $geo['results'][0]['geometry']['location']['lng'];
 return  $latlng;
} else {
return "";
}
}

$address = 'Caen, Basse-Normandie';
print_r(getLatLng($address));