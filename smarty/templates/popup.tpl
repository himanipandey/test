<!DOCTYPE html>
<html>
  <head>
    <title>Drawing tools</title>
  </head>
  <body>
  <script src="http://cms.localhost.com/boundaryTracing/jquery-1.8.3.min.js"></script>  
  <script src="freeHandDrawingLogic.js" > </script>
  <script type="text/javascript"> 
      function savePopUpInfo(){
          var placeName = document.getElementById("Place").value;
          var cityName = document.getElementById("city").value;
          //alert(placeName);

          var e1 = document.getElementById("subtypes");
          var subType = e1.options[e1.selectedIndex].text;    


          var e2 = document.getElementById("landmarktype");
          var landmarkType = e2.options[e2.selectedIndex].text;
          
          var waitForLoad = function () {
             if (typeof jQuery != "undefined") {
                  $.ajax({
                    url: '/saveNearPlacePriority.php',          
                    type: "POST",
                    data: {placeName : placeName, cityName:cityName, landType:landType, task: 'PopUpdataSendCMs'},

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
          window.setTimeout(waitForLoad, 1);
      }

  </script>

  <div> 
        Place Name: <input id="Place" type="text" name="Place" style = "width:70px"> <br>
        City Name : <input id="city" type="text" name="city" style = "width:70px"> <br>
       <select id="landmarktype" name="landmarktype" style="width:150px;" >
          <option value=''> Select Landmark </option>
          <option value="0">Roads</option>
          <option value="1">Water Body</option>
          <option value="2">Rail Road</option>
          <option value="3">Land Usage</option>
       </select> 
        
        <select id="subtypes" name="subtypes" >
            <option value='0'>Major </option>
            <option value='1'>Minor </option>
            <option value='2'>River </option>
            <option value='3'>Canal </option>
            <option value='4'>Drain </option>
            <option value='5'>Railway Line </option>
            <option value='6'>Railway Station </option>
            <option value='7'>Metro Line </option>
            <option value='8'>Metro Station </option>
            <option value='9'>Industrial </option>
            <option value='10'>Commercial </option>
            <option value='11'>Agricultural </option>
            <option value='12'>Residential Land </option>
            <option value='13'>Green Belt</option>
        </select>
        <!--Land Type : <input id="land" type="text" name="land" style = "width:70px"> <br> -->
        <input type="button" value="go" style = "left-padding:140px" onclick="savePopUpInfo()">
  </div>

  </body>
</html>