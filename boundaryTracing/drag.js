
var overlay;
var geocoder;
var map;
var flag = 0;
var fl = 0;
var poly;
var poly1;
var arr = [];
var arrMarker = [];
var p;
var overlay;
var latlngArray = [];
var PointArray = [];

var iniMarkerA;
var iniMarkerB;
var city = "";
var addr = "";
var boundary = "new";
var pixelArray = [];
var flagShow = 0;
var drawingFlag = false;
var PolyLineArray = [];
var BoundaryType = "";
var EncodeLatLong = "check";
var encodeString = "";

DebugOverlay.prototype = new google.maps.OverlayView();



var url = window.location.search;
url = url.replace("?", ''); // remove the ?
var urlElement = url.split(',');

var id = "";
var getLat = "";
var getLong = "";
var sessionID = "";
var BaseUrl = "http://";
sessionID = urlElement[0];
BaseUrl = BaseUrl + urlElement[1] + ".com";
getLat = urlElement[2];
getLong = urlElement[3];
id = urlElement[4];


var t = document;
var theNewScript = t.createElement("script");
theNewScript.type = "text/javascript";
theNewScript.src = BaseUrl+"/boundaryTracing/jquery-1.8.3.min.js";
document.getElementsByTagName("head")[0].appendChild(theNewScript);


