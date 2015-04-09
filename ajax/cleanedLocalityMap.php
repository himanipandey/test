<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$latLongList = '0,1,2,3,4,5,6,7,8,9';
$localityId = $_REQUEST['localityId'];

$allProject = ResiProject::find('all', array('conditions' => array("latitude not in($latLongList) 
                    and longitude not in($latLongList) and locality_id = '" . $localityId . "' and status in ('Active','ActiveInCms') and version = 'Cms'"), 'order' => 'LONGITUDE,LATITUDE ASC'));
//print_r($allProject->latitude);
if (count($allProject) > 0) {
    $arrLatitude = array();
    $arrLongitude = array();
    foreach ($allProject as $val) {
        $arrLatitude[] = $val->latitude;
        $arrLongitude[] = $val->longitude;
    }

    $localityDetail = Locality::getLocalityById($localityId);

    $localityName = $localityDetail[0]->label;

    $localityLatitude = $localityDetail[0]->latitude;
    $localityLongitude = $localityDetail[0]->longitude;

    $localityLatMax = max($arrLatitude);
    $localityLongMax = max($arrLongitude);
    $localityLongMin = min($arrLongitude);
    $localityLatMin = min($arrLatitude);


//        $option = Locality::find($localityId);
//
//        $option->max_latitude = max($arrLatitude);
//        $option->max_longitude = max($arrLongitude);
//        $option->min_latitude = min($arrLatitude);
//        $option->min_longitude = min($arrLongitude);
//        $option->is_geo_boundary_clean = true;
//
//        $result = $option->save();
    ?>

    <?php
    if ($localityLatMax && $localityLongMax && $localityLongMin && $localityLatMin) {
        ?>
        <div id="map" style="width: 550px; height: 400px;">

            <script>
                // Define your locations: HTML content for the info window, latitude, longitude
                var locations = [
                    ["<h4><?php echo $localityName ?></h4>", "<?php echo $localityLatitude ?>", "<?php echo $localityLongitude ?>"]
                ];

                // Setup the different icons and shadows
                var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';

                var icons = [
                    iconURLPrefix + 'red-dot.png',
                    iconURLPrefix + 'green-dot.png',
                    iconURLPrefix + 'blue-dot.png',
                    iconURLPrefix + 'orange-dot.png',
                    iconURLPrefix + 'purple-dot.png',
                    iconURLPrefix + 'pink-dot.png',
                    iconURLPrefix + 'yellow-dot.png'
                ]
                var iconsLength = icons.length;

                var mapOptions = {
                    zoom: 15,
                    center: new google.maps.LatLng("<?php echo $localityLatitude ?>", "<?php echo $localityLongitude ?>"),
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                    panControl: true,
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    streetViewControl: true,
                    overviewMapControl: true,
                    rotateControl: true
                };

                var map = new google.maps.Map(document.getElementById('map'),
                        mapOptions);

                var infowindow = new google.maps.InfoWindow({
                    maxWidth: 160
                });

                var markers = new Array();

                var iconCounter = 0;

                // Add the markers and infowindows to the map
                for (var i = 0; i < locations.length; i++) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        map: map,
                        icon: icons[iconCounter]
                    });

                    markers.push(marker);

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infowindow.setContent(locations[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));

                    iconCounter++;
                    // We only have a limited number of possible icon colors, so we may have to restart the counter
                    if (iconCounter >= iconsLength) {
                        iconCounter = 0;
                    }
                }

                localityLatMax = <?php echo $localityLatMax ?>;
                localityLongMin = <?php echo $localityLongMin ?>;
                localityLongMax = <?php echo $localityLongMax ?>;
                localityLatMin = <?php echo $localityLatMin ?>;

                console.log(localityLatMax, localityLongMin, localityLongMax, localityLatMin);



                // Define the LatLng coordinates for the polygon's path.
                var triangleCoords = [
                    new google.maps.LatLng(localityLatMax, localityLongMin),
                    new google.maps.LatLng(localityLatMin, localityLongMin),
                    new google.maps.LatLng(localityLatMin, localityLongMax),
                    new google.maps.LatLng(localityLatMax, localityLongMax),
                ];

                // Construct the polygon.
                bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                });

                bermudaTriangle.setMap(map);



                function autoCenter() {
                    //  Create a new viewpoint bound
                    var bounds = new google.maps.LatLngBounds();


                    //  Go through each...
                    for (var i = 0; i < markers.length; i++) {
                        bounds.extend(markers[i].position);
                    }
                    //  Fit these bounds to the map
                    map.fitBounds(bounds);
                }
                autoCenter();

                //google.maps.event.trigger(map, 'resize');

                $(document).ready(function () {
                    setTimeout(function () {
                        google.maps.event.trigger(map, 'resize');
                        autoCenter();
                        // $.goMap.fitBounds();
                    }, 100)
                    $('#ok').click(function () {
                        $.ajax({
                            type: "POST",
                            url: 'ajax/cleanedLocality.php',
                            data: {localityId: <?php echo $localityId ?>},
                            success: function (msg) {
                                if (msg) {
                                    if (msg.length > 823) {
                                        alert("New locality boundaries have been saved");
                                        jQuery.fancybox.close();
                                    }
                                    else {
                                        alert("No valid record in project table!");
                                    }
                                    $(".latLong").remove();
                                    $(msg).insertBefore($('.save_row'));
                                }
                            }
                        });
                    });
                    $('#cancel').click(function () {
                        jQuery.fancybox.close();
                    });

                });
            </script>



        </div>
        <div id="actions" style="text-align: center; padding-top: 15px;">
            <input type="button" id="ok" value="OK" style="border:1px solid #c2c2c2;height:30px;width:50px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;"/>
            <input type="button" id="cancel" value="Cancel" style="border:1px solid #c2c2c2;height:30px;width:80px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;"/>
        </div>
        <?php
    } else {
        echo "No valid record in project table!";
    }
}
?>
