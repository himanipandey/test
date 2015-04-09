

<script type="text/javascript" src="js/jquery.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script language="javascript">
    function GetXmlHttpObject()
    {
        var xmlHttp = null;
        try
        {
            // Firefox, Opera 8.0+, Safari
            xmlHttp = new XMLHttpRequest();
        }
        catch (e)
        {
            //Internet Explorer
            try
            {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e)
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
        return xmlHttp;
    }
    var idfordiv = 0;
    function statuschange(projectId)
    {
        idfordiv = projectId;
        xmlHttp = GetXmlHttpObject()
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request")
            return
        }
        var url = "RefreshBanStat.php?projectId=" + projectId;
        xmlHttp.onreadystatechange = stateChanged
        xmlHttp.open("GET", url, true)
        xmlHttp.send(null)
    }
    function stateChanged()
    {
        if (xmlHttp.readyState == 4)
        {
            document.getElementById('statusRefresh' + idfordiv).innerHTML = xmlHttp.responseText;
        }
    }
    function update_locality(ctid)
    {
        $("#localitySelectText").val('');
        xmlHttpLoc = GetXmlHttpObject()
        var url = "Refreshlocality.php?ctid=" + ctid;
        xmlHttpLoc.onreadystatechange = stateChanged
        xmlHttpLoc.open("GET", url, true)
        xmlHttpLoc.send(null)
    }
    function stateChanged()
    {
        if (xmlHttpLoc.readyState == 4)
        {
            //alert(xmlHttpLoc.responseText+"here");
            document.getElementById("LocalityList").innerHTML = xmlHttpLoc.responseText;
        }
    }

    function validation() {
        if ($('#city').val() == "") {
            alert("Please select the city");
            return false;
        }
        if ($('#locality').val() == "") {
            alert("Please select the Locality");
            return false;
        }
        if ($('#projectId').val().trim() == "") {
            alert("Please enter the project ID");
            return false;
        }

        return true;
    }
    function localitySelect(loclitySelectVal) {
        $("#localitySelectText").val(loclitySelectVal);
    }
    $(function () {
        $("#localitySelectText").val();
        localitySelect({$locality});

    });

</script>

</TD>
</TR>
<TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
                <TR>
                    <TD width=224 height=25>&nbsp;</TD>
                    <TD width=10>&nbsp;</TD>
                    <TD width=866>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
                        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                    </TD>
                    <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
                    <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>

                        {if $mapVarifyAuth == true}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Map Location Varification Tool<findOTP/TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

                                            <div id='create_agent' align="left">
                                                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                                                    <form method = "get" action = "" onsubmit = "return validation();">
                                                        <tr>
                                                            <td height="25" align="center" colspan= "2">
                                                                <span>
                                                                    <font color = "red">{if $errorMsg} {$errorMsg} {/if}</font>
                                                                </span>                                                                
                                                            </td>
                                                        </tr>                                                    
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;" width='35%'><b>City:</b></td>
                                                            <td align="left" style = "padding-left:20px;" width='65%'>
                                                                <select name = 'city' id = "city" onchange = "update_locality(this.value);">
                                                                    <option value = "">Select City</option>
                                                                    {foreach from = $citylist key= key item = val}

                                                                        <option value = "{$key}" {if $city == $key} selected  {else}{/if}>{$val}</option>
                                                                    {/foreach}
                                                                    <option value = "othercities" {if $city == "othercities"} selected  {else}{/if}>Other cities</option>
                                                                </select>
                                                            </td>

                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;"><b>Locality:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <span id = "LocalityList">
                                                                    <select name = 'locality' id = "locality" onchange="localitySelect(this.value);">
                                                                        <option value = "">Select Locality</option>
                                                                        {foreach from = $getLocality item = value}
                                                                            <option value = "{$value->locality_id}" 
                                                                                    {if $locality == $value->locality_id} selected {/if} >{if $city == "othercities"}{$value->cityname} - {/if}{$value->label}</option>
                                                                        {/foreach}
                                                                    </select>
                                                                </span>
                                                            </td>
                                                        <input id="localitySelectText" type="hidden" name="locality" />
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;"><b>Project Id:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <input type = "text" name = "projectId" id = "projectId" value = "{$projectId}">
                                                                <span>
                                                                    <font color = "green">{if $project_name} {$project_name} {/if}</font>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
                                                                <input type = "submit" value = "Generate Map" name = "generateMap" style="border:1px solid #c2c2c2;height:30px;width:120px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                    </form>
                                                </TABLE> 
                                                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                                                    <tr>
                                                        <td>
                                                            <div id="map" style="width: 950px; height: 600px;{if $errorMsg}display:none;{/if}">
                                                                <script>
                                                                    // Define your locations: HTML content for the info window, latitude, longitude
                                                                    var locations = [
                                                                        ["<h4>{$project_name}</h4>", "{$projectLatitude}", "{$projectLongitude}"],
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
                                                                        zoom: 5,
                                                                        center: new google.maps.LatLng("{$localityLatitude}", "{$localityLongitude}"),
                                                                        mapTypeId: google.maps.MapTypeId.TERRAIN
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

                                                                    // Define the LatLng coordinates for the polygon's path.
                                                                    var triangleCoords = [
                                                                        new google.maps.LatLng("{$localityLatMax}", "{$localityLongMin}"),
                                                                        new google.maps.LatLng("{$localityLatMin}", "{$localityLongMin}"),
                                                                        new google.maps.LatLng("{$localityLatMin}", "{$localityLongMax}"),
                                                                        new google.maps.LatLng("{$localityLatMax}", "{$localityLongMax}"),
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
                                                                </script>


                                                            </div>
                                                        </td>
                                                    </tr>

                                                </table>                                            
                                            </div> 
                                        {/if}

                                    </TD>
                                </TR>
                            </TBODY></TABLE>
                    </TD>

                </TR>
            </TBODY></TABLE>
    </TD>
</TR>