function initialize() {

  var getData = [];
  var XBoundary = 0.0;
  var YBoundary = 0.0;
  var BoundaryArray = [];
  var minDistance = 99999.0;
  var SecondminDistance = 99999.0;
  var LatNear1 = '';
  var LngNear1 = '';
  var LatNear2 = '';
  var LngNear2 = '';

  console.log(sessionID);

  if(getLat == '0.000000' || getLong == '0.000000' || getLat == ''|| getLong == 'null' || getLat == 'null')  {
    getLat = '28.580464';
    getLong = '77.3175427';
  }


  var place = "";
  var slaceFlag = 0;
  
  $.ajax({
    url: '/saveNearPlacePriority.php',          
    type: "GET",
    data: {id : id, task: 'GetMapdataType'},

    success:function(msg){
      BoundaryType = msg.trim();
    }                  
  });
  var waitForLoad = function () {
      if (typeof jQuery != "Undefined") {
          //console.log('define');

          $.ajax({
            url: '/saveNearPlacePriority.php',          
            type: "GET",
            data: {id : id, place : place, task: 'GetMapdataFromCMs'},

            success:function(msg){
              console.log("WORKing!!!");
              var coorArray = [];
              if(BoundaryType == "polyline" || BoundaryType == "polygon" || BoundaryType == "point")  {
                var polygons = [];
                
                msg = $.parseJSON(msg);
                getData = msg[0];
                console.log(getData);
                
                 
                var cnt = 0;
                var divideCnt = 1;
                
                
                if(msg[0] == "" || msg[0] == 'null' || msg[0] == 'check') {

                } else {
                    $.each(msg, function(k,v) {
                        getData = v;
                        var langitude = [];
                        var longitude = [];
                        v = $.parseJSON(v);
                        var coorArrayTemp = [];
                        $.each(v, function(k2,v2) {
                            XBoundary = XBoundary + parseFloat(v2['k']);
                            YBoundary = YBoundary + parseFloat(v2['D']);

                            coorArrayTemp.push({0: v2['A'] ,1: v2['F']});
                            BoundaryArray.push({0: v2['A'] ,1: v2['F']});
                            divideCnt++;
                        });

                        coorArray.push(coorArrayTemp);
                        cnt++;
                    });

                    
                    console.log(coorArray);
                    XBoundary = XBoundary / divideCnt;
                    YBoundary = YBoundary / divideCnt;   
                }
                
              }

              var centerMap = new google.maps.LatLng(getLat, getLong);  
              var mapOptions = { //  28.580464,77.3175427
                zoom: 15,
                center:centerMap,
                mapTypeId: google.maps.MapTypeId.TERRAIN
              };

              var bounds = new google.maps.LatLngBounds();
              map = new google.maps.Map(document.getElementById('map'),
                      mapOptions);
              if(BoundaryType == "point" && coorArray[0].length == 1)  {
                  var pointMap = new google.maps.LatLng(coorArray[0][0][0], coorArray[0][0][1]);
                  var marker = new google.maps.Marker({
                      position: pointMap,
                      title: "Property!"
                  });

                  // define map
                  //map = new google.maps.Map(document.getElementById('map'), mapOptions);

                  marker.setMap(map);  
              }

              // ************** POLYGON 1 **************************************************
              //console.log("BT = "+BoundaryType);
              if(BoundaryType == 'polygon') {
                  var triangleCoords = new Array();
                  var element1;
                  var element2;
                  for (i = 0; i < coorArray[0].length; i++) {  
                    element1 = coorArray[0][i][0];
                    element2 = coorArray[0][i][1];
                    triangleCoords.push(new google.maps.LatLng(element1, element2));
                  }



                  var bermudaTriangle = [];
                  bermudaTriangle[0] = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: '#GG5555',
                    strokeOpacity: 0.8,
                    strokeWeight: 1,
                    fillColor: '#9F8E8E',
                    fillOpacity: 0.35
                  });

                  /*var res = encodeLatLngPolygon(triangleCoords);
                  console.log("RES = "+res);

                  function encodeLatLngPolygon(array) {

                      var polyOptions = {
                      strokeColor: '#000000',
                      strokeOpacity: 1.0,
                      strokeWeight: 3
                        }
                        poly = new google.maps.Polyline(polyOptions);

                      var path = poly.getPath();

                      for(var i=0;i<array.length;i++) {
                          var xyz = new google.maps.LatLng(parseFloat(array[i][0]).toFixed(2), parseFloat(array[i][1]).toFixed(2));
                          path.push(xyz);            

                      }

                      var code = google.maps.geometry.encoding.encodePath(path)

                      return code;
                  }*/

                 // var EncodeLatLong = google.maps.geometry.encoding.encodePath(triangleCoords);

                 // console.log("Encode = "+EncodeLatLong);


                  // ************** POLYGON Next **************************************************

                  for(var j = 1; j < cnt; j++)  {
                    addNewPoly(coorArray[j]);
                  }

                  for(var i=0,l=bermudaTriangle.length;i<l;i++) {
                    bermudaTriangle[i].setMap(map);
                    infoWindow = new google.maps.InfoWindow();
                  }
              } else if(BoundaryType == 'polyline') {
                  var triangleCoords = new Array();
                  var element1;
                  var element2;
                 
                  for (i = 0; i < coorArray[0].length; i++) {  
                    element1 = coorArray[0][i][0];
                    element2 = coorArray[0][i][1];
                    triangleCoords.push(new google.maps.LatLng(element1, element2));
                  }

                  var flightPath = new google.maps.Polyline({
                    path: triangleCoords,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 1
                  });
                  flightPath.setMap(map);
                  
                  console.log("Completed!!");

              }

              google.maps.event.addListener(map, 'mousemove',
                  function(e) {
                  }
              );

              google.maps.event.addListener(bermudaTriangle[0], 'mouseout',
                  function() {
                      console.log("Out");
                  }
              );

            function addNewPoly(coorArray2) {

                var triangleCoords = new Array();
                var element1;
                var element2;

                for (i = 0; i < coorArray2.length; i++) { 
                    element1 = coorArray2[i][0];
                    element2 = coorArray2[i][1];
                    triangleCoords.push(new google.maps.LatLng(element1, element2));
                }

                // Construct the polygon.
                bermudaTriangle[bermudaTriangle.length] = new google.maps.Polygon({
                  paths: triangleCoords,
                  strokeColor: '#GG5555',
                  strokeOpacity: 0.8,
                  strokeWeight: 1,
                  fillColor: '#9F8E8E',
                  fillOpacity: 0.35
                });

                return bermudaTriangle;

            }
    
            function showArrays(event) {

                // Since this polygon has only one path, we can call getPath()
                // to return the MVCArray of LatLngs.
                var vertices = this.getPath();

                var contentString = '<b>Bermuda Triangle polygon</b><br>' +
                    'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
                    '<br>';

                // Iterate over the vertices.
                for (var i =0; i < vertices.getLength(); i++) {
                  var xy = vertices.getAt(i);
                  contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
                      xy.lng();
                }

                // Replace the info window's content and position.
                infoWindow.setContent(contentString);
                infoWindow.setPosition(event.latLng);

                infoWindow.open(map);
            }

              google.maps.event.addListener(map, 'click', function() {
                if(flag != 1) {
                    flag = 1;
                    console.log(map.getBounds());
                    var bounds = map.getBounds();

                    initMarkerA = bounds.getSouthWest();
                    initMarkerB = bounds.getNorthEast();
                    console.log("Selected File Name Is "+ document.getElementById('inputFiles').value);
                    srcImage = document.getElementById('inputFiles').value;
                     
                    overlay = new DebugOverlay(bounds, srcImage, map);
                    var markerA = new google.maps.Marker({
                              //position: swBound,
                              position: bounds.getSouthWest(),
                              map: map,
                              draggable:true
                    });

                    var markerB = new google.maps.Marker({
                        position: bounds.getNorthEast(),
                        map: map,
                        draggable:true
                    });
                     
                    google.maps.event.addListener(overlay,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        console.log("overlay drag event fired");
                        overlay.updateBounds(newBounds);
                    });
                     
                    google.maps.event.addListener(markerA,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                       

                       
                        var x = newPointB.lat() + (newPointA.lat() - initMarkerA.lat());
                        var y = newPointB.lng() + (newPointA.lng() - initMarkerA.lng());
                        newPointB = new google.maps.LatLng(x, y);
                      //  markerB.setPosition(newPointB);
                 

                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        overlay.updateBounds(newBounds);
                        console.log("markerA drag event fired");
                    });

                    google.maps.event.addListener(markerB,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        overlay.updateBounds(newBounds);
                        console.log("markerB drag event fired");
                    });

                      google.maps.event.addListener(markerA, 'dragend', function () {
                        /*
                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                        var x = newPointB.lat() + (newPointA.lat() - initMarkerA.lat()); 
                        var y = newPointB.lng() + (newPointA.lng() - initMarkerA.lng());
                        newPointB = new google.maps.LatLng(x, y);  
                        markerB.setPosition(newPointB); 
                       */ 
                            
                          var newPointA = markerA.getPosition();
                          var newPointB = markerB.getPosition();
                          console.log("point1"+ newPointA);
                          console.log("point2"+ newPointB);
                          console.log("markerA dragend event fired");  
                      });

                      google.maps.event.addListener(markerB, 'dragend', function () {
                          var newPointA = markerA.getPosition();
                          var newPointB = markerB.getPosition();
                          console.log("point1"+ newPointA);
                          console.log("point2"+ newPointB);
                          console.log("markerB dragend event fired");
                      });
                }
              });
            },
          });
      } else {
          console.log('Undefine');
          window.setTimeout(waitForLoad, 1000);
      }
  };
  window.setTimeout(waitForLoad, 0);
 //init(map);
}




