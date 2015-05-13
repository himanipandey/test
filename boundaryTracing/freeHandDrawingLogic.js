
var theArrayofLatLng = [];
var x = 5;
var FreeHandPoly;
var mywindow;
var encodeString = "";

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


var toggleMapOptions= function(map, status){
            status = status ? true : false;
             map.setOptions({
                draggable: status,
                scrollwheel: status,
                disableDoubleClickZoom: status
            });
}

var init = function(map){
            var self = this;
            google.maps.event.addListener(map, 'mousedown', function (clickEvent) {

                var polyline_obj = self.polyline_obj;

                toggleMapOptions(map, false);

                polyline_obj  = new google.maps.Polyline({
                                map: map,
                                strokeColor: '#2691ec',
                                strokeOpacity: 0.5,
                                strokeWeight: 2,
                                zIndex: 1,
                                clickable: false
                            });


                google.maps.event.addListener(map, 'mousemove', function (dragEvent) {
                    polyline_obj.getPath().push(dragEvent.latLng);
                    self.polyline_obj = polyline_obj;
                });


                google.maps.event.addListener(map, 'mouseup', function (clickEvent) {

                    google.maps.event.clearListeners(map, 'mousemove');

                    var path = polyline_obj.getPath();
                    polyline_obj.set('visible', false);
                    polyline_obj.setMap(null);


                    if((path && path.length<3)){
                        return;
                    }

                    map.setOptions({draggableCursor:'null'});

                    theArrayofLatLng = path.j;
                    theArrayofLatLng = simplifyPolygon(theArrayofLatLng, 100);
                    
                    toggleMapOptions(map, true);

                    google.maps.event.clearListeners(map, 'mousedown');

                    var polyOptions = {
                        map: map,
                        fillOpacity: 0,
                        strokeColor: '#2691ec',
                        strokeOpacity: 0.5,
                        strokeWeight: 2,
                        clickable: false,
                        zIndex: 1,
                        suppressUndo: true,
                        path:theArrayofLatLng,
                        editable: true
                    }

                    
                    // draw polygon
                    FreeHandPoly =new google.maps.Polygon(polyOptions);
                    theArrayofLatLng=FreeHandPoly.getPath(); 
                    var TempArray = [];
                    TempArray = theArrayofLatLng.getArray();

                    var triangleCoords = new Array();

                    for (i = 0; i < TempArray.length; i++) {  
                        var element1 = TempArray[i]['A'];
                        var element2 = TempArray[i]['F'];
                        
                        triangleCoords.push(new google.maps.LatLng(element1, element2));
                    }
                    encodeString = google.maps.geometry.encoding.encodePath(triangleCoords);
                    console.log("Encode = "+encodeString);


                    /*var t = document;
                    var theNewScript = t.createElement("script");
                    theNewScript.type = "text/javascript";
                    theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
                    document.getElementsByTagName("head")[0].appendChild(theNewScript);*/
                    
                    var convertToPixelNEW = function () {
                        overlay = new google.maps.OverlayView();
                        overlay.draw = function () {};
                        overlay.setMap(map);
                        var Xpixels = "";
                        var Ypixels = "";
                        for(var i = 0; i < TempArray.length; i++){
                          pixelArray[i] = overlay.getProjection().fromLatLngToContainerPixel(TempArray[i]);
                        }
                    }

                    window.setTimeout(convertToPixelNEW, 0);



                    var waitForLoadNEW = function () {
                      var JsonSVG = JSON.stringify(pixelArray);
                      if (typeof jQuery != "undefined") {
                          console.log('define');
                          $.ajax({
                            url: '/saveNearPlacePriority.php',          
                            type: "POST",
                            data: {sessionID: sessionID, JsonSVG: JsonSVG, encodeString : encodeString, task: 'saveEncodedBoundary'},

                            success:function(msg){
                              console.log(msg);  
                            },
                          });
                      } else {
                          console.log('undefine');
                          window.setTimeout(waitForLoadNEW, 1000);
                      }
                    };

                    window.setTimeout(waitForLoadNEW, 100);
                    
                   

                    //theArrayofLatLng.push({k: -1 ,D: -1});
                    self.currentPolygon = FreeHandPoly;
                    google.maps.event.addListener(FreeHandPoly.getPath(), 'set_at', function(e) {
                        //here this has changed path
                        //saveLatLng(e);
                        theArrayofLatLng = FreeHandPoly.getPath();
                        console.log(theArrayofLatLng);
                        
                        //console.log("e");
 

                    });

                    google.maps.event.addListener(FreeHandPoly.getPath(), 'insert_at', function(eve) {
                        //here this has changed path
                        console.log("eve");
                        //saveLatLng(eve);
                        theArrayofLatLng = FreeHandPoly.getPath();    
                    });

                    google.maps.event.clearListeners(map, 'mouseup');
                    
                    //console.log("Path of the polyline " + theArrayofLatLng[0].lat() +" "  + theArrayofLatLng[1].lng());
                    
                });
            });
           
               
}

