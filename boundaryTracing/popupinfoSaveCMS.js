function saveLatLng(pts){
  console.log("POP");
   latlngArray = pts;
   //console.log(pts);
   var count = 0;
   var lang = 0.0;
   var longi = 0.0;
   for (var i = 0; i < pts.length; i++)  {
      lang = lang + pts[i].k;
      longi = longi + pts[i].D;
      count++;
   }
   if (count != 0) {
    lang = lang / count;
    longi = longi / count;
   }
   console.log("Avg = ",lang);
   console.log("Avg = ", longi);
   saveCms = JSON.stringify(latlngArray);
   //console.log(saveCms);
    var t = document;
    var theNewScript = t.createElement("script");
    theNewScript.type = "text/javascript";
    theNewScript.src = "http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js";
    document.getElementsByTagName("head")[0].appendChild(theNewScript);

    var waitForLoad = function () {
        if (typeof jQuery != "undefined") {
            console.log('define hrfejjhfek');
            $.ajax({
              url: '/saveNearPlacePriority.php',          
              type: "POST",
              data: {latitude: lang, longitude: longi, boundary: saveCms, task: 'MapdataSendCMs'},

              success:function(msg){
                console.log("success !!");  
              },
            });
        } else {
            console.log('undefine');
            window.setTimeout(waitForLoad, 1000);
        }
    };
    window.setTimeout(waitForLoad, 1);
    

   /*localStorage.setItem('latlngArray', JSON.stringify(latlngArray));
     
   localStorage["latlngArray"] = JSON.stringify(latlngArray);
   var storedNames = JSON.parse(localStorage["latlngArray"]);
   /*console.log( "localStorage  "+storedNames[0].lat; */
}