function initializeErase() {

  /*var t = document;
  var theNewScript = t.createElement("script");
  theNewScript.type = "text/javascript";
  theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
  document.getElementsByTagName("head")[0].appendChild(theNewScript);*/

  var getData = [];
  var XBoundary = 0.0;
  var YBoundary = 0.0;
  var BoundaryArray = [];

  if(getLat == 'null' || getLong == '0.000000' || getLat == ''|| getLong == 'null')  {
    getLat = '28.580464';
    getLong = '77.3175427';
  }

  var place = "";
  var slaceFlag = 0;
  
  console.log("id = "+id);

  $.ajax({
    url: '/saveNearPlacePriority.php',          
    type: "POST",
    data: { sessionID: sessionID, task: 'EmptyLandmark_map_data'},

    success:function(msg){
      console.log(msg);
    }                  
  });

  $.ajax({
    url: '/saveNearPlacePriority.php',          
    type: "GET",
    data: {id : id, task: 'GetMapdataType'},

    success:function(msg){
      BoundaryType = msg.trim();
      console.log('msgNEW = ',BoundaryType);
    }                  
  });
  
  var waitForLoad = function () {
      if (typeof jQuery != "Undefined") {
          console.log('define');
          $.ajax({
            url: '/saveNearPlacePriority.php',          
            type: "GET",
            data: {id : id, task: 'GetMapdataFromCMs'},

            success:function(msg){
              var polygons = [];
              var coorArray = [];
              msg = $.parseJSON(msg);
              console.log('msg = ',msg);
              getData = msg[0];
              
               
              var cnt = 0;
              var divideCnt = 1;
                  
              var centerMap = new google.maps.LatLng(getLat,getLong);  
              var mapOptions = { //  52.081336668, 5.124039573
                zoom: 15,
                center:centerMap,
                mapTypeId: google.maps.MapTypeId.TERRAIN
              };

              var bounds = new google.maps.LatLngBounds();


              var marker = new google.maps.Marker({
                  position: centerMap,
                  title:"PropTiger!"
              }); 

              // To add the marker to the map, call setMap();
               

              // define map
              map = new google.maps.Map(document.getElementById('map'),
                  mapOptions);
             //saveLatLngmarker.setMap(map); 
              // ************** POLYGON 1 **************************************************
              console.log("BT = "+BoundaryType);
              if(BoundaryType == 'polygon') {
                var triangleCoords = new Array();
                var element1;
                var element2;
               
                for (i = 1; i < coorArray[0].length; i++) {  
                  element1 = coorArray[0][i][0];
                  element2 = coorArray[0][i][1];
                  triangleCoords.push(new google.maps.LatLng(element1, element2));
                }

                var bermudaTriangle = [];
                bermudaTriangle[0] = new google.maps.Polygon({
                  paths: triangleCoords,
                  strokeColor: '#GG5555',
                  strokeOpacity: 0.8,
                  strokeWeight: 1,
                  fillColor: '#9F8E8E',
                  fillOpacity: 0.35
                });


                // ************** POLYGON Next **************************************************

                for(var j = 1; j < cnt; j++)  {
                  addNewPoly(coorArray[j]);
                }

                for(var i=0,l=bermudaTriangle.length;i<l;i++) {
                  bermudaTriangle[i].setMap(map);
                  infoWindow = new google.maps.InfoWindow();
                }
              } else if(BoundaryType == 'polyline') {
                  var triangleCoords = new Array();
                  var element1;
                  var element2;
                 
                  for (i = 1; i < coorArray[0].length; i++) {  
                    element1 = coorArray[0][i][0];
                    element2 = coorArray[0][i][1];
                    triangleCoords.push(new google.maps.LatLng(element1, element2));
                  }

                  var flightPath = new google.maps.Polyline({
                    path: triangleCoords,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 1
                  });

                  flightPath.setMap(map);
                  console.log("Completed!!");
              }

              google.maps.event.addListener(bermudaTriangle[0], 'mousemove',
                  function(e) {
                      console.log("in"); 
                  }
              );

              google.maps.event.addListener(bermudaTriangle[0], 'mouseout',
                  function() {
                      console.log("Out");
                  }
              );

            function addNewPoly(coorArray2) {

                var triangleCoords = new Array();
                var element1;
                var element2;

                for (i = 0; i < coorArray2.length; i++) { 
                    element1 = coorArray2[i][0];
                    element2 = coorArray2[i][1];
                    triangleCoords.push(new google.maps.LatLng(element1, element2));
                }

                // Construct the polygon.
                bermudaTriangle[bermudaTriangle.length] = new google.maps.Polygon({
                  paths: triangleCoords,
                  strokeColor: '#GG5555',
                  strokeOpacity: 0.8,
                  strokeWeight: 1,
                  fillColor: '#9F8E8E',
                  fillOpacity: 0.35
                });

                return bermudaTriangle;

            }
    
            function showArrays(event) {

                // Since this polygon has only one path, we can call getPath()
                // to return the MVCArray of LatLngs.
                var vertices = this.getPath();

                var contentString = '<b>Bermuda Triangle polygon</b><br>' +
                    'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
                    '<br>';

                // Iterate over the vertices.
                for (var i =0; i < vertices.getLength(); i++) {
                  var xy = vertices.getAt(i);
                  contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
                      xy.lng();
                }

                // Replace the info window's content and position.
                infoWindow.setContent(contentString);
                infoWindow.setPosition(event.latLng);

                infoWindow.open(map);
            }

              google.maps.event.addListener(map, 'click', function() {
                if(flag != 1) {
                    flag = 1;
                    console.log(map.getBounds());
                    var bounds = map.getBounds();

                    initMarkerA = bounds.getSouthWest();
                    initMarkerB = bounds.getNorthEast();
                    console.log("Selected File Name Is "+ document.getElementById('inputFiles').value);
                    srcImage = document.getElementById('inputFiles').value;
                     
                    overlay = new DebugOverlay(bounds, srcImage, map);
                    var markerA = new google.maps.Marker({
                              //position: swBound,
                              position: bounds.getSouthWest(),
                              map: map,
                              draggable:true
                    });

                    var markerB = new google.maps.Marker({
                        position: bounds.getNorthEast(),
                        map: map,
                        draggable:true
                    });
                     
                    google.maps.event.addListener(overlay,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        console.log("overlay drag event fired");
                        overlay.updateBounds(newBounds);
                    });
                     
                    google.maps.event.addListener(markerA,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                       

                       
                        var x = newPointB.lat() + (newPointA.lat() - initMarkerA.lat());
                        var y = newPointB.lng() + (newPointA.lng() - initMarkerA.lng());
                        newPointB = new google.maps.LatLng(x, y);
                      //  markerB.setPosition(newPointB);
                 

                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        overlay.updateBounds(newBounds);
                        console.log("markerA drag event fired");
                    });

                    google.maps.event.addListener(markerB,'drag',function(){

                        var newPointA = markerA.getPosition();
                        var newPointB = markerB.getPosition();
                        var newBounds =  new google.maps.LatLngBounds(newPointA, newPointB);
                        overlay.updateBounds(newBounds);
                        console.log("markerB drag event fired");
                    });

                      google.maps.event.addListener(markerA, 'dragend', function () {

                            
                          var newPointA = markerA.getPosition();
                          var newPointB = markerB.getPosition();
                          console.log("point1"+ newPointA);
                          console.log("point2"+ newPointB);
                          console.log("markerA dragend event fired");  
                      });

                      google.maps.event.addListener(markerB, 'dragend', function () {
                          var newPointA = markerA.getPosition();
                          var newPointB = markerB.getPosition();
                          console.log("point1"+ newPointA);
                          console.log("point2"+ newPointB);
                          console.log("markerB dragend event fired");
                      });
                }
              });
            },
          });
      } else {
          console.log('Undefine');
          window.setTimeout(waitForLoad, 1000);
      }
  };
  window.setTimeout(waitForLoad, 0);
 //init(map);
}