function restorePolygon(){
   var p = localStorage.getItem('latlngArray');
   var c = JSON.parse(p);
   //console.log(c[0].k);
   var path=[];
   for(var i = 0; i < c.length; i++){
        path[i] = new google.maps.LatLng(parseFloat(c[i].k), parseFloat(c[i].D));
   }
    var polyOptions = {
                        map: map,
                        fillOpacity: 0,
                        strokeColor: '#2691ec',
                        strokeOpacity: 0.5,
                        strokeWeight: 2,
                        clickable: false,
                        zIndex: 1,
                        suppressUndo: true,
                        //path:theArrayofLatLng,
                        path: path,
                        editable: true
                    }
                    // draw polygon
                    var poly = new google.maps.Polygon(polyOptions);

}

function clearPolygone(){
     //FreeHandPoly.setMap(null);
     initializeErase();
     //utilinit();
}
function saveLatLngDrawingMode(){
    //theArrayofLatLng.push({k: -2 ,D: -2});
    console.log('The = '+theArrayofLatLng.getArray());
    var Type = "polygon"; 
    if(theArrayofLatLng == "")  {
        saveLatLngCheck();
    } else {
        saveLatLng(Type, theArrayofLatLng.getArray());
    }
}


/* Stack-based Douglas Peucker line simplification routine
   returned is a reduced GLatLng array
   After code by  Dr. Gary J. Robinson,
   Environmental Systems Science Centre,
   University of Reading, Reading, UK
*/
function simplifyPolygon(source, kink)
/* source[] Input coordinates in GLatLngs   */
/* kink in metres, kinks above this depth kept  */
/* kink depth is the height of the triangle abc where a-b and b-c are two consecutive line segments */
{
    var n_source, n_stack, n_dest, start, end, i, sig;
    var dev_sqr, max_dev_sqr, band_sqr;
    var x12, y12, d12, x13, y13, d13, x23, y23, d23;
    var F = ((Math.PI / 180.0) * 0.5 );
    var index = new Array(); /* aray of indexes of source points to include in the reduced line */
    var sig_start = new Array(); /* indices of start & end of working section */
    var sig_end = new Array();

    /* check for simple cases */

    if ( source.length < 3 )
        return(source);    /* one or two points */

    /* more complex case. initialize stack */

    n_source = source.length;
    band_sqr = kink * 360.0 / (2.0 * Math.PI * 6378137.0);  /* Now in degrees */
    band_sqr *= band_sqr;
    n_dest = 0;
    sig_start[0] = 0;
    sig_end[0] = n_source-1;
    n_stack = 1;

    /* while the stack is not empty  ... */
    while ( n_stack > 0 ){

        /* ... pop the top-most entries off the stacks */

        start = sig_start[n_stack-1];
        end = sig_end[n_stack-1];
        n_stack--;

        if ( (end - start) > 1 ){  /* any intermediate points ? */

                /* ... yes, so find most deviant intermediate point to
                       either side of line joining start & end points */

            x12 = (source[end].lng() - source[start].lng());
            y12 = (source[end].lat() - source[start].lat());
            if (Math.abs(x12) > 180.0)
                x12 = 360.0 - Math.abs(x12);
            x12 *= Math.cos(F * (source[end].lat() + source[start].lat()));/* use avg lat to reduce lng */
            d12 = (x12*x12) + (y12*y12);

            for ( i = start + 1, sig = start, max_dev_sqr = -1.0; i < end; i++ ){

                x13 = (source[i].lng() - source[start].lng());
                y13 = (source[i].lat() - source[start].lat());
                if (Math.abs(x13) > 180.0)
                    x13 = 360.0 - Math.abs(x13);
                x13 *= Math.cos (F * (source[i].lat() + source[start].lat()));
                d13 = (x13*x13) + (y13*y13);

                x23 = (source[i].lng() - source[end].lng());
                y23 = (source[i].lat() - source[end].lat());
                if (Math.abs(x23) > 180.0)
                    x23 = 360.0 - Math.abs(x23);
                x23 *= Math.cos(F * (source[i].lat() + source[end].lat()));
                d23 = (x23*x23) + (y23*y23);

                if ( d13 >= ( d12 + d23 ) )
                    dev_sqr = d23;
                else if ( d23 >= ( d12 + d13 ) )
                    dev_sqr = d13;
                else
                    dev_sqr = (x13 * y12 - y13 * x12) * (x13 * y12 - y13 * x12) / d12;// solve triangle

                if ( dev_sqr > max_dev_sqr  ){
                    sig = i;
                    max_dev_sqr = dev_sqr;
                }
            }

            if ( max_dev_sqr < band_sqr ){   /* is there a sig. intermediate point ? */
                /* ... no, so transfer current start point */
                index[n_dest] = start;
                n_dest++;
            }
            else{
                /* ... yes, so push two sub-sections on stack for further processing */
                n_stack++;
                sig_start[n_stack-1] = sig;
                sig_end[n_stack-1] = end;
                n_stack++;
                sig_start[n_stack-1] = start;
                sig_end[n_stack-1] = sig;
            }
        }
        else{
                /* ... no intermediate points, so transfer current start point */
                index[n_dest] = start;
                n_dest++;
        }
    }

    /* transfer last point */
    index[n_dest] = n_source-1;
    n_dest++;

    /* make return array */
    var r = new Array();
    for(var i=0; i < n_dest; i++)
        r.push(source[index[i]]);
    return r;

}
