<html>

<head>

<title>Multiple Location Marker in One Google Map</title>
<!-- Mobile viewport optimized -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link media="all" type="text/css" href="assets/dashicons.css" rel="stylesheet">
<link media="all" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet">
<link rel='stylesheet' id='style-css'  href='style.css' type='text/css' media='all' />
<script type='text/javascript' src='assets/jquery.js'></script>


<?php /* === GOOGLE MAP JAVASCRIPT NEEDED (JQUERY) ==== */ ?>
<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
<script type='text/javascript' src='assets/gmaps.js'></script>

</head>
<?php
function getLatLng($address) {
// We get the JSON results from this request
$geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=true&v=3');
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

$con=mysql_connect("localhost","root","");
$link=mysql_select_db("locations",$con);
?>



<body>
	<div id="container">

		<article class="entry">

			<header class="entry-header">
				<h1>Our Locations</h1>
				
			</header>

			<div class="entry-content">

				<?php /* === THIS IS WHERE WE WILL ADD OUR MAP USING JS ==== */ ?>
				<div class="google-map-wrap" itemscope itemprop="hasMap" itemtype="http://schema.org/Map">
					<div id="google-map" class="google-map">
					</div><!-- #google-map -->
				</div>

				<?php /* === MAP DATA === */ ?>
				<?php
				$locations = array();
	$strLoc=mysql_query("select * from storelocations");
			while($row=mysql_fetch_array($strLoc)){	
				/* Marker #1 */
				$address=$row['address'];
				$title=$row['title'];
				$latlng=getLatLng($address);
				$locations[] = array(
					'google_map' => array(
						'lat' => $latlng['latitude'],
						'lng' => $latlng['longitude'],
					),
					'location_address' => $address,
					'location_name'    => $title,
				);

			}
		
				?>


				<?php /* === PRINT THE JAVASCRIPT === */ ?>

				<?php
				/* Set Default Map Area Using First Location */
				$map_area_lat = isset( $locations[0]['google_map']['lat'] ) ? $locations[0]['google_map']['lat'] : '';
				$map_area_lng = isset( $locations[0]['google_map']['lng'] ) ? $locations[0]['google_map']['lng'] : '';
				?>

				<script>
				jQuery( document ).ready( function($) {

					/* Do not drag on mobile. */
					var is_touch_device = 'ontouchstart' in document.documentElement;

					var map = new GMaps({
						el: '#google-map',
						lat: '<?php echo $map_area_lat; ?>',
						lng: '<?php echo $map_area_lng; ?>',
						scrollwheel: false,
						draggable: ! is_touch_device
					});

					/* Map Bound */
					var bounds = [];

					<?php /* For Each Location Create a Marker. */
					foreach( $locations as $location ){
						$name = $location['location_name'];
						$addr = $location['location_address'];
						$map_lat = $location['google_map']['lat'];
						$map_lng = $location['google_map']['lng'];
						?>
						/* Set Bound Marker */
						var latlng = new google.maps.LatLng(<?php echo $map_lat; ?>, <?php echo $map_lng; ?>);
						bounds.push(latlng);
						/* Add Marker */
						map.addMarker({
							lat: <?php echo $map_lat; ?>,
							lng: <?php echo $map_lng; ?>,
							title: '<?php echo $name; ?>',
							infoWindow: {
								content: '<p><?php echo $name; ?></p>'
							}
						});
					<?php } //end foreach locations ?>

					/* Fit All Marker to map */
					map.fitLatLngBounds(bounds);

					/* Make Map Responsive */
					var $window = $(window);
					function mapWidth() {
						var size = $('.google-map-wrap').width();
						$('.google-map').css({width: size + 'px', height: (size/2) + 'px'});
					}
					mapWidth();
					$(window).resize(mapWidth);

				});
				</script>

				

			</div><!-- .entry-content -->

		</article>
<div class="map-list">

					<h3><span>View in Google Map</span></h3>

					<ul class="google-map-list" itemscope itemprop="hasMap" itemtype="http://schema.org/Map">

						<?php foreach( $locations as $location ){
							$name = $location['location_name'];
							$addr = $location['location_address'];
							$map_lat = $location['google_map']['lat'];
							$map_lng = $location['google_map']['lng'];
							?>
							<li>
								<a target="_blank" itemprop="url" href="<?php echo 'http://www.google.com/maps/place/' . $map_lat . ',' . $map_lng;?>"><?php echo $name; ?></a>
								<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $addr; ?></span>
							</li>
						
						<?php } //end foreach ?>

					</ul><!-- .google-map-list -->
				</div>
	</div><!-- #container -->

</body>

</html>