function utilinit(){
  //var latlng = new google.maps.LatLng(28.6707515716552730,77.1130905151367200); 
  //getLocation(latlng); 
  init(map);

  
  //mywindow = window.open("http://cms.localhost.com/boundaryTracing/popup.html", "_blank", "toolbar=no, scrollbars=no, resizable=yes, top=300, left=500, width=200, height=100");
}

function getLocation(latlng){

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var loc = getCountry(results);
                    alert("location is::"+loc);
                }
            }
        });

}

function getCountry(results)
{
    for (var i = 0; i < results[0].address_components.length; i++)
    {
        var shortname = results[0].address_components[i].short_name;
        var longname = results[0].address_components[i].long_name;
        var type = results[0].address_components[i].types;
        if (type.indexOf("country") != -1)
        {
            if (!isNullOrWhitespace(shortname))
            {
                return shortname;
            }
            else
            {
                return longname;
            }
        }
    }

}

function isNullOrWhitespace(text) {
    if (text == null) {
        return true;
    }
    return text.replace(/\s/gi, '').length < 1;
}

function setMap(){
  console.log("Hello");
  var getCity = "";
  var fileName = document.getElementById('inputFiles').value; 
  var srcImage = fileName;
  var latlng = fileName.split(",");
  var getaddr = fileName.split("\\");
  getCity = getaddr[2].split(",")[0];
  city = getCity;
  flag = 0;
  //var x = parseFloat(latlng[1]);
  var x = parseFloat(latlng[latlng.length-2]);
  //var y1 = latlng[2].split('.');
  var y1 = latlng[latlng.length-1].split('.');
  y1[1] = '.' + y1[1];
  var y = parseFloat(y1[0]);
  var f = parseFloat(y1[1]);
  y = y + f;
  //console.log(x+" "+y);
  addr = x+", "+y;
  console.log(city);
  console.log(addr);
  map.setCenter(new google.maps.LatLng(x,y));
}

function DebugOverlay(bounds, image, map) {

  this.bounds_ = bounds;
  this.image_ = image;
  this.map_ = map;
  this.div_ = null;
  this.draggable=true;
  this.setMap(map);
}
var opacity = '0.5';
var op = 0.0; 
var check = 0;
function imageOptacityIncrease(){
  console.log("parseBefore");
  op = parseFloat(opacity) + 0.2;
  
  opacity = op.toString();
  console.log(opacity);
  check = 1;
  //img.style.opacity = opacity;
  DebugOverlay.prototype.onAdd();

}

function imageOptacityDecrease(){
  console.log("parseBefore");
  op = parseFloat(opacity) - 0.2;
  
  opacity = op.toString();
  console.log(opacity);
  check = 1;
  //img.style.opacity = opacity;
  DebugOverlay.prototype.onAdd();

}
var img;
DebugOverlay.prototype.onAdd = function() {

 //img= document.createElement('img');
 if(check != 1){
  var div = document.createElement('div');
  div.style.borderStyle = 'none';
  div.style.borderWidth = '0px';
  div.style.position = 'absolute';
  img = document.createElement('img');
  img.src = this.image_;
  img.style.width = '100%';
  img.style.height = '100%';
  img.style.opacity = opacity;  
  img.style.position = 'absolute';
  div.appendChild(img);
  this.div_ = div;
  var panes = this.getPanes();
  panes.overlayLayer.appendChild(div);
}
  
 img.style.opacity = opacity;
  
};

DebugOverlay.prototype.draw = function() {
  var overlayProjection = this.getProjection();
  var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());
  var div = this.div_;
  div.style.left = sw.x + 'px';
  div.style.top = ne.y + 'px';
  div.style.width = (ne.x - sw.x) + 'px';
  div.style.height = (sw.y - ne.y) + 'px';
};

DebugOverlay.prototype.updateBounds = function(bounds){
    this.bounds_ = bounds;
    this.draw();
};

DebugOverlay.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);
  this.div_ = null;
};

function show_popup() {
  console.log("Calling");
  var p = window.createPopup();
}

var drawingManager;
function startTracing(){

  if(drawingFlag == true){
    drawingManager.setMap(null); 
  }
   drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.MARKER,
        google.maps.drawing.OverlayType.CIRCLE,
        google.maps.drawing.OverlayType.POLYGON,
        google.maps.drawing.OverlayType.POLYLINE,
        google.maps.drawing.OverlayType.RECTANGLE
      ]
    },
    markerOptions: {
      //icon: 'images/download.png';
      /*marker = new google.maps.Marker({
          position: latlng,
          map:map,
          draggable:true,
          animation: google.maps.Animation.DROP
      });*/
      //marker = new google.maps.Marker({position: event.latLng, map: map});
      //google.maps.event.addListener(marker, 'click', toggleBounce);
    },
    circleOptions: {
      fillColor: '#ffff00',
      fillOpacity: 1,
      strokeWeight: 5,
      clickable: false,
      editable: true,
      zIndex: 1
    },
    polylineOptions: {
      fillColor: '#00ff00',
      fillOpacity: .2,
      strokeWeight: 2,
      strokeOpacity: 0.5,
      clickable: true,
      editable: true
    }
  });

  google.maps.event.addListener(drawingManager, 'markercomplete', function(point) {
      //console.log(point['position']['A']);
      var latPoint = point['position']['A'];
      var lngPoint = point['position']['F'];
      PointArray.push(point['position']);
      //saveLatLng('point', PointArray);
      //point['position'];
      //var radius = circle.getRadius();
  });

  google.maps.event.addListener(drawingManager, 'polylinecomplete', function(line) {
    p = line;
    //alert(line.getPath().getArray().toString());
    latlngArray = line.getPath().getArray();
    console.log(latlngArray[0]);

    var convertToPixelNEW = function () {
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {};
        overlay.setMap(map);
        var Xpixels = "";
        var Ypixels = "";
        for(var i = 0; i < latlngArray.length; i++){
          pixelArray[i] = overlay.getProjection().fromLatLngToContainerPixel(latlngArray[i]);
        }
    }

    window.setTimeout(convertToPixelNEW, 0);

    var triangleCoords = new Array();

    for (i = 0; i < latlngArray.length; i++) {  
        var element1 = latlngArray[i]['A'];
        var element2 = latlngArray[i]['F'];
        
        triangleCoords.push(new google.maps.LatLng(element1, element2));
    }
    encodeString = google.maps.geometry.encoding.encodePath(triangleCoords);
    console.log("Encode = "+encodeString);

    /*var t = document;
    var theNewScript = t.createElement("script");
    theNewScript.type = "text/javascript";
    theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
    document.getElementsByTagName("head")[0].appendChild(theNewScript);*/


    var waitForLoadNEW = function () {
      var JsonSVG = JSON.stringify(pixelArray);
      console.log("J = "+JsonSVG);
      if (typeof jQuery != "undefined") {
          console.log('define');
          $.ajax({
            url: '/saveNearPlacePriority.php',          
            type: "POST",
            data: {sessionID : sessionID, JsonSVG: JsonSVG, encodeString : encodeString, task: 'saveEncodedBoundary'},

            success:function(msg){
              console.log(msg);  
            },
          });
      } else {
          console.log('undefine');
          window.setTimeout(waitForLoadNEW, 1000);
      }
    };

    window.setTimeout(waitForLoadNEW, 0);

    var latitudePolyline = "";
    var longitudePolyline = "";
    
    var waitForLoad = function () {
        if (typeof jQuery != "undefined") {
            console.log('define');
            var jsonString = JSON.stringify(latlngArray);
            $.ajax({
              url: '/saveNearPlacePriority.php',          
              type: "GET",
              //dataType: "json",
              data: { latlngArray: jsonString , task: 'GetLength'},

              success:function(msg){
                console.log(msg);
                msg = $.parseJSON(msg);

                $.each(msg, function(k,v) {
                  console.log(v['A']);   
                  console.log(v['F']);
                });   
              },
            });
        } else {
            console.log('undefine');
            window.setTimeout(waitForLoad, 1000);
        }
    };
    //window.setTimeout(waitForLoad, 1);


    //console.log("Lat Long = ",latlngArray[0]['k'],latlngArray[0]['D']);
    google.maps.event.addListener(line, 'dragend', function() {
    console.log("chamged!!!!");
      //alert(line.getPath().getArray().toString());
    });
  });

  poly = new google.maps.Polyline(drawingManager[5]);
  drawingManager.setMap(map);
  var a = poly.getPath();
  drawingFlag = true; 
}

function savePoint()  {
  var Type = "point";
  saveLatLng(Type, PointArray);
}

function path1(){
  var Type = "polyline";
  //console.log("EEEE= " + encodeString);
  saveLatLng(Type, p.getPath().getArray()); 
  //return repoints;
} 

function saveLatLngCheck(){

    var waitForLoad = function () {
        var JsonSVG = JSON.stringify(pixelArray);
        console.log(JsonSVG);
        if (typeof jQuery != "undefined") {
            console.log('define');
            $.ajax({
              url: '/saveNearPlacePriority.php',          
              type: "POST",
              data: {sessionID : sessionID, EncodeLatLong : null, center_of_boundary : null, boundary: null , JsonSVG : null, Type : null, task: 'MapdataSendCMs'},

              success:function(msg){
                console.log('Encode = '+msg);
                console.log("success !!");  
              },
            });
        } else {
            console.log('undefine');
            window.setTimeout(waitForLoad, 1000);
        }
    };
    window.setTimeout(waitForLoad, 100);
}


function saveLatLng(Type, pts){
   latlngArray = pts;
   console.log(pts);
   var convertToPixelNEW = function () {
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {};
        overlay.setMap(map);
        var Xpixels = "";
        var Ypixels = "";
        for(var i = 0; i < latlngArray.length; i++){
          pixelArray[i] = overlay.getProjection().fromLatLngToContainerPixel(latlngArray[i]);
        }
    }
    if (Type != 'point')  {
        window.setTimeout(convertToPixelNEW, 0);   
    }
   
   var count = 0;
   var lang = 0.0;
   var longi = 0.0;
   console.log(pts);
   var center_of_boundary = "";
   var saveCms = "";

   if(pts.length != 0)  {

   
   for (var i = 0; i < pts.length; i++)  {
      lang = lang + pts[i].A;
      longi = longi + pts[i].F;
      count++;
   }
   if (count != 0) {
    lang = lang / count;
    longi = longi / count;
   }
   console.log("Avg = "+lang);
   console.log("Avg = "+ longi);

   var center_lat_long = [];
   center_lat_long.push({0: lang ,1: longi});

   saveCms = JSON.stringify(latlngArray);
   var center_of_boundary = JSON.stringify(center_lat_long);
   console.log('c = '+saveCms);
    }
   //console.log(saveCms);
    /*var t = document;
    var theNewScript = t.createElement("script");
    theNewScript.type = "text/javascript";
    theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
    document.getElementsByTagName("head")[0].appendChild(theNewScript);*/

    var waitForLoad = function () {
        var JsonSVG = JSON.stringify(pixelArray);
        console.log("JS = "+JsonSVG);
        if (typeof jQuery != "undefined") {
            console.log('define');
            $.ajax({
              url: '/saveNearPlacePriority.php',          
              type: "POST",
              data: {sessionID : sessionID, EncodeLatLong : EncodeLatLong, center_of_boundary : center_of_boundary, boundary: saveCms, JsonSVG : JsonSVG, Type : Type, task: 'MapdataSendCMs'},

              success:function(msg){
                //console.log('Encode = '+msg);
                console.log("success !!");  
              },
            });
        } else {
            console.log('undefine');
            window.setTimeout(waitForLoad, 100);
        }
    };
    window.setTimeout(waitForLoad, 0);
    

   /*localStorage.setItem('latlngArray', JSON.stringify(latlngArray));
     
   localStorage["latlngArray"] = JSON.stringify(latlngArray);
   var storedNames = JSON.parse(localStorage["latlngArray"]);
   /*console.log( "localStorage  "+storedNames[0].lat; */
}


function clearPolyline () {
  initializeErase();
  //poly.setMap(null);
}

function restorePolyline () {
  poly.setMap(map);
}



function convertToPixel(){
  overlay = new google.maps.OverlayView();
  overlay.draw = function () {};
  overlay.setMap(map);
  var Xpixels = "";
  var Ypixels = "";
  for(var i = 0; i < latlngArray.length; i++){
    pixelArray[i] = overlay.getProjection().fromLatLngToContainerPixel(latlngArray[i]);
  }


  var myJsonString = JSON.stringify(pixelArray);
  //console.log(myJsonString);

  /*var t = document;
  var theNewScript = t.createElement("script");
  theNewScript.type = "text/javascript";
  theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
  document.getElementsByTagName("head")[0].appendChild(theNewScript);

  var waitForLoad = function () {
      if (typeof jQuery != "undefined") {
          console.log('define ');
          $.ajax({
            url: '/saveNearPlacePriority.php',          
            type: "POST",
            data: {Pixels: myJsonString, task: 'PixeldataSendCMs'},

            success:function(msg){
              console.log(msg);
              console.log("success !!");  
            },
          });
      } else {
          console.log('undefine');
          window.setTimeout(waitForLoad, 1000);
      }
  };
  window.setTimeout(waitForLoad, 1000);*/
    
}
